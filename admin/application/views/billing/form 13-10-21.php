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
		.custom-label{
			font-weight: 600 !important;
		    letter-spacing: 0.5px !important;
		    text-transform: uppercase !important;
		}
		.payment_blk .form-control{
			width: 250px;
		} 
		.gift_details {
          color: #FF0000;
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
				<?php 
					$metal_rates = $this->admin_settings_model->metal_ratesDB("last");
				?>
				<form id="bill_pay">
				<div class="col-md-12"> 
					<!-- Lot Details Start Here -->
					<div class="row"> 
						<div class="col-md-2">	
					    		<label>Cost Centre <span class="error">*</span></label>
						 			<div class="form-group"> 
									<?php if($this->session->userdata('branchWiseLogin') == 1 && $this->session->userdata('id_branch') == "") { ?>
										<select name="billing[id_branch]" id="id_branch" class="form-control" required>
											<?php echo $this->ret_billing_model->get_currentBranches($billing['id_branch']); ?>
										</select>
									<?php }else { ?>
							 			<label><?php echo $this->ret_billing_model->get_currentBranchName($type == 'add' ? $this->session->userdata('id_branch') : $billing['id_branch']); ?> </label>
										<input type="hidden" id="id_branch" name="billing[id_branch]" value="<?php echo $type == 'add' ? $this->session->userdata('id_branch') : $billing['id_branch'];?>" />
									<?php } ?>
									</div>
									<input type="hidden" id="enable_gift_voucher" value="">
									<p id="branchAlert" class="error" align="left"></p>
				 		</div> 
				 		<div class="col-md-2">
				 		        <label>Billing To</label>
				 		        <div class="form-group">
				 		        <input type="radio"  name="billing[billing_for]" value="1" checked> Customer </label>&nbsp;
				 		        <input type="radio"  name="billing[billing_for]" value="2" > Company </label>&nbsp;
				 		    </div>
				 		</div>
			    		<div class="col-md-2">
					    			<label>Customer <span class="error">*</span></label>
						 			<div class="form-group">
							 			<div class="input-group">
							 			   
											<input class="form-control" id="bill_cus_name" name="billing[cus_name]" type="text"  placeholder="Customer Name / Mobile"  value="<?php echo set_value('billing[cus_name]',isset($billing['cus_name'])?$billing['cus_name']:NULL); ?>" required autocomplete="off"/>
											<input class="form-control" id="bill_cus_id" name="billing[bill_cus_id]" type="hidden" value="<?php echo set_value('billing[bill_cus_id]',$billing['bill_cus_id']); ?>"/>

											<input type="hidden" id="validity_days" name="billing[validity_days]" value="">
											<input type="hidden" id="validate_date" name="billing[validate_date]" value="">
											<input type="hidden" id="id_set_gift_voucher" name="billing[id_set_gift_voucher]" value="">
											<input type="hidden" id="gift_type" name="billing[gift_type]" value="">
											<input type="hidden" id="utilize_for" name="billing[utilize_for]" value="">
											<input type="hidden" id="issue_for" name="billing[issue_for]" value="">
											<input type="hidden" id="bill_value" value="">
											<input type="hidden" id="credit_value" value="">
											<input type="hidden" id="calc_type" value="">
											
											<input type="hidden" id="goldrate_22ct" name="billing[goldrate_22ct]" value="">
											<input type="hidden" id="silverrate_1gm" name="billing[silverrate_1gm]" value="">
											<input type="hidden" id="goldrate_18ct" name="billing[goldrate_18ct]" value="">
											<input type="hidden" id="goldrate_24ct" name="billing[goldrate_24ct]" value="">
											
											<input id="is_counter_req" type="hidden" value="<?php echo set_value('billing[is_counter_req]',$billing['is_counter_req']); ?>" />
											<input id="counter_id" type="hidden" value="<?php echo $this->session->userdata('counter_id'); ?>" />
											
											<input id="is_tcs_required"  type="hidden" value="<?php echo set_value('billing[is_tcs_required]',$billing['is_tcs_required']); ?>" />
											<input id="tcs_tax_per"  name="billing[tcs_tax_per]" type="hidden" value="<?php echo set_value('billing[tcs_tax_per]',$billing['tcs_tax_per']); ?>" />
											<input id="tcs_min_bill_amt"  type="hidden" value="<?php echo set_value('billing[tcs_min_bill_amt]',$billing['tcs_min_bill_amt']); ?>" />
											<input id="tot_purchase_amt"  type="hidden" value="" />
											<input type="hidden" id="tcs_total_tax_amount" name="billing[tcs_tax_amt]" value="">
											
											<label style="display:none;" class="per-grm-sale-value"> </label>
											<label style="display:none;" class="silver_per-grm-sale-value"> </label>
											<input type="hidden" id="cus_state" name="">
											<input type="hidden" id="cmp_state" name="">
											<input id="bill_id" name="billing[bill_id]" type="hidden" value="<?php echo set_value('billing[bill_id]',$billing['bill_id']); ?>" />
											<span id="customerAlert"></span>
										</div> 
									</div>
									<p id=cus_info></p> 
									<p id="cusAlert" class="error" align="left"></p>
				 		</div>
				 		
				 		<div class="col-md-2"  id="emp_user" style="display:none;">
				 		    <label>Select Employee</label>
				 		    <div class="form-group">
				 		        	<div class="input-group"> 
				 		            	<input type="text" class="form-control" id="bill_emp_name" placeholder="Employee Name / Mobile">
				 		                <input type="hidden" id="id_cmp_emp" name="billing[id_cmp_emp]">
                                        <span class="input-group-btn">
                                        <button type="button" id="add_cmp_emp" class="btn btn-default btn-flat"><i class="fa fa-plus"></i></button>
                                        </span>
				 		        	</div>
				 		        
				 		    </div>
				 		</div>

						<div class="col-sm-2">	
						    <label></label>
						     <div class="form-group">
						         	<a class="btn btn-success pull-right" id="add_new_customer" href="#" data-toggle="modal" data-target="#confirm-add" ><i class="fa fa-user-plus"></i>New Customer</a> 
						      </div>
						</div>
				 	</div> 
				 	<p></p>
				 	<p></p>
					<div class="row"> <!--stickyBlk-->
						<div class="col-sm-12">
							<div class="box box-solid">
					            <!--<div class="box-header">
					              <h3 class="box-title">Bill Type</h3>
					            </div>-->
					            <div class="box-body" align="center">
					            	<label class="pull-left">Bill Type <span class="error">*</span></label>
					            	<div class="row">
					            		<div class="col-sm-12">
							              <a class="btn btn-app btn-flat margin bg-green">
							                <!--<span class="badge bg-yellow">3</span>-->
							                <!--<i class="fa fa-bullhorn"></i> -->
							                <input type="radio" id="bill_type_sales" name="billing[bill_type]" value="1" <?php echo $billing['bill_type'] == 1? 'checked':'' ?>> <label for="bill_type_sales" class="custom-label"> SALES </label>
							              </a>
							              <a class="btn btn-app btn-flat margin bg-teal"> 
							                <!--<i class="fa fa-bullhorn"></i> -->
							                <input type="radio" id="bill_type_sales" name="billing[bill_type]" value="2"  <?php echo $billing['bill_type'] == 2? 'checked':'' ?>> <label for="bill_type_salePurch" class="custom-label"> Sales & Purchase</label>
							              </a>
							              <a class="btn btn-app btn-flat margin bg-olive"> 
							                <!--<i class="fa fa-bullhorn"></i> -->
							                <input type="radio" id="bill_type_sales" name="billing[bill_type]" value="3"  <?php echo $billing['bill_type'] == 3? 'checked':'' ?>> <label for="bill_type_saleRet" class="custom-label"> Sales,Purchase & Return</label>
							              </a>
							              <a class="btn btn-app btn-flat margin bg-yellow"> 
							                <!--<i class="fa fa-barcode"></i> -->
							                <input type="radio" id="bill_type_sales" name="billing[bill_type]" value="4"  <?php echo $billing['bill_type'] == 4? 'checked':'' ?>> <label for="bill_type_purchase" class="custom-label"> Purchase </label>
							              </a>
							              <a class="btn btn-app btn-flat margin bg-purple"> 
							                <!--<i class="fa fa-users"></i> -->
							                <input type="radio" id="bill_type_sales"name="billing[bill_type]" value="5"  <?php echo $billing['bill_type'] == 5? 'checked':'' ?>/> <label for="bill_type_order_advance" class="custom-label"> Order Advance </label>
							              </a> 
							              <!--<a class="btn btn-app btn-flat margin bg-orange"> 
							                <input type="radio" id="bill_type_sales" name="billing[bill_type]" value="6"  <?php echo $billing['bill_type'] == 6? 'checked':'' ?>> <label for="bill_type_advance" class="custom-label"> Advance </label>
							              </a>-->
							              <a class="btn btn-app btn-flat margin bg-red"> 
							                <!--<i class="fa fa-bullhorn"></i> -->
							                <input type="radio" id="bill_type_sales" name="billing[bill_type]" value="7" <?php echo $billing['bill_type'] == 7? 'checked':'' ?>> <label for="bill_type_sales_return" class="custom-label"> Sales Return </label>
							              </a>
							               <a class="btn btn-app btn-flat margin bg-maroon"> 
							                <!--<i class="fa fa-bullhorn"></i> -->
							                <input type="radio" id="bill_type_sales" name="billing[bill_type]" value="8" <?php echo $billing['bill_type'] == 8? 'checked':'' ?>> <label for="bill_type_credit_bill" class="custom-label"> Credit Collection </label>
							              </a>
							               <a class="btn btn-app btn-flat margin bg-maroon"> 
							                <!--<i class="fa fa-bullhorn"></i> -->
							                <input type="radio" id="bill_type_sales" name="billing[bill_type]" value="9" <?php echo $billing['bill_type'] == 9? 'checked':'' ?>> <label for="bill_type_credit_bill" class="custom-label">Order Delivery</label>
							              </a>
							              <a class="btn btn-app btn-flat margin bg-maroon"> 
							                <!--<i class="fa fa-bullhorn"></i> -->
							                <input type="radio" id="bill_type_sales" name="billing[bill_type]" value="10" <?php echo $billing['bill_type'] == 10? 'checked':'' ?>> <label for="bill_type_credit_bill" class="custom-label">Chit Pre Close</label>
							              </a>
							            </div>
					            	</div>
					            	<p class="help-block"></p>
					            	<hr />
									<div class="row">			 			    	
							    		<div class="col-sm-3 search_esti"> 
											<div class="row">				    	
									    		<div class="col-sm-4">
									    			<label>EstNo. </label>
										 		</div>
										 		<div class="col-sm-8">
										 			<div class="form-group" > 
											 			<div class="input-group" > 
															<input class="form-control" id="filter_est_no" name="filter_est_no" type="text" placeholder="Esti No." value="" />
															<span class="input-group-btn">
										                      <button type="button" id="search_est_no" class="btn btn-default btn-flat"><i class="fa fa-search"></i></button>
										                    </span>
														</div>
														<p id="searchEstiAlert" class="error" align="left"></p>
													</div>
										 		</div>
										 	</div>
										 </div>
										 <div class="col-sm-3 search_tag">
										 	<div class="row">				    	
									    		<div class="col-sm-4">
									    			<label>TagNo. </label>
										 		</div>
										 		<div class="col-sm-8">
										 			<div class="form-group" > 
											 			<div class="input-group" > 
															<input class="form-control" id="filter_tag_no" name="filter_tag_no" type="text" placeholder="Tag No." value="" />
															<span class="input-group-btn">
										                      <button type="button" id="search_tag_no" class="btn btn-default btn-flat"><i class="fa fa-search"></i></button>
										                    </span>
														</div>
														<p id="searchTagAlert" class="error" align="left"></p>
													</div>
										 		</div>
										 	</div>
										</div> 
										<div class="col-sm-3 search_order"> 
											<div class="row">				    	
									    		<div class="col-sm-4">
									    			<label>OrderNo. </label>
										 		</div>
										 		<div class="col-sm-8">
										 			<div class="form-group" > 
											 			<div class="input-group" > 
											 			    <span class="input-group-btn">
											 			        <select class="form-control" id="order_fin_year_select" style="width:100px;">
											 			            <?php 
											 			            foreach($billing['financial_year'] as $fin_year)
											 			            {?>
											 			                <option value=<?php echo $fin_year['fin_year_code'];?> <?php echo ($fin_year['fin_status']==1 ?'selected' :'')  ?> ><?php echo $fin_year['fin_year_name'];?></option>
											 			            <?php }
											 			            ?>
											 			        </select>
											 			    </span>
											 			    
															<input class="form-control" id="filter_order_no" name="billing[filter_order_no]" type="text" placeholder="Order No." value="" style="width:100px;" />
															<span class="input-group-btn">
										                      <button type="button" id="search_order_no" class="btn btn-default btn-flat"><i class="fa fa-search"></i></button>
										                    </span>
														</div>
														<p id="searchOrderNoAlert" class="error" align="left"></p>
													</div>
										 		</div>
										 	</div>
										 </div>
										 <div class="col-sm-3 search_bill">
										 	<div class="row">				    	
									    		<div class="col-sm-4">
									    			<label>BillNo. </label>
										 		</div>
										 		<div class="col-sm-8">
										 			<div class="form-group" > 
											 			<div class="input-group" > 
											 			    <span class="input-group-btn">
											 			        <select class="form-control" id="bill_fin_year_select" style="width:100px;">
											 			            <?php 
											 			            foreach($billing['financial_year'] as $fin_year)
											 			            {?>
											 			                <option value=<?php echo $fin_year['fin_year_code'];?> <?php echo ($fin_year['fin_status']==1 ?'selected' :'')  ?> ><?php echo $fin_year['fin_year_name'];?></option>
											 			            <?php }
											 			            ?>
											 			        </select>
											 			    </span>
															<input class="form-control" id="filter_bill_no" name="filter_bill_no" type="text" placeholder="Bill No." value="" style="width:100px;"/>
															<input type="hidden" id="ret_bill_id" name="billing[ret_bill_id]"  value="<?php echo set_value('billing[cus_name]',isset($billing['ref_bill_id'])?$billing['ref_bill_id']:NULL); ?>"/>
															<span class="input-group-btn">
										                      <button type="button" id="search_bill_no" class="btn btn-default btn-flat"><i class="fa fa-search"></i></button>
										                    </span>
														</div>
														<p id="searchBillAlert" class="error" align="left"></p>
													</div>
										 		</div>
										 	</div>
										</div>
									<!--	<div class="col-sm-3 date_filter">
										 	<div class="row">				    	
									    		<div class="col-sm-4">
									    			<label>Choose Bill Date</label>
										 		</div>
										 		<div class="col-sm-8">
										 			<div class="form-group">
														   <a class="btn btn-default btn_date_range" id="payment-dt-btn"> 
															<span  style="display:none;" id="payment_list1"></span>
															<span  style="display:none;" id="payment_list2"></span>
															<i class="fa fa-calendar"></i> Date range picker
															<i class="fa fa-caret-down"></i>
															</a>
													</div>	
										 		</div>
										 	</div>
										</div>-->
									</div>
								</div>
					            <!-- /.box-body -->
					        </div>
							<!--<div class="form-group"> 
								<div class="col-md-10">
									<label for="Offer">Bill Type :</label>
									<div class="row">
										<div class="col-md-6">
											<input type="checkbox" id="bill_type_sales" name="sales" value="1" > <label for="bill_type_sales">Sales </label>
										</div>
										<div class="col-md-6">
											<input type="checkbox" id="bill_type_purchase" name="purchase" value="1"> <label for="bill_type_purchase">Purchase </label>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<input type="checkbox" id="select_bill_type_order_advanceamt_details" name="orderadvanceamt" value="1" <?php echo !empty($est_other_item['stone_details']) ? 'checked' : '' ;?>> <label for="select_bill_type_order_advanceamt_details">Order  Advance Amount </label>
										</div>
										<div class="col-md-6">
											<input type="checkbox" id="select_advance_oldmatel_details" name="oldmateladvance" value="1" > <label for="select_advance_oldmatel_details">Order Advance Metal </label>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<input type="checkbox" id="bill_type_advance" name="advancebill" value="1" > <label for="bill_type_advance">Advance Bill </label>
										</div>
										<div class="col-md-6">
											<input type="checkbox" id="bill_type_sales_return" name="salesreturn" value="1"> <label for="bill_type_sales_return">Sales Return </label>
										</div>
									</div>   
							   </div>
							</div>-->
						</div>
					</div> 
					<div class="box box-default sale_details">
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
											<th>Wast Wt(g)</th>   
											<th>MC</th>   
											<th>Discount</th>   
											<th>Taxable Amt</th>
											<th>Tax(%)</th>
											<th>Tax</th>
											<th>Stone</th>
											<th>Amount</th>
											<th>Partly</th>
											<th>Tag No</th>
											<th>Order No</th>
											<th>Est No</th>
											<!--<th>Advance Paid</th> -->
											<th>Action</th>
										  </tr>
										</thead> 
										<tbody>
											<?php if($this->uri->segment(3) == 'edit'){
										foreach($est_other_item['item_details'] as $ikey => $ival){
									$other_stone_price=0;
									$other_stone_wt=0;
									$stone_data=array();
									foreach ($ival['stone_details'] as $data) {
									$other_stone_price	+=	$data['price'];
									$other_stone_wt	+=	$data['wt'];
									$stone_data[]=array(
									'bill_item_stone_id'=>$data['bill_item_stone_id'],
									'stone_id'			=>$data['stone_id'],
									'stone_pcs'			=>$data['pieces'],
									'stone_wt'			=>$data['wt'],
									'stone_price'		=>$data['price']
									);
									}
									$stone_details=json_encode($stone_data);
										echo '<tr id="'.$ikey.'">
										<td>
										<span>'.$ival['hsn_code'].'</span><input type="hidden" class="bill_det_id" name="sale[bill_det_id][]" value="'.$ival['bill_det_id'].'" /><input type="hidden" class="sale_pro_hsn" name="sale[hsn][]" value="'.$ival['hsn_code'].'" /><input type="hidden" class="sale_type" name="sale[sourcetype][]" value="1" /><input type="hidden" class="sale_item_type" name="sale[itemtype][]" value="'.$ival['item_type'].'" /><input type="hidden" class="is_est_details" value="1" name="sale[is_est_details][]" /><input type="hidden" class="est_itm_id" name="sale[est_itm_id][]" value="'.$ival['est_item_id'].'" /><input type="hidden" class="sale_cal_type" name="sale[calltype][]" value="'.$ival['calculation_based_on'].'" /><input type="hidden" class="sale_metal_type" value="'.$ival['metal_type'].'" /><input type="hidden" class="sale_purity" value="'.$ival['purid'].'"  name="sale[purity][]" /><input type="hidden" class="sale_size" value="'.$ival['size'].'"  name="sale[size][]" /><input type="hidden" class="sale_uom" value="'.$ival['uom'].'"  name="sale[uom][]" /><input type="hidden" class="total_tax" name="sale[total_tax][]"><input type="hidden" class="is_partial"  name="sale[is_partial][]" value="'.$ival['is_partial'].'"/>
										<input type="hidden" class="total_tax" name="sale[item_total_tax][]">
										</td>
										<td>
											<span>'.$ival['product_name'].'</span><input class="sale_product_id" type="hidden" name="sale[product][]" value="'.$ival['product_id'].'"/>
										</td>
										<td><span>'.$ival['design_code'].'</span><input type="hidden" class="sale_design_id" name="sale[design][]" value="'.$ival['design_id'].'" />
										</td>
										<td><span>'.$ival['piece'].'</span><input type="hidden" class="sale_pcs" name="sale[pcs][]" value="'.$ival['piece'].'"/>
										<td><span>'.$ival['gross_wt'].'</span><input type="hidden" class="bill_gross_val" name="sale[gross][]" value="'.$ival['gross_wt'].'" /></td>
										</td>
										<td><span>'.$ival['less_wt'].'</span><input type="hidden" class="bill_less_val" name="sale[less][]" value="'.$ival['less_wt'].'" /></td>
									    <td><span>'.$ival['net_wt'].'</span><input type="hidden" class="bill_net_val" name="sale[net][]" value="'.$ival['net_wt'].'" /></td>
									    <td><span>'.$ival['wastage_percent'].'</span><input type="hidden" class="bill_wastage" name="sale[wastage][]" value="'.$ival['wastage_percent'].'" />
									     </td>
								        <td><span>'.($ival['mc_type']==1 ? 'Per Gram':'Per Piece').'</span><input type="hidden" class="bill_mctype" name="sale[bill_mctype][]" value="'.$ival['mc_type'].'" /><input type="hidden" class="bill_mc" name="sale[mc][]" value="'.$ival['mc_value'].'" />
								        </td>
								        <td><input type="number" class="bill_discount" name="sale[discount][]" value="'.$ival['bill_discount'].'" step="any" />
								        </td>
								        <td></td>
										<td><span>'.$ival['tgrp_name'].'</span>
											<input type="hidden" class="sale_tax_group" name="sale[taxgroup][]" value="'.$ival['tax_group_id'].'" />
										</td>
										<td></td>
										<td>'.((sizeof($ival['stone_details'])>0) ?'<a href="#" onClick="create_new_empty_bill_sales_stone_item($(this).closest(\'tr\'));" class="btn btn-success"><i class="fa fa-plus"></i></a>' :'-').'
										<input type="hidden" class="stone_details" value="'.$stone_details.'" name="sale[stone_details][]"/>
									<input type="hidden" class="bill_stone_price" value="'.$other_stone_price.'" />
									<input type="hidden" class="est_old_stone_val" value="" />
									<input type="hidden" class="est_old_dust_val" value="" />
									<input type="hidden" class="bill_material_price" value=""/>
										</td>
									<td><input type="number" class="bill_amount" name="sale[billamount][]" value="'.$ival['item_cost'].'" step="any" readonly /><input type="hidden" class="per_grm_amount" name="sale[per_grm][]" value="" step="any" />
									</td>
									<td>'.($ival['is_partial']==0 ? 'No': 'Yes').'</td>
								    <td>
								    <span>'.$ival['tag_id'].'</span><input type="hidden" class="sale_tag_id" name="sale[tag][]" value="'.$ival['tag_id'].'" />
								    </td>
								    <td>-</td>
									<td>
										<span>'.$ival['est_item_id'].'</span><input type="hidden" class="sale_est_itm_id" name="sale[estid][]" value="'.$ival['est_item_id'].'" />
									</td>
									<td>
										<a href="#" onClick="remove_row($(this).closest(\'tr\'));" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a>
									</td>
										</tr>';
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
					<div class="box box-default order_adv_details">
						<div class="box-header with-border">
						  <h3 class="box-title">Order Details</h3>
						  <div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
						  </div>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="box-body">
								   <div class="table-responsive">
									 <table id="billing_order_adv_details" class="table table-bordered table-striped text-center">
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
											<th>Tax(%)</th>
											<th>Order No</th>
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
					<div class="box box-default purchase_details" >
						<div class="box-header with-border">
						  <h3 class="box-title">Purchase Details</h3>
						  <div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
						  </div>
						</div>
						<div class="box-body">
							<!--<div class="row">
							  <div class="box-tools pull-right">
								<button type="button" id="create_catalog_details" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add</button>
							  </div>
							</div>-->
							<div class="row">
								<div class="box-body">
								   <div class="table-responsive">
									 <table id="purchase_item_details" class="table table-bordered table-striped text-center">
										<thead>
										  <tr>
											<th>Purpose</th>
											<th>Product</th>
											<th>Design</th>
											<th>Pcs</th>   
											<th>G.Wt</th>   
											<th>L.Wt</th>   
											<th>N.Wt</th>   
											<th>Wastage(%)</th>
											<th>Wastage.Wt(%)</th>
											<th>Discount</th>
											<th>Stone</th>
											<th>Amount</th>
											<th>Est No</th>
											<th>Action</th>
										  </tr>
										</thead> 
										<tbody>
											<?php if($this->uri->segment(3) == 'edit'){
									foreach($est_other_item['old_matel_details'] as $ikey => $ival){
									$net_wt=0;
									$other_stone_price=0;
									$other_stone_wt=0;
									$stone_data=array();
									$net_wt=$ival['gross_wt']-($ival['dust_wt']+$ival['stone_wt']);
									foreach ($ival['stone_details'] as $data) {
									$other_stone_price	+=	$data['price'];
									$other_stone_wt	+=	$data['wt'];
									$stone_data[]=array(
									'bill_item_stone_id'=>$data['bill_item_stone_id'],
									'stone_id'			=>$data['stone_id'],
									'stone_pcs'			=>$data['pieces'],
									'stone_wt'			=>$data['wt'],
									'stone_price'		=>$data['price']
									);
									}
									$stone_details=json_encode($stone_data);
									echo '<tr id="'.$ikey.'">
									<td><span>'.($ival['purpose']==1 ? 'Cash' :'Exchange').'</span></td>
									<td><span>'.($ival['metal_type']==1 ? 'Gold':'Silver').'</span>
										<input type="hidden" class="is_est_details" value="1" name="purchase[is_est_details][]" />
										<input type="hidden" class="item_type" name="purchase[itemtype][]" value="'.$ival['item_type'].'" />
									    <input type="hidden" class="pur_metal_type"value="'.$ival['metal_type'].'" name="purchase[metal_type][]"/>
									    <input type="hidden" class="old_metal_sale_id" value="'.$ival['old_metal_sale_id'].'" name="purchase[old_metal_sale_id][]"/>
										</td>
									<td>-</td>
									<td><input type="number" class="pur_pcs" name="purchase[pcs][]" value="1" /></td>
									<td><span>'.$ival['gross_wt'].'</span><input type="hidden" class="pur_gross_val" name="purchase[gross][]" value="'.$ival['gross_wt'].'"/></td>
									<td><span>-</span><input type="hidden" class="pur_less_val" name="purchase[less][]" value="" /></td>
									<td>
										<span>'.$net_wt.'</span>
										<input type="hidden" class="pur_net_val" name="purchase[net][]" value="'.$net_wt.'" />
										<input type="hidden" class="est_old_dust_val" name="purchase[dust_wt][]" value="'.$ival[
											'dust_wt'].'" />
										<input type="hidden" name="purchase[stone_wt][]" class="est_old_stone_val" value="'.$ival['stone_wt'].'"/>
									</td>
									<td><span>'.$ival['wastage_percent'].'</span><input type="hidden" class="pur_wastage" name="purchase[wastage][]" value="'.$ival['wastage_percent'].'" />
									</td>
									<td><input type="number" class="pur_discount" name="purchase[discount][]" value="'.$ival['bill_discount'].'" />
									</td>
									<td>
									<a href="#" onClick="create_new_empty_bill_purchase_stone_item($(this).closest(\'tr\'));" class="btn btn-success"><i class="fa fa-plus"></i></a><input type="hidden" class="stone_details"  name="purchase[stone_details][]" value='.$stone_details.'><input type="hidden" class="other_stone_price" value="'.$other_stone_price.'" /><input type="hidden" class="other_stone_wt" value="'.$other_stone_wt.'" /><input type="hidden" class="bill_material_price" value=""/>
									</td>
									<td><input type="number" class="bill_amount" name="purchase[billamount][]" value="'.$ival['amount'].'" step="any" readonly /><input type="hidden" class="bill_rate_per_grm" name="purchase[rate_per_grm][]" value="'.$ival['rate_per_gram'].'" step="any" readonly /></td>
									<td><span>'.$ival['est_id'].'</span><input type="hidden" class="pur_est_id" name="purchase[estid][]" value="'.$ival['est_id'].'" /></td>
									<td>-</td>
									</tr>';
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
					<div class="box box-default return_details">
						<div class="box-header with-border">
						  <h3 class="box-title">Return Items</h3>
						  <div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
						  </div>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="box-body">
								   <div class="table-responsive">
									 <table id="sale_return_details" class="table table-bordered table-striped text-center">
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
											<th>Sale Discount</th>   
											<th>Taxable Amt</th>
											<th>Tax(%)</th>
											<th>Tax</th>
											<th>Amount</th>
											<th>Discount</th>
											<th>Sale Return Amt</th>
											<th>Partly</th>
											<th>Tag No</th>
											<th>Order No</th>
											<th>Est No</th>
											<th>Action</th>
										  </tr>
										</thead> 
										<tbody>
												<?php if($this->uri->segment(3) == 'edit'){
								foreach($est_other_item['return_details'] as $ikey => $ival){
										echo '<tr>
												<td><span>'.$ival['hsn_code'].'</span
												>
												<input type="hidden" class="bill_det_id" name="sales_return[bill_det_id][]" value="'.$ival['bill_det_id'].'" />
												<input type="hidden" class="sale_pro_hsn" name="sales_return[hsn][]" value="'.$ival['hsn_code'].'"/>
												<input type="hidden" class="sale_type" name="sales_return[sourcetype][]" value="1" /><input type="hidden" class="sale_item_type" name="sales_return[itemtype][]" value="'.$ival['item_type'].'" /><input type="hidden" class="is_est_details" value="1" name="sales_return[is_est_details][]" /><input type="hidden" class="est_itm_id" name="sales_return[est_itm_id][]" value="'.$ival['esti_item_id'].'" /><input type="hidden" class="sale_cal_type" name="sales_return[calltype][]" value="'.$ival['calculation_based_on'].'" /><input type="hidden" class="sale_metal_type" value="" /><input type="hidden" class="sale_purity" value="'.$ival['purname'].'"  name="sales_return[purity][]" /><input type="hidden" class="sale_size" value="'.$ival['size'].'"  name="sales_return[size][]" /><input type="hidden" class="sale_uom" value="'.$ival['uom'].'"  name="sales_return[uom][]" /></td>
												<td><span>'.$ival['product_name'].'</span><input class="sale_product_id" type="hidden" name="sales_return[product]" value="'.$ival['product_id'].'" /></td>
												<td><span>'.$ival['design_code'].'</span><input type="hidden" class="sale_design_id" name="sales_return[design][]" value="'.$ival['design_id'].'" /></td><td><input type="number" class="sale_pcs" name="sales_return[pcs][]" value="'.$ival['piece'].'"  /></td>
												<td><span>'.$ival['gross_wt'].'</span><input type="hidden" class="bill_gross_val" name="sales_return[gross][]" value="'.$ival['gross_wt'].'" /></td>
												<td><span>'.$ival['less_wt'].'</span><input type="hidden" class="bill_less_val" name="sales_return[less][]" value="'.$ival['less_wt'].'" /></td>
												<td><span>'.$ival['net_wt'].'</span><input type="hidden" class="bill_net_val" name="sales_return[net][]" value="'.$ival['net_wt'].'" /></td>
												<td><span>'.$ival['wastage_percent'].'</span><input type="hidden" class="bill_wastage" name="sales_return[wastage][]" value="'.$ival['wastage_percent'].'" /></td>
												<td><span>'.$ival['mc_type'].'</span><input type="hidden" class="bill_mctype" name="sales_return[bill_mctype][]" value="'.$ival['mc_type'].'" /><input type="hidden" class="bill_mc" name="sales_return[mc][]" value="'.$ival['mc_value'].'" /></td>
												<td><input type="hidden" class="bill_discount" name="sales_return[discount][]" value="'.$ival['discount'].'"  />'.$ival['discount'].'</td><td></td>
												<td><span>'.$ival['tgrp_name'].'</span><input type="hidden" class="sale_tax_group" name="sales_return[taxgroup][]" value="'.$ival['tax_group_id'].'" /></td>
												<td><span>'.$ival['item_total_tax'].'</span></td>
												<td><input type="hidden" class="bill_stone_price" value="" /><input type="hidden" class="bill_material_price" value=""/><input type="number" class="bill_amount" name="sales_return[billamount][]" value="'.$ival['item_cost'].'" step="any" readonly style="width: 100px;"/><input type="hidden" class="per_grm_amount" name="sales_return[per_grm][]" value="" step="any" /></td>
												<td><input type="number" class="sale_ret_disc_amt" name="sales_return[sale_ret_disc_amt][]" value="" step="any" style="width: 100px;"/></td>
												<td><input type="number" class="sale_ret_amt" name="sales_return[sale_ret_amt][]" value="'.$ival['return_item_cost'].'" step="any" readonly style="width: 100px;" readonly/></td>
												<td>Yes</td>
												<td><span>'.$ival['tag_id'].'</span><input type="hidden" class="sale_tag_id" name="sales_return[tag][]" value="'.$ival['tag_id'].'" /></td>
												<td>-</td>
												<td><span>'.$ival['esti_id'].'</span><input type="hidden" class="sale_est_itm_id" name="sales_return[estid][]" value="'.$ival['esti_item_id'].'" /></td>
												<td><a href="#" onClick="remove_row($(this).closest(\'tr\'));" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>
												</tr>';
										}
										}?>
										</tbody>
										<tfoot>
											<tr> </tr>
										</tfoot>
									 </table>
								  </div>
								</div> 
							</div> 
						</div>
					</div>
					<!--<div class="box box-default custom_details" >
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
					</div>-->
					<!--<div class="box box-default old_matel_details" <?php echo !empty($est_other_item['old_matel_details']) ? '' : 'style="display:none;"' ;?>>
						<div class="box-header with-border">
						  <h3 class="box-title">Old Metal Details</h3>
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
					</div>-->
					<div class="row">
						<div class="col-md-6">
							<div class="box box-default stone_details" <?php echo !empty($est_other_item['stone_details']) ? '' : 'style="display:none;"' ;?>>
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
					</div>
					<div class="row">
						<div class="col-sm-7">
							<div class="box box-info total_summary_details" style="display: none;">
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
											 <table id="total_summary_details" class="table table-bordered table-striped">
												<thead>
													<tr>
														<th>Weight</th>
														<th>(Grms)</th>
														<th>Amount</th>
														<th>INR</th>
													</tr>
												</thead> 
												<tbody> 
													<tr>
														<td>Sale Weight</td>
														<td><span class="summary_lbl summary_sale_weight"></span></td>
														<td>Taxable Sale Amount</td>
														<td><span class="summary_lbl summary_sale_amt"></span></td>
													</tr>
													<tr>
														<td></td>
														<td></td>
														<td class="text-right">CGST</td>
														<td><span class="summary_lbl sales_cgst"></span>
															<input type="hidden" id="cgst" class="cgst" name="billing[cgst]">
														</td>
													</tr>
													<tr>
														<td></td>
														<td></td>
														<td class="text-right">SGST</td>
														<td><span class="summary_lbl sales_sgst"></span>
														<input type="hidden" id="sgst" class="sgst" name="billing[sgst]">
														</td>
													</tr>
													<tr>
														<td></td>
														<td></td>
														<td class="text-right">IGST</td>
														<td><span class="summary_lbl sales_igst"></span>
														<input type="hidden" id="igst" class="igst" name="billing[igst]">
														</td>
													</tr>
													<tr>
														<td></td>
														<td></td>
														<td>Sale Amount</td>
														<td><span class="summary_lbl sale_amt_with_tax"></span></td>
													</tr>
													<tr>
														<td></td>
														<td></td>
														<td class="text-right">TCS</td>
														<td><span class="summary_lbl tcs_tax_amt"></span></td>
													</tr>
													<tr>
														<td>Purchase Weight</td>
														<td><span class="summary_lbl summary_pur_weight"></span></td>
														<td>Purchase Amount</td>
														<td><span class="summary_lbl summary_pur_amt"></span></td>
													</tr>
													<tr>
														<td>Advance Paid Weight</td>
														<td><span class="summary_lbl summary_adv_paid_wt"></td>
														<td>Advance Paid Amount</td>
														<td><span class="summary_lbl summary_adv_paid_amt"></span></td>
													</tr>  
													<tr>
														<td>Return Weight</td>
														<td><span class="summary_lbl summary_sale_ret_weight"></span></td>
														<td>Return Amount</td>
														<td><span class="summary_lbl summary_sale_ret_amt"></span></td>
													</tr>  
													<tr>
														<td></td>
														<td></td>
														<td>Credit Amount</td>
														<td><span class="form-control summary_credit_amt"></td>
													</tr>
													<tr>
														<td></td>
														<td></td>
														<td class="text-right">Discount</td>
														<td>
														<div class="input-group" id="sale_discount">
															<input type="number" class="form-control summary_discount_amt summary_lbl" id="summary_discount_amt" name="billing[discount]"><input type="hidden" id="total_discount">
															<span class="input-group-btn">
										                      <button type="button" id="disc_apply" class="btn btn-default btn-flat" style="display:none;">Apply</button>
										                    </span>
														</div>
														<div class="input-group" id="credit_discount">
															<input type="number" class="form-control credit_discount_amt" id="credit_discount_amt" name="billing[credit_discount_amt]"><input type="hidden" id="credit_discount_amt_value">
														</div>
														</td>
													</tr>
													
													<tr>
														<td></td>
														<td></td>
														<td class="text-right">Handling Charges</td>
														<td>
														<div class="input-group">
															<input type="number" class="form-control handling_charges summary_lbl" id="handling_charges" name="billing[handling_charges]">
														</div>
														</td>
													</tr>
													
													<tr>
														<td></td>
														<td></td>
														<td class="text-right">Final Price</td>
														<td><input type="number" class="form-control total_cost summary_lbl" id="total_cost" name="billing[total_cost]" value="" required style="width: fit-content;"><input type="hidden" id="total_payment_amount"></td>
													</tr>
													
													
													
													<tr>
														<td></td>
														<td></td>
														<td class="text-right">Round Off</td>
														<td><span class="summary_round_off"></span>
														<input type="hidden" name="billing[round_off]" id="round_off">
														</td>
													</tr>
													<tr>
														<td></td>
														<td></td>
														<td></td>
														<td><p class="error "><span id="paymentAlert"></span></p></td>
													</tr>
													<tr  style="color: #FF0000;font-weight:bold;">
														<td class="" style="font-size:14px;"></td>
														<td></td>
														<td class="text-right gift_row" >Gift Voucher Worth</td>
														<td><span class="summary_gift_voucher"></span>
														<input class="form-control" id="gift_voucher_amt"  name="billing[gift_voucher_amt]" type="hidden" />
														</td>
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
							<div class="box box-info summary_adv_details" style="display:none;">
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
											 <table id="total_summary_details" class="table table-bordered table-striped">
												<thead>
													<tr>
														<th>Balance</th>
														<th></th>
														<th>Received Advance</th>
														<th></th>
														<th>Store As</th>
													</tr>
												</thead> 
												<tbody> 
													<tr>
														<td>Weight (Grams)</td>
														<td><span class="adv_blc_wt"></span></td>
														<td>Weight (Grams)</td>
														<td><span class="adv_rcd_wt"></span>
														<input type="hidden" class="max_wt" name="">
														</td>
														<td>
														    <input type="hidden" name="billing[id_customerorder]" id="id_customerorder">
															<input type="radio" name="billing[pur_store_as]" value="1" checked>Amount&nbsp;&nbsp;
															<input type="radio" name="billing[pur_store_as]" value="2" >Weight
														</td>
													</tr>
													<tr>
														<td>Amount(INR)</td>
														<td><span class="adv_blc_amt"></span></td>
														<td>Amount(INR)</td>
														<td>
															<input type="text" class="adv_amt" name="billing[bill_amount]">
														</td>
														<td>
															<input type="radio"  value="1" name="billing[sale_store_as]" checked>Amount
																&nbsp;&nbsp;
																<input type="radio" value="2"  name="billing[sale_store_as]" >Weight
														</td>
													</tr>
													<tr style="font-weight: bold;">
														<td>Advance Paid</td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
													</tr>
													<tr>
														<td>Weight (Grmas)</td>
														<td><span class="adv_paid_wt"></span></td>
														<td></td>
														<td></td>
														<td></td>
													</tr>
													<tr>
														<td>Amount (INR)</td>
														<td><span class="adv_paid_amt"></span></td>
														<td></td>
														<td></td>
														<td>
															
														</td>
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
						</div> 
						<div class="col-sm-5">
							<div class="box box-info payment_blk">
								<div class="box-header with-border">
								  <h3 class="box-title">Make Payment</h3>
								  <div class="box-tools pull-right">
									<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
								  </div>
								</div>
								<div class="box-body">
									<div class="row">
										<div class="col-sm-11">
											<div class="box-body">
											   <div class="table-responsive">
												 <table id="payment_modes" class="table table-bordered table-striped">
													<thead>
													</thead> 
													<tbody>
														<tr>
															<td class="text-right"><b class="custom-label">Pay</b></td>
															<th class="text-right"><?php echo $this->session->userdata('currency_symbol')?></th>
															<td> 
																<input type="number" class="form-control pay_to_cus" name="billing[pay_to_cus]" value="" required readonly>
															</td>
														</tr>
														<tr>
															<td class="text-right"><b class="custom-label">Received</b></td>
															<th class="text-right"><?php echo $this->session->userdata('currency_symbol')?></th>
															<td> 
																<input type="number" class="form-control receive_amount" name="billing[tot_amt_received]" value="<?php echo set_value('billing[tot_amt_received]',isset($billing['tot_amt_received'])?$billing['pan_no']:0); ?>" >
															</td>
														</tr>														
														<tr>
															<td class="text-right"><b class="custom-label">Calculate From</b></td>
															<th class="text-right"></th>
															<td> 
																<input type="radio" id="rate_calc1" value="1" name="billing[rate_calc]" checked >Gold
																&nbsp;
																<input type="radio" id="rate_calc2" value="2"  name="billing[rate_calc]" >Silver
															</td>
														</tr>
														<tr>
															<td class="text-right"><b class="custom-label">PAN No</b></td>
															<th class="text-right"></th>
															<td> 
															<input type="hidden" id="min_pan_amt" value="<?php echo $billing['min_pan_amt'];?>">
														<input type="hidden" id="is_pan_required" value="<?php echo $billing['is_pan_required'];?>">
															<input type="text" class="form-control pan_no" name="billing[pan_no]" id="pan_no" value="<?php echo set_value('billing[pan_no]',isset($billing['pan_no'])?$billing['pan_no']:NULL); ?>" disabled>
															</td>
														</tr>
														<tr>
															<td class="text-right"><b class="custom-label">Image</b></td>
															<th class="text-right"></th>
															<td> 
															<input type="file" id="pan_images"  multiple disabled>
															<input type="hidden" 
															id="panimg" name="billing[pan_img]">
															<div id="pan_preview" ></div>
															</td>
														</tr>
														<tr>
															<td class="text-right">Is Credit</td>
															<td></td>
															<td> 
																<input type="radio" id="is_credit_no" class="is_credit" name="billing[is_credit]" value="0" <?php echo $billing['is_credit'] == 0 ? 'checked':'' ?>> <label for="is_credit_no">&nbsp;No</label> &nbsp;&nbsp;
																<input type="radio" id="is_credit_yes" class="is_credit" name="billing[is_credit]" value="1" <?php echo $billing['is_credit'] == 1 ? 'checked':'' ?>><label for="is_credit_yes">&nbsp;Yes</label> 
															</td>
														</tr>
														<tr>
															<td class="text-right">Credit Due Date</td>
															<td></td>
													<td> 
														<input class="form-control" id="credit_due_date" data-date-format="dd-mm-yyyy hh:mm:ss" name="billing[credit_due_date]" value="" type="text" placeholder="Credit Due Date" disabled/>
													</td>
														</tr>
														<tr>
															<td class="text-right">Chit Utilization</td>
															<td class="text-right"><?php echo $this->session->userdata('currency_symbol')?></td>
															<td>
																<span id="tot_chit_amt"></span>
																<a class="btn bg-olive btn-xs pull-right" id="chit_util_modal" href="#" data-toggle="modal" data-target="#chit-confirm-add" ><b>+</b></a> 
																<input type="hidden"id="chit_details" name="billing[chit_uti]" value="">
															</td>
														</tr>
														<tr>
															<td class="text-right">Gift Voucher</td>
															<td class="text-right"><?php echo $this->session->userdata('currency_symbol')?></td>
															<td>
																<span id="tot_voucher_amt"></span>
																<a class="btn bg-olive btn-xs pull-right" id="gift_voucher_modal" href="#" data-toggle="modal" data-target="#gv-confirm-add" disabled><b>+</b></a> 
																<input type="hidden" id="giftVoucher_details" name="billing[vocuher]" value="">
															
															</td> 
														</tr>
														<?php 
														$modes = $this->ret_billing_model->get_payModes();
														if(sizeof($modes)>0){
														foreach($modes as $mode){
															$cash = ($mode['short_code'] == "CSH" ? '<input class="form-control" id="make_pay_cash" name="billing[cash_payment]" type="text" placeholder="Enter Amount" value=""/>' : '');
															$card = ($mode['short_code'] == "CC" || $mode['short_code'] == "DC" ? '<a class="btn bg-olive btn-xs pull-right" id="card_detail_modal" href="#" data-toggle="modal" data-target="#card-detail-modal" ><b>+</b></a> ' : '');
															$cheque = ($mode['short_code'] == "CHQ"  ? '<a class="btn bg-olive btn-xs pull-right" id="cheque_modal" href="#" data-toggle="modal" data-target="#cheque-detail-modal" ><b>+</b></a> ' : '');
															$net_banking = ($mode['short_code'] == "NB"  ? '<a class="btn bg-olive btn-xs pull-right"  href="#" data-toggle="modal" data-target="#net_banking_modal" ><b>+</b></a> ' : '');
														?>
														<tr>
															<td class="text-right"><?php echo $mode['mode_name']; ?>
															</td>
															<td class="text-right"><?php echo $this->session->userdata('currency_symbol')?></td>
															<td class="mode_<?php echo $mode['short_code']; ?>">
																<span class="<?php echo $mode['short_code'];?>"></span>
															<input type="hidden" id="card_payment" name="billing[card_pay]" value="">
															<input type="hidden" id="chq_payment" name="billing[chq_pay]" value="">
															<input type="hidden" id="nb_payment" name="billing[net_bank_pay]" value="">
															<?php echo $cash; ?> 
															<?php echo $card; ?> 
															<?php echo $cheque; ?> 
															<?php echo $net_banking; ?> 
															</td> 
														</tr>
														<?php }}?>
														<tr>
															<td class="text-right">Advance Adj</td>
															<td class="text-right"><?php echo $this->session->userdata('currency_symbol')?></td>
															<td>
																<span id="tot_adv_adj"></span>
																<a class="btn bg-olive btn-xs pull-right" id="adv_adj_modal" onclick="get_advance_details()" href="#" data-toggle="modal"><b>+</b></a> 
																<input type="hidden" id="adv_adj_details" name="billing[adv_adj]" value="">
																<input type="hidden" id="ord_adv_adj_details" name="billing[order_adv_adj]" value="">
															</td>
														</tr>
													</tbody>
													<tfoot>
														<tr>
															<th class="text-right custom-label">Total</th>
															<th class="text-right"><?php echo $this->session->userdata('currency_symbol')?></th>
															<th class="sum_of_amt"></th>
														</tr>
														<tr>
															<th class="text-right custom-label">Balance</th>
															<th class="text-right"><?php echo $this->session->userdata('currency_symbol')?></th>
															<th class="bal_amount"></th>
														</tr>
														<tr>
															<th class="text-right custom-label">Refund</th>
															<th class="text-right"><input type="checkbox" id="chit_refund" name="billing[chit_refund]"></th>
															<th class="chit_refund_amt"><input type="hidden" id="chit_refund_amt" name="billing[chit_refund_amt]" value=""></th>
														</tr>
													</tfoot>
												 </table>
											  </div>
											</div> 
										</div> 
									</div>
								</div>
							</div>
						</div>
					</div> 
	    			<div class="row">				    	
			    		<div class="col-sm-2">
			    			<label>Remark</label>
				 		</div>
				 		<div class="col-sm-10">
				 			<div class="form-group">
					 			<div class="input-group">
					 				<textarea class="form-control" id="remark" name="billing[remark]" <?php echo set_value('billing[remark]',isset($billing['remark'])?$billing['remark']:NULL); ?> rows="5" cols="100"> </textarea>
								</div>
							</div>
				 		</div> 
				 	</div>
				 	<div class="row">				    	
			    		<div class="col-sm-2">
			    			<label>Select Delivery</label>
				 		</div>
				 		<div class="col-sm-10">
				 			<div class="form-group">
					 			<div class="input-group">
					 				<select class="form-control" id="select_delivery" name="billing[id_delivery]" style="width:100%;"></select>
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
					  	<?php if($this->uri->segment(3) != 'edit'){?>
						<button type="button" id="pay_submit" class="btn btn-primary" disabled>Save</button> 
						<?php }?>
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
				<div class="row">   
					<div class="form-group">
					   <label for="" class="col-md-3 col-md-offset-1 ">Select Village<span class="error"></span></label>
					   <div class="col-md-6">
						 <select class="form-control" id="sel_village" ></select>
							<input type="hidden" name="cus[id_village]" id="id_village" name="">
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
				<h4 class="modal-title" id="myModalLabel">Select to add billing</h4>
			</div>
			<div class="modal-body">
				<div class="row" id="est_items_to_sale_convertion_tbl" style="display:none;">
					<div class="box-body">
						<p class="lead">Estimation</p>
						    <div class="row">
								<div class="col-md-6">
									<label>Non Tag Available Pieces : <span id="blc_pcs"></span>,</label>
									<label>Available Weight : <span id="blc_gwt"></span></label>
								</div>
								<div class="col-md-6">
									<label>Total Pieces : <span id="tot_pcs"></span>,</label>
									<label>Total Weight : <span id="tot_wt"></span></label>
								</div>
							</div>
						<div class="table-responsive">
							<table id="est_items_to_sale_convertion" class="table table-bordered table-striped text-center">
								<thead>
									<tr>
										<th><label class="checkbox-inline"><input type="checkbox" id="select_Allsale" name="select_all" value="all"/>All</label></th>
										<th>Product</th>
										<th>Design</th>
										<th>Pcs</th>    
										<th>Purity</th>   
										<th>Size</th> 
										<th>G.Wt</th>   
										<th>L.Wt</th>   
										<th>N.Wt</th>   
										<th>Wast(%)</th>   
										<th>Wast Wt(g)</th>   
										<th>MC</th>   
										<th>Discount</th>   
										<th>Tax Group</th>   
										<th>Tax</th>   
										<th>Amount</th>
										<th>Partly</th>
										<th>Tag No</th>
										<!--<th>Advance Paid</th> -->
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
						<p class="lead">Purchase</p>
						<div class="table-responsive">
							<table id="est_olditems_to_sale_convertion" class="table table-bordered table-striped text-center">
								<thead>
									<tr>
										<th><label class="checkbox-inline"><input type="checkbox" id="select_Allpur" name="select_all" value="all"/>All</label></th>
										<th>Purpose</th>
										<th>Category</th>
										<th>G.Wt</th>   
										<th>Dust.Wt</th>   
										<th>Stn.Wt</th>   
										<th>N.Wt</th>   
										<th>Wastage(%)</th>   
										<th>Wastage.Wt(%)</th>
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
<!-- / esti to billing modal -->        
<!--Gift Voucher-->
<div class="modal fade" id="gv-confirm-add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Gift Voucher</h4>
			</div>
			<div class="modal-body">
				<!--Gift Voucher--> 
					 <!--<?php echo !empty($est_other_item['voucher_details']) ? '' : 'style="display:none;"' ;?>-->
				<div class="box-body gift_voucher_details">
					<div class="row"> 
						<!--<div class="col-sm-12 pull-right">
							<button type="button" id="create_gift_voucher_details" class="btn bg-olive  btn-sm pull-right"><i class="fa fa-plus"></i> Add</button>
							<p class="error "><span id="voucherAlert"></span></p>
						</div>-->
					</div>
					<div class="row">
						<div class="box-body">
						   <div class="table-responsive">
							 <table id="gift_voucher_details" class="table table-bordered text-center">
								<thead>
								  <tr>
									<th>Voucher No</th>  
									<th>Amount</th>
									<th>Action</th>
								  </tr>
								</thead> 
								<tbody>
									<?php if($this->uri->segment(3) == 'edit'){
										foreach($est_other_item['voucher_details'] as $ikey => $ival){
												echo '<tr><td><input class="voucher_no" type="number" name="gift_voucher[voucher_no][]" style="width: 100px;" value="'.$ival['voucher_no'].'" /></td><td><input type="number" class="gift_voucher_amt" style="width: 100px;"  name="gift_voucher[gift_voucher_amt][]" value=""'.$ival['gift_voucher_amt'].'  /></td></tr>';
										}
									}else{ ?>
										<tr>
											<td><input class="voucher_no" type="text" name="gift_voucher[voucher_no][]" style="width: 100px;" /><input type="hidden" class="id_gift_card"></td>
											<td><input type="number" class="gift_voucher_amt" style="width: 100px;"  name="gift_voucher[gift_voucher_amt][]" readonly /></td>
											<td><a href="#" onclick="removeGift_voucher($(this).closest('tr'))" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>
										</tr>
									<?php }?>
								</tbody>
								<tfoot>
									<tr>
										<th >Total</th>
										<th colspan=2><span class="gift_total_amount"></span></th>
									</tr>
								</tfoot>
							 </table>
						  </div>
						</div> 
					</div> 
				</div>  
				<!--./Gift Voucher-->
			</div>
		  <div class="modal-footer">
			<a href="#" id="add_newvoucher" class="btn btn-success">Save</a>
			<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
		  </div>
		</div>
	</div>
</div>
<!-- / Gift Voucher Modal -->  
<!-- Chit Utilization -->
<div class="modal fade" id="chit-confirm-add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Chit Utilization</h4>
			</div>
			<div class="modal-body"> 
				<!--<?php echo !empty($est_other_item['chit_details']) ? '' : 'style="display:none;"' ;?>-->
				<div class="box-body chit_details">
					<div class="row"> 
						<div class="col-sm-12 pull-right">
							<button type="button" id="create_chit_details" class="btn bg-olive btn-sm pull-right"><i class="fa fa-plus"></i> Add</button>
							<p class="error "><span id="chitUtilAlert"></span></p>
						</div>
					</div>
					<div class="row">
						<div class="box-body">
						   <div class="table-responsive">
							 <table id="estimation_chit_details" class="table table-bordered text-center">
								<thead>
								  <tr>
									<th>A/c Id</th>
									<th>Scheme</th>
									<th>Amount</th>
									<th>Action</th>
								  </tr>
								</thead> 
								<tbody>
										<tr>
											<td><input class="scheme_account" type="text" style="width: 100px;" />
												<input type="hidden" class="scheme_account_id"  name="chit_uti[scheme_account_id][]">
											</td>
											<td><span class="sch"></span></td>
											<td><span class="chit_amount"></span><input type="hidden" class="chit_amt" name="chit_uti[chit_amt][]" /></td>
											<td><a href="#" onclick="removeChit_row($(this).closest('tr'))" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>
										</tr>
								</tbody>
								<tfoot>
									<tr>
										<th colspan=2>Total</th>
										<th colspan=2><span class="total_amount"></span></th>
									</tr>
								</tfoot>
							 </table>
							   <!-- <div class="col-md-12">
        							<div class="row">
        							   <div class="col-md-4">
        							      <input type="hidden" id="mobile" value="">
        							      <input type="hidden" id="send_resend" value="0">
                                         <button class="btn btn-primary" id="send_otp" value="Send OTP">Send OTP</button>
        							   </div>
        							   <div class="col-md-4">
                                         <input type="number" class="form-control" id="user_otp" disabled>
        							   </div>
        							 </div>
        							 <span id="otp_alert"></span>
							    </div>-->
						  </div>
						</div> 
					</div> 
				</div> 
			</div>
		  <div class="modal-footer" >
			<a id="add_newchit_util" class="btn btn-success">Save</a>
			<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
		  </div>
		</div>
	</div>
</div>
<!-- / Chit Utilisation -->  
<!-- Advance Adj -->
<div class="modal fade" id="adv-adj-confirm-add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Advance Adjustment</h4>
			</div>
			<div class="modal-body"> 
				<!--<?php echo !empty($est_other_item['chit_details']) ? '' : 'style="display:none;"' ;?>-->
				<div class="box-body chit_details"> 
					<div class="row">
						<div class="box-body">
						   <div class="table-responsive">
						   <div class="col-md-8">
							<div class="form-group">
			                  <label for="">Store As <span class="error"> *</span></label>&nbsp;&nbsp;
			                  <input type="radio" name="store_receipt_as" id="store_receipt_as_1" value="1" checked=""> Amount &nbsp;&nbsp;
			                      <input type="radio" name="store_receipt_as" id="store_receipt_as_2" value="2"> Weight  &nbsp;&nbsp;
			                  		<input type="hidden" id="id_ret_wallet" name="">
			                </div>
							</div>
							 <table id="bill_adv_adj" class="table table-bordered text-center">
								<thead>
								  <tr>
									<th>Select</th>
									<th>Type</th>
									<th>Wallet Balance</th> 
									<th>Total</th> 
								  </tr>
								</thead> 
								<tbody>
									
							</tbody>
								<tfoot>
									<tr>
										<th colspan=3>Total</th>
										<th><span class="adv_adj_amt"></span></th>
									</tr>
									<tr>
										<th colspan=3>Total Bill Amount</th>
										<th><span class="tot_bill_amt"></span></th>
									</tr>
									<tr>
										<th colspan=3>Adjusted Bill Amount</th>
										<th><span class="adjusted_amt"></span></th>
									</tr>
									<tr>
										<th colspan=3>Excess Amount</th>
										<th><span class="excess_amt"></span></th>
									</tr>
								</tfoot>

							 </table>
						  </div>
						</div> 
					</div> 
				</div> 
			</div>
		  <div class="modal-footer">
			<a href="#" id="add_adv_adj" class="btn btn-success">Save</a>
			<button type="button" class="btn btn-warning" data-dismiss="modal" id="close_add_adj">Close</button>
		  </div>
		</div>
	</div>
</div>
<!-- / Advance Adj -->  
<!-- Card Details -->
<div class="modal fade" id="card-detail-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:60%;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Card Details</h4>
			</div>
			<div class="modal-body"> 
				<div class="box-body">
					<div class="row"> 
						<div class="col-sm-12 pull-right">
							<button type="button" class="btn bg-olive btn-sm pull-right" id="new_card"><i class="fa fa-user-plus"></i>ADD</button>
							<p class="error "><span id="cardPayAlert"></span></p>
						</div>
					</div>
					<p></p>
				   <div class="table-responsive">
					 <table id="card_details" class="table table-bordered">
						<thead>
							<tr> 
								<th>Card Name</th>
								<th>Type</th> 
								<th>Card No</th>  
								<th>Amount</th>  
								<th>Action</th> 
							</tr>											
						</thead> 
						<tbody>
							<?php if($this->uri->segment(3) == 'edit'){
								/*foreach($est_other_item['card_details'] as $ikey => $ival){
										echo '<tr><td><input class="card_name" type="number" name="card_details[card_name][]" value="'.$ival['card_name'].'" /></td><td><input class="card_type" type="number" name="card_details[card_type][]" value="'.$ival['card_type'].'" /></td><td><input type="number" class="card_no" style="width: 100px;"  name="card_details[card_no][]" value="'.$ival['card_no'].'"  /></td><td><input type="number" class="card_amt" style="width: 100px;"  name="card_details[card_amt][]" value="'.$ival['card_amt'].'"  /></td><td>-</td></tr>';
								}*/
							}else{ ?>
							<tr> 
								<td><select name="card_details[card_name][]" class="card_name"><option value="1">RuPay</option><option value="2">VISA</option><option value="3">Mastro</option><option value="4">Master</option></select></td>
								<td><select name="card_details[card_type][]" class="card_type"><option value="1">CC</option><option value="2">DC</option></select></td>
								<td><input type="number" step="any" class="card_no" name="card_details[card_no][]"/></td> 
								<td><input type="number" step="any" class="card_amt" name="card_details[card_amt][]"/></td> 
								<td><a href="#" onclick="removeCC_row($(this).closest('tr'))" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>  
							</tr> 
							<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<th  colspan=3>Total</th>
								<th colspan=2>
									<span class="cc_total_amount"></span>
									<span class="cc_total_amt" style="display: none;"></span>
									<span class="dc_total_amt" style="display: none;"></span>
								</th>
							</tr>
						</tfoot>
					 </table>
				  </div>
				</div>  
			</div>
		  <div class="modal-footer">
			<a href="#" id="add_newcc" class="btn btn-success">Save</a>
			<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
		  </div>
		</div>
	</div>
</div>
<!-- / Advance Adj -->  
<!-- cheque-->
<!-- Card Details -->
<div class="modal fade" id="cheque-detail-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:60%;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Cheque Details</h4>
			</div>
			<div class="modal-body"> 
				<div class="box-body">
					<div class="row"> 
						<div class="col-sm-12 pull-right">
							<button type="button" class="btn bg-olive btn-sm pull-right" id="new_chq"><i class="fa fa-user-plus"></i>ADD</button>
							<p class="error "><span id="chqPayAlert"></span></p>
						</div>
					</div>
					<p></p>
				   <div class="table-responsive">
					 <table id="chq_details" class="table table-bordered">
						<thead>
							<tr> 
								<th>Cheque Date</th>
								<th>Bank</th> 
								<th>Branch</th>  
								<th>Cheque No</th>  
								<th>IFSC Code</th>  
								<th>Amount</th>  
								<th>Action</th> 
							</tr>											
						</thead> 
						<tbody>
							<tr> 
								<td><input id="cheque_datetime" data-date-format="dd-mm-yyyy hh:mm:ss" class="cheque_date" name="cheque_details[cheque_date][]" type="text" required="true" placeholder="Cheque Date" /></td>
								<td><input name="cheque_details[bank_name][]" type="text" required="true" class="bank_name"></td>
								<td><input name="cheque_details[bank_branch][]" type="text" required="true" class="bank_branch"></td>
								<td><input type="number" step="any" class="cheque_no" name="cheque_details[cheque_no][]"/></td> 
								<td><input type="text" step="any" class="bank_IFSC" name="cheque_details[bank_IFSC][]"/></td> 
								<td><input type="number" step="any" class="payment_amount" name="cheque_details[payment_amount][]"/></td> 
								<td><a href="#" onclick="removeChq_row($(this).closest('tr'))" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>  
							</tr> 
						</tbody>
						<tfoot>
							<tr>
								<td>Total</td><td></td><td></td><td></td><td></td><td><span class="chq_total_amount"></span></td><td></td>
							</tr>
						</tfoot>
					 </table>
				  </div>
				</div>  
			</div>
		  <div class="modal-footer">
			<a href="#" id="add_newchq" class="btn btn-success">Save</a>
			<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
		  </div>
		</div>
	</div>
</div>
<!-- cheque-->
<!-- Net Banking-->
<div class="modal fade" id="net_banking_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:60%;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Net Banking Details</h4>
			</div>
			<div class="modal-body"> 
				<div class="box-body">
					<div class="row"> 
						<div class="col-sm-12 pull-right">
							<button type="button" class="btn bg-olive btn-sm pull-right" id="new_net_bank"><i class="fa fa-user-plus"></i>ADD</button>
							<p class="error "><span id="NetBankAlert"></span></p>
						</div>
					</div>
					<p></p>
				   <div class="table-responsive">
					 <table id="net_bank_details" class="table table-bordered">
						<thead>
							<tr> 
								<th>Type</th> 
								<th>Ref No</th>  
								<th>Amount</th>  
								<th>Action</th> 
							</tr>											
						</thead> 
						<tbody>
							<tr> 
								<td><select name="nb_details[nb_type][]" class="nb_type"><option value=1>RTGS</option><option value=2>IMPS</option><option value=3>UPI</option></select></td>
								<td><input type="text" step="any" class="ref_no" name="nb_details[ref_no][]"/></td> 
								<td><input type="number" step="any" class="amount" name="nb_details[amount][]"/></td> 
								<td><a href="#" oonclick="removeNb_row($(this).closest('tr'))" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>  
							</tr> 
						</tbody>
						<tfoot>
							<tr>
								<th  colspan=2>Total</th>
								<th colspan=2>
									<span class="nb_total_amount"></span>
								</th>
							</tr>
						</tfoot>
					 </table>
				  </div>
				</div>  
			</div>
		  <div class="modal-footer">
			<a href="#" id="add_newnb" class="btn btn-success">Save</a>
			<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
		  </div>
		</div>
	</div>
</div>
<!-- Net Banking-->
<!-- Return Bill Modal -->      
<div class="modal fade" id="bill-detail-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:75%;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Select to Return</h4>
			</div>
			<div class="modal-body">
				<div class="row" id="bill_items_for_return" style="display:none;">
					<div class="box-body">
						<p class="lead">Bill Item Details</p>
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
										<th>Wast(%)</th>   
										<th>MC</th>   
										<th>Discount</th>   
										<th>Tax Group</th>   
										<th>Tax</th>   
										<th>Amount</th>
										<th>Partly</th>
										<th>Tag No</th>
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
				<div class="row" id="bill_old_items_purchased" style="display:none;"> 
					<div class="box-body">
						<p class="lead">Purchased Items</p>
						<div class="table-responsive">
							<table id="bill_old_items_purchased_tbl" class="table table-bordered table-striped text-center">
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
			<a href="#" id="update_bill_return" class="btn btn-success">Add</a>
			<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
		  </div>
		</div>
	</div>
</div>
<!-- / Return Bill Modal -->  
<!-- sale stone details-->
<div class="modal fade" id="stoneModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:60%;">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Add Stone</h4>
			</div>
			<div class="modal-body">
				<div class="row">
			<div class="box-tools pull-right">
			<button type="button" id="create_stone_item_details" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add</button>
			</div>
			</div>
				<div class="row">
						<input type="hidden" id="active_id"  name="">
					<table id="estimation_stone_item_details" class="table table-bordered table-striped text-center">
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
					</tbody>										
					<tfoot>
					<tr></tr>
					</tfoot>
					</table>
			</div>
		  </div>
		  <div class="modal-footer">
			<button type="button" id="update_stone_details" class="btn btn-success">Save</button>
			<button type="button" id="close_stone_details" class="btn btn-warning" data-dismiss="modal">Close</button>
		  </div>
		</div>
	</div>
</div>
</div>
<!-- sale stone details-->
<!--Purchase stone-->
<div class="modal fade" id="PurstoneModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:60%;">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Add Stone</h4>
			</div>
			<div class="modal-body">
				<div class="row">
			<div class="box-tools pull-right">
			</div>
			</div>
				<div class="row">
						<input type="hidden" id="pur_active_id"  name="">
					<table id="estimation_pur_stone_item_details" class="table table-bordered table-striped text-center">
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
					</tbody>										
					<tfoot>
					<tr></tr>
					</tfoot>
					</table>
			</div>
		  </div>
		  <div class="modal-footer">
			<button type="button" id="update_pur_stone_details" class="btn btn-success">Save</button>
			<button type="button" id="close_pur_stone_details" class="btn btn-warning" data-dismiss="modal">Close</button>
		  </div>
		</div>
	</div>
</div>
</div>
<!--Purchase stone-->
<!--Return Bill No-->
<div class="modal fade" id="billno-detail-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:75%;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Select to Return</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-4">	
						<div class="form-group">
							<label>Select Bill No</label>
							 <select id="billno_select" name="billno_select" class="form-control" style="width:100%;" multiple></select>  
							
							<input type="hidden" id="filter_Billno" name="">
						</div>
					</div>
				</div>
				<div class="row" id="bill_items_return" style="display:none;">
					<div class="box-body">
						<p class="lead">Bill Item Details</p>
						<div class="table-responsive">
							<table id="bill_items_for_return" class="table table-bordered table-striped text-center">
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
										<th>Tag No</th>
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
			</div>
		  <div class="modal-footer">
			<a href="#" id="update_billreturn" class="btn btn-success">Add</a>
			<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
		  </div>
		</div>
	</div>
</div>
<!-- / Return Bill Modal -->  
<!--Return Bill No-->


<!-- emp modal -->      
<div class="modal fade" id="emp_add"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Add Employee</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="form-group">
					   <label for="cus_first_name" class="col-md-3 col-md-offset-1 ">Employee Name<span class="error">*</span></label>
					   <div class="col-md-6">
							<input type="text" class="form-control" id="emp_firstname" name="emp[firstname]" placeholder="Enter customer first name" required="true"> 

							<p class="help-block cus_first_name"></p>
					   </div>
					</div>
				</div> 
				<div class="row">   
					<div class="form-group">
					   <label for="cus_mobile" class="col-md-3 col-md-offset-1 ">Mobile<span class="error">*</span></label>
					   <div class="col-md-6">
							<input type="number" class="form-control" id="emp_mobile" name="emp[mobile]" placeholder="Enter customer mobile"> 
							<p class="help-block cus_mobile"></p>
					   </div>
					</div>
				</div>
			</div>
		  <div class="modal-footer">
			<a href="#" id="add_newemployee" class="btn btn-success">Add</a>
			<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
		  </div>
		</div>
	</div>
</div>
<!-- / emp modal -->
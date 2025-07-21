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
	
    .form-group {
        margin-bottom: 1px;
    }
    
    
    
    *[tabindex]:focus {
    outline: 1px black solid;
    }
    
    .billType{
        padding : 3px !important;
        margin : 0px !important;
        height: auto;
    }
    
</style>
  <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <!--<section class="content-header">
          <h1>
        	Billing
            <small>Customer Billing</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Billings</a></li>
            <li class="active">Billing</li>
          </ol>
        </section>-->
        <!-- Main content -->
        <section class="content product">
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
          <div class="row">
             <!-- form -->
			<form id="metal_issue_form">
				<div class="col-md-12"> 	
					<h4 class="box-title">SIMTH METAL ISSUE FORM</h4>
                	<div class="tab-content">
                		<div class="tab-pane active" id="pay_items">
                		    <div class="box box-default ">
                		        <div class="box-body" align="center">
                                <div class="row">
                		                    <div class="col-md-3"> 
                		                        <h4>Outstanding Amt(Rs)</h4>
                		                    </div>
                		                    <div class="col-md-3"> 
                		                        <h4><span class="availableamtbalance"></span></h4>
                		                    </div>
                		                    <div class="col-md-3"> 
                		                        <h4>Outstanding Pure Wt(Grms)</h4>
                		                    </div>
                		                    <div class="col-md-3"> 
                		                        <h4><span class="availablepurebalance"></span></h4>
                		                    </div>
                		                    
                		             </div>
                		          </div>   
                		         <div class="box-body" align="center">
                		             
				            	    <div class="row">
				            	        <div class="col-md-12">
				            	         <div class="row">
                    						 <div class="col-md-2"> 
                                                <div class="form-group" >
                                                    <label>Karigar<span class="error">*</span></label> 
                                                    <select class="form-control" id="select_karigar" name="issue[id_karigar]" style="width:100%;"></select>
                                                </div>
                    						 </div>
                    						 
                    						 <div class="col-md-2"> 
                                                <div class="form-group" >
                                                    <label>Aganist Order<span class="error">*</span></label><br>
            										<input type="radio" id="aganist_order_yes" name="issue[issue_aganist]" value="1" ><label for="aganist_order_yes">&nbsp;Yes</label>
            										<input type="radio" id="aganist_order_no" name="issue[issue_aganist]" value="0" checked><label for="aganist_order_no">&nbsp;No</label>
                                                </div>
                    						 </div>
                    						 
                    						 <div class="col-md-2"> 
                                                <div class="form-group" >
                                                    <label>Select PO No<span class="error">*</span></label> 
                                                    <select class="form-control" id="select_po_no" name="issue[id_order]" style="width:100%;" disabled></select>
                                                </div>
                    						 </div>
                    						 
                    						 
                    						 
                    						 <div class="col-md-2"> 
                                                <div class="form-group" >
                                                    <label>Metal<span class="error">*</span></label> 
                                                    <select class="form-control" id="select_metal" name="issue[id_metal]"  style="width:100%;"></select>
                                                </div>
                    						 </div>
                    						 
                    						 <div class="col-md-2"> 
                                                <div class="form-group" >
                                                    <label>Category<span class="error">*</span></label> 
                                                    <select class="form-control" id="select_category"  style="width:100%;"></select>
                                                </div>
                    						 </div>
                    						 <div class="col-md-2"> 
                                                <div class="form-group" >
                                                    <label>Section<span class="error">*</span></label> 
                                                    <select class="form-control" id="select_section" name="issue[id_section]"  style="width:100%;"></select>
                                                </div>
                    						 </div>
                    						  
                    					  </div><p></p>
                    					  <div class="row">
                    					     <div class="col-md-2"> 
                                                <div class="form-group" >
                                                    <label>Product<span class="error">*</span></label> 
                                                    <select class="form-control" id="select_product" style="width:100%;"></select>
                                                </div>
                    						 </div>
                    						 <div class="col-md-2"> 
                                                <div class="form-group" >
                                                    <label>Design<span class="error">*</span></label> 
                                                    <select class="form-control" id="select_design" style="width:100%;"></select>
                                                </div>
                    						 </div>
                    						 <div class="col-md-2"> 
                                                <div class="form-group" >
                                                    <label>Sub Design<span class="error">*</span></label> 
                                                    <select class="form-control" id="select_sub_design" style="width:100%;"></select>
                                                </div>
                    						 </div>
                    						 <div class="col-md-2"> 
                                                <div class="form-group" >
                                                    <label>Purity<span class="error">*</span></label> 
                                                    <select class="form-control" id="select_purity"  style="width:100%;"></select>
                                                </div>
                    						 </div>
                    						 <div class="col-md-2"> 
                                                <div class="form-group" >
                                                    <label>Piece<span class="error">*</span></label> 
                                                    <input class="form-control" type="number" id="issue_pcs"  placeholder="Pcs">
                                                    <b>Avail Pcs :<span class="available_pcs"></span></b>
                                                </div>
                    						 </div>
                    						 
                    						 <div class="col-md-2"> 
                                                <div class="form-group" >
                                                    <label>Weight<span class="error">*</span></label> 
                                                    <input class="form-control" type="number" id="issue_weight"  placeholder="Weight">
                                                    <b>Avail Wt :<span class="available_weight"></span></b>
                                                </div>
                    						 </div>
                    						 
                        					 <div class="col-md-2"> 
                    						    <label>Pure Weight<span class="error">*</span></label> 
                                                <div class="input-group" > 
        											<input class="form-control" id="pur_weight" placeholder="Pure Weight" readonly>
        											<span class="input-group-btn">
        						                      <button type="button" id="add_metal_issue" class="btn btn-default btn-flat" ><i class="fa fa-plus"></i></button>
        						                    </span>
        										</div>
                    						 </div>
                    					  </div>

                						 
                    						 
				            	        </div>
				            	         <!--<div class="col-md-3">
				            	             <label>Available Stock Details</label>
				            	             <div class="table-responsive">
				            	                 <table id="available_stock_details" class="table table-bordered table-striped text-center" style="text-transform:uppercase;">
				            	                     <thead>
				            	                         <tr>
				            	                             <th>Product</th>
				            	                             <th>Gwt(Gms)</th>
				            	                             <th>Nwt(Gms)</th>
				            	                         </tr>
				            	                     </thead>
				            	                     <tbody></tbody>
				            	                 </table>
				            	             </div>
				            	         </div>-->
				            	    </div>
								</div>
								
								
									<div class="box-body">
                			        <!-- Search Block	 -->
                					
        							<div class="row sale_details">
        								<div class="col-md-12">
        							       <p class="text-light-blue">Metal Issue Details</p>
        								   <div class="table-responsive">
        									 <table id="metal_details" class="table table-bordered table-striped text-center" style="text-transform:uppercase;">
        										<thead>
        										  <tr>
        											<th>Category</th>
        											<th>Section</th>
        											<th>Product</th>
        											<th>Purity</th>  
        											<th>Pcs</th>  
        											<th>Weight</th>  
        											<th>Pure Wt</th>   											
        											<th>Action</th>   											
        										  </tr>
        										</thead> 
        										<tbody>
        										</tbody>
        									 </table>
        								  </div>
        								</div> 
        							</div> 
                				</div>
								
								
        						<div class="box-body">
                			        <!-- Search Block	 -->
                					
        							<div class="row sale_details" style="display:none;">
        								<div class="col-md-12">
        							       <p class="text-light-blue">Purchase Details</p>
        								   <div class="table-responsive">
        									 <table id="item_details" class="table table-bordered table-striped text-center" style="text-transform:uppercase;">
        										<thead>
        										  <tr>
        											<th>Category</th>
        											<th>Product</th>
        											<th>Design</th>  
        											<th>Sub Design</th>  
        											<th>Pcs</th>   											
        											<th>G.Wt</th>   
        											<th>L.Wt</th>   
        											<th>N.Wt</th>   
        											<th>Wast(%)</th>   
        											<th>MC</th>  
        											<th>PURE WT</th>   
        										  </tr>
        										</thead> 
        										<tbody>
        										</tbody>
        									 </table>
        								  </div>
        								</div> 
        							</div> 
                				</div>
                    		</div>
                		</div>
                		<div class="row">  
                            <div class="col-sm-6 "> 
                            <label>Remark</label>	
                            <textarea class="form-control" id="remark" name="issue[remark]" rows="5" cols="100" style="width: 452px; height: 100px;"> </textarea>
                            </div>
                        </div>

                		<div class="row">
						    <div class="col-sm-12" align="center">
						        <?php if($this->uri->segment(3) != 'edit'){?>
        						<button type="button" id="submit_metal_issue" class="btn btn-primary" >Save</button> 
        						<?php }?>
        						<button type="button" class="btn btn-default btn-cancel">Cancel</button>
						    </div>
						</div>
                	</div>
				 	<p></p>
				</div>	<!--/ Col --> 
			</div>	 <!--/ row -->
			   <p class="help-block"> </p>  
	            </div>  
	          <?php echo form_close();?>
	            <div class="overlay" style="display:none">
				  <i class="fa fa-refresh fa-spin"></i>
				</div>
	             <!-- /form -->
	          </div>
             </section>
        </div>
        
        

<div class="modal fade" id="netbanking-confirm-add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Net Banking Details</h4>
			</div>
			<div class="modal-body"> 
				<div class="box-body chit_details">
					<div class="row"> 
						<div class="col-sm-12 pull-right">
							<button type="button" id="create_net_banking_row" class="btn bg-olive btn-sm pull-right"><i class="fa fa-plus"></i> Add</button>
						</div>
					</div>
					<div class="row">
						<div class="box-body">
						   <div class="table-responsive">
							 <table id="net_banking_details" class="table table-bordered text-center">
								<thead>
								  <tr>
									<th>Amount</th>
									<th>Type</th>
									<th>Ref NO</th>
									<th>Action</th>
								  </tr>
								</thead> 
								<tbody>
										<tr>
											<td><input type="number" class="form-control pay_amount" type="number"/></td>
											<td>
    											<select class="form-control nb_type">
    											    <option value="RTGS">RTGS</option>
    											    <option value="NEFT">NEFT</option>
    											</select>
											</td>
											<td><input type="number" class="form-control ref_no" type="text"/></td>
											<td><a href="#" onclick="remove_net_banking_row($(this).closest('tr'))" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>
										</tr>
								</tbody>
								<tfoot>
									<tr>
										<th colspan=2>Total</th>
										<th colspan=2><span class="total_amount"></span></th>
									</tr>
								</tfoot>
							 </table>
						  </div>
						</div> 
					</div> 
				</div> 
			</div>
		  <div class="modal-footer" >
			<a id="save_net_banking" class="btn btn-success">Save</a>
			<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
		  </div>
		</div>
	</div>
</div>



<div class="modal fade" id="sales_adjustment_add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Sales Details</h4>
			</div>
			<div class="modal-body"> 
				<div class="box-body chit_details">
					<div class="row"> 
						<div class="col-sm-12 pull-right">
							<button type="button" id="create_sales_details_row" class="btn bg-olive btn-sm pull-right"><i class="fa fa-plus"></i> Add</button>
						</div>
					</div>
					<div class="row">
						<div class="box-body">
						   <div class="table-responsive">
							 <table id="bill_details" class="table table-bordered text-center">
								<thead>
								  <tr>
									<th>Bill No</th>
									<th>Amount</th>
									<th>Action</th>
								  </tr>
								</thead> 
								<tbody>
										<tr>
											<td><input type="text" class="form-control bill_no"/><input type="hidden" class="form-control bill_id"/></td>
											<td><input type="number" class="form-control payment_amount" type="text" readonly/></td>
											<td><a href="#" onclick="remove_net_banking_row($(this).closest('tr'))" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>
										</tr>
								</tbody>
								<tfoot>
									<tr>
										<th colspan=2>Total</th>
										<th colspan=2><span class="total_amount"></span></th>
									</tr>
								</tfoot>
							 </table>
						  </div>
						</div> 
					</div> 
				</div> 
			</div>
		  <div class="modal-footer" >
			<a id="save_sales_details" class="btn btn-success">Save</a>
			<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
		  </div>
		</div>
	</div>
</div>
 
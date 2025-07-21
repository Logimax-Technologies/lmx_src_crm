<!-- Content Wrapper. Contains page content -->
      <style>
.remove-btn {
    margin-top: -168px;
    margin-left: -38px;
    background-color: #e51712 !important;
    border: none;
    color: white !important;
}

.custom-bx {
    box-shadow: none;
    border: 0.5px solid #e1e1e1;
}
      </style>
      <div class="content-wrapper">
          <!-- Content Header (Page header) -->
          <section class="content-header">
              <h1>
                  Sales Trasnfer
              </h1>
          </section>

          <!-- Main content -->
          <section class="content">

              <!-- Default box -->
              <div class="box box-primary">
                  <div class="box-body">
                      <!-- Alert -->
                      <?php 
					if($this->session->flashdata('chit_alert'))
					 {
						$message = $this->session->flashdata('chit_alert');
				?>
                      <div class="alert alert-<?php echo $message['class']; ?> alert-dismissable">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          <h4><i class="icon fa fa-check"></i> <?php echo $message['title']; ?>!</h4>
                          <?php echo $message['message']; ?>
                      </div>
                      <?php } ?>
                      <!-- form -->
                      <?php  echo form_open_multipart(""); ?>
                      <div class="row">
                          <div class="col-md-5">
                              <div class="row">
                                  <div class="col-md-12">
                                      <div class="form-group">
                                          <label for="">Type <span class="error"> *</span></label>
                                          <div class="form-group">
                                             <?php 
                            				 		$this->session->unset_userdata('SALES_TRANS_FORM_SECRET');
                        				 		    $form_secret=md5(uniqid(rand(), true));
                        					        $this->session->set_userdata('SALES_TRANS_FORM_SECRET', $form_secret);
                    				 		    ?>
                                              <input type="hidden" id="form_secret" value="<?php echo $form_secret; ?>">
                                              <input type="radio" name="sales_transfer_item_type" id="type1" value="1"
                                                  checked> <label for="type1">Sales Transfer Request</label>
                                              &nbsp;&nbsp;
                                              <input type="radio" name="sales_transfer_item_type" id="type2" value="2">
                                              <label for="type2">Sales Transfer Download</label>&nbsp;&nbsp;
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-sm-5 remark"> 
					 		 <label >Remark</label>	
					 		<textarea class="form-control" id="remark"  rows="5" cols="100"> </textarea>
					 	</div>
                          </div>
                          <div class="col-md-7">
                              <div class="row">
                                  <div class="col-md-12">
                                      <div class="box box-default custom-bx">
                                          <div class="box-body">
                                              <div class="row">
                                                  <div class="col-md-5">
                                                      <div class="form-group">
                                                          <div class="row">
                                                              <div class="col-md-5 ">
                                                                  <label for="" class="control-label pull-right">From
                                                                      Branch <span class="error"> *</span></label>
                                                              </div>
                                                              <div class="col-md-7">
                                                                 
                                                                  <select class="form-control from_branch" id="from_brn"
                                                                      required></select>
                                                                  
                                                              </div>
                                                          </div>
                                                      </div>
                                                  </div>
                                                  <div class="col-md-1 tagged" align="right"> </div>
                                                  <div class="col-md-5 to_branch_blk">
                                                      <div class="form-group">
                                                          <div class="row">
                                                              <div class="col-md-5 ">
                                                                  <label for="" class="control-label pull-right">To
                                                                      Branch <span class="error"> *</span></label>
                                                              </div>
                                                              <div class="col-md-7">
                                                                  <select class="form-control to_branch" id="to_brn"
                                                                      required></select>
                                                              </div>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-md-5">
                                                      <div class="form-group sales_trans">
                                                          <div class="row" style="display:none;">
                                                              <div class="col-md-5 ">
                                                                  <label for="" class="control-label pull-right">BT
                                                                      Code</label>
                                                              </div>
                                                              <div class="col-md-7">
                                                                  <input type="text" class="form-control" id="bt_code"
                                                                      placeholder="BT Code" autocomplete="off">
                                                              </div>
                                                          </div>
                                                      </div>
                                                      <div class="form-group sales_trans" > <!--Client asked to remove LOT NO SEARCH--> 
            												<div class="row">
            													<div class="col-md-5 ">
            														<label for="" class="control-label pull-right">Select Metal</label> 
            													</div>
            													<div class="col-md-7">
            														<select class="form-control" id="select_metal"></select>
            													</div>
            												</div> 
            											</div> 
            											
            											<div class="form-group sales_trans">
                                                          <div class="row">
                                                              <div class="col-md-5 ">
                                                                  <label for=""
                                                                      class="control-label pull-right">Category<span class="error"> *</span></label>
                                                              </div>
                                                              <div class="col-md-7">
                                                                  <select class="form-control" id="select_category"
                                                                      style="width:100%;"></select>
                                                              </div>
                                                          </div>
                                                      </div>
            											
            											<div class="form-group sales_trans" > <!--Client asked to remove LOT NO SEARCH--> 
            												<div class="row">
            													<div class="col-md-5 ">
            														<label for="" class="control-label pull-right">Lot No</label> 
            													</div>
            													<div class="col-md-7">
            														<select class="form-control" id="lotno"></select>
            													</div>
            												</div> 
            											</div> 
                                                      
                                                      
                                                      
            										<div class="form-group sales_trans" >   
        												<div class="row">
        													<div class="col-md-5 ">
        														<label for="" class="control-label pull-right">Product</label> 
        													</div>
        													<div class="col-md-7">
        														<div class="form-group" > 
        															<input type="text" class="form-control product" id="nt_product" placeholder="Product Name/Code" autocomplete="off">
        										                    <input type="hidden" class="form-control" id="id_product">
        										                    <span class="prodAlert"></span>
        														</div> 
        													</div>
        												</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">    
                                                 <div class="form-group tagged sales_trans"> 
        												<div class="row">
        													<div class="col-md-5 ">
        														<label for="" class="control-label pull-right">Design</label> 
        													</div>
        													<div class="col-md-7">
        														<input type="text" class="form-control" id="design" placeholder="Design"  autocomplete="off">
        														<input type="hidden" class="form-control" id="id_design">
        													</div>
        												</div> 
        											</div>
        											<div class="form-group tagged sales_trans"> 
        												<div class="row">
        													<div class="col-md-5 ">
        														<label for="" class="control-label pull-right">Tag Code</label> 
        													</div>
        													<div class="col-md-7">
        														<input type="text" class="form-control" id="tag_no" placeholder="Tag Code"  autocomplete="off">
        													</div>
        												</div> 
        											</div>
                                                     
                                                      <div class="form-group sales_trans_calc_type" style="display:none;">
                                                          <div class="row">
                                                              <div class="col-md-5">
                                                                  <label for="" class="control-label pull-right">Calc
                                                                      Type<span class="error"> *</span></label>
                                                              </div>
                                                              <div class="col-md-7">
                                                                  <select class="form-control"
                                                                      id="select_calculation_type" style="width:100%;">
                                                                      <option value="1" selected>Per Gram</option>
                                                                      <option value="2" >Per Piece</option>
                                                                  </select>

                                                              </div>
                                                          </div>
                                                      </div>



                                                      <div class="form-group sales_trans_tag_no" style="">
                                                          <div class="row" style="display:none;">
                                                              <div class="col-md-5">
                                                                  <label for="" class="control-label pull-right">Tag
                                                                      No</label>
                                                              </div>
                                                              <div class="col-md-7">
                                                                  <input type="text" class="form-control" id="tag_no"
                                                                      placeholder="Tag No" autocomplete="off">
                                                              </div>
                                                          </div>
                                                      </div>
                                                      
                                                      <div class="form-group sales_trans_tag_no" style="">
                                                          <div class="row">
                                                              <div class="col-md-5">
                                                                  <label for="" class="control-label pull-right">Old Tag
                                                                      No</label>
                                                              </div>
                                                              <div class="col-md-7">
                                                                  <input type="text" class="form-control" id="old_tag_no"
                                                                      placeholder="Tag No" autocomplete="off">
                                                              </div>
                                                          </div>
                                                      </div>
                                                      
                                                      <div class="form-group sales_trans_tag_no" style="">
                                                          <div class="row">
                                                              <div class="col-md-5">
                                                                  <label for="" class="control-label pull-right">Rate/Gram<span class="error"> *</span></label>
                                                              </div>
                                                              <div class="col-md-7">
                                                                  <input type="number" class="form-control" id="rate_per_gram" placeholder="Rate per Gram" autocomplete="off">
                                                              </div>
                                                          </div>
                                                      </div>
                                                      
                                                    </div>
                                                    <div class="col-md-5">
                                                      <div class="form-group sales_trans_bill_no" style="display:none;">
                                                          <div class="row">
                                                              <div class="col-md-5">
                                                                  <label for="" class="control-label pull-right">Bill
                                                                      No<span class="error"> *</span></label>
                                                              </div>
                                                              <div class="col-md-7">
                                                                  
                                                                  
                                                                  <div class="form-group" > 
        											 			    <div class="input-group" > 
                                                                        <span class="input-group-btn">
                                                                            <select class="form-control" id="fin_year_code" style="width:100px;">
                                                                            <?php 
                                                                                foreach($fin_year as $val)
                                                                                {?>
                                                                                <option value=<?php echo $val['fin_year_code'];?> <?php echo ($val['fin_status']==1 ?'selected' :'')  ?> ><?php echo $val['fin_year_name'];?></option>
                                                                            <?php }
                                                                            ?>
                                                                        </select>
                                                                        </span>
                                                                        <input type="text" class="form-control" id="bill_no" placeholder="Bill No" autocomplete="off" style="width: 100px;">
                        											</div>
            													</div>
													
                                                                  
                                                              </div>
                                                          </div>
                                                      </div>


                                                      <div class="form-group sales_trans">
                                                          <div class="row">
                                                              <div class="col-md-offset-5 col-md-7">
                                                                  <button type="button"
                                                                      class="btn btn-info btn-flat sales_transfer_search pull-right">Search</button>
                                                              </div>
                                                          </div>
                                                      </div>

                                                      <div class="form-group sales_trans_download_search"
                                                          style="display:none;">
                                                          <div class="row">
                                                              <div class="col-md-offset-5 col-md-7">
                                                                  <button type="button"
                                                                      class="btn btn-info btn-flat sales_transfer_approval_search pull-right">Search</button>
                                                                      <input type="hidden" id="sales_trans_dnload" value="<?php echo $sales_transfer_download;?>">
                                                                      <input type="hidden" id="actual_pcs_dnload" value="">
                                                              </div>
                                                          </div>
                                                      </div>

                                                  </div>
                                              </div>
                                          </div>
                                          <!-- /.box-body -->
                                      </div>
                                      <!-- /.box -->
                                  </div>
                              </div>
                          </div>
                      </div>

                      <p class="help-block"></p>
                      <div class="row sales_trans">
                          <div class="col-md-12">
                              <div style="display:none;">
                                  <p>
                                      <span style="margin-right:15%;margin-left: 42%;">
                                          <span><b>TOTAL : </b></span>
                                          <span><b>PCS : </b></span>
                                          <span class="tot_bt_pcs" style="font-bold;">0</span>
                                          <span><b>WEIGHT : </b></span>
                                          <span class="tot_bt_gross_wt" style="font-bold;">0.000</span>
                                      </span>
                                  </p>
                              </div></br>
                              <div class="table-responsive">
                                  <table id="bt_search_list" class="table table-bordered table-striped text-center">
                                      <thead>
                                          <tr>
                                              <th width="5%"><label class="checkbox-inline"><input type="checkbox"
                                                          id="select_all" name="select_all" value="all" />All</label>
                                              </th>
                                              <th width="10%">Category</th>
                                              <th width="10%">Pcs</th>
                                              <th width="10%">G.wt</th>
                                              <th width="10%">L.wt</th>
                                              <th width="10%">N.wt</th>
                                              <th width="10%">Calc Type</th>
                                              <th width="10%">Purchase rate</th>
                                              <th width="10%">Amount</th>
                                              <th width="10%">Action</th>
                                          </tr>
                                      </thead>
                                      <tbody></tbody>
                                      <tfoot><tr style="font-weight:bold;"><td>TOTAL</td><td></td><td class="tot_bt_pcs"></td><td class="tot_bt_gross_wt"></td><td class="tot_lwt"></td><td class="tot_nwt"></td><td></td><td></td><td class="total_item_cost"></td><td></td></tr></tfoot>
                                  </table>
                              </div>
                          </div>
                      </div>


                      <div class="row sales_trans_download" style="display:none;">
                          <div class="col-md-12">
                              <div class="table-responsive">
                                  <table id="bt_search_download_list"
                                      class="table table-bordered table-striped text-center">
                                      <thead>
                                          <tr>
                                              <th width="10%"><label class="checkbox-inline"><input type="checkbox"
                                                          id="sales_return_trans_select_all" name="select_all"
                                                          value="all" />Bill No</label></th>
                                              <th width="10%">Bill Date</th>
                                              <th width="5%">Pcs</th>
                                              <th width="10%">G.wt</th>
                                              <th width="10%">Action</th>
                                          </tr>
                                      </thead>
                                      <tbody></tbody>
                                  </table>
                              </div>
                          </div>
                      </div>

                       <div class="row">
                            <div class="col-md-12 container">
                                <div class="table-responsive">
                                <table id="bill_approval_list_by_scan"  style="display:none;width:80%;margin-left: auto;margin-right: auto;"  class="table table-bordered table-striped text-center ">
                                    <thead>
                                    <tr>
                                        <th>Bill No</th> 
                                        <th>Bill Date</th>
                                        <th>Pcs</th>
                                        <th>G.wt</th>
                                    </tr>
                                    </thead> 
                                    <tbody></tbody>
                                    
                                </table>
                            </div>
                            </div>
				      </div>

                      <div class="form-group tagged col-md-offset-4" id="tag_scan_code" style="display:none;    margin-top: 20px;"> 
						<div class="row">
							<div class="col-md-2">
							    <div class="form-group" > 
								    <input type="text" class="form-control" id="scan_tag_no" placeholder="Tag Code"  autocomplete="off">
								</div>
							</div>
							
							<div class="col-md-2">
							    <div class="form-group" > 
								    <input type="text" class="form-control" id="old_tag_code" placeholder="Old Tag Code"  autocomplete="off">
								</div>
							</div>
						</div> 
					</div>

                    <div class="table-responsive">
                        <table id="bill_dwnload_list" style="display:none" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th width="5%">Tag Code</th>   
                                    <th width="10%">Product</th>
                                    <th width="10%">Pcs</th>  
                                    <th width="10%">G.wt</th>  
                                </tr>
                            </thead>
                            <tbody></tbody>

                        </table>
                    </div>

                      <div class="row sales_submit">
                          <div class="box box-default"><br />
                              <div class="col-xs-offset-5">
                                  <button type="button" id="sales_trans_submit" class="btn btn-primary">Save</button>
                                  <button type="button" class="btn btn-default btn-cancel">Cancel</button>
                              </div> <br />
                          </div>
                      </div>


                  </div>

                  <div class="overlay" style="display:none">
                      <i class="fa fa-refresh fa-spin"></i>
                  </div>
              </div>

              <!-- /form -->
          </section>
      </div>


      <div class="modal fade" id="oi_remark_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
          aria-hidden="true" data-keyboard="false" data-backdrop="static">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">

                      <button type="button" class="close" data-dismiss="modal"><span
                              class="sr-only">Close</span></button>
                      <h4 class="modal-title" id="myModalLabel">Other Issue Remark</h4>
                  </div>
                  <div class="modal-body">
                      <textarea id="oi_remark" name="oi_rem" rows="4" cols="50"></textarea>
                  </div>
                  <div class="modal-footer">
                      <a href="#" class="btn btn-danger btn-confirm" id="oi_save">Save</a>
                  </div>
              </div>
          </div>
      </div>
  <style>
      .content-header{
        padding: 5px 15px 0 3px !important;
      }
      .breadcrumb{
         margin-bottom: 10px !important;
      }
      .content{
          padding-top: 5px;
      }
  </style>
  <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="row">
                <div class="col-md-2">
                    <span id="total_count" class="badge bg-green"></span><span id="total_wt" class="badge bg-green"></span>
                </div>
                <div class="col-md-2"> 
									<?php if($this->session->userdata('branch_settings')==1 && $this->session->userdata('id_branch')==0){?>
								
									<div class="form-group tagged">
										<select id="branch_select" class="form-control branch_filter"></select>
									</div> 
									<?php }else{?>
										<input type="hidden" id="branch_filter"  value="<?php echo $this->session->userdata('id_branch') ?>"> 
									<?php }?> 
				</div> 
				
				<div class="col-md-2">
                	<div class="form-group">
                		<select id="metal" style="width:100%;"></select>
                	</div>
                </div>
                                
				<div class="col-md-2"> 
					<div class="form-group">
						<select id="category" style="width: 100%;"></select>
					</div> 
				</div>
				<div class="col-md box-tools pull-right">
                      <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="#">Retail Reports</a></li>
                        <li class="active">Design Wise Tag Items</li>
                      </ol>
                </div>
                
                

          </div>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
               
               <div class="box box-primary">
			    
                 <div class="box-body">  
                   <div class="box box-info stock_details">
                              <div class="box-header with-border">
							        <div class="row">
                                      <div class="col-md-2"> 
        									<div class="form-group">
        										<select id="prod_select" style="width: 100%;"></select>
        									</div> 
        								</div> 
        								<div class="col-md-2"> 
        									<div class="form-group">
        										<select id="des_select" style="width: 100%;"></select>
        									</div> 
        								</div>
        								
        								<div class="col-md-2"> 
        									<div class="form-group">
        										<select id="select_collection" style="width: 100%;" multiple></select>
        									</div> 
        								</div>
        								
        								<div class="col-md-1"> 
        									<div class="form-group"> 
        										<input type="number" id="from_weight" class="form-control" placeholder="From Weight">
        									</div> 
        								</div>
        								<div class="col-md-1"> 
        									<div class="form-group"> 
        										<input type="number" id="to_weight" class="form-control" placeholder="To Weight">
        									</div> 
        								</div>
        								<div class="col-md-2"> 
        									<div class="form-group">
        										<select id="select_size" style="width: 100%;"></select>
        									</div> 
        								</div>
        								
        								<div class="col-md-2"> 
                                        	<div class="form-group">
                                        		<select id="karigar" class="form-control" style="width:100%;"></select>
                                        	</div>
                                        </div>
                
        								<div class="col-md-2"> 
        									<div class="form-group">
        										<button type="button" id="tag_design_search" class="btn btn-info"><i class="fa fa-search"></i></button>   
        									</div>
        								</div>
                                  </div>
                                  <div class="row">
                                      <div class="col-md-1">
    									 <div class="form-group">
    								        <button id="btnExport" onclick="fntaggedItemsExcelReport('1');" class="btn btn-success "><i class="fa fa-file-excel-o"></i>&nbsp;</button>
    								     </div>
        								</div>
                                  </div>
                                
                              </div>
                              <div class="box-body collapse">
                                  <div class="row">
                                      <div class="box-body col-md-offset-2 col-md-8">
                                          <div class="table-responsive">
                                              <div>
                                                  <table id="tag_item_branchwise"
                                                      class="table table-bordered table-striped text-center">
                                                      <thead>
                                                          <tr>
                                                              <th width="10%;">Branch</th>
                                                              <th width="10%;">Piece</th>
                                                              <th width="10%;">Gross Wt</th>
                                                              <th width="10%;">Net Wt</th>
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
	                   <div class="col-md-12">
	                   	<div class="table-responsive">
		                 <table id="tag_items_list" class="table table-bordered table-striped text-center">
		                    <thead>
							  <tr>
							    <th width="10%;">Lot No</th>
							    <th width="10%;">Karigar</th>
							    <th width="10%;">Tag No</th>
							    <th width="10%;">Old Tag</th>
							    <th width="10%;">Tag Date</th>
							    <th width="10%;">Branch</th>
							    <th width="10%;">Category</th>
							    <th width="10%;">Product</th>
							    <th width="10%;">Design</th>
							    <th width="10%;">Sub Design</th>
							    <th width="10%;">Collection</th>
							    <th width="10%;">Pcs</th>
							    <th width="10%;">Gross Wgt</th>
							    <th width="10%;">Net Wgt</th>
							    <th width="1%">Size/Length</th>
							    <th width="10%;">Wastage</th>
							    <th width="10%;">Mc /G </th>
							    <th width="10%;">Mc /pcs </th>
							    <th width="10%;">Cost</th>
							    <th width="5%;">Attributes</th>
							    <th width="10%;">Cert No</th>
							    <th width="10%;">Style Code</th>
							    <th width="10%;">Tot Est</th>
							  </tr>
		                    </thead> 
		                    
		                 </table>
	                  </div>
	                   </div>
                   </div>
                </div><!-- /.box-body -->
                <div class="overlay" style="display:none">
				  <i class="fa fa-refresh fa-spin"></i>
				</div>
              </div>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      


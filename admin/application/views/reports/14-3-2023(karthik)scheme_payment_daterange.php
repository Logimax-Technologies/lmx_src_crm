  <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Collection Report
            <small></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Reports</a></li>
            <li class="active">Collection List</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
           
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Collection Report</h3> <span id="total" class="badge bg-green"></span>     
                         
                </div><!-- /.box-header -->
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
	                 <div class="row">
    	                   <div class="col-md-12">
    	                       
    	                 	<div class="col-md-2">
    		                  <div class="form-group">
    		                    <div class="input-group">
    		                       <button class="btn btn-default btn_date_range" id="rpt_payment_date">
    							  <!-- <input id="rpt_payments"  name="rpt_payment" type="hidden" value="" />-->
    							    <span  style="display:none;" id="rpt_payments1"></span>
    							    <span  style="display:none;" id="rpt_payments2"></span>
    		                        <i class="fa fa-calendar"></i> Date range picker
    		                        <i class="fa fa-caret-down"></i>
    		                      </button>
    		                    </div>
    		                 </div><!-- /.form group -->
    		                </div>
    		              <?php if($this->session->userdata('branch_settings')==1 && $this->session->userdata('id_branch')==0){?> 			
							<div class="col-md-2">
									<div class="form-group" >
									<label>Select Branch </label>
									<select id="branch_select" class="form-control" style="width:200px;" ></select>
									<input id="id_branch" name="scheme[id_branch]"  type="hidden" value=""/>
								</div>
							</div>
							<?php }else{?>
							<input type="hidden" id="branch_filter"  value="<?php echo $this->session->userdata('id_branch') ?>"> 
							<?php }?>
        		                
        		           <!-- <div class="col-md-2" >
                                <div class="form-group">
                                    <label>Group Name</label>									
                                    <select id="classify_select" class="form-control" style="width:200px; margin-left: 15px !important;"></select>
                                    <input id="id_classifications"  name="id_classification" type="hidden" value="" />
                                </div>
                            </div> -->
    						
    						   <div class="col-md-2">
    								<div class="form-group" >
    									<label>Scheme name</label>									
    									<select id="scheme_select" class="form-control" style="width:200px; margin-left: 15px !important;"></select>
    									<input id="id_schemes"  name="id_scheme" type="hidden" value="" />
    								</div>
    						   </div>
    						   
    						   <div class="col-md-2">
    								<div class="form-group" >
    									<label>Pay Mode</label>									
    									<select id="mode_select" class="form-control" style="width:200px; margin-left: 15px !important;"></select>
    									<input id="id_pay_mode"  name="id_pay_mode" type="hidden" value="" />
    								</div>
    						   </div>
    						   
    						 
    							
    							<div class="col-md-2">
                                    <div class="form-group">
                                        <label> Select Pay Through</label>
                                        <select id="select_pay_mode" class="form-control" style="width:200px;">
                                            <option value="">All</option>
                                            <option value="0">Admin</option>
                                            <option value="2">Online</option>
                                            <option value="3">Admin App</option>
                                            <option value="1">Web App</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-2"> 
									<label></label>
									<div class="form-group">
										<button type="button" id="search_payment_list" class="btn btn-info">Search</button>   
									</div>
							    </div>
    					</div>
	                 </div>
					 </br>
					<!-- <div class="row">
				        <div class="col-md-2">
                            <div class="form-group">
                                <a href="<?php echo base_url('index.php/reports/payment_schemewise');?>" target="_blank"><button class="btn btn-warning">Summary Report</button></a>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <button class="btn btn-warning">Mode Wise Report</button>
                            </div>
                        </div>
					 </div>-->
					 
					 <div class="box box-info stock_details collapsed-box">
						<div class="box-header with-border">
						  <h3 class="box-title">Payment Summary <span class="summery_description"></span></h3>
						  <div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-plus"></i></button>
						  </div>
						</div>
					    <div class="box-body collapse" style="display: none;">
					       <div class="row" style="background: #ecf0f5;">
					           <div class="col-md-4" style="text-align:center;font-weight: bold;">Showroom Collection Mode-wise</div>
					           <div class="col-md-4" style="text-align:center;font-weight: bold;">Online Collection Mode-wise</div>
					       </div>
							<div class="row">
								<div class="box-body col-md-4" id="offline_modewise"></div>
                                <div class="box-body col-md-4" id="online_modewise"></div>
							</div>
						</div>

					</div>
					<div id="modesummary"></div>
                    <div class="row">
                        <div class="box-body">
                             <div class="table-responsive">
                                    <table id="report_payment_daterange" class="table table-bordered table-striped text-center">
                                        
                                        <thead>
                                            
                                            <tr  style="text-transform:uppercase;">
                                                <th width="1%">S.No</th>
                                                <th width="1%">Mobile</th>
                                                <!--<th width="1%">M.No</th>-->
                                                <th width="1%">Recpt.No</th>
                                                <th width="1%">Acc Name</th>
                                                <th width="1%">Ins</th>
                                                <th width="1%">Pay.Date</th>
                                                <!--<th width="1%">Apprl.Date</th>-->
                                                <th width="5%">Mode</th>
                                                <th width="5%">M.Rate</th>
                                               <!-- <th width="5%">Weight(g)</th> -->
                                                <th width="5%">Received.Amt</th>
                                               <!-- <th width="5%">Bonus</th>
                                                <th width="5%">Gross Amount</th> 
                                                <th width="5%">Emp Code</th> -->
                                                <th width="5%">Cost Center</th>
                                                <th width="5%">Paid Through</th>
                                              <!--  <th width="5%">Received Type</th>  -->
                                            </tr>
                                        </thead> 
                                        <tbody> 
                                       
                                        </tbody>
        	                        </table>
                             </div>
                            
                        </div>
                    </div>
				<!-- /.box-body -->
              </div><!-- /.box -->
              	<div class="overlay" style="display:block">
				  <i class="fa fa-refresh fa-spin"></i>
				  </div>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

<!-- / modal -->  
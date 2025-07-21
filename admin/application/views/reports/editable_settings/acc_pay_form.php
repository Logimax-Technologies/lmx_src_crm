 <!-- line added by durga 28/12/2022 to get usertype -->
 <?php 
 
 $username=($this->session->userdata['profile']);
 $sync_settings = $this->config->item('integrationType');

 
 ?>
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
        CRM Queries Master
            <small></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            
            <li class="active">CRM Queries Master</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
      
          <div class="row">
            <div class="col-xs-12">
           
              <div class="box">
			   <div class="box-body">
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

<!-- edit account div row block starts-->

            <div class="box box-info stock_details collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title">Edit Account </h3>
                    <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-plus"></i></button>
                    </div>
                </div>

                <div class="box-body collapse" style="display: none;">
                    <div class="row col-md-12">
                        <div class="col-md-8" id="input_data_acc" style="background: #ecf0f5;">
                            <label id="acc_id_label">Enter Scheme Account Id  </label>
                            <input type="text" name="" id="sch_acc_id" style="margin-left: 20px;" >
                            <button type="submit" style="margin-left: 20px;" id="acc_submit" name="acc_submit" class="btn btn-primary">Submit</button>           
                        </div>
                        <div class="col-md-4  pull-right" data-toggle="buttons">
                            <label class="btn btn-success update_acc" id="update_acc" style="display: none;">
                            <input type="radio" name="upd_acc_btn" value="1"><i class="icon fa fa-check"></i> Update
                            </label>

                            <label class="btn btn-warning" id="cancel_acc"  style="display: none;">
                            <input type="radio" name="cancel_acc_btn" value="2"><i class="icon fa fa-close"></i> Cancel
                            </label>
                        </div>
                    </div>

                    <div class="row col-md-12" id="table_Account">
                        <table id="table_acc_list" class="table table-bordered table-striped text-center">
                            <thead>
                            <tr> 
                                <th>Sch_Acc_Id</th>
                                <th>Customer Mobile</th>
                                <th>Id_Customer</th>
                                <th>Account_Name</th>
                                <th>Account_Number</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>  
                </div>

            </div>                

	
					       
<!-- edit account div row block ends-->		


<!-- edit payment div row block starts-->

<div class="box box-info stock_details collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title">Edit Payment </h3>
                    <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-plus"></i></button>
                    </div>
                </div>

                <div class="box-body collapse" style="display: none;">
                    <div class="row col-md-12">
                        <div class="col-md-8" id="input_data_paymnt" style="background: #ecf0f5;">
                            <label id="pay_id_label">Enter Payment Id  </label>
                            <input type="text" name="" id="pay_id" style="margin-left: 20px;" >
                            <button type="submit" style="margin-left: 20px;" id="pay_submit" name="pay_submit" class="btn btn-primary">Submit</button>
                        </div>
                        <div class="col-md-4  pull-right" data-toggle="buttons">
                            <label class="btn btn-success update_pay" id="update_pay" style="display: none;">
                            <input type="radio" name="upd_pay_btn" value="1"><i class="icon fa fa-check"></i> Update
                            </label>

                            <label class="btn btn-warning" id="cancel_pay" style="display: none;">
                            <input type="radio" name="cancel_pay_btn" value="2"><i class="icon fa fa-close"></i> Cancel
                            </label>
                        </div>
                    </div>
                    
                    <!--<div class="col-md-2">
                        <span class="text-primary" id="payment_settings"></span>
                    </div> -->

                    <div class="row col-md-12" id="table_payment">
                        <table id="table_paymnt_list" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr> 
                                    <th>Pay_Id</th>
                                    <th>Sch_Acc_Id</th>
                                    <th>Payment_Date</th>
                                    <th>Amount</th>
                                    <th>Metal_Rate</th>
                                    <th>Metal_Weight</th>
                                    <th>Receipt Number</th>
                                    <th>Payment Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>  
                </div>

            </div>                

	
					       
<!-- edit payment div row block ends-->	
				       
<!-- edit integrationType =>5 settings (for khimji services) div row block starts-->
<?php if($sync_settings == 5){ ?>
<div class="box box-info stock_details collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title">Acme Integration Edits  </h3>
                    <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-plus"></i></button>
                    </div>
                </div>

                <div class="box-body collapse" style="display: none;">
                    

	            
                    <div class="row col-md-12"> <span class="text-primary" id="khimji_post"></span></div>    
                    <div class="row col-md-12">
                        <div class="col-md-6">
                            <h4 class="box-title">Generate Acc/Rcpt No</h4>
                         <!--   <div class="col-md-3">
                                <label>From Date								
                                <input type="date" id="gen_fromdt">
                                </label>	
    						</div>
                            <div></div>
                            <div class="col-md-3">
                                <label>To Date									
                                <input type="date" id="gen_todt" >
                                </label>
    						</div> -->
                            <div class="col-md-3">
                                <label>Payment Id								
                                <input type="number" id="payId">
                                </label>	
    						</div>
                            <div class="col-md-2 pull-right">
                                <label></label>
                                <button class="btn btn-success pull-right" id="gen_accrcpt"> Generate </button>
    						</div>
                            
                        </div>

                        <div class="col-md-1" style="align:center;border-right: 2px solid dodgerblue;height:100px;"></div>
                        <div class="col-md-5">
                            <h4 class="box-title">Generate Trans-uniq ID</h4>
                            <label>Enter Payment ID</label>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="" id="trans_idpay" >
                            <button class="btn btn-success pull-right" id="gen_transid"> Generate </button>
                        </div>
                    </div> 
                    
                    
                </div>

            </div>                

<?php } ?>	
					       
<!-- edit account div row block ends-->	
                       
				
				
                     <!-- line added by durga 28/12/2022 to get usertype -->
                  <input type="hidden" id="hiddenuserdata" value=<?php echo $username ?> >
                 

                <!-- <div class="overlay" style="display: none;">
                   <i class="fa fa-refresh fa-spin"></i>
                	</div> -->
				
			    
			      <div class="overlay" style="display: none;">
                   <i class="fa fa-refresh fa-spin"></i>
                	</div>
                </div><!-- /.box-body -->
                
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
 
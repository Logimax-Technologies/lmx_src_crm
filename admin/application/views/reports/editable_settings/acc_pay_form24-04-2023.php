<!-- line added by durga 28/12/2022 to get usertype -->
 <?php $username=($this->session->userdata['profile']);?>
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
        Edit Accounts/Payments
            <small></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            
            <li class="active">Edit Acc/Payments</li>
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
			
					       
                <!-- Scheme Account Editing Block -->				       
					       
					     
					       
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Edit Accounts </h3>   
                            </div>
                        </div>
					       
					       
					       
				        <div class="row">
				         
                                    <div class="col-md-12" id="input_data_acc" style="display: block;">
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                
                                                <label id="acc_id_label">Enter Scheme Account Id  </label>
                                                <input type="text" name="" id="sch_acc_id" style="margin-left: 20px;" >
                                                
                                                <button type="submit" style="margin-left: 20px;" id="acc_submit" name="acc_submit" class="btn btn-primary">Submit</button>
                                                                            
                                                
                                            </div>
                                            

                                        </div>
                                       
                                    </div> 
                                    <div class="row">
                                        <div class="col-md-12" id="table_Account">
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
                                    <div class="row">
                                            <div class="col-sm-6">
                                                <div class="pull-right">
                                                
                                                    <div class="form-group">
                                                    <div class="btn-group" data-toggle="buttons">
                                                        <label class="btn btn-success update_acc" id="update_acc" style="display: none;">
                                                        <input type="radio" name="upd_acc_btn" value="1"><i class="icon fa fa-check"></i> Update
                                                        </label>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <!-- <div class="pull-right"> -->
                                                
                                                    <div class="form-group">
                                                        <div class="btn-group" data-toggle="buttons">
                                                            <label class="btn btn-warning" id="cancel_acc"  style="display: none;">
                                                            <input type="radio" name="cancel_acc_btn" value="2"><i class="icon fa fa-close"></i> Cancel
                                                            </label>
                                                        </div>
                                                    </div>
                                                <!-- </div> -->
                                            </div>
                                    </div>
                        </div>

                        <!-- Scheme Account Editing Block  Ends Here-->	

                        <!-- Scheme Payment Editing Block -->	

                        <div class="row">
                            <div class="col-md-12">
                                <h3>Edit Payments </h3>   
                            </div>
				        </div>
                        <div class="row">
                                    <div class="col-md-12" id="input_data_paymnt" style="display: block;">
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                
                                                <label id="pay_id_label">Enter Payment Id  </label>
                                                <input type="text" name="" id="pay_id" style="margin-left: 20px;" >
                                                
                                                <button type="submit" style="margin-left: 20px;" id="pay_submit" name="pay_submit" class="btn btn-primary">Submit</button>
                                                                            
                                                
                                            </div>
                                          
                                        </div>
                                    </div> 
                                    <div class="row">
                                        <div class="col-md-12"  id="table_payment">
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
                                    <div class="row">
                                            <div class="col-sm-6">
                                                <div class="pull-right">
                                                
                                                    <div class="form-group">
                                                    <div class="btn-group" data-toggle="buttons">
                                                        <label class="btn btn-success update_pay" id="update_pay" style="display: none;">
                                                        <input type="radio" name="upd_pay_btn" value="1"><i class="icon fa fa-check"></i> Update
                                                        </label>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <!-- <div class="pull-right"> -->
                                                
                                                    <div class="form-group">
                                                        <div class="btn-group" data-toggle="buttons">
                                                            <label class="btn btn-warning" id="cancel_pay" style="display: none;">
                                                            <input type="radio" name="cancel_pay_btn" value="2"><i class="icon fa fa-close"></i> Cancel
                                                            </label>
                                                        </div>
                                                    </div>
                                                <!-- </div> -->
                                            </div>
                                            
                                            <div class="col-sm-2">
                                            <span class="text-primary" id="payment_settings"></span>
                                            </div>
                                    </div>
                                
                        </div>

                         <!-- Scheme Payment Editing Block Ends Here-->
				
				
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
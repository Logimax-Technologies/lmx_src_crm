<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Reports
       <small>Tobe History</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Reports</a></li>
      <li class="active">Tobe History</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
         
         <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Tobe History List</h3>  <span id="total_count" class="badge bg-green"></span>  
           
          </div>
           <div class="box-body">  
            <div class="row">
                <div class="col-md-offset-2 col-md-8">  
                <div class="box box-default">  
                 <div class="box-body">  
                     <div class="row">
                          <?php if($this->session->userdata('branch_settings')==1 && $this->session->userdata('id_branch')==0){?>
                          <div class="col-md-3"> 
                              <div class="form-group tagged">
                                  <label>Select Branch</label>
                                  <select id="branch_select" class="form-control branch_filter"></select>
                              </div> 
                          </div> 
                          <?php }else{?>
                              <input type="hidden" id="branch_filter"  value="<?php echo $this->session->userdata('id_branch') ?>"> 
                              <input type="hidden" id="branch_name"  value="<?php echo $this->session->userdata('branch_name') ?>"> 
                          <?php }?> 
                          
                          
                          <div class="col-md-3"> 
                              <div class="form-group tagged">
                                  <label>Select Customer</label>
                                  <select id="select_customer" class="form-control"></select>
                              </div> 
                          </div> 
                          
                          <div class="col-md-3"> 
                              <div class="form-group">    
                                  <label>Date</label> 
                                  <?php   
                                      $fromdt = date("d/m/Y", strtotime('-1 days'));
                                      $todt = date("d/m/Y");
                                  ?>
                                     <input type="text" class="form-control pull-right dateRangePicker" id="dt_range" placeholder="From Date -  To Date" value="<?php echo $fromdt.' - '.$todt?>" readonly="">  
                              </div> 
                          </div>
                          <div class="col-md-2"> 
                              <label></label>
                              <div class="form-group">
                                  <button type="button" id="tobe_history_search" class="btn btn-info">Search</button>   
                              </div>
                          </div>
                      </div>
                   </div>
                 </div> 
                </div> 
             </div> 
          
             <div class="row">
                  <div class="col-xs-12">
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
                  </div>
             </div>
        
             <div class="row">
                 <div class="col-md-12">
                     <div class="table-responsive">
                   <table id="tobe_history_list" class="table table-bordered table-striped text-center">
                      <thead>
                        <tr>
                          <th>Branch</th>
                          <th>Customer</th>
                          <th>Mobile</th>
                          <th>Bill No</th>
                          <th>Bill Date</th>
                          <th>Bill Amount</th>
                          <th>Paid Amount</th>
                          <th>Discout Amount</th>
                          <th>Purchase Amount</th>
                          <th>Balance Amount</th>
                        </tr>
                      </thead> 
                       <tbody> 
                  </tbody>
                         
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




 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Karigar Approval List
            <small></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Master</a></li>
            <li class="active">Karigar Approval list</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
           
              <div class="box box-primary">
              <div class="box-header with-border">
                  <h3 class="box-title">Karigar Approval List</h3> 
                          
                </div><!-- /.box-header -->
				
                <div class="box-body">
                  <div class="row">

                  <div class="col-md-3"> 
                      <div class="form-group tagged">
                          <label>Approval For :<span class="error"></span></label>
                          <div class="form-group">
                          <input type="radio" class = "aprroval_for" id="wast_approval" name="app[approval_for]"      value="0" checked><label for="">&nbsp;&nbsp;Wastage</label>&nbsp;&nbsp;
    											<input type="radio" class = "aprroval_for"  id="stn_approval"  name="app[approval_for]"     value="1" ><label for="">&nbsp;&nbsp;Stone</label>&nbsp;&nbsp;
                          <input type="radio" class = "aprroval_for"  id="charge_approval"  name="app[approval_for]"  value="2" ><label for="">&nbsp;&nbsp;Charges</label>
                      </div> 
                      </div> 
                  </div>

                  </div> 
                <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-6">  
                                <div class="box box-primary">  
                                    <div class="box-body"> 
                                        <div class="box-header with-border">
                                          <h3 class="box-title">Karigar Details</h3> 
                                        </div><!-- /.box-header -->
                                        <div class="row">
                                            <div class="col-md-4"> 
                                                <div class="form-group tagged">
                                                    <label>Select karigar<span class="error"></span></label>
                                                    <select id="karigar_sel" class="form-control" style="width:100%;"></select>
                                                </div> 
                                            </div>
                                            <div class="col-md-2"> 
                                                <label></label>
                                                    <div class="form-group">
                                                        <button type="button" id="approval_search" class="btn btn-info">Search</button>   
                                                    </div>
                                            </div>
                                        </div>
                                        <!-- <div class="row" id="delete_row">
                                            <div class="col-md-2"> 
                                                <label></label>
                                                    <div class="form-group">
                                                        <button type="button" id="delete_product_mapping" class="btn btn-danger">Delete</button>   
                                                    </div>
                                            </div>
                                        </div> -->
                                    </div>
                                </div> 
                            </div>
                            
                            <div class="col-md-6">  
                                <div class="box box-primary">  
                                    <div class="box-body"> 
                                         <div class="box-header with-border">
                                          <h3 class="box-title">Status Details</h3> 
                                        </div><!-- /.box-header -->
                                        <div class="row">
                                            <div class="col-md-4"> 
                                                <div class="form-group tagged">
                                                    <label>Select Status</label>
                                                    <select id="select_status" class="form-control" style="width:100%;">
                                                        <option value="1">Approved</option>
                                                        <option value="2">Rejected</option>
                                                    </select>
                                                </div> 
                                            </div>
                                            <div class="col-md-2"> 
                                                <label></label>
                                                    <div class="form-group">
                                                        <button type="button" id="status_submit" class="btn btn-success">Submit</button>   
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>
                   </div> 
                
                  <div class="table-responsive wastages" style="display:block;">
                    <table id="karigar_wastage_list" class="table table-bordered table-striped text-center">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Karigar</th>
                          <th>Product</th>
                          <th>Design</th>
                          <th>Sub Design</th>
                          <th>Wastage</th>
                          <th>MC Type</th>
                          <th>MC Value</th>
                          <th>Pur Touch</th>
                        </tr>
                      </thead>
                      <tbody><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tbody>
                    </table>
                  </div> 

                  <div class="table-responsive stones" style="display:none;">
                    <table id="karigar_stones_list" class="table table-bordered table-striped text-center">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Karigar</th>
                          <th>Stone Type</th>
                          <th>Stone Name</th>
                          <th>Uom</th>
                          <th>Calc Type</th>
                          <th>Rate</th>
                        </tr>
                      </thead>
                      <tbody><td></td><td></td><td></td><td></td><td></td><td></td></tbody>
                    </table>
                  </div> 

                  <div class="table-responsive charges" style="display:none;">
                    <table id="karigar_charges_list" class="table table-bordered table-striped text-center">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Karigar</th>
                          <th>Charge Name</th>
                          <th>Charge Value</th>
                        </tr>
                      </thead>
                      <tbody><td></td><td></td><td></td><td></td></tbody>
                    </table>
                  </div> 
                 
                </div><!-- /.box-body -->
                 <!-- <div class="overlay" style="display:none">
				  <i class="fa fa-refresh fa-spin"></i> -->
				</div>
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      

 



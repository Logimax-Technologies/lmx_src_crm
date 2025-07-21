  <!-- Content Wrapper. Contains page content -->



  <div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>



    <section class="content-header">

      <h1>

        Reports

        <small>Sales Report</small>

      </h1>

      <ol class="breadcrumb">

        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

        <li><a href="#">Reports</a></li>

        <li class="active">Sales Report</li>

      </ol>

    </section>



    <!-- Main content -->

    <section class="content">

      <div class="row">

        <div class="col-xs-12">



          <div class="box box-primary">

            <div class="box-header with-border">

              <h3 class="box-title">Employee Sales Referrals & Sales Return</h3> <span id="total_count" class="badge bg-green"></span>



            </div>

            <div class="box-body">

              <div class="row">

                <div class="col-md-12">

                  <div class="box box-default">

                    <div class="box-body">

                      <div class="row">

                        <?php if ($this->session->userdata('branch_settings') == 1 && $this->session->userdata('id_branch') == 0) { ?>

                          <div class="col-md-2">

                            <div class="form-group tagged">

                              <label>Select Branch</label>

                              <select id="branch_select" class="form-control branch_filter"></select>

                            </div>

                          </div>

                        <?php } else { ?>

                          <input type="hidden" id="branch_filter" value="<?php echo $this->session->userdata('id_branch') ?>">

                        <?php } ?>



                        <div class="col-md-2">

                          <div class="form-group">

                            <label>Date</label>

                            <?php

                            $fromdt = date("d/m/Y");

                            $todt = date("d/m/Y");

                            ?>

                            <input type="text" class="form-control pull-right dateRangePicker" id="dt_range" placeholder="From Date -  To Date" value="<?php echo $fromdt . ' - ' . $todt ?>" readonly="">

                          </div>

                        </div>

                        <!--<div class="col-md-2"> 

                            <label>Group By</label>

                            <select id="est_ref_group_by" class="form-control" style="width:100%;"></select>

                        </div>-->



                        <div class="col-md-2">

                          <label>Select Metal</label>

                          <select id="metal" class="form-control" style="width:100%;" multiple></select>

                        </div>



                        <div class="col-md-2">

                          <label>Select Category</label>

                          <select id="category" class="form-control" style="width:100%;" multiple></select>

                        </div>



                        <div class="col-md-2">

                          <label>Select Product</label>

                          <select id="prod_select" class="form-control" style="width:100%;" multiple></select>

                        </div>

                        <div class="col-md-2">

                          <label>Select Report Type</label>

                          <select id="id_report_type" class="form-control" style="width:100%;">

                            <option value="1">Detailed</option>

                            <option value="2">Summary</option>

                          </select>

                        </div>
                      </div>
                      <div class="row">

                        <div class="col-md-2">

                          <label></label>

                          <div class="form-group">

                            <button type="button" id="emp_ref_search" class="btn btn-info">Search</button>

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

                  if ($this->session->flashdata('chit_alert')) {

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

              <div class="box box-info stock_details">

                <div class="box-header with-border">

                  <h3 class="box-title">Sales Details</h3>

                  <div class="box-tools pull-right">

                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>

                  </div>

                </div>

          

                <div class="box-body">

                  <div class="row">

                    <div class="box-body">

                      <div class="table-responsive ref_list_sum" style="Display:none;">

                        <table id="ref_list_sum" class="table table-bordered table-striped text-center">

                          <thead>
                            <tr>
                              <td colspan="4"></td>
                              <td colspan="5"><b>SALES</b></td>
                              <td colspan="5"><b>SALES RETURN </b></td>
                              <td colspan="5"><b>TOTAL<b></td>

                            </tr>

                            <tr style="text-transform:uppercase;">

                              <th>Branch Name</th>
                              <th>Employee Name</th>
                              <th>Employee Code</th>
                              
                              <th>Pcs</th>
                              <th>GWT</th>
                              <th>LWt</th>
                              <th>NWt</th>
                              <th>DIA WT</th>
                              <th>Amount</th>

                              <th>GWT</th>
                              <th>LWT</th>
                              <th>NWt</th>

                              <th>DWT</th>
                              <th>AMOUNT</th>


                              <th>GWT</th>
                              <th>LWT</th>
                              <th>NWt</th>

                              <th>DWT</th>
                              <th>AMOUNT</th>





                            </tr>

                          </thead>

                          <tbody></tbody>

                          <tfoot></tfoot>

                        </table>

                      </div>

                      <div class="table-responsive ref_list_det">

                        <table id="ref_list_det" class="table table-bordered table-striped text-center">

                          <thead>

                            <tr>
                            <td colspan="2"></td>
                              <td colspan="6"><b>SALES</b></td>
                              <td colspan="5"><b>SALES RETURN </b></td>
                              <td colspan="5"><b>TOTAL<b></td>
                            </tr>

                            <tr style="text-transform:uppercase;">
                              <th>S.No</th>
                              <th>Product Name </th>
                              <th>Pcs</th>

                              <th>GWT</th>
                              <th>LWt</th>
                              <th>NWt</th>
                              <th>DIA WT</th>
                              <th>AMOUNT</th>

                              <th>GWt</th>
                              <th>LWT</th>
                              <th>NWt</th>
                              <th>DWT</th>
                              <th>AMOUNT</th>

                              <th>GWT</th>
                              <th>LWT</th>
                              <th>NWt</th>

                              <th>DWT</th>
                              <th>AMOUNT</th>
                            </tr>

                          </thead>

                          <tbody></tbody>

                          <tfoot></tfoot>

                        </table>

                      </div>

                    </div>

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

  <style>
    #ref_list_sum thead th {
    position: sticky !important;
    top: 0;
    background-color: #f2f2f2; /* Adjust the background color as needed */
}

#stc thead th {
    position: sticky !important;
    top: 0;
    background-color: #f2f2f2; /* Adjust the background color as needed */
}
  </style>
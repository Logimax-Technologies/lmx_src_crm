  <!-- Content Wrapper. Contains page content -->

      <div class="content-wrapper">

        <!-- Content Header (Page header) -->

        <section class="content-header">

          <h1>

            Settlement Detail

            <small></small>

          </h1>

          <ol class="breadcrumb">

            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

            <li><a href="#">Payment</a></li>

            <li class="active">Settlement Detail List</li>

          </ol>

        </section>



        <!-- Main content -->

        <section class="content">

          <div class="row">

            <div class="col-xs-12">

           

              <div class="box">

                <div class="box-header">

                  <h3 class="box-title">Settlement Detail</h3>      

                          <!-- <a class="btn btn-success pull-right" href="<?php echo base_url('index.php/customer/add'); ?>"><i class="fa fa-user-plus"></i> Add</a> -->

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

                  <div class="table-responsive">

                  <table id="sett_det_list" class="table grid table-bordered table-striped text-center">

                     <thead>

	                      <tr>

	                        <th>ID</th>

	                        <th>Customer</th>

	                        <th>A/c Name</th>

	                        <th>Scheme A/c No</th>

	                        <th>Mobile</th>                                          

	                        <th>Settlement Type</th>
	                                                                   
	                        <th>Adjusted By</th>                                           

	                        <th>Metal Rate (<?php echo $this->session->userdata('currency_symbol')?>)</th> 

	                        <th>Metal Weight (g) </th>                                           

	                        <th>Amount (<?php echo $this->session->userdata('currency_symbol')?>)</th>  

	                        <th>Status</th> 

	                      </tr>

	                    </thead> 

	                    <tbody>



	                    </tbody>

                 <!--   <tfoot>

                      <tr>

                        

                      </tr>

                    </tfoot> -->

                  </table>

                  </div>

                </div><!-- /.box-body -->

               <div class="overlay" style="display:none">

				  <i class="fa fa-refresh fa-spin"></i>

				</div>

              </div><!-- /.box -->

            </div><!-- /.col -->

          </div><!-- /.row -->

        </section><!-- /.content -->

      </div><!-- /.content-wrapper -->

      



   


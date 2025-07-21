<style>

        .stickyBlk {

		    margin: 0 auto;

		    top: 0;

		     max-width: 1200px

		    z-index: 999;

		    background: #fff;

		}

		#bank_deposit_report th:nth-child(2), #bank_deposit_report th:nth-child(3), #bank_deposit_report th:nth-child(5), #bank_deposit_report td:nth-child(2), #bank_deposit_report td:nth-child(3), #bank_deposit_report td:nth-child(5) {

			text-align: right;

		}


		
		

    </style>



  <!-- Content Wrapper. Contains page content -->



      <div class="content-wrapper">

        <!-- Content Header (Page header) -->

        <section class="content-header">

          <h1>

            Reports

			 <small> Bank Deposit Report </small>

          </h1>

          <ol class="breadcrumb">

            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

            <li><a href="#">Reports</a></li>

            <li class="active"> Bank Deposit Report </li>  <span id="total_count" class="badge bg-green"></span>  

          </ol>

        </section>



        <!-- Main content -->

        <section class="content">

          <div class="row">

            <div class="col-xs-12">

               

               <div class="box box-primary">

			    <div class="box-header with-border">

                  <h3 class="box-title"> Bank Deposit Report </h3>

                 

                </div>

                 <div class="box-body">  

                  <div class="row">

				  	<div class="col-md-offset-2 col-md-8">  

	                  <div class="box box-default">  

	                   <div class="box-body">  

						   <div class="row">
				
								<div class="col-md-3"> 

									<div class="form-group">    

										<label>Date</label> 

										<?php   

											$fromdt = date("d/m/Y");

											$todt = date("d/m/Y");

									    ?>

			                   		    <input type="text" class="form-control pull-right dateRangePicker" id="dt_range" placeholder="From Date -  To Date" value="<?php echo $fromdt.' - '.$todt?>" readonly="">  

									</div> 

								</div>

								<!--<div class="col-md-2"> 

									<label>Select Village</label>

									<select id="village_select" class="form-control" style="width:100%;"></select>

								</div>

								<div class="col-md-2"> 

									<label>Select Customer</label>

									<select id="cus_select" class="form-control" style="width:100%;"></select>

								</div>-->

								<div class="col-md-2"> 

									<label></label>

									<div class="form-group">

										<button type="button" id="bank_deposit_search" class="btn btn-info">Search</button>   

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

				   	<div>

						<div class="box-body">

							<div class="row">

								<div class="box-body">

								   <div class="table-responsive">

									  <table id="bank_deposit_report" class="table table-bordered table-striped text-center">

										 <thead>

            							  <tr  style="text-transform:uppercase;">

										  	<th>Date</th>
											
											<th>Opening Balance</th>

											<th>Deposited Amount</th>   

											<th>Type</th>      
											
											<th>Closing Balance</th>

            							  </tr>

		                            </thead> 

		                    <tbody></tbody>

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

      




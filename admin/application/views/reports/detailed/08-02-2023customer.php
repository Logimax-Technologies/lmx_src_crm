<!-- Content Wrapper. Contains page content -->
	
      <div class="content-wrapper">

        <!-- Content Header (Page header) -->

        <section class="content-header">

          <h1>

            Customer Details

            <small></small>

          </h1>

          <ol class="breadcrumb">

            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

            <li><a href="#">Reports</a></li>

            <li class="active">Customer</li>

          </ol>

        </section>



        <!-- Main content -->

        <section class="content">

          <div class="row">

            <div class="col-xs-12">

           

              <div class="box box-primary">

                <div class="box-header">

              <!--    <h3 class="box-title">Customer List</h3>    -->  

                           <!-- print pdf-->
					<input  hidden id="company_name" value="<?php echo $print['company_name']; ?>" >
					<input hidden id="branch_name" value="<?php  echo $print['branch_name'];; ?>" >
					<input hidden id="report_name" value="Customer List">

                </div> <!-- /.box-header -->

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

                  <table id="" class="table table-bordered table-striped text-center grid">

                    <thead>

                      <tr>

                        <th>SNO</th>

                        <th>Customer Name</th> 

                        <th>Mobile</th>                       

                      <!--  <th>Type</th> >-->

                        <th>Reg Date</th>
						
						<th>Reg Through</th>
						
					   <!-- <th>Closed A/c</th>             
                       
					    <th>Closing Balance</th>
						
						<th>Closed Date</th>-->    
						
                        <th>Profile</th>

                        <th>Active</th> 
						
                      </tr>

                    </thead>
                    <tbody>

                    <?php 

                    if(isset($customer)) { 

                            $sno=1;   	

                    foreach($customer as $account)

{

                     ?>

                      <tr>

                        <td style="text-align:left;"><?php echo $sno++;?></td>

                      <td style="text-align:left;"><?php echo $account['name'];?></td>

                      <td style="text-align:left;"><?php echo $account['mobile'];?></td>

                    <!--  <td><?php echo $account['is_new'];?></td>  -->                     

                        <td style="text-align:left;"><?php echo $account['date_add'];?></td>

                        <td style="text-align:left;"><?php echo $account['reg_by'];?></td>
					<!-- <td><?php echo $account['date_add'];?></td>

					<td><?php echo $account['closing_balance'];?></td>
					  
					<td><?php echo $account['closing_date'];?></td> -->
					<td style="text-align:left;"><?php echo $account['profile_complete'];?></td>

					<td style="text-align:left;"><?php echo $account['active'];?></td>



                      

                      </tr>

                      <?php } } ?>

                   </tbody>
					
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
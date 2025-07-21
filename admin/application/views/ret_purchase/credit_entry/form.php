<!-- Content Wrapper. Contains page content -->

      <style>

.remove-btn {

    margin-top: -168px;

    margin-left: -38px;

    background-color: #e51712 !important;

    border: none;

    color: white !important;

}



.sm {

    font-weight: normal;

}

}

      </style>

      <div class="content-wrapper">

          <!-- Content Header (Page header) -->

          <section class="content-header">

              <h1>

                  Credit/Debit Entry

              </h1>

              <ol class="breadcrumb">

                  <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

                  <li><a href="#">purchase</a></li>

                  <li class="active">Credit/Debit Entry</li>

              </ol>

          </section>



          <!-- Main content -->

          <section class="content order">



              <!-- Default box -->

              <div class="box box-primary">

                  <div class="box-header with-border">

                   </div>

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

    

                      <input id="crdrid" name="credit[crdrid]" type="hidden" value="<?php echo set_value('credit[crdrid]', $credit['crdrid']); ?>" />



                      <div class="row">

                          <label for="" class="col-md-2 col-md-offset-3">Supplier<span class="error"> *</span></label>

						  <div class="col-md-2">

						  <div class="input-group">

                          <select id="select_karigar" name="credit[karigar]" class="form-control select_karigar" required><span class="error"> *</span></select>

                        </div>

						</div>

                      </div>



                      <br>



                      <div class="row">

                          <label for="" class="col-md-2 col-md-offset-3"> Type</label>

						  <div class="col-md-3">

                         <div class="input-group">

                         <input type="radio"  name="credit[accountto]" id="accountto1" value="1" checked=""> Supplier &nbsp;&nbsp;



                          <input type="radio" name="credit[accountto]" id="accountto2" value="2" > Smith  &nbsp;



                           <input type="radio" name="credit[accountto]" id="accountto3" value="3"> Approvals  &nbsp;&nbsp;                              </div>

						</div>

                      </div>

                       <br>



                    <div class="row">

                          <label for="tire_minimum_required" class="col-md-2 col-md-offset-3">Amount<span

                                  class="error">*</span> </label>



                          <div class="col-md-3">

                              <div class="input-group">

                                     <div class="input-group" >

                                     <input class="form-control" id="trans_amount" name="credit[transamount]" type="number" placeholder="Enter Amount" value=""/>

						 			    <span class="input-group-btn">

						 			        <select class="form-control" name ="credit[transtype]" id="transtype" style="width:100px;">

                                             <option value="1">Credit</option>

                                             <option value="2">Debit</option>

						 			        </select>

						 			    </span>

									</div>

                              </div>

                          </div>

                      </div>

                      <br>

                      <div class="row">

                          <label for="" class="col-md-2 col-md-offset-3">Narration</label>

						  <div class="col-md-2">

                         <div class="input-group">

                             <textarea name="credit[naration]" id="naration" class="form-control" rows="5" cols="100" required> </textarea>

                              </div>

							  </div>

                      </div>

					  <br>

					



                  <p class="hepl-block"></p>

                  <div class="row">

                      <br />

                          <div class="col-xs-offset-5">

                              <button class="btn btn-primary" id="save_credit_entry">Save</button>

                              <button type="button" class="btn btn-default btn-cancel" id="cancel_bill_edit">Cancel</button>

                          </div> <br />



                  </div>

              </div> <!-- box-body-->

              <div class="overlay" style="display:none">

                  <i class="fa fa-refresh fa-spin"></i>

              </div>

      </div> <!-- Default box-->

      <?php echo form_close();?>



      <!-- /form -->

      </section>

      </div>

      </div>
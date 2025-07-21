<div class="content-wrapper">
    <section class="content-header">
        <h1>
           Non Tag Receipt
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                    </div>
                    <div class="box-body">
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
                        <div class="row">
                            <div class="col-md-offset-2 col-md-8">
                                <div class="box box-default">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="pull-right">
                                                <?php if($access['add']){?>
                                                <a class="btn btn-success pull-right" id="add_Order" href="<?php echo base_url('index.php/admin_ret_purchase/nontag_receipt/add'); ?>"><i class="fa fa-plus-circle"></i> Add</a>
                                                 <?php }?>
                                            </div>
                                            <div class="col-md-3"> 
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <br>
                                                    <button class="btn btn-default btn_date_range" id="rpt_payment_date">
                                                        <span  style="display:none;" id="rpt_payments1"></span>
                                                        <span  style="display:none;" id="rpt_payments2"></span>
                                                        <i class="fa fa-calendar"></i> Date range picker
                                                        <i class="fa fa-caret-down"></i>
                                                    </button>
                                                    </div>
                                                </div><!-- /.form group -->
                                            </div>
                                            <div class="col-md-2">
                                                <label></label>
                                                <div class="form-group">
                                                    <button type="button" id="nontag_receipt_search" class="btn btn-info">Search</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="nontag_receipt_list" class="table table-bordered table-striped text-center">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="9%">NonTag Receipt No</th>
                                        <th width="9%">Lot No</th>
                                        <th width="9%">Branch</th>
                                        <th width="9%">Section</th>
                                        <th width="9%">Product</th>
                                        <th width="9%">Design</th>
                                        <th width="9%">Sub Design</th>
                                        <th width="9%">Pcs</th>
                                        <th width="9%">Grs Wt</th>
                                        <th width="9%">Less Wt</th>
                                        <th width="9%">Net Wt</th>
                                        <th width="9%">Remark</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div><!-- /.box-body -->
                    <div class="overlay" style="display:none">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div><!-- /.col -->
            </div><!-- /.row -->
    </section>
</div><!-- /.content-wrapper -->

<!-- modal -->      
<div class="modal fade" id="confirm_cancel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Cancel Bill</h4>
      </div>
        <div class="modal-body">
            <strong>Are you sure! You want to Cancel This Entry?</strong>
            <p></p>
            <div class="row">
                <div class="col-md-12 bill_remarks">
                    <label>Remarks<span class="error">*</span></label>
                    <input type="hidden" id="id_supplier_rate_cut" name="">
                    <textarea class="form-control" id="conversion_cancel_remark" placeholder="Enter Remarks"  rows="5" cols="10"> </textarea>
                </div>
            </div><p></p>
            
        </div>
      <div class="modal-footer">
      	<button class="btn btn-danger" type="button" id="rate_conversion_cancel" disabled>Cancel</button>
      </div>
    </div>
  </div>
</div>  
<!-- / modal -->      
<!-- / modal -->  
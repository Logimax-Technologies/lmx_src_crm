<!-- Content Wrapper. Contains page content -->
<style>
    .remove-btn {
        margin-top: -168px;
        margin-left: -38px;
        background-color: #e51712 !important;
        border: none;
        color: white !important;
    }

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        margin: 0;
    }
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Smith / Company Opening Balance
        </h1>
    </section>
    <!-- Main content -->
    <section class="content product">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <form id="smth_cmp_op_bal">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <div class="form-group">
                                                <div class="input-group ">
                                                    <label type="text">O/P Balance For</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="radio" value="1" name="smth_cmpy_stk[stock_type]" id="cmpy_stock"> Company Stock</input>
                                        </div>
                                        <div class="col-md-4">

                                            <input type="radio" value="2" name="smth_cmpy_stk[stock_type]" id="smth_stock" checked> Smith Stock</input>
                                        </div>
                                    </div>


                                    <div class="row">

                                        <div class="col-xs-4">

                                            <div class="form-group">

                                                <div class="input-group ">

                                                    <label type="text">Op Date</label>

                                                </div>

                                            </div>

                                        </div>

                                        <div class="col-md-4">

                                            <input class="form-control" readonly type="text" data-date-format="dd-mm-yyyy" value=<?= date("d-m-Y") ?> name="smth_cmpy_stk[date]" id="cmpy_opening_date"></input>

                                        </div>


                                    </div>

                                    <div class="row smith_type">
                                        <div class="col-xs-4">
                                            <div class="form-group">
                                                <div class="input-group ">
                                                    <label type="text">Smith Type</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="radio" value="1" name="smth_cmpy_stk[smith_type]" id="supplier" checked> Supplier</input>
                                            <input type="radio" value="2" name="smth_cmpy_stk[smith_type]" id="smith"> Smith</input>
                                            <input type="radio" value="3" name="smth_cmpy_stk[smith_type]" id="approval_supplier"> Approval Supplier</input>
                                            <input type="radio" value="4" name="smth_cmpy_stk[smith_type]" id="stone_supplier"> Stone Supplier</input>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <div class="form-group">
                                                <div class="input-group ">
                                                    <label type="text">Balance Type</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="radio" value="1" name="smth_cmpy_stk[bal_type]" id="metal_balance" checked> Metal</input>
                                        </div>
                                        <div class="col-md-4">

                                            <input type="radio" value="3" name="smth_cmpy_stk[bal_type]" id="ornament_balance"> Diamond/Stone</input>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <div class="form-group">
                                                <div class="input-group ">
                                                    <label type="text">Metal Type</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="radio" value="1" name="smth_cmpy_stk[metal_type]" id="" checked> Oranment</input>
                                            <input type="radio" value="2" name="smth_cmpy_stk[metal_type]" id="">Old Metal</input>
                                        </div>
                                    </div>

                                    <div class="row smth_stock">
                                        <div class="col-xs-4">
                                            <div class="form-group">
                                                <div class="input-group ">
                                                    <label type="text">Select Karigar</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="form-group">
                                                <div class="input-group ">
                                                    <select id="select_karigar" class="form-control" name="smth_cmpy_stk[id_karigar]" style="width:100%;"></select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-xs-4">
                                            <div class="form-group">
                                                <div class="input-group ">
                                                    <label type="text">Select Metal</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="form-group">
                                                <div class="input-group ">
                                                    <select id="select_metal" class="form-control" name="smth_cmpy_stk[id_metal]" style="width:100%;"></select>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row category">
                                        <div class="col-xs-4">
                                            <div class="form-group">
                                                <div class="input-group ">
                                                    <label type="text">Select Category</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <select id="select_category" class="form-control" name="smth_cmpy_stk[id_ret_category]" style="width:100%;"></select>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row old_metal_category" style="display:none;">
                                        <div class="col-xs-4">
                                            <div class="form-group">
                                                <div class="input-group ">
                                                    <label type="text">Select Old Metal Category</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <select id="oldcategory" class="form-control" name="smth_cmpy_stk[old_metal_category]" style="width:100%;"></select>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <div class="form-group">
                                                <div class="input-group ">
                                                    <label type="text">Select Product</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="form-group">
                                                <div class="input-group ">
                                                    <select id="select_product" class="form-control" name="smth_cmpy_stk[id_product]" style="width:100%;"></select>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" style="display:none;">
                                        <div class="col-xs-4">
                                            <label type="text">Pieces</label>
                                        </div>
                                        <div class="col-xs-4">
                                            <input class="form-control op_pcs" name="smth_cmpy_stk[op_pcs]" placeholder="Pieces" type="number"></input>
                                        </div>
                                    </div>

                                    <div class="row smth_stock_wt" style="display:none;">
                                        <div class="col-xs-4">
                                            <label type="text">Gross Weight</label>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="input-group" style="width:200px;">
                                                <input class="form-control op_wgt" name="smth_cmpy_stk[op_wgt]" placeholder="Weight" type="number"></input>
                                                <span class="input-group-btn">
                                                    <select id="op_uom" class="form-control" name="smth_cmpy_stk[op_uom]" style="width:80px;">
                                                        <option value="1" selected>Gram</option>
                                                        <option value="6">Carat</option>
                                                    </select>
                                                </span>
                                            </div>
                                        </div>
                                    </div></br>

                                    <div class="row smth_stock_wt" style="display:none;">
                                        <div class="col-xs-4">
                                            <label type="text">Net Weight</label>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="input-group" style="width:200px;">
                                                <input class="form-control op_net_wgt" name="smth_cmpy_stk[op_net_wgt]" placeholder="Net Weight" type="number"></input>
                                                <span class="input-group-btn">
                                                    <select id="op_uom" class="form-control" name="smth_cmpy_stk[op_net_uom]" style="width:80px;">
                                                        <option value="1" selected>Gram</option>
                                                        <option value="6">Carat</option>
                                                    </select>
                                                </span>
                                            </div>
                                        </div>
                                    </div></br>


                                    <div class="row smth_stock_wt" style="display:none;">

                                        <div class="col-xs-4">

                                            <label type="text">Dia Weight</label>

                                        </div>

                                        <div class="col-xs-4">

                                        <div class="input-group"style="width:200px;">

                                            <input class="form-control op_dia_wgt" name="smth_cmpy_stk[op_dia_wgt]" placeholder="Dia Weight" type="number"></input>

                                            <span class="input-group-btn">

                                                <select id="op_dia_uom" class="form-control" name="smth_cmpy_stk[op_dia_uom]"  style="width:80px;">

                                                    <option value="1" selected>Gram</option>

                                                    <option value="6">Carat</option>

                                                </select>

											</span>

                                        </div>

                                        </div>

                                    </div></br>

                                    <div class="row smth_stock_wt" style="display:none;">
                                        <div class="col-xs-4">
                                            <label type="text">Pure Weight</label>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="input-group" style="width:200px;">
                                                <input class="form-control op_ure_wgt" name="smth_cmpy_stk[op_pure_wgt]" placeholder="Pure Weight" type="number"></input>

                                            </div>
                                        </div>
                                    </div></br>

                                    <div class="row smth_stock_wt_type" style="display:none;">
                                        <div class="col-xs-4">
                                            <div class="form-group">
                                                <div class="input-group ">
                                                    <label type="text">Type</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="radio" value="1" name="smth_cmpy_stk[wt_receipt_type]" checked> Credit</input>
                                        </div>
                                        <div class="col-md-2">

                                            <input type="radio" value="2" name="smth_cmpy_stk[wt_receipt_type]"> Debit</input>
                                        </div>
                                    </div>

                                    <div class="row smth_stock_amt">
                                        <div class="col-xs-4">
                                            <label type="text">Value</label>
                                        </div>
                                        <div class="col-xs-4">
                                            <input class="form-control op_amt" name=smth_cmpy_stk[op_amt] placeholder="Amount" type="number"></input>
                                        </div>
                                    </div></br>

                                    <div class="row smth_stock_amt">
                                        <div class="col-xs-4">
                                            <div class="form-group">
                                                <div class="input-group ">
                                                    <label type="text">Type</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="radio" value="1" name="smth_cmpy_stk[amt_receipt_type]" checked> Credit</input>
                                        </div>
                                        <div class="col-md-2">

                                            <input type="radio" value="2" name="smth_cmpy_stk[amt_receipt_type]"> Debit</input>
                                        </div>
                                    </div></br>

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label type="text">Remark</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <textarea rows="5" cols="10" name=smth_cmpy_stk[remark] class="form-control" placeholder="Enter Here....." type="text"></textarea rows="50">

                                        </div>
                                    </div></br>
                                </div>
                                <!-- <div class="col-md-6">


                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label type="text">Net Wt</label>
                                        </div>
                                        <div class="col-xs-4">
                                            <input class="form-control op_net_wt" name=smth_cmpy_stk[op_net_wt] placeholder="Weight" type="number"></input>
                                        </div>
                                    </div></br>

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label type="text">Pure Wt</label>
                                        </div>
                                        <div class="col-xs-4">
                                            <input class="form-control op_pure_wt" name=smth_cmpy_stk[op_pure_wt] placeholder="Weight" type="number"></input>
                                        </div>
                                    </div></br>

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label type="text">Rate</label>
                                        </div>
                                        <div class="col-xs-4">
                                            <input class="form-control op_rate" name=smth_cmpy_stk[op_rate] placeholder="Rate" type="number"></input>
                                        </div>
                                    </div></br>






                                </div> -->
                            </div>
                        </div>
                        <div class="row">
      							<div class="box box-default"><br />
      								<div class="col-xs-offset-5">
      									<button type="button" id="smth_cmpy_bal_submit" class="btn btn-primary">Save</button>
      									<button type="button" class="btn btn-default btn-cancel">Cancel</button>

      								</div> <br />
      							</div>
      						</div>
      					</div>
						<div class="overlay" style="display:none">
      						<i class="fa fa-refresh fa-spin"></i>
      					</div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
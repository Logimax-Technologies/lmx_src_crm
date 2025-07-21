      <!-- Content Wrapper. Contains page content -->
    <style>
    	.remove-btn{
			margin-top: -168px;
		    margin-left: -38px;
		    background-color: #e51712 !important;
		    border: none;
		    color: white !important;
		}
		
		
    </style>
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
       

        <!-- Main content -->
        <section class="content order">

          <!-- Default box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Purchase Return</h3>
            </div>
            <div class="box-body">
             <!-- form container --> 
	             <!-- form -->
				<form id="qc_entry_form">
				<div class="row">
				    <div class="col-md-12">
				        <div class="col-md-2">
    	                     <div class="form-group">
    	                       <label>PO Ref No</label>
    							    <select class="form-control" id="select_po_ref_no"></select>
    	                     </div> 
				        </div>
						<div class="col-md-1">
				            <div class="form-group">
				                <br>
				                <button id="search_by_refno" type="button" class="btn btn-primary" >OR</button>
				            </div>
				        </div>
						
				        <div class="col-md-2">
    	                     <div class="form-group">
    	                       <label>Select Supplier</label>
    							    <select class="form-control" id="select_karigar">
									</select>
    	                     </div> 
				        </div>
						<div class="col-md-3">
    	                     <div class="form-group">
    	                       <label>Return reason(*)</label>
    							<div class="form-group" >  
										<input type="radio" id="return_by_damage" name="returnreason" value="1" checked><label for="return_by_damage">Damage</label>
										&nbsp;&nbsp;&nbsp;
								<input type="radio" id="return_by_excess" name="returnreason" value="2" ><label for="return_by_excess">Excess</label>    
									</div>
    	                     </div> 
				        </div>
						<div class="col-md-3">
    	                     <div class="form-group">
    	                       <label>Narration(*)</label>
    							<div class="form-group" >  
									<textarea id="returnnarration" name="returnnarration" rows="4" cols="50"></textarea>
								</div>
    	                     </div> 
				        </div>
				    </div>
				</div>
				
				<div class="row">
					<div class="col-md-12">
					    <div class="table-responsive">
                         <h4>Return Item Details</h4>
						 <table id="item_detail" class="table table-bordered table-striped">
							<thead style="text-transform:uppercase;">
							     
						          <tr>
						            <th width="10%;"><label class="checkbox-inline"><input type="checkbox" id="select_all" name="select_all" value="all"/>All</label></th>  
									<th width="10%;">Supplier</th> 
						            <th width="15%;">Product</th> 
						            <th width="15%;">Design</th> 
						            <th width="10%;">Sub Design</th> 
						            <th width="10%;">Pur Pcs</th> 
						            <th width="10%;">Pur Wt</th> 
						            <th width="10%;">Return Pcs</th> 
						            <th width="10%;">Return Wt</th>
						          </tr>
					         </thead>
					         <tbody></tbody>
					         <tfoot><tr style="font-weight:bold;"><td colspan="5" style="text-align: center;">TOTAL</td><td class="total_pur_pcs"></td><td class="total_pur_gwt"></td><td class="return_pcs"></td><td class="return_wt"></td></tr></tfoot>
						</table>
					    </div>
					</div> 
				</div>	
				
				<div class="row">
					<div class="col-sm-12" align="center">
						<button type="button" id="return_po_items_submit" class="btn btn-primary" >Save</button> 
						<button type="button" class="btn btn-default btn-cancel">Cancel</button>
					</div>
				</div>
				
				<p class="help-block"></p>
 
				  <?php echo form_close();?>
	           </div>  
	            
	           <div class="overlay" style="display:none">
				  <i class="fa fa-refresh fa-spin"></i>
				</div>
	       </div>  
        </section>
</div>
<!-- Content Wrapper. Contains page content -->
<style type="text/css">
    .multiselect-container .multiselect-search {
        width: 100px;
    }
    .table-responsive { overflow: unset; }
    .del_img {
        cursor: pointer;
        text-decoration: underline;
        color: blue;
    }
    .images_container {
        display: inline-block;
    }

    .order_images_new{
    position: absolute;
    z-index: 1000;
    opacity: 0;
    cursor: pointer;
    right: 0;
    top: 0;
    height: 100%;
    font-size: 24px;
    width: 100%;
  }

  .ord_img
  {
    padding:5px 10px;
    background:#605CA8;
    border:1px solid #605CA8;
    position:relative;
    color:#fff;
    border-radius:2px;
    text-align:center;
    float:left;
    cursor:pointer;
  }
</style>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
		Supplier Catalogue
		</h1>
		<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Master</a></li>
		<li class="active">Supplier Catalogue</li>
		</ol>
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- form -->
		<?php  echo form_open_multipart(($supp['id_supp_catalogue']!=NULL && $supp['id_supp_catalogue']>0 ?'admin_ret_supp_catalog/supplier_catalog/update/'.$supp['id_supp_catalogue']:'admin_ret_supp_catalog/supplier_catalog/save')); ?>

     
		<!-- Default box -->
		<div class="box">
			<div class="box-header with-border">
			<h3 class="box-title">Create Supplier Catalogue</h3>
			</div>
			<div class="box-body">
                <div class="row" >
                        <div class="col-md-offset-1 col-md-10" id='error-msg'></div>
                </div>
				<div class="row">  
					<div class="tab-content col-md-12">
                        <div class='row'>							       

                            <div class='col-sm-3'>
                                <div class='form-group'>
                                    <label for="short_code">Product<span class="error"> *</span></label>
                                    <select id="ret_product" class="form-control"></select>
                                    <!-- <input type='text' class='form-control product_name' placeholder='Product' name='product' required id='product_name' autocomplete='off' value='<?php echo $supp['product_name'] ?>' /> -->
                                    <input type='hidden' id='product_id' name='product_id' class='id_product' required='true' value='<?php echo $supp['product_id'] ?>' /><input type='hidden' id='cat_id' name='cat_id' class='cat_id' required='true' value='<?php echo $supp['cat_id'] ?>' />	
                                </div>
                            </div>

                            <div class='col-sm-3'>
                                <div class='form-group'>
                                    <label for="short_code">Design<span class="error"> *</span></label>		                
                                 <!-- <input type='text' class='form-control design_name' placeholder='Design' name='design' required id='design_name' autocomplete='off' value='<?php echo $supp['design_name'] ?>' /> -->
                                 <input type='hidden' id='design_id' name='design_id' class='id_design' required='true' value='<?php echo $supp['design_id'] ?>' />				
                                    <select id="ret_design" class="form-control"></select>
                                </div> 
                            </div>

                            <div class='col-sm-3'>
                                <div class='form-group'>
                                    <label for="short_code">SubDesign<span class="error"> *</span></label>		                
                                  <!-- <input type='text' class='form-control subdesign_name' placeholder='Sub Design' name='subdesign' required id='subdesign_name' autocomplete='off' value='<?php echo $supp['sub_design_name'] ?>' /> -->
                                      <input type='hidden' id='subdesign_id' name='subdesign_id' class='id_subdesign' required='true' value='<?php echo $supp['id_sub_design'] ?>' />	
                                    <select id="ret_sub_design" class="form-control"></select>
                                </div> 
                            </div>

                            <div class='col-sm-3'>
                                <div class='form-group'>
                                    <label for="short_code"> Design Code</label>

                                    <input type='text' class='form-control design_code' placeholder='Design Code' name='design_code' required id='design_code' autocomplete='off' value='<?php echo $supp['design_code'] ?>' readonly />
                                </div> 
                            </div>

                        </div>

                        <div class='row'>

                            <div class='col-sm-3'>
                                <div class='form-group'>
                                    <label>Status<span class="error"> *</span></label>
                                    <input type="radio" value="1" name="status" id="status_active" <?php if($supp['status'] == 1) { ?> checked <?php } ?> /> <label for="status_active">Active</label>
                                    <input type="radio" value="0" name="status" id="status_inactive" <?php if($supp['status'] == 0) { ?> checked <?php } ?> /> <label for="status_inactive">InActive</label>
                                </div>
                            </div>

                        </div>

                        <hr />

                        <div class="row">
                            <div class='col-sm-12' style="text-align: right;">
                                <div class='form-group'>
                                    <button type="button" id="create_catlog_weight" class="btn btn-success"><i class="fa fa-plus"></i> Add</button>	
                                </div>
                            </div>
                        </div>

                        <div class='row'>	
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table id="weight_details" class="table table-bordered table-striped text-center">
                                        <thead>
                                            <tr>
                                                <th style="width: 7%;">Weight</th>
                                                <th style="width: 7%;">From Wgt</th>
                                                <th style="width: 7%;">To Wgt</th>
                                                <th style="width: 7%;">Purity</th>
                                                <th style="width: 7%;">Size</th>
                                                <th style="width: 10%;">MC Type</th>
                                                <th style="width: 7%;">MC Value</th>
                                                <th style="width: 10%;">Show MC</th>
                                                <th style="width: 7%;">VA(%)</th>
                                                <th style="width: 10%;">Show VA</th>
                                                <th style="width: 7%;">Delivery Duration</th>
                                                <th style="width: 10%;">Show Delivery Duration</th>
                                                
                                                <th style="width: 14%;">Karigar</th>
                                                <th style="width: 14%;">Image</th>
                                                <th style="width: 6%;">Action</th>
                                            </tr>
                                        </thead> 
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div> 
                        </div> 

                        <div class="row">
                            <p class="help-block"></p>
                        </div>

                    <!-- Dynamic image upload-->

                    <div class="modal fade" id="imageModal_new" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Image Upload</h4>
      </div>
      <div class="modal-body">
            <div class="row col-xs-8">
                    <div class="col-md-offset-1">
                        <div class="ord_img">
                            Add Image
                            <input id="sub_design_images" class="order_images_new" name="order_images_new" accept="image/*" type="file" multiple="true">
                            <!-- <form id="subdesign_img_form"> -->
                                 <input type="hidden" name="subdesign[id_sub_design_mapping]" id="id_sub_design_mapping">
                                <input type="hidden" name="subdesign[subdesign_images]" id="subdesign_images" value="">
                                <input type="hidden" name="current_row" id="current_row" value= '0' >
                            <!-- </form> -->
                        </div>
                    </div>
			    </div></br></br></br>
               <div class="row">
                        <div class="col-md-9">
                          <div class="col-md-12 box-items no-paddingwidth" style="max-height: 300px;overflow: auto;">
                    			<div class="col-md-12 col-xs-12 recent_bills no-paddingwidth blog-box">
                    				<div class="col-md-12 col-xs-12">
                    					<div class="col-md-12 col-xs-12 no-paddingwidth container-table">
                    						<table class="table table-bordered" id="design_img_preview">
                    							<thead>
                    							<tr>
                    								<th width="1%">#</th>
                    								<th width="1%">Img</th>
                    								<th width="2%">Action</th>
                    							</tr>
                    							</thead>
                    							<tbody>
                    							    
                    							</tbody>
                    						</table>
                    					</div>
                    				</div>
                    			</div>
                    		</div>
                       </div>
                    </div>
      </div>
      <div class="modal-footer">
      	<button type="button" class="btn btn-success" id="subdesignimg_submit" onClick="save_image()">Save</button>
        <button type="button" class="btn btn-warning" id="close_img_modal"  data-dismiss="modal" >Close</button>
      </div>
    </div>
  </div>
</div>


                        <!-- <div class="row" >
                            <div class="col-sm-12">
                                <div class="box box-default">
                                    <div class="box-header with-border">
                                        <legend><i>Upload Image</i></legend>
                                    </div>
                                    <p class="help-block">Note : Image size shouldn't exceed <b>1 MB</b>.   Upload <b>.jpg or .png </b>images only.</p> 

                                    <input id="s_img" name="supp_cat_image[]" accept="image/*" type="file" multiple onclick="validate_image(this)" >

                                    <div class="images_preview">

                                        <?php 
                                        
                                        $i = 1;

                                        foreach($supp['images'] as $imgs) { ?>

                                        <div class="images_container col-md-3">

                                            <input type="hidden" value="<?php echo $imgs['id_supp_cat_img'] ?>" class="id_supp_cat_img" />

                                            <input type="hidden" value="<?php echo $imgs['id_supp_catalogue'] ?>" class="id_supp_catalogue" />

                                            <span class="img_name"><?php echo $imgs['image'] ?></span>

                                            <div class="img_buttons">

                                                <label for="is_default_<?php echo $i ?>"> Is Default </label> &nbsp; <input type="checkbox" class="is_default" id="is_default_<?php echo $i ?>" <?php if($imgs['is_default'] == 1) { ?> checked <?php } ?> />
                                                
                                                &nbsp; &nbsp; &nbsp;
                                                
                                                <span class="del_img">Delete</span>

                                            </div>

                                            <div class="img">
                                                
                                                <img src="<?php echo $imgs['image'] != ''?base_url('assets/img/supplier/'.$imgs['id_supp_cat_img']."-".$imgs['image']) : base_url('assets/img/no_image.png'); ?>" id="img_preview" alt="Supplier Catalogue Image" width="200" height="200">

                                            </div>

                                        </div>

                                        <?php $i++; } ?>
                                        
                                    </div>

                                </div>
                            </div>
                        </div>  -->
			        <!-- End of Dynamic image upload-->
					</div>
				</div>  
			</div>  <!-- /Tab content --> 
        </div><!-- /.box-body -->
        <div class="overlay" style="display:none">
            <i class="fa fa-refresh fa-spin"></i>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default"><br/>
                    <div class="col-xs-offset-5">
                        <button type="submit" class="btn btn-primary">Save</button> 
                        <button type="button" class="btn btn-default btn-cancel" >Cancel</button>
                    </div> <br/>
                </div> 
            </div>
        </div>    
		</div><!-- /.box -->
		<?php echo form_close(); ?>
      <!-- </form> -->
	</section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script type="text/javascript">
    let weightRange = '<?php echo json_encode($supp['weightRange']) ?>';
</script>
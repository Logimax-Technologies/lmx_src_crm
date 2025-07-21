<!-- Content Wrapper. Contains page content -->
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
		<?php echo form_open_multipart(($supp['id_supp_catalogue']!=NULL && $supp['id_supp_catalogue']>0 ?'admin_ret_supp_catalog/supplier_catalog/update/'.$supp['id_supp_catalogue']:'admin_ret_supp_catalog/supplier_catalog/save')); ?>
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
                                    <input type='text' class='form-control product_name' placeholder='Product' name='product' required id='product_name' autocomplete='off' value='<?php echo $supp['product_name'] ?>' /><input type='hidden' id='product_id' name='product_id' class='id_product' required='true' value='<?php echo $supp['product_id'] ?>' /><input type='hidden' id='cat_id' name='cat_id' class='cat_id' required='true' value='<?php echo $supp['cat_id'] ?>' />	
                                </div>
                            </div>

                            <div class='col-sm-3'>
                                <div class='form-group'>
                                    <label for="short_code">Design<span class="error"> *</span></label>		                
                                    <input type='text' class='form-control design_name' placeholder='Design' name='design' required id='design_name' autocomplete='off' value='<?php echo $supp['design_name'] ?>' /><input type='hidden' id='design_id' name='design_id' class='id_design' required='true' value='<?php echo $supp['design_id'] ?>' />				
                                </div> 
                            </div>

                            <div class='col-sm-3'>
                                <div class='form-group'>
                                    <label for="short_code">SubDesign<span class="error"> *</span></label>		                
                                    <input type='text' class='form-control subdesign_name' placeholder='Sub Design' name='subdesign' required id='subdesign_name' autocomplete='off' value='<?php echo $supp['sub_design_name'] ?>' /><input type='hidden' id='subdesign_id' name='subdesign_id' class='id_subdesign' required='true' value='<?php echo $supp['id_sub_design'] ?>' />				
                                </div> 
                            </div>

                            <div class='col-sm-3'>
                                <div class='form-group'>
                                    <label for="short_code"> Design Code</label>

                                    <input type='text' class='form-control design_code' placeholder='Design Code' name='design_code' required id='design_code' autocomplete='off' value='<?php echo $supp['design_code'] ?>' />
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
                                                <th style="width: 9%;">Weight</th>
                                                <th style="width: 9%;">Purity</th>
                                                <th style="width: 9%;">Size</th>
                                                <th style="width: 7%;">MC Type</th>
                                                <th style="width: 7%;">MC Value</th>
                                                <th style="width: 7%;">Show MC</th>
                                                <th style="width: 7%;">VA(%)</th>
                                                <th style="width: 7%;">Show VA</th>
                                                <th style="width: 7%;">Show Delivery Duration</th>
                                                <th style="width: 7%;">Delivery Duration</th>
                                                <th style="width: 18%;">Karigar</th>
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

                        <div class="row" >
                            <div class="col-sm-12">
                                <div class="box box-default">
                                    <div class="box-header with-border">
                                        <legend><i>Upload Image</i></legend>
                                    </div>
                                    <p class="help-block">Note : Image size shouldn't exceed <b>1 MB</b>.   Upload <b>.jpg or .png </b>images only.</p> 

                                    <input id="s_img" name="supp_cat_image" accept="image/*" type="file" onchange="validate_image(this)" >

                                    <img src="<?php echo $supp['image'] != ''?base_url('assets/img/supplier/'.$supp['id_supp_catalogue']."-".$supp['image']) : base_url('assets/img/no_image.png'); ?>" id="img_preview" alt="Supplier Catalogue Image" width="200" height="200">

                                    <input type="hidden" name="supp_old_image" value="<?php echo $supp['image'] ?>" />

                                </div>
                            </div>
                        </div> 
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
                        <button type="button" class="btn btn-default btn-cancel">Cancel</button>
                    </div> <br/>
                </div> 
            </div>
        </div>    
		</div><!-- /.box -->
		<?php echo form_close();?>
	</section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script type="text/javascript">
    let weightRange = '<?php echo json_encode($supp['weightRange']) ?>';
</script>
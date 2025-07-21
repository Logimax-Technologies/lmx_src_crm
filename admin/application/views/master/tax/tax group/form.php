<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
		Tax Group Master
		</h1>
		<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i>Master</a></li>
		<li class="active">Add Tax Group</li>
		</ol>
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- form -->
		<?php echo form_open_multipart(($tgrp['tgrp_id']!= NULL && $tgrp['tgrp_id']>0 ?'admin_ret_catalog/tgrp/update/'.$tgrp['tgrp_id']:'admin_ret_catalog/tgrp/save')); ?>
		<!-- Default box -->
		<div class="box">
			<div class="box-header with-border">
              <h3 class="box-title">Create Group</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div> 
            <div class="box-body">
			 <div class="row">   
			   <label for="scheme_code" class="col-md-2 col-md-offset-1 ">Tax Group Name
			   <span class="error">*</span></label>
				   <div class="col-md-3">
				   <div class="form-group">
						<input type="hidden" id="edit-id" name="tgrp[tgrp_id]" value="<?php echo set_value('tgrp[tgrp_id]',$tgrp['tgrp_id']); ?>"/>
						<input id="tgrp_name"  class="form-control" name="tgrp[tgrp_name]" type="text" required="true" placeholder="" value="<?php echo set_value('tgrp[tgrp_name]',$tgrp['tgrp_name']); ?>" />
						
					</div>
                    </div>
				 </div> 
			<div class="row">
				<label for="scheme_code" class="col-md-2 col-md-offset-1 ">Active</label>
				 <div class="col-md-3">
					 <div class="form-group">	
						<input type="checkbox" id="tgrp_status" class="status" data-on-text="YES" data-off-text="NO" name="tgrp[tgrp_status]" value="1"  <?php if($tgrp['tgrp_status'] == 1) { ?> checked="true" <?php } ?>/>
						<input type="hidden" id="ad_tgrp_status" name="tgrp[tgrp_status]" value="1"/>
					</div>
				<p></p>
				</div>
			</div>
			<legend>Add Tax Rule</legend>
			   <button id="tax_grp" type="button" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add Rule </button>
			<div class="row">
			  <div class="col-md-offset-1 col-md-10">
					<input  type="hidden" value="0" id="grp_increment"/>	
					<p class="help-block"></p>
					<table id="tax_detail" class="table table-bordered table-striped text-center">
						<thead>
						  <tr>   
							<th>S.NO</th>
							<th>Tax Name</th>
							<th>Calculation</th>
							<th>Add/Sub</th>
							<th>Action</th>		
						  </tr>
						 </thead>
					</table>
			 </div>					
		 </div>		
		<div class="row">
		   <div class="box box-default"><br/>
			  <div class="col-xs-offset-5">
				<button type="submit"  class="btn btn-primary">Save</button> 
				<button type="button" class="btn btn-default btn-cancel">Cancel</button>
				
			  </div> <br/>
			</div>
		  </div> 			
     </div>	
		<?php echo form_close();?> 
	</section><!-- /.content -->
</div><!-- /.content-wrapper -->
   <script type="text/javascript">

  </script>
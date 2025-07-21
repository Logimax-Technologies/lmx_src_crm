<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            
            <small>Scheme Group</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Scheme</a></li>
            <!--<li class="active">Scheme Group</li>-->
          </ol>
        </section>
        <!-- Main content -->
        <section class="content">

          <!-- Default box -->
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Scheme Group</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="">
				<?php echo form_open(($group['id_scheme_group']!=NULL &&  $group['id_scheme_group']>0 ?'account/schemegroup/add'.$group['id_scheme_group']:'scheme_group/save'),array('id'=>'id_scheme_group')) ?>				
				 <div class="row">
				 	<div class="form-group">
                       <label for="chargeseme_name" class="col-md-2 col-md-offset-1 ">Group Code</label>  
                       <div class="col-md-4" style="padding-left: 0px;">
                         <div class="col-md-6">
                         		<div class="form-group">                         			
                         			 <div class="input-group">              				
                         			<input type="text"  value="<?php echo set_value('group[code]',$group['code']); ?>"  name="group[code]"  class="form-control" />
                         		   </div>
                         		</div>
                         </div>                         
      
                         <p class="help-block"></p>
                       	
                       </div>
                    </div>
				 </div>	 
				 <div class="col-sm-4">
              				<div class="form-group">
              					<label for="" ><a  data-toggle="tooltip" title="Select scheme ">Scheme</a><span class="error">*<span></label>
              	         		<input type="hidden" id="scheme_val" name="scheme_val" value="<?php echo set_value('scheme_val',$scheme['id_scheme']); ?>" />
               					<select class="form-control" id="scheme" name="scheme[id_scheme]"></select>
              				</div>
				 </div>
				
                 <div class="row">
				 	<div class="form-group">
                       <label for="chargeseme_name" class="col-md-2 col-md-offset-1 ">Date</label>
                       <div class="col-md-4" style="padding-left: 0px;">
	                       <div class="col-md-6">
		                       <input class="form-control datemask"  data-date-format="dd-mm-yyyy" id="date_add" name="group[date_add]" value="<?php echo set_value('group[date_add]',$group['date_add']); ?>" type="text" />
		                 	    <p class="help-block"></p>
	                       	
                     	  </div>
                     	  <div class="col-md-6">
                     	  </div>
                       </div>
                    </div>
				 </div>
						
				 
				 
				
				<br/>      
				 <div class="row col-xs-12">
				   <div class="box box-default"><br/>
					  <div class="col-xs-offset-5">
						<button type="submit" class="btn btn-primary">Save</button> 
						<button type="button" class="btn btn-default btn-cancel">Cancel</button>
						
					  </div> <br/>
					</div>
				  </div>      
				        	
               </form>              	              	
              </div>
            </div><!-- /.box-body -->
            <div class="box-footer">
              
            </div><!-- /.box-footer-->
          </div><!-- /.box -->
         

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
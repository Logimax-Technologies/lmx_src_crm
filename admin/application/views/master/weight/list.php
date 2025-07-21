  <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Weight
            <small></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Masters</a></li>
            <li class="active">Weight List</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
           
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Weight List</h3>    <span id="total_weights" class="badge bg-green"></span>      
                           <a class="btn btn-success pull-right" id="add_wt" href="#" data-toggle="modal" data-target="#confirm-add" ><i class="fa fa-user-plus"></i> Add</a> 
                </div><!-- /.box-header -->
                <div class="box-body">
                <!-- Alert -->
                <?php 
                	if($this->session->flashdata('chit_alert'))
                	 {
                		$message = $this->session->flashdata('chit_alert');
                ?>
                       <div  class="alert alert-<?php echo $message['class']; ?> alert-dismissable">
	                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	                    <h4><i class="icon fa fa-check"></i> <?php echo $message['title']; ?>!</h4>
	                    <?php echo $message['message']; ?>
	                  </div>
	                  
	            <?php } ?>  
								<div class="row">
									<div class="col-sm-10 col-sm-offset-1">
									<div id="chit_alert"></div>
									 
									</div>
								</div>				
                  <div class="table-responsive">
                  <table id="weight_list" class="table table-bordered table-striped text-center">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Weight</th>
                        
                        <th>Action</th>
                      </tr>
                      
                 </thead>
                 
                  
                    
                     <?php  /*
                     	if(isset($weights)) {                     		
                     	 foreach($weights as $weight)
						{
                      ?>
                       <tr>
                         <td><?php echo $weight['id_weight'];?></td>
                       	 <td><?php echo $weight['weight'];?></td>
                       	
                       	
                       	 <td>
                       	 	<a href="#" data-id="<?php echo $weight['id_weight'];?>" class="btn btn-primary btn-edit"  data-toggle="modal" data-target="#confirm-edit"><i class="fa fa-edit"></i> Edit</a>
                       	<!-- 	<?php echo ($customer['username']!=NULL? anchor('#', '<i class="fa fa-user-plus"></i> Edit A/c', array( 'title'=>"Get Login Account",'class'=>'btn btn-warning','data-target'=>'#create-login','data-toggle'=>'modal','data-href'=>'')):anchor('#', 'Create A/c', array( 'title'=>"Get Login Account",'class'=>'btn btn-warning','data-target'=>'#create-login','data-toggle'=>'modal','data-href'=>'')));?> -->
                       	 	<a href="#" class="btn btn-danger btn-del" data-href="<?php echo base_url('index.php/settings/weight/delete/'.$weight['id_weight']) ?>" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-user-times"></i> Delete</a>
                       	 </td>
                       </tr>
                       <?php } }  */ ?>
                    
                 <!--   <tfoot>
                      <tr>
                        
                      </tr>
                    </tfoot> -->
                  </table>
                  </div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      

<!-- modal -->      
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Delete Weight</h4>
      </div>
      <div class="modal-body">
               <strong>Are you sure! You want to delete this weight record?</strong>
      </div>
      <div class="modal-footer">
      	<a href="#" class="btn btn-danger btn-confirm" >Delete</a>
        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- / modal -->  
<!-- modal -->      
<div class="modal fade" id="confirm-add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Add Weight</h4>
      </div>
      <div class="modal-body">
          <div class="row">
				 	<div class="form-group">
                       <label for="scheme_code" class="col-md-2 col-md-offset-1 ">Weight</label>
                       <div class="col-md-4">
<input type="any" class="form-control" id="weight" name="weight" value="<?php echo set_value('weight',(isset($wt)?$wt:"")); ?>" placeholder="Enter Weight">                  
 <p class="help-block"></p>                       	
                       </div>
                    </div>
				 </div>      
      </div>
      <div class="modal-footer">
      	<a href="#" id="add_weight" class="btn btn-success" data-dismiss="modal" >Add</a>
        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- / modal -->
<!-- modal -->      
<div class="modal fade" id="confirm-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Weight</h4>
      </div>
      <div class="modal-body">
          <div class="row">
				 	<div class="form-group">
                       <label for="scheme_code" class="col-md-2 col-md-offset-1 ">Weight</label>
                       <div class="col-md-4">
                       <input type="hidden" id="edit-id" value="" />
<input type="any" class="form-control" id="ed_weight" name="weight" value="<?php echo set_value('weight',(isset($wt)?$wt:"")); ?>" placeholder="Enter Weight">                   
<p class="help-block"></p>                       	
                       </div>
                    </div>
				 </div>      
      </div>
      <div class="modal-footer">
      	<a href="#" id="update_weight" class="btn btn-success" data-dismiss="modal" >Update</a>
        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- / modal -->      


      <!-- Content Wrapper. Contains page content -->

    <style>

    	.remove-btn{

			margin-top: -168px;

		    margin-left: -38px;

		    background-color: #e51712 !important;

		    border: none;

		    color: white !important;

		}

		.summary_lbl{

			font-weight:bold;

		}

		.stickyBlk {

		    margin: 0 auto;

		    top: 0;

		    width: 100%;

		    z-index: 999;

		    background: #fff;

		}

    </style>

      <div class="content-wrapper">

        <!-- Content Header (Page header) -->

        <section class="content-header">

          <h1>

        	Estimations

            <small>Customer Estimation</small>

          </h1>

          <ol class="breadcrumb">

            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

            <li><a href="#">Estimations</a></li>

            <li class="active">Estimation</li>

          </ol>

        </section>

        <!-- Main content -->

        <section class="content product">

          <!-- Default box -->

          <div class="box box-primary">

            <div class="box-header with-border">

              <h3 class="box-title">Add Estimation</h3>

              <div class="box-tools pull-right">

                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>

                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>

              </div>

            </div>

            <div class="box-body">

			<?php 

				function other_item_array_exist($array, $key, $val) {

					foreach ($array as $item)

						if (isset($item[$key]) && $item[$key] == $val)

							return true;

					return false;

				}

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

             <!-- form container -->

              <div class="row">

	             <!-- form -->

				<!--<?php echo form_open_multipart(( $estimation['estimation_id'] != NULL && $estimation['estimation_id'] > 0 ? 'admin_ret_estimation/estimation/update/'.$estimation['estimation_id']:'admin_ret_estimation/estimation/save')); ?>-->

				<?php 

					$metal_rates = $this->admin_settings_model->metal_ratesDB("last");

				?>

		<form id="est_form">

				<div class="col-sm-12"> 

					<!-- Lot Details Start Here -->

					<div class="row">	

						<?php if($this->session->userdata('branch_settings')==1){?>

				 		<div class="col-sm-2"> 

			 				<div class="form-group">

				 				<label>Branch  <span class="error"> *</span></label>

				 				<div class="input-group ">

				 				<?php if($this->session->userdata('id_branch')==''){?>

				 					<select id="branch_select" required></select>

				 					<input type="hidden" name="estimation[id_branch]" id="id_branch" value="<?php echo set_value('estimation[id_branch]',$estimation['id_branch']); ?>" required="">

				 				<?php }else{?>

				 					<select id="branch_select" disabled></select>

				 					<input type="hidden" name="estimation[id_branch]" id="id_branch" value="<?php echo $this->session->userdata('id_branch'); ?>"> 

				 				<?php }?>

				 				<input type="hidden"  id="branch_settings" value="<?php echo $this->session->userdata('branch_settings'); ?>">

				 				</div>	 

			 				</div>	 

				 		</div>

				 		<?php }?>	

				 			

				 		<div class="col-sm-2"> 

				 			<div class="form-group">

				 				<label>Select Employee<span class="error"> *</span></label>

								<select id="emp_select" class="form-control" style="width:100%;" required></select> 

								<input type="hidden" name="estimation[created_by]" id="id_employee" value="<?php echo set_value('estimation[created_by]',isset($estimation['created_by'])?$estimation['created_by']:NULL); ?>">

								<input type="hidden" id="disc_limit_type">

								<input type="hidden" id="disc_limit">

								<input type="hidden" id="allowed_old_met_pur">

							</div>	    	

				 		</div>



				 		<div class="col-sm-3"> 

			 				<div class="form-group">

				 				<label>Esti For  <span class="error"> *</span></label>

				 				<div class="input-group ">   

				                    <input type="radio" name="estimation[esti_for]" id="type1" disabled value="1" <?php echo $estimation['esti_for'] == 1 ? 'checked' : '' ?>> Customer

				                    <input type="radio" name="estimation[esti_for]" id="type2" disabled value="2" <?php echo $estimation['esti_for'] == 2 ? 'checked' : '' ?>> Branch Transfer

				                    <input type="radio" name="estimation[esti_for]" id="type3" disabled value="3" <?php echo $estimation['esti_for'] == 3 ? 'checked' : '' ?>> Company

					            </div>	 

			 				</div>	 

				 		</div>

						

			    		<div class="col-sm-2"> 

						 	<label>Customer  <span class="error" id="cus_req"> *</span></label>

				 			<div class="form-group" >

					 			<div class="input-group " style="width: 150%;">

									<input class="form-control" id="est_cus_name" name="estimation[cus_name]" type="text"  placeholder="Customer Name / Mobile"  value="<?php echo set_value('estimation[cus_name]',isset($estimation['cus_name'])?$estimation['cus_name']:NULL); ?>" required autocomplete="off"/>

									<input class="form-control" id="cus_id" name="estimation[cus_id]" type="hidden" value="<?php echo set_value('estimation[cus_id]',$estimation['cus_id']); ?>"/>

								    <span class="input-group-btn">

                                        <button type="button" id="add_new_customer" class="btn btn-success"><i class="fa fa-plus"></i></button>

                                    </span>

                                    <span class="input-group-btn">

                                        <button type="button" id="edit_customer" class="btn btn-primary"><i class="fa fa-edit"></i></button>

                                    </span>

									<label style="display:none;" class="per-grm-sale-value"> <?php echo $metal_rates['goldrate_22ct']; ?> </label>

									<label style="display:none;" class="silver_per-grm-sale-value"> <?php echo $metal_rates['silverrate_1gm']; ?> </label>

									<label style="display:none;" class="mjdmagoldrate_22ct"> <?php echo $metal_rates['mjdmagoldrate_22ct']; ?> </label>

									<label style="display:none;" class="mjdmasilverrate_1gm"> <?php echo $metal_rates['mjdmasilverrate_1gm']; ?> </label>

									

									

									<label style="display:none;" class="goldrate_22ct"> <?php echo $metal_rates['goldrate_22ct']; ?> </label>

                    				<label style="display:none;" class="silverrate_1gm"> <?php echo $metal_rates['silverrate_1gm']; ?> </label>

                    				<label style="display:none;" class="goldrate_18ct"> <?php echo $metal_rates['goldrate_18ct']; ?> </label>

                    				<label style="display:none;" class="goldrate_24ct"> <?php echo $metal_rates['goldrate_24ct']; ?> </label>

                    				

									<span id="customerAlert"></span> 

								</div>

								<input id="estimation_id" name="estimation[estimation_id]" type="hidden" value="<?php echo set_value('estimation[estimation_id]',$estimation['estimation_id']); ?>" />

								

							</div> 

							<p id=cus_info></p> 

				 		</div> 



				 	</div> 

					<div class="row stickyBlk">

						<div class="col-md-offset-1 col-md-10 bg-teal"> 

							<div class="form-group">

								<label for="Offer" class="col-md-2">ESTIMATION ITEMS :</label>

								<div class="col-md-2">

									<input type="checkbox" id="select_tag_details" name="tagging" value="1"<?php echo other_item_array_exist($est_other_item['item_details'], 'item_type', 0) ? 'checked' : '' ;?> > <label for="select_tag_details">Tag </label>

								</div>

								<!-- <div class="col-md-2">

									<input type="checkbox" id="select_order_details" name="order" value="1"<?php echo other_item_array_exist($est_other_item['item_details'], 'item_type', 0) ? 'checked' : '' ;?> > <label for="select_order_details">Order </label>

								</div> -->

								<div class="col-md-2">

									<input type="checkbox" id="select_catalog_details" name="catalog" value="1" <?php echo other_item_array_exist($est_other_item['item_details'], 'item_type', 1) ? 'checked' : '' ;?>> <label for="select_catalog_details">Non-Tag/Catalog </label>

								</div>

								<div class="col-md-2">

									<input type="checkbox" id="select_custom_details" name="custom" value="1" <?php echo other_item_array_exist($est_other_item['item_details'], 'item_type', 2) ? 'checked' : '' ;?>> <label for="select_custom_details">Home Bill </label>

								</div>

								<div class="col-md-2">

									<input type="checkbox" id="select_oldmatel_details" name="oldmatel" value="1" <?php echo !empty($est_other_item['old_matel_details']) ? 'checked' : '' ;?>> <label for="select_oldmatel_details">Old Metal </label>

								</div>

						   </div>

						</div>

					</div>

					<p></p>

					<div class="box box-primary tag_details" <?php echo other_item_array_exist($est_other_item['item_details'], 'item_type', 0) ? '' : 'style="display:none;"' ;?>>

						<div class="box-header with-border">

						  <h3 class="box-title">Tagging Details</h3>

						  <!--<div class="box-tools pull-right">

							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>

						  </div>-->

						</div>

						<div class="box-body">

							<div class="row">

							    <div class="col-sm-2">

	    						    <div class="box-tools pull-left"> 

	        						    <div class="form-group" > 

	        					 			<button type="button" id="create_tag_details" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add</button>

	        							</div>

	    							</div>

    							</div>

							    <div class="col-sm-2">

	    						    <div class="box-tools pull-left"> 

	        						    <div class="form-group" > 

	        					 			<div class="input-group" > 

	        								    <input type="text" id="est_tag_scan" class="form-control" placeholder="Tag Scan Code">

	        									<span class="input-group-btn">

	        									    <button type="button" id="tag_search" class="btn btn-default btn-flat"><i class="fa fa-search"></i></button>

	        				                    </span>

	        								</div>

	        								<p id="searchEstiAlert" class="error" align="left"></p>

	        							</div>

	    							</div>

    							</div>

    							<!--<div class="col-sm-2">

	    						    <div class="box-tools pull-left"> 

	        						    <div class="form-group" > 

	        					 			<div class="input-group" > 

	        								    <input type="text" id="est_order" class="form-control" placeholder="Enter Order No">

	        									<span class="input-group-btn">

	        									    <button type="button" id="order_search" class="btn btn-default btn-flat"><i class="fa fa-search"></i></button>

	        				                    </span>

	        								</div>

	        								<p id="searchEstiOrderAlert" class="error" align="left"></p>

	        							</div>

	    							</div>

    							</div>-->

                            

                            	<div class="col-sm-2">

										<div class="box-tools pull-left"> 

											<div class="form-group" > 

												<div class="input-group" > 

												    <span class="input-group-btn">

									 			        <select class="form-control" id="fin_year" style="width:100px;">

									 			            <?php 

									 			            foreach($estimation['financial_year'] as $fin_year)

									 			            {?>

									 			                <option value=<?php echo $fin_year['fin_year_code'];?> <?php echo ($fin_year['fin_status']==1 ?'selected' :'')  ?> ><?php echo $fin_year['fin_year_name'];?></option>

									 			            <?php }

									 			            ?>

									 			        </select>

									 			    </span>

													<input type="text" id="est_order" class="form-control" placeholder="Enter Order No" style="width: 83px;">

													<span class="input-group-btn">

														<button type="button" id="order_search" class="btn btn-default btn-flat"><i class="fa fa-search"></i></button>

													</span>

												</div>

												<p id="searchEstiOrderAlert" class="error" align="left"></p>

											</div>

										</div>

									</div>

									

							</div>

							<div class="row">

								<div class="box-body">

								   <div class="table-responsive">

									 <table id="estimation_tag_details" class="table table-bordered table-striped text-center">

										<thead>

										  <tr>

											<th>Tag Code</th>

											<th>IS Prtly</th>

											<th>Product</th>

											<th>Design</th>

											<th>Order No</th>

											<th>Purity</th>   

											<th>Size</th>   

											<th>Pcs</th>   

											<th>G.Wt</th>   

											<th>L.Wt</th>   

											<th>N.Wt</th>   

											<th>Wastage(%)</th>   

											<th>MC Value</th>   

											<th>Amount</th>

											<th>Action</th>

										  </tr>

										</thead> 

										<tbody>

                                            <?php if($this->uri->segment(3) == 'edit')

                                            {

											$checked=' checked="checked"';

                                            foreach($est_other_item['item_details'] as $ikey => $ival){

                                            if($ival['item_type'] == 0){

                                                $stone_price=0;

    											foreach ($ival['stone_details'] as $data)

    											{

    											    $stone_price+=$data['amount'];

    											} 

                                            echo '<tr>

                                                <td><input class="est_tag_name" type="text" name="est_tag[tag_name][]" value="'.$ival['tag_id'].'" placeholder="Enter tag code" required autocomplete="off"/><input class="est_tag_id" type="hidden" name="est_tag[tag_id][]" value="'.$ival['tag_id'].'" required /></td>

                                                <td><input type="checkbox" class="partial" '.($ival['is_partial']==1 ? $checked:'').' '.($ival['sales_mode']==1 ? 'disabled':'').'><input type="hidden" class="is_partial" name="est_tag[is_partial][]" value="'.$ival['is_partial'].'"></td>

                                                <td><div class="prodct_name">'.$ival['product_name'].'</div><input type="hidden" class="pro_id" name="est_tag[pro_id][]" value="'.$ival['product_id'].'" /></td>

                                                <td><div class="purity">'.$ival['purname'].'</div><input type="hidden" name="est_tag[purity][]" value="'.$ival['purid'].'" /></td>

                                                <td><div class="size">'.$ival['size'].'</div><input type="hidden" name="est_tag[size][]" value="'.$ival['size'].'" /></td>

                                                <td><div class="piece">'.$ival['piece'].'</div><input type="hidden" class="piece" name="est_tag[piece][]" value="'.$ival['piece'].'" /></td>

                                                <td><input class="gwt" name="est_tag[gwt][]" value="'.$ival['gross_wt'].'" type="text" disabled><input type="hidden" class="cur_gwt" name="est_tag[gwt][]" value="'.$ival['gross_wt'].'" /></td>

                                                <td><input type="text" class="lwt" name="est_tag[lwt][]" value="'.$ival['less_wt'].'" disabled/><input type="hidden" name="est_tag[lwt][]" value="'.$ival['less_wt'].'" /></td>

                                                <td><div class="nwt">'.$ival['net_wt'].'</div><input type="hidden" name="est_tag[nwt][]"  class="tot_nwt" value="'.$ival['net_wt'].'" /></td>

                                                <td><div class="wastage">'.$ival['wastage_percent'].'</div><input type="hidden" class="wastage_max_per" name="est_tag[wastage][]" value="'.$ival['wastage_percent'].'" /></td>

                                                <td><div class="mc">'.$ival['mc_value'].'</div></td>

                                                <td><div class="cost">'.$ival['item_cost'].'</div><input class="tag_item_rate" type="hidden" name="est_tag[item_rate][]" value="'.$ival['item_rate'].'" /><input class="sales_value" type="hidden" name="est_tag[cost][]" value="'.$ival['item_cost'].'" /><input class="caltype" type="hidden" name="est_tag[caltype][]" value="'.$ival['calculation_based_on'].'" /><input class="tgi_calculation" type="hidden" name="est_tag[tgi_calculation][]" value="'.$ival['tgi_calculation'].'" /><input class="tax_percentage" type="hidden" name="est_tag[tax_percentage][]" value="'.$ival['tax_percentage'].'" /><input class="stone_price" type="hidden" name="est_tag[stone_price][] "value="'.$stone_price.'" /><input class="id_mc_type" type="hidden" name="est_tag[id_mc_type][]" value="'.$ival['mc_type'].'" /><input class="mc_value" type="hidden" name="est_tag[mc][]" value="'.$ival['mc_value'].'" /></td>

                                                <td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td>

                                            </tr>';

                                            }

                                            }

                                            }?>

										</tbody>

										<tfoot>

											<tr></tr>

										</tfoot>

									 </table>

								  </div>

								</div> 

							</div> 

						</div>

					</div>

					<div class="box box-primary order_details" <?php echo other_item_array_exist($est_other_item['item_details'], 'item_type', 0) ? '' : 'style="display:none;"' ;?>>

						<div class="box-header with-border">

						  <h3 class="box-title">Order Details</h3>

						  <!--<div class="box-tools pull-right">

							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>

						  </div>-->

						</div>

						<div class="box-body">

							<div class="row">

							  <div class="box-tools pull-right">

								<button type="button" id="create_order_details" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add</button>

							  </div>

							</div>

							<div class="row">

								<div class="box-body">

								   <div class="table-responsive">

									 <table id="estimation_order_details" class="table table-bordered table-striped text-center">

										<thead>

										  <tr>

											<th>Order No</th>

											<th>Product</th>

											<th>Design</th>

											<th>Purity</th>

											<th>Size</th>   

											<th>Pcs</th>   

											<th>G.Wt</th>   

											<th>Wastage(%)</th> 

											<th>MC Value</th>   

											<th>Amount</th>

											<th>Action</th>

										  </tr>

										</thead> 

										<tbody>

                                            

										</tbody>

										<tfoot>

											<tr></tr>

										</tfoot>

									 </table>

								  </div>

								</div> 

							</div> 

						</div>

					</div>

					<div class="box box-primary catalog_details" <?php echo other_item_array_exist($est_other_item['item_details'], 'item_type', 1) ? '' : 'style="display:none;"' ;?>>

						<div class="box-header with-border">

						  <h3 class="box-title">Catalog Details</h3>

						  <!--<div class="box-tools pull-right">

							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>

						  </div>-->

						</div>

						<div class="box-body">

							<div class="row">

							  <div class="box-tools pull-right">

								<button type="button" id="create_catalog_details" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add</button>

							  </div>

							</div>

							<div class="row">

								<div class="box-body">

								   <div class="table-responsive">

								   	<input type="hidden" id="catRow" value="0"  name="">

								   	<input type="hidden" id="active_id"  name="">

									 <table id="estimation_catalog_details" class="table table-bordered table-striped text-center">

										<thead>

										  <tr>

										  	<th>Is Non-Tag</th>

										  <!--	<th>Lot No</th>-->

											<th>Product</th>

											<th>Design</th>

											<th>Purity</th>   

											<th>Size</th>   

											<th>Pcs</th>   

											<th>G.Wt</th>   

											<th>L.Wt</th>   

											<th>N.Wt</th>   

											<th>MC Type</th>   

											<th>MC Value</th>   

											<th>Wastage(%)</th>   

											<!--<th>Discount</th>-->

											<th>Taxable Amt</th>   

											<th>Tax(%)</th>   

											<th>Tax</th>   

											<th>Stone</th>   

											<th>Amount</th>

											<th>Action</th>

										  </tr>

										</thead> 

										<tbody>

						<?php if($this->uri->segment(3) == 'edit'){

						foreach($est_other_item['item_details'] as $ikey => $ival){

						$stone_data=[];

						$stone_price=0;

						$rate_per_gram=($ival['mc_type']=='' || $ival['mc_type']==1 ? $metal_rates['goldrate_22ct']:$ival['piece']);

						$tax_percentage=array(explode(',',$ival['tax_percentage']));

						$tgi_calculation=array(explode(',',$ival['tgi_calculation']));

						$base_value_tax=0;

						$base_rate_tax=0;

						$base_value_price=0;

						$arrived_value_tax=0;

						$arrived_rate_tax=0;

						$arrived_value_price=0;

						$stone=$this->ret_estimation_model->get_stone_details($ival['est_item_id']);

						foreach ($stone as $data) {

							$stone_price+=$data['price'];

							$stone_data[]=array(

											'stone_id'=>$data['stone_id'],

											'stone_pcs'=>$data['pieces'],

											'stone_wt'=>$data['wt'],

											'stone_price'=>$data['price']

												);

						}

						$stone_details=json_encode($stone_data);

				    	if($ival['calculation_based_on']==0)

						{

							$wastage=($ival['gross_wt']*($ival['wastage_percent']/100));

							$mc_type=($ival['mc_type']==1 ? ($ival['mc_value']*$ival['gross_wt']): ($ival['mc_value']*$ival['piece']));

							$rate_with_mc=((($rate_per_gram *($wastage+$ival['net_wt'])) +$mc_type+$stone_price));

						}

						else if($ival['calculation_based_on']==1)

						{

							$wastage=($ival['net_wt']*($ival['wastage_percent']/100));

							$mc_type=($ival['mc_type']==1 ? ($ival['mc_value']*$ival['net_wt']): ($ival['mc_value']*$ival['piece']));

							$rate_with_mc=((($rate_per_gram *($wastage+$ival['net_wt'])) +$mc_type+$stone_price));

						}

						else if($ival['calculation_based_on']==2)

						{

							$wastage=($ival['net_wt']*($ival['wastage_percent']/100));

							$mc_type=($ival['mc_type']==1 ? ($ival['mc_value']*$ival['gross_wt']): ($ival['mc_value']*$ival['piece']));

							$rate_with_mc=((($rate_per_gram *($wastage+$ival['net_wt'])) +$mc_type+$stone_price));

						}

						if($ival['disc_limit_type']==1)

						{

							$rate_with_mc=($rate_with_mc-$ival['discount']);

						}

						else

						{

							$rate_with_mc=($rate_with_mc+($rate_with_mc*($ival['discount']/100)));

						}

						foreach($tgi_calculation[0]  as $key =>$cal_type)

						{

							if($cal_type==1)   //Base value

							{

							    foreach($tax_percentage[0] as $pkey => $tax)

							    {

							    	if($key==$pkey)

							    	{

							    		$base_value_tax+=$tax;

							    	}

							    }	

							   $base_rate_tax=($rate_with_mc*(($base_value_tax / 100)));

							   $base_value_price =($rate_with_mc +$base_rate_tax);

							}

							if($cal_type==2)   //Arrived value

							{

							    foreach($tax_percentage[0] as $pkey => $arr_tax)

							    {

							    	if($key==$pkey)

							    	{

							    		$arrived_value_tax+=$tax;

							    	}

							    }	

							    $arrived_rate_tax	 = ($base_value_price*(($arrived_value_tax / 100)));

								$arrived_value_price =($base_value_price +$arrived_rate_tax);

							}

						}

					$total_tax_rate=number_format((float)($base_rate_tax+$arrived_rate_tax),2,'.','');

					$total_tax_per=number_format((float)($base_value_tax+$arrived_value_tax),2,'.','');

					$total_price=number_format((float)($base_value_price+$arrived_value_price),2,'.','');

					$taxable_rate=number_format((float)$rate_with_mc,2,'.','');

                                    if($ival['item_type'] == 1)

                                    {

										$checked=' checked="checked"';

										$selected=' selected="selected"';

										$disabled=' disabled="disabled"';

										echo '<tr id="'.$ikey.'">

											<td><input type="checkbox" class="non_tag" "'.($ival['is_non_tag']==1 ? $checked:'').'"><input type="hidden" class="is_non_tag" name="est_catalog[is_non_tag][]" value="'.$ival['is_non_tag'].'"></td>

                                            <td><input type="text" class="cat_product" name="est_catalog[product][]" value="'.$ival['product_name'].'" required style="width:100px;"/><input type="hidden" class="cat_pro_id" name="est_catalog[pro_id][]" value="'.$ival['product_id'].'" /><input type="hidden" class="tax_percentage" value="'.$ival['tax_percentage'].'"><input type="hidden" class="tgi_calculation" value="'.$ival['tgi_calculation'].'"></td>

                                            <td><input type="text" class="cat_design" name="est_catalog[design][]" value="'.$ival['design_name'].'" required style="width:150px;"/><input type="hidden" class="cat_des_id" name="est_catalog[des_id][]" value="'.$ival['design_id'].'" /></td>

                                            <td><div class="qty">'.$ival['purname'].'</div><input type="hidden" class="cat_purity" name="est_catalog[purity][]" value="'.$ival['purid'].'" /></td>

                                            <td><input type="number" class="cat_size" name="est_catalog[size][]" value="'.$ival['size'].'"style="width:80px;" /></td>

                                            <td><input type="number" class="cat_pcs" name="est_catalog[pcs][]" value="'.$ival['piece'].'"  style="width:80px;"/></td>

                                            <td><input type="number"  class="cat_gwt" name="est_catalog[gwt][]" value="'.$ival['gross_wt'].'"style="width:80px;" /></td>

                                            <td><input type="number" class="cat_lwt" name="est_catalog[lwt][]" value="'.$ival['less_wt'].'"style="width:80px;" /></td>

                                            <td><input type="number" class="cat_nwt" name="est_catalog[nwt][]" value="'.$ival['net_wt'].'" readonly style="width:80px;"/></td>

                                            <td><select class="mc_type" style="width:80px;"><option value="1" "'.($ival['mc_type']==1? $selected :'').'"> Gram</option><option value="2" "'.($ival['mc_type']==2? $selected :'').'"> Piece</option></select><input type="hidden" value="'.$ival['mc_type'].'" name="est_catalog[id_mc_type][]" class="id_mc_type"></td>

                                            <td><input type="number"  class="cat_mc" name="est_catalog[mc][]" value="'.$ival['mc_value'].'" style="width:80px;"/></td>

                                            <td><input type="number" class="cat_wastage" name="est_catalog[wastage][]" value="'.$ival['wastage_percent'].'" style="width:80px;"/></td>

                                            <td><input type="number" class="cat_taxable_amt" name="est_catalog[taxable_amt][]" readonly="" value="'.$taxable_rate.'"></td>

                                            <td><input type="number" class="cat_tax_per" name="est_catalog[tax_per][]" value="'.$total_tax_per.'" readonly style="width:80px;"></td>

											<td><input type="number" class="cat_tax_price" name="est_catalog[tax_price][]" value="'.$total_tax_rate.'" ="" style="width:80px;"></td>

											<td><a href="#" onClick="create_new_empty_est_cat_stone_item($(this).closest(\'tr\'),'.$ival['est_item_id'].');" class="btn btn-success"><i class="fa fa-plus"></i></a></td>

                                            <td><input type="number" class="cat_amt" name="est_catalog[amount][]" value="'.$ival['item_cost'].'" readonly /><input type="hidden" class="catalog_amt" name="est_catalog[catalog_amt][]" value="'.$ival['item_cost'].'" readonly /><input type="hidden" class="cat_calculation_based_on" name="est_catalog[calculation_based_on][]" value="'.$ival['calculation_based_on'].'" /><input type="hidden" class="stone_details" name="est_catalog[stone_details][]" value='.$stone_details.'><input type="hidden" id="stone_price" class="stone_price" value="'.$stone_price.'"/><td><a href="#" onClick="removeCat_row($(this).closest(\'tr\'));" class="btn btn-danger"><i class="fa fa-trash"></i></a></td>

                                        </tr>';

                                    }

							}

						}?>

										</tbody>

										<tfoot>

											<tr></tr>

										</tfoot>

									 </table>

								  </div>

								</div> 

							</div> 

						</div> 

					</div>

					<div class="box box-primary custom_details" <?php echo other_item_array_exist($est_other_item['item_details'], 'item_type', 2) ? '' : 'style="display:none;"' ;?>>

						<div class="box-header with-border">

						  <h3 class="box-title">Custom Details</h3>

						  <!--<div class="box-tools pull-right">

							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>

						  </div>-->

						</div>

						<div class="box-body">

							<div class="row">

							  <div class="box-tools pull-right">

								<button type="button" id="create_custom_details" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add</button>

							  </div>

							</div>

							<div class="row">

								<div class="box-body">

								   <div class="table-responsive">

									 <table id="estimation_custom_details" class="table table-bordered table-striped text-center">

									 	<input type="hidden" id="cusRow" value="0"  name="">

								   	<input type="hidden" id="custom_active_id"  name="">

										<thead>

										  <tr>

										    <th>Tag</th>

											<th>Product</th>

											<th>Design</th>

											<th>Purity</th>   

											<th>Size</th>   

											<th>Pcs</th>   

											<th>G.Wt</th>   

											<th>L.Wt</th>   

											<th>N.Wt</th>   

											<th>MC Type</th> 

											<th>MC Value</th>

											<th>Wastage(%)</th> 
											
											<th>Other Charge</th>   

											<th>Taxable Amt</th>   

											<th>Tax</th>    

											<th>Stone</th>   

											<th>Amount</th>

											<th>Action</th>

										  </tr>

										</thead> 

										<tbody>

							<?php if($this->uri->segment(3) == 'edit'){

				foreach($est_other_item['item_details'] as $ikey => $ival){

							$stone_data=[];

							$stone=$this->ret_estimation_model->get_stone_details($ival['est_item_id']);

							$stone_price=0;

						$rate_per_gram=($ival['mc_type']=='' || $ival['mc_type']==1 ? $metal_rates['goldrate_22ct']:$ival['piece']);

						$tax_percentage=array(explode(',',$ival['tax_percentage']));

						$tgi_calculation=array(explode(',',$ival['tgi_calculation']));

						$base_value_tax=0;

						$base_rate_tax=0;

						$base_value_price=0;

						$arrived_value_tax=0;

						$arrived_rate_tax=0;

						$arrived_value_price=0;

							foreach ($stone as $data) {

								$stone_price+=$data['price'];

								$stone_data[]=array(

												'stone_id'=>$data['stone_id'],

												'stone_pcs'=>$data['pieces'],

												'stone_wt'=>$data['wt'],

												'stone_price'=>$data['price']

													);

							}

						    $stone_details=json_encode($stone_data);

					    if($ival['calculation_based_on']==0)

						{

							$wastage=($ival['gross_wt']*($ival['wastage_percent']/100));

							$mc_type=($ival['mc_type']==1 ? ($ival['mc_value']*$ival['gross_wt']): ($ival['mc_value']*$ival['piece']));

							$rate_with_mc=((($rate_per_gram *($wastage+$ival['net_wt'])) +$mc_type+$stone_price));

						}

						else if($ival['calculation_based_on']==1)

						{

							$wastage=($ival['net_wt']*($ival['wastage_percent']/100));

							$mc_type=($ival['mc_type']==1 ? ($ival['mc_value']*$ival['net_wt']): ($ival['mc_value']*$ival['piece']));

							$rate_with_mc=((($rate_per_gram *($wastage+$ival['net_wt'])) +$mc_type+$stone_price));

						}

						else if($ival['calculation_based_on']==2)

						{

							$wastage=($ival['net_wt']*($ival['wastage_percent']/100));

							$mc_type=($ival['mc_type']==1 ? ($ival['mc_value']*$ival['gross_wt']): ($ival['mc_value']*$ival['piece']));

							$rate_with_mc=((($rate_per_gram *($wastage+$ival['net_wt'])) +$mc_type+$stone_price));

						}

						foreach($tgi_calculation[0]  as $key =>$cal_type)

						{

							if($cal_type==1)   //Base value

							{

							    foreach($tax_percentage[0] as $pkey => $tax)

							    {

							    	if($key==$pkey)

							    	{

							    		$base_value_tax+=$tax;

							    	}

							    }	

							   $base_rate_tax=($rate_with_mc*(($base_value_tax / 100)));

							   $base_value_price =($rate_with_mc +$base_rate_tax);

							}

							if($cal_type==2)   //Arrived value

							{

							    foreach($tax_percentage[0] as $pkey => $arr_tax)

							    {

							    	if($key==$pkey)

							    	{

							    		$arrived_value_tax+=$tax;

							    	}

							    }	

							    $arrived_rate_tax	 = ($base_value_price*(($arrived_value_tax / 100)));

								$arrived_value_price =($base_value_price +$arrived_rate_tax);

							}

						}

					$total_tax_rate=number_format((float)($base_rate_tax+$arrived_rate_tax),2,'.','');

					$total_tax_per=number_format((float)($base_value_tax+$arrived_value_tax),2,'.','');

					$total_price=number_format((float)($base_value_price+$arrived_value_price),2,'.','');

					$taxable_rate=number_format((float)$rate_with_mc,2,'.','');

                                    if($ival['item_type'] == 2)

                                    {

										$selected=' selected="selected"';

                                    echo '<tr id="cus'.$ikey.'">

                                        <td><input type="text" name="est_custom[product][]" value="'.$ival['product_name'].'" class="cus_product" required style="width:80px;"/><input class="cus_product_id" type="hidden" name="est_custom[pro_id][]" value="'.$ival['product_id'].'" /><input type="hidden" class="tax_percentage" value="'.$ival['tax_percentage'].'"><input type="hidden" class="tgi_calculation" value="'.$ival['tgi_calculation'].'"></td>

                                        <td><div>'.$ival['purname'].'</div><input class="cus_purity" name="est_custom[purity][]" value="'.$ival['purid'].'" type="hidden"/></td><td><input type="number" class="cus_size" name="est_custom[size][]" value="'.$ival['size'].'" style="width:80px;"/></td>

                                        <td><input class="cus_pcs" type="number" name="est_custom[pcs][]" value="'.$ival['piece'].'" style="width:80px;"/></td>

                                        <td><input type="number" class="cus_gwt" name="est_custom[gwt][]" value="'.$ival['gross_wt'].'" style="width:80px;"/></td>

                                        <td><input class="cus_lwt" type="number" name="est_custom[lwt][]" value="'.$ival['less_wt'].'" style="width:80px;"/></td>

                                        <td><input type="number" class="cus_nwt" name="est_custom[nwt][]" value="'.$ival['net_wt'].'" readonly style="width:80px;"/></td>

                                        <td><select class="cus_mc_type" style="width:80px;"><option value="1" "'.($ival['mc_type']==1? $selected :'').'"> Gram</option><option value="2" "'.($ival['mc_type']==2? $selected :'').'"> Piece</option></select><input type="hidden" value="'.$ival['mc_type'].'" name="est_custom[id_mc_type][]" class="id_mc_type"></td>

                                        <td><input type="number" class="cus_mc" name="est_custom[mc][]" value="'.$ival['mc_value'].'" style="width:80px;"/></td><td><input class="cus_wastage" type="number" name="est_custom[wastage][]" value="'.$ival['wastage_percent'].'" style="width:80px;"/></td>

                                        <td><input type="number" class="cus_taxable_amt" name="est_custom[taxable_amt][]" readonly="" value="'.$taxable_rate.'"></td>

                                        <td><input type="number" class="cus_tax_per" name="est_custom[tax_per][]" value="'.$total_tax_per.'" readonly="" style="width:80px;"></td>

                                        <td><input type="number" class="cus_tax_price" name="est_custom[tax_price][]" value="'.$total_tax_rate.'" readonly="" style="width:80px;"></td>

                                        <td><a data-toggle="modal" onClick="create_new_empty_est_cus_stone_item($(this).closest(\'tr\'),'.$ival['est_item_id'].');" class="btn btn-success"><i class="fa fa-plus"></i></a></td>

                                        <td><input class="cus_amount" type="number" name="est_custom[amount][]" value="'.$ival['item_cost'].'" readonly /><input type="hidden" class="cus_calculation_based_on" name="est_custom[calculation_based_on][]" value="'.$ival['calculation_based_on'].'" /><input type="hidden" class="stone_details" name="est_custom[stone_details][]" value='.$stone_details.'><input type="hidden" id="stone_price" class="stone_price" value="'.$stone_price.'"/></td>

                                        <td><a href="#" onClick="removeCat_row($(this).closest(\'tr\'));" class="btn btn-danger"><i class="fa fa-trash"></i></a></td>

                                    </tr>';

                                    }

								}

							}?>

										</tbody>

										<tfoot>

											<tr></tr>

										</tfoot>

									 </table>

								  </div>

								</div> 

							</div>

						</div>

					</div>

					<div class="box box-primary old_matel_details" <?php echo !empty($est_other_item['old_matel_details']) ? '' : 'style="display:none;"' ;?>>

						<div class="box-header with-border">

						  <h3 class="box-title">Old Metal Details</h3>

						  <!--<div class="box-tools pull-right">

							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>

						  </div>-->

						</div>

						<div class="box-body">

							<div class="row">

							  <div class="box-tools pull-right">

								<button type="button" id="create_old_matel_details" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add</button>

							  </div>

							</div>

							<div class="row">

								<div class="box-body">

								   <div class="table-responsive">

									 <table id="estimation_old_matel_details" class="table table-bordered table-striped text-center">

									 	<input type="hidden" id="oldMRow" value="0"  name="">

									 	<input type="hidden" id="old_metal_active_id"  name="">

										<thead>

										  <tr>

											<th>Category</th> 

											<th>Metal Type</th>    

											<th>Category</th>

											<th>G.Wt</th>   

											<th>Dust.Wt</th>   

											<th>Stone.Wt</th> 

											<th>Wastage(%)</th>

											<th>Wastage Wt</th>

											<th>N.Wt</th>   

											<th>Rate</th>   

											<th>Stone</th>

											<th>Purpose</th>

											<th>Amount</th>

											<th>Action</th>

										  </tr>

										</thead> 

										<tbody>
											<?php if($this->uri->segment(3) == 'edit'){
											
											$allowed_old_metal_purity = 0;

											$emp_id = $estimation['created_by'];

											foreach($estimation['employee'] as $emp) {
												if($emp['id_employee'] == $emp_id) {
													$allowed_old_metal_purity = $emp['allowed_old_met_pur'];
												}
											}

											foreach($est_other_item['old_matel_details'] as $ikey => $ival){
											$stone=$this->ret_estimation_model->get_old_metal_stone_details($ival['old_metal_sale_id']);
												$stone_price=0;
												$stone_data=array();
												foreach ($stone as $data) {
													$stone_price+=$data['price'];
													$stone_data[]=array(
																	'stone_id'=>$data['stone_id'],
																	'stone_pcs'=>$data['pieces'],
																	'stone_wt'=>$data['wt'],
																	'stone_price'=>$data['price']
																		);
												}


												$metalTypes = "<option value=''>- Select -</option>";

												$id_category = $ival['id_category'];

												if($allowed_old_metal_purity == 1) //All Metal
												{
													//matel_types = [{'id_metal':1,'metal':'Gold'},{'id_metal':2,'metal':'Silver'}];
													
													$gold_selected = $id_category == 1 ? "selected='selected'" : '';
													$silver_selected = $id_category == 2 ? "selected='selected'" : '';
													$metalTypes .= "<option ".$gold_selected." value='1'>Gold</option><option ".$silver_selected." value='2'>Silver</option>";
												}
												else if($allowed_old_metal_purity == 2)//Only Gold
												{
													//matel_types=[{'id_metal':1,'metal':'Gold'}];
													$gold_selected = $id_category == 1 ? "selected='selected'" : '';
													$metalTypes .= "<option ".$gold_selected." value='1'>Gold</option>";
												}
												else if($allowed_old_metal_purity == 3)//Only Silver
												{
													$silver_selected = $id_category == 2 ? "selected='selected'" : '';
													$metalTypes .= "<option ".$silver_selected." value='2'>Silver</option>";
													//matel_types=[{'id_metal':2,'metal':'Silver'}];
												}

												$old_metal_id = $ival['id_old_metal_type'];

												$old_metal_category_id = $ival['id_old_metal_category'];

												$old_metal_select = "";
												foreach($ival['old_metal_types'] as $omt) {
													$selected = $old_metal_id == $omt['id_metal_type'] ? "selected='selected'" : '';
													$old_metal_select .= "<option ".$selected." value='".$omt['id_metal_type']."'>".$omt['metal_type']."</option>";
												}

												$old_metal_category_select = "";
												foreach($ival['old_metal_category'] as $omc) {
													if($old_metal_id == $omc['id_old_metal_type'])
													{
														$selected = $old_metal_category_id == $omc['id_old_metal_cat'] ? "selected='selected'" : '';
														$old_metal_category_select .= "<option ".$selected." value='".$omc['id_old_metal_cat']."'>".$omc['old_metal_cat']."</option>";
													}
												}

												$stone_details=json_encode($stone_data);
														echo '<tr id="oldM'.$ikey.'"><td><select class="old_id_category"  name="est_oldmatel[id_category][]">'.$metalTypes.'</select></td><td><select class="old_metal_type"  name="est_oldmatel[id_old_metal_type][]" value=""><option value="">- Select -</option>'.$old_metal_select.'</select></td><td><select class="old_metal_category"  name="est_oldmatel[id_old_metal_category][]" value=""><option value="">- Select -</option>'.$old_metal_category_select.'</select></td><td><input type="number" class="old_gwt" name="est_oldmatel[gwt][]" value="'.$ival['gross_wt'].'" style="width: 64px;"/></td><td><input class="old_dwt" type="number" name="est_oldmatel[dwt][]" value="'.$ival['dust_wt'].'" style="width: 64px;"/></td><td><input class="old_swt" type="number" name="est_oldmatel[swt][]" value="'.$ival['stone_wt'].'" style="width: 64px;"/></td><td><input class="old_wastage" type="number" name="est_oldmatel[wastage][]" value="'.$ival['wastage_percent'].'" style="width: 64px;"/></td><td><input class="old_wastage_wt" type="number" name="est_oldmatel[wastage_wt][]" value="'.$ival['wastage_wt'].'" style="width: 64px;"/></td><td><input type="number" class="old_nwt" name="est_oldmatel[nwt][]" value="'.$ival['net_wt'].'" readonly style="width: 64px;"/></td><td><input type="number" class="old_rate" name="est_oldmatel[rate][]" value="'.$ival['rate_per_gram'].'" style="width: 64px;"/></td><td><a data-toggle="modal" onClick="create_new_empty_est_old_metal_stone($(this).closest(\'tr\'),'.$ival['old_metal_sale_id'].');" class="btn btn-success"><i class="fa fa-plus"></i></a></td><td><select class="purpose"><option value="1">Cash</option><option value="2">Exchange</option></select><input type="hidden" value="'.$ival['purpose'].'" name="est_oldmatel[id_purpose][]" class="id_purpose"></td><td><input class="old_amount" type="number" name="est_oldmatel[amount][]" value="'.$ival['amount'].'" /><input type="hidden" class="stone_details" name="est_oldmatel[stone_details][]" value='.$stone_details.'><input type="hidden" id="stone_price" class="stone_price" value="'.$stone_price.'"/></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';
												}
											}?>
										</tbody>

										<tfoot>

											<tr></tr>

										</tfoot>

									 </table>

								  </div>

								</div> 

							</div>

						</div>

					</div>

					<div class="row">

						<!--<div class="col-md-6">

							<div class="box box-primary stone_details" <?php echo !empty($est_other_item['stone_details']) ? '' : 'style="display:none;"' ;?>>

								<div class="box-header with-border">

								  <h3 class="box-title">Stone Details</h3>

								  <div class="box-tools pull-right">

									<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>

								  </div>

								</div>

								<div class="box-body">

									<div class="row">

									  <div class="box-tools pull-right">

										<button type="button" id="create_stone_details" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add</button>

									  </div>

									</div>

									<div class="row">

										<div class="box-body">

										   <div class="table-responsive">

											 <table id="estimation_stone_details" class="table table-bordered table-striped text-center">

												<thead>

												  <tr>

													<th>Stone</th>

													<th>Pcs</th>   

													<th>Wt</th>

													<th>Price</th>

													<th>Action</th>

												  </tr>

												</thead> 

												<tfoot>

													<tr></tr>

												</tfoot>

											 </table>

										  </div>

										</div> 

									</div> 

								</div>

							</div>

						</div>-->

						<!--<div class="col-md-6">

							<div class="box box-primary material_details" <?php echo !empty($est_other_item['other_material_details']) ? '' : 'style="display:none;"' ;?>>

								<div class="box-header with-border">

								  <h3 class="box-title">Materials Details</h3>

								  <div class="box-tools pull-right">

									<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>

								  </div>

								</div>

								<div class="box-body">

									<div class="row">

									  <div class="box-tools pull-right">

										<button type="button" id="create_material_details" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add</button>

									  </div>

									</div>

									<div class="row">

										<div class="box-body">

										   <div class="table-responsive">

											 <table id="estimation_material_details" class="table table-bordered table-striped text-center">

												<thead>

												  <tr>

													<th>Material</th>

													<th>Wt</th>

													<th>Price</th>

													<th>Action</th>

												  </tr>

												</thead> 

												<tbody>

													<?php if($this->uri->segment(3) == 'edit'){

												foreach($est_other_item['other_material_details'] as $ikey => $ival){

														echo '<tr><td><div>'.$ival['material_code'].'</div><input type="hidden" class="material_id" name="est_materials[material_id][]" value="'.$ival['material_id'].'" /></td><td><input class="material_wt" type="number" name="est_materials[material_wt][]" value="'.$ival['wt'].'" /></td><td><input type="number" class="material_price" name="est_materials[material_price][]" value="'.$ival['price'].'"  /></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

												}

											}?>

												</tbody>

												<tfoot>

													<tr></tr>

												</tfoot>

											 </table>

										  </div>

										</div> 

									</div> 

								</div>

							</div>

						</div>-->

					</div>

					<div class="row">

						<div class="col-md-6">

							<div class="box box-primary gift_voucher_details" <?php echo !empty($est_other_item['voucher_details']) ? '' : 'style="display:none;"' ;?>>

								<div class="box-header with-border">

								  <h3 class="box-title">Gift Voucher Details</h3>

								  <div class="box-tools pull-right">

									<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>

								  </div>

								</div>

								<div class="box-body">

									<div class="row">

									  <div class="box-tools pull-right">

										<button type="button" id="create_gift_voucher_details" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add</button>

									  </div>

									</div>

									<div class="row">

										<div class="box-body">

										   <div class="table-responsive">

											 <table id="estimation_gift_voucher_details" class="table table-bordered table-striped text-center">

												<thead>

												  <tr>

													<th>Voucher No</th>

													<th>Voucher Detail</th>   

													<th>Amount</th>

													<th>Action</th>

												  </tr>

												</thead> 

												<tbody>

													<?php if($this->uri->segment(3) == 'edit'){

														foreach($est_other_item['voucher_details'] as $ikey => $ival){

																echo '<tr><td><input class="voucher_no" type="number" name="gift_voucher[voucher_no][]" value="'.$ival['voucher_no'].'" /></td><td></td><td><input type="number" class="gift_voucher_amt" name="gift_voucher[gift_voucher_amt][]" value=""'.$ival['gift_voucher_amt'].'  /></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

														}

													}?>

												</tbody>

												<tfoot>

													<tr></tr>

												</tfoot>

											 </table>

										  </div>

										</div> 

									</div> 

								</div>

							</div>

						</div>

						<div class="col-md-6">

							<div class="box box-primary chit_details" <?php echo !empty($est_other_item['chit_details']) ? '' : 'style="display:none;"' ;?>>

								<div class="box-header with-border">

								  <h3 class="box-title">Chit Details</h3>

								  <div class="box-tools pull-right">

									<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>

								  </div>

								</div>

								<div class="box-body">

									<div class="row">

									  <div class="box-tools pull-right">

										<button type="button" id="create_chit_details" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add</button>

									  </div>

									</div>

									<div class="row">

										<div class="box-body">

										   <div class="table-responsive">

											 <table id="estimation_chit_details" class="table table-bordered table-striped text-center">

												<thead>

												  <tr>

													<th>Scheme Acc no</th>

													<th>Amount</th>

												  </tr>

												</thead> 

												<tbody>

													<?php if($this->uri->segment(3) == 'edit'){

														foreach($est_other_item['chit_details'] as $ikey => $ival){

																echo '<tr><td><input class="scheme_account_id" type="number" name="chit_uti[scheme_account_id][]" value="'.$ival['scheme_account_id'].'" /></td><td><input type="number" class="chit_amt" name="chit_uti[chit_amt][]" value="'.$ival['utl_amount'].'"  /></td><td><a href="#" onClick="$(this).closest(\'tr\').remove();" class="btn btn-danger btn-del"><i class="fa fa-trash"></i></a></td></tr>';

														}

													}?>

												</tbody>

												<tfoot>

													<tr></tr>

												</tfoot>

											 </table>

										  </div>

										</div> 

									</div> 

								</div>

							</div>

						</div>

					</div>

					<div class="box box-primary total_summary_details">

						<div class="box-header with-border">

						  <h3 class="box-title">Total Summary</h3>

						  <!--<div class="box-tools pull-right">

							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>

						  </div>-->

						</div>

						<div class="box-body">

							<div class="row">

								<div class="box-body">

								   <div class="table-responsive">

									 <table id="total_summary_details" class="table table-bordered table-striped text-center">

										<thead>

										</thead> 

										<tbody>

											<tr>

												<td>Total Sale Weight / Pcs</td>

												<td><span class="summary_lbl summary_pur_weight"></span></td>

												<td>Total Sale Amount</td>

												<td><span class="summary_lbl summary_pur_amt"></span></td>

											</tr>

											<tr>

												<td>Total Purchase Weight</td>

												<td><span class="summary_lbl summary_sale_weight"></span></td>

												<td>Total Purchase Amount</td>

												<td><span class="summary_lbl summary_sale_amt"></span></td>

											</tr>

											<tr>

												<td>Advance Paid Weight</td>

												<td><span class="summary_lbl summary_adv_paid_weight"></span></td>

												<td>Total Advance Paid Amount</td>

												<td><span class="summary_lbl summary_adv_paid_amt"></span></td>

											</tr>

											<!--<tr>

												<td></td>

												<td></td>

												<td>Stone Amount</td>

												<td><span class="summary_lbl summary_stone_amt"></span></td>

											</tr>

											<tr>

												<td></td>

												<td></td>

												<td>Material Amount</td>

												<td><span class="summary_lbl summary_material_amt"></span></td>

											</tr>

											<tr>

												<td></td>

												<td></td>

												<td>Gift Voucher Amount</td>

												<td><span class="summary_lbl summary_gift_voucher_amt"></span>

												<input type="hidden" class="summary_gift_amt summary_lbl" name="estimation[gift_voucher_amt]" value="">

												</td>

											</tr>

											<tr>

												<td></td>

												<td></td>

												<td>Chit Amount</td>

												<td><span class="summary_lbl summary_chit_amt"></td>

											</tr>-->

											

											<!--<tr>

												<td></td>

												<td></td>

												<td>Discount</td>

												<td><input type="number" class="summary_discount_amt summary_lbl" name="estimation[discount]" value="<?php echo set_value('estimation[discount]',$estimation['discount']); ?>" step="any"></td>

											</tr>-->

											<tr>

												<td></td>

												<td></td>

												<td>Final Price</td>

												<td><input type="number" class="total_cost summary_lbl" name="estimation[total_cost]" value="<?php echo set_value('estimation[total_cost]',$estimation['total_cost']); ?>" required readonly></td>

											</tr> 

										</tbody>

										<tfoot>

											<tr></tr>

										</tfoot>

									 </table>

								  </div>

								</div> 

							</div>

						</div>

					</div>

				</div>	<!--/ Col --> 

			</div>	 <!--/ row -->

			   <p class="help-block"> </p>  

			     <div class="row">

				   <div class="box box-default"><br/>

					  <div class="col-xs-offset-5">

					<!--<div class="btn-group" id="btn-submit" data-toggle="buttons">

				        <label class="btn btn-primary">

				            <input type="radio" id="est_print" name="type" value="1">Save and Print

				        </label>

				        <label class="btn btn-primary">

				            <input type="radio" id="est_save" name="type" value="2"> Save

				        </label>				       

					 </div>-->

					 	<span id="btn-submit"><button type="button" class="btn btn-primary" id="est_print" name="type" value="1">Save and Print</button></span>

						<button type="button" class="btn btn-default btn-cancel">Cancel</button>

					  </div> <br/>

					</div>

				  </div> 

	            </div>  

	        </form>

	          <?php echo form_close();?>

	            <div class="overlay" style="display:none">

				  <i class="fa fa-refresh fa-spin"></i>

				</div>

	             <!-- /form -->

	          </div>

             </section>

            </div>

  <!-- modal -->      

<div class="modal fade" id="confirm-add" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

				<h4 class="modal-title" id="myModalLabel">Add Customer</h4>

			</div>

			<div class="modal-body">

				<div class="row">

					<div class="form-group">

					   <label for="cus_first_name" class="col-md-3 col-md-offset-1 ">First Name<span class="error">*</span></label>

					   <div class="col-md-6">

							<input type="text" class="form-control" id="cus_first_name" name="cus[first_name]" placeholder="Enter customer first name" required="true" style="text-transform:uppercase"> 

							<p class="help-block cus_first_name"></p>

					   </div>

					</div>

				</div> 

				<div class="row">   

					<div class="form-group">

					   <label for="cus_mobile" class="col-md-3 col-md-offset-1 ">Mobile<span class="error">*</span></label>

					   <div class="col-md-6">

							<input type="number" class="form-control" id="cus_mobile" name="cus[mobile]" placeholder="Enter customer mobile"> 

							<p class="help-block cus_mobile"></p>

					   </div>

					</div>

				</div>

				

				<div class="row">   

					<div class="form-group">

					   <label for="" class="col-md-3 col-md-offset-1 ">Select Area<span class="error"></span></label>

					   <div class="col-md-6">

						 <select class="form-control" id="village_select" style="width:100%;"></select>

							<input type="hidden" name="cus[id_village]" id="id_village" name="" value="">

					   </div>

					</div>

				</div></br>

				

			

				

				 <div class="row">

                          <div class="form-group">

                          <label for="city_need" class="col-md-3 col-md-offset-1 ">Other City</label>

                          <div class="col-md-6">

                              <input type="hidden" name="cus[id_country]" id="id_country">

                              <input type="hidden" name="cus[id_state]" id="id_state">

                              <input type="checkbox" id="city_need" name="city" value="yes" >

                              </div>

                          </div>

                </div><br>

                

                <div class="row">

                          <div class="form-group">

                              <label for="" class="col-md-3 col-md-offset-1 ">Select City<span

                                      class="error"></span></label>

                              <div class="col-md-6">

                                  <select class="form-control" id="city" style="width:100%;" disabled></select>

                                  <input type="hidden" name="cus[id_city]" id="id_city">

                              </div>

                          </div>

                </div></br>

                      

				<div class="row">   

					<div class="form-group">

					   <label for="" class="col-md-3 col-md-offset-1 ">Customer Type<span class="error"></span></label>

					   <div class="col-md-6">

						 <input type="radio" id="cus_type"  name="cus[cus_type]" value="1" class="minimal" checked/> Individual

						 <input type="radio" id="cus_type"  name="cus[cus_type]" value="2" class="minimal" /> Business

					   </div>

					</div>

				</div>

				 <!--<div class="row">   

					<div class="form-group">

					   <label for="cus_address" class="col-md-3 col-md-offset-1 ">Address<span class="error">*</span></label>

					   <div class="col-md-6">

							<input type="text" class="form-control" id="cus_address" name="cus[address]" placeholder="Enter customer address"> 

							<p class="help-block cus_address"></p>

					   </div>

					</div>

				</div>-->

			</div>

		  <div class="modal-footer">

			<a href="#" id="add_newcutomer" class="btn btn-success">Add</a>

			<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>

		  </div>

		</div>

	</div>

</div>

<!-- / modal -->        

<!--Customer Update-->

<div class="modal fade" id="confirm-edit" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

				<h4 class="modal-title" id="myModalLabel">Edit Customer</h4>

			</div>

			<div class="modal-body">

				<div class="row">

					<div class="form-group">

					   <label for="cus_first_name" class="col-md-3 col-md-offset-1 ">First Name<span class="error">*</span></label>

					   <div class="col-md-6">

							<input type="text" class="form-control" id="ed_cus_first_name" name="cus[first_name]" placeholder="Enter customer first name" required="true" style="text-transform:uppercase"> 

							<p class="help-block cus_first_name"></p>

					   </div>

					</div>

				</div> 

				<div class="row">   

					<div class="form-group">

					   <label for="cus_mobile" class="col-md-3 col-md-offset-1 ">Mobile<span class="error">*</span></label>

					   <div class="col-md-6">

							<input type="text" class="form-control" id="ed_cus_mobile" name="cus[mobile]" placeholder="Enter customer mobile" readonly> 

							<p class="help-block cus_mobile"></p>

					   </div>

					</div>

				</div>

				



				

				<div class="row">   

					<div class="form-group">

					   <label for="" class="col-md-3 col-md-offset-1 ">Select Area<span class="error"></span></label>

					   <div class="col-md-6">

						 <select class="form-control" id="ed_sel_village" style="width:100%;"></select>

						 <input type="hidden" id="ed_id_village">

					   </div>

					</div>

				</div></br>

				<div class="row">   

					<div class="form-group">

					   <label for="" class="col-md-3 col-md-offset-1 ">Customer Type<span class="error"></span></label>

					   <div class="col-md-6">

						 <input type="radio" id="ed_cus_type1"  name="ed_cus[cus_type]" value="1" class="minimal" checked/> Individual

						 <input type="radio" id="ed_cus_type2"  name="ed_cus[cus_type]" value="2" class="minimal" /> Business

					   </div>

					</div>

				</div>

				 <!--<div class="row">   

					<div class="form-group">

					   <label for="cus_address" class="col-md-3 col-md-offset-1 ">Address<span class="error">*</span></label>

					   <div class="col-md-6">

							<input type="text" class="form-control" id="cus_address" name="cus[address]" placeholder="Enter customer address"> 

							<p class="help-block cus_address"></p>

					   </div>

					</div>

				</div>-->

			</div>

		  <div class="modal-footer">

			<a href="#" id="update_cutomer" class="btn btn-success">Update</a>

			<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>

		  </div>

		</div>

	</div>

</div>

<!--Customer Update-->

<!-- / modal -->        

<!--Customer Update-->

<div class="modal fade" id="confirm-edit" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

				<h4 class="modal-title" id="myModalLabel">Edit Customer</h4>

			</div>

			<div class="modal-body">

				<div class="row">

					<div class="form-group">

					   <label for="cus_first_name" class="col-md-3 col-md-offset-1 ">First Name<span class="error">*</span></label>

					   <div class="col-md-6">

							<input type="text" class="form-control" id="ed_cus_first_name" name="cus[first_name]" placeholder="Enter customer first name" required="true" style="text-transform:uppercase"> 

							<p class="help-block cus_first_name"></p>

					   </div>

					</div>

				</div> 

				<div class="row">   

					<div class="form-group">

					   <label for="cus_mobile" class="col-md-3 col-md-offset-1 ">Mobile<span class="error">*</span></label>

					   <div class="col-md-6">

							<input type="text" class="form-control" id="ed_cus_mobile" name="cus[mobile]" placeholder="Enter customer mobile" disabled> 

							<p class="help-block cus_mobile"></p>

					   </div>

					</div>

				</div>

				<div class="row">   

					<div class="form-group">

					   <label for="" class="col-md-3 col-md-offset-1 ">Select Village<span class="error"></span></label>

					   <div class="col-md-6">

						 <select class="form-control" id="ed_sel_village" style="width:100%;"></select>

						 <input type="hidden" id="ed_id_village">

					   </div>

					</div>

				</div></br>

				<div class="row">   

					<div class="form-group">

					   <label for="" class="col-md-3 col-md-offset-1 ">Customer Type<span class="error"></span></label>

					   <div class="col-md-6">

						 <input type="radio" id="ed_cus_type1"  name="ed_cus[cus_type]" value="1" class="minimal" checked/> Individual

						 <input type="radio" id="ed_cus_type2"  name="ed_cus[cus_type]" value="2" class="minimal" /> Business

					   </div>

					</div>

				</div>

				 <!--<div class="row">   

					<div class="form-group">

					   <label for="cus_address" class="col-md-3 col-md-offset-1 ">Address<span class="error">*</span></label>

					   <div class="col-md-6">

							<input type="text" class="form-control" id="cus_address" name="cus[address]" placeholder="Enter customer address"> 

							<p class="help-block cus_address"></p>

					   </div>

					</div>

				</div>-->

			</div>

		  <div class="modal-footer">

			<a href="#" id="update_cutomer" class="btn btn-success">Udpate</a>

			<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>

		  </div>

		</div>

	</div>

</div>

<!--Customer Update-->

<div class="modal fade" id="stoneModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog" style="width:60%;">

		<div class="modal-content">

			<div class="modal-header">

				<h4 class="modal-title" id="myModalLabel">Add Stone</h4>

			</div>

			<div class="modal-body">

				<div class="row">

			<div class="box-tools pull-right">

			<button type="button" id="create_stone_item_details" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add</button>

			</div>

			</div>

				<div class="row">

					<table id="estimation_stone_item_details" class="table table-bordered table-striped text-center">

					<thead>

					<tr>

					<th>Stone</th>

					<th>Pcs</th>   

					<th>Wt</th>

					<th>Price</th>

					<th>Action</th>

					</tr>

					</thead> 

					<tbody>

					</tbody>										

					<tfoot>

					<tr></tr>

					</tfoot>

					</table>

			</div>

		  </div>

		  <div class="modal-footer">

			<button type="button" id="update_stone_details" class="btn btn-success">Save</button>

			<button type="button" id="close_stone_details" class="btn btn-warning" data-dismiss="modal">Close</button>

		  </div>

		</div>

	</div>

</div>

</div>

<div class="modal fade" id="other_material_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog" style="width: 54%;">

		<div class="modal-content">

			<div class="modal-header">

				<h4 class="modal-title" id="myModalLabel">Add Material</h4>

			</div>

			<div class="modal-body">

				<div class="row">

			<div class="box-tools pull-right">

			<button type="button" id="create_material_item_details" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add</button>

			</div>

			</div>

				<div class="row">

					<table id="estimation_other_material_details" class="table table-bordered table-striped text-center">

					<thead>

					<tr>

					<th>Material</th>

					<th>Wt</th>

					<th>Price</th>

					<th>Action</th>

					</tr>

					</thead> 

					<tbody>

					</tbody>										

					<tfoot>

					<tr></tr>

					</tfoot>

					</table>

			</div>

		  </div>

		  <div class="modal-footer">

			<button type="button" id="update_material_details" class="btn btn-success">Save</button>

			<button type="button" id="close_material_details" class="btn btn-warning" data-dismiss="modal">Close</button>

		  </div>

		</div>

	</div>

</div>

</div>

<!--  custom items-->

<div class="modal fade" id="cus_stoneModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog" style="width:60%;">

		<div class="modal-content">

			<div class="modal-header">

				<h4 class="modal-title" id="myModalLabel">Add Stone</h4>

			</div>

			<div class="modal-body">

				<div class="row">

			<div class="box-tools pull-right">

			<button type="button" id="create_stone_item_details" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add</button>

			</div>

			</div>

				<div class="row">

					<table id="estimation_stone_cus_item_details" class="table table-bordered table-striped text-center">

					<thead>

					<tr>

					<th>Stone</th>

					<th>Pcs</th>   

					<th>Wt</th>

					<th>Price</th>

					<th>Action</th>

					</tr>

					</thead> 

					<tbody>

					</tbody>										

					<tfoot>

					<tr></tr>

					</tfoot>

					</table>

			</div>

		  </div>

		  <div class="modal-footer">

			<button type="button" id="update_stone_details" class="btn btn-success">Save</button>

			<button type="button" id="close_stone_details" class="btn btn-warning" data-dismiss="modal">Close</button>

		  </div>

		</div>

	</div>

</div>

</div>

<div class="modal fade" id="cus_other_material_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog" style="width: 54%;">

		<div class="modal-content">

			<div class="modal-header">

				<h4 class="modal-title" id="myModalLabel">Add Material</h4>

			</div>

			<div class="modal-body">

				<div class="row">

			<div class="box-tools pull-right">

			<button type="button" id="create_material_item_details" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add</button>

			</div>

			</div>

				<div class="row">

					<table id="estimation_other_cus_material_details" class="table table-bordered table-striped text-center">

					<thead>

					<tr>

					<th>Material</th>

					<th>Wt</th>

					<th>Price</th>

					<th>Action</th>

					</tr>

					</thead> 

					<tbody>

					</tbody>										

					<tfoot>

					<tr></tr>

					</tfoot>

					</table>

			</div>

		  </div>

		  <div class="modal-footer">

			<button type="button" id="update_material_details" class="btn btn-success">Save</button>

			<button type="button" id="close_material_details" class="btn btn-warning" data-dismiss="modal">Close</button>

		  </div>

		</div>

	</div>

</div>

</div>

<!-- old metals -->

<div class="modal fade" id="old_stoneModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog" style="width:60%;">

		<div class="modal-content">

			<div class="modal-header">

				<h4 class="modal-title" id="myModalLabel">Add Stone</h4>

			</div>

			<div class="modal-body">

				<div class="row">

			<div class="box-tools pull-right">

			<button type="button" id="create_stone_old" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add</button>

			</div>

			</div>

				<div class="row">

					<table id="estimation_stone_old_metal_details" class="table table-bordered table-striped text-center">

					<thead>

					<tr>

					<th>Stone</th>

					<th>Pcs</th>   

					<th>Wt</th>

					<th>Price</th>

					<th>Action</th>

					</tr>

					</thead> 

					<tbody>

					</tbody>										

					<tfoot>

					<tr></tr>

					</tfoot>

					</table>

			</div>

		  </div>

		  <div class="modal-footer">

			<button type="button" id="update_stone_details" class="btn btn-success">Save</button>

			<button type="button" id="close_stone_details" class="btn btn-warning" data-dismiss="modal">Close</button>

		  </div>

		</div>

	</div>

</div>

</div>

<!-- old metals -->





 <!-- modal -->

      <div class="modal fade" id="customer-popup" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

          <div class="modal-dialog modal-lg">

              <div class="modal-content">

                  <div class="modal-header">

                      <button type="button" class="close" data-dismiss="modal"><span

                              aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

                      <h4 class="modal-title" id="myModalLabel">Customer Details</h4>

                  </div>

                  <div class="modal-body">

                      <form id="cus_pop"></form>

                      <div id="cus_bill_details"></div>

                  </div>

                  <div class="modal-footer">

                      <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>

                  </div>

              </div>

          </div>

      </div>

      <!-- / modal -->
      
      
      
<div class="modal fade" id="cus_other_charges_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:60%;">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Add Charges</h4>
			</div>
			
			<div class="modal-body">
                <div class="row">
                    <div class="box-tools pull-right">
                        <button type="button" id="add_new_charge" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add</button>
                    </div>
                </div>
                
				<div class="row">
					<table id="estimation_other_charge_cus_item_details" class="table table-bordered table-striped text-center">
					<thead>
					<tr>
					<th>Charge Name</th>
					<th>Value</th>
					<th>Action</th>
					</tr>
					</thead> 
					<tbody>
					</tbody>										
					<tfoot>
					<tr></tr>
					</tfoot>
					</table>
			</div>
		  </div>
		  <div class="modal-footer">
			<button type="button" id="update_charge_details" class="btn btn-success">Save</button>
			<button type="button" id="close_stone_details" class="btn btn-warning" data-dismiss="modal">Close</button>
		  </div>
		</div>
	</div>
</div>
</div>
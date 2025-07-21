<html><head>

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

		<title>Estimation</title>

		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/estimation.css">

	<style type="text/css">

		body, html {

		margin-bottom:0

		}

		 @page { 

		 	size: 78mm;

		 	margin-bottom:40px !important;

		 } 

		 

		 /* output size */

		 span { display: inline-block; }

	</style>

</head>

<body class="plugin">

	<span class="PDFReceipt">

		<div class="printable">

		   

			<div class="header">		

				<h3>Estimation - <?php echo $estimation['esti_no'].($estimation['short_name']!='' ? '-'.$estimation['short_name'] :'');?> </h3> 

			</div>

			<div class="tap_head">

				<div class="cus_name">

					<span><?php echo $estimation['customer_name'].($estimation['village_name']!='' ? ' / '.$estimation['village_name']:'').' / '.$estimation['mobile']; ?> </span>

				</div>

				<div class="estimation_datetime">

					<?php echo $estimation['estimation_datetime'];?>

				</div>

			</div><p></p>

			<div>

				<table class="metal_rate">

					<tr align="left">

						<th>Rate</th>

						<!--<th>Market Rate</th>-->

					</tr>

					<tbody>

						<tr>

							<td>Gold &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;<?php echo number_format($metal_rates['goldrate_22ct'],2,'.','');?></td>

							<td>SILVER &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;<?php echo $metal_rates['silverrate_1gm'];?></td>

						</tr>

					<!--	<tr>

							<td>SILVER &nbsp;&nbsp;&nbsp;  :  <?php echo $metal_rates['silverrate_1gm'];?></td>

							<td>SILVER &nbsp;&nbsp;&nbsp;  :  <?php echo $metal_rates['mjdmasilverrate_1gm'];?></td>

						</tr>-->

					</tbody>

				</table>

			</div>

				

			<hr class="dashed">

    			<div class="item_details">

        		    <?php if(sizeof($est_other_item['item_details']) && $est_other_item['item_details'][0]['id_orderdetails']==''){?>

        			<table class="estimation" style="width:100%;">

        				<tr>

        					<th width="15%">ITEM</th>

        					<th width="10%">WT</th>

        					<th width="10%">MC</th>

        					<th width="10%">VALUE</th>

        				</tr>

        				<!--</thead>-->

        				<tbody>

        					<?php 

        					    $tot_payable=0;

        					    $market_rate_cost=0;

        					    $total_wt=0;

        					    $net_wt=0;

        					    $total_piece=0;

        					    $total_net_wt=0;

        					    $tag_net_wt=0;

        					    $making_charge=0;

        					    

        					    

        					    $paid_advance   =0;

				                $paid_weight    =0;

				                $wt_amt         =0;

				                $tot_adv_paid   =0;

				                if(sizeof($est_other_item['advance_details'])>0)

				                {

				                    foreach($est_other_item['advance_details'] as $advance)

            					    {

            					            $paid_advance+=$advance['paid_advance'];

    					                    $paid_weight+=$advance['paid_weight'];

    					                    $wt_amt+=($advance['paid_weight']);

            					    }

            					    $tot_adv_paid=number_format(($paid_advance+$wt_amt),2,'.','');

				                }

				                

				                

				                

        						foreach($est_other_item['item_details'] as $items){

        						     $making_charge=0;

        						$stone_price=0;

								$stone_weight=0;

        						

        						$certification_cost=0;

        						

        						$total_piece+=$items['piece'];

        						

        						$total_wt+=$items['net_wt'];

        						

        						$tot_payable+=$items['item_cost'];

        						

        						$market_rate_cost+=$items['market_rate_cost'];

        						

        						if($items['is_partial']==1)

        						{

        							$net_wt+=$items['net_wt'];

        							$tag_net_wt+=$items['tag_net_wt'];

        						}

        						if($items['calculation_based_on']==0)

        						{

        						  $wast_wgt=number_format((($items['gross_wt']) * ($items['wastage_percent']/100)),2,'.','');

        						  

        						  $making_charge=($items['mc_type']==2 ? $items['gross_wt']*$items['mc_value'] : $items['mc_value']*$items['piece']);

        						  

        						}else if($items['calculation_based_on']==1)

        						{

        							$wast_wgt=number_format((($items['net_wt']) * ($items['wastage_percent']/100)),2,'.','');

        							

        							$making_charge=($items['mc_type']==2 ? $items['net_wt']*$items['mc_value'] : $items['mc_value']*$items['piece']);

        							

        						}else if($items['calculation_based_on']==2)

        						{

        							$wast_wgt=number_format((($items['net_wt']) * ($items['wastage_percent']/100)),3,'.','');

        							

        							$making_charge=($items['mc_type']==2 ? $items['gross_wt']*$items['mc_value'] : $items['mc_value']*$items['piece']);

        							

        						}

        						foreach($items['stone_details'] as $stone)

        						{

        							$stone_price+=$stone['amount'];

									$stone_weight+=$stone['wt'];

        							$certification_cost+=$stone['certification_cost'];

        						}

        					?>

        					<tr>

        						<td><hr class="item_dashed"></td>

        					</tr>

        					<tr style="margin-top:2px !important;">

        						<td ><?php echo substr($items['product_name'],0,6);?></td>

        						<td><?php echo $items['gross_wt'];?></td>

        						<td><?php echo $making_charge;?></td>

        						<td><?php echo number_format($items['item_cost']-$items['item_total_tax']-$stone_price,2,'.','');?></td>

        					</tr>

        					<?php if($items['less_wt']>0){?>

        					

        					<tr>

        						<td >LESS WT</td>

        						<td><?php echo $items['less_wt'];?></td>

        						<td></td>

        						<td></td>

        					</tr>

        					

        					<tr>

        						<td >NET WT</td>

        						<td><?php echo $items['net_wt'];?></td>

        						<td></td>

        						<td></td>

        					</tr>

        					

        					<?php }?>

        					<tr>

        						<td >Va</td>

        						<td><?php echo $wast_wgt;?></td>

        						<td></td>

        						<td></td>

        					</tr>

        					<?php if($stone_price>0){?>

        					<tr>

        						<td >STONE CHARGE</td>

        						<td><?php echo number_format($stone_weight,3,'.','');?> &times;</td>

        						<td><?php echo number_format($stone_price/$stone_weight,2,'.','');?></td>

        						<td><?php echo number_format($stone_price,2,'.','');?></td>

        					</tr>

        					<?php }?>

        					<?php if($certification_cost>0){?>

        					<tr>

        						<td >CERTIFICATION CHARGE</td>

        						<td></td>

        						<td></td>

        						<td><?php echo number_format($certification_cost,2,'.','');?></td>

        					</tr>

        					<?php }?>

        					<tr>

        						<td><?php echo $items['tgrp_name'];?></td>

        						<td></td>

        						<td></td>

        						<td><?php echo number_format($items['item_total_tax'],2,'.','');?></td>

        					</tr>

        					<tr style="font-weight:bold;">

        						<td >TOTAL</td>

        						<td></td>

        						<td></td>

        						<td><?php echo $items['item_cost'];?></td>

        					</tr>

        					<?php }?>

        					<tr>

        						<td><hr class="tot_dashed"></td>

        					</tr>

        					<?php if($tot_adv_paid>0){?>

            					<tr style="font-weight:bold;">

            						<td >Adv Paid</td>

            						<td></td>

            						<td></td>

            						<td><?php echo number_format($tot_adv_paid,2,'.','');?></td>

            					</tr>

        					<?php }?>

        					

        					<tr style="font-weight:bold;">

        						<td >NET <?php echo $total_piece.' - '.$total_wt ;?></td>

        						<td></td>

        						<td></td>

        						<td><?php echo number_format($tot_payable-$tot_adv_paid,2,'.','');?></td>

        					</tr>

        				</tbody>

        				</table>

        			<!--<div style="font-weight:bold;font-size:12px !important;">

        				<label>SAVINGS FROM MARKET RATE  : <?php echo number_format($market_rate_cost-$tot_payable,2,'.',''); ?></label>

        			</div>-->

        			<?php if($tag_net_wt!=0){?>

        			<div style="margin-left:3px;">

        				<label>PARTLY (<?php echo number_format($tag_net_wt,3,'.','').'-'.number_format($net_wt,3,'.','')?>): <?php echo number_format(($tag_net_wt-$net_wt),3,'.','');?></label>

        			</div>

        			<?php }} else  if(sizeof($est_other_item['advance_details'])>0){?>

        			 <table class="estimation" style="width:100%;">

        				<tr>

        					<th width="15%">ITEM</th>

        					<th width="10%">WT</th>

        					<th width="10%">V.A</th>

        					<th width="10%">TOT WT</th>

        				</tr>

        				<!--</thead>-->

            				<tbody>

            					<?php 

            					    $tot_payable=0;

            					    $market_rate_cost=0;

            					    $total_wt=0;

            					    $net_wt=0;

            					    $total_piece=0;

            					    $total_net_wt=0;

            					    $tag_net_wt=0;

            					    $making_charge=0;

            					    $total_wastage_wt=0;

            					    $taxable_amt=0;

            					    $total_making_charge=0;

            					    $balance_pay_amt=0;

            					    $total_tax_amt=0;

            					    

            					    

            					    $paid_advance   =0;

    				                $paid_weight    =0;

    				                $wt_amt         =0;

    				                $tot_adv_paid   =0;

    				                if(sizeof($est_other_item['advance_details'])>0)

    				                {

    				                    foreach($est_other_item['advance_details'] as $advance)

                					    {

                					            $paid_advance+=$advance['paid_advance'];

        					                    $paid_weight+=$advance['paid_weight'];

        					                    $wt_amt+=($advance['paid_weight']);

                					    }

                					    $tot_adv_paid=number_format(($paid_advance+$wt_amt),2,'.','');

    				                }

    				                

    				                

    				                

            						foreach($est_other_item['item_details'] as $items){

            						     $making_charge=0;

            						$stone_price=0;

            						

            						$certification_cost=0;

            						

            						$total_piece+=$items['piece'];

            						

            						$total_wt+=$items['net_wt'];

            						

            						$tot_payable+=$items['item_cost'];

            						

            						$market_rate_cost+=$items['market_rate_cost'];

            						

            						$taxable_amt+=$items['item_cost']-$items['item_total_tax'];

            						

            						$total_tax_amt+=$items['item_total_tax'];

            						

            						if($items['is_partial']==1)

            						{

            							$net_wt+=$items['net_wt'];

            							$tag_net_wt+=$items['tag_net_wt'];

            						}

            						if($items['calculation_based_on']==0)

            						{

            						  $wast_wgt=number_format((($items['gross_wt']) * ($items['wastage_percent']/100)),2,'.','');

            						  

            						  $making_charge=($items['mc_type']==1 ? $items['gross_wt']*$items['mc_value'] : $items['mc_value']*$items['piece']);

            						  

            						}else if($items['calculation_based_on']==1)

            						{

            							$wast_wgt=number_format((($items['net_wt']) * ($items['wastage_percent']/100)),2,'.','');

            							

            							$making_charge=($items['mc_type']==1 ? $items['net_wt']*$items['mc_value'] : $items['mc_value']*$items['piece']);

            							

            						}else if($items['calculation_based_on']==2)

            						{

            							$wast_wgt=number_format((($items['net_wt']) * ($items['wastage_percent']/100)),3,'.','');

            							

            							$making_charge=($items['mc_type']==1 ? $items['gross_wt']*$items['mc_value'] : $items['mc_value']*$items['piece']);

            							

            						}

            						foreach($items['stone_details'] as $stone)

            						{

            							$stone_price+=$stone['amount'];

            							$certification_cost+=$stone['certification_cost'];

            						}

            					    $total_wastage_wt+=$wast_wgt;

            					    $total_making_charge+=$making_charge;

            					?>

            					<tr>

            						<td><hr class="item_dashed"></td>

            					</tr>

            					<tr style="margin-top:2px !important;">

            						<!--<td ><?php echo substr($items['product_name'],0,6);?></td>-->

            						<td ><?php echo $items['product_name'];?></td>

            						<td style="text-align:right;"><?php echo $items['gross_wt'];?></td>

            						<td style="text-align:right;"><?php echo $wast_wgt;?></td>

            						<td style="text-align:right;"><?php echo number_format($wast_wgt+$items['net_wt'],3,'.','');?></td>

            					</tr>

            				    <tr>

            						<td>MC</td>

            						<td style="text-align:right;"><?php echo $making_charge;?></td>

            						<td></td>

            						<td></td>

            					</tr>

            					

            					

            					<?php }?>

            					<tr>

            						<td><hr class="tot_dashed"></td>

            					</tr>

            					

            					<tr style="font-weight:bold;">

            						<td >TOTAL</td>

            						<td></td>

            						<td></td>

            						<td style="text-align:right;"><?php echo number_format($total_wt+$total_wastage_wt,3,'.','');?></td>

            					</tr>

            					<tr>

            						<td><hr class="tot_dashed"></td>

            					</tr>

            				

                				

                					<?php 

                					$i=1;

                					$adv_paid_wt=0;

                					 foreach($est_other_item['advance_details'] as $advance)

                					 {

                					 $adv_paid_wt+=($advance['store_as']==1 ? ($advance['paid_advance']/$advance['rate_per_gram']):$advance['paid_weight']);

                					 ?>

                                        <tr>

                                            <td><?php echo $advance['bill_date'];?></td>

                                            <td style="text-align:right;"><?php echo number_format(($advance['store_as']==1 ? $advance['paid_advance'] :( $advance['paid_weight']* $advance['rate_per_gram'])),2,'.','');?></td>

                                            <td style="text-align:right;"><?php echo $advance['rate_per_gram'];?></td>

                                            <td style="text-align:right;"><?php echo number_format(($advance['store_as']==1 ? ($advance['paid_advance']/$advance['rate_per_gram']):$advance['paid_weight']),3,'.','');?></td>

                                        </tr>

                					<?php

                				    $balance_pay_amt=$taxable_amt-$tot_adv_paid-$total_making_charge-$stone_price;

                					 }

                					?>

                					<tr>

            						    <td><hr class="tot_dashed"></td>

            					    </tr>

                					<tr style="font-weight:bold;">

                					    <td>Total ADV</td>

                					    <td style="text-align:right;"><?php echo $tot_adv_paid;?></td>

                					    <td></td>

                					    <td style="text-align:right;"><?php echo number_format($adv_paid_wt,3,'.','');?></td>

                					</tr>

                					<tr>

            						    <td><hr class="tot_dashed"></td>

            					    </tr>

            					    <tr>

                					    <td>Net Wt</td>

                					    <td></td>

                					    <td></td>

                					    <td style="text-align:right;"><?php echo number_format(($total_wt+$total_wastage_wt)-$adv_paid_wt,3,'.','');?></td>

                					</tr>

                				

                    				<tr>

                						<td>Bal Amt</td>

                						<td></td>

                						<td></td>

                						<td style="text-align:right;"><?php echo number_format($balance_pay_amt,2,'.','');?></td>

                					</tr>

                					<?php if($stone_price>0){?>

                						<tr>

                    						<td >STONE</td>

                    						<td></td>

                    						<td></td>

                    						<td style="text-align:right;"><?php echo number_format($stone_price,2,'.','');?></td>

                    					</tr>

                					<?php }?>

                					<tr>

                						<td>Tot Mc</td>

                						<td></td>

                						<td></td>

                						<td style="text-align:right;"><?php echo number_format($total_making_charge,2,'.','');?></td>

                					</tr>

                					

                					<tr>

                						<td>GST<?php echo ' '.$est_other_item['item_details'][0]['tgrp_name'];?></td>

                						<td></td>

                						<td></td>

                						<td style="text-align:right;"><?php echo number_format($total_tax_amt,2,'.','');?></td>

                					</tr>

                					

                					<tr>

                						<td>Net Amt</td>

                						<td></td>

                						<td></td>

                						<td style="text-align:right;font-weight:bold;"><?php echo number_format($tot_payable-$tot_adv_paid,2,'.','');?></td>

                					</tr>

            					

            				</tbody>

        				</table>

        					<br>

                        <?php if($tag_net_wt!=0){?>

                        <div style="margin-left:3px;">

                        <label>PARTLY (<?php echo number_format($tag_net_wt,3,'.','').'-'.number_format($net_wt,3,'.','')?>): <?php echo number_format(($tag_net_wt-$net_wt),3,'.','');?></label>

                        </div>

                        <?php }?>

        			<?php }?>

        		

        				<?php 

        

        				if(sizeof($est_other_item['old_matel_details'])>0){?>

        					<div class="head" align="center;">

        						<span style="text-transform:uppercase;;">purchase items</span>

        					</div>

        				<table class="purchase">

        					<tr align="left">

        					<th>GR WT</th>

        					<th>LESS</th>

        					<th>V.A</th>

        					<th>RATE</th>

        					<th>VALUE</th>

        				</tr>

        				<tbody>

        					<?php 

        					$gross_wt=0;

        					$amount=0;

        					foreach($est_other_item['old_matel_details'] as $data){

        					$gross_wt+=$data['gross_wt'];

        					$amount+=$data['amount'];

        					?>

        					<tr>

        						<td><hr class="item_dashed"></td>

        					</tr>

        					<tr>

        						<td><?php echo $data['gross_wt'];?></td>

        						<td><?php echo number_format($data['stone_wt']+$data['dust_wt'],3,'.','');?></td>

        						<td><?php echo $data['wastage_wt'];?></td>

        						<td><?php echo $data['rate_per_gram'];?></td>

        						<td><?php echo $data['amount'];?></td>

        					</tr>

        					<!--<?php if($data['stone_wt']>0){?>

        					<tr style="font-size:10px !important;text-transform: capitalize;">

        					    <td>Stn Wt</td>

        						<td><?php echo $data['stone_wt'];?></td>

        						<td>Dust</td>

        						<td><?php echo $data['dust_wt'];?></td>

        						<td></td>

        					</tr>

        					<?php }?>-->

        					

        					<?php }?>

        				</tbody>

        				<tr>

        						<td><hr class="tot_dashed"></td>

        				</tr>

        				<tr style="font-weight: bold;">

        					<td><?php echo number_format($gross_wt,3,'.','');?></td>

        					<td></td>

        					<td></td>

        					<td></td>

        					<td><?php echo number_format($amount,2,'.','');?></td>

        				</tr>

        				<?php if($tot_payable>0){?>

        				    <tr>

        						<td><hr class="tot_dashed"></td>

        				    </tr>

        				    <tr style="font-weight: bold;">

        					<td>Total</td>

        					<td></td>

        					<td></td>

        					<td></td>

        					<td><?php echo number_format(($tot_payable-$amount),2,'.','');?></th>

        				</tr>

                       

        				<?php }?>

        				

        			</table>

                    <p></p>

                    <div style="margin-left:3px;">

        				<label>EMP-ID : <?php echo $estimation['emp_code'].' / '.($est_other_item['old_matel_details'][0]['purpose']==1 ? 'Cash' :'Exchange');?></label>

        				

        			</div>

        			<div style="margin-left:3px;font-weight:bold;">

        				<label>Total : <?php echo number_format($amount,2,'.','');?>&nbsp;&nbsp;&nbsp;

        					<?php echo date('h:i A', strtotime(date('d-m-Y H:i:s')));?></label>

        			</div>

        		   <?php }?>

            

        			<div style="margin-left:3px;">

        				<label>EMP-ID : <?php echo $estimation['emp_code'].' / '.date('h:i A', strtotime(date('d-m-Y H:i:s')));?></label>

        			</div><br><br><br><br>

        				<div class="breif_copy">

    				<label><b>BRIEF COPY</b></label>

    			</div><br>

    			<div style="margin-top:4px;font-size:10px;width:100%;">

    				<label><b><?php echo $estimation['estimation_datetime'].' , Estimation - '. $estimation['esti_no'];?></b></label>

    				<br><b><label><?php echo $estimation['customer_name'].($estimation['village_name']!='' ? ' / '.$estimation['village_name']:'').' / '.$estimation['mobile']; ?></b></label><br>

    				<?php 

    				if(sizeof($est_other_item['item_details'])>0)

    				{

    					foreach($est_other_item['item_details'] as $items){?>

    					<br><span style="font-size:10px;"><?php echo $items['product_name'].' / '.($items['design_name']!='' ? $items['design_name'].' / ' : '').($items['calculation_based_on']!=3 && $items['calculation_based_on']!=4 ? $items['gross_wt']:$items['item_cost']).($items['size']!='' ? ' / '.$items['size']:'').($items['tag_id']!='' ? ' / '.$items['tag_code'] :'');?></span>

    				<?php }?>

    				<p></p>

    				<?php }?>

    					<?php foreach($est_other_item['old_matel_details'] as $items){?>

    					<br><label><?php echo $items['metal'].' / '.$items['gross_wt'].' / '.$items['amount'].'/'.(number_format((((($items['amount']/$items['gross_wt'])/$items['goldrate_24ct'])*100)/10000),5,'.',''));?></label>

    				<?php }?>

    				

    				<br><label>EMP-ID : <?php echo $estimation['emp_code'].' / '.date('h:i A', strtotime(date('d-m-Y H:i:s')));?></label>

    			</div><p></p>

            </div>

        </div>

	 </span>          

</body></html>
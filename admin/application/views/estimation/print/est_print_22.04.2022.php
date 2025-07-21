<html><head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Estimation</title>
		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/estimation.css">
	<style type="text/css">
		body, html {
		margin-bottom:0
		}
		.alignLeft {
			text-align: left;
		}
		.alignRight {
			text-align: right;
		}
		.item_name {
			font-weight: bold;
		}
		.finalTotal {
			width: 109.6%;
			font-size: 18px !important;
		}
		 @page { 
		 	size: 78mm;
			margin-bottom: 150px !important;
		 } 
		 
		 /* output size */
		 span { display: inline-block; }
	</style>
</head>
<?php
function moneyFormatIndia($num) {
	return preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $num);
}
?>
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
				<table class="metal_rate" width="110%">
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
        		    <?php if(sizeof($est_other_item['item_details']) && $est_other_item['item_details'][0]['id_orderdetails']=='' && (sizeof($est_other_item['chit_details'])==0)){?>
        			<table class="estimation" style="width:110%;">
        				<tr>
        					<th  class="alignLeft" width="20%">ITEM</th>
        					<th  class="alignRight" width="15%">WT</th>
        					<th  class="alignRight" width="15%">MC</th>
        					<th  class="alignRight" width="25%">VALUE</th>
        				</tr>
						<tr>
						<td><hr class="item_dashed"></td>
						</tr>
        				<!--</thead>-->
        				<tbody>
        					<?php 
        					    $tot_payable=0;
								$tot_purchase=0;
        					    $market_rate_cost=0;
        					    $total_wt=0;
        					    $total_gwt=0;
        					    $net_wt=0;
        					    $total_piece=0;
        					    $total_net_wt=0;
        					    $tag_net_wt=0;
        					    $making_charge=0;
								$total_tax=0;
								$sub_total=0;
        					    
        					    
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
				                
				                
				                $item_no = 1;
        						foreach($est_other_item['item_details'] as $items){
        						$making_charge=0;
        						$stone_price=0;
								$stone_weight=0;
								$stone_piece=0;
								
								$charge_price=0;
        						
        						$certification_cost=0;
        						
        						$total_piece+=$items['piece'];
        						
        						$total_gwt+=$items['gross_wt'];
        						
        						$total_wt+=$items['net_wt'];
        						
								$tot_payable+=$items['item_cost'];
        						
        						$market_rate_cost+=$items['market_rate_cost'];
								
								$payable_without_tax = $items['item_cost']-$items['item_total_tax'];
								$sub_total += $payable_without_tax;
								
								$total_tax += $items['item_total_tax'];
        						
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
        							
        							$making_charge=($items['mc_type']==2 ? $items['gross_wt']*$items['mc_value'] : $items['mc_value']*1);
        							
        						}
        						foreach($items['stone_details'] as $stone)
        						{
        							$stone_price+=$stone['price'];
									$stone_weight+=$stone['wt'];
									$stone_piece+=$stone['pieces'];
        							$certification_cost+=$stone['certification_cost'];
        						}
        						
        						foreach($items['charges'] as $charge)
        						{
        						    $charge_price+=$charge['amount'];
        						}
        					?>
        					<tr style="margin-top:2px !important;">
        						<td colspan="4" class="item_name"><?php echo $item_no; ?>) <?php echo substr($items['design'],0,15);?> (<?php echo $items['tag_code'] ?>)</td>
        					</tr>
							
							<tr style="margin-top:2px !important;">
        						<td ></td>
        						<td class="alignRight"><?php echo $items['gross_wt'];?></td>
        						<td class="alignRight"><?php echo $making_charge;?></td>
        						<td class="alignRight"><?php echo moneyFormatIndia(number_format($items['item_cost']-$charge_price-$items['item_total_tax']-$stone_price,2,'.',''));?></td>
        					</tr>
        					<?php if($items['less_wt']>0){?>
        					
        					<tr>
        						<td >LESS WT</td>
        						<td class="alignRight"><?php echo $items['less_wt'];?></td>
        						<td></td>
        						<td></td>
        					</tr>
        					
        					<tr>
        						<td >NET WT</td>
        						<td  class="alignRight"><?php echo $items['net_wt'];?></td>
        						<td></td>
        						<td></td>
        					</tr>
        					
        					<?php }?>
        					<tr>
        						<td >Va(%)</td>
        						<td  class="alignRight"><?php  echo ($items['wastage_percent']).'%'?></td>
        						<td></td>
        						<td></td>
        					</tr>
        					<?php if($stone_price>0){?>
        					<tr>
        						<td >ST CHAR</td>
        						<td class="alignRight"><?php echo "Pcs : " .$stone_piece; ?></td>
								<td> </td>
        						<td class="alignRight"><?php echo moneyFormatIndia(number_format($stone_price,2,'.',''));?></td>
        					</tr>
        					<?php }?>
							
							<?php if(sizeof($items['charges']) > 0) { ?>
								<?php foreach($items['charges'] as $charge) { 
									?>
									<tr>
										<td ><?php echo $charge['code_charge'] ?></td>
										<td></td>
										<td></td>
										<td class="alignRight"><?php echo moneyFormatIndia(number_format($charge['amount'],2,'.',''));?></td>
									</tr>
							<?php } } ?>
        					<?php if($certification_cost>0){?>
        					<tr>
        						<td >CERTIFICATION CHARGE</td>
        						<td></td>
        						<td></td>
        						<td class="alignRight"><?php echo moneyFormatIndia(number_format($certification_cost,2,'.',''));?></td>
        					</tr>
        					<?php }?>
        					<tr style="display:none">
        						<td><?php echo $items['tgrp_name'];?></td>
        						<td></td>
        						<td></td>
        						<td class="alignRight"><?php echo moneyFormatIndia(number_format($items['item_total_tax'],2,'.',''));?></td>
        					</tr>
        					<?php $item_no ++; } ?>
        					<tr>
        						<td><hr class="tot_dashed"></td>
        					</tr>
							<tr>
        						<td >SUB TOTAL</td>
        						<td></td>
        						<td></td>
        						<td class="alignRight"><?php echo moneyFormatIndia($sub_total);?></td>
        					</tr>
							<tr>
        						<td >CGST <?php echo $items['tax_percentage'] / 2 ?>%</td>
        						<td></td>
        						<td></td>
        						<td class="alignRight"><?php echo moneyFormatIndia(number_format($total_tax/2,2,'.',''));?></td>
        					</tr>
							<tr>
        						<td >SGST <?php echo $items['tax_percentage'] / 2 ?>%</td>
        						<td></td>
        						<td></td>
        						<td class="alignRight"><?php echo moneyFormatIndia(number_format($total_tax/2,2,'.',''));?></td>
        					</tr>
							<tr>
        						<td><hr class="tot_dashed"></td>
        					</tr>
        					<?php if($tot_adv_paid>0){?>
            					<tr>
            						<td >Adv Paid</td>
            						<td></td>
            						<td></td>
            						<td class="alignRight"><?php echo moneyFormatIndia(number_format($tot_adv_paid,2,'.',''));?></td>
            					</tr>
        					<?php }?>
        					
        					<tr style="font-weight:bold;">
        						<td class="alignRight"><?php echo number_format($total_gwt,3,'.','');?></td>
        						<td class="alignRight"><?php echo number_format($total_wt,3,'.','');?></td>
                                <td class="alignRight"><?php echo $total_piece;?></td>
        						<td class="alignRight">
									
								<?php echo moneyFormatIndia(number_format($tot_payable-$tot_adv_paid,2,'.','')); ?></td>
        					</tr>
							
							<tr>
        						<td><hr class="tot_dashed"></td>
        					</tr>
        				</tbody>
        				</table>
						<?php if($tag_net_wt!=0){?>
        			<div>
        				<label>PARTLY (<?php echo number_format($tag_net_wt,3,'.','').'-'.number_format($net_wt,3,'.','')?>): <?php echo number_format(($tag_net_wt-$net_wt),3,'.','');?></label>
        			</div>
        			<?php }} else  if(sizeof($est_other_item['advance_details'])>0){?>
        			 <table class="estimation" style="width:10%;">
        				<tr>
        					<th width="15%">ITEM</th>
        					<th width="10%">WT</th>
        					<th width="10%">V.A</th>
        					<th width="25%">TOT WT</th>
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
            							$stone_price+=$stone['price'];
            							$certification_cost+=$stone['certification_cost'];
            						}
            					    $total_wastage_wt+=$wast_wgt;
            					    $total_making_charge+=$making_charge;
            					?>
            					<tr>
            						<td><hr class="item_dashed"></td>
            					</tr>
            					<tr>
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
            					
            					<tr>
            						<td >TOTAL</td>
            						<td></td>
            						<td></td>
            						<td style="text-align:right;"><?php echo moneyFormatIndia(number_format($total_wt+$total_wastage_wt,3,'.',''));?></td>
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
                					<tr>
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
                						<td style="text-align:right;"><?php echo moneyFormatIndia(number_format($balance_pay_amt,2,'.',''));?></td>
                					</tr>
                					<?php if($stone_price>0){?>
                						<tr>
                    						<td >STONE</td>
                    						<td></td>
                    						<td></td>
                    						<td style="text-align:right;"><?php echo moneyFormatIndia(number_format($stone_price,2,'.',''));?></td>
                    					</tr>
                					<?php }?>
                					<tr>
                						<td>Tot Mc</td>
                						<td></td>
                						<td></td>
                						<td style="text-align:right;"><?php echo moneyFormatIndia(number_format($total_making_charge,2,'.',''));?></td>
                					</tr>
                					
                					<tr>
                						<td>GST<?php echo ' '.$est_other_item['item_details'][0]['tgrp_name'];?></td>
                						<td></td>
                						<td></td>
                						<td style="text-align:right;"><?php echo moneyFormatIndia(number_format($total_tax_amt,2,'.',''));?></td>
                					</tr>
                					
                					<tr>
                						<td>Net Amt</td>
                						<td></td>
                						<td></td>
                						<td style="text-align:right;"><?php echo moneyFormatIndia(number_format($tot_payable-$tot_adv_paid,2,'.',''));?></td>
                					</tr>
            					
            				</tbody>
        				</table>
        					<br>
                        <?php if($tag_net_wt!=0){?>
                        <div >
                        <label>PARTLY (<?php echo number_format($tag_net_wt,3,'.','').'-'.number_format($net_wt,3,'.','')?>): <?php echo number_format(($tag_net_wt-$net_wt),3,'.','');?></label>
                        </div>
                        <?php }?>
        			<?php }else if(sizeof($est_other_item['chit_details'])>0){?>
        			 <table class="estimation" style="width:110%;">
        				<tr>
        					<th class="alignLeft" width="15%">ITEM</th>
        					<th class="alignLeft" width="15%">WT</th>
        					<th class="alignLeft" width="10%">V.A</th>
        					<th class="alignLeft" width="25%">TOT WT</th>
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
            					    $charge_price=0;
            					    
            					    $item_no = 1;
            					    $chit_amount   =0;
    				                if(sizeof($est_other_item['chit_details'])>0)
    				                {
    				                    foreach($est_other_item['chit_details'] as $advance)
                					    {
                					            $chit_amount+=$advance['utl_amount'];
                					    }
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
            						
            						foreach($items['charges'] as $charge)
            						{
            						    $charge_price+=$charge['amount'];
            						}
        						
            					    $total_wastage_wt+=$wast_wgt;
            					    $total_making_charge+=$making_charge;
            					?>
            					<tr>
            						<td><hr class="item_dashed"></td>
            					</tr>
            					
            					<tr style="margin-top:2px !important;">
            						<td colspan="4" class="item_name"><?php echo $item_no; ?>) <?php echo substr($items['design'],0,15);?> (<?php echo $items['tag_code'] ?>)</td>
            					</tr>
        					
                				<tr style="margin-top:2px !important;">
            						<td ></td>
            						<td class="alignRight"><?php echo $items['gross_wt'];?></td>
            						<td class="alignRight"><?php echo $wast_wgt;?></td>
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
            						<td style="text-align:right;"><?php echo moneyFormatIndia(number_format($total_wt+$total_wastage_wt,3,'.',''));?></td>
            					</tr>
            					
            					<tr>
            						<td><hr class="tot_dashed"></td>
            					</tr>
            					
            					<tr>
                                    <td>S.No</td>
                                    <td width="20%;">Acc No</td>
                                    <td style="text-align:right;">Amount</td>
                                    <td style="text-align:right;">Weight</td>
                                </tr>
                                
                                <tr>
            						<td><hr class="tot_dashed"></td>
            					</tr>
                                        
            					<?php 
                					$i=1;
                					$total_chit_amt=0;
                					$total_chit_weight=0;
                					 foreach($est_other_item['chit_details'] as $chit)
                					 {
                					    $total_chit_amt+=$chit['utl_amount'];
                					    $total_chit_weight+=($chit['scheme_type']==2 || $chit['scheme_type']==3 ?$chit['closing_balance'] :0);
                					   
                					 ?>
                                        <tr>
                                            <td><?php echo $i;?></td>
                                            <td ><?php echo $chit['scheme_acc_number'];?></td>
                                            <td style="text-align:right;"><?php echo number_format($chit['utl_amount'],2,'.','');?></td>
                                            <td style="text-align:right;"><?php echo  ($chit['scheme_type']==2 || $chit['scheme_type']==3 ? number_format($chit['closing_balance'],3,'.','') :'-');?></td>
                                        </tr>
                    			<?php $i++; 
                    			    $balance_pay_amt=$taxable_amt-$total_chit_amt-$total_making_charge-$stone_price-$charge_price;
                    			}	
                    			?>
                					
            					<tr>
            						<td><hr class="tot_dashed"></td>
            					</tr>
            					
            					<tr style="font-weight:bold;">
                                    <td>Total</td>
                                    <td ></td>
                                    <td style="text-align:right;"><?php echo number_format($total_chit_amt,2,'.','');?></td>
                                    <td style="text-align:right;"><?php echo number_format($total_chit_weight,3,'.','');?></td>
                                </tr>
            					
            					
            				
                					<tr>
            						    <td><hr class="tot_dashed"></td>
            					    </tr>
                					
                				     <tr>
                					    <td>Bal Wt</td>
                					    <td></td>
                					    <td></td>
                					    <td style="text-align:right;"><?php echo number_format(($total_wt+$total_wastage_wt-$total_chit_weight),3,'.','');?></td>
                					</tr>
                				
                    				<tr>
                						<td>Bal Amt</td>
                						<td></td>
                						<td></td>
                						<td style="text-align:right;"><?php echo moneyFormatIndia(number_format($balance_pay_amt,2,'.',''));?></td>
                					</tr>
                					<?php if($stone_price>0){?>
                						<tr>
                    						<td >STONE</td>
                    						<td></td>
                    						<td></td>
                    						<td style="text-align:right;"><?php echo moneyFormatIndia(number_format($stone_price,2,'.',''));?></td>
                    					</tr>
                					<?php }?>
                					<tr>
                						<td>Tot Mc</td>
                						<td></td>
                						<td></td>
                						<td style="text-align:right;"><?php echo moneyFormatIndia(number_format($total_making_charge,2,'.',''));?></td>
                					</tr>
                					
                					<tr>
                						<td colspan="2">Tot Charge</td>
                						<td></td>
                						<td style="text-align:right;"><?php echo moneyFormatIndia(number_format($charge_price,2,'.',''));?></td>
                					</tr>
                					
                					<tr>
                						<td colspan="2">GST<?php echo ' '.$est_other_item['item_details'][0]['tgrp_name'];?></td>
                						<td></td>
                						<td style="text-align:right;"><?php echo moneyFormatIndia(number_format($total_tax_amt,2,'.',''));?></td>
                					</tr>
                					
                					<tr style="font-weight:bold;">
                						<td>Net Amt</td>
                						<td></td>
                						<td></td>
                						<td style="text-align:right;"><?php echo moneyFormatIndia(number_format($tot_payable-$chit_amount,2,'.',''));?></td>
                					</tr>
            					
            				</tbody>
        				</table>
        			<?php }?>
        		
        				<?php 
        
        				if(sizeof($est_other_item['old_matel_details'])>0){?>
        					<div>
        						<p style="text-transform:uppercase;text-align:left; font-weight: bold">purchase items</p>
        					</div>
        				<table class="purchase" width="120%">
        					<tr>
							<th width="20%">METAL</th>
        					<th width="20%" class="alignRight">GR WT</th>
        					<th width="15%" class="alignRight">V.A</th>
        					<th width="20%" class="alignRight">RATE</th>
        					<th width="25%" class="alignRight">VALUE</th>
        				</tr>
						<tr>
							<td><hr class="item_dashed"></td>
						</tr>
        				<tbody>
        					<?php 
        					$gross_wt=0;
							$total_va=0;
        					$amount=0;
        					foreach($est_other_item['old_matel_details'] as $data){
        					$gross_wt+=$data['gross_wt'];
							$total_va+=$data['wastage_wt'];
        					$amount+=$data['amount'];
        					?>
        					<tr>
								<td><?php echo $data['old_metal_type'];?></td>
        						<td class="alignRight"><?php echo $data['gross_wt'];?></td>
        						<td class="alignRight"><?php echo $data['wastage_wt'];?></td>
        						<td class="alignRight"><?php echo $data['rate_per_gram'];?></td>
        						<td class="alignRight"><?php echo moneyFormatIndia($data['amount']);?></td>
        					</tr>
        					<?php }?>
						
							<tr>
        						<td><hr class="item_dashed"></td>
        					</tr>
        				</tbody>
        				<tr>
        					<td></td>
        					<td class="alignRight"><?php echo number_format($gross_wt,3,'.','');?></td>
        					<td class="alignRight"><?php echo number_format($gross_wt-$total_va,3,'.','');?></td>
        					<td class="alignRight"></td>
        					<td class="alignRight"><?php 
							$tot_purchase = number_format($amount,2,'.',''); 
							echo moneyFormatIndia($tot_purchase);
							?>
							</td>
        				</tr>
						<tr>
							<td><hr class="item_dashed"></td>
						</tr>
						<tr>
							<td colspan="4" class="alignRight">Sales :</td>
							<td class="alignRight"><?php echo moneyFormatIndia(number_format(($tot_payable),2,'.',''));?></td>
						</tr>
						<tr>
							
							<td colspan="4" class="alignRight">Purchase :</td>
							<td class="alignRight"> <?php echo moneyFormatIndia(number_format(($tot_purchase),2,'.',''));?></td>
						</tr>
						<tr>
							
							<td colspan="4" class="alignRight">Total :</td>
							<td class="alignRight"> <?php echo moneyFormatIndia(number_format(($tot_payable-$tot_purchase),2,'.',''));?></td>
						</tr>
        			</table>
                    <!--<p></p>
                    <div style="margin-left:3px;">
        				<label><?php echo ($est_other_item['old_matel_details'][0]['purpose']==1 ? 'Cash' :'Exchange');?></label>
        				
        			</div>
        			<div style="margin-left:3px;font-weight:bold;">
        				<label>Total : <?php echo number_format($amount,2,'.','');?>&nbsp;&nbsp;&nbsp;
        					<?php echo date('h:i A', strtotime(date('d-m-Y H:i:s')));?></label>
        			</div>-->
        		   <?php }?>
					<!--<table width="120%">
						<tr >
							<td width="20%"></td>
							<td width="10%"></td>
							<td width="10%"></td>
							<td width="60%" class="alignRight"><span style="font-size: 18px !important">Rs. <?php echo moneyFormatIndia(number_format(($tot_payable-$tot_purchase),2,'.',''));?></span></td>
						</tr>
						
				   </table>-->
				   <p class="alignRight finalTotal">Rs. <?php echo moneyFormatIndia(number_format(($tot_payable-$tot_purchase-$chit_amount),2,'.',''));?></p>
        		   <div><label>EMP-ID : <?php echo $estimation['id_employee'].' / '.$estimation['emp_name'].' / '.date('h:i A', strtotime(date('d-m-Y H:i:s')));?></label></div>
        			
					
            </div>
        </div>
	 </span>          
</body></html>
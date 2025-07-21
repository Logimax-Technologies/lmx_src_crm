<html><head>
		<meta charset="utf-8">
		<title>Payment Report</title>
		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/billing_receipt.css">
		<!--	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/receipt_temp.css">-->
		<style >
		 .head
		 {
			 color: black;
			 font-size: 50px;
		 }
         </style>
</head><body>
<span class="PDFReceipt">
            <div>
                <img alt="" src="<?php echo base_url();?>assets/img/receipt_logo.png">
            </div>
            <div class="address" align="right">
				 <?php echo (!empty($comp_details['address1']) ? $comp_details['address1'].' ,':''); ?>				
				 <?php echo (!empty($comp_details['address2']) ? $comp_details['address2'].' ,':''); ?>				
				<?php echo $comp_details['city'].'  '.$comp_details['pincode'].' ,'; ?>
				<?php echo $comp_details['state'].' ,'; ?>
				<?php echo $comp_details['country'].' .'; ?>
			</div>
			<div class="heading"><h2><?php echo ($billing['bill_type']==1 ? 'Sales Receipt': ($billing['bill_type']==2 ? 'Sales And Purchase Receipt':($billing['bill_type']==3 ? 'Sales and Purchase and Return Receipt' :($billing['bill_type']==4 ? 'Purchase Bill':($billing['bill_type']==5 ? 'Order Advance Receipt' :($billing['bill_type']==6 ? 'Advance Receipt' :($billing['bill_type']==7 ? 'Sales Return Receipt':'Credit Collection Receipt')))))))?></h2></div>
			<div class="row">
				<table class="meta" style="width: 40%;" align="left">
					<tr>
						<th><span>Gold Rate</span></th>
						<td><span><?php echo $metal_rate['goldrate_22ct'].'/'.'Gm'; ?></span></td>
					</tr><br><br>
					<tr>
						<th><span>Silver Rate</span></th>
						<td><span><?php echo $metal_rate['silverrate_1gm'].'/'.'Gm'; ?></span></td>
					</tr>
				</table>		
				<table class="meta" style="width: 40%;margin-top:-3%;" align="right">
					<tr>
						<th><span>Bill No</span></th>
						<td><span><?php echo $billing['bill_no'].($billing['print_taken']>0 ? '-Copy' :''); ?></span></td>
					</tr><br><br>
					<tr>
						<th><span>Customer</span></th>
						<td><span><?php echo $billing['cus_name']; ?></span></td>
					</tr>
				</table>
			</div>

			<p></p><br>
<div  class="content-wrapper">
 <div class="box">
  <div class="box-body">
 			<div  class="container-fluid">
				<div id="printable">
					<?php if(sizeof($est_other_item['item_details'])>0 && $billing['bill_type']!=5){?>
						<div  class="row">
							<div class="table_content" style="text-align:center;text-transform: uppercase;">
									<span>Sale Item Details</span>
							</div>
							<div class="col-xs-12">
								<div class="table-responsive">
								<table id="pp" class="table text-center">
									<!--	<thead> -->
											<tr>
												<th width="5%;">S.No</th>
												<th width="6%;">HSN Code</th>
												<th>Description</th>
												<th width="5%;">PCS</th>
												<th width="7%;">Gwt(g)</th>
												<th width="7%;">Nwt(g)</th>
												<th width="7%;">V.A</th>
												<th width="7%;">MC</th>
												<th>Tax Amt / %</th>
												<th>Taxable Amt</th>
												<th>Amount</th>
											</tr>
										<!--</thead>
										<tbody>-->
										<?php
										$i=1; 
										$pieces=0;
										$gross_wt=0;
										$net_wt=0;
										$discount=0;
										$taxable_amt=0;
										$tot_tax=0;
										$sales_cost=0;
										$tot_tax_per=0;
										foreach($est_other_item['item_details'] as $items)
											{
												$pieces+=$items['piece'];
												$gross_wt+=$items['gross_wt'];
												$net_wt+=$items['net_wt'];
												$discount+=$items['discount'];
												$tot_tax+=$items['item_total_tax'];
												$sales_cost+=$items['item_cost'];
												$taxable_amt+=$items['item_cost']-$items['item_total_tax'];
												$item_taxable=number_format((float)$items['item_cost']-$items['item_total_tax'],2,'.','');
												$tax_percentage=number_format(($items['item_total_tax']*100)/$item_taxable,'2','.','');
												$tot_tax_per+=$tax_percentage;
												if($items['calculation_based_on']==0)
												{
												    $wastge_wt=($items['gross_wt']*($items['wastage_percent']/100));
												}else if($items['calculation_based_on']==1)
												{
												    $wastge_wt=($items['net_wt']*($items['wastage_percent']/100));
												}else if($items['calculation_based_on']==2)
												{
												    $wastge_wt=($items['net_wt']*($items['wastage_percent']/100));
												}
											?>
											<tr>
											<td><?php echo $i;?></td>
											<td><?php echo $items['hsn_code'];?></td>
											<td><?php echo $items['product_name'];?></td>
											<td><?php echo $items['piece'];?></td>
											<td><?php echo $items['gross_wt'];?></td>
											<td><?php echo $items['net_wt'];?></td>
											<td><?php echo number_format($wastge_wt,3,'.','');?></td>
											<td><?php echo $items['mc_value'];?></td>
											<td><?php echo $items['item_total_tax'].' / '.$tax_percentage;?></td>
											<td><?php echo $item_taxable;?></td>
											<td><?php echo $items['item_cost'];?></td>
											</tr>
										<?php $i++;}?>
									<!--</tbody> -->
										<tfoot style="border: 0px;">
											<tr style="font-weight: bold;">
												<td>Total</td>
												<td></td>
												<td></td>
												<td><?php echo $pieces;?></td>
												<td><?php echo $gross_wt;?></td>
												<td><?php echo $net_wt;?></td>
												<td></td>
												<td></td>
												<td><?php echo $tot_tax.'/'.$tot_tax_per;?></td>
												<td><?php echo number_format((float)$taxable_amt,2,'.','');?></td>
												<td><?php echo number_format((float)$sales_cost,2,'.','');?></td>
											</tr>
									
											<tr style="font-weight: bold;">
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td>Total</td>
												<td>Rs.</td>
												<td><?php echo number_format((float)$sales_cost,'2','.','');?></td>
											</tr>
										</tfoot>
									</table><br>	
									
									
								</div>	
							 </div>	
						</div><br><br><br>
						<?php }?>

						<?php if(sizeof($est_other_item['item_details'])>0 && $billing['bill_type']==5){?>

						<div  class="row">
							<div class="col-xs-12">
								<div class="table-responsive">
									<div align="center">
										<label><b>Order No:<?php echo $est_other_item['advance_details']['order_no'];?></b></label>
									</div>

								<table id="pp" class="table text-center">
									<!--	<thead> -->
											<tr>
												<th>S.No</th>
												<th>HSN Code</th>
												<th>Description</th>
												<th>PCS</th>
												<th>Gwt(g)</th>
												<th>Nwt(g)</th>
												<th>V.A</th>
												<th>MC</th>
												<th>Discount</th>
												<th>Approx Amt</th>
											</tr>
										<!--</thead>
										<tbody>-->
										<?php
										$i=1; 
										$pieces=0;
										$gross_wt=0;
										$net_wt=0;
										$discount=0;
										$tot_tax=0;
										$sales_cost=0;
										foreach($est_other_item['item_details'] as $items)
											{
												$pieces+=$items['piece'];
												$gross_wt+=$items['gross_wt'];
												$net_wt+=$items['net_wt'];
												$discount+=$items['discount'];
												$sales_cost+=$items['item_cost'];
												if($items['calculation_based_on']==0)
												{
												    $wastge_wt=($items['gross_wt']*($items['wastage_percent']/100));
												}else if($items['calculation_based_on']==1)
												{
												    $wastge_wt=($items['net_wt']*($items['wastage_percent']/100));
												}else if($items['calculation_based_on']==2)
												{
												    $wastge_wt=($items['net_wt']*($items['wastage_percent']/100));
												}
											?>
											<tr>
											<td><?php echo $i;?></td>
											<td><?php echo $items['hsn_code'];?></td>
											<td><?php echo $items['product_name'];?></td>
											<td><?php echo $items['piece'];?></td>
											<td><?php echo $items['gross_wt'];?></td>
											<td><?php echo $items['net_wt'];?></td>
										    <td><?php echo number_format($wastge_wt,3,'.','');?></td>
											<td><?php echo $items['mc_value'];?></td>
											<td><?php echo $items['discount'];?></td>
											<td><?php echo $items['item_cost'];?></td>
											</tr>
										<?php $i++;}?>
									<!--</tbody> -->
										<tfoot style="border: 0px;">
											<tr style="font-weight: bold;">
												<td>Total</td>
												<td></td>
												<td></td>
												<td><?php echo $pieces;?></td>
												<td><?php echo $gross_wt;?></td>
												<td><?php echo $net_wt;?></td>
												<td></td>
												<td></td>
												<td><?php echo $discount;?></td>
												<td><?php echo number_format((float)$sales_cost,2,'.','');?></td>
											</tr>
										</tfoot>
									</table><br>	
									
									
								</div>	
							 </div>	
						</div><br><br><br>
						<?php }?>
						<?php if(sizeof($est_other_item['old_matel_details'])>0){?>
						<div  class="row">
						        <div class="table_content" style="text-align:center;text-transform: uppercase;">
									<span>Purchase Item Details</span>
							    </div>
								
							<div class="col-xs-12">
								<div class="table-responsive">
								<table id="pp" class="table text-center">
									<!--	<thead> -->
											<tr>
												<th>S.No</th>
												<th>Metal</th>
												<th>Gwt(g)</th>
												<th>Nwt(g)</th>
												<th>V.A</th>
												<th>Rate</th>
												<th>Amount</th>
											</tr>
										<!--</thead>
										<tbody>-->
										<?php
										$i=1; 
										$total_amt=0;
										$pieces=0;
										$gross_wt=0;
										$net_wt=0;
										foreach($est_other_item['old_matel_details'] as $items)
											{
												$pur_total_amt+=$items['amount'];
												$gross_wt+=$items['gross_wt'];
												$net_wt+=($items['gross_wt']-$items['stone_wt']-$items['dust_wt']);
											?>
											<tr>
											<td><?php echo $i;?></td>
											<td><?php echo ($items['metal_type']==1 ?'Gold' :'Silver');?></td>
											<td><?php echo $items['gross_wt'];?></td>
											<td><?php echo ($items['gross_wt']-$items['stone_wt']-$items['dust_wt']);?></td>
											<td><?php echo $items['wast_wt'];?></td>
											<td><?php echo $items['rate_per_gram'];?></td>
											<td><?php echo $items['amount'];?></td>
											</tr>
										<?php $i++;}?>
									<!--</tbody> -->
										<tfoot style="border: 0px;font-weight: bold;">
											<tr>
												<td>Total</td>
												<td></td>
												<td><?php echo $gross_wt;?></td>
												<td><?php echo $net_wt;?></td>
												<td></td>
												<td></td>
												<td><?php echo number_format((float)$pur_total_amt,2,'.','');?></td>
											</tr>
										</tfoot>
									</table><br>	
								</div>	
							 </div>	
						</div><br>
						<?php }?>
					
						<?php if(sizeof($est_other_item['return_details'])>0){?>
							<div  class="row">
							<div class="col-xs-12">
							
								 <div class="table_content" style="text-align:center;text-transform: uppercase;">
									<span>>Return Item Details</span>
							    </div>
								<div class="table-responsive">
								<table id="pp" class="table text-center">
									<!--	<thead> -->
											<tr>
												<th>S.No</th>
												<th>HSN Code</th>
												<th>Description</th>
												<th>PCS</th>
												<th>Gwt(g)</th>
												<th>Nwt(g)</th>
												<th>Wastage</th>
												<th>MC</th>
												<th>Taxable Amt</th>
											</tr>
										<!--</thead>
										<tbody>-->
										<?php
										$i=1; 
										$pieces=0;
										$gross_wt=0;
										$net_wt=0;
										$return_item_cost=0;
										foreach($est_other_item['return_details'] as $items)
											{
												$pieces+=$items['piece'];
												$gross_wt+=$items['gross_wt'];
												$net_wt+=$items['net_wt'];
												$return_item_cost+=$items['return_item_cost'];
											?>
											<tr>
											<td><?php echo $i;?></td>
											<td><?php echo $items['hsn_code'];?></td>
											<td><?php echo $items['product_name'];?></td>
											<td><?php echo $items['piece'];?></td>
											<td><?php echo $items['gross_wt'];?></td>
											<td><?php echo $items['net_wt'];?></td>
											<td><?php echo $items['wastage_percent'];?></td>
											<td><?php echo $items['mc_value'];?></td>
											<td><?php echo $items['return_item_cost'];?></td>
											</tr>
										<?php $i++;}?>
									<!--</tbody> -->
										<tfoot style="border: 0px;">
										
											<tr style="font-weight: bold;">
												<td>Total</td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td><?php echo '-'.number_format((float)$return_item_cost,'2','.','');?></td>
											</tr>
											<?php if($billing['bill_type']==3){?>
											<tr style="font-weight: bold;">
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td>Sales</td>
												<td><?php echo number_format((float)$sales_cost,'2','.','');?></td>
											</tr>
											<tr style="font-weight: bold;">
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td>Purchase</td>
												<td><?php echo number_format((float)$total_amt,'2','.','');?></td>
											</tr>
											<tr style="font-weight: bold;">
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td>Return Sales</td>
												<td><?php echo number_format((float)$return_item_cost,'2','.','');?></td>
											</tr>
											<tr style="font-weight: bold;">
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td>Net Amount</td>
												<td><?php echo number_format((float)
												$sales_cost-$total_amt-$return_item_cost,'2','.','');?></td>
											</tr>
											<?php }?>
										</tfoot>
									</table>
									<p>Returned Bill No : <?php echo $billing['return_bill_no'];?></p>
									<br>
								</div>	
							 </div>	
						</div><br><br><br>
						<?php }?>


						<?php if(sizeof($est_other_item['return_details'])>0 || sizeof($est_other_item['old_matel_details'])>0 || sizeof($est_other_item['item_details'])>0 && ($billing['bill_type']!=5)){?>
						<div  class="row">
							<div class="col-xs-12">
								
								 <div class="table_content" style="text-align:center;text-transform: uppercase;">
									<span>Total Summary Details</span>
							    </div>
									<div class="table-responsive">
										<table id="pp" class="table text-center" style="font-weight: bold;">
										<tr>
											<td>Total Sale Amount</td>
											<td><?php echo number_format((float)$sales_cost,2,'.','');?></td>
										</tr>
										<tr>
											<td>Total Purchase Amount</td>
											<td><?php echo number_format((float)$pur_total_amt,2,'.','');?></td>
										</tr>
										<tr>
											<td>Total Sales Return Amount</td>
											<td><?php echo number_format((float)$return_item_cost,'2','.','');?></td>
										</tr>
										<tr>
											<td>Net Amount</td>
											<td><?php echo number_format((float)($sales_cost-$pur_total_amt-$return_item_cost),'2','.','');?></td>
										</tr>
										</table>
									</div>
							</div>
						</div><br>
						<?php }?>
						<?php if($billing['bill_type']==8){?>
							<p>The Credit Bill Collection Bill Number : <?php echo $billing['return_bill_no'];?>  </p>
						
						<?php }?>
						<?php if(sizeof($payment['pay_details'])>0){?>
							<div  class="row">
							<div class="col-xs-12">
							     <div class="table_content" style="text-align:center;text-transform: uppercase;">
									<span>Payment Details</span>
							    </div>
								<div class="table-responsive">
								<table id="pp" class="table text-center">
									<!--	<thead> -->
											<tr>
												<th>S.No</th>
												<th>Payment Mode</th>
												<th>Amount</th>
											</tr>
										<!--</thead>
										<tbody>-->
										<?php
										$i=1;
										$total_amt=0;
										foreach($payment['pay_details'] as $items)
											{
												$total_amt+=$items['payment_amount'];
											?>
											<tr>
											<td><?php echo $i;?></td>
											<td><?php echo $items['payment_mode'];?></td>
											<td><?php echo $items['payment_amount'];?></td>
											</tr>
										<?php $i++;}?>
										<?php if($payment['advance_adj']['advance_amount']!=''){
											$adv_adj=$payment['advance_adj']['advance_amount'];
										?>
										<tr>
											<td><?php echo $i++;?></td>
											<td>Adnance Adj</td>
											<td><?php echo $adv_adj;?></td>
										</tr>
										<?php }?>
									<!--</tbody> -->
										<tfoot style="border: 0px;">
											<tr style="font-weight: bold;">
												<td>Total</td>
												<td></td>
												<td><?php echo number_format((float)$total_amt,2,'.','');?></td>
											</tr>
											<?php if($billing['bill_type']!=6 && $billing['bill_type']!=8){?>
											<tr style="font-weight: bold;">
												<td>Balance To Pay</td>
												<td></td>
												<td><?php echo number_format((float)(($sales_cost-$pur_total_amt-$adv_adj)-$total_amt),2,'.','');?></td>
											</tr>
											<?php }?>
											<?php if($billing['bill_type']==5){?>
												<tr style="font-weight: bold;">
													<td></td>
													<td>Total Bill Amount</td>
													<td><?php echo number_format((float)$sales_cost,2,'.','');?></td>
												</tr>
												<tr style="font-weight: bold;">
													<td></td>
													<td>Advance Paid</td>
													<td><?php echo number_format($est_other_item['advance_details']['advance_amount'],2,'.','');?></td>
												</tr>
												<tr style="font-weight: bold;">
													<td></td>
													<td>Balance</td>
													<td><?php echo number_format((float)$sales_cost-$est_other_item['advance_details']['advance_amount'],2,'.','');?></td>
												</tr>
											<?php }?>											
										</tfoot>
									</table><br>	
									
									
								</div>	
							 </div>	
						</div><br><br><br>
						<?php }?>
				</div><p></p>
						<div align="right">
							<div class="row">
								<label>Date : <?php echo date('d-m-Y');?></label>
							</div><br>
							<div class="row">
							<label>Time : <?php echo date('h:i A', strtotime(date('d-m-Y H:i:s')));?></label>
							</div>
						</div>
			</div>
 </div>
 </div><!-- /.box-body --> 
</div>
 </span>          
</body></html>
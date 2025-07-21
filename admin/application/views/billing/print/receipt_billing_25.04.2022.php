<html><head>
		<meta charset="utf-8">
		<title>Billing Receipt</title>
		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/billing_receipt.css">
		<style >
		 .head
		 {
			 color: black;
			 font-size: 30px;
		 }
		 .alignCenter {
			 text-align: center;
		 }
		 .alignRight {
			 text-align: right;
		 }
		 .table_heading {
			 font-weight: bold;
		 }
		 .textOverflowHidden {
			white-space: nowrap; 
			overflow: hidden;
			text-overflow: ellipsis;
		 }
		.duplicate_copy * {
			font-size: 9px;
		}
		.duplicate_copy #pp th, .duplicate_copy #pp td{
			font-size: 9px !important;
		}
		.return_dashed {
			width:700px !important;
		}
		.old_metal_dashed {
			width:700px !important;
		}

		.stones, .charges {
			font-style: italic;
		}
		.stones .stoneData, .charges .chargeData {
			font-size: 10px !important;
		}
		

        </style>
</head>
<body>
<span class="PDFReceipt" >
            <!--<div>
                <img alt="" src="<?php echo base_url();?>assets/img/receipt_logo.png">
            </div>-->
<?php 

$login_emp = $billing['emp_name'];

$esti_sales_emp = '';
$esti_purchase_emp = '';
$esti_return_emp = '';

$esti_sales_id = '';
$esti_purchase_id = '';
$esti_return_id = '';

function moneyFormatIndia($num) {
	return preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $num);
}

$gold_metal_rate = ($billing['goldrate_22ct']>0 ? $billing['goldrate_22ct']:$metal_rate['goldrate_22ct']);
$silver_metal_rate = ($billing['silverrate_1gm']>0 ?$billing['silverrate_1gm'] :$metal_rate['silverrate_1gm']);
$metal_type = 0;

$tot_sales_amt=0;
$sales_cost = 0;
foreach($est_other_item['item_details'] as $items) {
	$sales_cost += $items['item_cost'];
}
$tot_sales_amt  =  number_format($sales_cost,2,'.','');

$total_return=0;
foreach($est_other_item['return_details'] as $items) {
	$total_return  += $items['item_cost'];
}

$pur_total_amt=0;
foreach($est_other_item['old_matel_details'] as $items) {
	$pur_total_amt += $items['amount'];
}
?>
    <div>
			<div class="hare_krishna"> </div>
			<div class="header_top">
				<!--<div class="header_top_left">
					<div>CIN : 394872094392030</div>
					<div>GSTIN : 98423792430923 </div>
				</div>
				<div class="header_top_right">
					<img src="<?php echo dirname(base_url()) ?>/assets/img/logo.png" />
				</div>-->
			</div><br>
			<div style="width: 100%; text-transform:uppercase;">
			    
			    <div style="text-align:right;margin-top:-24px;">
                    <?php echo $billing['branch_name'];?>
                </div>
                
			    <?php if($billing['bill_type']!=12){?>
			    
				<div style="display: inline-block; width: 50%; padding-left:0px;margin-top:18px;">
                        <label><?php echo 'Mr./Ms.'.$billing['customer_name'].' - '.$billing['mobile']; ?></label><br>
                        <label><?php echo (isset($billing['address1']) && $billing['address1']!='' ?$billing['address1'].','."<br>" :''); ?></label>
                        <label><?php echo (isset($billing['address2']) && $billing['address2']!='' ?$billing['address2'].','."<br>" :''); ?></label>
                        <label><?php echo (isset($billing['address3']) && $billing['address3']!='' ?$billing['address3'].','."<br>" :''); ?></label>
                        <label><?php echo ($billing['city']!='' ? $billing['city'].($billing['pincode']!='' ? ' - '.$billing['pincode'].'.' :''):''); ?><br></label>
                        <label><?php echo ($billing['cus_state']!='' ?$billing['cus_state'].','."<br>" :''); ?></label>
                        
                        <label><?php echo (isset($billing['pan_no']) && $billing['pan_no']!='' ?   'PAN : '.$billing['pan_no'] :''); ?></label>
                        <label><?php echo (isset($billing['gst_number']) && $billing['gst_number']!='' ?   'GST IN : '.$billing['gst_number'] :''); ?></label>
				</div>
                <?php }else if($billing['bill_type']==12){?>
                <div style="display: inline-block; width: 50%; padding-left: 40px">
                    <label><b><?php echo 'Mr./Ms.'.$billing['karigar_name']; ?></label></b><br>
                    <label><?php echo (isset($billing['karigar_address1']) && $billing['karigar_address1']!='' ?$billing['karigar_address1'].','."<br>" :''); ?></label>
                    <label><?php echo (isset($billing['karigar_address2']) && $billing['karigar_address2']!='' ?$billing['karigar_address2'].','."<br>" :''); ?></label>
                    <label><?php echo (isset($billing['pan_no']) && $billing['pan_no']!='' ?   'PAN : '.$billing['pan_no'] :''); ?></label>
                    <label><?php echo (isset($billing['karigar_gst_number']) && $billing['karigar_gst_number']!='' ? "<br>" .'GST : '.$billing['karigar_gst_number'] :''); ?></label>
                </div>
                <?php }?>
                
                
               
				<div style="width: 100%; text-align: right; display: inline-block; vertical-align: top;">
					<div style="text-align: right; width:100%; height: 20px;">
						<div style="width: 24%; display: inline-block;">&nbsp;  </div>
						<div style="text-align: left;width: 30%; display: inline-block;"> 
						<?php echo ($comp_details['address1']!='' ? "<br>".$comp_details['address1'].',' :'');?> 
						<?php echo ($comp_details['address2']!='' ? "<br>".$comp_details['address2'].',' :'');?> 
						<?php echo ($comp_details['city']!='' ? "<br>".$comp_details['city'].' - '.$comp_details['pincode'] :'');?> 
						</div>
					</div>
				</div>
			</div>
			<p></p>
			<div style="width: 100%; text-transform:uppercase;margin-top:-8px;">
		        <div style="text-align: left; width:100%;height: 18px; ">
					<div style="width: 15%; display: inline-block"> Invoice Date &nbsp;&nbsp; : &nbsp; </div>
					<div style="width: 15%; display: inline-block; margin-top: -1px"> <?php echo $billing['bill_date']; ?></div>
				</div>
				<div style="text-align: left; width:100%;height: 18px;">
					<div style="width: 15%; display: inline-block"> Invoice no &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp; </div>
					<div style="width: 19%; display: inline-block; margin-top: -1px"> <?php echo ($billing['branch_code']!='' ? $billing['branch_code'].'/':'').($billing['fin_year_code']!='' ?$billing['fin_year_code'].'/' :'').(($billing['bill_type']==1 || $billing['bill_type']==2 || $billing['bill_type']==3 || $billing['bill_type']==7 || $billing['bill_type']==9) ? 'SA/': ($billing['bill_type']==4 ? 'PUR/' :($billing['bill_type']==5 ? 'OD/' :''))).$billing['bill_no']; ?> </div>
				</div>
		    </div>
		    
	    	<div style="width: 100%; text-align: right; display: inline-block;margin-top:-40px;">
			    <div style="text-align: right; width:100%;height: 18px;">
					<div style="width: 88%; display: inline-block"> GOLD &nbsp; : &nbsp; </div>
					<div style="width: 10%; display: inline-block; margin-top: -2px"> <?php echo number_format($gold_metal_rate,2,'.','').'/'.'Gm'; ?></div>
				</div>
				<div style="text-align: right; width:100%;height: 18px;">
					<div style="width: 88%; display: inline-block"> SILVER &nbsp; : &nbsp; </div>
					<div style="width: 10%; display: inline-block; margin-top: -2px"> <?php echo $silver_metal_rate.'/'.'Gm'; ?> </div>
				</div>
			</div>
			
		 	<div style="width: 100%; text-align: center; margin-top:-67px; font-weight: bold; text-transform:uppercase;">
				<label>
					<?php echo "TAX INVOICE"; //($billing['bill_type']==1 ? 'Sales Bill': ($billing['bill_type']==2 ? 'Sales And Purchase Bill':($billing['bill_type']==3 ? 'Sales and purchase bill' :($billing['bill_type']==4 ? ' URD Purchase Bill':($billing['bill_type']==5 ? 'Order Advance Receipt' :($billing['bill_type']==6 ? 'Advance Receipt' :($billing['bill_type']==7 ? 'Sales Return Receipt':($billing['bill_type']==9 ? 'Order Delivery' :($billing['bill_type']==10 ? 'Chit PreClose Receipt': ($billing['bill_type']==11 ? 'Repair Order Delivery' : 'Credit Collection Receipt') ))))))))).($billing['bill_status']==2 ? '- Cancelled' : '');?>
				</label>
			</div>
			
			<p></p>
			<div  class="content-wrapper">
			<div class="box">
			<div class="box-body">
						<div  class="container-fluid">
							<div id="printable">
								<?php if(sizeof($est_other_item['item_details'])>0){?>
									<hr class="header_dashed">
										<div class="col-xs-12">
											<div class="table-responsive">
											<table id="pp" class="table text-center" >
													<thead style="text-transform:uppercase;font-size:10px;">
														<tr>
															<td class="table_heading" style="width: 5%">S.No</td>
															<td class="table_heading" style="width: 26%">Description of Goods</td>
															<td class="table_heading" style="width: 15%">HSN</td>
															<td class="table_heading alignRight" style="width: 9%">PCS</td>
															<td class="table_heading alignRight" style="width: 15%">Gwt(g)</td>
															<td class="table_heading alignRight" style="width: 15%">Nwt(g)</td>
															<td class="table_heading alignRight" style="width: 15%">Amount</td>
														</tr>
													</thead>
														<tr>
															<td><hr class="item_dashed"></td>
														</tr>
													
													<!--<tbody>-->
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
													$total_cgst=0;
													$total_sgst=0;
													$total_igst=0;
													$bill_discount=0;
													$mc=0;
													$wastge_wt=0;
													$total_stone_amount=0;
													foreach($est_other_item['item_details'] as $items)
														{
															$esti_sales_emp = $items['esti_emp_name'];
															$esti_sales_id = $items['esti_emp_id'];

															$mc=0;
															$wastge_wt=0;

															$stone_amount=0;
															if(count($items['stone_details']) > 0) {
																foreach($items['stone_details'] as $stoneItems) { 
																	$stone_amount += $stoneItems['amount'];
																}
															}

															$total_stone_amount += $stone_amount;

															$metal_type = $items['metal_type'];
															$pieces         +=$items['piece'];
															$gross_wt       +=$items['gross_wt'];
															$net_wt         +=$items['net_wt'];
															$discount       +=$items['discount'];
															$tot_tax        +=$items['item_total_tax'];
															$sales_cost     +=$items['item_cost'];
															$total_cgst     +=$items['total_cgst'];
															$total_sgst     +=$items['total_sgst'];
															$total_igst     +=$items['total_igst'];
															$bill_discount  +=$items['bill_discount'];
															$taxable_amt    +=$items['item_cost']-$items['item_total_tax'];
															$amt_in_words   = $this->ret_billing_model->no_to_words($billing['tot_bill_amount']);

															$item_taxable   =number_format((float)$items['item_cost']-$items['item_total_tax'],2,'.','');
															$tax_percentage =number_format(($items['item_total_tax']*100)/$item_taxable,'2','.','');
															$tot_tax_per    +=$tax_percentage;

															if($items['calculation_based_on']==0)
															{
																$wastge_wt=($items['gross_wt']*($items['wastage_percent']/100));
																$mc = ($items['mc_type']== 1 ? ($items['mc_value'] * $items['gross_wt'] ) : ($items['mc_value'] * 1));

															}else if($items['calculation_based_on']==1)
															{
																$wastge_wt=($items['net_wt']*($items['wastage_percent']/100));
																$mc = ($items['mc_type']== 1 ? ($items['mc_value'] * $items['net_wt'] ) : ( $items['mc_value'] * 1 ));
															}else if($items['calculation_based_on']==2)
															{
																$wastge_wt=($items['net_wt']*($items['wastage_percent']/100));
																$mc = ($items['mc_type']== 2 ? ($items['mc_value'] * $items['gross_wt'] ) : ( $items['mc_value'] * 1 ));
															}
														?>

														<tr>
														<td><?php echo $i;?></td>
														<td style="font-size:10px !important;" class='textOverflowHidden'><?php echo $items['product_name'];?><?php echo $items['size'] > 0 ? "-".$items['size_name'] : '';?></td>
														<td><?php echo $items['hsn_code'];?></td>
														<td class="alignRight"><?php echo $items['piece'];?></td>
														<td class="alignRight"><?php echo $items['gross_wt'];?></td>
														<td class="alignRight"><?php echo $items['net_wt'];?></td>
														<td class="alignRight"><?php echo moneyFormatIndia(number_format((float)($item_taxable-$stone_amount),2,'.',''));?></td>
														</tr>
														<?php
															if(count($items['stone_details']) > 0) {
																foreach($items['stone_details'] as $stoneItems) { 
																	?>
																	<tr class="stones">
																		<td></td>
																		<td class='textOverflowHidden stoneData'><?php echo $stoneItems['stone_name'];?></td>
																		<td></td>
																		<td class="alignRight stoneData"><?php echo $stoneItems['pieces'];?></td>
																		<td class="alignRight stoneData"><?php echo $stoneItems['wt'];?></td>
																		<td class="alignRight"></td>
																		<td class="alignRight stoneData"><?php echo moneyFormatIndia(number_format((float)($stoneItems['amount']),2,'.',''));?></td>
																	</tr>
															 <?php }
															}

															if(count($items['charges']) > 0) {
																foreach($items['charges'] as $chargeItems) { ?>
																	<tr class="charges" style="display:none;">
																		<td></td>
																		<td></td>
																		<td class="chargeData"><?php echo $chargeItems['code_charge'];?></td>
																		<td></td>
																		<td></td>
																		<td></td>
																		<td></td>
																	</tr>
															 <?php }
															}
														?>
													<?php $i++;}?>
												<!--</tbody> -->
														<tr>
															<td><hr class="item_dashed"></td>
														</tr>
														<tr class="total" style="font-weight: bold" >
															<td>Total</td>
															<td></td>
															<td></td>
															<td class="alignRight"><?php echo $pieces;?></td>
															<td class="alignRight"><?php echo number_format($gross_wt,3,'.','');?></td>
															<td class="alignRight"><?php echo number_format($net_wt,3,'.','');?></td>
															<td class="alignRight"><?php echo moneyFormatIndia(number_format((float)($taxable_amt),2,'.',''));?></td>
														</tr>
														<tr>
															<td><hr class="item_dashed"></td>
														</tr>
														
															<!--<?php if($bill_discount>0){?>
                                								<tr>
                                									<td colspan="4" ></td>
                                									<td  class="alignRight">LESS DISC</td>
                                									<td class="alignRight">Rs.</td>
                                									<td class="alignRight"><?php echo moneyFormatIndia(number_format($bill_discount,2,'.',''));?></td>
                                								</tr>
                                							<?php }?>-->
														<!-- <tr style="font-weight: bold" >
															<td colspan="9" class="alignRight">Hallmark charges Added in the Invoice</td>
														</tr> -->
														<?php if($taxable_amt>0){?>
														<tr>
															<td colspan="4"></td>
															<td class="alignRight">SUB TOTAL</td>
															<td class="alignRight">Rs.</td>
															<td class="alignRight"><?php echo moneyFormatIndia(number_format((float)($taxable_amt),2,'.',''));?></td>
														</tr>
														<?php } ?>
														<?php if($total_sgst>0){?>
														<tr>
															<td colspan="4"></td>
															<td class="alignRight">SGST</td>
															<td class="alignRight"><?php echo ($est_other_item['item_details'][0]['tax_percentage']/2).'%'?></td>
															<td class="alignRight"><?php echo moneyFormatIndia(number_format((float)($total_sgst),2,'.',''));?></td>
														</tr>
														<?php }?>
														
														<?php if($total_cgst>0){?>
														<tr>
															<td colspan="4"></td>
															<td class="alignRight">CGST</td>
															<td class="alignRight"><?php echo ($est_other_item['item_details'][0]['tax_percentage']/2).'%'?></td>
															<td class="alignRight"><?php echo moneyFormatIndia(number_format((float)($total_cgst),2,'.',''));?></td>
														</tr>
                                                        <?php }?>
                                                        
														<?php if($total_igst>0){?>
															<tr>
															<td colspan="4"></td>
															<td class="alignRight">IGST</td>
															<td class="alignRight"><?php echo ($est_other_item['item_details'][0]['tax_percentage']).'%'?></td>
															<td class="alignRight"><?php echo moneyFormatIndia(number_format((float)($total_igst),2,'.',''));?></td>
														</tr>
														<?php }?>
														
														<?php if($billing['handling_charges']>0){?>
														<tr>
															<td colspan="4"></td>
															<td class="alignRight">H.C</td>
															<td class="alignRight">Rs.</td>
															<td class="alignRight"><?php echo moneyFormatIndia(number_format($billing['handling_charges'],2,'.',''));?></td>
														</tr>
														<?php }?>
														
														<tr>
															<td colspan="4"></td>
															<td class="alignRight">TOTAL</td>
															<td class="alignRight">Rs.</td>
															<td class="alignRight"><?php echo moneyFormatIndia(number_format($tot_sales_amt+$billing['handling_charges']+$billing['round_off_amt'],2,'.',''));?></td>
														</tr>
														<?php if($billing['tcs_tax_amt']>0){?>
														<tr>
															<td colspan="4"></td>
															<td class="alignRight">TCS %</td>
															<td class="alignRight"><?php echo $billing['tcs_tax_per'];?></td>
															<td class="alignRight"><?php echo moneyFormatIndia(number_format($billing['tcs_tax_amt'],2,'.',''));?></td>
														</tr>
														<tr>
															<td colspan="4"></td>
															<td class="alignRight">Net Amt</td>
															<td class="alignRight">Rs.</td>
															<td class="alignRight"><?php echo moneyFormatIndia(number_format($tot_sales_amt+$billing['handling_charges']+$billing['tcs_tax_amt'],2,'.',''));?></td>
														</tr>
														<?php }?>
												</table>
											</div>	
										</div>	
									</div>
									<?php }
									//echo "<pre>"; print_r($stones); echo "</pre>";exit;
									?>
									
								
									<?php if(sizeof($est_other_item['return_details'])>0){?>
											<table id="pp" class="table text-center">
												<!--	<thead> -->
														<tr style="display: none;">
															<td><hr class="return_dashed"></td>
														</tr>
														<tr style="text-transform:uppercase; display:none">
															<td style="width: 5%" class="table_heading">S.No</td>
															<td style="width: 25%" class="table_heading">Description</td>
															<td style="width: 15%" class="table_heading">HSN Code</td>
															<td style="width: 10%" class="table_heading alignRight">PCS</td>
															<td style="width: 15%" class="table_heading alignRight">Gwt(g)</td>
															<td style="width: 15%" class="table_heading alignRight">Nwt(g)</td>
															<td style="width: 15%" class="table_heading alignRight">Amount</td>
														</tr>
														<tr>
															<td><hr class="return_dashed"></td>
														</tr>
													<!--</thead>
													<tbody>-->
													<tr style="font-weight:bold; text-transform: uppercase;"><td colspan="9">Exchange</td></tr>
													<?php
													$i=1; 
													$pieces=0;
													$gross_wt=0;
													$net_wt=0;
													$return_item_cost=0;
													$discount=0;
													$tax_percentage=0;
													$total_cgst=0;
													$total_igst=0;
													$total_sgst=0;
													$mc = 0;
													$wastge_wt=0;
													foreach($est_other_item['return_details'] as $items)
														{
															$esti_return_emp = $items['esti_emp_name'];
															$esti_return_id = $items['esti_emp_id'];

															$mc = 0;
															$wastge_wt=0;
															$pieces             +=$items['piece'];
															$gross_wt           +=$items['gross_wt'];
															$net_wt             +=$items['net_wt'];
															$discount           +=$items['discount'];
															$total_sgst         +=$items['total_sgst'];
															$total_igst         +=$items['total_igst'];
															$total_cgst         +=$items['total_cgst'];
															$return_item_cost   +=$items['item_cost']-$items['item_total_tax'];
															$tax_percentage     =$items['tax_percentage']/2;

															if($items['calculation_based_on']==0)
															{
																$wastge_wt=($items['gross_wt']*($items['wastage_percent']/100));
																$mc = ($items['mc_type']== 1 ? ($items['mc_value'] * $items['gross_wt'] ) : ($items['mc_value'] *$items['piece']));

															}else if($items['calculation_based_on']==1)
															{
																$wastge_wt=($items['net_wt']*($items['wastage_percent']/100));
																$mc = ($items['mc_type']== 1 ? ($items['mc_value'] * $items['net_wt'] ) : ( $items['mc_value'] * $items['piece'] ));
															}else if($items['calculation_based_on']==2)
															{
																$wastge_wt=($items['net_wt']*($items['wastage_percent']/100));
																$mc = ($items['mc_type']== 1 ? ($items['mc_value'] * $items['gross_wt'] ) : ( $items['mc_value'] * $items['piece'] ));
															}
														?>
														<tr>
															<td colspan="9" style="text-transform:uppercase; font-weight: bold">Refer sale  bill no : <?php echo $items['ref_bill_no'].' Dt '.$items['ref_bill_date']?> </td>
														</tr>
														<tr>
														<td style="width: 5%"><?php echo $i;?></td>
														<td style="width: 26%; font-size:10px;" class='textOverflowHidden'><?php echo $items['product_name'];?></td>
														<td style="width: 15%"><?php echo $items['hsn_code'];?></td>
														<td style="width: 9%" class="alignRight"><?php echo $items['piece'];?></td>
														<td style="width: 15%" class="alignRight"><?php echo $items['gross_wt'];?></td>
														<td style="width: 15%" class="alignRight"><?php echo $items['net_wt'];?></td>
														<td style="width: 15%" class="alignRight"><?php echo moneyFormatIndia(number_format($items['item_cost'],2,'.',''));?></td>
														</tr>
													<?php $i++;}?>
														<tr >
															<td><hr class="return_dashed"></td>
														</tr>
												<!--</tbody> -->
													<!--<tfoot>-->
														<tr style="font-weight: bold">
															<td>Total</td>
															<td></td>
															<td></td>
															<td></td>
															<td></td>
															<td></td>
															<td class="alignRight"><?php echo moneyFormatIndia(number_format((float)$total_return,'2','.',''));?></td>
														</tr>
														<tr>
															<td><hr class="return_dashed"></td>
														</tr>
														<?php 
														if($pur_total_amt == 0) { 
														if($tot_sales_amt+$billing['handling_charges'] > 0){?>
														<tr>
															<td colspan="4"></td>
															<td class="alignRight">Sales Amount</td>
															<td class="alignRight">Rs.</td>
															<td class="alignRight"><?php echo moneyFormatIndia(number_format($tot_sales_amt+$billing['handling_charges'],2,'.',''));?></td>
														</tr>
														<?php }?>
														<?php if($total_return>0){?>
														<tr>
															<td colspan="4"></td>
															<td class="alignRight">Exchange Amount</td>
															<td class="alignRight">Rs.</td>
															<td class="alignRight"><?php echo moneyFormatIndia(number_format($total_return,2,'.',''));?></td>
														</tr>
														<?php } ?>
														<?php $round_off = moneyFormatIndia(number_format($billing['round_off_amt'],2,'.','')); ?>
														<?php if($round_off != 0) { ?>
														<tr>
															<td colspan="4"></td>
															<td class="alignRight">Round Off</td>
															<td class="alignRight">Rs.</td>
															<td class="alignRight"><?php echo $round_off ?></td>
														</tr>
														<?php } ?>
														<?php if($tot_sales_amt > 0 && $total_return > 0){?>
														<tr>
															<td colspan="4"></td>
															<td class="alignRight">Net Amount</td>
															<td class="alignRight">Rs.</td>
															<td class="alignRight"><?php echo moneyFormatIndia(number_format(($tot_sales_amt+$billing['handling_charges']) -$total_return+$round_off ,2,'.',''));?></td>
														</tr>
														<?php } } ?>
												</table>
									<?php }?>
									
										<?php if($billing['bill_type']==5){?>
										<?php if(sizeof($est_other_item['advance_details'])>0){
											$amount=0;
											$order_no='';
											$order_adv_pur=0;
											foreach($est_other_item['advance_details'] as $item)
											{
													$order_no=$item['order_no'];
													if($item['advance_type']==1)
													{	
														if($item['store_as']==1)
														{
															$amount +=$item['advance_amount'];
														}
														else
														{
															$amount +=$item['advance_weight']*$item['rate_per_gram'];
														}
													}
													
											
											}
										}
										?>

										<?php ?>
										<div  class="row">
											<div align="center">
												<label><b>Order No.<?php echo $order_no;?></b></label>
											</div>
										<div class="col-xs-12">
											<div class="table-responsive">
											<table id="pp" class="table text-center">
												<!--	<thead> -->
														<tr>
															<td><hr class="old_metal_dashed"></td>
														</tr>
														<tr>
															<td class="table_heading" width="15%;">S.No</td>
															<td class="table_heading" width="25%;">Description</td>
															<td class="table_heading" width="20%;">Design</td>
															<td class="table_heading alignRight" width="20%;">PCS</td>
															<td class="table_heading alignRight" width="20%;">Gwt(g)</td>
														</tr>
														<tr>
															<td><hr class="old_metal_dashed"></td>
														</tr>
													<!--</thead>
													<tbody>-->
														<?php 
														$i=1; 
														$weight=0;
														foreach($est_other_item['order_details'] as $items){
														$weight+=$items['weight'];
														?>
															<tr>
																<td><?php echo $i;?></td>
																<td><?php echo $items['product_name'];?></td>
																<td><?php echo $items['design_name'];?></td>
																<td class="alignRight"><?php echo $items['totalitems'];?></td>
																<td class="alignRight"><?php echo $items['weight'];?></td>
															</tr>
														<?php $i++;}
														?>
														
												<!--</tbody> -->
													<tr>
														<td><hr class="old_metal_dashed"></td>
													</tr>
													
													<tr>
														<td>Total</td>
														<td></td>
														<td></td>
														<td></td>
														<td class="alignRight"><?php echo number_format($weight,3,'.','');?></td>
													</tr>
													
													<tr>
														<td><hr class="old_metal_dashed"></td>
													</tr>
													
													<tr>
															<td colspan="4"><?php echo 'Received with thanks from Mr./Ms.'.$billing['customer_name'].' Towards Order  No : '.$order_no.'';?></td>
															<td colspan="2"><b><?php echo 'Rs. '.moneyFormatIndia($amount+$pur_total_amt);?></b></td>
													</tr>
													
												</table><br>	
											</div>	
										</div>	
									</div><br>
									<?php }?>

									<?php if(sizeof($est_other_item['old_matel_details'])>0){?>
										<span style="font-weight: bold">PURCHASE NO : <?php echo $billing['pur_ref_no'];?></span>

											<table id="pp" class="table text-center">
												<!--	<thead> -->
												        <tr style="">
															<td><hr class="old_metal_dashed"></td>
														</tr>
												        <?php if(sizeof($est_other_item['item_details'])==0){?>
														
														<tr style="text-transform:uppercase;">
															<td style="width: 5%" class="table_heading">S.No</td>
															<td style="width: 25%;" class="table_heading">DESCRIPTION OF GOODS</td>
															<td style="width: 15%" class="table_heading">HSN</td>
															<td style="width: 24%" class="table_heading alignRight">GROSS WT(g)</td>
															<td style="width: 15%" class="table_heading alignRight">Nwt(g)</td>
															<td style="width: 15%" class="table_heading alignRight">Amount</td>
														</tr>
														<tr>
															<td><hr class="old_metal_dashed"></td>
														</tr>
														<?php }?>
													<!--</thead>
													<tbody>-->
													<?php
													$old_metal_details = array();
													foreach ($est_other_item['old_matel_details'] as $olditem) {
														$key = $olditem['metal_type'];
														if (!array_key_exists($key, $old_metal_details)) {
															$old_metal_details[$key] = array(
																'metal_type'=> $olditem['metal_type'],
																'gross_wt' => $olditem['gross_wt'],
																'net_wt' => $olditem['net_wt'],
																'amount' => $olditem['amount'],
															);
														} else {
															$old_metal_details[$key]['gross_wt'] = $old_metal_details[$key]['gross_wt'] + $olditem['gross_wt'];
															$old_metal_details[$key]['net_wt'] = $old_metal_details[$key]['net_wt'] + $olditem['net_wt'];
															$old_metal_details[$key]['amount'] = $old_metal_details[$key]['amount'] + $olditem['amount'];
														}
													}

													$i=1; 
													$total_amt=0;
													$pieces=0;
													$gross_wt=0;
													$net_wt=0;
													foreach($old_metal_details as $items)
														{
															$esti_purchase_emp = $items['esti_emp_name'];
															$esti_purchase_id = $items['esti_emp_id'];

															$gross_wt+=$items['gross_wt'];
															$net_wt+=($items['net_wt']);
														?>
														<tr>
														<td style="width: 5%"><?php echo $i;?></td>
														<td style="font-size:10px; width: 26%" class='textOverflowHidden'><?php echo ($items['metal_type']==1 ?'Old Gold' :'Old Silver');?></td>
														<td style="width: 15%">71080000</td>
														<td style="width: 24%" style="width: 9%" class="alignRight"><?php echo number_format($items['gross_wt'],3,'.','');?></td>
														<td style="width: 15%" class="alignRight"><?php echo number_format($items['net_wt'],3,'.','');?></td>
														<td style="width: 15%" class="alignRight"><?php echo moneyFormatIndia(number_format($items['amount'],2,'.',''));?></td>
														</tr>
													<?php $i++;}?>
												<!--</tbody> -->
														<tr>
															<td><hr class="old_metal_dashed"></td>
														</tr>
														<tr style="font-weight: bold">
															<td>Total</td>
															<td></td>
															<td></td>
															<td class="alignRight"><?php echo number_format($gross_wt,3,'.','');?></td>
															<td class="alignRight"><?php echo number_format($net_wt,3,'.','');?></td>
															<td class="alignRight"><?php echo moneyFormatIndia(number_format((float)$pur_total_amt,2,'.',''));?></td>
														</tr>
														<tr style="display: none;">
															<td><hr class="old_metal_dashed"></td>
														</tr>
														<?php if($tot_sales_amt+$billing['handling_charges'] > 0){?>
														<tr>
															<td colspan="2"></td>
															<td class="alignRight" colspan="2">Sales Amount</td>
															<td class="alignRight">Rs.</td>
															<td class="alignRight"><?php echo moneyFormatIndia(number_format($tot_sales_amt+$billing['handling_charges'],2,'.',''));?></td>
														</tr>
														<?php }?>
														<?php if($total_return>0){?>
														<tr>
															<td colspan="2"></td>
															<td class="alignRight" colspan="2">Exchange Amount</td>
															<td class="alignRight">Rs.</td>
															<td class="alignRight"><?php echo moneyFormatIndia(number_format($total_return,2,'.',''));?></td>
														</tr>
														<?php }?>
														<?php if($pur_total_amt > 0) { ?>
														<tr style="font-weight:bold;">
															<td colspan="2">
																<?php
																	$cgst_sgst_perc = ($est_other_item['item_details'][0]['tax_percentage']/2);
																	$cgst_sgst_amt = ($pur_total_amt * $cgst_sgst_perc / 100);
																?>	
																	<!--RCM : (CGST <?php echo  $cgst_sgst_perc ."% - ". number_format($cgst_sgst_amt,2,'.','') ?>,  SGST <?php echo  $cgst_sgst_perc ."% - ". number_format($cgst_sgst_amt,2,'.','') ?>)-->
															</td>
															<td class="alignRight" colspan="2">Purchase Amount</td>
															<td class="alignRight">Rs.</td>
															<td class="alignRight"><?php echo moneyFormatIndia(number_format($pur_total_amt ,2,'.',''));?></td>
														</tr>
														<?php } ?>
														<?php $round_off = moneyFormatIndia(number_format($billing['round_off_amt'],2,'.','')); ?>
														<?php if($round_off != 0) { ?>
														<tr>
															<td colspan="2"></td>
															<td class="alignRight" colspan="2">Round Off</td>
															<td class="alignRight">Rs.</td>
															<td class="alignRight"><?php echo $round_off ?></td>
														</tr>
														<?php } ?>
														<?php if($tot_sales_amt>0){?>
														<tr>
															<td colspan="2"></td>
															<td class="alignRight" colspan="2">Net Amount</td>
															<td class="alignRight">Rs.</td>
															<td class="alignRight"><?php echo moneyFormatIndia(number_format(($tot_sales_amt+$billing['handling_charges']) -$total_return-$pur_total_amt+$round_off,2,'.',''));?></td>
														</tr>
														<?php }?>
												</table>
									<?php }?>
									
									<?php if(sizeof($est_other_item['repair_order_details'])>0){?>
                        <div class="row">
                            <hr class="old_metal_header_dashed">
                            <div class="col-xs-12">
                                <div class="table-responsive">

                                    <table id="pp" class="table text-center">
                                        <!--	<thead> -->
                                        <tr style="text-transform:uppercase;">
                                            <td>S.No</td>
                                            <td>Description</td>
                                            <td>Repair WT(g)</td>
                                            <td>Completed Wt(g)</td>
                                            <td>Amount</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <hr class="old_metal_dashed" style="width:500% !important;">
                                            </td>
                                        </tr>
                                        <!--</thead>
										<tbody>-->
                                        <?php
										$i=1; 
										$tot_repair_amt=0;
										$gst=0;
										$tot_gst=0;
										$igst=0;
										$tot_igst=0;
										$taxable_amt=0;
										$tot_order_wt=0;
										$tot_completed_wt=0;
										$amt_in_words   = $this->ret_billing_model->no_to_words($billing['tot_bill_amount']);
										foreach($est_other_item['repair_order_details'] as $items)
											{
												$tot_order_wt+=$items['weight'];
												$tot_completed_wt+=$items['completed_weight'];
												$tot_repair_amt+=$items['rate'];
												$tot_gst+=$items['repair_tot_tax'];
												$igst+=$items['igst'];
												$cgst+=$items['cgst'];
												$sgst+=$items['igst'];
												$taxable_amt+=$items['rate']-$items['repair_tot_tax'];

											?>
                                        <tr>
                                            <td><?php echo $i;?></td>
                                            <td><?php echo $items['product_name'];?></td>
                                            <td><?php echo ($items['weight']);?></td>
                                            <td><?php echo $items['completed_weight'];?></td>
                                            <td><?php echo number_format($items['rate']-$items['repair_tot_tax'],2,'.','');?>
                                            </td>
                                        </tr>

                                        <?php $i++;}?>
                                        <!--</tbody> -->
                                        <tr>
                                            <td>
                                                <hr class="old_metal_dashed" style="width:500% !important;">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>Total</td>
                                            <td><?php echo number_format($tot_order_wt,3,'.',''); ?></td>
                                            <td><?php echo number_format($tot_completed_wt,3,'.',''); ?></td>
                                            <td><?php echo number_format($taxable_amt,2,'.',''); ?></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <hr class="old_metal_dashed" style="width:500% !important;">
                                            </td>
                                        </tr>

                                        <?php if($tot_repair_amt>0){?>
                                        <tr>
                                            <td colspan="2"></td>
                                            <td width="">SUB TOTAL</td>
                                            <td>Rs.</td>
                                            <td><?php echo number_format($taxable_amt,2,'.','');?></td>
                                        </tr>
                                        <?php }?>

                                        <?php if($tot_gst>0){?>
                                        <tr>
                                            <td colspan="2"></td>
                                            <td width="">CGST</td>
                                            <td>Rs.</td>
                                            <td><?php echo number_format($tot_gst/2,2,'.','');?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"></td>
                                            <td width="">SGST</td>
                                            <td>Rs.</td>
                                            <td><?php echo number_format($tot_gst/2,2,'.','');?></td>
                                        </tr>
                                        <?php }?>
                                        <?php if($igst>0){?>
                                        <tr>
                                            <td colspan="2"></td>
                                            <td width="">IGST</td>
                                            <td>Rs.</td>
                                            <td><?php echo number_format($igst,2,'.','');?></td>
                                        </tr>
                                        <?php }?>
                                       
                                        <?php if($tot_repair_amt>0){?>
                                        
                                         <tr>
                                            <td colspan="2"></td>
                                            <td>
                                                <hr class="total_dashed" style="width:300% !important;">
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td colspan="2"></td>
                                            <td width="">Total</td>
                                            <td>Rs.</td>
                                            <td><?php echo number_format($tot_repair_amt,2,'.','');?></td>
                                        </tr>
                                        
                                        <tr>
                                            <td colspan="2"></td>
                                            <td>
                                                <hr class="total_dashed" style="width:300% !important;">
                                            </td>
                                        </tr>
                                        
                                        <?php }?>
                                        
                                    </table><br>
                                </div>
                            </div>
                        </div><br>
                        <?php }?>

									<?php if($billing['bill_type']==8){?>
										<div  class="row">
											<div align="center" style="text-transform:uppercase;">
												<label>Credit No : <?php echo $billing['ref_bill_id'];?></label>
											</div>
											
										<div class="col-xs-12">
											<div class="table-responsive">
												<table id="pp" class="table text-center">
													<tr>
														<td><hr class="old_metal_dashed"></td>
													</tr>
													<tr>
														<td style="width: 70%; font-weight: bold">Description</td>
														<td style="width: 30%; font-weight: bold">Amount</td>
													</tr>
													<tr>
														<td><?php echo 'Received with thanks from Mr./Ms.'.$billing['customer_name'].' Towards Credit Bill No : '.$billing['ref_bill_no'].'/ Ref No : '.$billing['ref_bill_id'];?></td>
														<td><?php echo $billing['due_amount'];?></td>
													</tr>
													<tr>
														<td><hr class="old_metal_dashed"></td>
													</tr>
												</table>
												<br>	
											</div>	
										</div>	
									</div><br>
									<?php }?>

								
										
												<?php 
												    $due_amount=0;
												    
												    if($billing['bill_type']==8)
													{
														$due_amount=number_format(($billing['due_amount']-($billing['tot_paid_amt']+$pur_total_amt)),2,'.','');
														
													}
													else{
														if($billing['is_credit']==1)
														{
															$due_amount=number_format(($billing['tot_bill_amount']-$billing['tot_amt_received']),2,'.','');
														}
													}
													
													if(sizeof($payment['pay_details'])>0){
													$cash_amt=0;
													$card_amt=0;
													$net_banking_amt=0;
													
													$gift_amount=0;
													$chit_amount=0;
													$total_amt=0;
													$adv_adj=0;
													$imps_amt=0;
													$rtgs_amt=0;
													$upi_amt=0;
													$chq_amt=0;
												
													
													foreach($payment['pay_details'] as $items)
														{
															$total_amt+=$items['payment_amount'];
															
															if($items['payment_mode']=='Cash')
															{
																$cash_amt+=$items['payment_amount'];
															}
															if($items['payment_mode']=='CHQ')
															{
																$chq_amt+=$items['payment_amount'];
															}
															if($items['payment_mode']=='DC' || $items['payment_mode']=='CC')
															{
																$card_amt+=$items['payment_amount'];
															}
															if($items['payment_mode']=='NB' && $items['transfer_type'] == 'RTGS')
															{
																$net_banking_amt+=$items['payment_amount'];
																$rtgs_amt+=$items['payment_amount'];
															}
															if($items['payment_mode']=='NB' && $items['transfer_type'] == 'IMPS')
															{
																$net_banking_amt+=$items['payment_amount'];
																$imps_amt+=$items['payment_amount'];
															}
															if($items['payment_mode']=='NB' && $items['transfer_type'] == 'UPI')
															{
																$net_banking_amt+=$items['payment_amount'];
																$upi_amt+=$items['payment_amount'];
															}
														}
												}?>
										
										<?php if(sizeof($est_other_item['chit_details'])>0){
										$chit_adj=0;
										$bouns_amt=0;
										foreach($est_other_item['chit_details'] as $chit)
										{
											if(($chit['paid_installments']==$chit['total_installments']) && ($chit['scheme_type']==0) && $chit['firstPayDisc_value']>0)
											{
												$bouns_amt+=($chit['paid_installments']*$chit['firstPayDisc_value']);
											}
											$chit_adj +=$chit['utilized_amt'];
										}
										$chit_adj=$chit_adj-$bouns_amt;
										?>
										<?php }?>
										
										<?php if(sizeof($est_other_item['voucher_details'])>0){
										foreach($est_other_item['voucher_details'] as $voc)
										{
											$gift_amount +=$voc['gift_voucher_amt'];
										}
										?>
										<?php }?>
										
										<?php if(sizeof($est_other_item['order_adj'])>0){
										$ord_adj_amt=0;
										$chit_adj_wt=0;
										foreach($est_other_item['order_adj'] as $ord)
										{
											
											if($ord['received_weight']>0)
											{
												$chit_adj_wt +=($ord['received_weight']*($ord['rate_per_gram']));
											}
											$ord_adj_amt +=$ord['received_amount'];
										}
										$ord_adj_amt=$ord_adj_amt+$chit_adj_wt;
										
										?>
										
										<?php }?>
													<table id="pp" class="pay_mode_totals table text-center" style="font-weight: bold;">
														<tr>
															<td>CASH</td>
															
															<?php if($chq_amt>0){?>
															<td>CHQ</td>
															<?php }?>
															
															<?php if($card_amt>0) { ?>
															<?php foreach($payment['pay_details'] as $cardItem) {
															if($cardItem['payment_mode']=='DC' || $cardItem['payment_mode']=='CC') { ?>
															<td>CARD(<?php echo $cardItem['card_no'] ?>)</td>
															<?php } } }  ?>
															
															<?php if($rtgs_amt != 0) { ?>
															<td>RTGS</td>
															<?php } ?>
															
															<?php if($imps_amt != 0) { ?>
															<td>IMPS</td>
															<?php } ?>
															
															<?php if($upi_amt != 0) { ?>
															<td>UPI</td>
															<?php } ?>
															
															<?php if($due_amount != 0){?>
															<td>DUE AMOUNT</td>
															<?php }?>
															
															<?php if($adv_adj>0){?>
															<td>ADVANCE ADJ</td>
															<?php }?>
															<?php if($chit_adj != 0){?>
															<td>CHIT ADJ</td>
															<?php }?>
															
															<?php if($bouns_amt != 0){?>
															<td>BONUS</td>
															<?php }?>
															
															<?php if($ord_adj_amt != 0 || $billing['adv_adj_amt'] != 0){?>
															<td>ADV ADJ</td>
															<?php }?>
															<?php if($gift_amount != 0){?>
															<td>GIFT UTILIZED</td>
															<?php }?>

															<?php if($billing['credit_disc_amt'] != 0){?>
															<td>DISCOUNT</td>
															<?php }?>
															
															<?php if($billing['advance_deposit'] != 0){?>
            							        	        <td>Advance</td>
            							        	        <?php }?>
															
															
															<td>TOTAL</td>
															
														</tr>
														<tbody>
															<tr>
																<td><?php echo moneyFormatIndia(number_format($cash_amt,2,'.',''));?></td>

																<?php if($chq_amt > 0) { ?>
																<td><?php echo moneyFormatIndia(number_format($chq_amt,2,'.',''));?></td>
																<?php } ?>

																<?php if($card_amt>0){?>
																	<?php foreach($payment['pay_details'] as $cardItem) {
																	if($cardItem['payment_mode']=='DC' || $cardItem['payment_mode']=='CC') { ?>
																	<td><?php echo moneyFormatIndia(number_format($cardItem['payment_amount'],2,'.',''));?></td>
																<?php } } }?>
																
																<?php if($rtgs_amt != 0) { ?>
																<td><?php echo moneyFormatIndia(number_format($rtgs_amt,2,'.',''));?></td>
																<?php } ?>
																<?php if($imps_amt != 0) { ?>
																<td><?php echo moneyFormatIndia(number_format($imps_amt,2,'.',''));?></td>
																<?php } ?>
																<?php if($upi_amt != 0) { ?>
																<td><?php echo moneyFormatIndia(number_format($upi_amt,2,'.',''));?></td>
																<?php } ?>
															
																
																<?php if($due_amount != 0){?>
																<td><?php echo moneyFormatIndia(number_format($due_amount,2,'.',''));?></td>
																<?php }?>
																
																
																
																<?php if($adv_adj != 0){?>
																<td><?php echo moneyFormatIndia(number_format($adv_adj,2,'.',''));?></td>
																<?php }?>
																
																<?php if($chit_adj != 0){?>
																<td><?php echo moneyFormatIndia(number_format($chit_adj,2,'.',''));?></td>
																<?php }?>
																
																<?php if($bouns_amt>0){?>
																<td><?php echo moneyFormatIndia(number_format($bouns_amt,2,'.',''));?></td>
																<?php }?>
																
																<?php if($ord_adj_amt != 0 || $billing['adv_adj_amt'] != 0){?>
																<td><?php echo moneyFormatIndia(number_format($ord_adj_amt+$billing['adv_adj_amt'],2,'.',''));?></td>
																<?php }?>
																
																<?php if($gift_amount != 0){?>
																<td><?php echo moneyFormatIndia(number_format($gift_amount,2,'.',''));?></td>
																<?php }?>
																
																<?php if($billing['credit_disc_amt'] != 0){?>
																<td><?php echo moneyFormatIndia(number_format($billing['credit_disc_amt'],2,'.',''));?></td>
																<?php }?>

																<?php if($billing['advance_deposit'] != 0){?>
            							        	                <td><?php echo number_format($billing['advance_deposit'],2,'.','') ?></td>
            							        	            <?php }?>
            							        	            
																<td> 
																<?php if($billing['tot_amt_received']==0 && $due_amount>0) {  
																$amount_total = number_format(($billing['tot_bill_amount']),2,'.','');
																$amount_in_words = 	$billing['tot_bill_amount'];
																 } else { 
																$amount_total = moneyFormatIndia(number_format(($total_amt+$chit_adj+$bouns_amt+$ord_adj_amt+$gift_amount+$billing['adv_adj_amt']+$billing['credit_disc_amt']+$billing['advance_deposit']+($due_amount>0 ? $due_amount:0)),2,'.',''));
																$amount_in_words = 	$total_amt+$chit_adj+$bouns_amt+$ord_adj_amt+$gift_amount+$billing['adv_adj_amt']+$billing['credit_disc_amt']+$billing['advance_deposit']+($due_amount>0 ? $due_amount:0);
																}
																echo $amount_total;?></td>
																
																
							        	            
															</tr>
														</tbody>
													</table>
										
										<?php if(sizeof($est_other_item['chit_details'])>0){?>
											<div class="col-xs-6">
												<div class="table-responsive">
													<table id="pp" class="table text-center">
														<tr>
															<td width="2%;">S.No</td>
															<td width="5%;">Ref No</td>
															<td width="25%;">Amount</td>
														</tr>
														<tr>
															<td><hr class="item_dashed" style="width:450% !important;"></td>
														</tr>
														<?php
														$i=1; 
														foreach($est_other_item['chit_details'] as $chit)
														{?>
														<tr>
														<td><?php echo $i;?></td>
														<td><?php echo $chit['scheme_acc_number'];?></td>
														<td><?php echo moneyFormatIndia($chit['utilized_amt']);?></td>
														</tr>
														<?php $i++;}?>
													</table>
												</div>
											</div>
										<?php }?>
									<br>	
									<?php if(sizeof($est_other_item['order_adj'])>0){?>
										<div class="col-xs-6">
											<div class="table-responsive">
												<table id="pp" class="table text-center">
													<tr>
														<td>Date</td>
														<td class="alignRight">Amount</td>
														<td class="alignRight">Rate</td>
														<td class="alignRight">Weight</td>
													</tr>
													<tr>
														<td><hr class="item_dashed" style="width:600% !important;"></td>
													</tr>
										<?php  
										$i=1;
										$adv_paid_amount=0;
										$adv_paid_weight=0;
										foreach($est_other_item['order_adj'] as $ord)
										{
										$adv_paid_amount+=($ord['store_as']==1 ? $ord['advance_amount'] :( $ord['received_weight']* $ord['rate_per_gram']));
										$adv_paid_weight+=($ord['store_as']==1 ? ($ord['advance_amount']/$ord['rate_per_gram']):$ord['received_weight']);
										?>
											<tr>
												<td><?php echo $ord['bill_date'];?></td>
												<td class="alignRight"><?php echo number_format(($ord['store_as']==1 ? $ord['advance_amount'] :( $ord['received_weight']* $ord['rate_per_gram'])),2,'.','');?></td>
												<td class="alignRight"><?php echo $ord['rate_per_gram'];?></td>
												<td class="alignRight"><?php echo number_format(($ord['store_as']==1 ? ($ord['advance_amount']/$ord['rate_per_gram']):$ord['received_weight']),3,'.','');?></td>
											</tr>
											
										<?php $i++; } ?>
												<tr>
													<td><hr class="item_dashed" style="width:600% !important;"></td>
												</tr>
												<tr>
													<td>Total</td>
													<td class="alignRight"><?php echo moneyFormatIndia(number_format($adv_paid_amount,2,'.',''))?></td>
													<td class="alignRight"></td>
													<td class="alignRight"><?php echo number_format($adv_paid_weight,3,'.','')?></td>
												</tr>
											</table>
											</div>
										</div>
									<?php }?>
										
										<?php if($billing['gift_issue_amount']>0){?>
											<span>Gift Voucher Worth Rs. <?php echo ' '.$billing['gift_issue_amount'].' '?>Valid Till <?php echo $billing['valid_to'].'. Voucher Code - '.$billing['code'].'';?></span>
										<?php }else if($billing['gift_issue_weight']>0){?>
											<span>Gift Voucher Worth <?php echo ' '.$billing['gift_issue_weight'].' '.(($billing['utilize_for'])==1 ? ' Gold ' :' Silver ').' '?>Valid Till <?php echo $billing['valid_to'].'. Voucher Code - '.$billing['code'].'';?></span>
										<?php } ?>
										<?php 
										if($billing['note']!='')
										{?>
										<label>Terms and Conditions</label>
										<?php  echo $billing['note'];?>
										<?php } ?>
								
							</div><br>
							<?php if($amount_in_words != "") { ?>
							<div style="margin-top: 3px; margin-bottom: 3px">
								<div><span style="font-weight: bold;">Amount in Words</span> : <span >Rupees <?php echo $this->ret_billing_model->no_to_words($amount_in_words); ?> Only</span></div>
							</div>
							<?php } ?>
							<div style="font-weight: bold"> 
								EMP : <?php echo ($esti_sales_emp != '' ? $esti_sales_emp ."/".$esti_sales_id : ($esti_purchase_emp != '' ? $esti_purchase_emp ."/".$esti_purchase_id : ($esti_return_emp != '' ? $esti_return_emp ."/".$esti_return_id : $login_emp)));?>
							</div>
							<!--<div style="font-weight: bold"> 
								EMP-ID : <?php echo $billing['id_employee'] ?> / <?php echo $billing['emp_name'];?>
							</div>-->
						</div>
			</div><br><br>
			
			<?php if($billing['delivered_at']==2){?>
			<div class="row" >
                    <label><b>Delivered safely at : </b><br>
                    <label><?php echo ($billing['del_add_address1']!='' ? strtoupper($billing['del_add_address1']).','."<br>" :''); ?></label>
                    <label><?php echo ($billing['del_add_address2']!='' ? strtoupper($billing['del_add_address2']).','."<br>" :''); ?></label>
                    <label><?php echo ($billing['del_add_address3']!='' ? strtoupper($billing['del_add_address3']).','."<br>" :''); ?></label>
                    <label><?php echo ($billing['del_city_name']!='' ? strtoupper($billing['del_city_name']).($billing['del_pincode']!='' ? ' - '.$billing['del_pincode'].'.' :''):''); ?><br></label>
                    <label><?php echo ($billing['del_state_name']!='' ? strtoupper($billing['del_state_name']).','."<br>" :''); ?></label>
			</div>
			<?php }?>
				
			</div><!-- /.box-body --> 
</div>
<div>
</span>          
</body></html>
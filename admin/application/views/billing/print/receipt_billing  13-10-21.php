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
		.stoneDiv {
			display: inline-block;
		}
		.stoneName, .stoneTitle {
			width: 220px;
		}
		.stonePiece, .stonePieceTitle {
			width: 10px;
			text-align: right;
		}
		.stoneWt, .stoneWtTitle {
			width: 45px;
			text-align: right;
		}
		.stoneRate, .stoneRateTitle {
			width: 50px;
			text-align: right;
		}
		.stoneAmt, .stoneAmtTitle {
			width: 60px;
			text-align: right;
		}
		.stoneData {
			font-size: 9px !important;
			display:inline-block;
			vertical-align:top;
		}
		.stoneTotal {
			font-size: 9px !important;
			font-weight: bold;
			display:inline-block;
			vertical-align: middle;
			text-align: center;
			width: 100px;
		}
		.stoneTitle, .stonePieceTitle, .stoneWtTitle, .stoneRateTitle, .stoneAmtTitle {
			font-weight: bold;
			height: 12px;
		}
 
        </style>
</head><body style="margin-top:9%;">
<span class="PDFReceipt" >
            <!--<div>
                <img alt="" src="<?php echo base_url();?>assets/img/receipt_logo.png">
            </div>-->
<?php 
function moneyFormatIndia($num) {
	return preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $num);
}

function stone_details_row($stones, $rowspan, $colspan) {
	//echo "<pre>"; print_r($stones); echo "</pre>";
	$stoneData = '';
	$stoneTotal = 0;
	foreach($stones as $stoneval) {
		$stoneTotal += $stoneval['amount'];
		$stoneData .= "<div>
							<div class='stoneDiv stoneName textOverflowHidden'>".($stoneval['design_name']."-".$stoneval['stone_name'])."</div><div class='stoneDiv stonePiece'>".($stoneval['pieces'])."</div><div class='stoneDiv stoneWt'>".(number_format($stoneval['wt'],3,'.',''))."</div><div class='stoneDiv stoneRate'>".($stoneval['rate_per_gram'])."</div><div class='stoneDiv stoneAmt'>".($stoneval['amount'])."</div></div>";
	}

	if($stoneTotal > 0) {
		$stoneTitles = "<div class='stoneDiv stoneTitle'>STONE DETAILS</div><div class='stoneDiv stonePieceTitle'>PCS</div><div class='stoneDiv stoneWtTitle'>WT</div><div class='stoneDiv stoneRateTitle'>RATE</div><div class='stoneDiv stoneAmtTitle'>TOTAL</div>";
		$stoneData = "<div class='stoneData'>".$stoneTitles.$stoneData."</div>";
		$stoneData .= "<div class='stoneTotal'><div>Total St.Amt</div> <div>Rs.".$stoneTotal."</div></div>";
	}

	$rowData =  "<td rowspan='".$rowspan."' colspan='".$colspan."'>".$stoneData."</td>";

	return $rowData;
}

$gold_metal_rate = ($billing['goldrate_22ct']>0 ? $billing['goldrate_22ct']:$metal_rate['goldrate_22ct']);
$silver_metal_rate = ($billing['silverrate_1gm']>0 ?$billing['silverrate_1gm'] :$metal_rate['silverrate_1gm']);
$metal_type = 0;

$tot_sales_amt=0;
$sales_cost = 0;
foreach($est_other_item['item_details'] as $items) {
	$sales_cost     += $items['item_cost'];
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

$stones = array();

?>
    <div style="height: 630px">
			<div style="width: 100%; text-transform:uppercase; margin-top: -50px">
				<div style="display: inline-block; width: 50%;">
					<div style="width: 100%; text-align: left; height: 12px; margin-top: -15px">
						<div style="text-align: left; width: 20%; display:inline-block"> Date </div>
						<div style="text-align: left; width: 80%; display:inline-block; margin-top:-2px"> <?php echo " &nbsp; : &nbsp; ".$billing['bill_date']; ?></div>
					</div>
					<div style="width: 100%; text-align: left; height: 20px">
						<div style="text-align: left; width: 20%; display:inline-block"> <span><?php echo ($billing['is_trail']==0 ? 'GST Bill No' :'Trail Bill No')?></span> </div>
						
						<div style="text-align: left; width: 80%; display:inline-block; margin-top:-2px"> <span><?php echo " &nbsp; : &nbsp; ".($billing['branch_code']!='' ? $billing['branch_code'].'/':'').($billing['fin_year_code']!='' ?$billing['fin_year_code'].'/' :'').($billing['bill_type']==1 || $billing['bill_type']==2 || $billing['bill_type']==3 || $billing['bill_type']==7 || $billing['bill_type']==9 ? 'SA/':($billing['bill_type']==4 ? 'PUR/' :$billing['bill_type']==5 ? 'OD/':'')).$billing['bill_no']; ?> </span> </div>
					</div>
				</div>

				<!--<div style="width: 50%; text-align: center; display: inline-block; vertical-align: top; font-weight: bold; display:none">
					<label><?php echo ($billing['is_trail']==0 ? 'TAX INVOICE' :'TRAIL INVOICE');?></label>
				</div>-->
				<div style="width: 50%; text-align: right; display: inline-block;margin-top: 5px">
					<div style="text-align: right; width:100%; height: 15px; ">
						<div style="width: 82%; display: inline-block"> GOLD &nbsp; : &nbsp; </div>
						<div style="width: 18%; display: inline-block; margin-top: -2px"> <?php echo number_format($gold_metal_rate,2,'.','').'/'.'Gm'; ?></div>
					</div>
					<div style="text-align: right; width:100%; height: 10px; ">
						<div style="width: 82%; display: inline-block"> SILVER &nbsp; : &nbsp; </div>
						<div style="width: 18%; display: inline-block; margin-top: -2px"> <?php echo $silver_metal_rate.'/'.'Gm'; ?> </div>
					</div>
				</div>
			</div>
		 	<div style="width: 100%; text-align: center; margin-top:-27px; font-weight: bold; text-transform:uppercase;">
				<label>
					<?php echo ($billing['bill_type']==1 ? 'Sales Bill': ($billing['bill_type']==2 ? 'Sales And Purchase Bill':($billing['bill_type']==3 ? 'Sales and purchase bill' :($billing['bill_type']==4 ? ' URD Purchase Bill':($billing['bill_type']==5 ? 'Order Advance Receipt' :($billing['bill_type']==6 ? 'Advance Receipt' :($billing['bill_type']==7 ? 'Sales Return Receipt':($billing['bill_type']==9 ? 'Order Delivery' :($billing['bill_type']==10 ? 'Chit PreClose Receipt':'Credit Collection Receipt'))))))))).($billing['bill_status']==2 ? '- Cancelled' : '');?>
				</label>
			</div>
			<div style="width: 100%; display: inline-block; text-transform:uppercase; height:30px;">
				<div style="width: 55%; text-align: left; display: inline-block">
					<?php if($billing['cus_type']==1) { ?>
						<div style="text-align: left; width:100%; height: 20px; ">
							<div><?php echo 'Shri.'.$billing['customer_name'].' '.$billing['mobile']; ?></div>
						</div>
					<?php } ?>

					<?php if($billing['cus_type']==2) { ?>
						<div style="text-align: left; width:100%; height: 20px;">
							<label><?php echo ($billing['cmp_user_name']!='' ?$billing['cmp_user_name'] :$billing['customer_name']); ?> </label>
						</div>
					<?php } ?>
				</div>
				<div style="width: 45%; text-align: right; display: inline-block; margin-top: -3px">
					<?php echo $billing['gst_number'] != '' ? "GST NO:". $billing['gst_number'] : ''?>
					<?php echo $billing['gst_number'] != '' && $billing['pan_number'] != '' ? ", " : ''?>
					<?php echo $billing['pan_number'] != '' ? "PAN NO:". $billing['pan_number'] : ''?>
				</div>
			</div>
			<?php if($billing['cus_type']==1) { ?>
				<?php if($billing['address1'] !='' || $billing['address2'] != '' || $billing['pincode'] != '') { ?>
					<div style="margin-top: -20px; height: 30px">
						<label><?php echo ($billing['address1']!='' ? $billing['address1'] :''); ?></label> 
						<label> <?php echo ($billing['address2']!='' ? $billing['address2'] :''); ?></label>
						<label><?php echo ($billing['city']!='' ?$billing['city'].'.'.($billing['pincode']!='' ? ' - '.$billing['pincode'] :'') :''); ?></label>	
					</div>
			<?php } } else if($billing['cus_type']==2) { ?>
					<div style="margin-top: -20px; height: 30px"> 
						<label><?php echo ($billing['cmp_user_name'] != '' ? $billing['cmp_user_name'] : $billing['customer_name']); ?> </label> 
						<label><?php echo ($billing['address1']!='' ? $billing['address1'] :''); ?></label> 
						<label> <?php echo ($billing['address2']!='' ? $billing['address2'] :''); ?></label>
						<label><?php echo ($billing['city']!='' ?$billing['city'].'.'.($billing['pincode']!='' ? ' - '.$billing['pincode'] :'') :''); ?></label>	
					</div>
			<? } ?>

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
															<td class="table_heading" style="width: 8%">HSN</td>
															<td class="table_heading" style="width: 20%">Description</td>
															<td class="table_heading alignRight" style="width: 5%">PCS</td>
															<td class="table_heading alignRight" style="width: 9%">Gwt(g)</td>
															<td class="table_heading alignRight" style="width: 9%">Nwt(g)</td>
															<td class="table_heading alignRight" style="width: 9%">MC</td>
															<td class="table_heading alignRight" style="width: 13%">VA</td>
															<td class="table_heading alignRight" style="width: 10%">AF.VA</td>
															<td class="table_heading alignRight" style="width: 12%">Amount</td>
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
													foreach($est_other_item['item_details'] as $items)
														{
															if(count($items['stone_details']) > 0) {
																foreach($items['stone_details'] as $stoneItems) {
																	$stoneItems['design_name'] = $items['design_name'];
																	$stones[]  = $stoneItems;
																}
															}

															$mc=0;
															$wastge_wt=0;
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

															$after_wastage = $wastge_wt - ($items['bill_discount'] / $gold_metal_rate);
															$after_wastage = ( $after_wastage / $items['net_wt'] ) * 100;
														?>

														<tr>
														<td><?php echo $i;?></td>
														<td><?php echo $items['hsn_code'];?></td>
														<td style="font-size:10px !important;" class='textOverflowHidden'><?php echo $items['product_name']."-".$items['design_name'];?></td>
														<td class="alignRight"><?php echo $items['piece'];?></td>
														<td class="alignRight"><?php echo $items['gross_wt'];?></td>
														<td class="alignRight"><?php echo $items['net_wt'];?></td>
														<td class="alignRight"><?php echo $mc;?></td>
														<td class="alignRight"><?php echo number_format($wastge_wt,3,'.',''). " (" .($items['wastage_percent'] +0) ?>%)</td>
														<td class="alignRight" style="font-weight: bold">(<?php echo number_format($after_wastage,2,'.','') + 0?>%)</td>
														<td class="alignRight"><?php echo moneyFormatIndia(number_format((float)($item_taxable+$items['bill_discount']),2,'.',''));?></td>
														</tr>
													<?php $i++;}?>
												<!--</tbody> -->
														<tr>
															<td><hr class="item_dashed"></td>
														</tr>
														<tr class="total" style="font-weight: bold" >
															<td></td>
															<td></td>
															<td>TOTAL</td>
															<td class="alignRight"><?php echo $pieces;?></td>
															<td class="alignRight"><?php echo number_format($gross_wt,3,'.','');?></td>
															<td class="alignRight"><?php echo number_format($net_wt,3,'.','');?></td>
															<td></td>
															<td></td>
															<td></td>
															<td class="alignRight"><?php echo moneyFormatIndia(number_format((float)($taxable_amt+$bill_discount),2,'.',''));?></td>
														</tr>
														<tr>
															<td><hr class="item_dashed"></td>
														</tr>
														<?php
														$rowspan = 1;
														$colspan = 7;
														$rowspan = $bill_discount > 0 ? ++$rowspan : $rowspan;
														$rowspan = $taxable_amt > 0  ? ++$rowspan : $rowspan;
														$rowspan = ($billing['cus_state'] == $comp_details['id_state'] || ($billing['cus_state']== '')) ? $rowspan + 2 : $rowspan + 1;
														$rowspan = $billing['tcs_tax_amt'] > 0 ? $rowspan + 2 : $rowspan;

														$rowspanRow = ($bill_discount > 0 ? 'bill_discount' : ($taxable_amt > 0 ? 'taxable_amt' : ($billing['cus_state']==$comp_details['id_state'] || ($billing['cus_state']=='') ? 'sgst_cgst' : ($billing['cus_state'] != $comp_details['id_state'] > 0 ? 'igst' : 'total'))));
														$rowspanRow = $billing['tcs_tax_amt'] > 0 ? 'tcs_tax_amt' : $rowspanRow;

														if($bill_discount>0) { ?>
														<tr>
															<?php if($rowspanRow == 'bill_discount') { echo stone_details_row($stones, $rowspan, $colspan); } ?>
															<td class="alignRight">LESS DISC</td>
															<td class="alignRight">Rs.</td>
															<td class="alignRight"><?php echo moneyFormatIndia(number_format($bill_discount,2,'.',''));?></td>
														</tr>
														<?php } ?>
														<?php if($taxable_amt>0){?>
														<tr>
															<?php if($rowspanRow == 'taxable_amt') { echo stone_details_row($stones, $rowspan, $colspan); } ?>
															<td class="alignRight">SUB TOTAL</td>
															<td class="alignRight">Rs.</td>
															<td class="alignRight"><?php echo moneyFormatIndia(number_format((float)($taxable_amt),2,'.',''));?></td>
														</tr>
														<?php } ?>
														<?php if($billing['cus_state']==$comp_details['id_state'] || ($billing['cus_state']=='')){?>
														<tr>
															<?php if($rowspanRow == 'sgst_cgst') { echo stone_details_row($stones, $rowspan, $colspan); } ?>
															<td class="alignRight">SGST</td>
															<td class="alignRight"><?php echo ($est_other_item['item_details'][0]['tax_percentage']/2).'%'?></td>
															<td class="alignRight"><?php echo moneyFormatIndia($total_sgst);?></td>
														</tr>
														<tr>
															<td class="alignRight">CGST</td>
															<td class="alignRight"><?php echo ($est_other_item['item_details'][0]['tax_percentage']/2).'%'?></td>
															<td class="alignRight"><?php echo moneyFormatIndia($total_cgst);?></td>
														</tr>

														<?php }else{?>
															<tr>
															<?php if($rowspanRow == 'igst') { echo stone_details_row($stones, $rowspan, $colspan); } ?>
															<td class="alignRight">IGST</td>
															<td class="alignRight"><?php echo ($est_other_item['item_details'][0]['tax_percentage']).'%'?></td>
															<td class="alignRight"><?php echo moneyFormatIndia($total_igst);?></td>
														</tr>
														<?php }?>
														<tr>
															<?php if($rowspanRow == 'total') { echo stone_details_row($stones, $rowspan, $colspan); } ?>
															<td class="alignRight">TOTAL</td>
															<td class="alignRight">Rs.</td>
															<td class="alignRight"><?php echo moneyFormatIndia($tot_sales_amt+$billing['handling_charges']);?></td>
														</tr>
														<?php if($billing['tcs_tax_amt']>0){?>
														<tr>
															<?php if($rowspanRow == 'tcs_tax_amt') { echo stone_details_row($stones, $rowspan, $colspan); } ?>
															<td class="alignRight">TCS %</td>
															<td class="alignRight"><?php echo $billing['tcs_tax_per'];?></td>
															<td class="alignRight"><?php echo moneyFormatIndia($billing['tcs_tax_amt']);?></td>
														</tr>
														<tr>
															<td class="alignRight">Net Amt</td>
															<td class="alignRight">Rs.</td>
															<td class="alignRight"><?php echo moneyFormatIndia($tot_sales_amt+$billing['handling_charges']+$billing['tcs_tax_amt']);?></td>
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
														<tr style="text-transform:uppercase; display:none">
															<td class="table_heading">S.No</td>
															<td class="table_heading">HSN Code</td>
															<td class="table_heading">Description</td>
															<td class="table_heading alignRight">PCS</td>
															<td class="table_heading alignRight">Gwt(g)</td>
															<td class="table_heading alignRight">Nwt(g)</td>
															<td class="table_heading alignRight">MC</td>
															<td class="table_heading alignRight">V.A</td>
															<td class="table_heading alignRight">Amount</td>
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
														<tr style="display:none">
															<td colspan="9" style="text-transform:uppercase; font-weight: bold">Refer sale  bill no : <?php echo $items['ref_bill_no'].' Dt '.$items['ref_bill_date']?> </td>
														</tr>
														<tr>
														<td style="width: 5%"><?php echo $i;?></td>
														<td style="width: 8%"><?php echo $items['hsn_code'];?></td>
														<td style="width: 20%; font-size:10px;" class='textOverflowHidden'><?php echo $items['product_name'];?></td>
														<td style="width: 5%" class="alignRight"><?php echo $items['piece'];?></td>
														<td style="width: 9%" class="alignRight"><?php echo $items['gross_wt'];?></td>
														<td style="width: 9%" class="alignRight"><?php echo $items['net_wt'];?></td>
														<td style="width: 9%" class="alignRight"><?php echo number_format($mc,2,'.','');?></td>
														<td style="width: 13%" class="alignRight"><?php echo number_format($wastge_wt,3,'.','');?></td>
														<td style="width: 22%" class="alignRight"><?php echo moneyFormatIndia(number_format($items['item_cost'],2,'.',''));?></td>
														</tr>
													<?php $i++;}?>
														<tr>
															<td><hr class="return_dashed"></td>
														</tr>
												<!--</tbody> -->
													<!--<tfoot>-->
														<tr>
															<td>Total</td>
															<td></td>
															<td></td>
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
															<td colspan="6"></td>
															<td class="alignRight">Sales Amount</td>
															<td class="alignRight">Rs.</td>
															<td class="alignRight"><?php echo moneyFormatIndia(number_format($tot_sales_amt+$billing['handling_charges'],2,'.',''));?></td>
														</tr>
														<?php }?>
														<?php if($total_return>0){?>
														<tr>
															<td colspan="6"></td>
															<td class="alignRight">Exchange Amount</td>
															<td class="alignRight">Rs.</td>
															<td class="alignRight"><?php echo moneyFormatIndia(number_format($total_return,2,'.',''));?></td>
														</tr>
														<?php } ?>
														<?php if($tot_sales_amt > 0 && $total_return > 0){?>
														<tr>
															<td colspan="6"></td>
															<td class="alignRight">Net Amount</td>
															<td class="alignRight">Rs.</td>
															<td class="alignRight"><?php echo moneyFormatIndia(number_format(($tot_sales_amt+$billing['handling_charges']) -$total_return,2,'.',''));?></td>
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
														<tr style="display:none">
															<td class="table_heading" width="5%;">S.No</td>
															<td class="table_heading" width="25%;">Description</td>
															<td class="table_heading" width="20%;">Design</td>
															<td class="table_heading alignRight" width="5%;">PCS</td>
															<td class="table_heading alignRight" width="15%;">Gwt(g)</td>
														</tr>
														<tr>
															<td><hr class="order_tr_item_dashed"></td>
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
														<td><hr class="order_tr_item_dashed"></td>
													</tr>
													
													<tr>
														<td>TOTAL</td>
														<td></td>
														<td></td>
														<td></td>
														<td class="alignRight"><?php echo number_format($weight,3,'.','');?></td>
													</tr>
													
													<tr>
														<td><hr class="order_tr_item_dashed"></td>
													</tr>
													<?php if($amount>0){?>
													<tr>
															<td colspan="4"><?php echo 'Received with thanks from Mr./Ms.'.$billing['customer_name'].' Towards Order  No : '.$order_no.'';?></td>
															<td colspan="2"><?php echo 'Rs. '.moneyFormatIndia($amount);?></td>
													</tr>
													<?php }?>
												</table><br>	
											</div>	
										</div>	
									</div><br>
									<?php }?>

									<?php if(sizeof($est_other_item['old_matel_details'])>0){?>
										<span style="font-weight: bold">PURCHASE NO : <?php echo $billing['pur_ref_no'];?></span>

											<table id="pp" class="table text-center">
												<!--	<thead> -->
														<tr style="text-transform:uppercase; display:none">
															<td class="table_heading">S.No</td>
															<td class="table_heading">HSN</td>
															<td class="table_heading">Metal</td>
															<td class="table_heading alignRight">GROSS WT(g)</td>
															<td class="table_heading alignRight">Nwt(g)</td>
															<td class="table_heading alignRight">V.A</td>
															<td class="table_heading alignRight">Rate</td>
															<td class="table_heading alignRight">Amount</td>
														</tr>
														<tr>
															<td><hr class="old_metal_dashed"></td>
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
															$gross_wt+=$items['gross_wt'];
															$net_wt+=($items['net_wt']);
														?>
														<tr>
														<td style="width: 5%"><?php echo $i;?></td>
														<td style="width: 8%">71080000</td>
														<td style="width: 25%; font-size:10px;" class='textOverflowHidden'><?php echo ($items['metal_type']==1 ?'Old Gold' :'Old Silver');?></td>
														<td style="width: 9%" class="alignRight"><?php echo $items['gross_wt'];?></td>
														<td style="width: 9%" class="alignRight"><?php echo ($items['net_wt']);?></td>
														<!--<td style="width: 22%" class="alignRight"><?php echo $items['wast_wt'];?></td>-->
														<td style="width: 22%" class="alignRight"><?php echo number_format(($items['gross_wt'] - $items['net_wt']),3,'.','') ;?></td>
														<td style="width: 10%" class="alignRight"><?php echo $items['rate_per_gram'];?></td>
														<td style="width: 12%" class="alignRight"><?php echo moneyFormatIndia($items['amount']);?></td>
														</tr>
													<?php $i++;}?>
												<!--</tbody> -->
														<tr>
															<td><hr class="old_metal_dashed"></td>
														</tr>
														<tr>
															<td>Total</td>
															<td></td>
															<td></td>
															<td class="alignRight"><?php echo number_format($gross_wt,3,'.','');?></td>
															<td class="alignRight"><?php echo number_format($net_wt,3,'.','');?></td>
															<td></td>
															<td></td>
															<td class="alignRight"><?php echo moneyFormatIndia(number_format((float)$pur_total_amt,2,'.',''));?></td>
														</tr>
														<tr>
															<td><hr class="old_metal_dashed"></td>
														</tr>
														<?php if($tot_sales_amt+$billing['handling_charges'] > 0){?>
														<tr>
															<td colspan="4"></td>
															<td class="alignRight" colspan="2">Sales Amount</td>
															<td class="alignRight">Rs.</td>
															<td class="alignRight"><?php echo moneyFormatIndia(number_format($tot_sales_amt+$billing['handling_charges'],2,'.',''));?></td>
														</tr>
														<?php }?>
														<?php if($total_return>0){?>
														<tr>
															<td colspan="4"></td>
															<td class="alignRight" colspan="2">Exchange Amount</td>
															<td class="alignRight">Rs.</td>
															<td class="alignRight"><?php echo moneyFormatIndia(number_format($total_return,2,'.',''));?></td>
														</tr>
														<?php }?>
														<?php if($pur_total_amt > 0) { ?>
														<tr>
															<td colspan="4">
																<?php
																	$cgst_sgst_perc = ($est_other_item['item_details'][0]['tax_percentage']/2);
																	$cgst_sgst_amt = ($pur_total_amt * $cgst_sgst_perc / 100);
																?>	
																	RCM : (CGST <?php echo  $cgst_sgst_perc ."% - ". number_format($cgst_sgst_amt,2,'.','') ?>,  SGST <?php echo  $cgst_sgst_perc ."% - ". number_format($cgst_sgst_amt,2,'.','') ?>)
															</td>
															<td class="alignRight" colspan="2">Purchase Amount</td>
															<td class="alignRight">Rs.</td>
															<td class="alignRight"><?php echo moneyFormatIndia(number_format($pur_total_amt ,2,'.',''));?></td>
														</tr>
														<?php } ?>

														<?php if($tot_sales_amt>0){?>
														<tr>
															<td colspan="4"></td>
															<td class="alignRight" colspan="2">Net Amount</td>
															<td class="alignRight">Rs.</td>
															<td class="alignRight"><?php echo moneyFormatIndia(number_format(($tot_sales_amt+$billing['handling_charges']) -$total_return-$pur_total_amt,2,'.',''));?></td>
														</tr>
														<?php }?>
												</table>
									<?php }?>

									<?php if($billing['bill_type']==8){?>
										<div  class="row">
											<div align="center" style="text-transform:uppercase;">
												<label>Credit No : <?php echo $billing['ref_bill_id'];?></label>
											</div>
											
										<div class="col-xs-12">
											<div class="table-responsive">
											<table id="pp" class="table text-center">
												<!--	<thead> -->
														<tr style="display:none">
															<th colspan="5">Description</th>
															<th>Amount</th>
														</tr>
													<!--</thead>
													<tbody>-->
														<tr>
															<td colspan="5"><?php echo 'Received with thanks from Mr./Ms.'.$billing['customer_name'].' Towards Credit Bill No : '.$billing['ref_bill_no'].'/ Ref No : '.$billing['ref_bill_id'];?></td>
															<td class="alignRight" colspan="5"><?php echo $billing['due_amount'];?></td>
														</tr>
												<!--</tbody> -->
														<tr>
															<td colspan="7"><hr style="border-bottom:0.5pt;"></td>
														</tr>
												</table><br>	
											</div>	
										</div>	
									</div><br>
									<?php }?>

								
										
												<?php 
													if($billing['bill_type']!=8)
													{
														$due_amount=number_format(($billing['tot_bill_amount']-$billing['tot_amt_received']),2,'.','');
													}
													if(sizeof($payment['pay_details'])>0){
													$cash_amt=0;
													$card_amt=0;
													$net_banking_amt=0;
													$due_amount=0;
													$gift_amount=0;
													$chit_amount=0;
													$total_amt=0;
													$adv_adj=0;
													$imps_amt=0;
													$rtgs_amt=0;
													$upi_amt=0;
													if($billing['bill_type']==8)
													{
														$due_amount=number_format(($billing['tot_bill_amount']-($billing['tot_adv_received']+$billing['tot_paid_amt']+$pur_total_amt)),2,'.','');
													}else{
														if($billing['is_credit']==1)
														{
															$due_amount=number_format(($billing['tot_bill_amount']-$billing['tot_amt_received']),2,'.','');
														}
														else{
															$due_amount=number_format(($billing['tot_bill_amount']-$billing['tot_amt_received']),2,'.','');
														}
													}
													
													foreach($payment['pay_details'] as $items)
														{
															$total_amt+=$items['payment_amount'];
															
															if($items['payment_mode']=='Cash')
															{
																$cash_amt+=$items['payment_amount'];
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
													<table id="pp" class="table text-center" style="width:65%; font-weight: bold">
														<tr>
															<td>CASH</td>
															<?php if($card_amt>0) { ?>
															<?php foreach($payment['pay_details'] as $cardItem) {
															if($cardItem['payment_mode']=='DC' || $cardItem['payment_mode']=='CC') { ?>
															<td>CARD(<?php echo $cardItem['card_no'] ?>)</td>
															<?php } } }  ?>
															<?php if($rtgs_amt > 0) { ?>
															<td>RTGS</td>
															<?php } ?>
															<?php if($imps_amt > 0) { ?>
															<td>IMPS</td>
															<?php } ?>
															<?php if($upi_amt > 0) { ?>
															<td>UPI</td>
															<?php } ?>
															<?php if($due_amount>0){?>
															<td>DUE AMOUNT</td>
															<?php }?>
															
															<?php if($adv_adj>0){?>
															<td>ADVANCE ADJ</td>
															<?php }?>
															<?php if($chit_adj>0){?>
															<td>CHIT ADJ</td>
															<?php }?>
															
															<?php if($bouns_amt>0){?>
															<td>BONUS</td>
															<?php }?>
															
															<?php if($ord_adj_amt>0 || $billing['adv_adj_amt']>0){?>
															<td>ADV ADJ</td>
															<?php }?>
															<?php if($gift_amount>0){?>
															<td>GIFT UTILIZED</td>
															<?php }?>
															
															<?php if($billing['credit_disc_amt']>0){?>
															<td>DISCOUNT</td>
															<?php }?>
															
															<td style="display:none">ROUND OFF</td>
															<td>TOTAL</td>
														</tr>
														<tbody>
															<tr>
																<td><?php echo moneyFormatIndia(number_format($cash_amt,2,'.',''));?></td>
																
																<?php if($card_amt>0){?>
																	<?php foreach($payment['pay_details'] as $cardItem) {
																	if($cardItem['payment_mode']=='DC' || $cardItem['payment_mode']=='CC') { ?>
																	<td><?php echo moneyFormatIndia(number_format($cardItem['payment_amount'],2,'.',''));?></td>
																<?php } } }?>
																
																<?php if($rtgs_amt > 0) { ?>
																<td><?php echo moneyFormatIndia(number_format($rtgs_amt,2,'.',''));?></td>
																<?php } ?>
																<?php if($imps_amt > 0) { ?>
																<td><?php echo moneyFormatIndia(number_format($imps_amt,2,'.',''));?></td>
																<?php } ?>
																<?php if($upi_amt > 0) { ?>
																<td><?php echo moneyFormatIndia(number_format($upi_amt,2,'.',''));?></td>
																<?php } ?>
															
																
																<?php if($due_amount>0){?>
																<td><?php echo moneyFormatIndia(number_format($due_amount,2,'.',''));?></td>
																<?php }?>
																
																
																
																<?php if($adv_adj>0){?>
																<td><?php echo moneyFormatIndia(number_format($adv_adj,2,'.',''));?></td>
																<?php }?>
																
																<?php if($chit_adj>0){?>
																<td><?php echo moneyFormatIndia(number_format($chit_adj,2,'.',''));?></td>
																<?php }?>
																
																<?php if($bouns_amt>0){?>
																<td><?php echo moneyFormatIndia(number_format($bouns_amt,2,'.',''));?></td>
																<?php }?>
																
																<?php if($ord_adj_amt>0 || $billing['adv_adj_amt']>0){?>
																<td><?php echo moneyFormatIndia(number_format($ord_adj_amt+$billing['adv_adj_amt'],2,'.',''));?></td>
																<?php }?>
																
																<?php if($gift_amount>0){?>
																<td><?php echo moneyFormatIndia(number_format($gift_amount,2,'.',''));?></td>
																<?php }?>
																
																<?php if($billing['credit_disc_amt']>0){?>
																<td><?php echo moneyFormatIndia(number_format($billing['credit_disc_amt'],2,'.',''));?></td>
																<?php }?>
															
																<td style="display:none"><?php echo moneyFormatIndia(number_format($billing['round_off_amt'],2,'.',''));?></td>
																<td> 
																<?php if($billing['tot_amt_received']==0 && $due_amount>0) {  
																$amount_total = number_format(($billing['tot_bill_amount']),2,'.','');
																$amount_in_words = 	$billing['tot_bill_amount'];
																 } else { 
																$amount_total = moneyFormatIndia(number_format(($total_amt+$chit_adj+$bouns_amt+$ord_adj_amt+$gift_amount+$billing['adv_adj_amt']+$billing['credit_disc_amt']+($due_amount>0 ? $due_amount:0)),2,'.',''));
																$amount_in_words = 	$total_amt+$chit_adj+$bouns_amt+$ord_adj_amt+$gift_amount+$billing['adv_adj_amt']+$billing['credit_disc_amt']+($due_amount>0 ? $due_amount:0);
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
										
											<?php if(sizeof($est_other_item['order_adj'])>0){?>
										<div class="col-xs-6">
											<div class="table-responsive">
												<table id="pp" class="table text-center" style="width:100%;">
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
								
							</div>
							<div style="margin-top: 5px">
								<div><span style="font-weight: bold;">Amount in Words</span> : <span >Rupees <?php echo $this->ret_billing_model->no_to_words($amount_in_words); ?> Only</span></div>
							</div>
							<div style="font-weight: bold">
								On Exchange Rs: <?php echo $settings['on_exchange_in_billing'] ?>+ MC+Wast+Va+Stone+Stone.Wt+GST Will Be Deducted.
							</div>
							<div style="font-weight: bold"> 
								EMP : <?php echo $billing['emp_name'];?>
							</div>
						</div>
			</div>
			</div><!-- /.box-body --> 
</div>
<div>

<div  class="container-fluid duplicate_copy" style="margin-top: 7px;">
	<div style="width: 100%; font-weight: bold; text-transform:uppercase"> 
		<span>Sales Bill No :</span> 
		<span> <?php echo $billing['bill_no'] ?></span>
		<span> <?php echo 'Shri.'.$billing['customer_name']; ?></span>
		<span> <?php echo ($billing['city']!='' ? $billing['city'] :'').' '.$billing['mobile']; ?></span>
	</div>
	<div id="printable">
		<?php if(sizeof($est_other_item['item_details'])>0){?>
				<div class="col-xs-12">
					<div class="table-responsive">
					<table id="pp" class="table text-center" >
							<thead style="text-transform:uppercase;">
								<tr style="display:none">
									<td class="table_heading" style="width: 5%">S.No</td>
									<td class="table_heading" style="width: 8%">HSN</td>
									<td class="table_heading" style="width: 20%">Description</td>
									<td class="table_heading alignRight" style="width: 5%">PCS</td>
									<td class="table_heading alignRight" style="width: 9%">Gwt(g)</td>
									<td class="table_heading alignRight" style="width: 9%">Nwt(g)</td>
									<td class="table_heading alignRight" style="width: 9%">MC</td>
									<td class="table_heading alignRight" style="width: 13%">VA</td>
									<td class="table_heading alignRight" style="width: 10%">AF.VA</td>
									<td class="table_heading alignRight" style="width: 12%">Amount</td>
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
							foreach($est_other_item['item_details'] as $items)
								{
									$mc=0;
									$wastge_wt=0;
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

									$after_wastage = $wastge_wt - ($items['bill_discount'] / $gold_metal_rate);
									$after_wastage = ( $after_wastage / $items['net_wt'] ) * 100;
								?>
								<tr>
								<td style="width: 5%"><?php echo $i;?></td>
								<td style="width: 8%"><?php echo $items['hsn_code'];?></td>
								<td style="width: 20%" class='textOverflowHidden'><?php echo $items['product_name']."-".$items['design_name'];?></td>
								<td style="width: 5%" class="alignRight"><?php echo $items['piece'];?></td>
								<td style="width: 9%" class="alignRight"><?php echo $items['gross_wt'];?></td>
								<td style="width: 9%" class="alignRight"><?php echo $items['net_wt'];?></td>
								<td style="width: 9%" class="alignRight"><?php echo $mc;?></td>
								<td style="width: 13%" class="alignRight"><?php echo number_format($wastge_wt,3,'.',''). " (" .($items['wastage_percent'] +0) ?>%)</td>
								<td style="width: 10%" class="alignRight" style="font-weight: bold">(<?php echo number_format($after_wastage,2,'.','') + 0?>%)</td>
								<td style="width: 12%" class="alignRight"><?php echo moneyFormatIndia(number_format((float)($item_taxable+$items['bill_discount']),2,'.',''));?></td>
								</tr>
							<?php $i++;}?>
						<!--</tbody> -->
								<tr>
									<td><hr class="item_dashed"></td>
								</tr>
								<tr class="total" style="font-weight: bold" >
									<td></td>
									<td></td>
									<td>TOTAL</td>
									<td class="alignRight"><?php echo $pieces;?></td>
									<td class="alignRight"><?php echo number_format($gross_wt,3,'.','');?></td>
									<td class="alignRight"><?php echo number_format($net_wt,3,'.','');?></td>
									<td></td>
									<td></td>
									<td></td>
									<td class="alignRight"><?php echo moneyFormatIndia(number_format((float)($taxable_amt+$bill_discount),2,'.',''));?></td>
								</tr>
								<tr>
									<td><hr class="item_dashed"></td>
								</tr>
								<?php if($bill_discount>0){?>
								<tr style="display:none">
									<td colspan="6" ></td>
									<td colspan="2">LESS DISC</td>
									<td></td>
									<td class="alignRight"><?php echo moneyFormatIndia(number_format($bill_discount,2,'.',''));?></td>
								</tr>
								<?php }?>
								<?php if($taxable_amt>0){?>
								<tr style="display:none">
									<td colspan="6" ></td>
									<td colspan="2">SUB TOTAL</td>
									<td >Rs.</td>
									<td class="alignRight"><?php echo moneyFormatIndia(number_format((float)($taxable_amt),2,'.',''));?></td>
								</tr>
								<?php }?>
								<tr>
									<td colspan='7'  class="alignRight">
									<?php 
									if($billing['cus_state']==$comp_details['id_state'] || ($billing['cus_state']=='')) {
										$tax_igst = 0;
									} else {
										$tax_igst = 1;
									}
									if($billing['tcs_tax_amt']>0){
										$has_tcs_amt = 1;
										$totalAmt = $tot_sales_amt+$billing['handling_charges']+$billing['tcs_tax_amt'];
									} else {
										$has_tcs_amt = 0;
										$totalAmt = $tot_sales_amt+$billing['handling_charges'];
									} 
									if($tax_igst == 0) { 
										echo "SGST ".($est_other_item['item_details'][0]['tax_percentage']/2).'% &nbsp; &nbsp; '.moneyFormatIndia($total_sgst).'&nbsp; &nbsp; ';
										echo "CGST ".($est_other_item['item_details'][0]['tax_percentage']/2).'% &nbsp; &nbsp; '.moneyFormatIndia($total_cgst).'&nbsp; &nbsp; ';
									} else {
										echo "IGST ".($est_other_item['item_details'][0]['tax_percentage']).'% &nbsp; &nbsp; '.moneyFormatIndia($total_igst).'&nbsp; &nbsp; ';
									}
									if( $has_tcs_amt == 1) {
										echo "TCS ".(moneyFormatIndia($billing['tcs_tax_amt'])).'% &nbsp; &nbsp; ';
									 } ?>
									</td>
									<td class="alignRight">TOTAL</td>
									<td class="alignRight">Rs.</td>
									<td class="alignRight"><?php echo moneyFormatIndia($totalAmt);?></td>
								</tr>
							
						</table>
					</div>	
				</div>	
			</div>
			<?php }?>
			
		
			<?php if(sizeof($est_other_item['return_details'])>0){?>
					<table id="pp" class="table text-center">
						<!--	<thead> -->
								<tr style="text-transform:uppercase; display:none">
									<td class="table_heading">S.No</td>
									<td class="table_heading">HSN Code</td>
									<td class="table_heading">Description</td>
									<td class="table_heading alignRight">PCS</td>
									<td class="table_heading alignRight">Gwt(g)</td>
									<td class="table_heading alignRight">Nwt(g)</td>
									<td class="table_heading alignRight">MC</td>
									<td class="table_heading alignRight">V.A</td>
									<td class="table_heading alignRight">Amount</td>
								</tr>
								<tr>
									<td><hr class="return_dashed"></td>
								</tr>
							<!--</thead>
							<tbody>-->
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
							$mc=0;
							$wastge_wt=0;
							foreach($est_other_item['return_details'] as $items)
								{
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
								<tr style="display:none">
									<td colspan="9" style="text-transform:uppercase; font-weight: bold">Refer sale  bill no : <?php echo $items['ref_bill_no'].' Dt '.$items['ref_bill_date']?> </td>
								</tr>
								<tr>
								<td style="width: 5%"><?php echo $i;?></td>
								<td style="width: 8%"><?php echo $items['hsn_code'];?></td>
								<td style="width: 20%; font-size:10px;" class='textOverflowHidden'><?php echo $items['product_name'];?></td>
								<td style="width: 5%" class="alignRight"><?php echo $items['piece'];?></td>
								<td style="width: 9%" class="alignRight"><?php echo $items['gross_wt'];?></td>
								<td style="width: 9%" class="alignRight"><?php echo $items['net_wt'];?></td>
								<td style="width: 9%" class="alignRight"><?php echo number_format($mc,2,'.','');?></td>
								<td style="width: 13%" class="alignRight"><?php echo number_format($wastge_wt,3,'.','');?></td>
								<td style="width: 22%" class="alignRight"><?php echo moneyFormatIndia(number_format($items['item_cost'],2,'.',''));?></td>
								</tr>
							<?php $i++;}?>
								<tr>
									<td><hr class="return_dashed"></td>
								</tr>
						<!--</tbody> -->
							<!--<tfoot>-->
								<tr>
									<td>Total</td>
									<td></td>
									<td></td>
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
								<?php if($tot_sales_amt > 0 && $total_return > 0 && $pur_total_amt == 0){?>
								<tr>
									<td colspan="6"></td>
									<td class="alignRight">Net Amount</td>
									<td class="alignRight">Rs.</td>
									<td class="alignRight"><?php echo moneyFormatIndia(number_format(($tot_sales_amt+$billing['handling_charges']) -$total_return,2,'.',''));?></td>
								</tr>
								<?php } ?>
						</table>
			<?php }?>

			<?php if(sizeof($est_other_item['old_matel_details'])>0){?>
			<div  class="row">
				<span style="font-weight: bold">PURCHASE NO : <?php echo $billing['pur_ref_no'];?></span>
				<div class="col-xs-12">
					<div class="table-responsive">

					<table id="pp" class="table text-center">
						<!--	<thead> -->
								<tr style="text-transform:uppercase; display:none">
									<td class="table_heading">S.No</td>
									<td class="table_heading">HSN</td>
									<td class="table_heading">Metal</td>
									<td class="table_heading alignRight">GROSS WT(g)</td>
									<td class="table_heading alignRight">Nwt(g)</td>
									<td class="table_heading alignRight">V.A</td>
									<td class="table_heading alignRight">Rate</td>
									<td class="table_heading alignRight">Amount</td>
								</tr>
								<tr>
									<td><hr class="old_metal_dashed"></td>
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
									$gross_wt+=$items['gross_wt'];
									$net_wt+=($items['net_wt']);
								?>
								
								<tr>
								<td style="width: 5%"><?php echo $i;?></td>
								<td style="width: 8%">71080000</td>
								<td style="width: 25%; font-size:10px;" class='textOverflowHidden'><?php echo ($items['metal_type']==1 ?'Old Gold' :'Old Silver');?></td>
								<td style="width: 9%" class="alignRight"><?php echo $items['gross_wt'];?></td>
								<td style="width: 9%" class="alignRight"><?php echo ($items['net_wt']);?></td>
								<td style="width: 22%" class="alignRight"><?php echo number_format(($items['gross_wt'] - $items['net_wt']),3,'.','') ;?></td>
								<td style="width: 10%" class="alignRight"><?php echo $items['rate_per_gram'];?></td>
								<td style="width: 12%" class="alignRight"><?php echo moneyFormatIndia($items['amount']);?></td>
								</tr>
							<?php $i++;}?>
						<!--</tbody> -->
								<tr>
									<td><hr class="old_metal_dashed"></td>
								</tr>
								<tr>
									<td>Total</td>
									<td></td>
									<td></td>
									<td class="alignRight"><?php echo number_format($gross_wt,3,'.','');?></td>
									<td class="alignRight"><?php echo number_format($net_wt,3,'.','');?></td>
									<td></td>
									<td></td>
									<td class="alignRight"><?php echo moneyFormatIndia(number_format((float)$pur_total_amt,2,'.',''));?></td>
								</tr>
								<tr>
									<td><hr class="old_metal_dashed"></td>
								</tr>
								<?php if($tot_sales_amt+$billing['handling_charges'] > 0){?>
								<tr style="display:none">
									<td colspan="5"></td>
									<td class="alignRight">Sales Amount</td>
									<td class="alignRight">Rs.</td>
									<td class="alignRight"><?php echo moneyFormatIndia(number_format($tot_sales_amt+$billing['handling_charges'],2,'.',''));?></td>
								</tr>
								<?php }?>

								<?php if($pur_total_amt > 0) { ?>
								<tr style="display:none">
									<td colspan="5"></td>
									<td class="alignRight">Purchase Amount</td>
									<td class="alignRight">Rs.</td>
									<td class="alignRight"><?php echo moneyFormatIndia(number_format($pur_total_amt ,2,'.',''));?></td>
								</tr>
								<?php } ?>

								<?php if($tot_sales_amt>0){?>
								<tr>
									<td colspan="5"></td>
									<td class="alignRight">Net Amount</td>
									<td class="alignRight">Rs.</td>
									<td class="alignRight"><?php echo moneyFormatIndia(number_format(($tot_sales_amt+$billing['handling_charges']) -$total_return-$pur_total_amt,2,'.',''));?></td>
								</tr>
								<?php }?>
						</table>
					</div>	
				</div>	
			</div>
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
								<tr style="display:none">
									<td class="table_heading" width="5%;">S.No</td>
									<td class="table_heading" width="25%;">Description</td>
									<td class="table_heading" width="20%;">Design</td>
									<td class="table_heading alignRight" width="5%;">PCS</td>
									<td class="table_heading alignRight" width="15%;">Gwt(g)</td>
								</tr>
								<tr>
									<td><hr class="order_tr_item_dashed"></td>
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
								<td><hr class="order_tr_item_dashed"></td>
							</tr>
							
							<tr>
								<td>TOTAL</td>
								<td></td>
								<td></td>
								<td></td>
								<td class="alignRight"><?php echo number_format($weight,3,'.','');?></td>
							</tr>
							
							<tr>
								<td><hr class="order_tr_item_dashed"></td>
							</tr>
							<?php if($amount>0){?>
							<tr>
									<td colspan="4"><?php echo 'Received with thanks from Mr./Ms.'.$billing['customer_name'].' Towards Order  No : '.$order_no.'';?></td>
									<td colspan="2" class="alignRight"><?php echo 'Rs. '.moneyFormatIndia($amount);?></td>
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
						<!--	<thead> -->
								<tr style="display:none">
									<th colspan="5">Description</th>
									<th>Amount</th>
								</tr>
							<!--</thead>
							<tbody>-->
								<tr>
									<td colspan="5"><?php echo 'Received with thanks from Mr./Ms.'.$billing['customer_name'].' Towards Credit Bill No : '.$billing['ref_bill_no'].'/ Ref No : '.$billing['ref_bill_id'];?></td>
									<td class="alignRight" colspan="5"><?php echo $billing['due_amount'];?></td>
								</tr>
						<!--</tbody> -->
								<tr>
									<td colspan="7"><hr style="border-bottom:0.5pt;"></td>
								</tr>
						</table><br>	
					</div>	
				</div>	
			</div><br>
			<?php }?>

		
				<div  class="row">
						<?php 
							if($billing['bill_type']!=8)
							{
								$due_amount=number_format(($billing['tot_bill_amount']-$billing['tot_amt_received']),2,'.','');
							}
							if(sizeof($payment['pay_details'])>0){
							$cash_amt=0;
							$card_amt=0;
							$net_banking_amt=0;
							$due_amount=0;
							$gift_amount=0;
							$chit_amount=0;
							$total_amt=0;
							$adv_adj=0;
							$imps_amt=0;
							$rtgs_amt=0;
							$upi_amt=0;
							if($billing['bill_type']==8)
							{
								$due_amount=number_format(($billing['tot_bill_amount']-($billing['tot_adv_received']+$billing['tot_paid_amt']+$pur_total_amt)),2,'.','');
							}else{
								if($billing['is_credit']==1)
								{
									$due_amount=number_format(($billing['tot_bill_amount']-$billing['tot_amt_received']),2,'.','');
								}
								else{
									$due_amount=number_format(($billing['tot_bill_amount']-$billing['tot_amt_received']),2,'.','');
								}
							}
							//echo "<pre>"; print_r($payment); echo "</pre>";exit;
							foreach($payment['pay_details'] as $items)
								{
									$total_amt+=$items['payment_amount'];
									
									if($items['payment_mode']=='Cash')
									{
										$cash_amt+=$items['payment_amount'];
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
						
					<div class="col-md-12" style="font-weight: bold">
							<table id="pp" class="table text-center" style="width:100%;">
								<tr>
									<td>CASH</td>

									<?php if($card_amt>0) { ?>
									<?php foreach($payment['pay_details'] as $cardItem) {
									if($cardItem['payment_mode']=='DC' || $cardItem['payment_mode']=='CC') { ?>
									<td>CARD(<?php echo $cardItem['card_no'] ?>)</td>
									<?php } } }  ?>
									
									<?php if($rtgs_amt > 0) { ?>
									<td>RTGS</td>
									<?php } ?>
									<?php if($imps_amt > 0) { ?>
									<td>IMPS</td>
									<?php } ?>
									<?php if($upi_amt > 0) { ?>
									<td>UPI</td>
									<?php } ?>

									<?php if($due_amount>0){?>
									<td>DUE AMOUNT</td>
									<?php }?>
									
									<?php if($adv_adj>0){?>
									<td>ADVANCE ADJ</td>
									<?php }?>
									<?php if($chit_adj>0){?>
									<td>CHIT ADJ</td>
									<?php }?>
									
									<?php if($bouns_amt>0){?>
									<td>BONUS</td>
									<?php }?>
									
									<?php if($ord_adj_amt>0 || $billing['adv_adj_amt']>0){?>
									<td>ADV ADJ</td>
									<?php }?>
									<?php if($gift_amount>0){?>
									<td>GIFT UTILIZED</td>
									<?php }?>
									
									<?php if($billing['credit_disc_amt']>0){?>
									<td>DISCOUNT</td>
									<?php }?>

									<?php if($bill_discount>0){?>
									<td>DISCOUNT</td>
									<?php }?>
									
									<td style="display:none">ROUND OFF</td>
									<td>TOTAL</td>
								</tr>
								<tbody>
									<tr>
										<td><?php echo moneyFormatIndia(number_format($cash_amt,2,'.',''));?></td>

										<?php if($card_amt>0){?>
											<?php foreach($payment['pay_details'] as $cardItem) {
											if($cardItem['payment_mode']=='DC' || $cardItem['payment_mode']=='CC') { ?>
											<td><?php echo moneyFormatIndia(number_format($cardItem['payment_amount'],2,'.',''));?></td>
										<?php } } }?>
										
										<?php if($rtgs_amt > 0) { ?>
										<td><?php echo moneyFormatIndia(number_format($rtgs_amt,2,'.',''));?></td>
										<?php } ?>
										<?php if($imps_amt > 0) { ?>
										<td><?php echo moneyFormatIndia(number_format($imps_amt,2,'.',''));?></td>
										<?php } ?>
										<?php if($upi_amt > 0) { ?>
										<td><?php echo moneyFormatIndia(number_format($upi_amt,2,'.',''));?></td>
										<?php } ?>
									
										
										<?php if($due_amount>0){?>
										<td><?php echo moneyFormatIndia(number_format($due_amount,2,'.',''));?></td>
										<?php }?>
										
										
										
										<?php if($adv_adj>0){?>
										<td><?php echo moneyFormatIndia(number_format($adv_adj,2,'.',''));?></td>
										<?php }?>
										
										<?php if($chit_adj>0){?>
										<td><?php echo moneyFormatIndia(number_format($chit_adj,2,'.',''));?></td>
										<?php }?>
										
										<?php if($bouns_amt>0){?>
										<td><?php echo moneyFormatIndia(number_format($bouns_amt,2,'.',''));?></td>
										<?php }?>
										
										<?php if($ord_adj_amt>0 || $billing['adv_adj_amt']>0){?>
										<td><?php echo moneyFormatIndia(number_format($ord_adj_amt+$billing['adv_adj_amt'],2,'.',''));?></td>
										<?php }?>
										
										<?php if($gift_amount>0){?>
										<td><?php echo moneyFormatIndia(number_format($gift_amount,2,'.',''));?></td>
										<?php }?>
										
										<?php if($billing['credit_disc_amt']>0){?>
										<td><?php echo moneyFormatIndia(number_format($billing['credit_disc_amt'],2,'.',''));?></td>
										<?php }?>

										<?php if($bill_discount>0){?>
										<td><?php echo moneyFormatIndia(number_format($bill_discount,2,'.',''));?></td>
										<?php }?>
									
										<td style="display:none"><?php echo moneyFormatIndia(number_format($billing['round_off_amt'],2,'.',''));?></td>
										<td> 
										<?php if($billing['tot_amt_received']==0 && $due_amount>0) {  
										$amount_total = number_format(($billing['tot_bill_amount']),2,'.','');
										$amount_in_words = 	$billing['tot_bill_amount'];
											} else { 
										$amount_total = moneyFormatIndia(number_format(($total_amt+$chit_adj+$bouns_amt+$ord_adj_amt+$gift_amount+$billing['adv_adj_amt']+$billing['credit_disc_amt']+($due_amount>0 ? $due_amount:0)),2,'.',''));
										$amount_in_words = 	$total_amt+$chit_adj+$bouns_amt+$ord_adj_amt+$gift_amount+$billing['adv_adj_amt']+$billing['credit_disc_amt']+($due_amount>0 ? $due_amount:0);
										}
										echo $amount_total;?></td>
									</tr>
								</tbody>
							</table>
					</div>
				</div>
				
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
				
					<?php if(sizeof($est_other_item['order_adj'])>0){?>
				<div class="col-xs-6">
					<div class="table-responsive">
						<table id="pp" class="table text-center" style="width:100%;">
							<tr>
								<td>Date</td>
								<td class="alignRight">Amount</td>
								<td class="alignRight">Rate</td>
								<td class="alignRight">Weight</td>
							</tr>
							<tr>
								<td><hr class="item_dashed" style="width:500% !important;"></td>
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
			<div width= "100%" class="margin-top: -30px">
				<div width= "80%" style="font-weight: bold; display:inline">
					<div style="display:inline">
						EMP : <?php echo $billing['emp_name']; ?>
					</div>
					<div style="display:inline">
						Bill Time : <?php echo $billing['bill_time']; ?>
					</div>
					<div style="display:inline">
						Bill Date : <?php echo $billing['bill_date']; ?>
					</div>
				</div>
				<div width= "20%" style="font-weight: bold; display:inline;float: right">
				<?php
					echo $metal_type == 1 ? 'Gold Rate' : 'Silver Rate';
					echo "&nbsp; : &nbsp";
					echo $metal_type == 1 ? $gold_metal_rate.'/'.'Gm' : $silver_metal_rate.'/'.'Gm'; ?>
				</div>
		
			</div>
</div>
 </span>          
</body></html>
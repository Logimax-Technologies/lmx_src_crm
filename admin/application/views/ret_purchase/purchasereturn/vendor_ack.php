<html>

<head>
	<meta charset="utf-8">

	<title>Purchase Return - <?php echo $order['pur_no']; ?> </title>

	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/purchase_return.css">

	<style>
		.addr_labels {
			display: inline-block;
			width: 30%;
			padding-bottom: 5px;
		}

		.addr_values {
			display: inline-block;
			padding-left: -5px;
		}

		.addr_brch_labels {
			display: inline-block;
			vertical-align: top;
			width: 40%;
			text-align: right;
		}

		.addr_brch_values {
			display: inline-block;
			vertical-align: top;
			width: 40%;
			text-align: left;
		}
	</style>
</head>

<body>
	<?php
	function moneyFormatIndia($num)
	{
		return preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $num);
	}
	?>
	<div class="PDFReceipt">
		<div class="heading">
			<div class="company_name"><?php echo strtoupper($comp_details['company_name']); ?></div>
			<div><?php echo strtoupper($comp_details['address1']) ?> , <?php echo strtoupper($comp_details['city']) ?></div>
			<div><?php echo strtoupper($comp_details['address2']) ?></div>
			<?php ($comp_details['email'] != '' ? '<div>Email : ' . $comp_details['email'] . ' :</div>' : '') ?>
			<?php ($comp_details['gst_number'] != '' ? '<div>GST No ' . $comp_details['gst_number'] . ' :</div>' : '') ?>
			<div><?php echo $comp_details['state'] ?></div>
		</div><br>

		<div style="width: 100%; text-transform:uppercase;height:140px;">
			<div style="display: inline-block; width: 35%;">
				<label><?php echo '<div class="addr_labels">Name</div><div class="addr_values">:&nbsp;&nbsp;' . 'Mr./Ms.' . $issue['karigar_name'] . "</div>"; ?></label><br>
				<label><?php echo '<div class="addr_labels">Mobile</div><div class="addr_values">:&nbsp;&nbsp;' . $issue['supplier_mobile'] . "</div>"; ?></label><br>
				<label><?php echo ($issue['address1'] != '' ? '<div class="addr_labels">Address</div><div class="addr_values">:&nbsp;&nbsp;' . strtoupper($issue['address1']) . ',' . "</div><br>" : ''); ?></label>
				<label><?php echo ($issue['address2'] != '' ? '<div class="addr_labels"></div><div class="addr_values">&nbsp;&nbsp;&nbsp;' . strtoupper($issue['address2']) . ',' . "</div><br>" : ''); ?></label>
				<label><?php echo ($issue['city_name'] != '' ? '<div class="addr_labels">city</div><div class="addr_values">:&nbsp;&nbsp;' . strtoupper($issue['city_name']) . ($issue['pincode'] != '' ? ' - ' . $issue['pincode'] . '.' : '') . "</div><br>" : ''); ?></label>
				<label><?php echo ($issue['state_name'] != '' ? '<div class="addr_labels">State</div><div class="addr_values">:&nbsp;&nbsp;' . strtoupper($issue['state_name'] . '-' . $issue['state_name']) . ',' . "</div><br>" : ''); ?></label>
				<label><?php echo ($issue['country_name'] != '' ? '<div class="addr_labels">Country</div><div class="addr_values">:&nbsp;&nbsp;' . strtoupper($issue['country_name']) . "</div><br>" : ''); ?></label>
				<label><?php echo (isset($issue['pan_no']) && $issue['pan_no'] != '' ? '<div class="addr_labels">PAN</div><div class="addr_values">:&nbsp;&nbsp;' . strtoupper($issue['pan_no']) . "</div><br>" : ''); ?></label>
				<label><?php echo (isset($issue['gst_number']) && $issue['gst_number'] != '' ? '<div class="addr_labels">GST IN</div><div class="addr_values">:&nbsp;&nbsp;' . strtoupper($issue['gst_number']) . "</div><br>" : ''); ?></label>
			</div>


			<div style="display: inline-block; text-align: center !important; width: 29%; "><?php echo $issue['purchase_type'];?></div>

			<div style="width: 35%; display: inline-block;vertical-align: top;text-align: right">

				<label><?php echo ($issue['date_add'] != '' ? '<div class="addr_brch_labels">Receipt Date &nbsp;&nbsp;</div><div class="addr_brch_values">&nbsp;&nbsp;:&nbsp;&nbsp;' . $issue['date_add'] . "</div><br>" : ''); ?></label><br>
				<label><?php echo ($issue['pur_ret_ref_no'] != '' ? '<div class="addr_brch_labels">Ref No &nbsp;&nbsp;</div><div class="addr_brch_values">&nbsp;&nbsp;:&nbsp;&nbsp;' . $issue['pur_ret_ref_no'] . "</div><br>" : ''); ?></label>


			</div>
		</div><br>
		<div class="receipt_details">
			<table class="job_receipt_table">
				<thead>
					<tr>
						<th class="alignRight">S.No</th>
						<th style="text-align: center;white-space: nowrap;">DESCRIPTION OF GOODS</th>
						<th class="alignRight" style="width:10%">HSN</th>
						<th class="alignRight" style="width:10%">PURITY</th>
						<th class="alignRight" style="width:10%">QTY</th>
						<th class="alignRight" style="width:10%">GWT</th>
						<th class="alignRight" style="width:10%">NWT</th>
						<th class="alignRight" style="width:10%">TOUCH</th>
						<th class="alignRight" style="width:10%">V.A(%)</th>
						<th class="alignRight" style="width:6%;">PURE</th>
						<th class="alignRight" style="width:10%">RATE</th>
						<th class="alignRight" style="width:10%">AMOUNT(RS)</th>

					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$total_taxable_amt = 0;
					$cgst_cost = 0;
					$sgst_cost = 0;
					$cgst_iost = 0;
					$total_item_cost = 0;
					$total_charges = 0;
					$pur_purity = '';
					$stone_amount= 0;
					$tot_pcs =0;
					$tot_gwt =0;
					$tot_nwt =0;
					$tot_pur_wt =0;
					$tot_pur_rate =0;


					if (sizeof($item_details['po_details']) > 0)
					{
						foreach($item_details['po_details'] as $po)
						{
							$pur_purity = $po['purity'];
						}
					}

					if(sizeof($item_details['tag_details']) > 0)
					{
						foreach($item_details['tag_details'] as $tag)
						{
							$pur_purity = $tag['purity'];
						}
					}

					foreach ($grn_details['gst_details'] as $gst) {
						$cgst_cost += $gst['cgst_cost'];
						$sgst_cost += $gst['sgst_cost'];
						$igst_cost += $gst['igst_cost'];
					}
					foreach ($grn_details['charge_details'] as $charge) {
						$total_charges += $charge['grn_charge_value'];
					}
					foreach ($receipt_details as $row) {
						$total_taxable_amt += $row['pur_ret_item_cost'] - $row['pur_ret_tax_value'];
						$total_item_cost += $row['pur_ret_item_cost'];
						$purity = $row['pur_ret_purchase_touch'];
						$tot_pcs +=$row['pur_ret_pcs'];
						$tot_gwt +=$row['pur_ret_gwt'];
						$tot_nwt +=$row['pur_ret_nwt'];
						$tot_pur_wt +=$row['pur_ret_pur_wt'];
						$tot_pur_rate +=$row['pur_ret_rate'];
						foreach ($row['stn_details'] as $stn_details) {
							$stone_amount += $stn_details['stone_amount'];
						}

					?>
						<tr>
							<td class="alignRight" style="text-align: center;border-left: none;"><?php echo $i; ?></td>
							<td class="alignRight" style="text-align: left">&nbsp;<?php echo $row['product_name'] ?></td>
							<td class="alignRight"><?php echo ($row['hsn_code']); ?></td>
							<td class="alignRight"><?php echo number_format($pur_purity,3,'.',''); ?></td>
							<td class="alignRight"><?php echo ($row['pur_ret_pcs']); ?></td>
							<td class="alignRight"><?php echo ($row['pur_ret_gwt']); ?></td>
							<td class="alignRight"><?php echo ($row['pur_ret_nwt']); ?></td>
							<td class="alignRight"><?php echo $row['pur_ret_purchase_touch'] ?></td>
							<td class="alignRight"><?php echo  number_format($row['pur_ret_wastage'], 3, '.', '') ?></td>
							<td class="alignRight"><?php echo $row['pur_ret_pur_wt']; ?></td>
							<td class="alignRight"><?php echo  $row['pur_ret_rate'] ?></td>
							<td class="alignRight"><?php echo moneyFormatIndia(number_format($row['pur_ret_item_cost'] - $row['pur_ret_tax_value'], 2, '.', '')) ?> </td>
						</tr>


						<?php
						foreach($row['stn_details'] as $sval)
						{?>  
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td><?php echo $sval['stone_name'];?></td>
								<td class="alignRight"><?php echo $sval['stone_wt'].'/'.$sval['uom_short_code'];?></td>
								<td class="alignRight">Rs.<?php echo $sval['stone_rate'].'/'.$sval['uom_short_code'];?></td>
								<td class="alignRight">Rs.<?php echo moneyFormatIndia(number_format($sval['stone_amount'],2,'.',''))?></td>
								<td class="alignRight"></td>
								<td class="alignRight"></td>
								<td></td>  
								
							</tr>
						<?php }?>

						<?php if($stone_amount>0){?>
							<tr style="font-weight:bold;">	
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td class="alignRight"></td>
								<td class="alignRight">TOTAL</td>
								<td class="alignRight">Rs.<?php echo moneyFormatIndia(number_format($stone_amount,2,'.',''))?></td>
								<td></td>
								<td class="alignRight"></td>
								<td class="alignRight"></td>
							</tr>
							<?php }?>
					<?php $i++;
					}

					?>

					<tr>
						<td style="border-top: 1px solid #ccc;"></td>
						<td style="border-top: 1px solid #ccc;"></td>
						<td style="border-top: 1px solid #ccc;"></td>
						<td style="border-top: 1px solid #ccc;"></td>
						<td style="border-top: 1px solid #ccc;"></td>
						<td style="border-top: 1px solid #ccc;"></td>
						<td style="border-top: 1px solid #ccc;"></td>
						<td style="border-top: 1px solid #ccc;"></td>
						<td style="border-top: 1px solid #ccc;"></td>
						<td style="border-top: 1px solid #ccc;"></td>
						<td style="border-top: 1px solid #ccc;"></td>
						<td style="border-top: 1px solid #ccc;"></td>


					</tr>


					<tr style="font-weight:bold;">
							<td class="rateborders" ></td>
							<td class="rateborders alignCenter"> TOTAL</td>
							<td class="rateborders"></td>
							<td class="rateborders"></td>
							<td class="rateborders alignRight" ><?php echo $tot_pcs ?></td>
							<td class="rateborders alignRight" ><?php echo moneyFormatIndia(number_format((float)$tot_gwt, 3, '.', '')); ?></td>
							<td class="rateborders alignRight" ><?php echo moneyFormatIndia(number_format((float)$tot_nwt, 3, '.', '')); ?></td>
							<td class="rateborders alignRight" ></td>
							<td class="rateborders alignRight" ></td>
							<td class="rateborders alignRight" ><?php echo moneyFormatIndia(number_format((float)$tot_pur_wt, 3, '.', '')); ?></td>
							<td class="rateborders alignRight" ><?php echo moneyFormatIndia(number_format((float)$tot_pur_rate, 2, '.', '')); ?></td>
							<td class="rateborders alignRight" ></td>
						</tr>	
					</tbody>
					<tfoot>




					<tr>
						
						<td class="rateborders alignRight" colspan="10"><strong>SUB TOTAL</strong></td>
						<td class="alignRight border-top: 1px solid #ccc; alignRight"  colspan="2"style="font-weight:bold"><?php echo moneyFormatIndia($total_taxable_amt + $total_charges, 2, ".", "") ?></td>

					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td> 
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td  style="border-top: 1px solid #ccc;"></td>
						<td style="border-top: 1px solid #ccc;"></td>
						<td style="border-top: 1px solid #ccc;"></td>
						<td style="border-top: 1px solid #ccc;"></td>


					</tr>

					</tr>
					<?php
					foreach ($gst_details as $gst) { ?>
						<?php
						if ($gst['cgst_cost'] > 0) { ?>
							<tr>
								<td class="rateborders" style="border-left: none"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td style="white-space: nowrap;" style="border:1px solid red">CGST(<?php echo ($gst['pur_ret_tax_percent'] / 2) ?> %)</td>
								<td></td>
								<td class="alignRight"><?php echo moneyFormatIndia(number_format($gst['cgst_cost'], 2, ".", "")) ?></td>
							</tr>

							<tr>
								<td class="rateborders" style="border-left: none"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td style="white-space: nowrap;" colspan="2">SGST(<?php echo ($gst['pur_ret_tax_percent'] / 2) ?> %)</td>
								<td class="alignRight"><?php echo moneyFormatIndia(number_format($gst['sgst_cost'], 2, ".", "")) ?></td>
							</tr>

						<?php } else if ($gst['igst_cost'] > 0) { ?>
							<tr>
								<td class="rateborders" style="border-left: none"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td style="white-space: nowrap;"colspan="2">IGST(<?php echo ($gst['pur_ret_tax_percent']) ?> %)</td>
								<td class="alignRight"><?php echo moneyFormatIndia(number_format($gst['igst_cost'], 2, ".", "")) ?></td>
							</tr>
						<?php } ?>
					<?php } ?>

					<?php
					if ($issue['pur_ret_tcs_percent'] > 0) { ?>
						<tr>
							<td class="rateborders" style="border-left: none"></td>
							<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
							<td class="rateborders">TCS(<?php echo ($issue['pur_ret_tcs_percent']) ?> %)</td>
							<td class="rateborders"><?php echo moneyFormatIndia($issue['pur_ret_tcs_value'], 2, ".", "") ?></td>
						</tr>
					<?php }
					?>

					<?php
					if ($issue['pur_ret_tds_percent'] > 0) { ?>
						<tr>
							<td class="rateborders" style="border-left: none"></td>
							<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
							<td class="rateborders">TDS(<?php echo ($issue['pur_ret_tds_percent']) ?> %)</td>
							<td class="rateborders"><?php echo moneyFormatIndia($issue['pur_ret_tds_value'], 2, ".", "") ?></td>
						</tr>
					<?php }
					?>


					<?php foreach ($charge_details as $charge) { ?>
						<tr>
							<td class="desc" style="text-align: center;border-left: none;"></td>
							<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
							<td class="desc"><?php echo $charge['name_charge'] ?></td>
							<td class="desc"><?php echo moneyFormatIndia(number_format($charge['pur_ret_charge_value'])) ?> </td>
						</tr>
					<?php $i++;
					}
					?>


					<?php
					foreach ($charge_gst_details as $gst) { ?>
						<?php
						if ($gst['cgst_cost'] > 0) { ?>
							<tr>
								<td class="rateborders" style="border-left: none"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders">CGST(<?php echo ($gst['pur_ret_charge_tax'] / 2) ?> %)</td>
								<td class="rateborders"><?php echo moneyFormatIndia(number_format($gst['cgst_cost'], 2, ".", "")) ?></td>
							</tr>

							<tr>
								<td class="rateborders" style="border-left: none"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders">SGST(<?php echo ($gst['pur_ret_charge_tax'] / 2) ?> %)</td>
								<td class="rateborders"><?php echo moneyFormatIndia(number_format($gst['sgst_cost'], 2, ".", "")) ?></td>
							</tr>

						<?php } else if ($gst['igst_cost'] > 0) { ?>
							<tr>
								<td class="rateborders" style="border-left: none"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders">IGST(<?php echo ($gst['pur_ret_charge_tax']) ?> %)</td>
								<td class="rateborders"><?php echo moneyFormatIndia(number_format($gst['igst_cost'], 2, ".", "")) ?></td>
							</tr>
						<?php } ?>
					<?php } ?>


					<?php
					if ($issue['pur_ret_other_charges_tds_percent'] > 0) { ?>
						<tr>
							<td class="rateborders" style="border-left: none"></td>
							<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
							<td class="rateborders">CHARGES TDS(<?php echo ($issue['pur_ret_other_charges_tds_percent']) ?> %)</td>
							<td class="rateborders"><?php echo moneyFormatIndia(round($issue['pur_ret_other_charges_tds_value'], 2), 2, ".", "") ?></td>
						</tr>
					<?php }
					?>


					<?php
					if ($issue['pur_ret_discount'] != 0) { ?>
						<tr>
							<td class="rateborders" style="border-left: none"></td>
							<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
								<td class="rateborders"></td>
							<td class="rateborders">DISCOUNT</td>
							<td class="rateborders"><?php echo moneyFormatIndia(round($issue['pur_ret_discount'], 2), 2, ".", "") ?></td>
						</tr>
					<?php }
					?>


					<?php
					if ($issue['pur_ret_round_off'] != 0) { ?>
						<tr>
							<td class="rateborders" style="border-left: none"></td>
							<td class="rateborders"></td>
							<td class="rateborders"></td>
							<td class="rateborders"></td>
							<td class="rateborders alignRight"  colspan="6">ROUND OFF</td>
							<td></td>
							<td class="rateborders alignRight"><?php echo moneyFormatIndia(round($issue['pur_ret_round_off'], 2), 2, ".", "") ?></td>
						</tr>
					<?php }
					?>


					<?php $height = "";
					if ($i == 2)
						$height = "165px";
					else if ($i == 3)
						$height = "140px";
					else if ($i == 4)
						$height = "105px";
					else if ($i == 5)
						$height = "80px";
					else if ($i == 6)
						$height = "55px";
					else if ($i == 7)
						$height = "30px";
					?>


					<?php if ($i < 8) { ?>
						<!-- <tr style="height: <?php echo $height ?>">			
						<td class="rateborders" style="border-left: none"></td>
						<td class="rateborders"></td>
						<td class="rateborders"></td>
						<td class="rateborders"></td>
						<td class="rateborders"></td>
						<td class="rateborders"></td>
					</tr> -->
					<?php } ?>

					<?php $grandtotal = $gst['cgst_cost'] + $gst['sgst_cost'] + $gst['igst_cost'] + $issue['return_total_cost'] + $issue['pur_ret_tcs_value'] + $issue['pur_ret_tds_value'] + $charge['pur_ret_charge_value'] + $issue['pur_ret_other_charges_tds_value'] ?>


					<tr>
						<td class="rateborders"></td>
						<td class="rateborders"></td>
						<td class="rateborders"></td>
						<td class="rateborders"></td>
						<td class="rateborders"></td>
						<td class="rateborders"></td>
						<td class="rateborders"></td>
						<td class="rateborders"></td>
						<td class="rateborders"></td>
						<td class="rateborders " style="border-top: 1px solid #ccc;white-space: nowrap;"><b>FINAL TOTAL</b></td>
						<td class="rateborders" style="border-top: 1px solid #ccc;"><b><?php echo $total_qty; ?></b></td>
						<td class="alignRight" style="border-top: 1px solid #ccc;"><b><?php echo moneyFormatIndia(number_format(round($total_item_cost, 3), 2, ".", ""),2,'.','') ?></b></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td> 
						<td></td>
						<td></td>
						<td></td>
						<td ></td>
						<td  style="border-top: 1px solid #ccc;"></td>
						<td style="border-top: 1px solid #ccc;"></td>
						<td style="border-top: 1px solid #ccc;"></td>
						<td style="border-top: 1px solid #ccc;"></td>


					</tr>
				<!-- </tbody>
				<tfoot> -->


				</tfoot>
			</table>
		</div>

		<div class="footer">
			<div class="footer_left" style="width:25%;">
				<label>Audited By</label>
			</div>
			<div class="footer_left" style="width:20%;">
				<label>Party Sign</label>
			</div>
			<div class="footer_left" style="width:25%;">
				<label>Manager Sign</label>
			</div>
			<div class="footer_left" style="width:25%;">
				<label>Operator </label></br>
				<?php echo $issue['emp_name'] . '-' . date("d-m-y h:i:sa"); ?>
			</div>
		</div>

	</div>

	<script type="text/javascript">
		setTimeout(function() {

			window.print();

		}, 1000);
	</script>
</body>

</html>
<html>

<head>
	<meta charset="utf-8">
	<title>Payment Report</title>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/billing_receipt.css">
	<!--	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/receipt_temp.css">-->
	<style>
		.bill_invoice {
			position: absolute;
			top: 50;
			right: 0;
			width: 50%;
			transform: translate(40%, 0);
			white-space: nowrap;
		}

		.cus_sign {
			width: 25%;
			display: inline-block;
		}

		.cashier_sign {
			width: 23%;
			text-align: right;
			display: inline-block;
		}

		.auth_sign {
			width: 50%;
			text-align: center;
			height: 10px;
			display: inline-block;
		}

		.footer_signature {
			position: absolute;
			bottom: 0;
			left: 0;
			width: 100%;
			height: 50px;
		}

		.rept_details {
			width: 50%;
		}

		.head {
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

		.duplicate_copy #pp th,
		.duplicate_copy #pp td {
			font-size: 9px !important;
		}

		.return_dashed {
			width: 700px !important;
		}

		.old_metal_dashed {
			width: 700px !important;
		}

		.stones,
		.charges {
			font-style: italic;
		}

		.stones .stoneData,
		.charges .chargeData {
			font-size: 10px !important;
		}

		.addr_labels {
			display: inline-block;
			width: 30%;
		}

		.rate_labels {
			display: inline-block;
			width: 30%;
		}

		.addr_values {
			display: inline-block;
			padding-left: -5px;
		}

		.addr_brch_labels {
			display: inline-block;
			width: 30%;
		}

		.addr_brch_values {
			display: inline-block;
			padding-left: 2px;
		}

		@page {
			margin-top: 33mm;
			margin-bottom: 33mm;
		}
	</style>
</head>

<body style="margin-top:23px !important;">
	<span class="PDFReceipt">
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
				<!-- <div style="display: inline-block; width: 50%;margin-top:20px;"> -->
				<!-- <label><?php echo '<div class="addr_labels">Name</div><div class="addr_values">:&nbsp;&nbsp;' . 'Mr./Ms.' . $issue['name'] . "</div>"; ?></label><br> -->
				<!-- <label><?php echo '<div class="addr_labels">Mobile</div><div class="addr_values">:&nbsp;&nbsp;' . $issue['mobile'] . "</div>"; ?></label><br> -->
				<!-- <label><?php echo ($issue['address1'] != '' ? '<div class="addr_labels">Address</div><div style="margin-left:0px;"class="addr_values">:&nbsp;&nbsp;' . strtoupper($issue['address1']) . ',' . "</div><br>" : ''); ?></label> -->
				<!-- <label><?php echo ($issue['address2'] != '' ? '<div class="addr_labels"></div><div class="addr_values">&nbsp;&nbsp;&nbsp;' . strtoupper($issue['address2']) . ',' . "</div><br>" : ''); ?></label> -->
				<!-- <label><?php echo ($issue['address3'] != '' ? '<div class="addr_labels"></div><div class="addr_values">&nbsp;&nbsp;&nbsp;' . strtoupper($issue['address3']) . ',' . "</div><br>" : ''); ?></label> -->
				<!-- <label><?php echo ($issue['village_name'] != '' ? '<div class="addr_labels"></div><div class="addr_values">&nbsp;&nbsp;&nbsp;' . strtoupper($issue['village_name']) . ',' . "</div><br>" : ''); ?></label> -->
				<!-- <label><?php echo ($issue['city_name'] != '' ? '<div class="addr_labels">Place</div><div class="addr_values">:&nbsp;&nbsp;' . strtoupper($issue['city_name']) . ($issue['pincode'] != '' ? ' - ' . $issue['pincode'] . '.' : '') . "</div><br>" : ''); ?></label> -->
				<!-- <label><?php echo ($issue['cus_state'] != '' ? '<div class="addr_labels">State</div><div class="addr_values">:&nbsp;&nbsp;' . strtoupper($issue['cus_state']) . ',' . "</div><br>" : ''); ?></label> -->
				<!-- <label><?php echo ($issue['cus_country'] != '' ? '<div class="addr_labels">Country</div><div class="addr_values">:&nbsp;&nbsp;' . strtoupper($issue['cus_country']) . "</div><br>" : ''); ?></label> -->
				<!-- <label><?php echo (isset($issue['pan_no']) && $issue['pan_no'] != '' ? '<div class="addr_labels">PAN</div><div class="addr_values">:&nbsp;&nbsp;' . strtoupper($issue['pan_no']) . "</div><br>" : ''); ?></label> -->
				<!-- <label><?php echo (isset($issue['gst_number']) && $issue['gst_number'] != '' ? '<div class="addr_labels">GST IN</div><div class="addr_values">:&nbsp;&nbsp;' . strtoupper($issue['gst_number']) . "</div><br>" : ''); ?></label> -->
				<!-- </div> -->
				<div id="headertable" style="width:50%;vertical-align:top;margin-top:-14px;">
					<table style="width:100%;line-height:-6px !important">
						<tr>
							<td class="line_height" style="width:15%">NAME: </td>
							<td class="line_height" style="width:85%"><?php echo $issue['name']; ?></td>
						</tr>
						<tr>
							<td class="line_height" style="width:15%">MOBILE: </td>
							<td class="line_height" style="width:85%"><?php echo $issue['mobile']; ?></td>
						</tr>
						<?php if ($issue['address1'] != '') { ?>
							<tr>
								<td class="line_height" style="width:15%;vertical-align:top"><?php echo ($issue['address1'] != '' ? 'ADDRESS:' : ''); ?> </td>
								<td class="line_height" style="width:85%"><?php echo ($issue['address1'] != '' ? strtoupper($issue['address1']) . ',' : ''); ?></td>
							</tr>
						<?php } ?>
						<?php $issue['address2'] != '' ?>
						<tr>
							<td class="line_height" style="width:15%"></td>
							<td class="line_height" style="width:85%"><?php echo ($issue['address2'] != '' ? strtoupper($issue['address2']) . ',' : ''); ?></td>
						</tr>
						<?php ?>
						<?php $issue['address3'] != '' ?>
						<tr>
							<td class="line_height" style="width:15%"></td>
							<td class="line_height" style="width:85%"><?php echo ($issue['address3'] != '' ? strtoupper($issue['address3']) . ',' : ''); ?></td>
						</tr>
						<?php ?>
						<?php if ($issue['village_name'] != '') { ?>
							<tr>
								<td class="line_height" style="width:15%"> </td>
								<td class="line_height" style="width:85%"><?php echo ($issue['village_name'] != '' ? strtoupper($issue['village_name']) . ',' : ''); ?></td>
							</tr>
						<?php } ?>
						<?php if ($issue['city_name'] != '') { ?>
							<tr>
								<td class="line_height" style="width:15%"><?php echo ($issue['city_name'] != '' ? 'CITY:' : ''); ?> </td>
								<td class="line_height" style="width:85%"><?php echo ($issue['city_name'] != '' ? strtoupper($issue['city_name'])  . ($issue['pincode'] != '' ? ' - ' . $issue['pincode'] . '.' : '') : ''); ?></td>
							</tr>
						<?php } ?>
						<?php if ($issue['cus_state'] != '') { ?>
							<tr>
								<td class="line_height" style="width:15%"><?php echo ($issue['cus_state'] != '' ? 'STATE:' : ''); ?> </td>
								<td class="line_height" style="width:85%"><?php echo ($issue['cus_state'] != '' ? strtoupper($issue['cus_state']) . ',' . ($issue['gst_number'] != '' ? ' &nbsp;&nbsp; GST IN: &nbsp;&nbsp;' . strtoupper($issue['gst_number']) . ',' : '') : ''); ?></td>
							</tr>
						<?php } ?>
						<?php if ($issue['cus_country'] != '') { ?>
							<tr>
								<td class="line_height" style="width:15%">COUNTRY: </td>
								<td class="line_height" style="width:85%"><?php echo ($issue['cus_country'] != '' ? strtoupper($issue['cus_country']) . ',' : ''); ?></td>
							</tr>
						<?php } ?>
						<?php if ($issue['pan_no'] != '') { ?>
							<tr>
								<td class="line_height" style="width:15%">PAN: </td>
								<td class="line_height" style="width:85%"><?php echo ($issue['pan_no'] != '' ? strtoupper($issue['pan_no']) . ',' : ''); ?></td>
							</tr>
						<?php } ?>
						<?php if ($issue['gst_number'] != '') { ?>
							<tr>
								<td class="line_height" style="width:15%">GST NO: </td>
								<td class="line_height" style="width:85%"><?php echo ($issue['gst_number'] != '' ? strtoupper($issue['gst_number']) . ',' : ''); ?></td>
							</tr>
						<?php } ?>
					</table>
				</div>
				<!-- <div class="textOverflowHidden" style="width: 49%; text-align: right !important; display: inline-block; vertical-align: top;">
					<div style="text-align: left !important;width: 100%; display: inline-block;">
						<label><?php echo ($comp_details['name'] != '' ? '<div class="addr_brch_labels">Branch</div><div class="addr_brch_values">:&nbsp;&nbsp;' . strtoupper($comp_details['name']) . ',' . "</div><br>" : ''); ?></label>
						<label><?php echo ($comp_details['address1'] != '' ? '<div class="addr_brch_labels">Address</div><div class="addr_brch_values" >:&nbsp;&nbsp;' . strtoupper($comp_details['address1']) . ',' . "</div><br>" : ''); ?></label>
						<label><?php echo ($comp_details['address2'] != '' ? '<div class="addr_brch_labels"></div><div class="addr_brch_values">&nbsp;&nbsp;&nbsp;' . strtoupper($comp_details['address2']) . ',' . "</div><br>" : ''); ?></label>
						<label><?php echo ($comp_details['city'] != '' ? '<div class="addr_brch_labels">city</div><div class="addr_brch_values">:&nbsp;&nbsp;' . strtoupper($comp_details['city']) . ($comp_details['pincode'] != '' ? ' - ' . $comp_details['pincode'] . '.' : '') . "</div>" : ''); ?><br></label>
						<label><?php echo ($comp_details['state'] != '' ? '<div class="addr_brch_labels">State</div><div class="addr_brch_values">:&nbsp;&nbsp;' . strtoupper($comp_details['state'] . ($comp_details['state_code'] != '' ? '-' . $comp_details['state_code']  : '')) . '.' . "</div><br>" : ''); ?></label>
						<div style="display:none">
							<label><br><?php echo '<div  style="height:18px !important"class="addr_brch_labels">Place of supply</div><div class="addr_brch_values">:&nbsp;&nbsp;' . strtoupper($comp_details['state'] . ($comp_details['state_code'] != '' ? '-' . $comp_details['state_code']  : '')) . '.' . "</div><br>"; ?></label>
							<label>
								<div class="addr_brch_labels">Reverse Charges</div>
								<div class="addr_brch_values">:&nbsp;&nbsp;NO</div><br>
							</label>
						</div>
					</div>
				</div> -->
				<div id="headertable" style="width:50%;vertical-align:top;padding-top:20px;">
					<table style="width:100%;line-height:-6px !important">
						<?php if ($comp_details['name'] != '') { ?>
							<tr>
								<td class="line_height" style="width:15%"><?php echo ($comp_details['name'] != '' ? 'BRANCH:' : ''); ?></td>
								<td class="line_height" style="width:85%"><?php echo $comp_details['name'] != '' ? $comp_details['name'] : ''; ?></td>
							</tr>
						<?php } ?>
						<?php if ($comp_details['address1'] != '') { ?>
							<tr>
								<td class="line_height" style="width:15%"><?php echo ($comp_details['address1'] != '' ? 'ADDRESS:' : ''); ?> </td>
								<td class="line_height" style="width:85%"><?php echo $comp_details['address1']; ?></td>
							</tr>
						<?php } ?>
						<?php if ($comp_details['address2'] != '') { ?>
							<tr>
								<td class="line_height" style="width:15%"><?php echo ($comp_details['address2'] != '' ? "" : ''); ?> </td>
								<td class="line_height" style="width:85%"><?php echo $comp_details['address2']; ?></td>
							</tr>

						<?php } ?>
						<?php if ($comp_details['city'] != '') { ?>
							<tr>
								<td class="line_height" style="width:15%"><?php echo ($comp_details['city'] != '' ? "CITY" : ''); ?> </td>
								<td class="line_height" style="width:85%"><?php echo $comp_details['city']; ?></td>
							</tr>
						<?php } ?>
						<?php if ($comp_details['state'] != '') { ?>
							<tr>
								<td class="line_height" style="width:15%"><?php echo ($comp_details['state'] != '' ? "STATE" : ''); ?> </td>
								<td class="line_height" style="width:85%"><?php echo $comp_details['state']; ?></td>
							</tr>
						<?php } ?>
						<?php if ($comp_details['city_name'] != '') { ?>
							<tr>
								<td class="line_height" style="width:15%"><?php echo ($comp_details['city_name'] != '' ? 'CITY:' : ''); ?> </td>
								<td class="line_height" style="width:85%"><?php echo ($comp_details['city_name'] != '' ? strtoupper($comp_details['city_name'])  . ($issue['pincode'] != '' ? ' - ' . $issue['pincode'] . '.' : '') : ''); ?></td>
							</tr>
						<?php } ?>

						<div style="display:none">
							<label><br><?php echo '<div  style="height:18px !important"class="addr_brch_labels">Place of supply</div><div class="addr_brch_values">:&nbsp;&nbsp;' . strtoupper($comp_details['state'] . ($comp_details['state_code'] != '' ? '-' . $comp_details['state_code']  : '')) . '.' . "</div><br>"; ?></label>
							<label>
								<div class="addr_brch_labels">Reverse Charges</div>
								<div class="addr_brch_values">:&nbsp;&nbsp;NO</div><br>
							</label>
						</div>
					</table>
				</div>
				<div class="rept_details">
					<div class="bill_invoice">
						<div style="">
							<label style=" width:10%"><?php echo '<div class="addr_brch_labels" style="height: 13px;">Bill Date</div><div class="addr_brch_values" style="height: 18px;font-weight: bold;">:&nbsp;&nbsp;' . $issue['date_add'] . "</div><br>"; ?></label>
							<label><?php echo '<div class="addr_brch_labels" style="height: 10px;">Bill No</div><div class="addr_brch_values" style="height: 18px;">:&nbsp;&nbsp;' . $issue['bill_no'] . "</div><br>"; ?>
							</label>
						</div>
					</div>
				</div>
			</div>
			<div style="width: 100%; text-transform:uppercase;padding-top:10px;">
				<div style="display: none; width: 30%; padding-left:0px;">
					<label><?php echo '<div class="rate_labels" style="height: 18px;">22 KT GOLD</div><div class="addr_values" style="height: 18px;">:&nbsp;&nbsp;' . number_format($metal_rate['goldrate_22ct'], 2, '.', '') . '/' . 'Gm' . "</div>"; ?></label><br>
					<label><?php echo '<div class="rate_labels" style="height: 18px;">SILVER</div><div class="addr_values" style="height: 18px;">:&nbsp;&nbsp;' . number_format($metal_rate['silverrate_1gm'], 2, '.', '') . '/' . 'Gm' . "</div>"; ?></label><br>
				</div>
				<div align="center">
					<label>
						<?php echo $issue['receipt_type'] == "Advance Receipt" ? "ADVANCE RECEIPT" : 'PAYMENT VOUCHER'; ?>
					</label>
				</div>
				<!-- <div style="width: 40%; text-align: right !important; display: inline-block; vertical-align: top;padding-left:40px;">
					<div style="text-align: left !important;width: 100%; display: inline-block;"> 
    						<label></label>
        						<label>
        						</label>
    				</div>
				</div> -->
			</div>
			<div class="content-wrapper">
				<div class="box">
					<div class="box-body">
						<div class="container-fluid">
							<div id="printable">
								<div class="row">
									<hr style="border-bottom:0.5px;">
									<div class="col-xs-12">
										<div class="table-responsive">
											<table id="pp" class="table text-center" style="width:100%">
												<!--	<thead> -->
												<tr>
													<th colspan="5" style="text-transform:uppercase;">Description</th>
													<th colspan="11" style="text-align:right">Amount</th>
												</tr>
												<!--</thead>
    										<tbody>-->
												<tr>
													<?php if ($issue['type'] == 2) { ?>
														<!--<td colspan="5" style="text-transform:uppercase;"><?php echo 'Received with thanks from Mr./Ms.' . $issue['name'] . ' Towards Advance Bill No : ' . $issue['bill_no']; ?></td>-->
														<?php if ($deposit_type_bill_no['deposit_type'] == 1) { ?>
															<td colspan="5" style="text-transform:uppercase;"><?php echo 'Received with thanks from Mr./Ms.' . $issue['name'] . ' Towards BY CUST OWN ' . ($deposit_type_bill_no['metal'] != '' ? $deposit_type_bill_no['metal'] : '') . ' EX Bill No : ' . $deposit_type_bill_no['pur_ref_no']; ?></td>
														<?php	} else if ($deposit_type_bill_no['deposit_type'] == 2) { ?>
															<td colspan="5" style="text-transform:uppercase;"><?php echo 'Received with thanks from Mr./Ms.' . $issue['name'] . ' Towards Credit Note Bill No : ' . $deposit_type_bill_no['s_ret_refno']; ?></td>
														<?php } else { ?>
															<td colspan="5" style="text-transform:uppercase;"><?php echo 'Received with thanks from Mr./Ms.' . $issue['name'] . ' Towards Advance Bill No : ' . $issue['bill_no']; ?></td>
														<?php } ?>
													<?php } else if ($issue['type'] == 1) { ?>
														<?php
														if ($issue['issue_type'] == 3) { ?>
															<td colspan="5" style="text-transform:uppercase;"><?php echo 'Refund to Mr./Ms.' . $issue['name'] . ($issue['refund_receipt'] != '' ? ' Towards Receipt No : ' . $issue['refund_receipt'] : ''); ?></td>
														<?php } else if ($issue['issue_type'] == 1) { ?>
															<td colspan="5" style="text-transform:uppercase;"><?php echo 'Petty Cash Issue'; ?></td>
														<?php } else if ($issue['issue_type'] == 2) { ?>
															<td colspan="5" style="text-transform:uppercase;"><?php echo 'Credit to Mr./Ms.' . $issue['name']; ?></td>
														<?php } ?>
													<?php } ?>
													<td style="text-align:right" colspan="11"><?php echo 'Rs ' . $issue['amount']; ?></td>
												</tr>
												<!--</tbody> -->
												<tr>
													<td colspan="11">
														<hr style="border-bottom:0.5pt; width:150%">
													</td>
												</tr>
											</table><br>
										</div>
									</div>
									<?php
									if ($issue['narration'] != '') { ?>
										<p><b>REMARKS :- <?php echo $issue['narration']; ?></b></p>
									<?php }	?>
								</div><br>
								<!--	<?php if (sizeof($payment) > 0) { ?>
							<div  class="row">
							   <div class="col-xs-6">
									<div class="table-responsive" >
										<table id="pp" class="table text-center" style="width:40%;" align="center">
								
										<?php
											$i = 1;
											$total_amt = 0;
											$due_amount = 0;
											$paid_advance = 0;
											foreach ($payment as $items) {
												$total_amt += $items['payment_amount'];
										?>
											<tr style="font-weight:bold;">
											<td><?php echo $items['payment_mode']; ?></td>
											<td>Rs.</td>
											<td><?php echo number_format($items['payment_amount'], 2, '.', ''); ?></td>
											</tr>
										<?php $i++;
											} ?>
											
								
											<tr>
												<td><hr style="border-bottom:0.5pt;width:170%;"></td>
											</tr>
											<tr style="font-weight: bold;">
												<td>Total</td>
												<td>Rs.</td>
												<td><?php echo number_format((float)($total_amt + $due_amount + $order_adv_pur + $paid_advance), 2, '.', ''); ?></td>
											</tr>
											
									
									</table><br>	
								</div>	
							 </div>	
						</div><br><br><br>
						<?php } ?>-->
								<?php
								if (sizeof($advance_adj_details) > 0) {
									$adjusted_amt = 0;
									foreach ($advance_adj_details as $adj) {
										$adjusted_amt += $adj['adjusted_amt'];
									}
								}
								?>
								<?php if (sizeof($payment) > 0) { ?>
									<div class="row">
										<div class="col-md-12">
											<div class="table-responsive">
												<table id="pp" class="table text-center" style="width:100%;" align="left">
													<div align="left">
														<label><b>PAYMENT DETAILS : </b></label>
													</div>
													<tbody>
														<!-- <tr> -->
														<?php
														$total_amt = 0;
														$due_amount = 0;
														$paid_advance = 0;
														$cash_amt = 0;
														$card_amt = 0;
														$net_banking_amt = 0;
														$gift_amount = 0;
														$chit_amount = 0;
														$adv_adj = 0;
														$imps_amt = 0;
														$rtgs_amt = 0;
														$upi_amt = 0;
														$chq_amt = 0;
														foreach ($payment as $items) {
															$total_amt += $items['payment_amount'];
															if ($items['payment_mode'] == 'Cash') {
																$cash_amt += $items['payment_amount'];
															}
															if ($items['payment_mode'] == 'CHQ') {
																$chq_amt += $items['payment_amount'];
															}
															if ($items['payment_mode'] == 'DC' || $items['payment_mode'] == 'CC') {
																$card_amt += $items['payment_amount'];
															}
															if ($items['payment_mode'] == 'NB' && $items['transfer_type'] == 'RTGS') {
																$net_banking_amt += $items['payment_amount'];
																$rtgs_amt += $items['payment_amount'];
															}
															if ($items['payment_mode'] == 'NB' && $items['transfer_type'] == 'IMPS') {
																$net_banking_amt += $items['payment_amount'];
																$imps_amt += $items['payment_amount'];
															}
															if ($items['payment_mode'] == 'NB' && $items['transfer_type'] == 'UPI') {
																$net_banking_amt += $items['payment_amount'];
																$upi_amt += $items['payment_amount'];
															}
														}

														if ($cash_amt > 0) {
														?>
															<tr>
																<td style="width:24%;">CASH</td>
																<td style="width:10%;" class="alignRight"><?php echo (number_format($cash_amt, 2, '.', '')); ?></td>
																<td style="width:66%;"></td>
															</tr>

														<?php } ?>

														<?php if ($chq_amt > 0) { ?>
															<?php foreach ($payment as $chq) {
																if ($chq['payment_mode'] == 'CHQ') { ?>
																	<tr>
																		<td style="width:24%">CHEQUE</td>
																		<td style="width:10%" class="alignRight"><?php echo (number_format($chq['payment_amount'], 2, '.', '')); ?></td>
																		<td style="width:66%"><?php echo ($chq['cheque_no'] != '' ? 'Chq\ Ref.No - ' . $chq['cheque_no'] : '') . ($chq['cheque_date'] != '' ? ' .Dtd - ' . $chq['cheque_date'] : '') . ($chq['bank_name'] != '' ? '- ' . $chq['bank_name'] : '') ?></td>

																	</tr>
														<?php }
															}
														} ?>

														<?php if ($card_amt > 0) { ?>
															<?php foreach ($payment as $cardItem) {
																if ($cardItem['payment_mode'] == 'DC' || $cardItem['payment_mode'] == 'CC') { ?>
																	<tr>
																		<td style="width:24%"><?php echo $cardItem['payment_mode'] == 'DC' ? 'DEBIT CARD' : ($cardItem['payment_mode'] == 'CC' ? 'CREDIT CARD' : '') ?></td>
																		<td style="width:10%" class="alignRight"><?php echo (number_format($cardItem['payment_amount'], 2, '.', '')); ?></td>
																		<td style="width:66%"><?php echo $cardItem['card_no'] != '' ? ' Ref.No - ' . $cardItem['card_no'] : '' ?></td>

																	</tr>
														<?php  }
															}
														} ?>

														<?php if ($rtgs_amt > 0) {
															foreach ($payment as $rtgs) {
																if ($rtgs['transfer_type'] == 'RTGS') {
																	$rtgs_ref_no = $rtgs['payment_ref_number'];
																	$rtgs_amount = $rtgs['payment_amount'] ?>
																	<tr>
																		<td style="width:24%">RTGS</td>
																		<td class="alignRight" style="width:10%;"><?php echo (number_format($rtgs_amount, 2, '.', '')); ?></td>
																		<td style="width:66%"><?php echo ($rtgs_ref_no != '' ? ' Ref.No - ' . $rtgs_ref_no : '') . ($rtgs['net_banking_date'] != '' ? ' .Dtd - ' . $rtgs['net_banking_date'] : '') ?> </td>
																	</tr>
																<?php }
																?>
														<?php }
														}  ?>

														<?php if ($imps_amt > 0) {
															foreach ($payment as $imps) {
																if ($imps['payment_mode'] == 'NB' && $imps['NB_type'] == 'IMPS') {
																	$imps_ref_no = $imps['payment_ref_number'];
																	$imps_amount = $imps['payment_amount']; ?>
																	<tr>
																		<td style="width:24%">IMPS</td>
																		<td class="alignRight" style="width:10%;"><?php echo (number_format($imps_amount, 2, '.', '')); ?></td>
																		<td style="width:66%;"><?php echo ($imps_ref_no != '' ? ' Ref.No - ' . $imps_ref_no : '') . ($imps['net_banking_date'] != '' ? ' .Dtd - ' . $imps['net_banking_date'] : '') ?></td>
																	</tr>
																<?php	}
																?>
														<?php }
														} ?>


														<?php if ($upi_amt > 0) {

															foreach ($payment as $nb) {
																if ($nb['transfer_type'] == 'UPI') {
																	$upi_amount = 	$nb['payment_amount'];
																	$ref_no = $nb['payment_ref_number']
														?>
																	<tr>
																		<!-- <td colspan="7"></td> -->
																		<td style="width:24%">UPI</td>
																		<td class="alignRight" style="width:10%;"><?php echo (number_format($upi_amount, 2, '.', '')); ?></td>
																		<td style="width:66%;"><?php echo ($ref_no != '' ? ' Ref.No - ' . $ref_no : '') . ($nb['net_banking_date'] != '' ? ' .Dtd - ' . $nb['net_banking_date'] : '') ?></td>

																	</tr>
														<?php }
															}
														} ?>




														<?php
														if ($adjusted_amt > 0) { ?>


															<tr>
																<!-- <td colspan="7"></td> -->
																<td style="width:24%">Adv Adj</td>
																<td class="alignRight" style="width:10%;"><?php echo number_format($adjusted_amt, 2, '.', ''); ?></td>
																<td style="width:66%;"></td>

															</tr>
														<?php }
														?>

														<!-- <td colspan="7"></td> -->
														<td style="width:24%">Total</td>
														<td class="alignRight" style="width:10%;"><?php echo number_format((float)($total_amt + $due_amount + $order_adv_pur + $paid_advance + $adjusted_amt), 2, '.', ''); ?></td>
														<td style="width:66%;"></td>

														</tr>
														</tr>

													</tbody>

												</table><br>
											</div>
										</div>
									</div><br><br><br>
								<?php } ?>
								<div class="row">
									<div class="col-md-12">
										<div class="table-responsive">
											<table id="pp" class="table text-center" style="width:70%;" align="left">
												<?php if ($billing_adv_adj > 0) {
													foreach ($billing_adv_adj as $val) { ?>
														<tr>
															<th><?= $val['utilized_amt'] != '' ? 'Adj Amt' : '' ?></th>
															<th><?php echo ($val['utilized_amt'] != '' ? number_format($val['utilized_amt'], 2, '.', '') : ''); ?></th>
															<th><?= $val['adjusted_bill_no'] != '' ? 'Refer Bill : ' . $val['adjusted_bill_no'] : '' ?></th>
														</tr>
												<?php }
												}	?>
											</table>
										</div>
									</div>
								</div>
							</div>
							<p></p>
							<div style="margin-top: 3px; margin-bottom: 3px">
								<div><span style="font-weight: bold;">Amount in Words</span> : <span><?php echo $this->ret_billing_model->no_to_words($issue['amount']); ?> Only</span></div>
							</div><br>
							<?php if (sizeof($receipt_adv_details) > 0) {
								$tot_adv = 0;
								$adj_amt = 0;
								foreach ($receipt_adv_details as $adv) {
									$tot_adv = $adv['receipt_amt'];
									$adj_amt = $adv['utilized_amt'];
							?>
									<div>
										<table id="pp" class="table text-center" style="width:85%">
											<tr>
												<td><b>Receipt No</b></td>
												<td><b>Receipt Date</b></td>
												<td><b>Receipt Amount</b></td>
												<td><b>Utilized Amount</b></td>
												<td><b>Refund Amount</b></td>
												<td><b>Balance Amount</b></td>
											</tr>
											<tbody>
												<tr>
													<td><?php echo $adv['bill_no']; ?></td>
													<td><?php echo $adv['bill_date']; ?></td>
													<td><?php echo number_format($adv['receipt_amt'], 2, '.', ''); ?></td>
													<td><?php echo number_format($adv['utilized_amt'], 2, '.', ''); ?></td>
													<td><?php echo number_format($adv['refund_amount'], 2, '.', ''); ?></td>
													<td><?php echo number_format($adv['balance_amount'], 2, '.', ''); ?></td>
												</tr>
											</tbody>
										</table>
									</div><br>
							<?php }
							} ?>
							<div class="footer_signature">
								<table>
									<td style="font-weight: bold;" class="cus_sign">
										Customer Signature
									</td>
									<td class="auth_sign">
										<div style="font-weight: bold;"> <?php
																			$emp_name = $issue['emp_name'] != '' ? $issue['emp_name'] . ' ' : '';
																			$system_id = $billing['counter_short_code'] != '' ? (' - System ID : ' . $billing['counter_short_code'] . ' - ') : '';
																			$current_date = date("d-m-y h:i:sa");
																			?>
											<label>Operator Signature</label><br>
													<?php
													echo $system_id . $current_date . '<br>';
													echo '[' . $emp_name . ']';
													?>
										</div>
									</td>
									<td style="font-weight: bold;" class="cashier_sign">
										Cashier Signature
									</td>
								</table>
								<!-- <div style="height: 60px;">
								<div class="mh-100" style="width: 100px; height: 200px; "></div>
							</div> -->
								<div style="padding-top:20px">
									<p>*This is System generated Issue *E & O.E.</p>
								</div>
							</div>
						</div>
					</div>
				</div><!-- /.box-body -->
			</div>
	</span>
	<script>
		window.print();
	</script>
</body>

</html>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Receipt</title>
		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/cus_receipt.css">
		<!--	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/receipt_temp.css">-->
		
	</head>
	
	<body class="margin">
	<div class="PDF_CusReceipt">
		<div class="" align="left">
				<?php echo $comp_details['company_name']; ?><br/>				
				<?php echo $comp_details['address1'].','; ?>				
				<?php echo $comp_details['address2'].','; ?><br/>
				<?php echo $comp_details['city'].' - '.$comp_details['pincode'].'.'; ?><br/><br/>
				<?php echo 'Receipt Date : '.$records[0]['date_payment'];?><br/><br/>
			</div>
			<div class="" style="font-weight: 400 !important; font-size:16px !important;" align="center">
				<?php echo $records[0]['scheme_name'];?><hr>				
			</div>
			<div>
			<table class="meta" style="width: 100%" align="center">
				<tr>
					<th><span >Group Code</span></th>
					<td><span><?php echo $records[0]['sch_code']; ?> - <?php echo $records[0]['scheme_acc_number']; ?></span></td>
				</tr>
				<tr>
					<th><span >Receipt No</span></th>
					<td><span ><?php echo $records[0]['receipt_no']; ?></span></td>
				</tr>
				<tr>
					<th><span >Installment No</span></th>
					<td><span><?php echo $records[0]['installment']; ?></span></td>
				</tr>
				<tr>
					<th><span >Mode</span></th>
					<td><span><?php echo $records[0]['payment_mode']; ?></span></td>
				</tr>
				<tr>
					<th><span >Mobile</span></th>
					<td><span><?php echo $records[0]['mobile']; ?></span></td>
				</tr>
			</table><hr>
			</div>
			<div>
				<?php if($records[0]['due_type']=='D') { ?>
					<p align="left">Paid by <?php echo $comp_details['company_name']; ?> for <br/>Mr/Mrs. <?php echo $records[0]['firstname'].(isset($records[0]['firstname'])?', ':''); ?></p>
					<div align="left"><span data-prefix><?php echo $comp_details['currency_symbol']; ?> </span><span style="font-weight: 400 !important; font-size:16px !important;"><?php echo $records[0]['payment_amount']; ?></span></div>
				<?php }else{ ?>
					<p align="left">Received with thanks form <br/>Mr/Mrs. <?php echo $records[0]['firstname'].(isset($records[0]['firstname'])?', ':''); ?></p>
					<div align="left"><span data-prefix><?php echo $comp_details['currency_symbol']; ?> </span><span style="font-weight: 400 !important; font-size:16px !important;"><?php echo $records[0]['payment_amount']; ?></span></div>
				<?php } ?>
				
				<p style="font-size:12px !important;"><?php echo ucwords($records[0]['amount_in_words']); ?> Only</p>
							
				<p></p><br/>
				<div align="right">For <?php echo $comp_details['company_name']; ?></div><br/><br/>
				<div align="right">Signature</div>
			</div>
		</div>
		<script type="text/javascript"> 
		this.print(); 
		</script> 
	</body>
	
</html>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Receipt</title>
		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/receipt_thermal.css">
		<!--	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/receipt_temp.css">-->
		
	</head>
	
	<body class="plugin"  align="">
		<div class="PDF_receipt_thermal">		
			<div class="copmy_name">
				<?php echo $comp_details['company_name']; ?>	
				</div>
		<p class="dotted"/>
			<table class="meta" align="center">
				<tr class="cus_name">
					<th><span >Customer Name</span></th>
					<td><span><?php echo $records[0]['name']; ?></span></td>
				</tr>
				<tr class="gp_code">
					<th><span >Group Code</span></th>
					<td><span><?php echo $records[0]['sch_code']; ?> - <?php echo $records[0]['scheme_acc_number']; ?></span></td>
				</tr>
				<tr class="rec_no">
					<th><span >Receipt No</span></th>
					<td><span ><?php echo $records[0]['receipt_no']; ?></span></td>
				</tr>
				
				<tr class="pay_amt">
					<th><span >Payment amount</span></th>
					<td><span><?php echo $records[0]['payment_amount']; ?></span></td>
				</tr>
			
				<tr class="rep_date">
					<th><span >Receipt Date</span></th>
					<td><span><?php echo $records[0]['date_payment']; ?></span></td>
				</tr>
			<p class="dotted1"/>
		</table>
			</div>
			
		</div>
		<script type="text/javascript"> 
		this.print(); 
		</script> 
	</body>
	
</html>
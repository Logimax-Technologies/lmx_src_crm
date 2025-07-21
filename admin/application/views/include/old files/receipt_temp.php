<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Receipt</title>
		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/receipt.css">
		<!--	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/receipt_temp.css">-->
		
	</head>
	<body>
	<span class="PDFReceipt">
		<div><img alt="" src="<?php echo base_url();?>assets/img/receipt_logo.png"></div>
		
		<div align="right">
			<p style="font-weight:600;">GST Number &nbsp;:&nbsp;<?php echo $comp_details['gst_number']; ?></p>
		</div>
		
		<div class="address" align="right">
				 <?php echo $comp_details['address1'].' ,'; ?>				
				<?php echo $comp_details['address2'].' ,'; ?>
				<?php echo $comp_details['city'].'  '.$comp_details['pincode'].' ,'; ?>
				<?php echo $comp_details['state'].' ,'; ?>
				<?php echo $comp_details['country'].' .'; ?>
				<p> <?php echo 'Phone : '. $comp_details['phone'];?></p><p> <?php echo 'Mobile : '.$comp_details['mobile'];?></p><p></p>
			</div>
			
			<div class="heading">Receipt</div>
			<table class="meta" style="width: 40%" align="right">
				<tr>
					<th><span >Receipt #</span></th>
					<td><span ><?php echo $records[0]['receipt_no']; ?></span></td>
				</tr>
				<tr>
					<th><span>Date</span></th>
					<td><span> <?php echo $records[0]['date_payment']; ?></span></td>
				</tr>
				
				<tr>
					<th><span >Mode</span></th>
					<td><span><?php echo $records[0]['payment_mode']; ?></span></td>
				</tr>
				<tr>
					<th><span >Group Code</span></th>
					<td><span><?php echo $records[0]['sch_code']; ?></span></td>
				</tr>
				<tr>
					<th><span >HSN Code</span></th>
					<td><span><?php echo $records[0]['hsn_code']; ?></span></td>
				</tr>
				<tr>
					<th><span >A/c No</span></th>
					<td><span><?php echo $records[0]['scheme_acc_number']; ?></span></td>
				</tr>
				<tr>
					<th><span >A/c Name</span></th>
					<td><span><?php echo $records[0]['account_name']; ?></span></td>
				</tr>
				<tr>
					<th><span >Mobile</span></th>
					<td><span><?php echo $records[0]['mobile']; ?></span></td>
				</tr>
			</table>
			<p></p>
			<p></p>
			<p></p>
			<p></p>
			<div class="useraddr"><p><?php echo $records[0]['firstname'].' '.$records[0]['lastname'].(isset($records[0]['firstname'])?',':''); ?></p>
				<p > <?php echo $records[0]['address1'].(isset($records[0]['address1'])?',<br/>':''); ?> </p>
				 <p ><?php echo $records[0]['address2'].(isset($records[0]['address2'])?',<br/>':''); ?> </p>
				 <p ><?php echo $records[0]['address3'].(isset($records[0]['address3'])?',<br/>':''); ?> </p>
				 <p ><?php echo $records[0]['city']." - ".$records[0]['pincode']; ?>	
				 </p>
				 </div>
			<table class="inventory">
				<thead>
					<tr>
						<!--<th><span contenteditable>Item</span></th>-->
						<th><span contenteditable>Description</span></th>
						<th><span contenteditable>Gold Rate</span></th>
						<th><span contenteditable>Weight</span></th>
						<th><span contenteditable>Price</span></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<!--<td><span contenteditable>Front End Consultation</span></td>-->
						<td><span contenteditable><?php echo $records[0]['scheme_name']; ?></span></td>
						<td style="text-align: center"><?php echo ($records[0]['scheme_type']=='0' ? '-':($records[0]['metal_rate']!='-'?$comp_details['currency_symbol'].' '.$records[0]['metal_rate']:'-')); ?></td>
						<td><span ><?php echo ($records[0]['scheme_type']=='0' ? '-': $records[0]['metal_weight'].' g'); ?></span></td>
						<td><span data-prefix><?php echo $comp_details['currency_symbol']; ?> </span><span><?php echo $records[0]['payment_amount']; ?></span></td>
					</tr>
					<tr>
						<td colspan="4"><?php echo ucwords($records[0]['amount_in_words']); ?> Only</td>
					</tr>
					<?php if($records[0]['due_type']=='D') { ?>
					<tr>
						<td colspan="4" align="center" style="color: #ff0000;">Paid by <?php echo $comp_details['company_name']; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<p></p>
			<table class="balance" style="width: 50%;" >
			<?php if($records[0]['add_charges'] > 0){?>
				<tr >
					<th><span ><?php echo  $records[0]['payment_type'] == 'Payu Checkout' ?$records[0]['charge_head']:'Additional Charge'?></span></th>
					<td><span data-prefix><?php echo $comp_details['currency_symbol']; ?> </span><span><?php echo $records[0]['add_charges']; ?></span></td>
				</tr>
			<?php }?>
			<?php
					$gst_calc = 0;
					$gst_amt = 0;
					if($records[0]['gst'] > 0){
						if($records[0]['gst_type'] == 1 ){
							$gst_calc = $records[0]['payment_amount']*($records[0]['gst']/100);
							$gst_amt = $gst_calc;
						}
						else{
							$gst_calc = $records[0]['payment_amount']-($records[0]['payment_amount']*(100/(100+$records[0]['gst'])));
						}
					}

					?>
			 <?php if($records[0]['gst'] > 0){?>
					<tr>
					<th><span >GST <?php echo $records[0]['gst'].' %'; ?></span></th>
					<td style="text-transform: uppercase;"><span data-prefix><?php echo $comp_details['currency_symbol']; ?> </span>
					<span ><?php
					$gst_calc = 0;
					$gst_amt = 0;
					if($records[0]['gst'] > 0){
						if($records[0]['gst_type'] == 1 ){
							$gst_calc = $records[0]['payment_amount']*($records[0]['gst']/100);
							$gst_amt = $gst_calc;
							echo number_format($gst_calc,'2','.','');
						}
						else{
							$gst_calc = $records[0]['payment_amount']-($records[0]['payment_amount']*(100/(100+$records[0]['gst'])));
							echo number_format($gst_calc,'2','.','');
						}						
					}
					else{
						echo '-';
					}

					?></span><br/>
					
					<?php foreach($gstSplitup as $data){
					//	if($data['type'] != NULL && $records[0]['gst'] > 0 && ($records[0]['id_state'] != '' || $records[0]['id_state'] != NULL)){
						if($data['type'] != NULL && $records[0]['gst'] > 0 ){	
							if(($records[0]['id_state'] == $comp_details['id_state']) || ($records[0]['id_state'] == '' || $records[0]['id_state'] == NULL)){
								if($data['type'] == 0){ //$data['type'] -> same or different state flag
								  if($records[0]['gst_type'] == 1 ){
								  	$calc_amt = $records[0]['payment_amount']*($data['percentage']/100);
								  }
								  else{
								  	$calc_amt = $records[0]['payment_amount']-($records[0]['payment_amount']*(100/(100+$data['percentage'])));
								  }
								  echo '<br/><div >'.$data['splitup_name'].'('.$data['percentage'].'%)   - '.$comp_details['currency_symbol'].' '.number_format($calc_amt,"2",".","").'</div> ';
								}	
							}
							else{
								if($data['type'] == 1){
									 if($records[0]['gst_type'] == 1 ){
									  	$calc_amt = $records[0]['payment_amount']*($data['percentage']/100);
									  }
									  else{
									  	$calc_amt = $records[0]['payment_amount']-($records[0]['payment_amount']*(100/(100+$data['percentage'])));
									  }
									echo '<br/><div >'.$data['splitup_name'].'('.$data['percentage'].'%)   - '.$comp_details['currency_symbol'].' '.number_format($calc_amt,"2",".","").'</div> ';
								}
							}
							
						}
					} ?>
					
					</td>
				</tr>
					<?php }?>
				<!--<tr>
					<th><span >Total</span></th>
					<td><span data-prefix><?php echo $comp_details['currency_symbol']; ?> </span><span><?php echo $records[0]['payment_amount']; ?></span></td>
				</tr>-->
				<tr>
					<th><span >Payment Amount </span></th>
					<td><span data-prefix><?php echo $comp_details['currency_symbol']; ?> </span><span ><?php echo number_format($records[0]['payment_amount']+$gst_amt+$records[0]['add_charges'],"2",".",""); ?></span></td>
				</tr>
		      	
		      	<tr>
					<th><span>Next Due</span></th>
					<td><span><?php echo $records[0]['next_due']; ?></span></td>
				</tr>
			</table>
			<p></p>
			
		<aside>
			
			<div >
				<p class="txtAckowlege">This is system generated receipt, keep this as an acknowledgement.</p>
			</div>
		</aside>
		</span>
	</body>
</html>
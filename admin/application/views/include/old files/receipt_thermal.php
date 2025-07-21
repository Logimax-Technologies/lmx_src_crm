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
				<!--<?php echo $comp_details['company_name']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $records[0]['scheme_name'];?>-->		
				<?php echo $comp_details['company_name']; ?>		
			</div>
			<p class="dotted"/>
			<table class="meta" align="center">
				<tr class="cus_name">
					<th><span >Customer Name &nbsp;:&nbsp;</span></th>
					<td>&nbsp;<span><?php  echo $records[0]['name']; ?></span></td>
				</tr>
				<tr class="sch_name">
					<th><span >Scheme Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;</span></th>
					<td>&nbsp;<span><?php  echo $records[0]['scheme_name']; ?></span></td>
				</tr>
			</table>
			
					<div class="col-md-12">
							<div class="col-md-5">
								<table class="meta" align="left">
									<tr class="gp_code">					
										<td><span >G.Code</span><span class="demo1">:&nbsp;&nbsp;</span><span><?php echo $records[0]['sch_code']; ?> - <?php echo $records[0]['scheme_acc_number']; ?></span></td>
									</tr>
									<tr class="rec_no">					
										<td><span >Rec.No</span><span class="demo2">:&nbsp;&nbsp;</span><span ><?php echo $records[0]['receipt_no']; ?></span></td>
									</tr>
									<tr class="received_amt">										
										<td><span >R.Amt</span><span class="demo3">:&nbsp;&nbsp;</span><span><?php echo $records[0]['payment_amount']; ?></span></td>
									</tr>
									<tr class="pay_date">					
										<td><span >Pay.Date</span><span class="demo4">:&nbsp;&nbsp;</span><span><span ><?php echo $records[0]['date_payment']; ?></span></td>
									</tr>
									<tr class="installment">					
										<td><span >Pay.Ins</span><span class="demo2">:&nbsp;&nbsp;</span><span></span><span ><?php echo $records_sch['paid_installments']; ?></span></td>
									</tr>
									
								<p class="dotted1"/>
							</table>
						</div>
						
						<div class="col-md-3">
								<table class="meta1" align="right">						
									
									<tr class="last_payamt">
										
										<td><span >Lpay.Amt</span><span class="demo2">:&nbsp;&nbsp;</span><span><?php 
										$pay=$records_sch['total_paid_amount']; 
										$wght=$records_sch['total_paid_weight'];
										if($pay!=''&& $pay!=0)
										{
											echo $records_sch['total_paid_amount'];
										}
										else if($wght!=''&& $wght==0 && $pay==0) 
										{
											echo $records_sch['total_paid_weight'];	
											
										}
										
										
										?></span></td>
									</tr>
									<tr class="last_paydate">
										
										<td><span >Lpay.date</span><span class="demo5">:&nbsp;&nbsp;</span><span><?php echo $records_sch['last_paid_date']; ?></span></td>
									</tr>
								
									
									<tr class="total_amt">										
										<!--<td><span >T.amount</span>&nbsp;:&nbsp;<span><?php echo $records[0]['payment_amount']; ?></span></td>-->
										<td><span >T.Amt</span><span class="demo6">:&nbsp;&nbsp;</span><span><?php echo $records_sch['total_installments']*$records_sch['payable']; ?></span></td>
									</tr>
									<tr class="emp_name">										
										<td><span >Emp.Name</span><span class="demo7">:&nbsp;&nbsp;</span><span><?php echo $username=($this->session->userdata['username']?$this->session->userdata['username']:'Admin'); ?></span></td>
									</tr>
								<p class="dotted1"/>
							</table>
						</div>
						<div class="col-md-9">
							<div class="clr">
							</div>
						</div>

					</div>
				</div>
			</div>
		<script type="text/javascript"> 
		this.print(); 
		</script> 
	</body>
	
</html>
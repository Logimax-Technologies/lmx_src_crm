<html><head>
		<meta charset="utf-8">
		<title>Supplier Payment Acknolodgement</title>
		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/process_ack.css">
		<!--	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/receipt_temp.css">-->
		<style type="text/css">
		body, html {
		margin-bottom:0
		}
		 span { display: inline-block; }
	</style>
</head><body>
<span class="PDFReceipt">
			<div>
				<table class="meta" style="width:30%;margin-top:-30px !important;" align="left">
					<tr>
					<td><img alt="" width="60%" src="<?php echo base_url();?>assets/img/receipt_logo.png"></td>
					</tr>
				</table>
				<table class="meta" style="width:30%;font-weight:bold;" align="right">
					<tr>
						<td style="font-size:11px !important;">
							<span>Pay ref no &nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp; <?php echo $paymentdetails['paydetails']['pay_refno'];?></span>
						</td>
					</tr>
					<tr style="margin-top:25px !important;">
						<td style="font-size:11px !important;">
							<span>DATE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							:&nbsp;&nbsp;<?php echo $paymentdetails['paydetails']['pay_date'];?></span>
						</td>
					</tr></br>
					<tr style="margin-top:25px !important;">
						<td style="font-size:11px !important;">
							<span>VENDOR&nbsp;&nbsp;
							:&nbsp;&nbsp;<?php echo $paymentdetails['paydetails']['karigar'];?> </span>
						</td>
					</tr></br>
					<tr style="margin-top:25px !important;">
						<td style="font-size:11px !important;">
							<span>EMP &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							:&nbsp;&nbsp; <?php echo $lot_inwards_detail[0]['emp_name'];?> </span>
						</td>
					</tr></br>
				</table>
				<p></p>
			</div>
			<br><br><br><br><br>
			<div class="address" align="center">
					<h2>Payment Slip </h2>
			</div><br>
<div  class="content-wrapper">
 <div class="box">
  <div class="box-body">
 			<div  class="container-fluid">
				<div id="printable">
				    
				        <div  class="row">
							<div class="col-xs-12">
								<div class="table-responsive">
							

									<table id="pp" class="table text-center">
									<!--	<thead> -->
											<tr>
												<th width="15%;" style="text-align:left;">Date</th>
												<th  width="15%;" style="text-align:left;">Type</th>
												<th  width="30%;"style="text-align:left;">Ref No</th>		
												<th  width="40%;" style="text-align:left;">Amount</th>
											
											</tr>
											<tr>
												<td><hr class="item_dashed"></td>
											</tr>
											<tr>
												<td style="text-align:left;"><?php echo $paymentdetails['paydetails']['pay_date'];?></td>
												<td style="text-align:left;"><?php echo $paymentdetails['paydetails']['bill_type'] == 1 ? "Purchase" : "Advance";?></td>
												<td style="text-align:left;"><?php echo $paymentdetails['paydetails']['pay_refno'];?></td>
												<td style="text-align:right;"><?php echo $paymentdetails['paydetails']['tot_cash_pay'];?></td>
											</tr>
									</table>
								</div>
							</div>
						</div>
						
						<?php if($paymentdetails['paydetails']['bill_type'] == 1) { ?>
						    <div  class="row">
    							<div class="col-xs-12">
    								<div class="table-responsive">
    							
    
    									<table id="pp" class="table text-center">
    									<!--	<thead> -->
    											<tr>
    												<th width="15%;" style="text-align:left;">PO Ref Id</th>
    												<th  width="40%;" style="text-align:left;">Amount</th>
    											
    											</tr>
    											<tr>
    												<td><hr class="item_dashed"></td>
    											</tr>
    												<?php 
            											
            											foreach($paymentdetails['pay_po_details'] as $pokey => $poval)
            											{  ?>
            										
                											<tr>
                												<td style="text-align:left;"><?php echo $poval['po_ref_no'];?></td>
                												<td style="text-align:right;"><?php echo $poval['payamt'];?></td>
                												
                											</tr>
    											<?php } ?>
    									</table>
    								</div>
    							</div>
    						</div>
						<?php } ?>
						
				    
						<div  class="row">
							<div class="col-xs-12">
								<div class="table-responsive">
							

									<table id="pp" class="table text-center">
									<!--	<thead> -->
											<tr>
												<th width="15%;" style="text-align:left;">Type</th>
												<th  width="25%;" style="text-align:left;">Mode</th>
												<th  width="20%;"style="text-align:left;">Amount</th>		
												<th  width="20%;" style="text-align:left;">Reference</th>
											</tr>
											<tr>
												<td><hr class="item_dashed"></td>
											</tr>
										<!--</thead>
										<tbody>-->
											<?php 
										
											foreach($paymentdetails['pay_mode_details'] as $pmkey => $pmval)
											{
											
											?>
												<tr>
											
														<td style="text-align:left;"><?php echo $pmval['product_name'];?></td>
														<td style="text-align:left;"><?php echo $pmval['pay_mode'];?></td>
														<td style="text-align:left;"><?php echo $pmval['payment_amount'];?></td>
														<td style="text-align:left;"><?php echo $pmval['ref_no'];?></td>
													</tr>
											<?php $i++;
											}?>
											<tr>
												<td><hr class="item_dashed"></td>
											</tr>
									<!--</tbody> -->
										<tfoot>
											<tr>
											<td><b>TOTAL</b></td>
											<td></td>
											<td><b></b></td>
											<td><b></b></td>
											<td></td>
											<td></td>
											</tr>
										</tfoot>
										<tr>
												<td><hr class="item_dashed"></td>
											</tr>
									</table><br>	
									
									
								</div>	
							 </div>	
						</div>
						<br><br><br><br><br><br><br><br><br><br><br><br>
						<div class="row" style="text-transform:uppercase;">
							<label>Issuer Sign</label>
							<label style="margin-left:30%;">Receiver Sign</label>
						</div>
				</div>
				
				
 </div>
 </div><!-- /.box-body --> 
</div>

 </span>          
</body></html>
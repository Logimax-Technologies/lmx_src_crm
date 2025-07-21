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
</head>
<body style="margin-top:10% !important;">
<span class="PDFReceipt">
            <!--<div>
                <img alt="" src="<?php echo base_url();?>assets/img/receipt_logo.png">
            </div>-->

            <div class="" align="left">
				<label style="font-weight:bold;"><?php echo 'Mr./Ms.'.$issue['name']; ?></label><br>			
				<label><?php echo ($billing['branch_name']!='' ?$billing['branch_name'].',' :'').'Mobile No : '.$issue['mobile']; ?></label>
			</div>
			<p></p>
			<div class="row">
				<table class="meta" style="width:30%;" align="left">
					<tr>
						<th><span>Gold Rate</span></th>
						<td><span><?php echo $metal_rate['goldrate_22ct'].'/'.'Gm'; ?></span></td>
					</tr>
					<tr style="padding-top:-0.5em;">
						<th><span>Silver Rate</span></th>
						<td><span><?php echo $metal_rate['silverrate_1gm'].'/'.'Gm'; ?></span></td>
					</tr>
				</table>
				<?php if($issue['issue_type']!=3){?>
				<table class="meta" style="width:30%;" align="center">
					<tr>
						<th style="text-transform:uppercase;">advance receipt</th>
					</tr>
				</table>
				<?php }else if($issue['issue_type']==3){?>
				<table class="meta" style="width:30%;" align="center">
					<tr>
						<th style="text-transform:uppercase;">Payment Receipt</th>
					</tr>
				</table>
				<?php }?>
				<table class="meta" style="width:40%;margin-top:-6%;text-transform:uppercase;" align="right">
					<tr>
						<th><span>State</span></th>
						<td><span><?php echo $comp_details['state'];?></span></td>
					</tr><br><br>
					<?php if($issue['issue_type']==3){?>
					<tr>
						<th><span>GST Bill No</span></th>
						<td><span><?php echo ($issue['short_name']!='' ? $issue['short_name'].'/':'').($issue['fin_year_code']!='' ?$issue['fin_year_code'].'/' :'').'PR/'.$issue['bill_no'] ?></span></td>
					</tr>
					<?php }else{?>
					<tr>
						<th><span>GST Bill No</span></th>
						<td><span><?php echo ($issue['short_name']!='' ? $issue['short_name'].'/':'').($issue['fin_year_code']!='' ?$issue['fin_year_code'].'/' :'').'AD/'.$issue['bill_no'] ?></span></td>
					</tr>
					<?php }?>
					<tr>
						<th><span>Date</span></th>
						<td><span><?php echo $issue['date_add'];?></span></td>
					</tr>
				</table>
			</div><br>
<br>			
<div  class="content-wrapper">
 <div class="box">
  <div class="box-body">
 			<div  class="container-fluid">
				<div id="printable">				
							<div  class="row">
								
						    <hr style="border-bottom:0.5px;">
							<div class="col-xs-12">
								<div class="table-responsive">
								<table id="pp" class="table text-center">
									<!--	<thead> -->
											<tr>
												<th colspan="5">Description</th>
												<th>Amount</th>
											</tr>
										<!--</thead>
										<tbody>-->
											<tr>
											    <?php if($issue['issue_type']!=3){?>
												<td colspan="5"><?php echo 'Received with thanks from Mr./Ms.'.$issue['name'].' Towards Advance Bill No : '.$issue['bill_no'];?></td>
												<?php }else if($issue['issue_type']==3){?>
												<td colspan="5"><?php echo 'Refund to Mr./Ms.'.$issue['name'];?></td>
												<?php }?>
												<td colspan="5"><?php echo 'Rs '.$issue['amount'];?></td>
											</tr>
									<!--</tbody> -->
											<tr>
												<td colspan="7"><hr style="border-bottom:0.5pt;"></td>
											</tr>
									</table><br>	
								</div>	
							 </div>	
						</div><br>

					<!--	<?php if(sizeof($payment)>0){?>
							<div  class="row">
							   <div class="col-xs-6">
									<div class="table-responsive" >
										<table id="pp" class="table text-center" style="width:40%;" align="center">
								
										<?php
										$i=1;
										$total_amt=0;
										$due_amount=0;
										$paid_advance=0;
										foreach($payment as $items)
											{
												$total_amt+=$items['payment_amount'];
											?>
											<tr style="font-weight:bold;">
											<td><?php echo $items['payment_mode'];?></td>
											<td>Rs.</td>
											<td><?php echo number_format($items['payment_amount'],2,'.','');?></td>
											</tr>
										<?php $i++;}?>
											
								
											<tr>
												<td><hr style="border-bottom:0.5pt;width:170%;"></td>
											</tr>
											<tr style="font-weight: bold;">
												<td>Total</td>
												<td>Rs.</td>
												<td><?php echo number_format((float)($total_amt+$due_amount+$order_adv_pur+$paid_advance),2,'.','');?></td>
											</tr>
											
									
									</table><br>	
								</div>	
							 </div>	
						</div><br><br><br>
						<?php }?>-->
						
				</div><p></p>
				<div class="row" style="text-transform:uppercase;margin-top:40%;">
					<label>Salesman sign</label>
					<label style="margin-left:30%;">customer sign</label>
					<label style="margin-left:30%;"><?php echo $comp_details['company_name']?></label>
				</div>
				<div class="row">
					(EMP ID : <?php echo $issue['id_employee'];?>)
				</div>
						
			</div>
 </div>
 </div><!-- /.box-body --> 
</div>
 </span>          
</body></html>
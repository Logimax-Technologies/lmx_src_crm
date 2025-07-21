<html>

<!--<script>
function printContent(el){
	var restorepage = document.body.innerHTML;
	var printcontent = document.getElementById(el).innerHTML;
	document.body.innerHTML = printcontent;
	window.print();
	document.body.innerHTML = restorepage;
}
</script>-->

<style >
          
		 .head
		 {
			 color: black;
			 font-size: 50px;
		 }
         </style>


<head>
		<meta charset="utf-8">
		<title>Payment Report</title>
		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/receipt.css">
		<!--	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/receipt_temp.css">-->
		
	</head>
 
<body>
<span class="PDFReceipt">

<div><img alt="" src="<?php echo base_url();?>assets/img/receipt_logo.png"></div>
<div class="address" align="right">
				 <?php echo $comp_details['address1'].' ,'; ?>				
				<?php echo $comp_details['address2'].' ,'; ?>
				<?php echo $comp_details['city'].'  '.$comp_details['pincode'].' ,'; ?>
				<?php echo $comp_details['state'].' ,'; ?>
				<?php echo $comp_details['country'].' .'; ?>
				
				
				<p> <?php echo 'Phone : '. $comp_details['phone'];?></p><p> <?php echo 'Mobile : '.$comp_details['mobile'];?></p><p></p>
			</div>
			
			
			<div class="heading"><?php echo $account['customer']['scheme_name']; ?></div>
			
			<table class="meta" style="width: 40%" align="right">
				
				<tr>
					<th><span>Mobile</span></th>
					<td><span> <?php echo $account['customer']['mobile']; ?></span></td>
				</tr>
				<tr>
				
					<th><span >Scheme A/c No</span></th>
					<td><span ><?php echo $account['customer']['scheme_code'].'-'.$account['customer']['scheme_ac']; ?></span></td>
				</tr>
				<tr>
					<th><span >A/c Name</span></th>
					<td><span><?php echo $account['customer']['account_name']; ?></span></td>
				</tr>
				<tr>
					<th><span >Start Date</span></th>
					<td><span><?php echo $account['customer']['start_date']; ?></span></td>
				</tr>
				<tr>
					<th><span >Paid Installments</span></th>
					<td><span><?php echo $account['customer']['paid_installments']; ?></span></td>
				</tr>
			</table>
			<p></p>
			<div class="useraddr"><p><?php echo  $account['customer']['customer_name']?></p>
				<p > <?php echo  $account['customer']['address1'].(!empty( $account['customer']['address1'])?',<br/>':''); ?> </p>
				 <p ><?php echo  $account['customer']['address2'].(!empty( $account['customer']['address2'])?',<br/>':''); ?> </p>
				 <p ><?php echo  $account['customer']['address3'].(!empty( $account['customer']['address3'])?',<br/>':''); ?> </p>
				 <p ><?php echo  $account['customer']['city']."  ". $account['customer']['pincode']; ?>	
				 </p>
				 </div>
<div  class="content-wrapper">

 <div class="box">
  
  <div class="box-body">
 
 
<div  class="container-fluid">
					
				<div id="printable">
						<div  class="row">

							<div class="col-xs-12">
						

								<div class="table-responsive">

								<table id="pp"class="table table-bordered table-striped text-center">

										<thead>

											<tr>

												<th>Payment Date</th>

												<th>Payment Mode</th>

												<th>Rate</th>

												<th>Amount (<?php echo $this->session->userdata('currency_symbol')?>)</th>											

												<th>Weight (g)</th>

												<th>Paid Amount (<?php echo $this->session->userdata('currency_symbol')?>)</th>

												<th>Running Weight (g)</</th>

												

												

											</tr>

										</thead>

										<tbody>

										<?php 

										

											if(isset($account['payment'])) { 

												$bal_amt = 0;		

												$bal_wt = 0;  											

												$prev_wt = number_format($account['customer']['balance_weight'],"3",".","");  											

												$prev_amt = number_format($account['customer']['balance_amount'],"2",".",""); 

												$type=$account['customer']['type'];										

											 foreach($account['payment'] as $pay)

											{

											  $bal_amt = number_format(($bal_amt + ($pay['payment_amount'] != ""? $pay['payment_amount']:0)),"2",".","");

											  $bal_wt = number_format(($bal_wt + ($pay['metal_weight'] != ""? $pay['metal_weight']:0)),"3",".","");	

											  

										  ?>

											<tr>
												
												<td><?php echo $pay['date_payment']; ?> </td>

												<td><?php echo $pay['payment_mode']; ?> </td>	

												<td><?php echo $pay['metal_rate']; ?> </td>												

												<td><?php echo $pay['payment_amount']; ?> </td>		

												<td><?php echo $pay['metal_weight']; ?> </td>	

												<td><?php echo number_format($bal_amt,"2",".","") ; ?> </td>		

												<td><?php echo number_format($bal_wt,"3",".",""); ?> </td>											
												

																							

											</tr>	

	 <?php } } ?>										

										</tbody>

										<tfoot>

																						<tr class="warning">
												
												<th colspan="3">Total</th>

												<td><?php echo $bal_amt ; ?> </td>

												<td><?php echo $bal_wt; ?> </td>

												<td><?php echo $bal_amt ; ?> </td>

												<td><?php echo $bal_wt; ?> </td>											

											 </tr>

											 <tr>

												<th colspan="3">Previous Weight</th>

												<td> <?php echo ($type == 1 ||$type == 2 ? number_format( $prev_amt,"2",".",""):'-'); ?> </td>

												<td><?php echo $prev_wt; ?> </td>

												<td><?php echo($type == 1 ||$type == 2 ? number_format($prev_amt,"2",".","") : '-'); ?> </td>

												<td><?php echo number_format( $prev_wt,"3",".",""); ?> </td>

											 </tr>			

											 <tr>

												<th colspan="3">Previous Amount </th>

												<td><?php echo ($type == 0 ||$type == 2 ? number_format( $prev_amt,"2",".",""):'-'); ?> </td>

												<td> - </td>

												<td><?php echo ($type == 0 ||$type == 2 ? number_format($prev_amt,"2",".","") : '-'); ?> </td>

												<td> - </td>
</tr>
											
											 <tr class="">

												<th colspan="3">Total Paid</th>

												<th><?php echo $this->session->userdata('currency_symbol')." ".number_format(($bal_amt + $prev_amt),"2",".",""); ?>  </th>

												<th><?php echo number_format(($bal_wt + $prev_wt),"3",".","")." g"; ?>  </th>

												<th><?php echo $this->session->userdata('currency_symbol')." ".number_format(($bal_amt + $prev_amt),"2",".",""); ?>  </th>											

												<th><?php echo number_format(($bal_wt + $prev_wt),"3",".","")." g"; ?>  </th>

											 </tr>
											<?php if($account['customer']['is_closed']==1){ ?>
											   <tr class="success">

												<th colspan="3">Deductions/Tax</th>
										
												<th colspan="4" align="right"><?php echo number_format($account['customer']['deductions'],"2",".",""); ?>  </th>
											 </tr>
											 <tr class="success">

												<th colspan="3">Benefits</th>
										
												<?php if($account['customer']['scheme_type']=='Amount'){ ?>
												<th colspan="4" align="right"><?php echo $this->session->userdata('currency_symbol').' '.$account['customer']['benefits']; ?>  </th>
												<?php } else{?>
												<th colspan="4" align="right"><?php echo number_format($account['customer']['benefits'],"3",".","")." g"; ?>  </th>
												<?php }?>
											 </tr>
											  <tr class="success">
												<?php if($account['customer']['scheme_type']=='Amount'){ ?>
												<th colspan="3">Closing Amount</th>
												<th colspan="4" align="right"><?php echo $this->session->userdata('currency_symbol').' '.$account['customer']['closing_balance']; ?>  </th>
												<?php } else{?>
												<th colspan="3">Closing Weight</th>
												<th colspan="4" align="right"><?php echo number_format($account['customer']['closing_balance'],"3",".","")." g"; ?>  </th>
												<?php }?>
											 </tr>
										<?php }?>
										</tfoot>

									</table>	
									
									

								</div>	

							</div>	

						</div>
						</div><p></p>
						<h5>REMARK : <?php echo $account['customer']['status'];?></h5>
						<hr />
						<?php echo date('d-m-Y H:i:s');?>
						
</div>
 </div>
 </div><!-- /.box-body -->
 
 
<!--function printDiv(printable) {
var printContents = document.getElementById(divName).innerHTML;
var originalContents = document.body.innerHTML;
document.getElementById('header').style.display = 'none';
document.getElementById('footer').style.display = 'none';
document.body.innerHTML = printContents;

window.print();


document.body.innerHTML = originalContents;
}-->



</div>
           
</body>
</span>
</html>
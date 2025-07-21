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
			    
			    <div style="text-align:right;margin-top:-28px;">
                    <?php echo $issue['branch_name'];?>
                </div>
                
			    
				<div style="display: inline-block; width: 50%; padding-left:0px;margin-top:4px;">
                        <label><?php echo 'Mr./Ms.'.$issue['name'].' - '.$issue['mobile']; ?></label><br>
                        <label><?php echo (isset($issue['address1']) && $issue['address1']!='' ?$issue['address1'].','."<br>" :''); ?></label>
                        <label><?php echo (isset($issue['address2']) && $issue['address2']!='' ?$issue['address2'].','."<br>" :''); ?></label>
                        <label><?php echo (isset($issue['address3']) && $issue['address3']!='' ?$issue['address3'].','."<br>" :''); ?></label>
                        <label><?php echo ($issue['city_name']!='' ? $issue['city_name'].($issue['pincode']!='' ? ' - '.$issue['pincode'].'.' :''):''); ?><br></label>
                        <label><?php echo ($issue['cus_state']!='' ? $issue.','."<br>" :''); ?></label>
                        <?php if(!empty($issue['cusgst'])) { ?>
                            <label><?php echo "GST : ".$issue['cusgst']; ?></label><br>
                        <?php } ?>
				</div>
                
               
				<div style="width: 100%; text-align: right; display: inline-block; vertical-align: top;">
					<div style="text-align: right; width:100%; height: 20px;">
						<div style="width: 24%; display: inline-block;">&nbsp;  </div>
						<div style="text-align: left;width: 30%; display: inline-block;"> 
						<?php echo ($comp_details['address1']!='' ? "<br>".$comp_details['address1'].',' :'');?> 
						<?php echo ($comp_details['address2']!='' ? "<br>".$comp_details['address2'].',' :'');?> 
						<?php echo ($comp_details['city']!='' ? "<br>".$comp_details['city'].' - '.$comp_details['pincode'] :'');?> 
						</div>
					</div>
				</div>
			</div>
			<p></p>
			<div style="width: 100%; text-transform:uppercase;margin-top:-8px;">
		        <div style="text-align: left; width:100%;height: 18px; ">
					<div style="width: 15%; display: inline-block"> Invoice Date &nbsp;&nbsp; : &nbsp; </div>
					<div style="width: 15%; display: inline-block; margin-top: -1px"> <?php echo $issue['date_add']; ?></div>
				</div>
				<div style="text-align: left; width:100%;height: 18px;">
					<div style="width: 15%; display: inline-block"> Invoice no &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp; </div>
					<div style="width: 18%; display: inline-block; margin-top: -1px"> <?php echo $issue['bill_no']; ?> </div>
				</div>
		    </div>
		    
	    	<div style="width: 100%; text-align: right; display: inline-block;margin-top:-40px;">
			    <div style="text-align: right; width:100%;height: 18px;">
					<div style="width: 88%; display: inline-block"> GOLD &nbsp; : &nbsp; </div>
					<div style="width: 10%; display: inline-block; margin-top: -2px"> <?php echo number_format($metal_rate['goldrate_22ct'],2,'.','').'/'.'Gm'; ?></div>
				</div>
				<div style="text-align: right; width:100%;height: 18px;">
					<div style="width: 88%; display: inline-block"> SILVER &nbsp; : &nbsp; </div>
					<div style="width: 10%; display: inline-block; margin-top: -2px"> <?php echo $metal_rate['silverrate_1gm'].'/'.'Gm'; ?> </div>
				</div>
			</div>
			
		 	<div style="width: 100%; text-align: center; margin-top:-67px; font-weight: bold; text-transform:uppercase;">
				<label>
					<?php echo $issue['receipt_type']; ?>
				</label>
			</div>
			
			<p></p>
           		
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
						
						<?php 
						if(sizeof($advance_adj_details)>0)
						{
						    $adjusted_amt=0;
						    foreach($advance_adj_details as $adj)
						    {
						        $adjusted_amt+=$adj['adjusted_amt'];
						    }
						}
						?>
						
						<?php if(sizeof($payment)>0){?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="pp" class="table text-center" style="width:35%;" align="left">
                                            <tr>
                                                <?php
										$total_amt=0;
										$due_amount=0;
										$paid_advance=0;
    										foreach($payment as $items)
											{												
    											$total_amt+=$items['payment_amount'];											
    											?>                                                
    											<th><?php if($items['payment_mode']=='NB' || $items['payment_mode']=='E-COM')												
    											{													
    											echo ($items['payment_mode']=='E-COM' ? 'Razorpay' : $items['payment_mode'])."<br><span style='font-size:10px !important;'>". ($items['received_date']!= null ? ' Dt ('. $items['received_date'] .')' : '') ."</span>";												
    											} 												
    											else												
    											{													
    											echo $items['payment_mode'];												
    											}?>
    											</th>                                                
    											<?php } ?>
    											<?php 
    											if($adjusted_amt>0)
    											{?>
    											<th>Adv Adj</th>
    											<?php }
    											?>
                                                <th>Total</th>
                                            </tr>
                                            <tbody>
                                                <tr>
                                                    <?php foreach($payment as $items){ ?>
                                                    <th><?php echo number_format($items['payment_amount'],2,'.','');?>
                                                    </th>
                                                    <?php } ?>
                                                    
                                                    <?php if($adjusted_amt>0){ ?>
                                                    <th><?php echo number_format($adjusted_amt,2,'.','');?>
                                                    </th>
                                                    <?php } ?>
                                                    
                                                    <th><?php echo number_format((float)($total_amt+$due_amount+$order_adv_pur+$paid_advance+$adjusted_amt),2,'.','');?>
                                                    </th>
                                                </tr>
                                            </tbody>
                                        </table><br>
                                    </div>
                                </div>
                            </div><br><br><br>
                            <?php }?>
						
				</div><p></p>
				<div class="row" style="text-transform:uppercase;margin-top:40%;">
					<label>Salesman sign</label>
					<label style="margin-left:20%;">customer sign</label>
					<label style="margin-left:30%;"><?php echo $comp_details['company_name']?></label>
				</div>
			</div>
 </div>
 </div><!-- /.box-body --> 
</div>
 </span>          
</body></html>
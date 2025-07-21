<html><head>
		<meta charset="utf-8">
		<title>Payment Report</title>
		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/billing_receipt.css">
		<!--	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/receipt_temp.css">-->
	<style >
		 .head
		 {
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
		.duplicate_copy #pp th, .duplicate_copy #pp td{
			font-size: 9px !important;
		}
		.return_dashed {
			width:700px !important;
		}
		.old_metal_dashed {
			width:700px !important;
		}

		.stones, .charges {
			font-style: italic;
		}
		.stones .stoneData, .charges .chargeData {
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
			<div style="width: 100%; text-transform:uppercase;;height:140px;">
			    
				<div style="display: inline-block; width: 50%; padding-left:0px;margin-top:20px;">
					<label><?php echo '<div class="addr_labels">Name</div><div class="addr_values">:&nbsp;&nbsp;'.'Mr./Ms.'.$issue['name']."</div>"; ?></label><br>
					<label><?php echo '<div class="addr_labels">Mobile</div><div class="addr_values">:&nbsp;&nbsp;'.$issue['mobile']."</div>"; ?></label><br>
					<label><?php echo ($issue['address1']!='' ? '<div class="addr_labels">Address</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($issue['address1']).','."</div><br>" :''); ?></label>
					<label><?php echo ($issue['address2']!='' ? '<div class="addr_labels"></div><div class="addr_values">&nbsp;&nbsp;&nbsp;'.strtoupper($issue['address2']).','."</div><br>" :''); ?></label>
					<label><?php echo ($issue['address3']!='' ? '<div class="addr_labels"></div><div class="addr_values">&nbsp;&nbsp;&nbsp;'.strtoupper($issue['address3']).','."</div><br>" :''); ?></label>
					<label><?php echo ($issue['village_name']!='' ? '<div class="addr_labels"></div><div class="addr_values">&nbsp;&nbsp;&nbsp;'.strtoupper($issue['village_name']).','."</div><br>" :''); ?></label>
					<label><?php echo ($issue['city_name']!='' ? '<div class="addr_labels">Place</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($issue['city_name']).($issue['pincode']!='' ? ' - '.$issue['pincode'].'.' :'')."</div><br>" :''); ?></label>
					<label><?php echo ($issue['cus_state']!='' ? '<div class="addr_labels">State</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($issue['cus_state']).','."</div><br>" :''); ?></label>
					<label><?php echo ($issue['cus_country']!='' ? '<div class="addr_labels">Country</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($issue['cus_country'])."</div><br>" :''); ?></label>
					<label><?php echo (isset($issue['pan_no']) && $issue['pan_no']!='' ? '<div class="addr_labels">PAN</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($issue['pan_no'])."</div><br>" :''); ?></label>
					<label><?php echo (isset($issue['gst_number']) && $issue['gst_number']!='' ? '<div class="addr_labels">GST IN</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($issue['gst_number'])."</div><br>" :''); ?></label>
                </div>
                
               
			    <div style="display: inline-block; width: 10%; padding-left:20px;"></div>
				<div style="width: 50%; text-align: right !important; display: inline-block; vertical-align: top;">
					<div style="text-align: left !important;width: 100%; display: inline-block;"> 
    						<label><?php echo ($comp_details['name']!='' ? '<div class="addr_brch_labels">Branch</div><div class="addr_brch_values">:&nbsp;&nbsp;'.strtoupper($comp_details['name']).','."</div><br>" :''); ?></label>
    						<label><?php echo ($comp_details['address1']!='' ? '<div class="addr_brch_labels">Address</div><div class="addr_brch_values">:&nbsp;&nbsp;'.strtoupper($comp_details['address1']).','."</div><br>" :''); ?></label>
    						<label><?php echo ($comp_details['address2']!='' ? '<div class="addr_brch_labels"></div><div class="addr_brch_values">&nbsp;&nbsp;&nbsp;'.strtoupper($comp_details['address2']).','."</div><br>" :''); ?></label>
    						<label><?php echo ($comp_details['city']!='' ? '<div class="addr_brch_labels">city</div><div class="addr_brch_values">:&nbsp;&nbsp;'.strtoupper($comp_details['city']).($comp_details['pincode']!='' ? ' - '.$comp_details['pincode'].'.' :'')."</div>" :''); ?><br></label>
    						<label><?php echo ($comp_details['state']!='' ? '<div class="addr_brch_labels">State</div><div class="addr_brch_values">:&nbsp;&nbsp;'.strtoupper($comp_details['state'].($comp_details['state_code']!='' ? '-'.$comp_details['state_code']  :'')).'.'."</div><br>" :''); ?></label>
    						<label><br><?php echo '<div class="addr_brch_labels">Place of supply</div><div class="addr_brch_values">:&nbsp;&nbsp;'.strtoupper($comp_details['state'].($comp_details['state_code']!='' ? '-'.$comp_details['state_code']  :'')).'.'."</div><br>"; ?></label>
    						<label><div class="addr_brch_labels">Reverse Charges</div><div class="addr_brch_values">:&nbsp;&nbsp;NO</div><br></label>
					</div>
				</div>
				
			</div>
			
			<div style="width: 100%; text-transform:uppercase;margin-top:-20px;">
			    <div style="display: inline-block; width: 30%; padding-left:0px;">
    					<label><?php echo '<div class="rate_labels" style="height: 18px;">22 KT GOLD</div><div class="addr_values" style="height: 18px;">:&nbsp;&nbsp;'.number_format($metal_rate['goldrate_22ct'],2,'.','').'/'.'Gm'."</div>"; ?></label><br>
    					<label><?php echo '<div class="rate_labels" style="height: 18px;">SILVER</div><div class="addr_values" style="height: 18px;">:&nbsp;&nbsp;'.number_format($metal_rate['silverrate_1gm'],2,'.','').'/'.'Gm'."</div>"; ?></label><br>
    		    </div>
    		    <div style="display: inline-block; width: 30%;margin-top:-30px;padding-left:30px;font-weight: bold;">
    		        <label>
    					<?php echo $issue['receipt_type']; ?>
    				</label>
    		    </div>
    		    <div style="width: 40%; text-align: right !important; display: inline-block; vertical-align: top;padding-left:80px;">
					<div style="text-align: left !important;width: 100%; display: inline-block;"> 
    						<label><?php echo '<div class="addr_brch_labels" style="height: 18px;">Receipt Date</div><div class="addr_brch_values" style="height: 18px;font-weight: bold;">:&nbsp;&nbsp;'.$issue['date_add']."</div><br>"; ?></label>
        						<label><?php echo '<div class="addr_brch_labels" style="height: 18px;">Receipt No</div><div class="addr_brch_values" style="height: 18px;">:&nbsp;&nbsp;'.$issue['bill_no']."</div><br>"; ?>
        						</label>
    				</div>
				</div>
			</div>
			
			
           		
<div  class="content-wrapper" style="margin-top:-30px;">
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
    												<th colspan="5" style="text-transform:uppercase;">Description</th>
    												<th>Amount</th>
    											</tr>
    										<!--</thead>
    										<tbody>-->
    											<tr>
    											    <?php if($issue['type']==2){?>
    												    <td colspan="5" style="text-transform:uppercase;"><?php echo 'Received with thanks from Mr./Ms.'.$issue['name'].' Towards Advance Bill No : '.$issue['bill_no'];?></td>
    												<?php }else if($issue['type']==1){?>
    												    <?php 
    												    if($issue['issue_type']==3)
    												    {?>
    												        <td colspan="5" style="text-transform:uppercase;"><?php echo 'Refund to Mr./Ms.'.$issue['name'];?></td>
    												    <?php }else if($issue['issue_type']==1)
    												    {?>
    												        <td colspan="5" style="text-transform:uppercase;"><?php echo 'Petty Cash Issue';?></td>
    												    <?php }
    												    ?>
    												    
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
    							 <?php 
        						if($issue['narration']!='')
        						{?>
        						    <p><b>REMARKS :- <?php echo $issue['narration'];?></b></p>
        						<?php }	?>
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
				<div style="margin-top: 3px; margin-bottom: 3px">
					<div><span style="font-weight: bold;">Amount in Words</span> : <span >Rupees <?php echo $this->ret_billing_model->no_to_words($issue['amount']); ?> Only</span></div>
				</div><br>
				
				<?php if(sizeof($receipt_adv_details)>0){
                    $tot_adv=0;
                    $adj_amt=0;
                    
                    foreach($receipt_adv_details as $adv)
                    {
                      $tot_adv=$adv['receipt_amt'];  
                      $adj_amt=$adv['utilized_amt'];

                    ?>
                     <div>
                        <table id="pp" class="table text-center"style="width:85%" >
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
                              <td><?php  echo $adv['bill_no'];?></td>
                              <td ><?php echo $adv['bill_date'];?></td>
                              <td ><?php echo number_format($adv['receipt_amt'],2,'.','' );?></td>
                              <td ><?php echo number_format($adv['utilized_amt'],2,'.','' );?></td>
                              <td ><?php echo number_format($adv['refund_amount'],2,'.','' );?></td>
                              <td ><?php echo number_format($adv['balance_amount'],2,'.','' );?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div><br>
               
                <?php }}?>
                
	
							
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
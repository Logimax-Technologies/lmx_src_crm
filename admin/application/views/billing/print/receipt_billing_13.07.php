<html><head>
		<meta charset="utf-8">
		<title>Billing Receipt</title>
		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/billing_receipt.css">
		<style >
		 .head
		 {
			 color: black;
			 font-size: 30px;
		 }
         </style>
</head><body style="margin-top:12%;">
<span class="PDFReceipt" >
            <!--<div>
                <img alt="" src="<?php echo base_url();?>assets/img/receipt_logo.png">
            </div>-->
            <div class="" align="left">
                <?php if($billing['cus_type']==1){?>
				<label><?php echo 'Shri.'.$billing['customer_name'].' - '.$billing['mobile']; ?></label><br>	
				<label><?php echo ($billing['village_name']!='' ?$billing['village_name'].',' :''); ?></label>
				<label><?php echo ($billing['pan_number']!='' ? 'PAN NO : '.$billing['pan_number'] :''); ?></label>
				<?php }?>
				
                <?php if($billing['cus_type']==2){?>
                    <label><?php echo ($billing['cmp_user_name']!='' ?$billing['cmp_user_name'] :$billing['customer_name']); ?></label><br>
                    <?php if($billing['address1']!=''){?>
                    <label><?php echo ($billing['address1']!='' ? $billing['address1'] :''); ?></label><br>
                    <?php }?>
                    <?php if($billing['address2']!='' || $billing['pincode']!=''){?>
                    <label><?php echo ($billing['address2']!='' ?$billing['address2'].'.'.($billing['pincode']!='' ? ' - '.$billing['pincode'] :'') :''); ?></label><br>
                    <?php }?>
                    <label><?php echo ($billing['gst_number']!='' ? 'GSTIN NO : '.$billing['gst_number'] :''); ?></label><br>
                    <label><?php echo ($billing['pan_number']!='' ? 'PAN NO : '.$billing['pan_number'] :''); ?></label>
                <?php }?>
                
			</div>
			 <div  align="center">
			     	<label style="margin-left:-20%;"><?php echo ($billing['is_trail']==0 ? 'TAX INVOICE' :'TRAIL INVOICE');?></label>
			 </div>
			<p></p>
			<div class="row">
				<table class="meta" style="width:30%;" align="left">
					<tr>
						<td style="font-size:10px !important;text-transform:uppercase;">Gold&nbsp;&nbsp; : <?php echo number_format(($billing['goldrate_22ct']>0 ? $billing['goldrate_22ct']:$metal_rate['goldrate_22ct']),2,'.','').'/'.'Gm'; ?></td>
					</tr>
					<tr style="padding-top:-0.5em;">
						<td style="text-transform:uppercase;">Silver &nbsp;: <?php echo ($billing['silverrate_1gm']>0 ?$billing['silverrate_1gm'] :$metal_rate['silverrate_1gm']).'/'.'Gm'; ?></td>
					</tr>
				</table>	
				<table class="meta" style="width:30%;" align="center">
					<tr>
						<td style="text-transform:uppercase;"><?php echo ($billing['bill_type']==1 ? 'Sales Bill': ($billing['bill_type']==2 ? 'Sales And Purchase':($billing['bill_type']==3 ? 'Sales Return/Credit note' :($billing['bill_type']==4 ? ' urd Purchase ':($billing['bill_type']==5 ? 'Order Advance Receipt' :($billing['bill_type']==6 ? 'Advance Receipt' :($billing['bill_type']==7 ? 'Sales Return Receipt':($billing['bill_type']==9 ? 'order delivery' :($billing['bill_type']==10 ? 'Chit PreClose Receipt':'Credit Collection Receipt'))))))))).($billing['bill_status']==2 ? '- Cancelled' : ($billing['print_taken']>0 ? ' - Copy' :''));?></td>
					</tr>
				</table>	
				<table class="meta" style="width:35%;margin-top:-10%;text-transform:uppercase;" align="right">
				    <tr>
						<td><span>Branch</span></td>
						<td><span><?php echo ':  '.$billing['branch_name'];?></span></td>
					</tr><br><br>
				     <tr>
						<td><span>Delivery Location</span></td>
						<td><span><?php echo ':  '.$billing['delivery_location'];?></span></td>
					</tr><br><br>
					<tr>
						<td><span>State</span></td>
						<td><span><?php echo ':  '.$comp_details['state'].' -'.$comp_details['state_code'];?></span></td>
					</tr><br><br>
					
					<tr>
						<td><span><?php echo ($billing['is_trail']==0 ? 'GST Bill No' :'Trail Bill No')?></span></td>
						<td><span><?php echo ':  '.($billing['branch_code']!='' ? $billing['branch_code'].'/':'').($billing['fin_year_code']!='' ?$billing['fin_year_code'].'/' :'').($billing['bill_type']==1 || $billing['bill_type']==2 || $billing['bill_type']==3 || $billing['bill_type']==7 || $billing['bill_type']==9 ? 'SA/':($billing['bill_type']==4 ? 'PUR/' :$billing['bill_type']==5 ? 'OD/':'')).$billing['bill_no']; ?></span></td>
					</tr>
					<tr>
						<td><span>Date</span></td>
						<td><span><?php echo ':  '.$billing['bill_date'];?></span></td>
					</tr>
				</table>
			</div><p></p>
<div  class="content-wrapper">
 <div class="box">
  <div class="box-body">
 			<div  class="container-fluid">
				<div id="printable">
					<?php if(sizeof($est_other_item['item_details'])>0){?>
				        <hr class="header_dashed">
							<div class="col-xs-12">
								<div class="table-responsive">
								<table id="pp" class="table text-center" >
										<thead style="text-transform:uppercase;font-size:10px;">
											<tr>
												<td width="5%;">S.No</td>
												<td width="10%;">HSN</td>
												<td width="15%;">Description</td>
												<td width="10%;">PCS</td>
												<td width="10%;">Gwt(g)</td>
												<td width="10%">Nwt(g)</td>
												<td width="5%;">V.A</td>
												<td width="5%;">MC</td>
												<td width="5%;">Disc</td>
												<td width="5%;">Amount</td>
											</tr>
										</thead>
											<tr>
                        						<td><hr class="item_dashed"></td>
                        					</tr>
										
										<!--<tbody>-->
										<?php
										$i=1; 
										$pieces=0;
										$gross_wt=0;
										$net_wt=0;
										$discount=0;
										$taxable_amt=0;
										$tot_tax=0;
										$sales_cost=0;
										$tot_sales_amt=0;
										$tot_tax_per=0;
										$total_cgst=0;
										$total_sgst=0;
										$total_igst=0;
										foreach($est_other_item['item_details'] as $items)
											{
											    $mc=0;
											    $wastge_wt=0;
												$pieces         +=$items['piece'];
												$gross_wt       +=$items['gross_wt'];
												$net_wt         +=$items['net_wt'];
												$discount       +=$items['discount'];
												$tot_tax        +=$items['item_total_tax'];
												$sales_cost     +=$items['item_cost'];
												$total_cgst     +=$items['total_cgst'];
												$total_sgst     +=$items['total_sgst'];
												$total_igst     +=$items['total_igst'];
												$taxable_amt    +=$items['item_cost']-$items['item_total_tax'];
												$amt_in_words   = $this->ret_billing_model->no_to_words($billing['tot_bill_amount']);
												$tot_sales_amt  =number_format($sales_cost,2,'.','');
												$item_taxable   =number_format((float)$items['item_cost']-$items['item_total_tax'],2,'.','');
												$tax_percentage =number_format(($items['item_total_tax']*100)/$item_taxable,'2','.','');
												$tot_tax_per    +=$tax_percentage;
												
												if($items['calculation_based_on']==0)
												{
												    $wastge_wt=($items['gross_wt']*($items['wastage_percent']/100));
													$mc = ($items['mc_type']== 1 ? ($items['mc_value'] * $items['gross_wt'] ) : ($items['mc_value'] *$items['piece']));

												}else if($items['calculation_based_on']==1)
												{
												    $wastge_wt=($items['net_wt']*($items['wastage_percent']/100));
												     $mc = ($items['mc_type']== 1 ? ($items['mc_value'] * $items['net_wt'] ) : ( $items['mc_value'] * $items['piece'] ));
												}else if($items['calculation_based_on']==2)
												{
												    $wastge_wt=($items['net_wt']*($items['wastage_percent']/100));
												     $mc = ($items['mc_type']== 1 ? ($items['mc_value'] * $items['gross_wt'] ) : ( $items['mc_value'] * $items['piece'] ));
												}
											?>
											
											<tr>
											<td><?php echo $i;?></td>
											<td><?php echo $items['hsn_code'];?></td>
											<td style="font-size:10px !important;"><?php echo $items['product_name'];?></td>
											<td><?php echo $items['piece'];?></td>
											<td><?php echo $items['gross_wt'];?></td>
											<td><?php echo $items['net_wt'];?></td>
											
											<td><?php echo number_format($wastge_wt,3,'.','');?></td>
											<td><?php echo $mc;?></td>
											<td><?php echo $items['bill_discount'];?></td>
											<td style="text-align:right;"><?php echo $item_taxable;?></td>
											</tr>
										<?php $i++;}?>
									<!--</tbody> -->
											<tr>
                        						<td><hr class="item_dashed"></td>
                        					</tr>
											<tr class="total" >
												<td></td>
												<td></td>
												<td>Total</td>
												<td><?php echo $pieces;?></td>
												<td><?php echo number_format($gross_wt,3,'.','');?></td>
												<td><?php echo number_format($net_wt,3,'.','');?></td>
												<td></td>
												<td></td>
												<td></td>
												<td style="text-align:right;"><?php echo number_format((float)$taxable_amt,2,'.','');?></td>
											</tr>
									    	<tr>
                        						<td><hr class="item_dashed"></td>
                        					</tr>
										<?php if($billing['cus_state']==$comp_details['id_state'] || ($billing['cus_state']=='')){?>
											<tr>
												<td colspan="7"></td>
												<td width="">SGST</td>
												<td width="1%;"><?php echo ($est_other_item['item_details'][0]['tax_percentage']/2).'%'?></td>
												<td style="text-align:right;"><?php echo $total_sgst;?></td>
											</tr>
											<tr>
												<td colspan="7"></td>
												<td width="">CGST</td>
												<td width="1%;"><?php echo ($est_other_item['item_details'][0]['tax_percentage']/2).'%'?></td>
										    	<td style="text-align:right;"><?php echo $total_cgst;?></td>
											</tr>
											
											<?php }else{?>
												<tr>
												<td colspan="7"></td>
												<td width="">IGST</td>
												<td width="1%;"><?php echo ($est_other_item['item_details'][0]['tax_percentage']).'%'?></td>
												<td style="text-align:right;"><?php echo $total_igst;?></td>
											</tr>
											<?php }?>
											
											<?php if($billing['handling_charges']>0){?>
											<tr>
												<td colspan="7" ></td>
												<td width="">H.C</td>
												<td width="1%;">Rs.</td>
												<td style="text-align:right;"><?php echo $billing['handling_charges'];?></td>
											</tr>
											<?php }?>
											<tr>
												<td colspan="7" ></td>
												<td width="">Total</td>
												<td width="1%;">Rs.</td>
												<td style="text-align:right;"><?php echo $tot_sales_amt+$billing['handling_charges'];?></td>
											</tr>
											<?php if($billing['tcs_tax_amt']>0){?>
											<tr>
												<td colspan="7" ></td>
												<td width="">TCS %</td>
												<td ><?php echo $billing['tcs_tax_per'];?></td>
												<td style="text-align:right;"><?php echo $billing['tcs_tax_amt'];?></td>
											</tr>
											<tr>
												<td colspan="7" ></td>
												<td width="">Net Amt</td>
												<td width="1%;">Rs.</td>
												<td style="text-align:right;"><?php echo $tot_sales_amt+$billing['handling_charges']+$billing['tcs_tax_amt'];?></td>
											</tr>
											<?php }?>
									</table>
								</div>	
							 </div>	
						</div><br>
						<?php }?>
						
					
						<?php if(sizeof($est_other_item['return_details'])>0){?>
							<br>
							<div  class="row">
							<div class="col-xs-12">
							
								<div class="table-responsive">
								<table id="pp" class="table text-center">
									<!--	<thead> -->
											<tr style="text-transform:uppercase;">
												<td>S.No</td>
												<td>HSN Code</td>
												<td>Description</td>
												<td>PCS</td>
												<td>Gwt(g)</td>
												<td>Nwt(g)</td>
												<td>V.A</td>
												<td>MC</td>
												<td>Amount</td>
											</tr>
											<tr>
                        						<td><hr class="return_dashed"></td>
                        					</tr>
										<!--</thead>
										<tbody>-->
										<?php
										$i=1; 
										$pieces=0;
										$gross_wt=0;
										$net_wt=0;
										$return_item_cost=0;
										$discount=0;
										$tax_percentage=0;
										$total_cgst=0;
										$total_igst=0;
										$total_sgst=0;
										$total_return=0;
										foreach($est_other_item['return_details'] as $items)
											{
												$pieces             +=$items['piece'];
												$gross_wt           +=$items['gross_wt'];
												$net_wt             +=$items['net_wt'];
												$discount           +=$items['discount'];
												$total_return       +=$items['item_cost'];
												$total_sgst         +=$items['total_sgst'];
												$total_igst         +=$items['total_igst'];
												$total_cgst         +=$items['total_cgst'];
												$return_item_cost   +=$items['item_cost']-$items['item_total_tax'];
												$tax_percentage     =$items['tax_percentage']/2;
												
												if($items['calculation_based_on']==0)
												{
												    $wastge_wt=($items['gross_wt']*($items['wastage_percent']/100));

												}else if($items['calculation_based_on']==1)
												{
												    $wastge_wt=($items['net_wt']*($items['wastage_percent']/100));
												}else if($items['calculation_based_on']==2)
												{
												    $wastge_wt=($items['net_wt']*($items['wastage_percent']/100));
												}
											?>
											<tr>
												<td colspan="9" style="font-size:12px !important;text-transform:uppercase;">Refer sale  bill no : <?php echo $items['ref_bill_no'].' Dt '.$items['ref_bill_date']?> </td>
											</tr>
											<tr>
											<td><?php echo $i;?></td>
											<td><?php echo $items['hsn_code'];?></td>
											<td style="font-size:10px;"><?php echo $items['product_name'];?></td>
											<td><?php echo $items['piece'];?></td>
											<td><?php echo $items['gross_wt'];?></td>
											<td><?php echo $items['net_wt'];?></td>
											<td><?php echo number_format($wastge_wt,3,'.','');?></td>
											<td><?php echo $items['mc_value'];?></td>
											<td><?php echo number_format($items['item_cost']-$items['item_total_tax'],2,'.','');?></td>
											</tr>
										<?php $i++;}?>
											<tr>
                        						<td><hr class="return_dashed"></td>
                        					</tr>
									<!--</tbody> -->
										<!--<tfoot>-->
											<tr>
												<td>Total</td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td><?php echo number_format((float)$return_item_cost,'2','.','');?></td>
											</tr>
											<tr>
                        						<td><hr class="return_dashed"></td>
                        					</tr>
										<!--</tfoot>-->
											<?php if($discount>0){?>
											<tr>
												<td colspan="6"></td>
												<td>Discount</td>
												<td>Rs.</td>
												<td><?php echo $discount;?></td>
											</tr>
											<?php }?>
											<?php if($total_cgst>0){?>
											<tr>
												<td colspan="6"></td>
												<td>SGST</td>
												<td><?php echo $tax_percentage.'%'?></td>
												<td><?php echo $total_sgst;?></td>
											</tr>
											<tr>
												<td colspan="6"></td>
												<td>CGST</td>
												<td><?php echo $tax_percentage.'%'?></td>
												<td><?php echo $total_cgst;?></td>
											</tr>
											<?php }?>
											<?php if($total_igst>0){?>
											<tr>
												<td colspan="6"></td>
												<td>IGST</td>
												<td>Rs.</td>
												<td><?php echo $total_igst;?></td>
											</tr>
											<?php }?>
											<tr>
												<td colspan="6"></td>
												<td>TOTAL</td>
												<td>Rs.</td>
												<td><?php echo number_format($total_return,2,'.','');?></td>
											</tr>
									</table>
									<br>
								</div>	
							 </div>	
						</div>
						<?php }?>
						
							<?php if($billing['bill_type']==5){?>
							<?php if(sizeof($est_other_item['advance_details'])>0){
								$amount=0;
								$order_no='';
								$order_adv_pur=0;
								foreach($est_other_item['advance_details'] as $item)
								{
									    $order_no=$item['order_no'];
										if($item['advance_type']==1)
										{	
											if($item['store_as']==1)
											{
												$amount +=$item['advance_amount'];
											}
											else
											{
												$amount +=$item['advance_weight']*$item['rate_per_gram'];
											}
										}
										
								
								}
							}
							?>

							<?php ?>
							<div  class="row">
								<div align="center">
									<label><b>Order No.<?php echo $order_no;?></b></label>
								</div>
						    	<tr>
            						<td><hr class="order_item_dashed"></td>
            					</tr>
							<div class="col-xs-12">
								<div class="table-responsive">
								<table id="pp" class="table text-center">
									<!--	<thead> -->
											<tr>
												<td width="5%;">S.No</td>
												<td width="5%;">HSN</td>
												<td width="25%;">Description</td>
												<td width="20%;">Design</td>
												<td width="5%;">PCS</td>
												<td width="15%;">Gwt(g)</td>
											</tr>
											<tr>
                        						<td><hr class="order_tr_item_dashed"></td>
                        					</tr>
										<!--</thead>
										<tbody>-->
										    <?php 
										    $i=1; 
										    $weight=0;
										    foreach($est_other_item['order_details'] as $items){
										    $weight+=$items['weight'];
										    ?>
										        <tr>
										            <td><?php echo $i;?></td>
										            <td></td>
										            <td><?php echo $items['product_name'];?></td>
										            <td><?php echo $items['design_name'];?></td>
										            <td><?php echo $items['totalitems'];?></td>
										            <td><?php echo $items['weight'];?></td>
										        </tr>
										    <?php $i++;}
										    ?>
											
									<!--</tbody> -->
									    <tr>
                                            <td><hr class="order_tr_item_dashed"></td>
                                        </tr>
                                        
                                        <tr>
								            <td>TOTAL</td>
								            <td></td>
								            <td></td>
								            <td></td>
								            <td></td>
								            <td><?php echo number_format($weight,3,'.','');?></td>
										 </tr>
                                        
                                        <tr>
                                            <td><hr class="order_tr_item_dashed"></td>
                                        </tr>
                                        <?php if($amount>0){?>
                                        <tr>
												<td colspan="4"><?php echo 'Received with thanks from Mr./Ms.'.$billing['customer_name'].' Towards Order  No : '.$order_no.'';?></td>
												<td colspan="2"><?php echo 'Rs. '.$amount;?></td>
										</tr>
										<?php }?>
									</table><br>	
								</div>	
							 </div>	
						</div><br>
						<?php }?>

						<?php if(sizeof($est_other_item['old_matel_details'])>0){?>
						<div  class="row">
							<span>PURCHASE NO : <?php echo $billing['pur_ref_no'];?></span>
						    <hr class="old_metal_header_dashed">
							<div class="col-xs-12">
								<div class="table-responsive">

								<table id="pp" class="table text-center">
									<!--	<thead> -->
											<tr style="text-transform:uppercase;">
												<td>S.No</td>
												<td>Metal</td>
												<td>GROSS WT(g)</td>
												<td>Nwt(g)</td>
												<td>V.A</td>
												<td>Rate</td>
												<td>Amount</td>
											</tr>
											<tr>
                        						<td><hr class="old_metal_dashed"></td>
                        					</tr>
										<!--</thead>
										<tbody>-->
										<?php
										$i=1; 
										$total_amt=0;
										$pieces=0;
										$gross_wt=0;
										$net_wt=0;
										foreach($est_other_item['old_matel_details'] as $items)
											{
												$pur_total_amt+=$items['amount'];
												$gross_wt+=$items['gross_wt'];
												$net_wt+=($items['net_wt']);
											?>
											
											<tr>
											<td><?php echo $i;?></td>
											<td><?php echo ($items['metal_type']==1 ?'Gold' :'Silver');?></td>
											<td><?php echo $items['gross_wt'];?></td>
											<td><?php echo ($items['net_wt']);?></td>
											<td><?php echo $items['wast_wt'];?></td>
											<td><?php echo $items['rate_per_gram'];?></td>
											<td><?php echo $items['amount'];?></td>
											</tr>
										<?php $i++;}?>
									<!--</tbody> -->
											<tr>
                        						<td><hr class="old_metal_dashed"></td>
                        					</tr>
											<tr>
												<td>Total</td>
												<td></td>
												<td><?php echo $gross_wt;?></td>
												<td><?php echo $net_wt;?></td>
												<td></td>
												<td></td>
												<td><?php echo number_format((float)$pur_total_amt,2,'.','');?></td>
											</tr>
											<tr>
                        						<td><hr class="old_metal_dashed"></td>
                        					</tr>
										
											<!--<?php if($total_return>0){?>
											<tr>
												<td colspan="4"></td>
												<td width="">Sales Return</td>
												<td>Rs.</td>
												<td><?php echo number_format($total_return,2,'.','');?></td>
											</tr>
											<?php }?>-->
						
											<?php if($tot_sales_amt>0){?>
											<tr>
												<td colspan="4"></td>
												<td width="">Net Amount</td>
												<td>Rs.</td>
												<td><?php echo number_format($tot_sales_amt-$total_return-$pur_total_amt,2,'.','');?></td>
											</tr>
											<?php }?>
									</table><br>	
								</div>	
							 </div>	
						</div><br>
						<?php }?>

						<?php if($billing['bill_type']==8){?>
							<div  class="row">
								<div align="center" style="text-transform:uppercase;">
									<label>Credit No : <?php echo $billing['ref_bill_id'];?></label>
								</div>
								
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
												<td colspan="5"><?php echo 'Received with thanks from Mr./Ms.'.$billing['customer_name'].' Towards Credit Bill No : '.$billing['ref_bill_no'].'/ Ref No : '.$billing['ref_bill_id'];?></td>
												<td colspan="5"><?php echo $billing['due_amount'];?></td>
											</tr>
									<!--</tbody> -->
											<tr>
												<td colspan="7"><hr style="border-bottom:0.5pt;"></td>
											</tr>
									</table><br>	
								</div>	
							 </div>	
						</div><br>
						<?php }?>

					
							<div  class="row">
							    	<?php 
							    	    if($billing['bill_type']!=8)
    							    	{
    							    	    $due_amount=number_format(($billing['tot_bill_amount']-$billing['tot_amt_received']),2,'.','');
    							    	}
							    	    if(sizeof($payment['pay_details'])>0){
							    	    $cash_amt=0;
							    	    $card_amt=0;
							    	    $net_banking_amt=0;
							    	    $due_amount=0;
							    	    $gift_amount=0;
							    	    $chit_amount=0;
							    	    $total_amt=0;
							    	    $adv_adj=0;
							    	    if($billing['bill_type']==8)
							    	    {
							    	        $due_amount=number_format(($billing['tot_bill_amount']-($billing['tot_adv_received']+$billing['tot_paid_amt']+$pur_total_amt)),2,'.','');
							    	    }else{
							    	        if($billing['is_credit']==1)
							    	        {
							    	            $due_amount=number_format(($billing['tot_bill_amount']-$billing['tot_amt_received']),2,'.','');
							    	        }
							    	        else{
							    	             $due_amount=number_format(($billing['tot_bill_amount']-$billing['tot_amt_received']),2,'.','');
							    	        }
							    	    }
							    	    
							    	    foreach($payment['pay_details'] as $items)
											{
											    $total_amt+=$items['payment_amount'];
											    
											    if($items['payment_mode']=='Cash')
											    {
											        $cash_amt+=$items['payment_amount'];
											    }
											    if($items['payment_mode']=='DC' || $items['payment_mode']=='CC')
											    {
											        $card_amt+=$items['payment_amount'];
											    }
											    if($items['payment_mode']=='NB')
											    {
											        $net_banking_amt+=$items['payment_amount'];
											    }
											}
							    	}?>
							   
							   <?php if(sizeof($est_other_item['chit_details'])>0){
							   $chit_adj=0;
							   $bouns_amt=0;
							   foreach($est_other_item['chit_details'] as $chit)
							   {
							       if(($chit['paid_installments']==$chit['total_installments']) && ($chit['scheme_type']==0) && $chit['firstPayDisc_value']>0)
							       {
							           $bouns_amt+=($chit['paid_installments']*$chit['firstPayDisc_value']);
							       }
							       $chit_adj +=$chit['utilized_amt'];
							   }
							   $chit_adj=$chit_adj-$bouns_amt;
							   ?>
							   <?php }?>
							   
							   <?php if(sizeof($est_other_item['voucher_details'])>0){
							   foreach($est_other_item['voucher_details'] as $voc)
							   {
							       $gift_amount +=$voc['gift_voucher_amt'];
							   }
							   ?>
							   <?php }?>
							   
							   <?php if(sizeof($est_other_item['order_adj'])>0){
							   $ord_adj_amt=0;
							   $chit_adj_wt=0;
							   foreach($est_other_item['order_adj'] as $ord)
							   {
							       
							       if($ord['received_weight']>0)
							       {
							           $chit_adj_wt +=($ord['received_weight']*($ord['rate_per_gram']));
							       }
							       $ord_adj_amt +=$ord['received_amount'];
							   }
							   $ord_adj_amt=$ord_adj_amt+$chit_adj_wt;
							   
							   ?>
							   
							   <?php }?>
							    	
							    <div class="col-md-12">
							        	<table id="pp" class="table text-center" style="width:45%;">
							        	    <tr>
							        	        <td>Cash</td>
							        	       <?php if($card_amt>0){?>
							        	        <td>Card</td>
							        	        <?php }?>
							        	       
							        	        <td>NB</td>
							        	       
							        	        <?php if($due_amount>0){?>
							        	        <td>Due Amount</td>
							        	        <?php }?>
							        	        
							        	        <?php if($adv_adj>0){?>
							        	        <td>Advance Adj</td>
							        	        <?php }?>
							        	        <?php if($chit_adj>0){?>
							        	        <td>Chit Adj</td>
							        	        <?php }?>
							        	        
							        	        <?php if($bouns_amt>0){?>
							        	        <td>Bonus</td>
							        	        <?php }?>
							        	        
							        	        <?php if($ord_adj_amt>0 || $billing['adv_adj_amt']>0){?>
							        	        <td>Adv Adj</td>
							        	        <?php }?>
							        	        <?php if($gift_amount>0){?>
							        	        <td>Gift Utilized</td>
							        	        <?php }?>
							        	        
							        	        <?php if($billing['credit_disc_amt']>0){?>
							        	        <td>Discount</td>
							        	        <?php }?>
							        	        
							        	        <td>Round Off</td>
							        	        <td>Total</td>
							        	    </tr>
							        	    <tbody>
							        	        <tr>
							        	            <td><?php echo number_format($cash_amt,2,'.','');?></td>
							        	            
							        	            <?php if($card_amt>0){?>
							        	            <td><?php echo number_format($card_amt,2,'.','');?></td>
							        	            <?php }?>
							        	            
							        	           
							        	            <td><?php echo number_format($net_banking_amt,2,'.','');?></td>
							        	           
							        	            
							        	             <?php if($due_amount>0){?>
							        	            <td><?php echo number_format($due_amount,2,'.','');?></td>
							        	            <?php }?>
							        	            
							        	             
							        	            
							        	            <?php if($adv_adj>0){?>
							        	            <td><?php echo number_format($adv_adj,2,'.','');?></td>
							        	            <?php }?>
							        	            
							        	            <?php if($chit_adj>0){?>
							        	             <td><?php echo number_format($chit_adj,2,'.','');?></td>
							        	            <?php }?>
							        	            
							        	            <?php if($bouns_amt>0){?>
							        	             <td><?php echo number_format($bouns_amt,2,'.','');?></td>
							        	            <?php }?>
							        	            
							        	            <?php if($ord_adj_amt>0 || $billing['adv_adj_amt']>0){?>
							        	             <td><?php echo number_format($ord_adj_amt+$billing['adv_adj_amt'],2,'.','');?></td>
							        	            <?php }?>
							        	            
							        	            <?php if($gift_amount>0){?>
							        	             <td><?php echo number_format($gift_amount,2,'.','');?></td>
							        	            <?php }?>
							        	            
							        	            <?php if($billing['credit_disc_amt']>0){?>
    							        	           <td><?php echo number_format($billing['credit_disc_amt'],2,'.','');?></td>
    							        	        <?php }?>
							        	          
							        	            <td><?php echo number_format($billing['round_off_amt'],2,'.','');?></td>
							        	            <?php if($billing['tot_amt_received']==0 && $due_amount>0){?>
							        	            <td><?php echo number_format(($billing['tot_bill_amount']),2,'.','');?></td>
							        	            <?php }else{?>
							        	            <td><?php echo number_format(($total_amt+$chit_adj+$bouns_amt+$ord_adj_amt+$gift_amount+$billing['adv_adj_amt']+$billing['credit_disc_amt']+($due_amount>0 ? $due_amount:0)),2,'.','');?></td>
							        	            <?php }?>
							        	        </tr>
							        	    </tbody>
							        	</table>
							    </div>
							 </div><br>
							 
							 <?php if(sizeof($est_other_item['chit_details'])>0){?>
                                <div class="col-xs-6">
                                    <div class="table-responsive">
                                        <table id="pp" class="table text-center">
                                            <tr>
                                                <td width="2%;">S.No</td>
                                                <td width="5%;">Ref No</td>
                                                <td width="25%;">Amount</td>
                                            </tr>
                                            <tr>
												<td><hr class="item_dashed" style="width:450% !important;"></td>
											</tr>
                                            <?php
                                            $i=1; 
                                            foreach($est_other_item['chit_details'] as $chit)
                                            {?>
                                            <tr>
                                            <td><?php echo $i;?></td>
                                            <td><?php echo $chit['scheme_acc_number'];?></td>
                                            <td><?php echo $chit['utilized_amt'];?></td>
                                            </tr>
                                            <?php $i++;}?>
                                        </table>
                                    </div>
                                </div>
							 <?php }?>
							 
							 	  <?php if(sizeof($est_other_item['order_adj'])>0){?>
                            <div class="col-xs-6">
                                <div class="table-responsive">
                                    <table id="pp" class="table text-center" style="width:45%;">
                                        <tr>
                                            <td>Date</td>
                                            <td>Amount</td>
                                            <td>Rate</td>
                                            <td>Weight</td>
                                        </tr>
                                        <tr>
											<td><hr class="item_dashed" style="width:450% !important;"></td>
										</tr>
						     <?php  
						      $i=1;
						      $adv_paid_amount=0;
						      $adv_paid_weight=0;
							   foreach($est_other_item['order_adj'] as $ord)
							   {
							   $adv_paid_amount+=($ord['store_as']==1 ? $ord['advance_amount'] :( $ord['received_weight']* $ord['rate_per_gram']));
							   $adv_paid_weight+=($ord['store_as']==1 ? ($ord['advance_amount']/$ord['rate_per_gram']):$ord['received_weight']);
							   ?>
							       <tr>
                                    <td><?php echo $ord['bill_date'];?></td>
                                    <td><?php echo number_format(($ord['store_as']==1 ? $ord['advance_amount'] :( $ord['received_weight']* $ord['rate_per_gram'])),2,'.','');?></td>
                                    <td><?php echo $ord['rate_per_gram'];?></td>
                                    <td><?php echo number_format(($ord['store_as']==1 ? ($ord['advance_amount']/$ord['rate_per_gram']):$ord['received_weight']),3,'.','');?></td>
							       </tr>
							       
							   <?php $i++; } ?>
    					             <tr>
    									<td><hr class="item_dashed" style="width:450% !important;"></td>
    								</tr>
    								<tr>
    								    <td>Total</td>
    								    <td><?php echo number_format($adv_paid_amount,2,'.','')?></td>
    								    <td></td>
    								    <td><?php echo number_format($adv_paid_weight,3,'.','')?></td>
    								</tr>
    							   </table>
        						</div>
            				</div>
						   <?php }?>
							 
							 <?php if($billing['gift_issue_amount']>0){?>
							    <span>Gift Voucher Worth Rs. <?php echo ' '.$billing['gift_issue_amount'].' '?>Valid Till <?php echo $billing['valid_to'].'. Voucher Code - '.$billing['code'].'';?></span>
							 <?php }else if($billing['gift_issue_weight']>0){?>
							    <span>Gift Voucher Worth <?php echo ' '.$billing['gift_issue_weight'].' '.(($billing['utilize_for'])==1 ? ' Gold ' :' Silver ').' '?>Valid Till <?php echo $billing['valid_to'].'. Voucher Code - '.$billing['code'].'';?></span>
							 <?php }?><br>
							 <br>
                            <?php 
                            if($billing['note']!='')
                            {?>
                            <label>Terms and Conditions</label><br><br>	
                            <?php  echo $billing['note'];?>
                            <?php  }?>
				    
				</div><br><br><br><br><br><br><br><br><br><br><br><br>
				<div class="row" style="text-transform:uppercase;">
					<label>Salesman sign</label>
					<label style="margin-left:25%;">customer sign</label>
					<label style="margin-left:15%;"><?php echo 'For '.$comp_details['company_name']?></label>
				</div>
				<div class="row" style="text-transform:uppercase;">
				    <label> EMP ID : <?php echo $billing['id_employee'];?></label>
				</div>
						
			</div>
 </div>
 </div><!-- /.box-body --> 
</div>
 </span>          
</body></html>
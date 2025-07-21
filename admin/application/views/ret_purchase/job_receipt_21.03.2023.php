<html>
	<head>
		<meta charset="utf-8">
		
		<title>Job Receipt - <?php echo $order['pur_no'];?> </title>
		
		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/job_receipt.css">
		
		<style>
		    .addr_labels {
                display: inline-block;
                width: 30%;
				padding-bottom: 5px;
            }
    		
            .addr_values {
                display: inline-block;
                padding-left: -5px;
            }
            
            .addr_brch_labels {
    			display: inline-block;
    			vertical-align:top; 
				width: 40%; 
				text-align: right;
    		}
    
    		.addr_brch_values {
    			display: inline-block;
    			vertical-align:top; 
				width: 40%; 
				text-align: left;
    		}
		
		</style>
	</head>
	<body>
	    <?php 
	    function moneyFormatIndia($num) {
        	return preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $num);
        }
	    ?>
		<div class="PDFReceipt">
			<div class="heading">
				<div class="company_name"><?php echo strtoupper($comp_details['company_name']); ?></div>
				<div><?php echo strtoupper($comp_details['address1']) ?> , <?php echo strtoupper($comp_details['address2']) ?></div>
				<?php echo ($comp_details['email']!='' ? '<div>Email : '.strtoupper($comp_details['email']).' </div>' :'') ?>
				<?php echo ($comp_details['gst_number']!='' ? '<div>GST'.strtoupper($comp_details['gst_number']).' :</div>' :'') ?>
				<div><?php echo $comp_details['state'] ?></div>
			</div><br>
			
			<div style="width: 100%; text-transform:uppercase;height:140px;">
				<div style="display: inline-block; width: 35%;">
					    <label><?php echo '<div class="addr_labels">Name</div><div class="addr_values">:&nbsp;&nbsp;'.'Mr./Ms.'.$po_item['supplier_name']."</div>"; ?></label><br>
    					<label><?php echo '<div class="addr_labels">Mobile</div><div class="addr_values">:&nbsp;&nbsp;'.$po_item['mobile']."</div>"; ?></label><br>
    					<label><?php echo ($po_item['supplier_address1']!='' ? '<div class="addr_labels">Address</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($po_item['supplier_address1']).','."</div><br>" :''); ?></label>
    					<label><?php echo ($po_item['supplier_address2']!='' ? '<div class="addr_labels"></div><div class="addr_values">&nbsp;&nbsp;&nbsp;'.strtoupper($po_item['supplier_address2']).','."</div><br>" :''); ?></label>
    					<label><?php echo ($po_item['supplier_city_name']!='' ? '<div class="addr_labels">city</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($po_item['supplier_city_name']).($po_item['supplier_pincode']!='' ? ' - '.$po_item['supplier_pincode'].'.' :'')."</div><br>" :''); ?></label>
    					<label><?php echo ($po_item['supplier_state_name']!='' ? '<div class="addr_labels">State</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($po_item['supplier_state_name'].'-'.$po_item['supplier_state_code']).','."</div><br>" :''); ?></label>
    					<label><?php echo ($po_item['supplier_country_name']!='' ? '<div class="addr_labels">Country</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($po_item['supplier_country_name'])."</div><br>" :''); ?></label>
    					<label><?php echo (isset($po_item['pan_no']) && $po_item['pan_no']!='' ? '<div class="addr_labels">PAN</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($po_item['pan_no'])."</div><br>" :''); ?></label>
    					<label><?php echo (isset($po_item['gst_number']) && $po_item['gst_number']!='' ? '<div class="addr_labels">GST IN</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($po_item['gst_number'])."</div><br>" :''); ?></label>
                </div>
				
                
                <div style="display: inline-block; text-align: center !important; width: 29%; ">JOB WORK RECEIPT</div>
                
               <div style="width: 35%; display: inline-block;vertical-align: top;text-align: right">
					 
					<label><?php echo ($po_item['po_date']!='' ? '<div class="addr_brch_labels">Receipt Date &nbsp;&nbsp;</div><div class="addr_brch_values">&nbsp;&nbsp;:&nbsp;&nbsp;'.$po_item['po_date']."</div><br>" :''); ?></label><br>
					<label><?php echo ($po_item['po_ref_no']!='' ? '<div class="addr_brch_labels">Ref No &nbsp;&nbsp;</div><div class="addr_brch_values">&nbsp;&nbsp;:&nbsp;&nbsp;'.$po_item['po_ref_no']."</div><br>" :''); ?></label>
    						
					
				</div>
			</div><br>
			<div class="receipt_details">
				<table class="job_receipt_table">
					<thead>
						<tr>
							<th class="alignLeft">S.No</th>
							<th class="alignLeft">Description</th>
							<th class="alignRight">Purity</th>
							<th class="alignRight">Pcs</th>
							<th class="alignRight">Gwt</th>
							<th class="alignRight">Nwt</th>
							<th class="alignRight">Touch</th>
							<th class="alignRight">V.A(%)</th>
							<th class="alignRight">Pure</th>
							<th class="alignRight">MC</th>
							<th class="alignRight">Amount</th>
							
						</tr>
					</thead>
					<tbody>
						<?php
							$i = 1;
							$total_pcs = 0;
							$total_gwt = 0;
							$total_nwt = 0;
							$total_wastage = 0;
							$total_mc = 0;
							$item_pure_wt = 0;
							$taxable_amt = 0;
							$total_cgst = 0;
							$total_sgst = 0;
							$total_igst = 0;
							foreach($po_item_details as $po_detail) { ?>
								<?php 
								$other_charges = 0;
								$stone_charges = 0;
								foreach($po_detail['other_charge_details'] as $val)
								{
								    $other_charges+=($val['calc_type']==2 ? ($val['pur_othr_charge_value']*$po_detail['no_of_pcs']) :$val['pur_othr_charge_value']);
								}
								foreach($po_detail['stn_details'] as $sval){
								    $stone_charges+=$sval['po_stone_amount'];
								}
								$total_cgst+=$po_detail['total_cgst'];
								$total_sgst+=$po_detail['total_sgst'];
								$total_igst+=$po_detail['total_igst'];
								$total_pcs = $total_pcs + $po_detail['no_of_pcs'];
								$total_gwt = $total_gwt + $po_detail['gross_wt'];
								$total_nwt = $total_nwt + $po_detail['net_wt'];
								$item_pure_wt = $item_pure_wt + $po_detail['item_pure_wt'];
								$taxable_amt+=$po_detail['item_cost']-$po_detail['total_tax'];
								
								$making_charge = round($po_detail['mc_type'] == 2 ? $po_detail['mc_value'] * $po_detail['gross_wt'] : $po_detail['mc_value'] * 1,3); 
								$total_mc = $total_mc + $making_charge;
								
								$wastage = round(($po_detail['gross_wt'] / 100) * $po_detail['item_wastage'],3);
								
								
								$total_wastage = $total_wastage + $wastage;
								?>
								<tr>
									<td class="alignLeft" ><?php echo $i ?></td>
									<td class="alignLeft"><?php echo $po_detail['product_name'] ?></td>
									<td class="alignRight"><?php echo $po_detail['purity_name'] ?></td>
									<td class="alignRight"><?php echo $po_detail['no_of_pcs'] ?></td>
									<td class="alignRight"><?php echo moneyFormatIndia($po_detail['gross_wt']) ?></td>
									<td class="alignRight"><?php echo moneyFormatIndia($po_detail['net_wt']) ?></td>
									<td class="alignRight"><?php echo moneyFormatIndia($po_detail['purchase_touch']) ?></td>
									<td class="alignRight"><?php echo moneyFormatIndia($po_detail['item_wastage']) ?></td>
									<td class="alignRight"><?php echo moneyFormatIndia($po_detail['item_pure_wt']) ?></td>
									<td class="alignRight"><?php echo moneyFormatIndia($making_charge); ?></td>
									<td class="alignRight"><?php echo moneyFormatIndia(number_format($po_detail['item_cost']-$po_detail['total_tax']-$other_charges-$stone_charges,2,'.','')) ?></td>
								</tr>
								<?php
    								foreach($po_detail['stn_details'] as $sval)
    							    {?>
    							        <tr>
    							            <td></td>
    							            <td><?php echo $sval['stone_name'];?></td>
    							            <td></td>
    							            <td></td>
    							            <td></td>
    							            <td class="alignRight"><?php echo $sval['po_stone_wt'].'/'.$sval['uom_short_code'];?></td>
    							            <td class="alignRight">Rs.<?php echo $sval['po_stone_rate'].'/'.$sval['uom_short_code'];?></td>
    							            <td></td>
    							            <td></td>
    							            <td></td>
    							            <td class="alignRight"><?php echo moneyFormatIndia(number_format($sval['po_stone_amount'],2,'.',''))?></td>
    							        </tr>
    							    <?php }
								?>
								
								<?php
    								foreach($po_detail['other_charge_details'] as $val)
    							    {?>
    							        <tr>
    							            <td></td>
    							            <td colspan="9"><?php echo $val['name_charge'];?></td>
    							            <td class="alignRight"><?php echo moneyFormatIndia(number_format(($val['calc_type']==2 ? ($val['pur_othr_charge_value']*$po_detail['no_of_pcs']) :$val['pur_othr_charge_value']),2,'.',''))?></td>
    							        </tr>
    							    <?php }
								?>
								
						<?php $i++; }	?>
					</tbody>
					<tfoot>
						<tr>
							<td class="alignCenter" colspan="3">SUB TOTAL</td>
							<td class="alignRight"><?php echo $total_pcs ?></td>
							<td class="alignRight"><?php echo moneyFormatIndia(number_format((float)$total_gwt, 3, '.', '')); ?></td>
							<td class="alignRight"><?php echo moneyFormatIndia(number_format((float)$total_nwt, 3, '.', '')); ?></td>
							<td ></td>
							<td ></td>
							<td class="alignRight"><?php echo moneyFormatIndia(number_format((float)$item_pure_wt, 3, '.', '')); ?></td>
							<td class="alignRight"><?php echo moneyFormatIndia(number_format((float)$making_charge, 2, '.', '')); ?></td>
							<td class="alignRight"><?php echo moneyFormatIndia(number_format((float)$taxable_amt, 2, '.', '')); ?></td>
						</tr>
						
					</tfoot>
				</table>
			</div>
			
			<div class="footer">
				<div class="footer_left">
					Checked By
				</div>
				<div class="footer_center">
					Verified By
				</div>
				<div class="footer_right">
					Authorised Signatory
				</div>
			</div>
			
		</div>
		
		<script type="text/javascript">
			setTimeout(function(){
			
				window.print();
			
			}, 1000);
		</script>
	</body>
</html>
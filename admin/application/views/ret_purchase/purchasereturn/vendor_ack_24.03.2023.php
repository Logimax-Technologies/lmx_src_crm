<?php
function moneyFormatIndia($num)
{
	$nums = explode(".",$num);
	if(count($nums)>2){
	return "0";
	}else{
	if(count($nums)==1){
	$nums[1]="00";
	}
	$num = $nums[0];
	$explrestunits = "" ;
	if(strlen($num)>3){
	$lastthree = substr($num, strlen($num)-3, strlen($num));
	$restunits = substr($num, 0, strlen($num)-3); 
	$restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; 
	$expunit = str_split($restunits, 2);
	for($i=0; $i<sizeof($expunit); $i++)
	{
	if($i==0)
	{
	$explrestunits .= (int)$expunit[$i].","; 
	}
	else
	{
	$explrestunits .= $expunit[$i].",";
	}
	}
	$thecash = $explrestunits.$lastthree;
	} else {
	$thecash = $num;
	}
	return $thecash.".".$nums[1]; 
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style type="text/css">
body{
	font-size: 12px;
}
table{
	width: 100%;
}
.rateborders{
	border-top: none;
	border-bottom: none;
	height: 20px;
	text-align: right;
	font-size: 14px;
}
.desc{
	text-align: right;
	font-size: 14px;
	height: 25px;
	border-bottom: none;
	border-top: none;
}
.descheading{
	text-align: center;
	font-weight: bold;
	height: 30px;
	font-size: 12px;
	border-top: none;
}
.nos_label{
	padding-right: 5px;
	font-weight: bold;
}
.nos_values{
	padding-left : 5px;
}
.iconDetails {
	 margin-left:2%;
	float:left; 
	width:40px;	
	height:40px;	
} 
.container2 {
	width:350px;
	height:auto;
	padding:1%;
    float:left;
}
.branchadd{
	padding-left: 8px !important;
    padding: 0px;
    margin-top: -91px;
    margin-left: 216px;
    border-left: 1px solid;
    margin-bottom: 24px;
}

.addr_brch_labels {
	display: inline-block;
	width: 40%;
}

.addr_brch_values {
	display: inline-block;
	padding-left: 2px;
}

 .addr_labels {
    display: inline-block;
    width: 30%;
    text-transform:uppercase;
}

.addr_values {
    display: inline-block;
    padding-left: -5px;
}
        
h4 {margin:0}
.left {float:left;width:45px;}
.right {float:left;margin:0 0 0 5px;width:400px;}
</style>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>PURCHASE RETURN</title>
</head>
<body>
<p style="text-align: center">
<div style="text-align: center">
    <span style="padding-left: 10px; font-weight: bold">DEBIT NOTE</span>
</div>
</p>

<?php

if (version_compare(phpversion(), '7.1', '>=')) {
    ini_set( 'precision', 14 );
    ini_set( 'serialize_precision', -1 );
}
function formatnumber($num){

	return floatval(number_format($num, 2, '.', ''));

}?>
<table width="921" height="766" border="1" cellpadding="3" cellspacing="0">
	<tr>
		<td height="77" colspan="2">
		
		<div style="text-align: right !important; display: inline-block; vertical-align: top;">
			<div style="text-align: left !important;width: 100%; display: inline-block;"> 
					<label><?php echo '<div class="addr_labels">Name</div><div class="addr_values">:&nbsp;&nbsp;'.$comp_details['company_name']."</div>"; ?></label><br>
					<label><?php echo ($comp_details['address1']!='' ? '<div class="addr_labels">Address</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($comp_details['address1']).','."</div><br>" :''); ?></label>
					<label><?php echo ($comp_details['address2']!='' ? '<div class="addr_labels"></div><div class="addr_values">&nbsp;&nbsp;&nbsp;'.strtoupper($comp_details['address2']).','."</div><br>" :''); ?></label>
					<label><?php echo ($comp_details['city']!='' ? '<div class="addr_labels">city</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($comp_details['city']).($comp_details['pincode'] ? ' - '.$comp_details['pincode'].'.' :'')."</div><br>" :''); ?></label>
					<label><?php echo ($comp_details['state']!='' ? '<div class="addr_labels">State</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($comp_details['state']).'.'."</div><br>" :''); ?></label>
					<label><?php echo ($comp_details['gst_number']!='' ? '<div class="addr_labels">GST</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($comp_details['gst_number']).'.'."</div><br>" :''); ?></label>
			</div>
		</div>
			
		</td>
		<td width="36%" style="text-align: left;">
		    <div style="text-align: right !important; display: inline-block; vertical-align: top;">
				<div style="text-align: left !important;width: 100%; display: inline-block;"> 
						<label><?php echo '<div class="addr_brch_labels">Receipt No</div><div class="addr_brch_values">:&nbsp;&nbsp;'.$issue['pur_ret_ref_no']."</div><br>"; ?></label>
						<label><?php echo '<div class="addr_brch_labels">Receipt Date</div><div class="addr_brch_values">:&nbsp;&nbsp;'.$issue['date_add']."</div><br>"; ?></label>
				</div>
			</div>
		</td>
	</tr>
	<tr>
		<td width="50%" valign="top" style="border-right: none;">
		
		 <div style="text-align: left !important;width: 100%; display: inline-block;"> 
					<label><?php echo '<div class="addr_labels">Name</div><div class="addr_values">:&nbsp;&nbsp;'.$issue['karigar_name']."</div>"; ?></label><br>
					<label><?php echo ($issue['address1']!='' ? '<div class="addr_labels">Address</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($issue['address1']).','."</div><br>" :''); ?></label>
					<label><?php echo ($issue['address2']!='' ? '<div class="addr_labels"></div><div class="addr_values">&nbsp;&nbsp;&nbsp;'.strtoupper($issue['address2']).','."</div><br>" :''); ?></label>
					<label><?php echo ($issue['city_name']!='' ? '<div class="addr_labels">city</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($issue['city_name']).($issue['pincode'] ? ' - '.$issue['pincode'].'.' :'')."</div><br>" :''); ?></label>
					<label><?php echo ($issue['state_name']!='' ? '<div class="addr_labels">State</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($issue['state_name']).'.'."</div><br>" :''); ?></label>
					<label><?php echo ($issue['gst_number']!='' ? '<div class="addr_labels">GST</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($issue['gst_number']).'.'."</div><br>" :''); ?></label>
					<label><?php echo ($issue['pan_no']!='' ? '<div class="addr_labels">PAN</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($issue['pan_no']).'.'."</div><br>" :''); ?></label>

			</div>
			
		</td>
		<td width="14%" style="font-size:16px;border-left: none;height: 15px">
		  <!--<div style="text-align:center">
			<img src="<?php echo base_url(); ?>qrcode/<?php echo $inv['cusdel_irn']; ?>.jpg" style="height: 125px;width: 125px;" />	
			</div>-->
		</td>
		<td style="font-size:16px;; border-bottom: none;height: 15px;vertical-align: top;text-align: center;">Terms of Delivery<br>
		<!--<?php echo nl2br($inv['terms_delivery']); ?></td>-->
	</tr>
	
	
	<tr>
		<td colspan="3" style="padding: 0px; border: none;">
		
				<table id="commodity_display" border="1" cellpadding="2" cellspacing="0">
					<tr>
						<td  class="descheading" style="border-left: none;">S.NO</td>
						<td  class="descheading">DESCRIPTION OF GOODS</td>
						<td  class="descheading">HSN</td>
						<td  class="descheading">QUANTITY (GM)<br /></td >
						<td  class="descheading">RATE PER GRAM</td>
						<td  class="descheading">AMOUNT(RS)<br/></td>
					</tr>
					<?php
					$i = 1;
					$total_taxable_amt = 0;
					$cgst_cost = 0;
					$sgst_cost = 0;
					$cgst_iost = 0;
					$total_item_cost = 0;
					$total_charges = 0;
					foreach($grn_details['gst_details'] as $gst)
					{
					    $cgst_cost+=$gst['cgst_cost'];
			            $sgst_cost+=$gst['sgst_cost'];
			            $igst_cost+=$gst['igst_cost'];
					}
					foreach($grn_details['charge_details'] as $charge)
					{
					    $total_charges+=$charge['grn_charge_value'];
					}
					foreach($receipt_details as $row) 
					{
			            $total_taxable_amt+=$row['pur_ret_item_cost']-$row['pur_ret_tax_value'];
			            $total_item_cost+=$row['pur_ret_item_cost'];
			            foreach($row['stone_details'] as $stn_details)
			            {
			                $stone_weight+=$stn_details['wt'];
			                $stone_cost+=$stn_details['amount'];
			            }
						
					?>
						<tr>
							<td class="desc" style="text-align: center;border-left: none;"><?php echo $i; ?></td>
							<td class="desc" style="text-align: left">&nbsp;<?php echo $row['product_name'] ?></td>
							<td class="desc"><?php echo  ($row['hsn_code']); ?></td>
							<td class="desc"><?php echo  number_format($row['pur_ret_gwt'],3,'.','') ?></td>
							<td class="desc"><?php echo  $row['pur_ret_rate'] ?></td>
							<td  class="desc"><?php echo moneyFormatIndia(number_format($row['pur_ret_item_cost']-$row['pur_ret_tax_value'],2,'.','')) ?> </td>
						</tr>
					<?php $i++;
    					
					}
						
					?>
					
					
				
					
					<tr>
						<td class="rateborders" style="border-left: none"></td>
						<td class="rateborders"></td>
						<td class="rateborders"></td>
						<td class="rateborders"></td>
						<td class="rateborders"></td>
						<td class="rateborders" style="border: 1px solid;"><?php echo moneyFormatIndia($total_taxable_amt+$total_charges,2,".","") ?></td>
					</tr>
					
					<?php 
					foreach($gst_details as $gst)
					{?>
					    <?php 
					    if($gst['cgst_cost']>0)
					    {?>
					        <tr>
        						<td class="rateborders" style="border-left: none"></td>
        						<td class="rateborders"></td>
        						<td class="rateborders"></td>
        						<td class="rateborders"></td>
        						<td class="rateborders">CGST(<?php echo ($gst['pur_ret_tax_value']/2) ?> %)</td>
        						<td class="rateborders"><?php echo moneyFormatIndia(number_format($gst['cgst_cost'],2,".","")) ?></td>
        					</tr>
        					
        					<tr>
        						<td class="rateborders" style="border-left: none"></td>
        						<td class="rateborders"></td>
        						<td class="rateborders"></td>
        						<td class="rateborders"></td>
        						<td class="rateborders">SGST(<?php echo ($gst['pur_ret_tax_value']/2) ?> %)</td>
        						<td class="rateborders"><?php echo moneyFormatIndia(number_format($gst['sgst_cost'],2,".","")) ?></td>
        					</tr>
        					
					    <?php }else if($gst['igst_cost']>0){ ?>
					        <tr>
        						<td class="rateborders" style="border-left: none"></td>
        						<td class="rateborders"></td>
        						<td class="rateborders"></td>
        						<td class="rateborders"></td>
        						<td class="rateborders">IGST(<?php echo ($gst['pur_ret_tax_value']) ?> %)</td>
        						<td class="rateborders"><?php echo moneyFormatIndia(number_format($gst['igst_cost'],2,".","")) ?></td>
        					</tr>
					    <?php }?>
				<?php }?>
					    <?php 
					    if($issue['pur_ret_tcs_percent']>0)
					    {?>
					        <tr>
        						<td class="rateborders" style="border-left: none"></td>
        						<td class="rateborders"></td>
        						<td class="rateborders"></td>
        						<td class="rateborders"></td>
        						<td class="rateborders">TCS(<?php echo ($issue['pur_ret_tcs_percent']) ?> %)</td>
        						<td class="rateborders"><?php echo moneyFormatIndia($issue['pur_ret_tcs_value'],2,".","") ?></td>
        					</tr>
					    <?php }
					    ?>
					    
					    
					    <?php 
					    if($issue['pur_ret_tds_percent']>0)
					    {?>
					        <tr>
        						<td class="rateborders" style="border-left: none"></td>
        						<td class="rateborders"></td>
        						<td class="rateborders"></td>
        						<td class="rateborders"></td>
        						<td class="rateborders">TDS(<?php echo ($issue['pur_ret_tds_percent']) ?> %)</td>
        						<td class="rateborders"><?php echo moneyFormatIndia($issue['pur_ret_tds_value'],2,".","") ?></td>
        					</tr>
					    <?php }
					    ?>
					    
					    
					    
					    	<?php 
        					foreach($charge_details as $charge)
        					{?>
        					    <tr>
            						<td class="desc" style="text-align: center;border-left: none;"></td>
            						<td class="rateborders"></td>
        							<td class="desc" style=""></td>
        							<td class="desc"></td>
        							<td class="desc"><?php echo $charge['name_charge'] ?></td>
        							<td  class="desc"><?php echo moneyFormatIndia(number_format($charge['pur_ret_charge_value'])) ?> </td>
            					</tr>
        					<?php $i++; }
        					?>
        					
        					<?php 
            					foreach($charge_gst_details as $gst)
            					{?>
            					    <?php 
            					    if($gst['cgst_cost']>0)
            					    {?>
            					        <tr>
                    						<td class="rateborders" style="border-left: none"></td>
                    						<td class="rateborders"></td>
                    						<td class="rateborders"></td>
                    						<td class="rateborders"></td>
                    						<td class="rateborders">CGST(<?php echo ($gst['pur_ret_charge_tax']/2) ?> %)</td>
                    						<td class="rateborders"><?php echo moneyFormatIndia(number_format($gst['cgst_cost'],2,".","")) ?></td>
                    					</tr>
                    					
                    					<tr>
                    						<td class="rateborders" style="border-left: none"></td>
                    						<td class="rateborders"></td>
                    						<td class="rateborders"></td>
                    						<td class="rateborders"></td>
                    						<td class="rateborders">SGST(<?php echo ($gst['pur_ret_charge_tax']/2) ?> %)</td>
                    						<td class="rateborders"><?php echo moneyFormatIndia(number_format($gst['sgst_cost'],2,".","")) ?></td>
                    					</tr>
                    					
            					    <?php }else if($gst['igst_cost']>0){ ?>
            					        <tr>
                    						<td class="rateborders" style="border-left: none"></td>
                    						<td class="rateborders"></td>
                    						<td class="rateborders"></td>
                    						<td class="rateborders"></td>
                    						<td class="rateborders">IGST(<?php echo ($gst['pur_ret_charge_tax']) ?> %)</td>
                    						<td class="rateborders"><?php echo moneyFormatIndia(number_format($gst['igst_cost'],2,".","")) ?></td>
                    					</tr>
            					    <?php }?>
            				<?php }?>
				
        					
        					<?php 
					    if($issue['pur_ret_other_charges_tds_percent']>0)
					    {?>
					        <tr>
        						<td class="rateborders" style="border-left: none"></td>
        						<td class="rateborders"></td>
        						<td class="rateborders"></td>
        						<td class="rateborders"></td>
        						<td class="rateborders">CHARGES TDS(<?php echo ($issue['pur_ret_other_charges_tds_percent']) ?> %)</td>
        						<td class="rateborders"><?php echo moneyFormatIndia(round($issue['pur_ret_other_charges_tds_value'],2),2,".","") ?></td>
        					</tr>
					    <?php }
					    ?>
					
					    <?php 
					    if($issue['pur_ret_discount']!=0)
					    {?>
					        <tr>
        						<td class="rateborders" style="border-left: none"></td>
        						<td class="rateborders"></td>
        						<td class="rateborders"></td>
        						<td class="rateborders"></td>
        						<td class="rateborders">DISCOUNT</td>
        						<td class="rateborders"><?php echo moneyFormatIndia(round($issue['pur_ret_discount'],2),2,".","") ?></td>
        					</tr>
					    <?php }
					    ?>
					    
					    <?php 
					    if($issue['pur_ret_round_off']!=0)
					    {?>
					        <tr>
        						<td class="rateborders" style="border-left: none"></td>
        						<td class="rateborders"></td>
        						<td class="rateborders"></td>
        						<td class="rateborders"></td>
        						<td class="rateborders">ROUND OFF</td>
        						<td class="rateborders"><?php echo moneyFormatIndia(round($issue['pur_ret_round_off'],2),2,".","") ?></td>
        					</tr>
					    <?php }
					    ?>
				
				
				
					<?php $height = "";
						if($i == 2)
							$height = "165px";
						else if($i == 3)
							$height = "140px";
						else if($i == 4)
							$height = "105px";
						else if($i == 5)
							$height = "80px";
						else if($i == 6)
							$height = "55px";
						else if($i == 7)
							$height = "30px";
					?>
					<?php if($i < 8) { ?>
					<tr style="height: <?php echo $height ?>">			
						<td class="rateborders" style="border-left: none"></td>
						<td class="rateborders"></td>
						<td class="rateborders"></td>
						<td class="rateborders"></td>
						<td class="rateborders"></td>
						<td class="rateborders"></td>
					</tr>
					<?php } ?>
					<tr>			
						<td class="rateborders" style="border-top: 1px solid #ccc; border-left: none"></td>
						<td class="rateborders" style="border-top: 1px solid #ccc; border-left: none"></td>
						<td class="rateborders" style="border-top: 1px solid #ccc;"><b>FINAL TOTAL</b></td>
						<td class="rateborders" style="border-top: 1px solid #ccc;"><b><?php echo $total_qty; ?></b></td>
						<td class="rateborders" style="border-top: 1px solid #ccc;"></td>
						<td class="rateborders" style="border-top: 1px solid #ccc;"><b><?php echo moneyFormatIndia(round($issue['return_total_cost'],3),2,".","") ?></b></td>
					</tr>
				</table>
			
		</td>
	</tr>
	
	

</table>
<div style="text-align: center; margin-top: 10px;"> This is computer generated invoice </div>
<div style="margin-top: 2px">DECLARATION</div>
<div>
"AS PER THE RULE 138 Of CGST ACT/ SGST ACT, PRECIOUS METALS / PRECIOUS STONES / JEWELLERY ARE EXEMPT FROM GENERATING E-WAY BILL AS NOTIFIED BY NOTIFICATION NO.3 OF 2018 DATED 23/01/2018"
</div>
</body>
</html>
<script type="text/javascript">
window.print();
</script>
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
h4 {margin:0}
.left {float:left;width:45px;}
.right {float:left;margin:0 0 0 5px;width:400px;}
</style>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>TAX INVOICE</title>
</head>
<body>
<p style="text-align: center">
<div style="text-align: center">
    <span style="padding-left: 10px; font-weight: bold">TAX INVOICE</span>
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
		<div class='container2'>
			<!--<div class="left">
				<img src="<?php echo base_url() ?>assets/img/receipt_logo.png" class='iconDetails' />			
			</div>-->
			<div class="right" style="width:300px;">
				<span style="font-size:16px; width: 300px; font-weight: bold"><?php echo $grn_details['karigar_name'] ?></span>
				<br/>
				<?php
					if(strlen($grn_details['supplier_address1'])>0) echo nl2br($grn_details['supplier_address1']); else echo '';
					if(strlen($grn_details['supplier_address2'])>0) echo nl2br($grn_details['supplier_address2']); else echo '';
					if(strlen($grn_details['supplier_city_name'])>0) echo $grn_details['supplier_city_name']; else echo ''; 
					if(strlen($grn_details['supplier_pincode'])>0) echo " - ".$grn_details['supplier_pincode']; else echo '';
					echo "</br>INDIA";
					if(strlen($grn_details['gst_number'])>0) echo "</br> GSTIN/UIN : ".$grn_details['gst_number']; else echo '';
					if(strlen($grn_details['supplier_state_name'])>0) echo "</br> State Name : ".$grn_details['supplier_state_name']; else echo '';
					if(strlen($grn_details['email'])>0) echo "</br> Email : ".$grn_details['email']; else echo '';
				?>
			</div>
		</div>		</td>
		<td width="36%" style="text-align: left;vertical-align: top">
			<div style="height:40px;width:200px;padding-top: 20px">Invoice No : <b><?php echo $grn_details['grn_ref_no']; ?></b></div>
			<div style="height: 40px;">Dated : <b><?php echo $grn_details['grn_date'] ?></b></div>		
			<div style="height: 40px;">Supplier Ref No : <b><?php echo $grn_details['grn_supplier_ref_no'] ?></b></div>
			<?php 
			if($grn_details['grn_ref_date']!='')
			{?>
			    <div style="height: 40px;">Supplier Ref Date : <b><?php echo $grn_details['grn_ref_date'] ?></b></div>
			<?php }
			?>
					
		</td>
	</tr>
	<tr>
		<td width="50%" valign="top" style="border-right: none;">&nbsp;Buyer(Bill to/Ship to)<br />
		<div style="padding-left:4px;padding-top: 4px;">
		<b><?php if(strlen($comp_details['company_name'])>0) echo $comp_details['company_name']; else echo ''; ?></b>
		<?php
			if(strlen($comp_details['address1'])>0) echo ",<br />".nl2br($comp_details['address1']); else echo '';
			if(strlen($comp_details['address2'])>0) echo ",<br />".nl2br($comp_details['address2']); else echo '';
			if(strlen($comp_details['city'])>0) echo ",<br />".$comp_details['city']; else echo ''; 
			if(strlen($comp_details['pincode'])>0) echo " - ".$comp_details['pincode']; else echo '';
			if(strlen($comp_details['gst_number'])>0) echo "</br> GSTIN/UIN : ".$comp_details['gst_number']; else echo '';
			if(strlen($comp_details['state'])>0) echo "<br> State Name: ".$comp_details['state']; else echo '';
			if(strlen($comp_details['state'])>0) echo "<br> Place of supply: ".$comp_details['state']; else echo '';
		?>
		</div>		</td>
		<td width="14%" style="font-size:16px;border-left: none;height: 15px">
		  <!--<div style="text-align:center">
			<img src="<?php echo base_url(); ?>qrcode/<?php echo $inv['cusdel_irn']; ?>.jpg" style="height: 125px;width: 125px;" />	
			</div>-->
		</td>
		<td style="font-size:16px;; border-bottom: none;height: 15px;vertical-align: top;text-align: center;">Terms of Delivery<br>
		<?php echo nl2br($inv['terms_delivery']); ?></td>
	</tr>
	
	
	<tr>
		<td colspan="3" style="padding: 0px; border: none;">
		
				<table id="commodity_display" border="1" cellpadding="2" cellspacing="0">
					<tr>
						<td  class="descheading" style="border-left: none;">S.NO</td>
						<td  class="descheading">DESCRIPTION OF GOODS</td>
						<td  class="descheading">QUANTITY (GM)<br /></td >
						<td  class="descheading">RATE PER GRAM</td>
						<td  class="descheading">AMOUNT(RS)<br/></td>
					</tr>
					<?php
					$i = 1;
					$total_taxable_amt = 0;
					$grn_item_cgst = 0;
					$grn_item_sgst = 0;
					$grn_item_igst = 0;
					$grn_item_total_cost = 0;
					$total_charges = 0;
					foreach($grn_details['gst_details'] as $gst)
					{
					    $grn_item_cgst+=$gst['grn_item_cgst'];
			            $grn_item_sgst+=$gst['grn_item_sgst'];
			            $grn_item_igst+=$gst['grn_item_igst'];
					}
					foreach($grn_details['charge_details'] as $charge)
					{
					    $total_charges+=$charge['grn_charge_value'];
					}
					foreach($grn_details['item_details'] as $row) 
					{
					    $stone_cost = 0;
					    $stone_weight = 0;
			            $total_taxable_amt+=$row['grn_item_cost']-$row['grn_item_gst_rate'];
			            $grn_item_total_cost+=$row['grn_item_cost'];
			            foreach($row['stone_details'] as $stn_details)
			            {
			                $stone_weight+=$stn_details['wt'];
			                $stone_cost+=$stn_details['amount'];
			            }
						
					?>
						<tr>
							<td class="desc" style="text-align: center;border-left: none;"><?php echo $i; ?></td>
							<td class="desc" style="text-align: left">&nbsp;<?php echo $row['category_name'] ?> <?php echo strlen($row['hsn_code']) > 0 ? "( " .$row['hsn_code']." )" : ''; ?> </td>
							<td class="desc"><?php echo  number_format($row['grn_gross_wt']-$stone_weight,3,'.','') ?></td>
							<td class="desc"><?php echo  $row['grn_rate_per_grm'] ?></td>
							<td  class="desc"><?php echo moneyFormatIndia(number_format(round($row['grn_item_cost']-$row['grn_item_gst_rate']-$stone_cost),2,'.','')) ?> </td>
						</tr>
					<?php $i++;
    					foreach($row['stone_details'] as $stn_details)
    					{?>
    					    <tr>
    							<td class="desc" style="text-align: center;border-left: none;"><?php echo $i; ?></td>
    							<td class="desc" style="text-align: left">&nbsp;<?php echo $stn_details['stone_name'] ?> </td>
    							<td class="desc"><?php echo  $stn_details['wt'] ?></td>
    							<td class="desc"></td>
    							<td  class="desc"><?php echo moneyFormatIndia(number_format(round($stn_details['amount']),2,'.','')) ?> </td>
    						</tr>
    					<?php $i++; }
					}
						
					?>
					
					
					<?php 
					foreach($grn_details['charge_details'] as $charge)
					{?>
					    <tr>
    						<td class="desc" style="text-align: center;border-left: none;"><?php echo $i; ?></td>
							<td class="desc" style="text-align: left">&nbsp;<?php echo $charge['name_charge'] ?></td>
							<td class="desc"></td>
							<td class="desc"></td>
							<td  class="desc"><?php echo moneyFormatIndia(number_format($charge['grn_charge_value'])) ?> </td>
    					</tr>
					<?php $i++; }
					?>
					
					<tr>
						<td class="rateborders" style="border-left: none"></td>
						<td class="rateborders">GROSS AMOUNT</td>
						<td class="rateborders"></td>
						<td class="rateborders"></td>
						<td class="rateborders" style="border: 1px solid;"><?php echo moneyFormatIndia(round($total_taxable_amt+$total_charges,3),2,".","") ?></td>
					</tr>
					
					<?php 
					foreach($grn_details['gst_details'] as $gst)
					{?>
					    <?php 
					    if($gst['cgst_cost']>0)
					    {?>
					        <tr>
        						<td class="rateborders" style="border-left: none"></td>
        						<td class="rateborders">CGST(<?php echo ($gst['grn_item_gst_value']/2) ?> %)</td>
        						<td class="rateborders"></td>
        						<td class="rateborders"></td>
        						<td class="rateborders"><?php echo moneyFormatIndia(number_format($gst['cgst_cost'],2,".","")) ?></td>
        					</tr>
        					
        					<tr>
        						<td class="rateborders" style="border-left: none"></td>
        						<td class="rateborders">SGST(<?php echo ($gst['grn_item_gst_value']/2) ?> %)</td>
        						<td class="rateborders"></td>
        						<td class="rateborders"></td>
        						<td class="rateborders"><?php echo moneyFormatIndia(number_format($gst['sgst_cost'],2,".","")) ?></td>
        					</tr>
        					
					    <?php }else if($gst['igst_cost']>0){ ?>
					        <tr>
        						<td class="rateborders" style="border-left: none"></td>
        						<td class="rateborders">IGST(<?php echo ($gst['grn_item_gst_value']) ?> %)</td>
        						<td class="rateborders"></td>
        						<td class="rateborders"></td>
        						<td class="rateborders"><?php echo moneyFormatIndia(number_format($gst['igst_cost'],2,".","")) ?></td>
        					</tr>
					    <?php }?>
					    <?php 
					    if($grn_details['grn_tcs_percent']>0)
					    {?>
					        <tr>
        						<td class="rateborders" style="border-left: none"></td>
        						<td class="rateborders">TCS(<?php echo ($grn_details['grn_tcs_percent']) ?> %)</td>
        						<td class="rateborders"></td>
        						<td class="rateborders"></td>
        						<td class="rateborders"><?php echo moneyFormatIndia(round($grn_details['grn_tcs_value'],2),2,".","") ?></td>
        					</tr>
					    <?php }
					    ?>
					    
					    <?php 
					    if($grn_details['grn_round_off']!=0)
					    {?>
					        <tr>
        						<td class="rateborders" style="border-left: none"></td>
        						<td class="rateborders">ROUND OFF</td>
        						<td class="rateborders"></td>
        						<td class="rateborders"></td>
        						<td class="rateborders"><?php echo moneyFormatIndia(round($grn_details['grn_round_off'],2),2,".","") ?></td>
        					</tr>
					    <?php }
					    ?>
					    
					    <?php 
					    if($grn_details['grn_pay_tds_percent']>0)
					    {?>
					        <tr>
        						<td class="rateborders" style="border-left: none"></td>
        						<td class="rateborders">TDS(<?php echo ($grn_details['grn_pay_tds_percent']) ?> %)</td>
        						<td class="rateborders"></td>
        						<td class="rateborders"></td>
        						<td class="rateborders"><?php echo moneyFormatIndia(round($grn_details['grn_pay_tds_value'],2),2,".","") ?></td>
        					</tr>
					    <?php }
					    ?>
					    
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
					</tr>
					<?php } ?>
					<tr>			
						<td class="rateborders" style="border-top: 1px solid #ccc; border-left: none"></td>
						<td class="rateborders" style="border-top: 1px solid #ccc;"><b>FINAL TOTAL</b></td>
						<td class="rateborders" style="border-top: 1px solid #ccc;"><b><?php echo $total_qty; ?></b></td>
						<td class="rateborders" style="border-top: 1px solid #ccc;"></td>
						<td class="rateborders" style="border-top: 1px solid #ccc;"><b><?php echo moneyFormatIndia(round($grn_details['grn_purchase_amt'],3),2,".","") ?></b></td>
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
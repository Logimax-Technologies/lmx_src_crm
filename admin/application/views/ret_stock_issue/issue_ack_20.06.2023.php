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
<title>OFFICE COPY</title>
</head>
<body>
<p style="text-align: center">
<div style="text-align: center">
    <span style="padding-left: 10px; font-weight: bold">DELIVERY CHALLAN</span>
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
				<div style="text-align: left !important;width: 130%; display: inline-block;"> 
						<label><?php echo '<div class="addr_labels">Name</div><div class="addr_values">:&nbsp;&nbsp;'.$comp_details['company_name']."</div>"; ?></label><br>
    					<label><?php echo '<div class="addr_labels">Mobile</div><div class="addr_values">:&nbsp;&nbsp;'.$comp_details['mobile']."</div>"; ?></label><br>
    					<label><?php echo ($comp_details['address1']!='' ? '<div class="addr_labels">Address</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($comp_details['address1']).','."</div><br>" :''); ?></label>
    					<label><?php echo ($comp_details['address2']!='' ? '<div class="addr_labels"></div><div class="addr_values">&nbsp;&nbsp;&nbsp;'.strtoupper($comp_details['address2']).','."</div><br>" :''); ?></label>
    					<label><?php echo ($comp_details['city']!='' ? '<div class="addr_labels">city</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($comp_details['city']).($comp_details['pincode']!='' ? ' - '.$comp_details['pincode'].'.' :'')."</div><br>" :''); ?></label>
    					<label><?php echo ($comp_details['state']!='' ? '<div class="addr_labels">State</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($comp_details['state']).','."</div><br>" :''); ?></label>
    					<label><?php echo (isset($comp_details['gst_number']) && $comp_details['gst_number']!='' ? '<div class="addr_labels">GST IN</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($comp_details['gst_number'])."</div><br>" :''); ?></label>
    					<!-- <label><php echo (isset($comp_details['pan_no']) && $comp_details['pan_no']!='' ? '<div class="addr_labels">PAN </div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($comp_details['pan_no'])."</div><br>" :''); ?></label> -->
				</div>
			</div>
		</td>
		<td width="36%" >
			<div style="text-align: right !important; display: inline-block; vertical-align: top;">
				<div style="text-align: left !important;width: 100%; display: inline-block;"> 
						<label><?php echo '<div class="addr_brch_labels">Invoice No</div><div class="addr_brch_values">:&nbsp;&nbsp;'.$issue['issue_no']."</div><br>"; ?></label>
						<label><?php echo '<div class="addr_brch_labels">Invoice Date</div><div class="addr_brch_values">:&nbsp;&nbsp;'.$issue['issue_date']."</div><br>"; ?></label>
						<!-- <label><php echo '<div class="addr_brch_labels">Supplier Ref No</div><div class="addr_brch_values">:&nbsp;&nbsp;'.$grn_details['grn_supplier_ref_no']."</div><br>"; ?></label>
						<label><php echo '<div class="addr_brch_labels">Supplier Ref Date</div><div class="addr_brch_values">:&nbsp;&nbsp;'.$grn_details['grn_ref_date']."</div>"; ?></label> -->
				</div>
			</div>
		</td>
	</tr>
	<tr>

		<td width="50%" valign="top" style="border-right: none;">
    	
    		<div style="text-align: right !important; display: inline-block; vertical-align: top;">
				<div style="text-align: left !important;width: 250%; display: inline-block;"> 
				        <label><b>Billed To</b></label><br>
						<?php if($issue['issued_to'] == 1){?>
						<label><?php echo '<div class="addr_labels">Name</div><div class="addr_values">:&nbsp;&nbsp;'.$issue['customer_name']."</div>"; ?></label><br>
						<label><?php echo '<div class="addr_labels">Mobile</div><div class="addr_values">:&nbsp;&nbsp;'.$issue['cus_mobile']."</div>"; ?></label><br>
						<label><?php echo ($issue['cus_address1']!='' ? '<div class="addr_labels">Address</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($issue['cus_address1']).','."</div><br>" :''); ?></label>
						<label><?php echo ($issue['cus_address2']!='' ? '<div class="addr_labels"></div><div class="addr_values">&nbsp;&nbsp;&nbsp;'.strtoupper($issue['cus_address2']).','."</div><br>" :''); ?></label>
    					<label><?php echo ($issue['cus_city_name']!='' ? '<div class="addr_labels">city</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($issue['cus_city_name']).($issue['cus_pincode']!='' ? ' - '.$issue['cus_pincode'].'.' :'')."</div><br>" :''); ?></label>
    					<label><?php echo ($issue['cus_state_name']!='' ? '<div class="addr_labels">State</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($issue['cus_state_name']).','."</div><br>" :''); ?></label>
    					<label><?php echo ($issue['cus_gst_number']!='' ? '<div class="addr_labels">GST</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($issue['cus_gst_number']).','."</div><br>" :''); ?></label>
						<?php }?>

						<?php  if($issue['issued_to'] == 2){?>
						<label><?php echo '<div class="addr_labels">Name</div><div class="addr_values">:&nbsp;&nbsp;'.$issue['emp_name']."</div>"; ?></label><br>
						<label><?php echo '<div class="addr_labels">Mobile</div><div class="addr_values">:&nbsp;&nbsp;'.$issue['emp_mobile']."</div>"; ?></label><br>
						<?php }?>

						<?php if($issue['issued_to'] == 3){?>
						<label><?php echo '<div class="addr_labels">Name</div><div class="addr_values">:&nbsp;&nbsp;'.$issue['karigar_name']."</div>"; ?></label><br>
						<label><?php echo '<div class="addr_labels">Mobile</div><div class="addr_values">:&nbsp;&nbsp;'.$issue['kar_mobile']."</div>"; ?></label><br>
    					<label><?php echo ($issue['supplier_address1']!='' ? '<div class="addr_labels">Address</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($issue['supplier_address1']).','."</div><br>" :''); ?></label>
    					<label><?php echo ($issue['supplier_address2']!='' ? '<div class="addr_labels"></div><div class="addr_values">&nbsp;&nbsp;&nbsp;'.strtoupper($issue['supplier_address2']).','."</div><br>" :''); ?></label>
    					<label><?php echo ($issue['supplier_city_name']!='' ? '<div class="addr_labels">city</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($issue['supplier_city_name']).($issue['supplier_pincode']!='' ? ' - '.$issue['supplier_pincode'].'.' :'')."</div><br>" :''); ?></label>
    					<label><?php echo ($issue['supplier_state_name']!='' ? '<div class="addr_labels">State</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($issue['supplier_state_name']).','."</div><br>" :''); ?></label>
    					<label><?php echo ($issue['kar_gst_number']!='' ? '<div class="addr_labels">GST</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($issue['kar_gst_number']).','."</div><br>" :''); ?></label>
						<?php }?>
					</div>

			</div><br>
			
			<div style="text-align: right !important; display: inline-block; vertical-align: top;">
				<div style="text-align: left !important;width: 250%; display: inline-block;"> 
				        <label><b>Shipped To</b></label><br>
						<?php if($issue['issued_to'] == 1){?>
						<label><?php echo '<div class="addr_labels">Name</div><div class="addr_values">:&nbsp;&nbsp;'.$issue['customer_name']."</div>"; ?></label><br>
						<label><?php echo '<div class="addr_labels">Mobile</div><div class="addr_values">:&nbsp;&nbsp;'.$issue['cus_mobile']."</div>"; ?></label><br>
						<label><?php echo ($issue['cus_address1']!='' ? '<div class="addr_labels">Address</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($issue['cus_address1']).','."</div><br>" :''); ?></label>
						<label><?php echo ($issue['cus_address2']!='' ? '<div class="addr_labels"></div><div class="addr_values">&nbsp;&nbsp;&nbsp;'.strtoupper($issue['cus_address2']).','."</div><br>" :''); ?></label>
    					<label><?php echo ($issue['cus_city_name']!='' ? '<div class="addr_labels">city</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($issue['cus_city_name']).($issue['cus_pincode']!='' ? ' - '.$issue['cus_pincode'].'.' :'')."</div><br>" :''); ?></label>
    					<label><?php echo ($issue['cus_state_name']!='' ? '<div class="addr_labels">State</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($issue['cus_state_name']).','."</div><br>" :''); ?></label>
    					<label><?php echo ($issue['cus_gst_number']!='' ? '<div class="addr_labels">GST</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($issue['cus_gst_number']).','."</div><br>" :''); ?></label>
						<?php }?>

						<?php  if($issue['issued_to'] == 2){?>
						<label><?php echo '<div class="addr_labels">Name</div><div class="addr_values">:&nbsp;&nbsp;'.$issue['emp_name']."</div>"; ?></label><br>
						<label><?php echo '<div class="addr_labels">Mobile</div><div class="addr_values">:&nbsp;&nbsp;'.$issue['emp_mobile']."</div>"; ?></label><br>
						<?php }?>

						<?php if($issue['issued_to'] == 3){?>
						<label><?php echo '<div class="addr_labels">Name</div><div class="addr_values">:&nbsp;&nbsp;'.$issue['karigar_name']."</div>"; ?></label><br>
						<label><?php echo '<div class="addr_labels">Mobile</div><div class="addr_values">:&nbsp;&nbsp;'.$issue['kar_mobile']."</div>"; ?></label><br>
    					<label><?php echo ($issue['supplier_address1']!='' ? '<div class="addr_labels">Address</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($issue['supplier_address1']).','."</div><br>" :''); ?></label>
    					<label><?php echo ($issue['supplier_address2']!='' ? '<div class="addr_labels"></div><div class="addr_values">&nbsp;&nbsp;&nbsp;'.strtoupper($issue['supplier_address2']).','."</div><br>" :''); ?></label>
    					<label><?php echo ($issue['supplier_city_name']!='' ? '<div class="addr_labels">city</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($issue['supplier_city_name']).($issue['supplier_pincode']!='' ? ' - '.$issue['supplier_pincode'].'.' :'')."</div><br>" :''); ?></label>
    					<label><?php echo ($issue['supplier_state_name']!='' ? '<div class="addr_labels">State</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($issue['supplier_state_name']).','."</div><br>" :''); ?></label>
    					<label><?php echo ($issue['kar_gst_number']!='' ? '<div class="addr_labels">GST</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($issue['kar_gst_number']).','."</div><br>" :''); ?></label>
						<?php }?>
				</div>
			</div>
			
		</td>
		<td width="14%" style="font-size:16px;border-left: none;height: 15px">
		  <div style="text-align:center">
			</div>
		</td>
		<td style="font-size:16px;; border-bottom: none;height: 15px;vertical-align: top;text-align: center;"><br>
	</td>
	</tr>
	
	
	<tr>
		<td colspan="3" style="padding: 0px; border: none;">
		
				<table id="commodity_display" border="1" cellpadding="2" cellspacing="0">
					<tr>
						<td  class="descheading" style="border-left: none;">S.NO</td>
						<td  class="descheading">DESCRIPTION OF GOODS</td>
						<td  class="descheading">QUANTITY (GM)<br /></td >
						<td  class="descheading">RATE</td>
						<td  class="descheading">PER GRAM</td>
						<td  class="descheading">DISC(%)</td>
						<td  class="descheading">AMOUNT(RS)<br/></td>
					</tr>
					<?php 
							$i=1;
							$quantity=0;
							$rate=0;
							$tot_amount=0;
							$total_amount=0;
							$total_cgst=0;
							$total_sgst=0;
							foreach($item_details as $val)
							{
								$rate = $val['rate_per_gram']; 
								$quantity+=$val['gross_wt'];												
								$total_amount+=round($val['issue_weight'] * $rate);
								$tax_group=round(($total_amount * 3)/100);
								$total_cgst=round($tax_group/2);
								$total_sgst=round($tax_group/2);
							?>

							

								<tr>
							           <td class="desc" style="text-align: center;border-left: none;"><?php echo $i; ?></td>
							           <td class="desc" style="text-align: left">&nbsp;<?php echo $val['category_name'] ?> </td>
							           <td class="desc"><?php echo  $val['gross_wt'] ?></td>
							           <td class="desc"><?php echo  moneyFormatIndia($rate) ?></td>
									   <td class="desc">Gms</td>
							           <td class="desc"></td>
							           <td  class="desc"><?php echo (moneyFormatIndia(number_format($val['issue_weight'] * $rate,2,'.',''))) ?> </td>
						        </tr>		
								
							<?php $i++;
							}?>											
											
											
					
					

					<tr style="font-weight:bold;">
						<td class="rateborders" style="border-left: none"></td>
						<td class="rateborders"></td>
						<td class="rateborders" style="border: 1px solid;"><?php echo moneyFormatIndia(number_format((float)$quantity,3,'.','')) ?></td>
						<td class="rateborders"></td>
						<td class="rateborders"></td>
						<td class="rateborders"></td>
						<td class="rateborders" style="border: 1px solid;"><?php echo moneyFormatIndia(number_format($total_amount,2,'.','')) ?></td>
						
					</tr>
					
				
					    <?php 
					    if($total_cgst>0)
					    {?>
					        <tr>
        						<td class="rateborders" style="border-left: none"></td>
        						<td class="rateborders"></td>
        						<td class="rateborders">CGST(<?php echo ($val['tax_percentage']/2) ?> %)</td>
        						<td class="rateborders"></td>
        						<td class="rateborders"></td>
								<td class="rateborders"></td>
        						<td class="rateborders"><?php echo moneyFormatIndia(number_format($total_cgst,2,".","")) ?></td>
        					</tr>
        					
        					<tr>
        						<td class="rateborders" style="border-left: none"></td>
        						<td class="rateborders" style="border-left: none"></td>
        						<td class="rateborders">SGST(<?php echo ($val['tax_percentage']/2) ?> %)</td>
        						<td class="rateborders"></td>
        						<td class="rateborders"></td>
								<td class="rateborders"></td>
        						<td class="rateborders"><?php echo moneyFormatIndia(number_format($total_sgst,2,".","")) ?></td>
        					</tr>
        					
					    <?php }?>
					  

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
					<?php $grandtotal = $total_cgst + $total_sgst +$total_amount ?>
					<tr>			
						<td class="rateborders" style="border-top: 1px solid #ccc; border-left: none"></td>
						<td class="rateborders" style="border-top: 1px solid #ccc; border-left: none"></td>
						<td class="rateborders" style="border-top: 1px solid #ccc;"><b>FINAL TOTAL</b></td>
						<td class="rateborders" style="border-top: 1px solid #ccc;"><b></td>
						<td class="rateborders" style="border-top: 1px solid #ccc;"></td>
						<td class="rateborders" style="border-top: 1px solid #ccc;"></td>
						<td class="rateborders" style="border-top: 1px solid #ccc;"><b><?php echo moneyFormatIndia(number_format($grandtotal,2,'.','')) ?></b></td>
					</tr>
				</table>
			
		</td>
	</tr>
	
	

</table>
<?php {?>
    <br><br>
    <div class="row" style="text-transform:uppercase;">
		<label style="margin-left:20%;">Authorised Signatory</label>
		<label style="margin-left:20%;">Checked By</label>
		<label style="margin-left:30%;">Verified By</label>
	</div>
<?php }?>


</body>
</html>
<script type="text/javascript">
window.print();
</script>
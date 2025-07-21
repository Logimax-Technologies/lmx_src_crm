<html><head>
		<meta charset="utf-8">
		<title>Metal Issue Receipt</title>
		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/metalissue_ack.css">
		<style type="text/css">
		body, html {
		margin-bottom:0;
		font-size: 12px !important;
		}
		 span { display: inline-block; }
		 
		 .addr_labels {
			display: inline-block;
			width: 25%;
		}

		.addr_values {
			display: inline-block;
			padding-left: 5px;
		}

		.addr_delv_labels {
			display: inline-block;
			width: 25%;
		}

		.addr_delv_values {
			display: inline-block;
			padding-left: 5px;
		}

		.addr_brch_labels {
			display: inline-block;
			width: 25%;
		}

		.addr_brch_values {
			display: inline-block;
			padding-left: 5px;
		}
		
	</style>
</head><body>
<span class="PDFReceipt">
			    
			    <div style="width: 100%; text-transform:uppercase;font-size: 11px !important;" align="center;">
			        <div style="display: inline-block; width: 100%;">
                          <img alt=""  src="<?php echo base_url();?>assets/img/receipt_logo.jpg" ><br>
                          <label><?php echo 'GST NO : '.$comp_details['gst_number'];?></label><br>
                          <label><?php echo 'CIN NO : '.$comp_details['cin_number'];?></label><br><br>
    				</div>
			    </div><br>
			    
			    <div style="width: 100%; text-transform:uppercase;font-size: 11px !important;">

    				<div style="display: inline-block; width: 30%;font-size: 11px;">
    				      <label><b>ISSUE TO</b></label><br><br>
                          <label><?php echo $issue['karigar_name'].' - '.$issue['mobile'];?></label>
                          <label><?php echo ($issue['address1']!='' ? '<br>'.''.$issue['address1'] :'')?></label>
                          <label><?php echo ($issue['address2']!='' ? '<br>'.''.$issue['address2'] :'')?></label>
                          <label><?php echo ($issue['address3']!='' ? '<br>'.''.$issue['address3'] :'')?></label>
                          <label><?php echo ($issue['city_name']!='' ? '<br>'.''.$issue['city_name'].($issue['pincode']!='' ? ' - '.$issue['pincode'] :'') :'')?></label>
                          <label><?php echo ($issue['state_name']!='' ? '<br>'.''.$issue['state_name'] :'')?></label>
                          <label><?php echo ($issue['country_name']!='' ? '<br>'.''.$issue['country_name'] :'')?></label>
    				</div>
    				
    				<div style="display: inline-block; width: 18%;font-size: 12px;margin-top:-5%;margin-left:10%;">
    				    <label><b>DELIVERY CHALLAN<br><BR>PURCHASE RETURN</b></label>
    				</div>
    		
    				
    				<div style="display: inline-block; width: 50%;margin-left:18%;font-size: 11px !important;">
                          <label><?php echo $comp_details['company_name'];?></label>
                          <label><?php echo ($comp_details['address1']!='' ? '<br>'.''.$comp_details['address1'] :'')?></label>
                          <label><?php echo ($comp_details['address2']!='' ? '<br>'.''.$comp_details['address2'] :'')?></label>
                          <label><?php echo ($comp_details['address3']!='' ? '<br>'.''.$comp_details['address3'] :'')?></label>
                          <label><?php echo ($comp_details['city']!='' ? '<br>'.''.$comp_details['city'].($comp_details['pincode']!='' ? ' - '.$comp_details['pincode'] :'') :'')?></label>
                          <label><?php echo ($comp_details['state']!='' ? '<br>'.''.$comp_details['state'] :'')?></label>
                          <label><?php echo ($comp_details['country']!='' ? '<br>'.''.$comp_details['country'] :'')?></label>
    				</div>
			</div>
			
			<div style="width: 100%; text-transform:uppercase;margin-top:-40px;text-transform:uppercase;font-size: 11px !important;font-weight:bold;">
		        <div style="text-align: left; width:100%; ">
					<div style="width: 12%; display: inline-block"> REF NO &nbsp;&nbsp; : &nbsp; </div>
					<div style="width: 15%; display: inline-block; margin-top: -1px"> <?php echo $issue['pur_ret_ref_no']; ?></div>
				</div>
		    </div>
			
			
			<div style="width: 100%; text-align: right; display: inline-block;margin-top:-74px;text-transform:uppercase;font-size: 11px !important;font-weight:bold;">
			   
				<div style="text-align: right; width:100%;height: 18px;">
					<div style="width: 88%; display: inline-block"> Date &nbsp; : &nbsp; </div>
					<div style="width: 10%; display: inline-block; margin-top: -2px"> <?php echo $issue['date_add']; ?> </div>
				</div>
			</div><br><br>
			
			<br><br><br>
			
<div  class="content-wrapper">
 <div class="box">
  <div class="box-body">
 			<div  class="container-fluid">
				<div id="printable">
						<div  class="row" style="margin-top:-8%;">
							<div class="col-xs-12">
								<div class="table-responsive">
								    <hr class="item_dashed" style="width:95% !important;">
									<table id="pp" class="table text-center">
									<!--	<thead> -->
											<tr>
												<th width="7%;" style="text-align:left;">S.NO</th>
												<th  width="25%;" style="text-align:left;">Category</th>
												<th  width="20%;" style="text-align:left;">Pcs</th>
												<th  width="20%;" style="text-align:left;">Wt(Gms)</th>
												<th  width="20%;" style="text-align:left;">Rate</th>
												<th  width="20%;" style="text-align:left;">Amount</th>
											</tr>
											<tr>
												<td><hr class="item_dashed" style="width:1800% !important;"></td>
											</tr>
										<!--</thead>
										<tbody>-->
											<?php 
											$i=1;
											$total_pur_wt=0;
											$pur_ret_pcs=0;
											$total_amount = 0;
											foreach($receipt_details as $val)
											{
    											$pur_ret_pcs+=$val['pur_ret_pcs'];
    											$total_pur_wt+=$val['pur_ret_gwt'];
    											$total_amount += $val['pur_ret_item_cost'];
											?>
												<tr>
														<td style="text-align:left;"><?php echo $i;?></td>
														<td style="text-align:left;"><?php echo $val['product_name'];?></td>
														<td style="text-align:left;"><?php echo $val['pur_ret_pcs'];?></td>
														<td style="text-align:left;"><?php echo $val['pur_ret_gwt'];?></td>
														<td style="text-align:left;"><?php echo $val['pur_ret_rate'];?></td>
														<td style="text-align:left;"><?php echo $val['pur_ret_item_cost'];?></td>
													</tr>
											<?php $i++;
											}?>
											<tr>
												<td><hr class="item_dashed" style="width:1800% !important;"></td>
											</tr>
									<!--</tbody> -->
										<tfoot>
											<tr>
											<td colspan="2"><b>TOTAL</b></td>
											<td><b><?php echo number_format((float)$pur_ret_pcs,0,'.','')?></b></td>
											<td><b><?php echo number_format((float)$total_pur_wt,3,'.','')?></b></td>
											<td></td>
											<td><b><?php echo number_format((float)$total_amount,2,'.','')?></b></td>
											</tr>
										</tfoot>
										<tr>
												<td><hr class="item_dashed" style="width:1800% !important;"></td>
											</tr>
									</table><br>	
								</div>	
							 </div>	
						</div>
				</div>
 </div>
 </div><!-- /.box-body --> 
</div>

 </span>          
</body></html>
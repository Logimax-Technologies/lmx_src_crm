<html><head>
		<meta charset="utf-8">
		<title>Metal Issue Receipt</title>
		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/billing_receipt.css">
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
<span class="PDFReceipt" style="margin-top:14%;">
	<table class="meta" style=  "align=center;width:100%">
        <tr>
            <td style="text-align:center !important;"><img width="10%" style="color:red"  src="<?php echo base_url();?>assets/img/receipt_logo.png"></td>
         </tr>
 </table>
			    <div style="width: 100%; text-transform:uppercase;font-size: 11px !important;">

    				<div style="display: inline-block; width: 30%;font-size: 11px;">
                          <label><?php echo ($cus_details[0]['cus_name'] .' - '.  $cus_details[0]['mobile']);?></label><br>
                         
                         
    				</div>
    				
    				<div style="display: inline-block; width: 18%;font-size: 12px;margin-top:5%;margin-left:10%;">
    				    <label><b>ORDER ITEM</b></label>
    				</div>
    		
    				
    				<div style="display: inline-block; width: 50%;margin-left:18%;font-size: 11px !important;">
                          <label><?php echo $comp_details['name'];?></label>
                          <label><?php echo ($comp_details['address1']!='' ? '<br>'.''.$comp_details['address1'] :'')?></label>
                          <label><?php echo ($comp_details['address2']!='' ? '<br>'.''.$comp_details['address2'] :'')?></label>
                          <label><?php echo ($comp_details['address3']!='' ? '<br>'.''.$comp_details['address3'] :'')?></label>
                          <label><?php echo ($comp_details['city']!='' ? '<br>'.''.$comp_details['city'].($comp_details['pincode']!='' ? ' - '.$comp_details['pincode'] :'') :'')?></label>
                          <label><?php echo ($comp_details['state']!='' ? '<br>'.''.$comp_details['state'] :'')?></label>
                          <label><?php echo ($comp_details['country']!='' ? '<br>'.''.$comp_details['country'] :'')?></label>
    				</div>
			</div><p></p>
			
		    <div style="width: 100%; text-transform:uppercase;margin-top:-40px;text-transform:uppercase;font-size: 11px !important;font-weight:bold;">
		        <div style="text-align: left; width:100%;height: 10px; ">
					<div style="width: 12%; display: inline-block"> ORDER NO &nbsp;&nbsp; : &nbsp; </div>
					<div style="width: 15%; display: inline-block; margin-top: 1px"> <?php echo $cus_details[0]['orderno']; ?></div>
				</div>
		    </div>
			
			
			<div style="width: 100%; text-align: right; display: inline-block;margin-top:-44px;text-transform:uppercase;font-size: 11px !important;font-weight:bold;">
			   
				<div style="text-align: right; width:100%;height: 18px;">
					<div style="width: 88%; display: inline-block; margin-top: 1px"> Date: </div>
					<div style="width: 9%; display: inline-block; margin-top: 1px;"> <?php echo $cus_details[0]['order_date']; ?> </div>
				</div>
			</div>
			
			<br><br><br><br><br>
			
<div  class="content-wrapper">
 <div class="box">
  <div class="box-body">
 			<div  class="container-fluid">
				<div id="printable">
						<div  class="row" style="margin-top:-8%;">
							<div class="col-xs-12">
								<div class="table-responsive">
									<table id="pp" class="table text-center" style="width:100%">
									<thead style="text-transform:uppercase;font-size:10px;">
                                    <tr>
        									<td><hr class="item_dashed" style="width:680px !important"></td>
        									</tr>
											<tr>
												<th class="table_heading alignRight" style="width: 5%">S.NO</th>
												<th class="table_heading alignRight" style="width: 9%">Product</th>
												<th class="table_heading alignRight" style="width: 12%">Design</th>
												<th class="table_heading alignRight" style="width: 15%">SubDesign</th>
												<th class="table_heading alignRight" style="width: 9%">Size</th>
												<th class="table_heading alignRight" style="width: 9%">Pcs</th>
												<th class="table_heading alignRight" style="width: 9%">Weight</th>
											</tr>
                                            </thead>
                                            <tr>
        									<td><hr class="item_dashed" style="width:680px !important"></td>
        									</tr>
										<!--</thead>
										<tbody>-->
											<?php 
											$i=1;
											$total_wt=0;
											$totalitems=0;
											foreach($order as $val)
											{
    											$totalitems+=$val['totalitems'];
    											$total_wt+=$val['weight'];
											?>
												<tr>
														<td style="alignRight;"><?php echo $i;?></td>
														<td style="alignRight;"><?php echo $val['product_name'];?></td>
														<td style="alignRight;"><?php echo $val['design_name'];?></td>
														<td style="alignRight;"><?php echo $val['sub_design_name'];?></td>
														<td style="alignRight;"><?php echo $val['size_name']!=''?$val['size_name']:'-';?></td>
														<td style="alignRight;"><?php echo $val['totalitems'];?></td>
														<td style="alignRight;"><?php echo $val['weight'];?></td>
													</tr>
											<?php $i++;
											}?>
											<tr>
												<td><hr class="item_dashed" style="width:680px !important"></td>
											</tr>
									<!--</tbody> -->
                                    
										
											<tr>
											<td colspan="2"><b>TOTAL</b></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
											<td class="alignRight"><b><?php echo number_format((float)$totalitems,0,'.','')?></b></td>
											<td class="alignRight"><b><?php echo number_format((float)$total_wt,3,'.','')?></b></td>
											</tr>
                                            <tr>
                                                <td><hr class="item_dashed" style="width:680px !important"></td>
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
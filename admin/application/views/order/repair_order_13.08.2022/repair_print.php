<html><head>
		<meta charset="utf-8">
		<title>Repair Item</title>
		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/metalissue_ack.css">
			<style type="text/css">
		body, html {
		margin-bottom:0;
		font-size: 12px !important;
		}
		 span { display: inline-block; }
		 
		 .addr_headers_labels {
            display: inline-block;
            width: 20%;
        }
        
        .addr_rate_labels {
            display: inline-block;
            width: 10%;
        }
        
        .addr_labels {
            display: inline-block;
            width: 20%;
        }
		
        .addr_values {
            display: inline-block;
            padding-left: -5px;
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
			width: 20%;
		}

		.addr_brch_values {
			display: inline-block;
			padding-left: 5px;
		}
		
	</style>
</head><body>
<span class="PDFReceipt" >
    
                <div style="width: 100%; text-transform:uppercase;font-size: 11px !important;">
    	        	<div style="display: inline-block; width: 50%; padding-left:0px;margin-top:20px;">
    					<label><?php echo '<div class="addr_headers_labels">CIN</div><div class="addr_values">:&nbsp;&nbsp;'.$comp_details['cin_number']."</div>"; ?></label><br>
    					<label><?php echo '<div class="addr_headers_labels">GSTIN</div><div class="addr_values">:&nbsp;&nbsp;'.$comp_details['gst_number']."</div>"; ?></label><br>
    				</div>
    				


    				<div style="width: 50%; text-align: right; display: inline-block; vertical-align: top;">
    					<div style="text-align: right;width: 100%; display: inline-block;margin-top:-30px;"> 
        					<img width="50%" src="<?php echo dirname(base_url()) ?>/assets/img/receipt_logo.png" />
						</div>
					</div>
			 </div><p></p>
			    
	
			<div style="width: 100%; text-transform:uppercase;font-size: 11px !important;">
    	        	<div style="display: inline-block; width: 48%; padding-left:0px;">
    					<label><?php echo '<div class="addr_labels">Name</div><div class="addr_values">:&nbsp;&nbsp;'.'Mr./Ms.'.$create_repair_details[0]['cus_name']."</div>"; ?></label><br>
    					<label><?php echo '<div class="addr_labels">Mobile</div><div class="addr_values">:&nbsp;&nbsp;'.$create_repair_details[0]['mobile']."</div>"; ?></label><br>
    					<label><?php echo ($create_repair_details[0]['address1']!='' ? '<div class="addr_labels">Address</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($create_repair_details[0]['address1']).','."</div><br>" :''); ?></label>
    					<label><?php echo ($create_repair_details[0]['address2']!='' ? '<div class="addr_labels"></div><div class="addr_values">&nbsp;&nbsp;&nbsp;'.strtoupper($create_repair_details[0]['address2']).','."</div><br>" :''); ?></label>
    					<label><?php echo ($create_repair_details[0]['address3']!='' ? '<div class="addr_labels"></div><div class="addr_values">&nbsp;&nbsp;&nbsp;'.strtoupper($create_repair_details[0]['address3']).','."</div><br>" :''); ?></label>
    					<label><?php echo ($create_repair_details[0]['village_name']!='' ? '<div class="addr_labels"></div><div class="addr_values">&nbsp;&nbsp;&nbsp;'.strtoupper($create_repair_details[0]['village_name']).','."</div><br>" :''); ?></label>
    					<label><?php echo ($create_repair_details[0]['city_name']!='' ? '<div class="addr_labels">city</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($create_repair_details[0]['city_name']).($create_repair_details[0]['pincode']!='' ? ' - '.$create_repair_details[0]['pincode'].'.' :'')."</div><br>" :''); ?></label>
    					<label><?php echo ($create_repair_details[0]['state_name']!='' ? '<div class="addr_labels">State</div><div class="addr_values">:&nbsp;&nbsp;'.strtoupper($create_repair_details[0]['state_name'].($create_repair_details[0]['state_code']!='' ? '-'.$create_repair_details[0]['state_code'] :'')).'.'."</div><br>" :''); ?></label>
    				</div>
    				<div style="display: inline-block; width: 10%; padding-left:0px;"></div>
    				<div style="width: 50%; text-align: right !importan; display: inline-block; vertical-align: top;margin-top:-14px !important;">
    					<div style="text-align: left !important;width: 100%; display: inline-block;"> 
        						<label><?php echo ($comp_details['name']!='' ? '<div class="addr_brch_labels">Branch</div><div class="addr_brch_values">:&nbsp;&nbsp;'.strtoupper($comp_details['name']).','."</div><br>" :''); ?></label>
        						<label><?php echo ($comp_details['address1']!='' ? '<div class="addr_brch_labels">Address</div><div class="addr_brch_values">:&nbsp;&nbsp;'.strtoupper($comp_details['address1']).','."</div><br>" :''); ?></label>
        						<label><?php echo ($comp_details['address2']!='' ? '<div class="addr_brch_labels"></div><div class="addr_brch_values">&nbsp;&nbsp;&nbsp;'.strtoupper($comp_details['address2']).','."</div><br>" :''); ?></label>
        						<label><?php echo ($comp_details['city']!='' ? '<div class="addr_brch_labels">City</div><div class="addr_brch_values">:&nbsp;&nbsp;'.strtoupper($comp_details['city']).($comp_details['pincode']!='' ? ' - '.$comp_details['pincode'].'.' :'')."</div>" :''); ?><br></label>
        						<label><?php echo ($comp_details['state']!='' ? '<div class="addr_brch_labels">State</div><div class="addr_brch_values">:&nbsp;&nbsp;'.strtoupper($comp_details['state'].($comp_details['state_code']!='' ? '-'.$comp_details['state_code']  :'')).'.'."</div><br>" :''); ?></label>
						</div>
					</div>
			    </div>
			    
			    <div style="width: 100%; text-transform:uppercase;font-size: 11px !important;text-align:center;margin-top:-45px !important;">
			        <label><b>Repair Order</b></label>
			    </div><p></p>
			
			
			<div style="text-align: left;width: 100%; text-transform:uppercase;margin-top:-8px;font-size: 11px !important">
		        <div style="text-align: left; width:100%;height: 18px; ">
		            <label><?php echo '<div class="addr_rate_labels">GOLD</div><div class="addr_values">:&nbsp;&nbsp;'.number_format($metal_rate['goldrate_22ct'],2,'.','').'/'.'Gm'."</div>"; ?></label><br>
		            <label><?php echo '<div class="addr_rate_labels">SILVER</div><div class="addr_values">:&nbsp;&nbsp;'.number_format($metal_rate['silverrate_1gm'],2,'.','').'/'.'Gm'."</div>"; ?></label><br>
				</div>
		    </div>
		    
	    	<div style="width: 100%; text-align: right; text-transform:uppercase;display: inline-block;margin-top:-40px;font-size: 11px !important">
			    <div style="text-align: right; width:100%;height: 18px;">
					<div style="width: 80%; display: inline-block"> Order Date &nbsp; : &nbsp; </div>
					<div style="width: 20%; display: inline-block; margin-top: -2px;text-align: left;"><?php echo $create_repair_details[0]['create_order_date']; ?></div>
				</div>
		
				
					<div style="text-align: right; width:100%;height: 18px;">
					<div style="width: 80%; display: inline-block"> Order no &nbsp; : &nbsp; </div>
					<div style="width: 15%; display: inline-block; margin-top: -2px;text-align: left;"><?php echo $create_repair_details[0]['orderno']; ?></div>
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
								    <hr class="item_dashed" style="width:100% !important;">
									<table id="pp" class="table text-center">
									<!--	<thead> -->
											<tr>
												<th width="15%;" style="text-align:left;">S.NO</th>
												<th  width="25%;" style="text-align:left;">Item</th>
												<th  width="25%;" style="text-align:left;">Pcs</th>
												<th  width="25%;" style="text-align:left;">Wt(Gms)</th>
											</tr>
											<tr>
												<td><hr class="item_dashed" style="width:600% !important;"></td>
											</tr>
										<!--</thead>
										<tbody>-->
											<?php 
											$i=1;
											$total_wt=0;
											$totalitems=0;
											foreach($create_repair_details as $val)
											{
    											$totalitems+=$val['totalitems'];
    											$total_wt+=$val['weight'];
											?>
												<tr>
														<td style="text-align:left;"><?php echo $i;?></td>
														<td style="text-align:left;"><?php echo $val['product_name'];?></td>
														<td style="text-align:left;"><?php echo $val['totalitems'];?></td>
														<td style="text-align:left;"><?php echo $val['weight'];?></td>
													</tr>
											<?php $i++;
											}?>
											<tr>
												<td><hr class="item_dashed" style="width:600% !important;"></td>
											</tr>
									<!--</tbody> -->
										<tfoot>
											<tr>
											<td colspan="2"><b>TOTAL</b></td>
											<td><b><?php echo number_format((float)$totalitems,0,'.','')?></b></td>
											<td><b><?php echo number_format((float)$total_wt,3,'.','')?></b></td>
											</tr>
										</tfoot>
										<tr>
												<td><hr class="item_dashed" style="width:600% !important;"></td>
											</tr>
									</table><br>	
								</div>	
							 </div>	
						</div>
						<div class="row" style="font-size:10px;">
						    <lable>Remarks : </lable>
						</div>
						<?php 
						$i = 1;
						foreach($create_repair_details as $val)
						{
						    if($val['description']!='')
						    {?>
						        <label><?php echo $i.'.'.$val['description'].'.';?></label><br>
						    <?php 
						        $i++;
						    }
						}
						?>
						<br><br><br><br><br><br><br>
						<div class="row" style="text-transform:uppercase;">
							<label>EMP Sign</label>
							<label style="margin-left:20%;">Customer Sign</label>
							<label style="margin-left:20%;"><?php echo $comp_details['company_name'];?></label>
						</div>
				</div>
 </div>
 </div><!-- /.box-body --> 
</div>

 </span>          
</body></html>
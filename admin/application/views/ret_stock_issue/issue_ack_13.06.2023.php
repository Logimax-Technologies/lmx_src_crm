<html><head>
		<meta charset="utf-8">
		<title>Office Copy - <?php echo $lot_inwards_detail[0]['lot_no'];?></title>
		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/stock_issue_ack.css">
		<!--	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/receipt_temp.css">-->
		<style type="text/css">
		body, html {
		margin-bottom:0
		}
		 span { display: inline-block; }
	</style>
</head><body>
<span class="PDFReceipt">
            <div style="width: 100%; text-transform:uppercase;">
                
                <div style="display: inline-block;width: 10%;" align="center">
					
			    </div>
			    
			    <div style="display: inline-block;width: 50%;" align="center">
					<h2>ISSUE FROM - <?php echo $issue['branch_name'];?> </h2>
			    </div>
			
		        <div style="display: inline-block; width: 70%;" align="right">
		    	    <div style="display: inline-block;">
    		            <table class="meta orders" style="text-transform:uppercase;width:60%;" >
        					<tr>
        						<td><span style="font-weight:bold;">ISSUE NO</span></td>
        						<td> : </td>
        						<td><span style="text-align:right;font-weight:bold"><?php echo $issue['issue_no'];?></span></td>
        					</tr>
        					<tr>
        						<td><span style="font-weight:bold;">ISSUE DATE</span></td>
        						<td> : </td>
        						<td><span style="text-align:right;font-weight:bold;"><?php echo $issue['issue_date'];?></span></td>
        					</tr>
        					<tr>
        						<td><span>Emp</span></td>
        						<td> : </td>
        						<td><span style="text-align:right;"><?php echo $issue['emp_name'];?></span></td>
        					</tr>
        				</table>
    		        </div>
		        </div>
		        
			</div>
<div  class="content-wrapper">
 <div class="box">
  <div class="box-body">
 			<div  class="container-fluid">
				<div id="printable" >
						<div  class="row">
							<div class="col-xs-12">
								<div class="table-responsive">
								<table id="pp" class="table text-center" style="margin-top:65px !important;">
									<!--	<thead> -->
											<tr>
												<th width="7%;" style="text-align:left;">S.NO</th>
												<th  width="25%;" style="text-align:left;">PRODUCT</th>
												<th  width="25%;" style="text-align:left;">DESIGN</th>
												<th  width="25%;" style="text-align:left;">SUB DESIGN</th>
												<th  width="20%;"style="text-align:left;">PCS</th>		
												<th  width="20%;" style="text-align:left;">WEIGHT</th>
											</tr>
											<tr>
												<td><hr class="item_dashed1"></td>
											</tr>
										<!--</thead>
										<tbody>-->
											<?php 
											$i=1;
											$no_of_piece=0;
											$gross_wt=0;
											foreach($item_details as $val)
											{
    											$no_of_piece+=$val['total_items'];
    											$gross_wt+=$val['weight'];
											?>
												<tr>
														<td style="text-align:left;"><?php echo $i;?></td>
														<td style="text-align:left;"><?php echo $val['product_name'];?></td>
														<td style="text-align:left;"><?php echo $val['design_name'];?></td>
														<td style="text-align:left;"><?php echo $val['sub_design_name'];?></td>
														<td style="text-align:left;"><?php echo $val['total_items'];?></td>
														<td style="text-align:left;"><?php echo $val['weight'];?></td>
													</tr>
											<?php $i++;
											}?>
											<tr>
												<td><hr class="item_dashed1"></td>
											</tr>
									<!--</tbody> -->
										<tfoot>
											<tr>
											<td><b>TOTAL</b></td>
											<td></td>
											<td></td>
											<td></td>
											<td><b><?php echo $no_of_piece;?></b></td>
											<td><b><?php echo number_format((float)$gross_wt,3,'.','')?></b></td>
											</tr>
										</tfoot>
										<tr>
												<td><hr class="item_dashed1"></td>
											</tr>
									</table><br>
								</div>	
							 </div>	
						</div>
						<div class="row">
						    <label><b>Remarks:</b></label><br>
						    <?php echo $issue['remarks'];?>
						</div>
						<br>
				</div>
						
			</div><br><br><br><br>
		
 </div>
 </div><!-- /.box-body --> 
</div>

 </span>          
</body></html>
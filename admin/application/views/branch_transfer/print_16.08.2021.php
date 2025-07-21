<html><head>
		<meta charset="utf-8">
		<title>Branch Copy - <?php echo $btrans[0]['branch_trans_code'];?></title>
		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/bt_ack.css">
		<!--	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/receipt_temp.css">-->
		<style type="text/css">
		body, html {
		margin-bottom:0
		}		
		 span { display: inline-block; }
	</style>
</head><body>
<span class="PDFReceipt">
			<div>
				<!--<table class="meta" style="width:30%;margin-top:-30px !important;" align="left">
					<tr>
					<td><img alt=""  src="<?php echo base_url();?>assets/img/receipt_logo.png"></td>
					</tr>
				</table>-->
				<table class="meta" style="width:30%;font-weight:bold;" align="right">
					<tr>
						<td style="font-size:11px !important;">
							<span>TRANS ID :&nbsp; <?php echo $btrans[0]['branch_transfer_id'];?></span>
						</td>
					</tr>
					<tr>
						<td style="font-size:11px !important;">
							<span>CODE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp; <?php echo $btrans[0]['branch_trans_code'];?></span>
						</td>
					</tr>
					<tr style="margin-top:30px !important;">
						<td style="font-size:11px !important;">
						    <span>DATE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							:&nbsp;&nbsp;<?php echo $btrans[0]['created_time'];?></span>
						</td>
					</tr></br>
					
					<tr style="margin-top:30px !important;">
						<td style="font-size:11px !important;">
							<span>FROM &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							:&nbsp;&nbsp;<?php echo $btrans[0]['from_branch'];?></span>
						</td>
					</tr></br>
					
				</table>
			</div>
			<div class="" align="center">
			    <h2><?php echo strtoupper($comp_details['company_name']);?></h2>
			</div>
			<div class="" align="center">
				<h2><?php echo ($btrans[0]['is_other_issue']==1 ? 'OTHER ISSUE RECEIPT -' :'BRANCH TRASNFER RECEIPT -')?><?php echo $btrans[0]['to_branch'];?></h2>
			</div><br>
<div  class="content-wrapper">
 <div class="box">
  <div class="box-body">
 			<div  class="container-fluid">
						<div  class="row">
							<div class="col-xs-12">
								<div class="table-responsive">
								    <?php if($type==2){?>
    								    <table id="pp" class="table text-center">
    											<tr>
    												<th  width="7%;" style="text-align:left;">S.NO</th>
    												<?php if($btrans[0]['transfer_item_type']==1){?>
    												<th  width="15%;" style="text-align:left;">TAG CODE</th>
    												<?php }?>
    												<th  width="25%;" style="text-align:left;">ITEMS</th>
    												<th  width="20%;" style="text-align:left;">PCS</th>
    												<th  width="20%;" style="text-align:left;">GWT</th>
    											</tr>
    											<tr>
    												<td><hr class="detail_dashed"></td>
    											</tr>
                                                <?php 
                                                    $i=1;
                                                    $pcs=0;$gross_wt=0;$tot_sales_value=0;
                                                    foreach($btrans as $items)
                                                    {
                                                        $pcs+=$items['piece'];
                                                        $gross_wt+=$items['gross_wt'];
                                                        ?>
                                                            <tr>
                                                                <td style="text-align:left;"><?php echo $i;?></td>
                                                                <?php if($items['transfer_item_type']==1){?>
                                                                <td style="text-align:left;"><?php echo $items['tag_code'];?></td>  
                                                                <?php }?>
                                                                <td style="text-align:left;"><?php echo $items['product_name'];?></td>
                                                                <td style="text-align:left;"><?php echo $items['piece'];?></td>
                                                                <td style="text-align:left;"><?php echo $items['gross_wt'];?></td>
                                                            </tr>
                                                        <?php 
                                                        $i++;
                                                    }
                                                ?>
    									<tr>
    										<td><hr class="detail_dashed"></td>
    									</tr>
    									<tfoot>
                                            <tr style="font-weight:bold;">
                                                <?php if($btrans[0]['transfer_item_type']==1){?>
                                                
                                                <td style="text-align:left;"></td>
                                                <?php }?>
                                                <td style="text-align:left;"></td>
                                                <td style="text-align:left;">TOTAL</td>
                                                <td style="text-align:left;"><?php echo $pcs;?></td>
                                                <td style="text-align:left;"><?php echo number_format($gross_wt,'3','.','');?></td>
                                            </tr>
    										</tfoot>
    										<tr>
    											<td><hr class="detail_dashed"></td>
    										</tr>
    									</table><br>
								<?php } else if($type==1){?>	
            								<table id="pp" class="table text-center">
                											<tr>
                												<th  width="7%;" style="text-align:left;">S.NO</th>
                												<th  width="25%;" style="text-align:left;">ITEMS</th>
                												<th  width="20%;" style="text-align:left;">PCS</th>
                												<th  width="20%;" style="text-align:left;">GWT</th>
                											</tr>
                											<tr>
                												<td><hr class="sumamry_dashed"></td>
                											</tr>
                                                            <?php 
                                                                $i=1;
                                                                $pcs=0;$gross_wt=0;$tot_sales_value=0;
                                                                foreach($btrans as $items)
                                                                {
                                                                    $pcs+=$items['piece'];
                                                                    $gross_wt+=$items['gross_wt'];
                                                                    ?>
                                                                        <tr>
                                                                            <td style="text-align:left;"><?php echo $i;?></td>
                                                                            <td style="text-align:left;"><?php echo $items['product_name'];?></td>
                                                                            <td style="text-align:left;"><?php echo $items['piece'];?></td>
                                                                            <td style="text-align:left;"><?php echo $items['gross_wt'];?></td>
                                                                        </tr>
                                                                    <?php 
                                                                    $i++;
                                                                }
                                                            ?>
                									<tr>
                										<td><hr class="sumamry_dashed"></td>
                									</tr>
                									<tfoot>
                                                        <tr style="font-weight:bold;">
                                                            <td style="text-align:left;"></td>
                                                            <td style="text-align:left;">TOTAL</td>
                                                            <td style="text-align:left;"><?php echo $pcs;?></td>
                                                            <td style="text-align:left;"><?php echo number_format($gross_wt,'3','.','');?></td>
                                                        </tr>
                										</tfoot>
                										<tr>
                											<td><hr class="sumamry_dashed"></td>
                										</tr>
                									</table><br>
							    <?php }?>
								</div>
								
							 </div>	
						</div>
						<br><br><br><br>
						<div class="row" style="text-transform:uppercase;">
							<label>individual wt verified by</label>
							<label style="margin-left:20%;">received by</label>
							<label style="margin-left:30%;">approved By</label>
						</div>
 </div>
			</div>
 </div>
 </div><!-- /.box-body --> 
</div>
 </span>          
</body></html>

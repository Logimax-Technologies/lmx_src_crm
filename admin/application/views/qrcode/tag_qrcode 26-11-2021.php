<!doctype html>

<html>

	<head>

		<meta charset="utf-8">

		<title>Tag</title>

		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/receipt_barcode.css">

	</head>
	<body>

		<div class="printBody">
			<?php foreach($img as $s){ ?>			

			<div class="PDF_CusReceipt">
					<table class="printable" >
						<tr>
							<td style="width: 45%;font-size:10px;font-weight:bold">
								Wt:<?php echo number_format($s['net_wt'],'3','.','');?> g
							</td>
							<td style="width: 32%">
								<span class="tag-product-design tag-code"> <?php echo $s['tag_code'];?></span>
							</td>
							<td rowspan="3" class="qrImg">
								<img src="<?php echo $s['src'];?>">
							</td>
						</tr>
						<tr >
							<td>
							    <?php if($s['sales_mode'] == 1) { ?>
									<span style="font-size:9px;font-weight:bold">MRP Rs. <?php echo $s['sell_rate'] + 0 ?></span>
								<?php } else if($s['sales_mode'] == 2) { ?>
									<span style="font-size:10px;font-weight:bold">MC:<?php echo ($s['tag_mc_value'] + 0).' '.($s['mc_cal_type']==1 ? '/ p' : ($s['mc_cal_type']==2 ? '/ g' : '%' ) )?></span>
								<?php } ?>
							    
							</td>
							<td>
								<span class="tag-product-design"><?php echo substr($s['product_name'], 0, 8); ?> <?php echo !empty($s['size']) ? " - ". $s['size'] : "";?></span>
							</td>
						</tr>
						<tr >
							<td>
							    <?php if($s['sales_mode'] == 2 && $s['retail_max_wastage_percent'] > 0) { ?>
									<span style="font-size:10px;font-weight:bold"><?php echo ($s['retail_max_wastage_percent']!='' && $s['metal_type'] != 2 ? 'VA:'.floor($s['retail_max_wastage_percent']).'%' :''); ?></span>
								<?php } ?>
							</td>
							<td>
								<span class="tag-product-design"><?php echo substr($s['design_name'], 0, 13);?></span>
							</td>
						</tr>
						<tr >
							<td style="font-size:10px;"><span>HUID:<?php echo $s['hu_id']; ?></span></td>
							<td>
								<span class="tag-product-design"><?php echo $s['charge_code']; ?></span>
							</td>
						</tr>
						
					</table>
			</div>

			<?php }?>

		</div>

		<script type="text/javascript"> 

		this.print(); 

		</script> 

	</body>
</html>
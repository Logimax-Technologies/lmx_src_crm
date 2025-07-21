<!doctype html>

<html>

	<head>

		<meta charset="utf-8">

		<title>Tag</title>

		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/receipt_barcode.css">

	</head>
	<body>

		<div class="printBody">
			<?php foreach($img as $s) { ?>

				<div class="PDF_CusReceipt">
					<table class="printable" >
						<tr>
							<td rowspan="4" class="firstCol"></td>
							<td class="secondCol">
								<?php echo $s['sub_design_name']; ?>
							</td>
							<td rowspan="2" class="thirdCol qrImg">
								<img src="<?php echo $s['src'];?>">
							</td>
						</tr>
						<tr>
							<td class="grossWt">
								GW:<?php echo $s['gross_wt']; ?>g
							</td>
						</tr>
						<tr>
							<td>
								<?php if($s['sales_mode'] == 2 && $s['retail_max_wastage_percent'] > 0) { ?>
								VA:<?php echo $s['retail_max_wastage_percent'] ?>%
								<?php } ?>
								<?php if($s['sales_mode'] == 1) { ?>
								MRP:<?php echo $s['sell_rate'] + 0 ?>
								<?php } ?>
							</td>
							<td class="tagCode">
								<?php echo $s['tag_code'] ?>
							</td>
						</tr>
						<tr>
							<td>
								<?php if($s['hu_id'] != "" && $s['hu_id'] != "-") { ?>
									HUID:<?php echo $s['hu_id'] ?>
								<?php } ?>
								<?php if($s['size'] != "") { ?>
									Sz:<?php echo $s['size'] ?>
								<?php } ?>
							</td>
							<td>
								<?php if($s['code_karigar'] != "") { ?>
									<?php echo $s['code_karigar'] ?>
								<?php } ?>
							</td>
						</tr>
					</table>
				</div>

			<?php } ?>

		</div>

		<script type="text/javascript"> 

		this.print(); 

		</script> 

	</body>
</html>
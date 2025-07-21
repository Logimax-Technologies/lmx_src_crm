<!doctype html>

<html>

	<head>

		<meta charset="utf-8">

		<title>Tag</title>

		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/receipt_barcode.css">

		<!--	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/receipt_temp.css">-->

		<style>

		    @page { size: 50mm 50mm } /* output size */

		    body  { width: 30mm; height: 50mm } /* sheet size */

		    @media print { body { width: 30mm } } /* fix for Chrome */

		  </style>

	</head>

	

	<body class="margin">

		<div class="PDF_CusReceipt">

			<?php foreach($img as $s){?>			

			<div class="printable" style="page-break-after: always;">

    				<table>

					<tr>

						<td  height="10px">5-200049</td>
						
					</tr>

					<tr>

						<td height="10px">
							<span>Bengali Neck</span>
							<span><img src="<?php echo $s['src'];?>" style="height:150%!important;margin-top:5px;"></span>
						</td>

					</tr>

					<tr>

						<td height="10px"><span>Necklace</span></td>

					</tr>

					<tr>

						<td height="10px"><span>MC 100/g was 8 %</span></td>

					</tr>

					<tr>

						<td height="10px"><span>Wt : 4.120</span></td>

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
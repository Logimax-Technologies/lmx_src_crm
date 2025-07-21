<link rel="stylesheet" href="<?php echo base_url();?>assets/css/cockpit.css">
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<style type="text/css">
    .tab-content>.tab-pane {
      height: 1px;
      overflow: hidden;
      display: block;
     visibility: hidden;
    }
    .tab-content>.active {
      height: auto;
      overflow: auto;
      visibility: visible;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="row cock_pit">
	<div class="col-md-12 col-xs-12 container-row">
		<div class="col-md-12 col-xs-12 box-items no-paddingwidth">
			<div class="col-md-12 col-xs-12 sales no-paddingwidth blog-box">
				<div class="col-md-12 col-xs-12 dash-item-heading">
					Stock by branch
				</div>
				<div class="col-md-12 col-xs-12">
					<div id="stock_by_branch" class="col-md-12 col-xs-12 no-paddingwidth inside-items" style="width: 100%; height: 350px;">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-12 col-xs-12 container-row">
		<div class="col-md-6 col-xs-12 box-items no-paddingwidth">
			<div class="col-md-12 col-xs-12 sales no-paddingwidth blog-box">
				<div class="col-md-12 col-xs-12 dash-item-heading">
					Stock by collections
				</div>
				<div class="col-md-12 col-xs-12">
					<div id="stock_by_collection" class="col-md-12 col-xs-12 no-paddingwidth inside-items" style="width: 100%; height: 320px;">
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-xs-12 box-items no-paddingwidth">
			<div class="col-md-12 col-xs-12 sales no-paddingwidth blog-box">
				<div class="col-md-12 col-xs-12 dash-item-heading">
					Stock by products
				</div>
				<div class="col-md-12 col-xs-12">
					<div id="stock_by_product" class="col-md-12 col-xs-12 no-paddingwidth inside-items" style="width: 100%; height: 320px;">
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-md-12 col-xs-12 container-row">
		<div class="col-md-6 col-xs-12 box-items no-paddingwidth">
			<div class="col-md-12 col-xs-12 sales no-paddingwidth blog-box">
				<div class="col-md-12 col-xs-12 dash-item-heading">
					Branch Trasfer
				</div>
				<div class="col-md-12 col-xs-12">
					<div id="bt_approval_pending" class="col-md-12 col-xs-12 no-paddingwidth inside-items" style="width: 100%; height: 320px;">
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-xs-12 box-items no-paddingwidth">
			<div class="col-md-12 col-xs-12 sales no-paddingwidth blog-box">
				<div class="col-md-12 col-xs-12 dash-item-heading">
					E-Com Stock
				</div>
				<div class="col-md-12 col-xs-12">
					<div id="ecom_stock" class="col-md-12 col-xs-12 no-paddingwidth inside-items" style="width: 100%; height: 320px;">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>	
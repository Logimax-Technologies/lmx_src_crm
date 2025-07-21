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
		<div class="col-md-2 col-xs-12 box-items no-paddingwidth">
			<div class="col-md-12 col-xs-12 estimation no-paddingwidth dash-blog-box">
				<div class="col-md-12 col-xs-12 no-paddingwidth">
					<div class="col-md-12 col-xs-12 no-paddingwidth">
						<a href="<?php echo base_url().'index.php/admin_ret_estimation/estimation/list/converted';?>">
							<div class="no-paddingwidth label-dash-value" id="sale_total_value">
								
							</div>
						</a>
					</div>
				</div>
				<div class="col-md-12 col-xs-12 item-heading alignTxtCenter">
					<i class="fa fa-cart-plus" aria-hidden="true"></i>Sales
				</div>
			</div>
			<div class="col-md-12 col-xs-12 estimation no-paddingwidth dash-blog-box">
				<div class="col-md-12 col-xs-12 no-paddingwidth">
					<div class="col-md-12 col-xs-12 no-paddingwidth">
						<a href="<?php echo base_url().'index.php/admin_ret_estimation/estimation/list/converted';?>">
							<div class="no-paddingwidth label-dash-value" id="sales_estimated">
								
							</div>
						</a>
					</div>
				</div>
				<div class="col-md-12 col-xs-12 item-heading alignTxtCenter">
					<i class="fa fa-calculator" aria-hidden="true"></i>Estimated
				</div>
			</div>
			<div class="col-md-12 col-xs-12 estimation no-paddingwidth dash-blog-box">
				<div class="col-md-12 col-xs-12 no-paddingwidth">
					<div class="col-md-12 col-xs-12 no-paddingwidth">
						<div style="width: 100%; height: 100px;" class="no-paddingwidth label-dash-value" id="sales_converted">
						</div>
					</div>
				</div>
				<div class="col-md-12 col-xs-12 item-heading alignTxtCenter">
					<i class="fa fa-university" aria-hidden="true"></i>Billed
				</div>
			</div>
			
		</div>
		<div class="col-md-4 col-xs-12 box-items no-paddingwidth">
			<div class="col-md-12 col-xs-12 sales no-paddingwidth blog-box">
				<div class="col-md-12 col-xs-12 dash-item-heading">
					Sales by branch
				</div>
				<div class="col-md-12 col-xs-12">
					<div id="sales_by_branch" class="col-md-12 col-xs-12 no-paddingwidth inside-items" style="width: 100%; height: 220px;">
					</div>
				</div>
			</div>
		</div>
		<!--<div class="col-md-6 col-xs-12 box-items no-paddingwidth">
			<div class="col-md-12 col-xs-12 sales no-paddingwidth blog-box">
				<div class="col-md-12 col-xs-12 dash-item-heading">
					Sales by collection
				</div>
				<div class="col-md-12 col-xs-12">
					<div id="sales_by_collection" class="col-md-12 col-xs-12 no-paddingwidth inside-items" style="width: 100%; height: 220px;">
					</div>
				</div>
			</div>
		</div>-->
	</div>
	
	<div class="col-md-12 col-xs-12 container-row">
		<div class="col-md-2 col-xs-12 box-items no-paddingwidth">
			<div class="col-md-12 col-xs-12 estimation no-paddingwidth dash-blog-box">
				<div class="col-md-12 col-xs-12 no-paddingwidth">
					<div class="col-md-12 col-xs-12 no-paddingwidth">
						<a href="<?php echo base_url().'index.php/admin_ret_estimation/estimation/list/converted';?>">
							<div class="no-paddingwidth label-dash-value" id="sales_gross_profit">
								-
							</div>
						</a>
					</div>
				</div>
				<div class="col-md-12 col-xs-12 item-heading alignTxtCenter">
					<i class="fa fa-line-chart" aria-hidden="true"></i>Gross Profit
				</div>
			</div>
			<div class="col-md-12 col-xs-12 estimation no-paddingwidth dash-blog-box">
				<div class="col-md-12 col-xs-12 no-paddingwidth">
					<div class="col-md-12 col-xs-12 no-paddingwidth">
						<a href="<?php echo base_url().'index.php/admin_ret_estimation/estimation/list/converted';?>">
							<div class="no-paddingwidth label-dash-value" id="sales_gp_margin">
								-
							</div>
						</a>
					</div>
				</div>
				<div class="col-md-12 col-xs-12 item-heading alignTxtCenter">
					<i class="fa fa-cart-plus" aria-hidden="true"></i>Gross Profit Margin
				</div>
			</div>
			<div class="col-md-12 col-xs-12 estimation no-paddingwidth dash-blog-box">
				<div class="col-md-12 col-xs-12 no-paddingwidth">
					<div class="col-md-12 col-xs-12 no-paddingwidth">
						<a href="<?php echo base_url().'index.php/admin_ret_estimation/estimation/list/converted';?>">
							<div class="no-paddingwidth label-dash-value" id="sales_returned">
								
							</div>
						</a>
					</div>
				</div>
				<div class="col-md-12 col-xs-12 item-heading alignTxtCenter">
					<i class="fa fa-undo" aria-hidden="true"></i>Returned
				</div>
			</div>
			
		</div>
		<div class="col-md-4 col-xs-12 box-items no-paddingwidth">
			<div class="col-md-12 col-xs-12 sales no-paddingwidth blog-box">
				<div class="col-md-12 col-xs-12 dash-item-heading">
					Sales Mode
				</div>
				<div class="col-md-12 col-xs-12">
					<div style="width: 100%; height: 220px;" id="sales_pay_mode" class="col-md-12 col-xs-12 no-paddingwidth inside-items">
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-xs-12 box-items no-paddingwidth">
			<div class="col-md-12 col-xs-12 sales no-paddingwidth blog-box">
				<div class="col-md-12 col-xs-12 dash-item-heading">
					Sales by products
				</div>
				<div class="col-md-12 col-xs-12">
					<div style="width: 100%; height: 220px;" id="sales_by_product" class="col-md-12 col-xs-12 no-paddingwidth inside-items">
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-12 col-xs-12 container-row">
		<div class="col-md-2 col-xs-12 box-items no-paddingwidth">
			<div class="col-md-12 col-xs-12 estimation no-paddingwidth dash-blog-box">
				<div class="col-md-12 col-xs-12 no-paddingwidth">
					<div class="col-md-12 col-xs-12 no-paddingwidth">
						<a href="<?php echo base_url().'index.php/admin_ret_estimation/estimation/list/converted';?>">
							<div class="no-paddingwidth label-dash-value" id="sales_credit">
								
							</div>
						</a>
					</div>
				</div>
				<div class="col-md-12 col-xs-12 item-heading alignTxtCenter">
					<i class="fa fa-line-chart" aria-hidden="true"></i>Credit Sale
				</div>
			</div>
			<div class="col-md-12 col-xs-12 estimation no-paddingwidth dash-blog-box">
				<div class="col-md-12 col-xs-12 no-paddingwidth">
					<div class="col-md-12 col-xs-12 no-paddingwidth">
						<a href="<?php echo base_url().'index.php/admin_ret_estimation/estimation/list/converted';?>">
							<div class="no-paddingwidth label-dash-value" id="sales_green_tag">
								
							</div>
						</a>
					</div>
				</div>
				<div class="col-md-12 col-xs-12 item-heading alignTxtCenter">
					<i class="fa fa-cart-plus" aria-hidden="true"></i>Green Tag
				</div>
			</div>
		</div>
		<div class="col-md-6 col-xs-12 box-items no-paddingwidth">
			<div class="col-md-12 col-xs-12 sales no-paddingwidth blog-box">
				<div class="col-md-12 col-xs-12 dash-item-heading">
					Sales Growth
				</div>
				<div class="col-md-12 col-xs-12">
					<div id="sales_growth" class="col-md-12 col-xs-12 no-paddingwidth inside-items">
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-xs-12 box-items no-paddingwidth">
			<div class="col-md-12 col-xs-12 sales no-paddingwidth blog-box">
				<div class="col-md-12 col-xs-12 dash-item-heading">
					Customer Visit
				</div>
				<div class="col-md-12 col-xs-12">
					<div id="sales_customer_visit" class="col-md-12 col-xs-12 no-paddingwidth inside-items">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>	
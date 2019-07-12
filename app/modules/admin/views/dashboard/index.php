<?php
/* @var $this \yii\web\View */

$this->title = 'Dashboard';
$this->params['subheading'] = 'Example';
?>

<div class="row">
	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="info-box">
			<span class="info-box-icon bg-aqua"><i class="fa fa-cog"></i></span>

			<div class="info-box-content">
				<span class="info-box-text">CPU Traffic</span>
				<span class="info-box-number">90<small>%</small></span>
			</div>
			<!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	</div>
	<!-- /.col -->
	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="info-box">
			<span class="info-box-icon bg-red"><i class="fa fa-google-plus"></i></span>

			<div class="info-box-content">
				<span class="info-box-text">Likes</span>
				<span class="info-box-number">41,410</span>
			</div>
			<!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	</div>
	<!-- /.col -->

	<!-- fix for small devices only -->
	<div class="clearfix visible-sm-block"></div>

	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="info-box">
			<span class="info-box-icon bg-green"><i class="fa fa-shopping-basket"></i></span>

			<div class="info-box-content">
				<span class="info-box-text">Sales</span>
				<span class="info-box-number">760</span>
			</div>
			<!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	</div>
	<!-- /.col -->
	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="info-box">
			<span class="info-box-icon bg-yellow"><i class="fa fa-users"></i></span>

			<div class="info-box-content">
				<span class="info-box-text">New Members</span>
				<span class="info-box-number">2,000</span>
			</div>
			<!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	</div>
	<!-- /.col -->
</div>

<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Latest Orders</h3>

		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
			</button>
			<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
		</div>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
		<div class="table-responsive">
			<table class="table no-margin">
				<thead>
				<tr>
					<th>Order ID</th>
					<th>Item</th>
					<th>Status</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td><a href="pages/examples/invoice.html">OR9842</a></td>
					<td>Call of Duty IV</td>
					<td><span class="label label-success">Shipped</span></td>
				</tr>
				<tr>
					<td><a href="pages/examples/invoice.html">OR1848</a></td>
					<td>Samsung Smart TV</td>
					<td><span class="label label-warning">Pending</span></td>
				</tr>
				<tr>
					<td><a href="pages/examples/invoice.html">OR7429</a></td>
					<td>iPhone 6 Plus</td>
					<td><span class="label label-danger">Delivered</span></td>
				</tr>
				<tr>
					<td><a href="pages/examples/invoice.html">OR7429</a></td>
					<td>Samsung Smart TV</td>
					<td><span class="label label-info">Processing</span></td>
				</tr>
				<tr>
					<td><a href="pages/examples/invoice.html">OR1848</a></td>
					<td>Samsung Smart TV</td>
					<td><span class="label label-warning">Pending</span></td>
				</tr>
				<tr>
					<td><a href="pages/examples/invoice.html">OR7429</a></td>
					<td>iPhone 6 Plus</td>
					<td><span class="label label-danger">Delivered</span></td>
				</tr>
				<tr>
					<td><a href="pages/examples/invoice.html">OR9842</a></td>
					<td>Call of Duty IV</td>
					<td><span class="label label-success">Shipped</span></td>
				</tr>
				</tbody>
			</table>
		</div>
		<!-- /.table-responsive -->
	</div>
	<!-- /.box-body -->
</div>

<?php
/* @var $this \yii\web\View */

$this->title = 'Dashboard';
$this->params['subheading'] = 'Example';
?>

<div class="row">
	<div class="col-12 col-sm-6 col-md-3">
		<div class="info-box">
			<span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>

			<div class="info-box-content">
				<span class="info-box-text">CPU Traffic</span>
				<span class="info-box-number">
                	10 <small>%</small>
                </span>
			</div>
			<!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	</div>
	<!-- /.col -->
	<div class="col-12 col-sm-6 col-md-3">
		<div class="info-box mb-3">
			<span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-up"></i></span>

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
	<div class="clearfix hidden-md-up"></div>

	<div class="col-12 col-sm-6 col-md-3">
		<div class="info-box mb-3">
			<span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>

			<div class="info-box-content">
				<span class="info-box-text">Sales</span>
				<span class="info-box-number">760</span>
			</div>
			<!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	</div>
	<!-- /.col -->
	<div class="col-12 col-sm-6 col-md-3">
		<div class="info-box mb-3">
			<span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

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

<div class="card">
	<div class="card-header border-transparent">
		<h3 class="card-title">Latest Orders</h3>

		<div class="card-tools">
			<button type="button" class="btn btn-tool" data-widget="collapse">
				<i class="fas fa-minus"></i>
			</button>
			<button type="button" class="btn btn-tool" data-widget="remove">
				<i class="fas fa-times"></i>
			</button>
		</div>
	</div>
	<!-- /.card-header -->
	<div class="card-body p-0">
		<div class="table-responsive">
			<table class="table m-0">
				<thead>
				<tr>
					<th>Order ID</th>
					<th>Item</th>
					<th>Status</th>
					<th>Popularity</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td><a href="pages/examples/invoice.html">OR9842</a></td>
					<td>Call of Duty IV</td>
					<td><span class="badge badge-success">Shipped</span></td>
					<td>
						<div class="sparkbar" data-color="#00a65a" data-height="20">90,80,90,-70,61,-83,63</div>
					</td>
				</tr>
				<tr>
					<td><a href="pages/examples/invoice.html">OR1848</a></td>
					<td>Samsung Smart TV</td>
					<td><span class="badge badge-warning">Pending</span></td>
					<td>
						<div class="sparkbar" data-color="#f39c12" data-height="20">90,80,-90,70,61,-83,68</div>
					</td>
				</tr>
				<tr>
					<td><a href="pages/examples/invoice.html">OR7429</a></td>
					<td>iPhone 6 Plus</td>
					<td><span class="badge badge-danger">Delivered</span></td>
					<td>
						<div class="sparkbar" data-color="#f56954" data-height="20">90,-80,90,70,-61,83,63</div>
					</td>
				</tr>
				<tr>
					<td><a href="pages/examples/invoice.html">OR7429</a></td>
					<td>Samsung Smart TV</td>
					<td><span class="badge badge-info">Processing</span></td>
					<td>
						<div class="sparkbar" data-color="#00c0ef" data-height="20">90,80,-90,70,-61,83,63</div>
					</td>
				</tr>
				<tr>
					<td><a href="pages/examples/invoice.html">OR1848</a></td>
					<td>Samsung Smart TV</td>
					<td><span class="badge badge-warning">Pending</span></td>
					<td>
						<div class="sparkbar" data-color="#f39c12" data-height="20">90,80,-90,70,61,-83,68</div>
					</td>
				</tr>
				<tr>
					<td><a href="pages/examples/invoice.html">OR7429</a></td>
					<td>iPhone 6 Plus</td>
					<td><span class="badge badge-danger">Delivered</span></td>
					<td>
						<div class="sparkbar" data-color="#f56954" data-height="20">90,-80,90,70,-61,83,63</div>
					</td>
				</tr>
				<tr>
					<td><a href="pages/examples/invoice.html">OR9842</a></td>
					<td>Call of Duty IV</td>
					<td><span class="badge badge-success">Shipped</span></td>
					<td>
						<div class="sparkbar" data-color="#00a65a" data-height="20">90,80,90,-70,61,-83,63</div>
					</td>
				</tr>
				</tbody>
			</table>
		</div>
		<!-- /.table-responsive -->
	</div>
	<!-- /.card-body -->
	<div class="card-footer clearfix">
		<a href="javascript:void(0)" class="btn btn-sm btn-info float-left">Place New Order</a>
		<a href="javascript:void(0)" class="btn btn-sm btn-secondary float-right">View All Orders</a>
	</div>
	<!-- /.card-footer -->
</div>
<?php

use yii\helpers\Html;
use yii\bootstrap4\Nav;
use app\modules\admin\widgets\Menu;

?>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4 sidebar-dark-info">
	<!-- Brand Logo -->
	<a href="<?= Yii::$app->homeUrl; ?>" class="brand-link">
		<span class="brand-image" style="opacity: .8; line-height: 1.8em">APP</span>
		<span class="brand-text font-weight-light"><?= Html::encode(Yii::$app->name); ?></span>
	</a>

	<!-- Sidebar -->
	<div class="sidebar">
		<!-- Sidebar user panel (optional) -->
		<div class="user-panel mt-3 pb-3 mb-3 d-flex">
			<div class="image">
				<img src="<?= $adminlteAssets; ?>/img/user2-160x160.jpg" class="img-circle elevation-2"
					 alt="User Image">
			</div>
			<div class="info flex-grow-1">
				<a href="#" class="d-block"><?= Html::encode(Yii::$app->user->identity->getFullName()) ?></a>
			</div>
			<div class="align-self-end">
				<?= Html::a(
					'<i class="fa fa-power-off"></i>',
					['/auth/logout'],
					[
						'title'       => 'Sign Out',
						'data-method' => 'post',
						'class'       => 'd-block',
						'style'       => 'line-height: 2em;',
					]
				); ?>
			</div>
		</div>
		<!-- Sidebar Menu -->
		<nav class="mt-2">
			<?= Menu::widget([
				'options' => [
					'class'          => 'nav nav-pills nav-sidebar flex-column',
					'data-widget'    => 'treeview',
					'role'           => 'menu',
					'data-accordion' => 'false',
				],
				'itemOptions' => [
					'class' => 'nav-item',
				],
				'items'   => [
					[
						'label' => 'Dashboard',
						'icon' => 'tachometer-alt',
						'url' => ['/admin/dashboard'],
						'active' => 'dashboard' === Yii::$app->controller->id,
					],
					[
						'label'  => 'Users',
						'icon'   => 'users',
						'url'    => ['/admin/users'],
						'active' => 'users' === Yii::$app->controller->id,
					],
					[
						'label'  => 'Permissions',
						'icon'   => 'lock',
						'url'    => ['/admin/rbac/permissions'],
						'active' => 'permissions' === Yii::$app->controller->id,
					],
					[
						'label' => 'Settings',
						'icon'  => 'cog',
						'url'   => '#',
						'items' => [
							[
								'label' => 'App',
								'url' => ['/admin/settings/app'],
							],
						],
					],
					[
						'label' => 'Developer',
						'icon'  => 'code',
						'url'   => '#',
						'items' => [
							['label' => 'Gii', 'url' => ['/gii'],],
							['label' => 'Debug', 'url' => ['/debug'],],
						],
					],
				],
			]) ?>
		</nav>
		<?php /*
		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
				<!-- Add icons to the links using the .nav-icon class
					 with font-awesome or any other icon font library -->
				<li class="nav-item has-treeview menu-open">
					<a href="#" class="nav-link active">
						<i class="nav-icon fas fa-tachometer-alt"></i>
						<p>
							Dashboard
							<i class="right fas fa-angle-left"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="./index.html" class="nav-link active">
								<i class="far fa-circle nav-icon"></i>
								<p>Dashboard v1</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="./index2.html" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Dashboard v2</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="./index3.html" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Dashboard v3</p>
							</a>
						</li>
					</ul>
				</li>
				<li class="nav-item">
					<a href="pages/widgets.html" class="nav-link">
						<i class="nav-icon fas fa-th"></i>
						<p>
							Widgets
							<span class="right badge badge-danger">New</span>
						</p>
					</a>
				</li>
				<li class="nav-item has-treeview">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-copy"></i>
						<p>
							Layout Options
							<i class="fas fa-angle-left right"></i>
							<span class="badge badge-info right">6</span>
						</p>
					</a>
				</li>
			</ul>
		</nav>
	</div>
	<section class="sidebar">
		<?php /*
		<?= dmstr\widgets\Menu::widget(
			[
				'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree',],
				'items'   => [
					['label' => 'MAIN NAVIGATION', 'options' => ['class' => 'header']],
					['label' => 'Dashboard', 'icon' => 'dashboard', 'url' => ['/admin/dashboard']],
					['label' => 'Users',
					 	'icon' => 'users',
					 	'url' => ['/admin/users'],
					 	'active' => 'users' === Yii::$app->controller->id,
					],
					['label' => 'Permissions',
					 'icon' => 'lock',
					 'url' => ['/admin/rbac/permissions'],
					 'active' => 'permissions' === Yii::$app->controller->id,
					],
					[
						'label' => 'Settings',
						'icon'  =>  'gears',
						'url'   => '#',
						'items' => [
							['label' => 'App', 'url' => ['/admin/settings/app']],
						],
					],
					[
						'label' => 'Drop example',
						'icon'  => 'share',
						'url'   => '#',
						'items' => [
							['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'],],
							['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'],],
							[
								'label' => 'Level One',
								'icon'  => 'circle-o',
								'url'   => '#',
								'items' => [
									['label' => 'Level Two', 'icon' => 'circle-o', 'url' => '#',],
									[
										'label' => 'Level Two',
										'icon'  => 'circle-o',
										'url'   => '#',
										'items' => [
											['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
											['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
										],
									],
								],
							],
						],
					],
				],
			]
		) ?>
*/ ?>
	</div>

</aside>

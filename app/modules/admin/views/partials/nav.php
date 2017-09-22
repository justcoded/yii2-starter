<aside class="main-sidebar">

	<section class="sidebar">

		<?= dmstr\widgets\Menu::widget(
			[
				'options' => ['class' => 'sidebar-menu'],
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
					 'url' => ['/rbacgui/permissions/index'],
					 'active' => 'permissions' === Yii::$app->controller->id,
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

	</section>

</aside>

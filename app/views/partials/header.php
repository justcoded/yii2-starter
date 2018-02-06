<?php

/* @var $this \yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use justcoded\yii2\rbac\models\Item as RbacItem;

NavBar::begin([
	'brandLabel' => 'My Company',
	'brandUrl'   => Yii::$app->homeUrl,
	'options'    => [
		'class' => 'navbar-inverse navbar-fixed-top',
	],
]);
echo Nav::widget([
	'options' => ['class' => 'navbar-nav navbar-right'],
	'items'   => [
		['label' => 'Home', 'url' => ['/site/index']],
		['label' => 'About', 'url' => ['/site/about']],
		['label' => 'Contact', 'url' => ['/site/contact']],

		['label' => 'Admin Panel', 'url' => ['/admin'], 'visible' => user()->can(RbacItem::PERMISSION_ADMINISTER)],
		Yii::$app->user->isGuest ? (
		['label' => 'Login', 'url' => ['/auth/login']]
		) : (
			'<li>'
			. Html::beginForm(['/auth/logout'], 'post')
			. Html::submitButton(
				'Logout (' . Yii::$app->user->identity->username . ')',
				['class' => 'btn btn-link logout']
			)
			. Html::endForm()
			. '</li>'
		)
	],
]);
NavBar::end();



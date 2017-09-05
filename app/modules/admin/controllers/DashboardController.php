<?php

namespace app\modules\admin\controllers;

class DashboardController extends Controller
{
	public function actionIndex()
	{
		return $this->render('index');
	}
}
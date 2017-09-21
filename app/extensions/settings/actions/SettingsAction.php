<?php

namespace app\extensions\settings\actions;

use app\helpers\Session;
use yii\base\Action;
use Yii;
use yii\base\Model;

class SettingsAction extends Action
{
	/**
	 * @var Model
	 */
	public $modelClass;
	
	/**
	 * @var string
	 */
	public $viewPath;
	
	/**
	 * @var string
	 */
	public $message = 'The settings has been saved successfully';
	
	/**
	 * View file example: app/extensions/settings/views/app.php
	 *
	 * @inheritdoc
	 */
	public function run()
	{
		$model = $this->setModel($this->modelClass);
		if ($model->load(\Yii::$app->request->post()) && $model->save()) {
			Session::addFlash($this->message, 'success');
		}
		return $this->controller->render($this->viewPath ?: $this->controller->action->id, ['model' => $model]);
	}
	
	/**
	 * @param $modelClass
	 *
	 * @return Model
	 */
	protected function setModel($modelClass)
	{
		$model = new $modelClass;
		return $model->loadData();
	}
}
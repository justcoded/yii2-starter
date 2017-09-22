<?php

namespace justcoded\yii2\settings\actions;

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
			Yii::$app->session->addFlash('success', $this->message);
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
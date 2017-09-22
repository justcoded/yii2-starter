<?php

namespace justcoded\yii2\settings\actions;

use yii\base\Action;
use Yii;
use yii\base\Model;

class SettingsAction extends Action
{
	/**
	 * Model, which properties should be stored as settings
	 *
	 * @var Model
	 */
	public $modelClass;
	
	/**
	 * Where to look for view. By default look in /views/controller_id/action_id
	 *
	 * @var string
	 */
	public $viewPath;
	
	/**
	 * Message to display on success result
	 *
	 * @var string
	 */
	public $message = 'The settings has been saved successfully';
	
	/**
	 * View file example: justcoded/yii2/settings/views/app.php
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
	 * Method to get model with loaded properties
	 *
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
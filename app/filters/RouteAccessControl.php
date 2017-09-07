<?php

namespace app\filters;

use Yii;
use yii\base\Action;
use yii\base\ActionFilter;
use yii\filters\AccessRule;
use yii\web\ErrorAction;
use yii\web\ForbiddenHttpException;

class RouteAccessControl extends ActionFilter
{
	/**
	 * @var array List of action that not need to check access.
	 */
	public $allowActions = [];

	/**
	 * This method is invoked right before an action is to be executed (after all possible filters.)
	 * Check $allowActions or RBAC permission for such entries:
	 *      {controller}/{action}   (or  {module}/{controller}/{action})
	 *      {controller}/*          (or  {module}/{controller}/*)
	 *
	 * @param Action $action the action to be executed.
	 * @return bool whether the action should continue to be executed.
	 */
	public function beforeAction($action)
	{
		// check for error action, otherwise and prevent throwing new exception.
		if (is_a($action, ErrorAction::class)) {
			return true;
		}

		$action_rule = "{$action->controller->uniqueId}/{$action->id}";
		$controller_rule = "{$action->controller->uniqueId}/*";

		if (in_array($action_rule, $this->allowActions)
		    || in_array($controller_rule, $this->allowActions)
		) {
			$allow = true;
		} else {
			$allow = Yii::$app->user->can($action_rule);
		}

		if (!$allow) {
			$this->denyAccess();
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Deny access method
	 *
	 * @throws ForbiddenHttpException
	 */
	public function denyAccess()
	{
		throw new ForbiddenHttpException('You are not allowed to perform this action.');
	}
}
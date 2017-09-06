<?php

namespace app\components\rbac;

use yii\db\Query;

class DbManager extends \yii\rbac\DbManager
{
	/**
	 * @inheritdoc
	 * @return bool
	 */
	public function checkAccess($userId, $permissionName, $params = [])
	{
		return parent::checkAccess($userId, 'everything', $params) ||
			parent::checkAccess($userId, $permissionName, $params);
	}

}
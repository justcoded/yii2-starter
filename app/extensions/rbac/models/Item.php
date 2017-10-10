<?php

namespace justcoded\yii2\rbac\models;

use Yii;
use yii\rbac\Item as RbacItem;

class Item
{
	const TYPE_ROLE = 1;
	const TYPE_PERMISSION = 2;

	const ROLE_GUEST = 'Guest';
	const ROLE_AUTHENTICATED = 'Authenticated';
	const ROLE_ADMIN = 'Administrator';
	const ROLE_MASTER = 'Master';

	const PERMISSION_ADMINISTER = 'administer';
	const PERMISSION_MASTER = '*';

	/**
	 * @param RbacItem $parent
	 * @param string[] $childNames
	 *
	 * @return bool
	 */
	public static function addChilds(RbacItem $parent, $childNames, $type = RbacItem::TYPE_PERMISSION)
	{
		if (empty($childNames)) return false;
		if (!is_array($childNames)) $childNames = [$childNames];

		$auth = Yii::$app->authManager;

		foreach ($childNames as $childName) {
			$item = (RbacItem::TYPE_ROLE === $type) ?
				$auth->getRole($childName) :
				$auth->getPermission($childName);

			if ($item && ! $auth->hasChild($parent, $item)) {
				$auth->addChild($parent, $item);
			}
		}

		return true;
	}

}

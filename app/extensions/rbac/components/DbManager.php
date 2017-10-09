<?php

namespace justcoded\yii2\rbac\components;

use justcoded\yii2\rbac\models\Item;
use yii\db\Query;
use yii\rbac\Permission;

class DbManager extends \yii\rbac\DbManager
{
	/**
	 * @var Permission|null
	 */
	protected $_masterPermission;

	/**
	 * @inheritdoc
	 */
	protected function addItem($item)
	{
		if (parent::addItem($item)) {
			// add master permission as parent to any new item of Permission type.
			if ($item instanceof Permission
			    && Item::PERMISSION_MASTER !== $item->name
			    && $master = $this->getMasterPermission()
			) {
				$this->addChild($master, $item);
			}

			// add everything permission as parent
			return true;
		}
		return false;
	}

	/**
	 * Find master permission object.
	 *
	 * @return null|\yii\rbac\Item
	 */
	protected function getMasterPermission()
	{
		if (empty($this->_masterPermission)) {
			$this->_masterPermission = $this->getItem(Item::PERMISSION_MASTER);
		}

		return $this->_masterPermission;
	}
}

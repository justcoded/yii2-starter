<?php

namespace app\console\controllers;

use app\models\User;
use Yii;
use yii\rbac\Permission;
use yii\rbac\Role;
use yii\rbac\Rule;

class RbacController extends Controller
{
	/**
	 * Define default roles
	 */
	public function actionInit()
	{
		$auth = Yii::$app->authManager;
		$auth->removeAll();

		$everything = $this->addPermission('everything', 'Allow Everything');
		$administer = $this->addPermission('administer', 'Access administration panel.');

		$guest = $this->addRole(User::ROLE_GUEST, 'Usual site visitor.');

		$authenticated = $this->addRole(User::ROLE_AUTHENTICATED, 'Authenticated user.');

		$admin = $this->addRole(User::ROLE_ADMIN, 'Administrator.', [$authenticated, $administer]);

		$sys_admin = $this->addRole(User::ROLE_SYS_ADMIN, 'Has full system access.', [
			$everything,
		]);

		// Assign sys admin roles to user 1
		$auth->assign($sys_admin, 1);

		$this->line();
		$this->warning("Assigned System Administrator to first user\n");

		$this->success("Finished.");
	}

	/**
	 * Scans controller/module directories to get route permissions info
	 */
	public function actionScan()
	{
		// TODO: remove hardcode and get info
		$auth = Yii::$app->authManager;

		$userAll = $this->addPermission('admin/users/*', '');
		$userIndex = $this->addPermission('admin/users/index', '');
		$userCreate = $this->addPermission('admin/users/create', '');

		$auth->addChild($userAll, $userIndex);

		$admin = $auth->getRole(User::ROLE_ADMIN);
		$auth->addChild($admin, $userCreate);
		$auth->addChild($admin, $userAll);

		$this->success("Done");
	}

	/**
	 * @param string    $name
	 * @param string    $descr
	 * @param Rule|null $rule
	 *
	 * @return \yii\rbac\Permission
	 */
	protected function addPermission($name, $descr, $rule = null)
	{
		$auth = Yii::$app->authManager;

		$p = $auth->createPermission($name);
		$p->description = $descr;
		if ($rule) {
			$p->ruleName = $rule->name;
		}
		$auth->add($p);

		return $p;
	}

	/**
	 * @param string              $name
	 * @param string              $descr
	 * @param Permission[]|Role[] $childs
	 *
	 * @return Role
	 */
	protected function addRole($name, $descr, $childs = [])
	{
		$auth = Yii::$app->authManager;

		$r = $auth->createRole($name);
		$r->description = $descr;
		$auth->add($r);
		$this->warning("Added {$r->name} role");

		if ($childs) {
			foreach ($childs as $child) {
				$auth->addChild($r, $child);
				$this->line("\tAdded '{$child->name}' to {$r->name}");
			}
		}

		return $r;
	}

}

<?php

namespace app\console\controllers;

use app\models\User;
use FilesystemIterator;
use Yii;
use yii\helpers\Inflector;
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

		$sysAdmin = $this->addRole(User::ROLE_SYS_ADMIN, 'Has full system access.', [
			$everything,
		]);

		// Assign sys admin roles to user 1
		$auth->assign($sysAdmin, 1);

		$this->line();
		$this->warning("Assigned System Administrator to first user\n");

		$this->success("Finished.");
	}

	/**
	 * Scans controller/module directories to get route permissions info
	 *
	 *      runs recursive search for *Controller.php, ignores files, which specified in ignorePath
	 *      register auth Permissions based on Controller public actions (methods in format "action{something}")
	 *
	 * @param string $directory
	 * @param array  $ignorePath
	 */
	public function actionScan($directory = '@app', array $ignorePath = ['app/console'])
	{
		$path = Yii::getAlias($directory);
		$controllers = $this->scanDirectory($path, $ignorePath);
		$this->warning('Found ' . count($controllers) . ' controllers');

		$actionRoutes = $this->scanControllerRoutes($controllers);
		$this->line("\tfound " . count($actionRoutes) . ' routes');

		$auth = Yii::$app->authManager;
		$inserted = 0;
		foreach ($actionRoutes as $route) {
			if (! $auth->getPermission($route)) {
				$this->addPermission($route, 'Route ' . $route);
				$inserted++;
			}
		}

		$this->success("Inserted {$inserted} new permissions.");
	}

	/**
	 * Recursive scan of directory searching the controllers.
	 *
	 * @param string $directory
	 * @param array  $ignorePath
	 *
	 * @return array
	 */
	protected function scanDirectory(string $directory, array $ignorePath)
	{
		$ignoreRegexp = false;
		if (! empty($ignorePath) && is_array($ignorePath)) {
			$ignoreRegexp = '('.implode('|', $ignorePath).')';
		}

		// register iterator to find files
		$dirIt = new \RecursiveDirectoryIterator($directory);
		$it = new \RecursiveIteratorIterator($dirIt);

		// get all controllers
		$allControllers = new \RegexIterator(
			$it,
			'/^.+Controller\.php$/',
			\RecursiveRegexIterator::GET_MATCH
		);
		// find controllers, which should be ignored
		$ignoredControllers = new \RegexIterator(
			$allControllers,
			"#^.+{$ignoreRegexp}.+Controller\.php$#",
			\RecursiveRegexIterator::GET_MATCH,
			\RecursiveRegexIterator::USE_KEY
		);

		$allControllers = array_keys(iterator_to_array($allControllers));
		$ignoredControllers = array_keys(iterator_to_array($ignoredControllers));

		$controllers = array_diff($allControllers, $ignoredControllers);
		return $controllers;
	}

	/**
	 * Scan controllers by filenames to find their public action routes.
	 *
	 * @param array $controllers
	 *
	 * @return array
	 */
	protected function scanControllerRoutes(array $controllers)
	{
		$actions = [];
		foreach ($controllers as $filename) {
			$content = file_get_contents($filename);

			// ignore abstract classes
			if (false !== strpos($content, 'abstract class')) {
				continue;
			}

			if (! preg_match('/namespace\s+([a-z0-9_\\\\]+)/i', $content, $namespaceMatch)) {
				continue;
			}

			if (! preg_match('/class\s+(([a-z0-9_]+)Controller)[^{]+{/i', $content, $classMatch)) {
				continue;
			}

			$className = '\\' . $namespaceMatch[1] . '\\' . $classMatch[1];
			$reflection = new \ReflectionClass($className);
			$methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);

			$moduleId = '';
			if (preg_match('/modules\\\\([a-z0-9_-]+)\\\\/i', $reflection->getNamespaceName(), $moduleMatch)) {
				$moduleId = Inflector::slug(Inflector::camel2words($moduleMatch[1])) . '/';
			}

			foreach ($methods as $method) {
				if (! preg_match('/^action([A-Z]([a-zA-Z0-9]+))$/', $method->getName(), $actionMatch)) {
					continue;
				}
				$controllerId = Inflector::slug(Inflector::camel2words($classMatch[2]));
				$actionId = Inflector::slug(Inflector::camel2words($actionMatch[1]));

				$actions[] = $moduleId . $controllerId . '/' . $actionId;
			}
		}

		return $actions;
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

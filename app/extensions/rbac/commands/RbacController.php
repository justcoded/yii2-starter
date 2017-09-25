<?php

namespace justcoded\yii2\rbac\commands;

use justcoded\yii2\rbac\models\Item;
use Yii;
use yii\base\InvalidParamException;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;
use yii\helpers\Inflector;
use yii\rbac\Permission;
use yii\rbac\Role;
use yii\rbac\Rule;

class RbacController extends Controller
{
	protected $itemsCache;

	/**
	 * Init default roles
	 * @return int
	 */
	public function actionInit()
	{
		$this->warning('Init command will clean all your current roles, permissions and assignments.');
		$confirm = $this->prompt('Are you sure you want to continue? (yes/no)', [
			'required'  => true,
		]);
		if ('y' !== strtolower($confirm){0}) {
			$this->line("\nTerminating.");
			return ExitCode::DATAERR;
		}

		$am = Yii::$app->authManager;
		$am->removeAll();

		$everything = $this->addPermission(Item::PERMISSION_MASTER, 'Allow Everything');
		$administer = $this->addPermission(Item::PERMISSION_ADMINISTER, 'Access administration panel.');

		$guest = $this->addRole(Item::ROLE_GUEST, 'Usual site visitor.');

		$authenticated = $this->addRole(Item::ROLE_AUTHENTICATED, 'Authenticated user.');

		$admin = $this->addRole(Item::ROLE_ADMIN, 'Administrator.', [
			$administer
		]);

		$sysAdmin = $this->addRole(Item::ROLE_MASTER, 'Has full system access.', [
			$everything,
		]);

		$this->success("Finished.");
		return ExitCode::OK;
	}

	/**
	 * Assign master role with full system access to a user.
	 *
	 * @param integer $userId
	 *
	 * @return int
	 */
	public function actionAssignMaster($userId)
	{
		if (!is_numeric($userId) || 0 >= $userId) {
			$this->error("User ID should be a positive integer");
			return ExitCode::DATAERR;
		}

		$am = Yii::$app->authManager;
		if (!$master = $am->getRole(Item::ROLE_MASTER)) {
			$this->error("Master role not found. Please run 'init' command first.");
			return ExitCode::UNAVAILABLE;
		}

		$am->assign($master, $userId);
		$this->success("Role ".Item::ROLE_MASTER." added to user {$userId}.");

		return ExitCode::OK;
	}

	/**
	 * Assign permissions or roles to users
	 * You can pass several permissions or users using comma.
	 *
	 * @param array $items
	 * @param array $userIds
	 *
	 * @return int
	 */
	public function actionAssign(array $items, array $userIds)
	{
		$this->line('Assigning items...');
		$am = Yii::$app->authManager;

		foreach ($items as $item) {
			$this->warning('Trying to assign ' . $item);
			// check role exists
			if (! $authItem = $am->getRole($item)) {
				if (! $authItem = $am->getPermission($item)) {
					$this->error("\trole or permission not found");
					continue;
				}
			}

			// assign
			foreach ($userIds as $userId) {
				$am->assign($authItem, $userId);
				$this->line("\tassigned to user " . $userId);
			}
		}

		$this->success("Finished.");
		return ExitCode::OK;
	}

	/**
	 * Scans controller/module directories to get route permissions info
	 *
	 *      runs recursive search for *Controller.php, ignores files, which specified in ignorePath
	 *      register auth Permissions based on Controller public actions (methods in format "action{something}")
	 *
	 * @param string $directory
	 * @param array  $ignorePath
	 *
	 * @return int
	 */
	public function actionScan($directory = '@app', $prefix = null, array $ignorePath = ['app/console', 'app/extensions'])
	{
		$path = Yii::getAlias($directory);
		$controllers = $this->scanDirectory($path, $ignorePath);
		$this->warning('Found ' . count($controllers) . ' controllers');

		$actionRoutes = $this->scanControllerRoutes($controllers);
		$this->line("\tfound " . count($actionRoutes) . ' routes');

		// TODO: support /* permission

		$auth = Yii::$app->authManager;
		$parents = [];

		$inserted = 0;
		foreach ($actionRoutes as $route) {
			if (! $auth->getPermission($route)) {
				$wildcard = $this->getRouteWildcardPermission($prefix . $route, 'Route ');
				$this->addPermission($prefix . $route, 'Route ' . $prefix . $route, null, [$wildcard]);
				$inserted++;
			}
		}

		$this->success("Inserted {$inserted} new permissions.");
		return ExitCode::OK;
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
	 * Find route wildcard permission (controller/*).
	 * Creates if not exists
	 *
	 * @param string $routeName
	 * @param string $descrPrefix
	 *
	 * @return Permission
	 */
	protected function getRouteWildcardPermission($routeName, $descrPrefix = 'Route ')
	{
		$wildcardName = dirname($routeName) . '/*';

		if (! isset($this->itemsCache[$wildcardName])) {
			$this->itemsCache[$wildcardName] = Yii::$app->authManager->getPermission($wildcardName);
			if (! $this->itemsCache[$wildcardName]) {
				$this->itemsCache[$wildcardName] = $this->addPermission($wildcardName, $descrPrefix . $wildcardName);
			}
		}
		return $this->itemsCache[$wildcardName];
	}

	/**
	 * @param string    $name
	 * @param string    $descr
	 * @param Rule|null $rule
	 * @param Permission[]|Role[]     $parents  assign permission to some parent
	 *
	 * @return Permission
	 */
	protected function addPermission($name, $descr, $rule = null, $parents = [])
	{
		$auth = Yii::$app->authManager;

		$p = $auth->createPermission($name);
		$p->description = $descr;
		if ($rule) {
			$p->ruleName = $rule->name;
		}
		$auth->add($p);
		$this->line("\tadded permission " . $p->name);

		foreach ($parents as $parent) {
			$auth->addChild($parent, $p);
			$this->line("\t\tadded as child of " . $parent->name);
		}

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

	/**
	 * Prints a line to STDOUT.
	 *
	 * You may optionally format the string with ANSI codes by
	 * passing additional parameters using the constants defined in [[\yii\helpers\Console]].
	 *
	 * Example:
	 *
	 * ```
	 * $this->stdout('This will be red and underlined.', Console::FG_RED, Console::UNDERLINE);
	 * ```
	 *
	 * @param string $string the string to print
	 * @return int|bool Number of bytes printed or false on error
	 */
	protected function line($string = '')
	{
		$args = func_get_args();
		$args[0] .= "\n";
		return call_user_func_array([$this, 'stdout'], $args);
	}

	/**
	 * Prints a yellow line to STDOUT.
	 *
	 * @param string $string the string to print
	 * @return int|bool Number of bytes printed or false on error
	 */
	protected function warning($string)
	{
		return $this->line($string, Console::FG_YELLOW);
	}

	/**
	 * Prints a yellow line to STDOUT.
	 *
	 * @param string $string the string to print
	 * @return int|bool Number of bytes printed or false on error
	 */
	protected function success($string)
	{
		return $this->line($string, Console::FG_GREEN);
	}

	/**
	 * Prints a red line to STDOUT.
	 *
	 * @param string $string the string to print
	 * @return int|bool Number of bytes printed or false on error
	 */
	protected function error($string)
	{
		return $this->line($string, Console::FG_RED);
	}

}

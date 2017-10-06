<?php

namespace justcoded\yii2\rbac\commands;

use justcoded\yii2\rbac\forms\ScanForm;
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
	/**
	 * @var string Path to scan.
	 */
	public $path = '@app';

	/**
	 * @var string Paths to ignore.
	 * Use comma to specify several paths.
	 */
	public $ignorePath = [];

	/**
	 * @var string Routes base prefix to be added to all found routes
	 */
	public $routesBase = '';

	/**
	 * @inheritdoc
	 *
	 * @return array|string[]
	 */
	public function options($actionID)
	{
		if ('scan' === $actionID) {
			return ['path', 'ignorePath', 'routesBase'];
		} else {
			return parent::options($actionID);
		}
	}

	/**
	 * @inheritdoc
	 *
	 * @return array
	 */
	public function optionAliases()
	{
		return [
			'p' => 'path',
			'i' => 'ignorePath',
			'b' => 'routesBase',
		];
	}

	/**
	 * Init default roles, master and administer permissions.
	 *
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
	 * @return int
	 */
	public function actionScan()
	{
		$scanner = new ScanForm();
		$scanner->path = $this->path;
		$scanner->ignorePath = $this->ignorePath;
		$scanner->routesBase = $this->routesBase;

		$this->warning('Scanning directory for controllers and routes...');

		if ($actionRoutes = $scanner->scan()) {
			$this->line("\tfound " . count($actionRoutes) . ' routes');
			$inserted = $scanner->importPermissions($actionRoutes);
			foreach ($inserted as $route => $status) {
				$this->line("\t\tadded " . $route);
			}

			$inserted = count($inserted);
			$this->success("Inserted {$inserted} new permissions.");
		} else {
			$this->line("No routes to import");
		}

		return ExitCode::OK;
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

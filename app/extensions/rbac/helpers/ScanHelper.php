<?php

namespace justcoded\yii2\rbac\helpers;

use Yii;
use yii\helpers\Inflector;

class ScanHelper
{
	/**
	 * Recursive scan of directory searching the controllers.
	 *
	 * @param string $directory
	 * @param array  $ignorePath
	 *
	 * @return array
	 */
	public static function scanControllers(string $directory, array $ignorePath)
	{
		// register iterator to find files
		$dirIt = new \RecursiveDirectoryIterator($directory);
		$it = new \RecursiveIteratorIterator($dirIt);

		// get all controllers
		$allControllers = new \RegexIterator(
			$it,
			'/^.+Controller\.php$/',
			\RecursiveRegexIterator::GET_MATCH
		);
		$controllers = array_keys(iterator_to_array($allControllers));

		// apply ignore path
		if (! empty($ignorePath)) {
			if (is_array($ignorePath)) {
				$ignoreRegexp = '('.implode('|', $ignorePath).')';
			} else {
				$ignoreRegexp = "({$ignorePath})";
			}

			// find controllers, which should be ignored
			$ignoredControllers = new \RegexIterator(
				$allControllers,
				"#^.+{$ignoreRegexp}.+Controller\.php$#",
				\RecursiveRegexIterator::GET_MATCH,
				\RecursiveRegexIterator::USE_KEY
			);

			$ignoredControllers = array_keys(iterator_to_array($ignoredControllers));

			$controllers = array_diff($allControllers, $ignoredControllers);
		}

		return $controllers;
	}

	/**
	 * Scan controllers by filenames to find their public action routes.
	 *
	 * @param array $controllers
	 *
	 * @return array
	 */
	public static function scanControllerActionIds(array $controllers)
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

			// ignore console commands
			if (! $reflection->isSubclassOf(\yii\web\Controller::className())) {
				continue;
			}

			// find public methods
			$methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);

			$moduleId = '';
			if (preg_match('/modules\\\\([a-z0-9_-]+)\\\\/i', $reflection->getNamespaceName(), $moduleMatch)) {
				$moduleId = Inflector::slug(Inflector::camel2words($moduleMatch[1])) . '/';
			}

			foreach ($methods as $method) {
				if (! preg_match('/^action([A-Z]([a-zA-Z0-9]+))$/', $method->getName(), $actionMatch)
				    && !('actions' === $method->getName() && $reflection->getName() === $method->class)
				) {
					continue;
				}
				$controllerId = Inflector::slug(Inflector::camel2words($classMatch[2]));

				if ('actions' === $method->getName()) {
					try {
						$controllerObj = Yii::createObject($method->class, [$controllerId, Yii::$app]);
						$customActions = $controllerObj->actions();
						foreach ($customActions as $actionId => $params) {
							$actions[] = $moduleId . $controllerId . '/' . $actionId;
						}
					} catch (\Exception $e) {
						Yii::warning("RBAC Scanner: can't scan custom actions from {$method->class}::actions(). You will need to add them manually.");
					}

				} else {
					$actionId = Inflector::slug(Inflector::camel2words($actionMatch[1]));
					$actions[] = $moduleId . $controllerId . '/' . $actionId;
				}
			}
		}

		return $actions;
	}
}

<?php
/**
 * IDE autocompletion for custom components!
 * This file doesn't included anywhere inside the code!!!
 */

/**
 * Yii bootstrap file.
 * Used for enhanced IDE code autocompletion.
 */
class Yii extends \yii\BaseYii
{
	/**
	 * @var BaseApplication|WebApplication|ConsoleApplication the application instance
	 */
	public static $app;
}

/**
 * Class BaseApplication
 * Used for properties that are identical for both WebApplication and ConsoleApplication
 *
 * @property \app\components\Settings $settings Configuration params
 */
abstract class BaseApplication extends \yii\base\Application
{
}

/**
 * Class WebApplication
 * Include only Web application related components here
 *
 * @property \app\i18n\Formatter $formatter The main formatter for app
 * @method \app\i18n\Formatter getFormatter The main formatter for app
 */
class WebApplication extends \yii\web\Application
{
}

/**
 * Class ConsoleApplication
 * Include only Console application related components here
 *
 * @property \app\i18n\Formatter $formatter The main formatter for app
 * @method \app\i18n\Formatter getFormatter The main formatter for app
 */
class ConsoleApplication extends \yii\console\Application
{
}
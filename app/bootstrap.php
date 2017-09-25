<?php

if (! function_exists('pa')) {
	/**
	 * Print array or object
	 *
	 * @param mixed $mixed
	 * @param bool $stop
	 */
	function pa($mixed, $stop = false)
	{
		$ar  = debug_backtrace();
		$key = pathinfo($ar[0]['file']);
		$key = $key['basename'] . ':' . $ar[0]['line'];
		\yii\helpers\VarDumper::dump([$key => $mixed], 8, 1);
		if ($stop) {
			exit();
		}
	}
}

if (!function_exists('dotenv')) {
	/**
	 * Wrapper for Dotenv class object to cache it to static variable.
	 *
	 * @param string|null $config
	 *
	 * @return \Dotenv\Dotenv
	 * @throws Exception
	 */
	function dotenv($config = null)
	{
		static $dotenv;

		if (!is_null($config)) {
			$dotenv = new Dotenv\Dotenv($config);
		}

		if (!$dotenv) {
			throw new \Exception("dotenv() helper is not initialized. Call dotenv('path to .env') first.");
		}

		return $dotenv;
	}
}

if (! function_exists('env')) {
	/**
	 * Gets the value of an environment variable. Supports boolean, empty and null.
	 *
	 * @param  string $key
	 * @param  mixed  $default
	 *
	 * @return mixed
	 */
	function env($key, $default = null)
	{
		$value = getenv($key);
		if ($value === false) {
			return $default;
		}
		switch (strtolower($value)) {
			case 'true':
			case '(true)':
				return true;
			case 'false':
			case '(false)':
				return false;
			case 'null':
			case '(null)':
				return null;
			case 'empty':
			case '(empty)':
				return '';
		}
		// remove double quotes from value, if wrapped
		if (0 === strpos($value, '"')
		    && '"' === substr($value, -1)
		) {
			return substr($value, 1, -1);
		}

		return $value;
	}
}

if (! function_exists('user')) {
	/**
	 * Get application user component
	 *
	 * @return \yii\web\User
	 */
	function user()
	{
		return \Yii::$app->getUser();
	}
}


if (! function_exists('settings')) {
	/**
	 * Get application settings object
	 *
	 * @return \app\components\Settings
	 */
	function settings()
	{
		return \Yii::$app->get('settings');
	}
}

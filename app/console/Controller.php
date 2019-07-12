<?php

namespace app\console;

use yii\console\ExitCode;
use yii\helpers\Console;

abstract class Controller extends \yii\console\Controller
{

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
	 * @return int Exit code status
	 */
	protected function warning($string)
	{
		$this->line($string, Console::FG_YELLOW);

		return ExitCode::TEMPFAIL;
	}

	/**
	 * Prints a yellow line to STDOUT.
	 *
	 * @param string $string the string to print
	 * @return int Exit code status
	 */
	protected function success($string)
	{
		$this->line($string, Console::FG_GREEN);

		return ExitCode::OK;
	}
}

<?php

namespace app\console;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 */
class HelloController extends Controller
{
	/**
	 * This command echoes what you have entered as the message.
	 *
	 * @param string $message the message to be echoed.
	 */
	public function actionIndex($message = 'hello world')
	{
		echo $message . "\n";
	}
}

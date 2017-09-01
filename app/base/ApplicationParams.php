<?php

namespace app\base;

use yii\base\BaseObject;

/**
 * Class ApplicationParams
 *
 * Describes the structure of available Application params
 *
 * @package app\base
 */
class ApplicationParams extends BaseObject
{
	/**
	 * Email to send mail from
	 *
	 * @var string|array
	 */
	public $systemEmail;

	/**
	 * Administrator email to send mails to (in terms of emergency)
	 *
	 * @var string|array
	 */
	public $adminEmail;
}

<?php

namespace app\i18n;

use DateInterval;
use DateTime;
use DateTimeInterface;
use DateTimeZone;
use IntlDateFormatter;
use NumberFormatter;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\helpers\VarDumper;
use app\helpers\Html;

class Formatter extends \yii\i18n\Formatter
{
	public $dateFormat = 'php:d M, Y';
	public $timeFormat = 'php:H:i';
	public $datetimeFormat = 'php:d M, Y, H:i';
	public $nullDisplay = '';

	public function asDateWithDay($value)
	{
		return $this->asDate($value, 'php:D, d M`y');
	}

	public function normalizeDatetimeValue($value, $checkTimeInfo = false)
	{
		// convert dd/mm/yyyy to yyyy-mm-dd, because it's not converted correcly.
		if (preg_match('/(\d{2})\/(\d{2})\/(\d{4})/', $value, $match)) {
			$value = "{$match[3]}-{$match[2]}-{$match[1]}";
		}

		// if value has format yyyymmdd.
		if (strlen($value) == 8 && strpos($value, '201') === 0) {
			$value = preg_replace('/(\d{4})(\d{2})(\d{2})/', '$1-$2-$3', $value);
		}

		return parent::normalizeDatetimeValue($value, $checkTimeInfo);
	}

}

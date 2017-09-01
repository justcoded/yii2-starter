<?php

namespace app\components\i18n;

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
	const FORMAT_JS = 'js';
	const FORMAT_ICU = 'icu';

	public $dateFormat = 'php:d M, Y';
	public $timeFormat = 'php:H:i';
	public $datetimeFormat = 'php:d M, Y, H:i';
	public $nullDisplay = '';

	public function asDateWithDay($value)
	{
		return $this->asDate($value, 'php:D, d M`y');
	}

	/**
	 * convert number of seconds to number of hours/minutes passed
	 *
	 * @param int $number_seconds
	 *
	 * @return string
	 */
	public function asPassedTime($number_seconds)
	{
		if ($number_seconds <= 0) {
			return 'n/a';
		}

		$hours   = floor($number_seconds / 3600);
		$minutes = floor(($number_seconds - $hours * 3600) / 60);

		return "{$hours}h {$minutes}m";
	}

	public function asTimeLeft($to, $from = null, $template = '{days} {hours}')
	{
		if (is_null($from))
			$from = time();
		$to   = $this->asTimestamp($to);
		$from = $this->asTimestamp($from);

		if ($from >= $to)
			return 'Passed';

		$seconds = $to - $from;

		$days    = floor($seconds / 86400);
		$seconds %= 86400;

		$hours   = floor($seconds / 3600);
		$seconds %= 3600;

		$minutes = floor($seconds / 60);
		$seconds %= 60;

		return strtr($template, [
			'{days}'    => "$days days",
			'{hours}'   => "$hours hours",
			'{minutes}' => "$minutes mins",
			'{seconds}' => "$seconds secs",
		]);
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

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

	public static function getDateFormat($language = self::FORMAT_ICU)
	{
		switch ($language) {
			case self::FORMAT_JS:
				$dateFormat = 'Y-m-d';
				break;

			default:
				$dateFormat = 'yyyy-MM-dd';
				break;
		}

		return $dateFormat;
	}

	public static function getWeekStart($language = self::FORMAT_ICU)
	{
		switch ($language) {
			default:
				$weekStart = 1;
				break;
		}

		return $weekStart;
	}

	public function validateDatetimeValue($value, $checkTimeInfo = false)
	{
		// checking for DateTime and DateTimeInterface is not redundant, DateTimeInterface is only in PHP>5.5
		if ($value == null || $value instanceof DateTime || $value instanceof DateTimeInterface) {
			// skip any processing
			return $checkTimeInfo ? [$value, true] : $value;
		}
		if (empty($value)) {
			$value = 0;
		}
		try {
			if (is_numeric($value)) { // process as unix timestamp, which is always in UTC
				$timestamp = new DateTime();
				$timestamp->setTimezone(new DateTimeZone('UTC'));
				$timestamp->setTimestamp($value);
				return $checkTimeInfo ? [$timestamp, true] : $timestamp;
			} elseif (($timestamp = DateTime::createFromFormat('Y-m-d', $value, new DateTimeZone($this->defaultTimeZone))) !== false) { // try Y-m-d format (support invalid dates like 2012-13-01)
				return $checkTimeInfo ? [$timestamp, false] : $timestamp;
			} elseif (($timestamp = DateTime::createFromFormat('Y-m-d H:i:s', $value, new DateTimeZone($this->defaultTimeZone))) !== false) { // try Y-m-d H:i:s format (support invalid dates like 2012-13-01 12:63:12)
				return $checkTimeInfo ? [$timestamp, true] : $timestamp;
			}
			// finally try to create a DateTime object with the value
			if ($checkTimeInfo) {
				$timestamp = new DateTime($value, new DateTimeZone($this->defaultTimeZone));
				$info = date_parse($value);
				return [$timestamp, !($info['hour'] === false && $info['minute'] === false && $info['second'] === false)];
			} else {
				return new DateTime($value, new DateTimeZone($this->defaultTimeZone));
			}
		} catch (\Exception $e) {
			return false;
		}
	}

	/**
	 * Convert all capitals to lowercase with hyphen
	 * @param string $string
	 * @return string
	 */
	public function asPathify( $string )
	{
		return trim(strtolower(preg_replace('/([A-Z]+)/', "-$1", $string)), '-');
	}

	/**
	 * convert to GMT/UTC date format with T/Z
	 * @param integer $timestamp
	 */
	public function asGmtDate( $timestamp )
	{
		if( empty($timestamp) )
			return $timestamp;

		$timestamp = $this->normalizeDatetimeValue($timestamp);
		return str_replace('+00:00', 'Z', gmdate('c', $timestamp->getTimestamp()));
	}

	public function asDateWithDay( $value )
	{
		return $this->asDate($value, 'php:D, d M`y');
	}

	public function asTimestamp( $value )
	{
		if( $value === null )
		{
			return $this->nullDisplay;
		}
		
		// if value has format yyyymmdd
		if( strlen($value) == 8 && strpos($value, '201') === 0 )
		{
			$value = preg_replace('/(\d{4})(\d{2})(\d{2})/', '$1-$2-$3', $value);
		}

		$timestamp = $this->normalizeDatetimeValue($value);
		if( $timestamp->format('U') < 100000 )
			return $this->nullDisplay;

		return number_format($timestamp->format('U'), 0, '.', '');
	}

	public function asTimestampAfternoon( $value )
	{
		$timestamp = $this->asTimestamp($value);
		if( $value === $this->nullDisplay )
		{
			return $this->nullDisplay;
		}
		
		return strtotime('today 12:00', $timestamp);
	}
	
	/**
	 * make short name as "{First name} {one letter last name}."
	 * @param string $string
	 */
	public function asShortName( $string )
	{
		$parts = explode(' ', trim($string), 2);
		if( count($parts) > 1 )
		{
			$parts[1] = $parts[1]{0} . '.';
		}

		$short_name = implode(' ', $parts);
		return $short_name;
	}

	/**
	 * convert to date format with YYYYMMDD
	 * @param integer $timestamp
	 */
	public function asDateYmd( $timestamp )
	{
		if( empty($timestamp) )
			return $timestamp;
		
		$timestamp = $this->normalizeDatetimeValue($timestamp);
		return $timestamp->format('Ymd');
		//return date('Ymd', $timestamp);
	}
		
	/**
	 * convert number of seconds to number of hours/minutes passed
	 * @param int $number_seconds
	 */
	public function asPassedTime( $number_seconds )
	{
		if( $number_seconds <= 0 ) return 'n/a';
		
		$hours = floor($number_seconds / 3600);
		$minutes = floor( ($number_seconds - $hours*3600) / 60 );
		return "{$hours}h {$minutes}m";
	}

	public function asTimeLeft($to, $from = null, $template = '{days} {hours}')
	{
		if ( is_null($from) ) $from = time();
		$to = $this->asTimestamp($to);
		$from = $this->asTimestamp($from);

		if ( $from >= $to ) return 'Passed';

		$seconds = $to - $from;

		$days = floor($seconds / 86400);
		$seconds %= 86400;

		$hours = floor($seconds / 3600);
		$seconds %= 3600;

		$minutes = floor($seconds / 60);
		$seconds %= 60;

		return strtr($template, [
			'{days}' => "$days days",
			'{hours}' => "$hours hours",
			'{minutes}' => "$minutes mins",
			'{seconds}' => "$seconds secs",
		]);
	}
	
	/**
	 * check that string is serialized and print var dump for this object
	 * @param text $string
	 */
	public function asSerializedObject( $string )
	{
		if( empty($string) || ! $unserialized = unserialize($string) ) return $this->nullDisplay;
		
		return VarDumper::dumpAsString($unserialized, 3, true);
	}

	/**
	 * convert number of minutes to hours + float
	 * @param integer $minutes
	 * @param integer $digits   how many digits after the .
	 * @return float
	 */
	public function asHours($minutes, $digits = 1)
	{
		$hours = round($minutes / 60, $digits);
		return $hours;
	}

	/**
	 * convert number of minutes to hours + float
	 * @param integer $minutes
	 * @param integer $days   days interval to calculate developer resources
	 * @param integer $digits   how many digits after the .
	 * @return float
	 */
	public function asDevelopers($minutes, $days = 5, $digits = 2)
	{
		$devs = round($minutes / 60 / (8 * $days), $digits);
		return $devs;
	}

	/**
	 * convert number of minutes to hours and plus sign if hours num is not integer
	 * @param integer $minutes
	 * @return float
	 */
	public function asHoursAv($minutes)
	{
		$hours = round($minutes / 60, 2);
		$full_hours = floor($hours);
		
		if ( $hours - $full_hours == 0.5 ) {
			return $hours;
		}
		
		if ( $hours > ($full_hours + 0.5) ) {
			$full_hours .= '.5+ ';
		}
		elseif ( $hours > $full_hours ) {
			$full_hours .= '+ ';
		}
		
		return $full_hours;
	}
	
	/**
	 * convert number of minutes to hours:minutes
	 * @param integer $total_minutes
	 * @return float
	 */
	public function asHoursMinutes($total_minutes)
	{
		$hours = floor($total_minutes / 60);
		$minutes = sprintf("%02d", $total_minutes - ($hours* 60));
		return "{$hours}:{$minutes}";
	}

	public function normalizeDatetimeValue($value, $checkTimeInfo = false)
	{
		if( $this->isDateIso8601($value) )
		{
			return new \DateTime($value);
		}

		if ( preg_match('/(\d{2})\/(\d{2})\/(\d{4})/', $value, $match) ) {
			$value = "{$match[3]}-{$match[2]}-{$match[1]}";
		}

		return parent::normalizeDatetimeValue($value, $checkTimeInfo);
	}

	public function asTime($value, $format = null)
	{
		if( $this->isDateIso8601($value) )
		{
			if( is_null($format) )
			{
				$format = "H:i:s";
			}

			return (new \DateTime($value))->format($format);
		}

		return parent::asTime($value, $format);
	}

	public function isDateIso8601($value)
	{
		if( is_string($value) && preg_match('/^(?:[1-9]\d{3}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1\d|2[0-8])|(?:0[13-9]|1[0-2])-(?:29|30)|(?:0[13578]|1[02])-31)|(?:[1-9]\d(?:0[48]|[2468][048]|[13579][26])|(?:[2468][048]|[13579][26])00)-02-29)T(?:[01]\d|2[0-3]):[0-5]\d:[0-5]\d(?:[+-][01]\d:[0-5]\d)$/', $value) > 0 )
		{
			return true;
		}

		return false;
	}
	
	public function asSingleArray($array)
	{
		if( !is_array($array) ) return null;
		
		$html = Html::beginTag('dl', ['class' => 'container-fluid']);
		foreach ($array as $key => $value) {
			$html .= Html::tag('dd', $key, ['class' => 'col-md-4']);
			$html .= Html::tag('dt', $value, ['class' => 'col-md-8']);
		}
		$html .= Html::endTag('dl');
		
		return $html;
	}
	
	/**
	 * print comma separated User names from user objects
	 * 
	 * @param \app\models\User[] $users
	 */
	public function asMembersList($users)
	{
		if ( empty($users) )
			return $this->nullDisplay;
		
		$names = [];
		foreach ($users as $user) {
			$names[] = $user->fullname;
		}
		
		return implode(', ', $names);
	}
}

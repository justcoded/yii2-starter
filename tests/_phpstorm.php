#!/usr/bin/env php
<?php
/**
 * Function to fix execution of codeception tests in PHPStorm, which using session.
 *
 * @see https://github.com/justcoded/yii2-starter/issues/25
 */

$file = './vendor/phpunit/phpunit/src/Util/Printer.php';
$content = file_get_contents($file);
$line_fix = 'if (!$out) $out = \'php://stderr\'; ';

if (!strpos($content, $line_fix)) {
	file_put_contents(
		$file,
		str_replace('if ($out !== null)', $line_fix . "\n        " . 'if ($out !== null)', $content)
	);
}

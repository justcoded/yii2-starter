<?php

namespace app\console;

use Yii;
use yii\base\Exception;

/**
 * Class GenerateController
 *
 * @package app\console\controllers
 */
class SecurityController extends Controller
{
	/**
	 * Action Generate
	 *
	 * @return bool|int
	 * @throws Exception
	 */
	public function actionAppKey()
	{
		$key = Yii::$app->security->generateRandomString();
		$envPath = Yii::getAlias('@srcPath/.env');

		if (file_exists($envPath)) {
			$envData = file_get_contents($envPath);

			if (! preg_match('/APP_KEY=(\w+)?/', $envData)) {
				return $this->warning('No APP_KEY has been found in your .env file');
			}

			$updatedEnvData = preg_replace('/APP_KEY=(\w+)?/', "APP_KEY={$key}", $envData);
			if (! $updatedEnvData) {
				return $this->stderr('Error during the app key setup');
			}

			file_put_contents($envPath, $updatedEnvData);

			return $this->success('The application key has been successfully set');
		}

		return $this->warning('The .env file not found. Do you need to create one?');
	}
}

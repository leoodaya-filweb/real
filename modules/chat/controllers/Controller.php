<?php

namespace app\modules\chat\controllers;

use app\modules\chat\helpers\App;


abstract class Controller extends \app\controllers\Controller
{
	// public $enableCsrfValidation = false;
	public $layout = 'main';

	public function beforeAction($action)
	{
		if (! parent::beforeAction($action)) {
			return false;
		}

		if (App::isGuest()) {
			$this->redirect(['/site/index']);

			return false;
		}

		return true;
	}
}
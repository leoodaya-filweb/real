<?php

namespace app\widgets;

use app\helpers\App;
use app\models\Theme;
use app\widgets\AnchorForm;
use app\widgets\RecordStatusInput;

class ActiveForm extends \yii\widgets\ActiveForm
{
	public $forceClass;

	public function init()
	{
		parent::init();

		$currentTheme = App::identity('currentTheme');

		if ($currentTheme) {
			if (in_array($currentTheme->slug, Theme::KEEN)) {
				$this->errorCssClass = 'is-invalid';
				$this->successCssClass = 'is-valid';
				$this->validationStateOn = 'input';

				$this->options['class'] = 'form' . ' ' . $this->forceClass;
				$this->options['novalidate'] = 'novalidate';
			}
		}
	}

	public static function buttons($size='md')
	{
		return AnchorForm::widget([
			'size' => $size
		]);
	}

	public static function recordStatus($params)
	{
		return RecordStatusInput::widget($params);
	}
}

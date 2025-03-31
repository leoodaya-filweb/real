<?php

use app\helpers\Html;
use app\models\Specialsurvey;
use app\models\search\SpecialsurveySearch;
use app\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Specialsurvey */

$this->title = 'Survey Settings';
$this->params['breadcrumbs'][] = ['label' => 'Surveys', 'url' => (new Specialsurvey())->indexUrl];
$this->params['breadcrumbs'][] = 'Settings';
$this->params['searchModel'] = new SpecialsurveySearch();
$this->params['activeMenuLink'] = '/specialsurvey/settings';
?>
<div class="specialsurvey-setting-page">
	<?php $form = ActiveForm::begin(['id' => 'specialsurvey-form']); ?>
		<div class="row">
			<div class="col-md-4">
				<?= $form->field($model, 'dominance_percentage')
					->textInput(['type' => 'number'])
					->label('Dominance Percentage (greater than or equal to)') ?>
			</div>
		</div>

		<div class="mt-10"></div>
		<p class="lead font-weight-bold">
			SURVEY COLOR
		</p>
		<table class="table">
			<thead>
				<tr>
					<th>ID</th>
					<th>COLOR</th>
					<th>LABEL</th>
					<!--<th>PRIORITY</th> -->
				</tr>
			</thead>
			<tbody>
				<?= Html::foreach($model->survey_color, function($survey) {
					$key = $survey['id'] - 1;
					return <<< HTML
						<tr>
							<td>
								{$survey['id']}
								<input name="SurveySettingForm[survey_color][{$key}][id]" type="hidden" value="{$survey['id']}">
								<input type="hidden" value="{$survey['id']}">
							</td>
							<td>
								<input name="SurveySettingForm[survey_color][{$key}][color]" type="color" value="{$survey['color']}" placeholder="hex" class="form-control" list>
							</td>
							<td>
								<input name="SurveySettingForm[survey_color][{$key}][label]" type="text" value="{$survey['label']}" class="form-control">
							</td>
							
							<!--<td><input  name="SurveySettingForm[survey_color][{$key}][priority]" type="number" value="{$survey['priority']}" class="form-control"></td>-->
							
						</tr>
					HTML;
				}) ?>
			</tbody>
		</table>
	    <div class="form-group">
			<?= ActiveForm::buttons() ?>
	    </div>
	<?php ActiveForm::end(); ?>
</div>
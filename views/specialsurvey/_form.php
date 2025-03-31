<?php

use app\helpers\App;
use app\models\Barangay;
use app\models\CivilStatus;
use app\models\Sex;
use app\widgets\ActiveForm;
use app\widgets\BootstrapSelect;
use app\widgets\DataList;
use app\widgets\DatePicker;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Specialsurvey */
/* @var $form app\widgets\ActiveForm */

$survey_colors = App::mapParams(App::setting('surveyColor')->survey_color);

$this->registerJs(<<< JS
	function getAge(dateString) {
		var ageInMilliseconds = new Date() - new Date(dateString);
		return Math.floor(ageInMilliseconds/1000/60/60/24/365); // convert to years
	}

	$(document).on('change', '#specialsurvey-date_of_birth', function() {
		let birthDate = $(this).val();

		$('#specialsurvey-age').val(getAge(birthDate));
	});
JS);

?>
<?php $form = ActiveForm::begin(['id' => 'specialsurvey-form']); ?>
	<section>
		<p class="lead font-weight-bold">
			MIAN INFORMATION
		</p>
		<div class="row">
			<div class="col-md-4">
				<?= $form->field($model, 'survey_name')->textInput(['maxlength' => true]) ?>
			</div>
			<div class="col-md-4">
				<?php 
				/*echo  DatePicker::widget([
					'form' => $form,
					'model' => $model,
					'attribute' => 'date_survey',
				])*/
				
					(int)$model->date_survey?$model->date_survey=date('Y-m-d',strtotime($model->date_survey)):null;
					
					echo $form->field($model, 'date_survey')->textInput([
							'type' => 'date'
						]);
				?>
			</div>
		</div>
	</section>
	<div class="my-5"></div>

	<section>
		<p class="lead font-weight-bold">
			PRIMARY INFORMATION
		</p>
		<div class="row">
			<div class="col-md-4">
				<?= $form->field($model, 'household_no')->textInput(['maxlength' => true]) ?>
			</div>
			<div class="col-md-4">
				<?= $form->field($model, 'precinct_no')->textInput(['maxlength' => true]) ?>
			</div>
			
		</div>

		<div class="row">
			<div class="col-md-4">
				<?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
			</div>
			<div class="col-md-4">
				<?= $form->field($model, 'middle_name')->textInput(['maxlength' => true]) ?>
			</div>
			<div class="col-md-4">
				<?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
			</div>
		</div>

		<div class="row">
			<div class="col-md-4">
				<?= BootstrapSelect::widget([
	                'form' => $form,
	                'model' => $model,
	                'attribute' => 'gender',
	                'data' => [
	                	'Male' => 'Male',
	                	'Female' => 'Female',
	                ],
	            ]) ?>
			</div>
			<div class="col-md-4">
				<div class="row">
					<div class="col-md-9">
						<?php
						/*
						echo DatePicker::widget([
							'form' => $form,
							'model' => $model,
							'attribute' => 'date_of_birth',
						]);
						*/
					(int)$model->date_of_birth?$model->date_of_birth=date('Y-m-d',strtotime($model->date_of_birth)):null;
					
					echo 	$form->field($model, 'date_of_birth')->textInput([
							'type' => 'date'
						]);
						?>
					</div>
					<div class="col-md-3">
						<?= $form->field($model, 'age')->textInput([
							'readonly' => true
						]) ?>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<?= DataList::widget([
					'form' => $form,
					'model' => $model,
					'attribute' => 'civil_status',
					'data' => CivilStatus::filter('label')
				]) ?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<?= DataList::widget([
					'form' => $form,
					'model' => $model,
					'attribute' => 'religion',
					'data' => App::params('religions')
				]) ?>
			</div>
		</div>
	</section>

	<div class="my-5"></div>

	<section>
		<p class="lead font-weight-bold">
			ADDRESS INFORMATION
		</p>

		<div class="row">
			<div class="col-md-4">
				<?= $form->field($model, 'house_no')->textInput(['maxlength' => true]) ?>
			</div>
			<div class="col-md-4">
				<?= $form->field($model, 'purok')->textInput(['maxlength' => true]) ?>
			</div>
			<div class="col-md-4">
				<?= BootstrapSelect::widget([
	                'form' => $form,
	                'model' => $model,
	                'attribute' => 'barangay',
	                'data' => Barangay::dropdown('name', 'name'),
	            ]) ?>
			</div>
		</div>

		<div class="row">
			<div class="col-md-4">
				<?= $form->field($model, 'municipality')->textInput(['maxlength' => true]) ?>
			</div>
			<div class="col-md-4">
				<?= $form->field($model, 'province')->textInput(['maxlength' => true]) ?>
			</div>
		</div>
	</section>

	<div class="my-5"></div>

	<section>
		<p class="lead font-weight-bold">
			CRITERIA INFORMATION
		</p>
    	<div class="row">
        	<div class="col-md-4">
        		<?= BootstrapSelect::widget([
					'form' => $form,
					'model' => $model,
					'attribute' => 'criteria1_color_id',
					'data' => $survey_colors,
				]) ?>
			</div>
        	<div class="col-md-4">
        		<?= BootstrapSelect::widget([
					'form' => $form,
					'model' => $model,
					'attribute' => 'criteria2_color_id',
					'data' => $survey_colors,
				]) ?>
			</div>
        	<div class="col-md-4">
        		<?= BootstrapSelect::widget([
					'form' => $form,
					'model' => $model,
					'attribute' => 'criteria3_color_id',
					'data' => $survey_colors,
				]) ?>
			</div>
		</div>
    	<div class="row">
        	<div class="col-md-4">
        		<?= BootstrapSelect::widget([
					'form' => $form,
					'model' => $model,
					'attribute' => 'criteria4_color_id',
					'data' => $survey_colors,
				]) ?>
			</div>
        	<div class="col-md-4">
        		<?= BootstrapSelect::widget([
					'form' => $form,
					'model' => $model,
					'attribute' => 'criteria5_color_id',
					'data' => $survey_colors,
				]) ?>
			</div>
		</div>
	</section>

	<div class="my-5"></div>

	<section>
		<p class="lead font-weight-bold">
			REMARKS
		</p>
	    <div class="row">
	        <div class="col-md-4">
				 <?php // $form->field($model, 'remarks')->textInput(['maxlength' => true]) ?>
				 <?= BootstrapSelect::widget([
					'form' => $form,
					'model' => $model,
					'attribute' => 'remarks',
					'data' => ['medical'=>'Medical', 'employment'=>'Employment', 'agriculture'=>'Agriculture', 'other'=>'Other'],
				]) ?>
			
				
	        </div>
	        <div class="col-md-4">
				 <?= $form->field($model, 'encoder')->textInput(['maxlength' => true]) ?>

	        </div>
	        <div class="col-md-4">
				 <?= $form->field($model, 'leader')->textInput(['maxlength' => true])->label('Leader in Charge') ?>

	        </div>
	    </div>
	</section>
    <div class="form-group">
		<?= ActiveForm::buttons() ?>
    </div>
<?php ActiveForm::end(); ?>
<?php

use app\models\File;
use app\widgets\ActiveForm;
use app\widgets\DatePicker;
use app\widgets\Dropzone;
use app\widgets\InputList;

/* @var $this yii\web\View */
/* @var $model app\models\PostActivityReport */
/* @var $form app\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(['id' => 'post-activity-report-form']); ?>

	<div class="row">
		<div class="col">
			<?= DatePicker::widget([
				'form' => $form,
				'model' => $model,
				'attribute' => 'date'
			]) ?>
		</div>
		<div class="col">
			<?= $form->field($model, 'subject')->textInput(['maxlength' => true]) ?>
		</div>
	</div>

	<div class="row">
		<div class="col">
			<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
		</div>
		<div class="col">
			<?= $form->field($model, 'location')->textInput(['maxlength' => true]) ?>
		</div>
	</div>

	<div class="row">
		<div class="col">
			<?= $form->field($model, 'concerned_office')->textInput(['maxlength' => true]) ?>
		</div>
		<div class="col">
			<?= DatePicker::widget([
				'form' => $form,
				'model' => $model,
				'attribute' => 'date_of_activity'
			]) ?>
		</div>
	</div>

	<div class="row">
		<div class="col">
			<?= $form->field($model, 'for')->textarea(['rows' => 6]) ?>
		</div>
		<div class="col">
			<?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
		</div>
	</div>

	<div class="row">
		<div class="col">
			<?= $form->field($model, 'prepared_by')->textInput(['maxlength' => true]) ?>
		</div>
		<div class="col">
			<?= $form->field($model, 'prepared_by_position')->textInput(['maxlength' => true]) ?>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<?= $form->field($model, 'noted_by')->textInput(['maxlength' => true]) ?>
		</div>
		<div class="col">
			<?= $form->field($model, 'noted_by_position')->textInput(['maxlength' => true]) ?>
		</div>
	</div>

    <div class="row">
		<div class="col">
			<label><?= $model->getAttributeLabel('highlights_of_activity') ?></label>
        	<?= InputList::widget([
        		'label' => 'Highlights of Activity',
        		'name' => 'PostActivityReport[highlights_of_activity][]',
        		'data' => $model->highlights_of_activity,
        	]) ?>
        </div>
		<div class="col">
        	<label>Upload Photos</label>
        	<?= Dropzone::widget([
		        'tag' => 'Post Activity Report',
		        'files' => $model->imageFiles,
		        'model' => $model,
		        'attribute' => 'photos',
		        'acceptedFiles' => array_map(fn($val) => ".{$val}", File::EXTENSIONS['image'] )
		    ]) ?>
        </div>
    </div>
    <div class="form-group mt-10">
		<?= ActiveForm::buttons() ?>
    </div>
<?php ActiveForm::end(); ?>
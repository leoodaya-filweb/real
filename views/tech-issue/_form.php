<?php

use app\helpers\App;
use app\models\File;
use app\widgets\ActiveForm;
use app\widgets\BootstrapSelect;
use app\widgets\Dropzone;
use app\widgets\InputList;

/* @var $this yii\web\View */
/* @var $model app\models\TechIssue */
/* @var $form app\widgets\ActiveForm */
$this->params['wrapCard'] = false;
?>
<?php $form = ActiveForm::begin(['id' => 'tech-issue-form']); ?>
    <div class="row">
        <div class="col-md-6">
        	<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
        		'title' => 'General Information'
        	]) ?>
        		<?= BootstrapSelect::widget([
        			'form' => $form,
        			'model' => $model,
        			'attribute' => 'type',
        			'data' => App::keyMapParams('tech_issue_types')
        		]) ?>
				<?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
				<div class="form-group required">
					<label class="control-label">How to Reproduce</label>
		        	<?= InputList::widget([
		        		'label' => 'Step',
						'name' => 'TechIssue[steps][]',
						'data' => $model->steps
		        	]) ?>
				</div> 
        	<?php $this->endContent() ?>
        </div>
        <div class="col-md-6">
        	<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
        		'title' => 'Device Information',
        		'stretch' => true
        	]) ?>
	        	<?= $form->field($model, 'ip')->textInput(['maxlength' => true]) ?>
				<?= $form->field($model, 'browser')->textInput(['maxlength' => true]) ?>
				<?= $form->field($model, 'os')->textInput(['maxlength' => true]) ?>
				<?= $form->field($model, 'device')->textInput(['maxlength' => true]) ?>
        	<?php $this->endContent() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
        	<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
        		'title' => 'Screenshots'
        	]) ?>
	        	<?= Dropzone::widget([
			        'tag' => 'Tech Issue',
			        'files' => $model->imageFiles,
			        'model' => $model,
			        'attribute' => 'photos',
			    ]) ?>
        	<?php $this->endContent() ?>
        </div>
        <div class="col-md-6">
        	<?php if (! $model->isNewRecord): ?>
	        	<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
	        		'title' => 'Remarks'
	        	]) ?>
					<?= $form->field($model, 'remarks')->textarea(['rows' => 8]) ?>
				<?php $this->endContent() ?>
    		<?php endif ?>
        </div>
    </div>

    

    <div class="form-group">
		<?= ActiveForm::buttons() ?>
    </div>
<?php ActiveForm::end(); ?>
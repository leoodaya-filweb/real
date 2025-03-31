<?php

use app\helpers\App;
use app\helpers\ArrayHelper;
use app\models\search\ThemeSearch;
use app\widgets\BootstrapSelect;
use app\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin(['id' => 'setting-general-notification-form']); ?>
    <h4 class="mb-10 font-weight-bold text-dark">System</h4>
	<div class="row">
		<div class="col-md-4">
			<?= BootstrapSelect::widget([
	            'attribute' => 'timezone',
	            'model' => $model,
	            'form' => $form,
	            'data' => App::component('general')->timezoneList(),
	        ]) ?>
		</div>
		<div class="col-md-4">
	        <?= $form->field($model, 'pagination')->dropDownList(
			    App::params('pagination'), [
			        'class' => "form-control kt-selectpicker",
			    ]
			) ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'auto_logout_timer')->textInput(['maxlength' => true])->label('Auto Logout Timer (Seconds)') ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4"> 
			<?= BootstrapSelect::widget([
	            'attribute' => 'theme',
	            'model' => $model,
	            'form' => $form,
	            'data' => ThemeSearch::dropdown(),
	            'searchable' => false,
	            'options' => [
			        'class' => 'kt-selectpicker form-control',
			    ]
	        ]) ?>
		</div> 
		<div class="col-md-4">
            <?= BootstrapSelect::widget([
	            'attribute' => 'whitelist_ip_only',
	            'model' => $model,
	            'form' => $form,
	            'label' => 'Ip Access',
	            'data' => App::keyMapParams('whitelist_ip_only'),
	            'searchable' => false,
	            'options' => [
			        'class' => 'kt-selectpicker form-control',
			    ]
	        ]) ?>
		</div>
		<div class="col-md-4">
            <?= BootstrapSelect::widget([
	            'attribute' => 'enable_visitor',
	            'model' => $model,
	            'form' => $form,
	            'label' => 'Enable Visitor',
	            'data' => App::keyMapParams('enable_visitor'),
	            'searchable' => false,
	            'options' => [
			        'class' => 'kt-selectpicker form-control',
			    ]
	        ]) ?>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4">
			<?= BootstrapSelect::widget([
	            'attribute' => 'logs_expiration',
	            'model' => $model,
	            'form' => $form,
	            'data' => ArrayHelper::range(100),
	            'label' => 'Logs Expiration (Days)'
	        ]) ?>
		</div>
	</div>
	<div class="form-group"> <br>
		<?= ActiveForm::buttons() ?>
	</div>
<?php ActiveForm::end(); ?>
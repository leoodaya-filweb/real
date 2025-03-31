<?php

use app\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin(['id' => 'database-form']); ?>
	
	<?php $this->beginContent('@app/views/layouts/_card_wrapper.php') ?>
		<?= $this->render('form/slp/main-information', [
			'model' => $model,
			'form' => $form,
		]) ?>
	<?php $this->endContent() ?>

	<div class="my-5"></div>
	<?php $this->beginContent('@app/views/layouts/_card_wrapper.php') ?>
		<?= $this->render('form/slp/primary-information', [
			'model' => $model,
			'form' => $form,
		]) ?>
	<?php $this->endContent() ?>

	<div class="my-5"></div>
	<?php $this->beginContent('@app/views/layouts/_card_wrapper.php') ?>
		<?= $this->render('form/address', [
			'model' => $model,
			'form' => $form,
		]) ?>
	<?php $this->endContent() ?>

	<div class="my-5"></div>
	<?php $this->beginContent('@app/views/layouts/_card_wrapper.php') ?>
		<?= $this->render('form/contact-information', [
			'model' => $model,
			'form' => $form,
		]) ?>
	<?php $this->endContent() ?>

	<div class="my-5"></div>
	<?php $this->beginContent('@app/views/layouts/_card_wrapper.php') ?>
		<?= $this->render('form/education-source-of-income', [
			'model' => $model,
			'form' => $form,
		]) ?>
	<?php $this->endContent() ?>
	
	<div class="my-5"></div>
	<?php $this->beginContent('@app/views/layouts/_card_wrapper.php') ?>
		<?= $this->render('form/others', [
			'model' => $model,
			'form' => $form,
		]) ?>
	<?php $this->endContent() ?>

	<div class="my-5"></div>
	<?= $this->render('form/id-documents', [
		'model' => $model,
		'form' => $form,
	]) ?>

	<div class="form-group">
		<?= ActiveForm::buttons('lg') ?>
	</div>
<?php ActiveForm::end(); ?>
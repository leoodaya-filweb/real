<?php

use app\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin(['id' => 'database-form']); ?>
	
	<?php $this->beginContent('@app/views/layouts/_card_wrapper.php') ?>
		<?= $this->render('form/pwd/main-information', [
			'model' => $model,
			'form' => $form,
		]) ?>
	<?php $this->endContent() ?>

	<div class="my-5"></div>
	<?php $this->beginContent('@app/views/layouts/_card_wrapper.php') ?>
		<?= $this->render('form/pwd/primary-information', [
			'model' => $model,
			'form' => $form,
		]) ?>
	<?php $this->endContent() ?>

	<div class="my-5"></div>
	<?php $this->beginContent('@app/views/layouts/_card_wrapper.php') ?>
		<?= $this->render('form/pwd/disability', [
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
		<?= $this->render('form/pwd/contact-information', [
			'model' => $model,
			'form' => $form,
		]) ?>
	<?php $this->endContent() ?>

	<div class="my-5"></div>
	<?php $this->beginContent('@app/views/layouts/_card_wrapper.php') ?>
		<?= $this->render('form/pwd/education-occupation', [
			'model' => $model,
			'form' => $form,
		]) ?>
	<?php $this->endContent() ?>

	<div class="my-5"></div>
	<?php $this->beginContent('@app/views/layouts/_card_wrapper.php') ?>
		<?= $this->render('form/pwd/employment-information', [
			'model' => $model,
			'form' => $form,
		]) ?>
	<?php $this->endContent() ?>

	<div class="my-5"></div>
	<?php $this->beginContent('@app/views/layouts/_card_wrapper.php') ?>
		<?= $this->render('form/pwd/organization-information', [
			'model' => $model,
			'form' => $form,
		]) ?>
	<?php $this->endContent() ?>

	<div class="my-5"></div>
	<?php $this->beginContent('@app/views/layouts/_card_wrapper.php') ?>
		<?= $this->render('form/pwd/reference-no', [
			'model' => $model,
			'form' => $form,
		]) ?>
	<?php $this->endContent() ?>

	<div class="my-5"></div>
	<?php $this->beginContent('@app/views/layouts/_card_wrapper.php') ?>
		<?= $this->render('form/pwd/family-background', [
			'model' => $model,
			'form' => $form,
		]) ?>
	<?php $this->endContent() ?>

	<div class="my-5"></div>
	<?php $this->beginContent('@app/views/layouts/_card_wrapper.php') ?>
		<?= $this->render('form/pwd/representative', [
			'model' => $model,
			'form' => $form,
		]) ?>
	<?php $this->endContent() ?>

	<div class="my-5"></div>
	<?php $this->beginContent('@app/views/layouts/_card_wrapper.php') ?>
		<?= $this->render('form/pwd/others', [
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
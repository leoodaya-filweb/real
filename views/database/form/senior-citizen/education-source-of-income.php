<?php

use app\models\Database;
use app\models\EducationalAttainment;
use app\widgets\DataList;

?>

<section>
	<div class="row">
		<div class="col-md-12">
			<h3 class="card-label">Education & Source of Income</h3>
			<hr/>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4">
			<?= DataList::widget([
				'form' => $form,
				'model' => $model,
				'attribute' => 'educ_attainment',
				'data' => EducationalAttainment::filter('label'),
				'sort' => false
			]) ?>
		</div>
		<div class="col-md-4">
			<?= DataList::widget([
				'form' => $form,
				'model' => $model,
				'attribute' => 'occupation',
				'data' => Database::filter('occupation'),
			]) ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'source_of_income')->textInput([
				'maxlength' => true,
			]) ?>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'monthly_income')->textInput([
				'type' => 'number'
			]) ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'other_source_income')->textInput([
				'maxlength' => true,
			]) ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'other_income_source_amount')->textInput([
				'type' => 'number'
			]) ?>
		</div>
	</div>
</section>
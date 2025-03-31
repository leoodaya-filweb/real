<?php

use app\models\Database;
use app\models\EducationalAttainment;
use app\widgets\DataList;
?>

<section>
	<div class="row">
		<div class="col-md-12">
			<h3 class="card-label">Source of Income</h3>
			<hr/>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'source_of_income')->textInput([
				'maxlength' => true,
			]) ?>
		</div>
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
	</div>

	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'other_income_source_amount')->textInput([
				'type' => 'number'
			]) ?>
		</div>
	</div>
</section>
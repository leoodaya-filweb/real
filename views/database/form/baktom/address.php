<?php

use app\models\Database;
use app\widgets\DataList;
?>

<section>
	<div class="row">
		<div class="col-md-12">
			<h3 class="card-label">Address</h3> 
			<hr/>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'house_no')->textInput([
				'maxlength' => true
			]) ?>
		</div>
		
		<div class="col-md-4">
			<?= $form->field($model, 'sitio')->textInput([
				'maxlength' => true
			]) ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'purok')->textInput([
				'maxlength' => true
			]) ?>
		</div>
	</div>
	<div class="row">
		
		<div class="col-md-4">
			<?= DataList::widget([
				'form' => $form,
				'model' => $model,
				'attribute' => 'barangay',
				'data' => Database::filter('barangay'),
			]) ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'municipality')->textInput([
				'maxlength' => true
			]) ?>
		</div>
	</div>
</section>
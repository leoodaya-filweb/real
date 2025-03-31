<?php

use app\models\Database;
use app\models\Relation;
use app\widgets\DataList;
?>
<section>
	<div class="row">
		<div class="col-md-12">
			<h3 class="card-label">Relation Information</h3>
			<hr/>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'living_with_whom')->textInput([
				'maxlength' => true
			]) ?>
		</div>
		<div class="col-md-4">
			<?= DataList::widget([
				'form' => $form,
				'model' => $model,
				'attribute' => 'relation',
				'data' => Relation::filter('label')
			]) ?>
		</div>
		<div class="col-md-4">
			<?= DataList::widget([
				'form' => $form,
				'model' => $model,
				'attribute' => 'relation_occupation',
				'data' => Database::filter('relation_occupation')
			]) ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'relation_income')->textInput([
				'type' => 'number'
			]) ?>
		</div>
	</div>
</section>
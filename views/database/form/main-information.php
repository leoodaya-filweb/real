<?php

use app\widgets\DatePicker;
?>

<section>
	<div class="row">
		<div class="col-md-12">
			<h3 class="card-label">Main Information</h3>
			 <hr/>
		</div>
	</div>
	
	<div class="row">
		<?= $this->render('@app/views/database/form/_record-status', [
			'form' => $form,
			'model' => $model,
		]) ?>
		<div class="col-md-4">
			<?= \app\widgets\SectorIDInput::widget([
				'priority_sector' => $_GET['priority_sector'],
				'model' => $model,
				'form' => $form,
			]) ?>
			<?php // $form->field($model, 'sector_id')->textInput([
				// 'maxlength' => true
			// ]) ?>
		</div>
		<div class="col-md-4">
			<?= DatePicker::widget([
				'form' => $form,
				'model' => $model,
				'attribute' => 'date_registered',
			]) ?>
		</div>
		<div class="col-md-4">
			<?= DatePicker::widget([
				'form' => $form,
				'model' => $model,
				'attribute' => 'date_of_application',
			]) ?>
		</div>
	</div>
</section>		
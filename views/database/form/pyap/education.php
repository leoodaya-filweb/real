<?php

use app\models\EducationalAttainment;
use app\widgets\DataList;
use app\widgets\DatePicker;
?>

<section>
	<div class="row">
		<div class="col-md-12">
			<h3 class="card-label">III. Paaralang Huling Pinasukan</h3>
			 <hr/>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'school_name_last_attended')->textInput([
				'maxlength' => true
			]) ?>
		</div>

		<div class="col-md-4">
			<?= $form->field($model, 'school_year_last_attended')->textInput() ?>
		</div>
		<div class="col-md-4">
			<?= DataList::widget([
				'form' => $form,
				'model' => $model,
				'attribute' => 'educ_attainment',
				'data' => EducationalAttainment::filter('label'),
				'sort' => false
			]) ?>
		</div>
	</div>
</section>		
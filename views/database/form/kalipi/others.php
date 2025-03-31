<?php

use app\helpers\App;
use app\widgets\Checkbox;
?>

<section>
	<div class="row">
		<div class="col-md-12">
			<h3 class="card-label">Others</h3>
			<hr/>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4">
			<label>
				<?= $model->getAttributeLabel('client_categories') ?>
			</label>
			<?= Checkbox::widget([
			    'data' => App::keyMapParams('client_categories', 'label', 'label'),
			    'name' => 'Database[client_category][]',
			    'inputClass' => 'checkbox',
			    'checkedFunction' => function($key, $value) use ($model) {
			        return isset($model->client_category) && in_array($key, $model->client_category) ? 'checked': '';
			    }
			]) ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'remarks')->textarea([
				'rows' => 8
			]) ?>
		</div>
	</div>
</section>
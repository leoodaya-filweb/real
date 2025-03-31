<?php

use app\helpers\App;
use app\helpers\ArrayHelper;
use app\widgets\BootstrapSelect;
use app\widgets\DataList;
?>

<section>
	<div class="row">
		<div class="col-md-12">
			<h3 class="card-label">Education & Occupation</h3>
			<hr/>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4">
			<?= BootstrapSelect::widget([
				'form' => $form,
				'model' => $model,
				'attribute' => 'educ_attainment',
				'data' => $model->educationalAttainmentDropdown,
			]) ?>
		</div>
		<div class="col-md-4">
			<?= DataList::widget([
				'form' => $form,
				'model' => $model,
				'attribute' => 'occupation',
				'data' => ArrayHelper::valueToKey(App::params('pwd_form')['occupation']),
			]) ?>
		</div>
	</div>
</section>
<?php

use app\helpers\App;
use app\helpers\ArrayHelper;
use app\widgets\BootstrapSelect;
?>

<section>
	<div class="row">
		<div class="col-md-12">
			<h3 class="card-label">Employment Information</h3>
			<hr/>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4">
			<?= BootstrapSelect::widget([
				'form' => $form,
				'model' => $model,
				'attribute' => 'status_of_employment',
				'data' => ArrayHelper::valueToKey(App::params('pwd_form')['status_of_employment']),
			]) ?>
		</div>
		<div class="col-md-4">
			<?= BootstrapSelect::widget([
				'form' => $form,
				'model' => $model,
				'attribute' => 'category_of_employment',
				'data' => ArrayHelper::valueToKey(App::params('pwd_form')['category_of_employment']),
			]) ?>
		</div>
		<div class="col-md-4">
			<?= BootstrapSelect::widget([
				'form' => $form,
				'model' => $model,
				'attribute' => 'types_of_employment',
				'data' => ArrayHelper::valueToKey(App::params('pwd_form')['types_of_employment']),
			]) ?>
		</div>
	</div>
</section>
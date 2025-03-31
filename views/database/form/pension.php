<?php

use app\helpers\App;
use app\widgets\BootstrapSelect;
use app\widgets\DataList;
?>

<section>
	<div class="row">
		<div class="col-md-12">
			<h3 class="card-label">Pension</h3>
			<hr/>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<?= BootstrapSelect::widget([
				'form' => $form,
				'model' => $model,
				'attribute' => 'pensioner',
				'data' => ['Yes' => 'Yes', 'No' => 'No']
			]) ?>
		</div>
		<div class="col-md-4">
			<?= DataList::widget([
				'form' => $form,
				'model' => $model,
				'attribute' => 'relation_where',
				'data' => App::keyMapParams('pensioner_from')
			]) ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'amount_of_pension')->textInput([
				'type' => 'number'
			]) ?>
		</div>
	</div>
</section>
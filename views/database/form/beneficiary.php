<?php

use app\widgets\BootstrapSelect;
?>

<section>
	<div class="row">
		<div class="col-md-12">
			<h3 class="card-label">Beneficiary</h3>
			<hr/>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<?= BootstrapSelect::widget([
				'form' => $form,
				'model' => $model,
				'attribute' => 'slp_beneficiary',
				'data' => ['True' => 'True', 'False' => 'False']
			]) ?>
		</div>
		<div class="col-md-4">
			<?= BootstrapSelect::widget([
				'form' => $form,
				'model' => $model,
				'attribute' => 'mcct_beneficiary',
				'data' => ['True' => 'True', 'False' => 'False']
			]) ?>
		</div>
	</div>
</section>
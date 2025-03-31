<?php

use app\models\ActiveRecord;
use app\widgets\BootstrapSelect;
?>

<?php if (! $model->isNewRecord): ?>
	<div class="col-md-4">
		<?= BootstrapSelect::widget([
			'form' => $form,
			'model' => $model,
			'attribute' => 'record_status',
			'label' => 'Status',
			'prompt' => false,
			'data' => [
				ActiveRecord::RECORD_ACTIVE => 'Active',
				ActiveRecord::RECORD_INACTIVE => 'Inactive',
			]
		]) ?>
	</div>
<?php endif ?>
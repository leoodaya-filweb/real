<?php

use app\helpers\Html;
use app\helpers\Url;
use app\widgets\ActiveForm;
use app\widgets\HouseholdSummary;
$h = $model->getHousehold();
?>


<?php $form = ActiveForm::begin(['id' => 'household-form']); ?>
	<?= HouseholdSummary::widget([
		'household' => $h,
        'action' => $action,
	]) ?>

	<div class="form-group mt-5">
		<?= Html::a('Back', Url::current(['step' => 'family-composition']), [
            'class' => 'btn btn-secondary btn-lg'
        ]) ?>
        <?= Html::submitButton('Save Household', [
            'class' => 'btn btn-success btn-lg'
        ]) ?>
    </div>
<?php ActiveForm::end(); ?>

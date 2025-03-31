<?php

use app\helpers\Html;
use app\widgets\HouseholdDetail;
?>

<?= Html::a('Update Household', $model->updateUrl, [
	'class' => 'btn btn-light-primary font-weight-bolder mb-5'
]) ?>
<?= HouseholdDetail::widget([
	'model' => $model
]) ?>
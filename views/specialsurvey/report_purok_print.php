<?php

use app\widgets\SpecialsurveyReport;

$query = clone $dataProvider->query;
$models = $query->asArray()->all();
?>
<div class="database-index-page">
	
	<?= SpecialsurveyReport::widget([
		'models' => $models,
		'searchModel' => $searchModel,
		'rowsummary' => $rowsummary,
		'per_purok' => true
	]) ?>
</div>

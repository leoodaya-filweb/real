<?php

use app\models\Database;
use app\widgets\DatabaseReport;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\DatabaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$query = clone $dataProvider->query;
$models = $query->asArray()->all();
?>

<?= DatabaseReport::widget([
	'models' => $models,
	'rowsummary' => $rowsummary,
	'priority_sector' => $priority_sector,
	'default' => '-0'
]) ?>
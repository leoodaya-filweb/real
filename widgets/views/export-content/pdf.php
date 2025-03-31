<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\form\export\ExportForm;
use app\widgets\ReportTemplate;
use yii\grid\GridView;
use yii\widgets\ListView;

$columns = (new ExportForm([
    'exportColumnsName' => $exportColumnsName,
    'excelIgnoreAttributesName' => $excelIgnoreAttributesName,
    'tableColumnsName' => $tableColumnsName,
]))->getExportColumns($searchModel, 'pdf');
?>

<?php // ReportTemplate::widget() ?>
<?= Html::ifElse($header, $header, implode(' ', [
    Html::tag('p', "{$reportName} Report", ['style' => 'font-size: 2rem !important;font-weight: 500;text-transform:uppercase;text-align:center;']),
    '<br>',
    Html::tag('span', implode(' - ', [
        date('F d, Y', strtotime($searchModel->startDate)),
        date('F d, Y', strtotime($searchModel->endDate)),
    ])),
])) ?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => $columns,
    'layout' => "{items}",
    'formatter' => ['class' => '\app\components\FormatterComponent']
]); ?>
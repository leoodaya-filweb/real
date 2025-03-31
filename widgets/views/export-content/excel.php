<?php
use \yii\grid\GridView;
use app\helpers\App;
use app\models\form\export\ExportForm;
?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => (new ExportForm([
        'exportColumnsName' => $exportColumnsName,
        'excelIgnoreAttributesName' => $excelIgnoreAttributesName,
        'tableColumnsName' => $tableColumnsName,
    ]))->getExportColumns($searchModel),
    'layout' => "{items}",
    'formatter' => ['class' => '\app\components\FormatterComponent']
]); ?>
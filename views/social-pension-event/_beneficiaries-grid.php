<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\CivilStatus;
use app\models\EducationalAttainment;
use app\models\EventMember;
use app\models\Household;
use app\models\Sex;
use app\widgets\ActiveForm;
use app\widgets\Anchor;
use app\widgets\Filter;
use app\widgets\Grid;
use yii\helpers\ArrayHelper;

$show = Html::if($dataProvider->totalCount > $searchModel->pagination,
    function() use($searchModel) {
        return $this->render('_show', [
            'paginations' => App::params('pagination'),
            'searchModel' => $searchModel,
        ]);
    }
);
?>

<?= Grid::widget([
    'dataProvider' => $dataProvider,
    'searchModel' => $searchModel,
    'layout' => <<< HTML
        <div class="d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap">
                <div class="mr-2">
                    {summary}
                </div>
                {$show}
            </div>
            <div class="">
                <form method="get" action="{$model->viewUrl}">
                    <input type="hidden" name="token" value="{$model->token}">
                    {$searchModel->getAutocompleteInput($model)}
                </form>
            </div>
        </div>
        <div class="my-2">
            {items}
        </div>
        <div class="d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap">
                <div class="mr-2">
                    {summary}
                </div>
            </div>
            <div class="">
                {pager}
            </div>
        </div>
    HTML,
    'columns' => $searchModel->socialPensionGridColumnsView,
]); ?>



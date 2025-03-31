<?php

use app\helpers\Html;
use app\models\search\DashboardSearch;
use app\widgets\BulkAction;
use app\widgets\FilterColumn;
use app\widgets\Grid;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\BudgetSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Budgets';
$this->params['breadcrumbs'][] = $this->title;
$this->params['searchModel'] = new DashboardSearch(); 
?>
<div class="budget-index-page">
    <?= $this->render('/setting/general/budget', [
        'model' => $model
    ]) ?>
</div>
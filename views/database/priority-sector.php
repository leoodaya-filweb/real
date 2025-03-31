<?php

use app\helpers\Html;
use app\models\search\DatabaseSearch;

$this->title ='All Priority Sector';

$this->params['breadcrumbs'][] = ['label' => 'Database: Priority Sectors', 'url' => (new DatabaseSearch())->indexUrl];
$this->params['breadcrumbs'][] = $this->title;
$this->params['searchModel'] = new DatabaseSearch(); 
$this->params['showCreateButton'] = false; 
$this->params['showExportButton'] = false;
$this->params['activeMenuLink'] = '/database/priority-sector';
$this->params['headerButtons'] = Html::button('Add Priority Sector', [
    'class' => 'btn btn-success font-weight-bold ml-2 btn-add-new-sector'
]);
?>

<div class="priority-sector-index-page">
    <?= $this->render('/setting/general/priority-sector', [
        'model' => $model,
        'withHeader' => false
    ]) ?>
</div>
<?php

use app\models\UnPlannedAttendeesEvent;
use app\models\search\UnPlannedAttendeesEventSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Transaction */

$this->title = 'Open Event: Create';
$this->params['breadcrumbs'][] = ['label' => 'Open Event', 'url' => (new UnPlannedAttendeesEvent())->indexUrl];
$this->params['breadcrumbs'][] = 'Create';
$this->params['searchModel'] = new UnPlannedAttendeesEventSearch();
$this->params['activeMenuLink'] = '/open-event';
$this->params['wrapCard'] = true;
?>

<div class="event-create-page">
    <?= $this->render('_form', [
        'model' => $model,
        'tab' => $tab,
    ]) ?>
</div>
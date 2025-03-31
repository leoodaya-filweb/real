<?php

use app\models\UnPlannedAttendeesEvent;
use app\models\search\UnPlannedAttendeesEventSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Transaction */

$this->title = 'Open Event: Update';
$this->params['breadcrumbs'][] = ['label' => 'Open Event', 'url' => (new UnPlannedAttendeesEvent())->indexUrl];
$this->params['breadcrumbs'][] = 'Update';
$this->params['searchModel'] = new UnPlannedAttendeesEventSearch();
$this->params['activeMenuLink'] = '/open-event';
$this->params['wrapCard'] = true;
?>

<div class="event-update-page">
    <?= $this->render('_form', [
        'model' => $model,
        'tab' => $tab,
    ]) ?>
</div>
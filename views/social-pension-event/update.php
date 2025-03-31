<?php

use app\models\SocialPensionEvent;
use app\models\search\SocialPensionEventSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Transaction */

$this->title = 'Social Pension: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Social Pension Event', 'url' => (new SocialPensionEvent())->indexUrl];
$this->params['breadcrumbs'][] = 'Update';
$this->params['searchModel'] = new SocialPensionEventSearch();
$this->params['wrapCard'] = true;
?>

<div class="event-create-page">
    <?= $this->render('_form', [
        'model' => $model,
        'tab' => $tab,
    ]) ?>
</div>
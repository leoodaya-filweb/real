<?php

use app\models\search\SocialPensionerSearch;

/* @var $this yii\web\View */
/* @var $model app\models\SocialPensioner */

$this->title = 'Duplicate Social Pensioner: ' . $originalModel->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Social Pensioners', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $originalModel->mainAttribute, 'url' => $originalModel->viewUrl];
$this->params['breadcrumbs'][] = 'Duplicate';
$this->params['searchModel'] = new SocialPensionerSearch();
$this->params['showCreateButton'] = true; 
$this->params['wrapCard'] = false;
?>
<div class="social-pensioner-duplicate-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
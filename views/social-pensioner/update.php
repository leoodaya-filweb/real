<?php

use app\models\search\SocialPensionerSearch;

/* @var $this yii\web\View */
/* @var $model app\models\SocialPensioner */

$this->title = 'Update Social Pensioner: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Social Pensioners', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $model->mainAttribute, 'url' => $model->viewUrl];
$this->params['breadcrumbs'][] = 'Update';
$this->params['searchModel'] = new SocialPensionerSearch();
$this->params['showCreateButton'] = true; 
$this->params['wrapCard'] = false;
?>
<div class="social-pensioner-update-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
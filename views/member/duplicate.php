<?php

use app\models\search\MemberSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Member */

$this->title = 'Duplicate Member: ' . $originalModel->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Members', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $originalModel->mainAttribute, 'url' => $originalModel->viewUrl];
$this->params['breadcrumbs'][] = 'Duplicate';
$this->params['searchModel'] = new MemberSearch();
$this->params['showCreateButton'] = true; 
$this->params['wrapCard'] = false;
?>
<div class="member-duplicate-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
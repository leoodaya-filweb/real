<?php

use app\models\search\SpecialsurveySearch;

/* @var $this yii\web\View */
/* @var $model app\models\Specialsurvey */

$this->title = 'Duplicate Survey: ' . $originalModel->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Surveys', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $originalModel->mainAttribute, 'url' => $originalModel->viewUrl];
$this->params['breadcrumbs'][] = 'Duplicate';
$this->params['searchModel'] = new SpecialsurveySearch();
$this->params['activeMenuLink'] = '/specialsurvey';
?>
<div class="specialsurvey-duplicate-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
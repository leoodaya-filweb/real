<?php

use app\models\search\SpecialsurveySearch;

/* @var $this yii\web\View */
/* @var $model app\models\Specialsurvey */

$this->title = 'Update Survey: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Surveys', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $model->mainAttribute, 'url' => $model->viewUrl];
$this->params['breadcrumbs'][] = 'Update';
$this->params['searchModel'] = new SpecialsurveySearch();
$this->params['activeMenuLink'] = '/specialsurvey';
?>
<div class="specialsurvey-update-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
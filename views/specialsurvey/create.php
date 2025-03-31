<?php

use app\models\search\SpecialsurveySearch;

/* @var $this yii\web\View */
/* @var $model app\models\Specialsurvey */

$this->title = 'Create Survey';
$this->params['breadcrumbs'][] = ['label' => 'Surveys', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = 'Create';
$this->params['searchModel'] = new SpecialsurveySearch();
$this->params['activeMenuLink'] = '/specialsurvey';
?>
<div class="specialsurvey-create-page">
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>
</div>
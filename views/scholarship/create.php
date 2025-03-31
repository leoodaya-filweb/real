<?php

use app\models\search\ScholarshipSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Scholarship */

$this->title = 'Create Scholarship';
$this->params['breadcrumbs'][] = ['label' => 'Scholarships', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = 'Create';
$this->params['searchModel'] = new ScholarshipSearch();
?>
<div class="scholarship-create-page">
	<?= $this->render('_form-step', [
		'model' => $model,
		'tab' => $tab,
	]) ?>
</div>
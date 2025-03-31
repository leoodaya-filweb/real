<?php

use app\models\search\MemberSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Member */

$this->title = 'Create Member';
$this->params['breadcrumbs'][] = ['label' => 'Members', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = 'Create';
$this->params['searchModel'] = new MemberSearch();
$this->params['wrapCard'] = false;
?>
<div class="member-create-page container">
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>
</div>
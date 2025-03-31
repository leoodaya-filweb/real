<?php

use app\helpers\Html;
use app\models\search\MemberSearch;
use app\widgets\ConfirmBulkAction;

$this->title = 'Confirm Bulk Action';
$this->params['breadcrumbs'][] = ['label' => 'Members', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = $this->title;
$this->params['showCreateButton'] = true;
$this->params['searchModel'] = new MemberSearch();
?>
<div class="member-bulk-action-page">

	<?= Html::ifElse(
		$post['process-selected'] == 'printqr', 
		$this->render('print-qr', ['models' => $models]),
		Html::ifElse(
			$post['process-selected'] == 'print-id', 
			$this->render('print-id', ['models' => $models]),
			ConfirmBulkAction::widget([
				'models' => $models,
				'process' => $process,
			    'post' => $post,
			])
		)
	) ?>
</div>
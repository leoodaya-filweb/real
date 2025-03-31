<?php

use app\helpers\Html;
use app\models\search\TransactionSearch;
use app\widgets\Reminder;
use app\widgets\SearchQrCode;

/* @var $this yii\web\View */
/* @var $model app\models\Transaction */

$this->title = 'Create Transaction';
$this->params['breadcrumbs'][] = ['label' => 'Transactions', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => 'Update Profile', 'url' => $model->getCreateTransactionLink($type)];
$this->params['breadcrumbs'][] = 'Create';
$this->params['searchModel'] = new TransactionSearch();
$this->params['wrapCard'] = false;
Html::if($withSlug, function() use($member) {
    $this->params['breadcrumbs'][] = ['label' => $member->name, 'url' => $member->viewUrl];
});

?>

<div class="transaction-create-page">
	<?php if ($member): ?>
	
		<div class="my-3"></div>

		<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
			'title' => 'Select Transaction Type'
		]); ?>
			<?= $this->render('@app/views/member/_add-transaction', ['model' => $member]) ?>
		<?php $this->endContent(); ?>
	<?php else: ?>
		<div class="row">
			<div class="col-md-6">
				<?= Html::ifELse(! $member, SearchQrCode::widget(['title' => 'Select Member'])) ?>
			</div>
			<div class="col-md-6">
				<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
	                'title' => 'Procedure'
	            ]); ?>
	            <ul>
	                <li>Type or Scan QR Code</li>
	                <li>Select Transaction Type</li>
	                <li>Upload Necessary Documents</li>
	                <li>Manage Status</li>
	            </ul>
	            <?php $this->endContent(); ?>
			</div>
		</div>
	<?php endif ?>
</div>
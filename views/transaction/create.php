<?php

use app\helpers\Html;
use app\models\search\TransactionSearch;
use app\widgets\SearchQrCode;

/* @var $this yii\web\View */
/* @var $model app\models\Transaction */

$this->title = 'Create Transaction';
$this->params['breadcrumbs'][] = ['label' => 'Transactions', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = 'Create';
$this->params['searchModel'] = new TransactionSearch();
$this->params['wrapCard'] = false;
Html::if($withSlug, function() use($member) {
	$this->params['breadcrumbs'][] = $member->name;
});

?>

<div class="transaction-create-page">
	<?php if ($member): ?>
		<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
			'title' => 'Select Transaction Type'
		]); ?>
			<?= $this->render('@app/views/member/_add-transaction', ['model' => $member]) ?>
		<?php $this->endContent(); ?>
	<?php else: ?>
		<div class="row">
			<div class="col-md-4">
				<?= Html::ifELse(! $member, SearchQrCode::widget(['title' => 'Select Member'])) ?>
			</div>
		</div>
	<?php endif ?>

</div>
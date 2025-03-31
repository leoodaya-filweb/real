<?php

use app\models\search\TransactionSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Transaction */

$this->title = 'View Transaction: ' . $model->mainAttribute.' - '.$model->transactionTypeName;
$this->params['breadcrumbs'][] = ['label' => 'Transactions', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ucwords(strtolower($model->mainAttribute));
$this->params['breadcrumbs'][] = $model->transactionTypeName;
$this->params['searchModel'] = new TransactionSearch();
$this->params['showCreateButton'] = false; 
$this->params['wrapCard'] = false; 

?> 
<?php $this->beginContent('@app/views/transaction/view/index.php', [
    'model' => $model,
    'tab' => $tab,
]); ?>

    <?= $this->render("tabs/{$tab}", [
        'model' => $model,
        'tab' => $tab,
    ]) ?>

<?php $this->endContent(); ?>

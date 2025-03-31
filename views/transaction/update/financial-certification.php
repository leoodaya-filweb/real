<?php

use app\models\search\TransactionSearch;
use app\widgets\ActiveForm;
use app\widgets\TinyMce;



$this->title = 'Update Certificate: Financial Certification';
$this->params['breadcrumbs'][] = ['label' => 'Transactions', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => 'Financial Certification', 'url' => $model->viewUrl];
$this->params['breadcrumbs'][] = 'Update';
$this->params['searchModel'] = new TransactionSearch();
// $this->params['wrapCard'] = false;

?>

<?php $form = ActiveForm::begin(['id' => 'transaction-form']); ?>
    <?= TinyMce::widget([
        'size' => 'A4',
        'model' => $model,
        'attribute' => 'content',
        'height' => '400mm',
        'toolbar' => 'advlist | autolink | link image | lists charmap | table tabledelete | pagebreak | print',
        'plugins' =>  'advlist autolink link image lists charmap preview table pagebreak print',
    ]) ?>
    <div class="mt-5">
        <?= ActiveForm::buttons('lg') ?>
    </div>
<?php ActiveForm::end(); ?>

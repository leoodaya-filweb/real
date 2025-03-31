<?php

use app\models\search\TransactionSearch;
use app\widgets\ActiveForm;
use app\widgets\TinyMce;



$this->title = 'Update Certificate: Social Case Study Report';
$this->params['breadcrumbs'][] = ['label' => 'Transactions', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => 'Social Case Study Report', 'url' => $model->viewUrl];
$this->params['breadcrumbs'][] = 'Update';
$this->params['searchModel'] = new TransactionSearch();
// $this->params['wrapCard'] = false;

?>

<?php $form = ActiveForm::begin(['id' => 'transaction-form']); ?>
    <?= TinyMce::widget([
        'model' => $model,
        'attribute' => 'content',
        'height' => '500mm',
        'toolbar' => 'advlist | autolink | link image | lists charmap | table tabledelete | pagebreak | print',
        'plugins' =>  'advlist autolink link image lists charmap preview table pagebreak print',
    ]) ?>
    <div class="mt-5">
        <?= ActiveForm::buttons('lg') ?>
    </div>
<?php ActiveForm::end(); ?>

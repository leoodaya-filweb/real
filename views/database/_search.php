<?php
use app\helpers\App;
use app\models\Database;
use app\widgets\BootstrapSelect;
use app\widgets\DateRange;
use app\widgets\Filter;
use app\widgets\Search;
use app\widgets\SearchButton;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\DatabaseSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin([
    'action' => $model->searchAction,
    'method' => 'get',
    'id' => 'database-search-form'
]); ?>
    <?= Search::widget(['model' => $model]) ?>
    <?= DateRange::widget(['model' => $model]) ?>

    <div class="mt-5">
        <?= BootstrapSelect::widget([
            'form' => $form,
            'model' => $model,
            'attribute' => 'priority_sector',
            'name' => 'priority_sector',
            'data' => Database::mapPrioritySector('id', 'label', false)
        ]) ?>
    </div>

    <?= Filter::widget([
    	'form' => $form,
    	'model' => $model,
    	'attribute' => 'gender',
    	'data' => ['Male' => 'Male', 'Female' => 'Female',]
    ]) ?>

    <?= Filter::widget([
    	'form' => $form,
    	'model' => $model,
    	'attribute' => 'status',
    	'data' => ['Active' => 'Active', 'Inactive' => 'Inactive',]
    ]) ?>

    <?= SearchButton::widget() ?>
<?php ActiveForm::end(); ?>
<?php
use app\helpers\App;
use yii\helpers\ArrayHelper;
use app\widgets\BootstrapSelect;
use app\widgets\Filter;
use app\widgets\Pagination;
use app\widgets\Search;
use app\widgets\DateRange;
use yii\widgets\ActiveForm;
use app\widgets\SearchButton;
use app\widgets\RecordStatusFilter;
use app\models\Specialsurvey;

/* @var $this yii\web\View */
/* @var $model app\models\search\SpecialsurveySearch */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin([
    'action' => $model->searchAction,
    'method' => 'get',
    'id' => 'specialsurvey-search-form'
]); ?>
    <?= Search::widget(['model' => $model]) ?>
    <?= DateRange::widget(['model' => $model]) ?>
<div class="my-5"></div>
	<?= BootstrapSelect::widget([
        'form' => $form,
        'model' => $model,
        'attribute' => 'barangay',
        'name' => 'barangay',
        'data' => Specialsurvey::filter('barangay')
    ]); ?>
    
   <?= $form->field($model, 'precinct_no')->textInput(['maxlength' => true, 'name'=>'precinct_no',  'class'=>'form-control']) ?>   
    
<div class="my-5"></div>
   <?= BootstrapSelect::widget([
        'form' => $form,
        'model' => $model,
        'attribute' => 'survey_name',
        'name' => 'survey_name',
        'data' => Specialsurvey::filter('survey_name')
    ]) ?>



    <div class="my-5"></div>
    <?= BootstrapSelect::widget([
        'form' => $form,
        'model' => $model,
        'attribute' => 'purok',
        'name' => 'purok',
        'data' => Specialsurvey::filter('purok')
    ]); ?>

	<?= Filter::widget([
    	'form' => $form,
    	'model' => $model,
    	'attribute' => 'gender',
    	'data' => Specialsurvey::filter('gender')
    ]) ?>

    <?= Pagination::widget([
        'model' => $model,
        'form' => $form,
    ]) ?>
    <?= SearchButton::widget() ?>
<?php ActiveForm::end(); ?>
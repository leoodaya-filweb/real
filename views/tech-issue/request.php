<?php

use app\helpers\App;
use app\helpers\Html;
use app\widgets\ActiveForm;
use app\widgets\BootstrapSelect;
use app\widgets\Dropzone;
use app\widgets\Grid;
use app\widgets\InputList;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\TechIssueSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Technical Issues';
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = 'Request';
$this->params['searchModel'] = $searchModel; 
$this->params['wrapCard'] = false;
$this->params['searchKeywordUrl'] = ['tech-issue/find-by-keywords', 'user_id' => App::identity('id')];
$this->params['activeMenuLink'] = '/tech-issue/request';

$this->registerJsFile(
    App::publishedUrl('/firebase/real.accessgov.ph.js', Yii::getAlias('@app/assets')), [
    'type' => 'module',
]);
?>
<div class="tech-issue-index-page">
    <div class="row">
        <div class="col-md-6">
            <?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
                'title' => 'Report a Problem',
                'stretch' => true
            ]) ?>
                <?php $form = ActiveForm::begin([
                    'id' => 'tech-issue-form',
                ]); ?>
                    <?= BootstrapSelect::widget([
                        'form' => $form,
                        'model' => $model,
                        'attribute' => 'type',
                        'data' => App::keyMapParams('tech_issue_types')
                    ]) ?>
                    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
                    <div class="form-group required">
                        <label class="control-label">How to Reproduce</label>
                        <?= InputList::widget([
                            'label' => 'Step',
                            'name' => 'TechIssue[steps][]',
                            'data' => $model->steps
                        ]) ?>
                    </div> 
                    <?= Dropzone::widget([
                        'tag' => 'Tech Issue',
                        'files' => $model->imageFiles,
                        'model' => $model,
                        'attribute' => 'photos',
                    ]) ?>

                    <div class="my-5"></div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'ip')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'browser')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'os')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'device')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?= ActiveForm::buttons() ?>
                    </div>
                <?php ActiveForm::end(); ?>
            <?php $this->endContent() ?>
        </div>
        <div class="col-md-6">
            <?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
                'title' => 'Request Logs',
                'stretch' => true
            ]) ?>
                <?= Grid::widget([
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                    'withActionColumn' => false,
                    'columns' => [
                        'serial' => ['class' => 'yii\grid\SerialColumn'],
                        'token' => [
                            'attribute' => 'token', 
                            'label' => 'Ticket Id',
                            'format' => 'raw',
                            'value' => fn ($techIssue) => Html::a($techIssue->ticketId, $techIssue->viewUrl)
                        ],
                        'type' => [
                            'attribute' => 'type', 
                            'value' => 'typeLabel',
                            'format' => 'raw'
                        ],
                        'description' => [
                            'attribute' => 'description', 
                            'format' => 'raw',
                            'value' => 'truncatedDescription'
                        ],
                        'status' => [
                            'attribute' => 'status', 
                            'value' => 'statusLabel',
                            'format' => 'raw'
                        ],
                        'last_updated' => [
                            'attribute' => 'updated_at',
                            'label' => 'last updated',
                            'format' => 'ago',
                        ],
                    ]
                ]); ?>
            <?php $this->endContent() ?>
        </div> 
    </div>
</div>

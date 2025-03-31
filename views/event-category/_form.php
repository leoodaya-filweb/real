<?php

use app\helpers\App;
use app\helpers\Html;
use app\widgets\ActiveForm;
use app\widgets\BootstrapSelect;
use app\widgets\ImageGallery;

/* @var $this yii\web\View */
/* @var $model app\models\EventCategory */
/* @var $form app\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(['id' => 'event-category-form']); ?>
    <div class="row">
        <div class="col-md-5">
            <?= BootstrapSelect::widget([
                'model' => $model,
                'form' => $form,
                'attribute' => 'sort_order',
                'data' => App::keyMapParams('event_category_types')
            ]) ?>

            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
			<?= ActiveForm::recordStatus([
                'model' => $model,
                'form' => $form,
            ]) ?>
        </div>
        <div class="col-md-7">
            <?= Html::image($model->value, ['w' => 200], [
                'class' => 'img-thumbnail event-photo mt-7 mw-200'
            ]) ?>
            <div class="mt-7">
                <?= ImageGallery::widget([
                    'tag' => 'Event Category',
                    'buttonTitle' => 'Choose Photo',
                    'model' => $model,
                    'attribute' => 'value',
                    'ajaxSuccess' => "
                        if(s.status == 'success') {
                            $('.event-photo').attr('src', s.src);
                        }
                    ",
                ]) ?> 
            </div>
        </div>
    </div>
    <div class="form-group">
		<?= ActiveForm::buttons() ?>
    </div>
<?php ActiveForm::end(); ?>
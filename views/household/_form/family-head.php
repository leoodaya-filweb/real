<?php

use app\helpers\Html;
use app\helpers\Url;
use app\widgets\ActiveForm;
?>

<h4 class="mb-10 font-weight-bold text-dark">
    Family Head's Information
    <!-- <a href="#" class="btn btn-secondary btn-sm">
        <i class="fa fa-search"> </i> Find from existing
    </a> -->
    <?= Html::if($model->qr_id, Html::a('Download QR', $model->downloadQrCodeUrl, [
        'class' => 'btn btn-sm btn-facebook font-weight-bolder'
    ])) ?>
</h4>

<?php $form = ActiveForm::begin(['id' => 'member-form']); ?>
    <?= $this->render('_member-form-fields', [
        'model' => $model,
        'form' => $form,
        'with_photo' => true,
    ]) ?>

    <div class="form-group mt-5">
		<?= Html::a('Back', Url::current(['step' => 'map']), [
            'class' => 'btn btn-secondary btn-lg'
        ]) ?>
        <?= Html::submitButton('Next', [
            'class' => 'btn btn-success btn-lg'
        ]) ?>
    </div>
<?php ActiveForm::end(); ?>
<?php

use app\helpers\App;
use app\helpers\Html;
use app\widgets\ActiveForm;
use app\widgets\BootstrapSelect;

?>

<?php $form = ActiveForm::begin([
    'id' => 'add-member-form', 
    'enableAjaxValidation' => true,
    'action' => $action ?? ['household/add-family-composition', 'no' => $household->no],
    'validationUrl' => [
        'household/add-family-composition', 
        'no' => $household->no, 
        'member_id' => $model->id, 
        'ajaxValidate' => true
    ]
]) ?>
    <?= $this->render('_member-form-fields', [
        'model' => $model,
        'form' => $form,
    ]) ?>

    <button type="submit" style="display: none;"></button>
<?php ActiveForm::end(); ?>
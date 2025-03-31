<?php

use app\helpers\App;
use app\helpers\Html;
use app\widgets\ActiveForm;

$counter = 0;
?>

<h4 class="mb-10 font-weight-bold text-dark">
    <?= $tabData['title'] ?>
</h4>

<?php $form = ActiveForm::begin() ?>
    <h6 class="font-weight-bolder mb-3">
        General Information:
        <?= Html::a('<i class="fa fa-edit text-warning" title="Edit" data-toggle="tooltip"></i>', [
            App::actionID(), 
            'token' => $model->token, 
            'tab' => 'general-information'
        ]) ?>
    </h6>
    <p class="font-weight-bolder text-muted mb-0">PRIMARY DETAILS</p>
    <div class="text-dark-50 line-height-lg">
        <div> <b><?= $model->getAttributeLabel('fullname') ?>:</b> <?= $model->fullname ?> </div>
        <div> <b><?= $model->getAttributeLabel('birth_date') ?>:</b> <?= $model->birth_date ?> </div>
        <div> <b><?= $model->getAttributeLabel('age') ?>:</b> <?= $model->age ?> </div>
        <div> <b><?= $model->getAttributeLabel('sex') ?>:</b> <?= $model->sexLabel ?> </div>
        <div> <b><?= $model->getAttributeLabel('guardian') ?>:</b> <?= $model->guardian ?> </div>
    </div>
    <div class="my-10"></div>
    <p class="font-weight-bolder text-muted mb-0">ADDRESS</p>
    <div class="text-dark-50 line-height-lg">
        <div> <b><?= $model->getAttributeLabel('barangayName') ?>:</b> <?= $model->barangayName ?> </div>
        <div> <b><?= $model->getAttributeLabel('house_no') ?>:</b> <?= $model->house_no ?> </div>
        <div> <b><?= $model->getAttributeLabel('street_address') ?>:</b> <?= $model->street_address ?> </div>
    </div>
    <div class="my-10"></div>
    <p class="font-weight-bolder text-muted mb-0">CONTACT DETAILS</p>
    <div class="text-dark-50 line-height-lg">
        <div> <b><?= $model->getAttributeLabel('email') ?>:</b> <?= $model->email ?> </div>
        <div> <b><?= $model->getAttributeLabel('alternate_email') ?>:</b> <?= $model->alternate_email ?> </div>
        <div> <b><?= $model->getAttributeLabel('contact_no') ?>:</b> <?= $model->contact_no ?> </div>
        <div> <b><?= $model->getAttributeLabel('alternate_contact_no') ?>:</b> <?= $model->alternate_contact_no ?> </div>
    </div>

    <div class="separator separator-dashed my-10"></div>

    <h6 class="font-weight-bolder mb-3">
        Educations:
        <?= Html::a('<i class="fa fa-edit text-warning" title="Edit" data-toggle="tooltip"></i>', [
            App::actionID(), 
            'token' => $model->token, 
            'tab' => 'educations'
        ]) ?>
    </h6>
    <div class="text-dark-50 line-height-lg">
        <table class="table table-bordered">
            <thead>
                <th>#</th>
                <th>school name</th>
                <th>course</th>
                <th>year level</th>
                <th>school year</th>
            </thead>
            <tbody>
                <?= App::foreach($model->educations, function($education, $index, $counter) {
                    return <<< HTML
                        <tr> 
                            <td>{$counter}</td>
                            <td>{$education['school_name']}</td>
                            <td>{$education['course']}</td>
                            <td>{$education['year_level']}</td>
                            <td>{$education['school_year']}</td>
                        </tr>
                    HTML;
                }) ?>
            </tbody>
        </table>
    </div>

    <div class="separator separator-dashed my-10"></div>

    <h6 class="font-weight-bolder mb-3">
        Documents & Photo:
        <?= Html::a('<i class="fa fa-edit text-warning" title="Edit" data-toggle="tooltip"></i>', [
            App::actionID(), 
            'token' => $model->token, 
            'tab' => 'documents'
        ]) ?>
    </h6>

    <p class="font-weight-bolder text-muted mb-0">PROFILE PHOTO</p>
    <?= Html::image($model->photo, ['w' => 200], ['class' => 'img-fluid symbol']) ?>

    <div class="my-10"></div>
    <p class="font-weight-bolder text-muted mb-2">DOCUMENTS</p>
    <?php $this->beginContent('@app/views/file/_row-header.php', [
        'withAction' => false
    ]); ?>
        <?= App::foreach(
            $model->imageFiles, 
            fn($file) => $this->render('/file/_row', [
                'model' => $file,
                'withAction' => false
            ])
        ) ?>
    <?php $this->endContent(); ?>

    <?= $form->field($model, 'id')->hiddenInput(['maxlength' => true])->label(false) ?>

    <div class="form-group mt-10">
        <?= Html::a('Previous', ['scholarship/' . App::actionID(), 'token' => $model->token, 'tab' => 'documents'], [
            'class' => 'btn btn-light-primary font-weight-bold btn-lg'
        ]) ?>
        <?= Html::submitButton('Save', [
            'class' => 'btn btn-success btn-lg font-weight-bold'
        ]) ?>
    </div>
<?php ActiveForm::end(); ?>


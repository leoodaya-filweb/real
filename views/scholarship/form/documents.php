<?php

use app\helpers\App;
use app\helpers\Html;
use app\widgets\ActiveForm;
use app\widgets\Dropzone;
use app\widgets\ImageGallery;
use app\widgets\Reminder;
?>

<h4 class="mb-10 font-weight-bold text-dark">
    <?= $tabData['title'] ?>
</h4>

<?php $form = ActiveForm::begin() ?>
    <?= Reminder::widget([
        'type' => 'info',
        'head' => 'Requirements',
        'withDot' => false,
        'message' => <<< HTML
            <ul class="mb-0">
                <li>Certificate of Grades (Renewal / College Student)</li>
                <li>Official Receipt</li>
                <li>Certificate of Registration / Enrollment Form</li>
                <li>Promisory Note (<b>If have grades below 80%</b>)</li>
                <li>Grades from grade 12 (<b>If new scholar / upcoming college student</b>)</li>
            </ul>
        HTML
    ]) ?>
    <div class="row">
        <div class="col-md-4">
            <p class="lead font-weight-bold text-uppercase">Profile Photo</p>
            <?= Html::image($model->photo, ['w' => 200], [
                'class' => 'img-thumbnail user-photo',
                'loading' => 'lazy',
            ] ) ?>
            <div class="my-2"></div>
            <?= ImageGallery::widget([
                'buttonTitle' => 'Choose Photo',
                'tag' => 'Scholarship',
                'model' => $model,
                'attribute' => 'photo',
                'ajaxSuccess' => "
                    if(s.status == 'success') {
                        $('.user-photo').attr('src', s.src);
                    }
                ",
            ]) ?> 
        </div>
        <div class="col-md-8">
            <p class="lead font-weight-bold text-uppercase">Requirements</p>
            <?= Dropzone::widget([
                'tag' => 'Scholarship',
                'model' => $model,
                'attribute' => 'documents',
                'inputName' => 'hidden',
                'success' => <<< JS
                    this.removeFile(file);
                    $('#table-file').DataTable({
                        destroy: true,
                        pageLength: 5,
                        order: [[0, 'desc']]
                    }).row.add($(s.row)).draw();

                    $('.document-container-holder').prepend('<input class="app-hidden file-hidden-input-'+ s.file.token +'" type="text" name="Scholarship[documents][]" value="'+ s.file.token +'">'); 
                JS,
            ]) ?>
        </div>
    </div>
    

    <?= $this->render('_documents', [
        'model' => $model
    ]) ?>
    <div class="form-group mt-10">
        <?= Html::a('Previous', ['scholarship/' . App::actionID(), 'token' => $model->token, 'tab' => 'educations'], [
            'class' => 'btn btn-light-primary font-weight-bold btn-lg'
        ]) ?>
        <?= Html::submitButton('Next', [
            'class' => 'btn btn-success btn-lg font-weight-bold'
        ]) ?>
    </div>
<?php ActiveForm::end(); ?>


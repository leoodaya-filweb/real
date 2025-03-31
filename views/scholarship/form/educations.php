<?php

use app\helpers\App;
use app\helpers\ArrayHelper;
use app\helpers\Html;
use app\widgets\ActiveForm;
use app\widgets\BootstrapSelect;

$this->addJsFile('sortable/Sortable.min');
$this->registerJs(<<< JS
    const schoolName = $('#educations-container input[name="school_name"]'),
        course = $('#educations-container input[name="course"]'),
        yearLevel = $('#educations-container input[name="year_level"]'),
        schoolYear = $('#educations-container input[name="school_year"]'),
        addBtn = $('#educations-container .btn-add'),
        listContainer = $('#educations-container .list-container');

    const getTimestampInSeconds =  () => {
        return Math.floor(Date.now() / 1000)
    }
    const generateHtml = () => {
        let schoolNameValue = schoolName.val().trim(),
            courseValue = course.val().trim(),
            yearLevelValue = yearLevel.val().trim(),
            schoolYearValue = schoolYear.val().trim(),
            index = getTimestampInSeconds();

        if(schoolNameValue && courseValue && yearLevelValue && schoolYearValue) {
            let html = '\
                <div class="input-group mb-2">\
                    <div class="input-group-prepend">\
                        <button class="btn btn-secondary handle-sortable" type="button">\
                            <i class="fas fa-arrows-alt"></i>\
                        </button>\
                    </div>\
                    <input value="'+ schoolNameValue +'" type="text" name="Scholarship[educations]['+index+'][school_name]" class="form-control" placeholder="Enter School Name">\
                    <input value="'+ courseValue +'" type="text" name="Scholarship[educations]['+index+'][course]" class="form-control" placeholder="Enter Course">\
                    <input value="'+ yearLevelValue +'" type="text" name="Scholarship[educations]['+index+'][year_level]" class="form-control" placeholder="Enter Year Level">\
                    <input value="'+ schoolYearValue +'" type="text" name="Scholarship[educations]['+index+'][school_year]" class="form-control" placeholder="Enter School Year">\
                    <div class="input-group-append">\
                        <button class="btn btn-danger btn-icon btn-remove" type="button">\
                            <i class="fa fa-trash"></i>\
                        </button>\
                    </div>\
                </div>\
            ';

            listContainer.append(html);
            schoolName.val('').focus();
            course.val('');
            yearLevel.val('');
            schoolYear.val('');
        }
    };

    const enterEvent = (e) => {
        if(e.key == 'Enter') {
            e.preventDefault();
            generateHtml();
        }
    }

    schoolName.on('keydown', enterEvent);
    course.on('keydown', enterEvent);
    yearLevel.on('keydown', enterEvent);
    schoolYear.on('keydown', enterEvent);

    addBtn.on('click', () => {
        generateHtml();
    });

    $(document).on('click', '#educations-container .btn-remove', function() {
        $(this).closest('.input-group').remove();
    });

    new Sortable(document.getElementById('educations-container-list'), {
        handle: '.handle-sortable', // handle's class
        animation: 150,
        ghostClass: 'bg-light-primary'
    });
JS);
?>

<h4 class="mb-10 font-weight-bold text-dark">
    <?= $tabData['title'] ?>
</h4>

<?php $form = ActiveForm::begin() ?>
    <div class="row">
        <div class="col-md-6">
            <?= BootstrapSelect::widget([
                'form' => $form,
                'model' => $model,
                'attribute' => 'first_enrollment',
                'data' => ArrayHelper::range(date('Y'), date('Y') - 10),
                'prompt' => false
            ]) ?><div class="text-muted small" style="margin-top: -20px;">Year</div>
        </div>
        <div class="col-md-6">
            <?= BootstrapSelect::widget([
                'form' => $form,
                'model' => $model,
                'attribute' => 'expected_graduation',
                'data' => ArrayHelper::range(date('Y') + 10, date('Y') ),
                'prompt' => false
            ]) ?><div class="text-muted small" style="margin-top: -20px;">Year</div>
        </div>
    </div>
    

    <p class="lead font-weight-bold text-uppercase text-muted mt-10">Educational Background</p>
    <div id="educations-container">
        <div class="input-group">
            <div class="input-group-prepend">
                <button class="btn btn-secondary" type="button">
                    <i class="fas fa-book"></i>
                </button>
            </div>
            <input type="text" name="school_name" class="form-control" placeholder="Enter School Name">
            <input type="text" name="course" class="form-control" placeholder="Enter Course">
            <input type="text" name="year_level" class="form-control" placeholder="Enter Year Level">
            <input type="text" name="school_year" class="form-control" placeholder="Enter School Year">
            <div class="input-group-append">
                <button class="btn btn-success btn-add btn-icon" type="button">
                    <i class="fa fa-plus-circle"></i>
                </button>
            </div>
        </div>
        <div class="list-container mt-2"  id="educations-container-list">
            <?= Html::foreach($model->educations, function($educations, $index) {
                $year_level = $educations['year_level'] ?? '';
                $course = $educations['course'] ?? '';
                $school_name = $educations['school_name'] ?? '';
                $school_year = $educations['school_year'] ?? '';
                return <<< HTML
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <button class="btn btn-secondary handle-sortable" type="button">
                                <i class="fas fa-arrows-alt"></i>
                            </button>
                        </div>
                        <input placeholder="Enter School Name" type="text" class="form-control" name="Scholarship[educations][{$index}][school_name]" value="{$school_name}">
                        <input placeholder="Enter Course" type="text" class="form-control" name="Scholarship[educations][{$index}][course]" value="{$course}">
                        <input placeholder="Enter Year Level" type="text" class="form-control" name="Scholarship[educations][{$index}][year_level]" value="{$year_level}">
                        <input placeholder="Enter School Year" type="text" class="form-control" name="Scholarship[educations][{$index}][school_year]" value="{$school_year}">
                        <div class="input-group-append">
                            <button class="btn btn-danger btn-icon btn-remove" type="button">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                HTML;
            }) ?>
        </div>
    </div>

    <div class="form-group mt-10">
        <?= Html::a('Previous', ['scholarship/' . App::actionID(), 'token' => $model->token], [
            'class' => 'btn btn-light-primary font-weight-bold btn-lg'
        ]) ?>
        <?= Html::submitButton('Next', [
            'class' => 'btn btn-success btn-lg font-weight-bold'
        ]) ?>
    </div>
<?php ActiveForm::end(); ?>


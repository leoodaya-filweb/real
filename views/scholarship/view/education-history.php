<?php

use app\helpers\App;
use app\helpers\Html;

$this->addJsFile('sortable/Sortable.min');
$this->registerJs(<<< JS
    $('#tbl-educations').DataTable({
        pageLength: 5,
        order: [],
        // "ordering": false
    });

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

    $('.btn-save-education').on('click', function() {
        KTApp.block('#modal-edit-educations .modal-body', {
            overlayColor: '#000000',
            message: 'Please wait...',
            state: 'primary'
        });

        $.ajax({
            url: app.baseUrl + 'scholarship/save-education?token={$model->token}',
            method: 'post',
            data: $('#form-education').serialize(),
            dataType: 'json',
            success: function(s) {
                if (s.status == 'success') {

                    $('#tbl-educations').DataTable().clear().destroy();
                    $('#tbl-educations tbody').html(s.educations);
                    $('#tbl-educations').DataTable({
                        pageLength: 5,
                        order: [],
                    });

                    $('span.current_year_level').html(s.model.year_level + ' Year');
                    $('span.course').html(s.model.course);
                    $('span.school_name').html(s.model.school_name);
                    $('span.school_year').html(s.model.school_year);

                    Swal.fire('Success', s.message, 'success');

                    $('#modal-edit-educations').modal('hide');
                }
                else {
                    Swal.fire('Error', s.errorSummary, 'error');
                }

                KTApp.unblock('#modal-edit-educations .modal-body');
            },
            error: function(e) {
                Swal.fire('Error', e.responseText, 'error');
                KTApp.unblock('#modal-edit-educations .modal-body');
            }
        })
    })
JS);
?>

<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
    'title' => 'Education History',
    'toolbar' => Html::tag('div', Html::a('<i class="fa fa-edit"></i> Edit', '#modal-edit-educations', [
        'class' => 'btn btn-light-primary font-weight-bold btn-sm',
        'data-toggle' => 'modal'
    ]), ['class' => 'card-toolbar']),
    'stretch' => true
]) ?>

    <table class="table table-bordered" id="tbl-educations">
        <thead>
            <th>school</th>
            <th>course</th>
            <th>year</th>
        </thead>
        <tbody>
            <?= App::foreach($model->educations, 
                fn($education) => $this->render('_education', ['education' => $education])
            ) ?>
        </tbody>
    </table>
<?php $this->endContent() ?>

<div class="modal fade" id="modal-edit-educations" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Education History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
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

                    <form id="form-education">
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
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success font-weight-bolder btn-save-education">
                    Save
                </button>
                <button type="button" class="btn btn-light-primary font-weight-bolder" data-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
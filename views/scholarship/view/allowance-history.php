<?php

use app\helpers\App;
use app\helpers\Html;
use app\widgets\ActiveForm;

$this->registerJs(<<< JS
    $(document).on('click', '.btn-remove-allowance', function() {
        var self = this;
        Swal.fire({
            title: "Are you sure?",
            text: "You won\"t be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            reverseButtons: true
        }).then(function(result) {
            if (result.value) {

                KTApp.block('body', {
                    overlayColor: '#000000',
                    state: 'warning',
                    message: 'Please wait...'
                });
                $.ajax({
                    url: $(self).data('delete-url'),
                    method: 'post',
                    dataType: 'json',
                    success: function(s) {
                        if(s.status == 'success') {
                            $('#tbl-allowances').DataTable({
                                destroy: true,
                                pageLength: 3,
                                order: [[0, 'desc']]
                            }).row($(self).closest('tr')).remove().draw();
                            $('.totalAllowance').html(s.totalAllowance);
                            Swal.fire({
                                icon: "success",
                                title: "Deleted",
                                text: "Allowance has been deleted.",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                        else {
                            Swal.fire('Error', s.errors, 'error');
                        }
                        KTApp.unblock('body');
                    },
                    error: function(e) {
                        Swal.fire('Error', e.responseText, 'error');
                        KTApp.unblock('body');
                    },
                })
            }
        });
    });
    $('#tbl-allowances').DataTable({
        pageLength: 5,
        order: [],
        // "ordering": false
    });

    $('.btn-add-allowance').on('click', function(e) {
        e.preventDefault();
        $('#modal-allowance .modal-title').html('Add Allowance');

        KTApp.block('#tbl-allowances', {
            overlayColor: '#000000',
            message: 'Please wait...',
            state: 'primary'
        });

        $.ajax({
            url: app.baseUrl + 'allowance/create',
            data: {scholarship_id: '{$model->id}'},
            method: 'get',
            dataType: 'json',
            success: (s) => {
                $('#modal-allowance .modal-body').html(s.form);
                $('#modal-allowance').modal('show');
                KTApp.unblock('#tbl-allowances');
            },
            error: (e) => {
                Swal.fire('Error', e.responseText, 'error');
                KTApp.unblock('#tbl-allowances');
            }
        });
    });

    $(document).on('beforeSubmit', 'form#allowance-ajax', function(e) {
        e.preventDefault();
        let form = $(this);
        KTApp.block('#modal-allowance .modal-body', {
            overlayColor: '#000000',
            state: 'warning',
            message: 'Saving...',
        });
 
        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            dataType: 'json',
            data: form.serialize(),
            success: function(s) {
                if(s.status == 'success') {
                    $('#tbl-allowances').DataTable().clear().destroy();
                    $('#tbl-allowances tbody').html(s.allowances);
                    $('#tbl-allowances').DataTable({
                        pageLength: 5,
                        order: [],
                    });

                    $('.totalAllowance').html(s.totalAllowance);

                    $('#modal-allowance').modal('hide');
                }
                else {
                    Swal.fire("Error", s.errorSummary, "error");
                }
                KTApp.unblock('#modal-allowance .modal-body');
            },
            error: function(e) {
                Swal.fire("Error", e.responseText, "error");
                KTApp.unblock('#modal-allowance .modal-body');
            }
        });
        return false;
    });

    $(document).on('click', '.semester', function(e) {
        e.preventDefault();
        $('#modal-allowance .modal-title').html('Allowance Detail');
        KTApp.block('#tbl-allowances', {
            overlayColor: '#000000',
            message: 'Please wait...',
            state: 'primary'
        });
        const id = $(this).data('id');

        $.ajax({
            url: app.baseUrl + 'allowance/view',
            data: {id},
            method: 'get',
            dataType: 'json',
            success: (s) => {
                if (s.status == 'success') {
                    $('#modal-allowance .modal-body').html(s.detailView);
                    $('#modal-allowance .modal-body').append('<button class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>');
                    $('#modal-allowance').modal('show');
                }
                else {
                    Swal.fire('Error', s.errorSummary, 'error');
                }
                KTApp.unblock('#tbl-allowances');
            },
            error: (e) => {
                Swal.fire('Error', e.responseText, 'error');
                KTApp.unblock('#tbl-allowances');
            }
        });
    })
JS);
?>

<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
    'title' => 'Allowance History',
    'toolbar' => Html::tag('div', Html::a('<i class="fa fa-plus-circle"></i> Add', '#', [
        'class' => 'btn btn-light-primary font-weight-bold btn-sm btn-add-allowance',
        'data-toggle' => 'modal'
    ]), ['class' => 'card-toolbar']),
    'stretch' => true
]) ?>
    <table class="table table-bordered" id="tbl-allowances">
        <thead>
            <th>semester</th>
            <th>amount</th>
            <th>date</th>
            <th>action</th>
        </thead>
        <tbody>
            <?= App::foreach($model->allowances, fn ($allowance) => $this->render('_allowance', [
                'allowance' => $allowance
            ])) ?>
        </tbody>
    </table>
<?php $this->endContent() ?>

<div class="modal fade" id="modal-allowance" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
              
            </div>
        </div>
    </div>
</div>
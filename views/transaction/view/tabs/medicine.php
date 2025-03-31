<?php

use app\helpers\Html;

$this->registerJs(<<< JS
    $(document).on('click', '.btn-edit-medicine', function() {
        KTApp.block('.medicines-container', {
            state: 'warning', // a bootstrap color
            message: 'Loading Form...',
        });

        let id = $(this).data('id');
        $.ajax({
            url: app.baseUrl + 'medicine/update',
            data: {id: id},
            dataType: 'json',
            method: 'get',
            success: function(s) {
                if(s.status == 'success') {
                    $('#modal-medicine .modal-body').html(s.form);
                    $('#modal-medicine .modal-title').html('Edit Medicine');
                    $('#modal-medicine').modal('show');
                }
                else {
                    Swal.fire('Error', s.error, 'error');
                }
                KTApp.unblock('.medicines-container');
            },
            error: function(e) {
                Swal.fire('Error', e.responseText, 'error');
                KTApp.unblock('.medicines-container');
            }
        });
    });

    $(document).on('click', '.btn-delete-medicine', function() {
        let self = this,
            id = $(self).data('id');
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
                KTApp.block('.medicines-container', {
                    state: 'warning', // a bootstrap color
                    message: 'Loading...',
                });
                $.ajax({
                    url: app.baseUrl + 'medicine/delete/' + id,
                    data: {id: id},
                    method: 'post',
                    dataType: 'json',
                    success: function(s) {
                        if(s.status == 'success') {
                            $(self).closest('tr').remove();
                            $.ajax({
                                url: app.baseUrl + 'transaction/load-medicines',
                                data: {token: '{$model->token}'},
                                dataType: 'json',
                                success: function(s) {
                                    
                                    KTApp.unblock('.medicines-container');
                                    
                                    Swal.fire("Removed", "Medicine Deleted", "success");
                                },
                                error: function(e) {
                                    Swal.fire('Error', e.responseText, 'error');
                                    KTApp.unblock('.medicines-container');
                                }
                            });
                        }
                        else {
                            Swal.fire('Error', s.error, 'error');
                            KTApp.unblock('.medicines-container');
                        }
                    },
                    error: function(e) {
                        Swal.fire('Error', e.responseText, 'error');
                        KTApp.unblock('.medicines-container');
                    }
                });
            } 
        });
    });

    $('.btn-add-medicine').click(function() {

        KTApp.block('.medicines-container', {
            state: 'warning', // a bootstrap color
            message: 'Loading Form...',
        });

        let id = $(this).data('id');
        $.ajax({
            url: app.baseUrl + 'medicine/create',
            data: {transaction_id: {$model->id}},
            dataType: 'json',
            method: 'get',
            success: function(s) {
                if(s.status == 'success') {
                    $('#modal-medicine .modal-body').html(s.form);
                    
                    $('#modal-medicine .modal-title').html('Add Medicine');
                    $('#modal-medicine').modal('show');
                }
                else {
                    Swal.fire('Error', s.error, 'error');
                }
                KTApp.unblock('.medicines-container');
            },
            error: function(e) {
                Swal.fire('Error', e.responseText, 'error');
                KTApp.unblock('.medicines-container');
            }
        });
    });
JS);
?>

<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
    'title' => "Medicines",
    'toolbar' => <<< HTML
        <div class="card-toolbar">
            <button type="button" class="btn btn-light-primary btn-sm font-weight-bolder btn-add-medicine">
                Add Medicine
            </button>
        </div>
    HTML
]); ?>
   <?php if (($medicines = $model->medicines) != null): ?>
        <div class="medicines-container">
           
            <?= $this->render('/transaction/_medicines', [
                'model' => $model
            ]) ?>
        </div>
    <?php endif ?>
<?php $this->endContent(); ?>



<div class="modal fade" id="modal-medicine" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Medicine</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                
            </div>
        </div>
    </div>
</div>


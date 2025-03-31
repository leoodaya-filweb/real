<?php

$this->registerJs(<<< JS
    let changeRecordStatus = function(el) {
        is_checked = $(el).is(':checked');
        $.ajax({
            url: $(el).data('link'),
            data: {
                id: $(el).data('model_id'),
                record_status: (is_checked)? 1: 0
            },
            dataType: 'json',
            method: 'post',
            success: (s => {
                if (s.status == 'success') {
                    toastr.success("Record status changed.");
                }
                else {
                    toastr.error(s.errorSummary);
                    $(el).prop('checked', is_checked? false: true);
                }
                if ($(el).prop('checked')) {
                    $(el).closest('span').removeClass('switch-danger-custom');
                    $(el).closest('span').addClass('switch-success-custom');
                }
                else {
                    $(el).closest('span').removeClass('switch-success-custom');
                    $(el).closest('span').addClass('switch-danger-custom');
                }
            }),
            error: (e => { 
                $(el).prop('checked', is_checked? false: true);
            })
        });
    }
    $('.input-switcher').on('change', function() {
        let self = this,
            is_checked = $(self).is(':checked'),
            withConfirmation = $(self).data('with-confirmation');

        if(withConfirmation) {
            Swal.fire({
                title: "Change status?",
                text: "Please confirm your action.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Confirm"
            }).then(function(result) {
                if (result.value) {
                   changeRecordStatus(self);
                }
                else {
                    $(self).prop('checked', is_checked? false: true);
                }
            });
        }
        else {
            changeRecordStatus(self);
        }
    });
JS);

$this->registerCss(<<< CSS
    .switch.switch-outline.switch-danger-custom input:empty ~ span:before {
        border: 2px solid #f64e60;
    }

    .switch.switch-outline.switch-danger-custom input:empty ~ span:after {
        background-color: #f64e60;
    }
CSS);
?>
<span class="switch switch-outline switch-icon switch-sm switch-success <?= ($checked) ? '': 'switch-danger-custom' ?>" data-widget_id="<?= $id ?>">
	<label>
		<input data-link="<?= $data_link ?>"
			data-model_id="<?= $model->id ?>" 
            class="input-switcher"
            data-with-confirmation="<?= $withConfirmation ? 'true': 'false' ?>"
			type="checkbox" 
			name="" 
			<?= ($checked) ? 'checked': '' ?>>
		<span></span>
	</label>
</span>
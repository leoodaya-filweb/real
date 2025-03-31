yii.confirm = function(message, okCallback, cancelCallback) {

    Swal.fire({
        title: message,
        text: "Please confirm your action.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Confirm"
    }).then(function(result) {
        if (result.value) {
            okCallback.call()
            Swal.fire(
                "Processing...",
                'Please wait!',
                "success"
            )
        }
    });
};

toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": true,
    "positionClass": "toast-bottom-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "3000",
    "timeOut": "3000",
    "extendedTimeOut": "1000"
};



$(document).ready(function() {
    $('form').submit(function() {
        KTApp.block('body', {
            overlayColor: '#000000',
            message: 'Please wait...',
            state: 'primary'
        });
        setTimeout(function() {
            KTApp.unblockPage();
        }, 2000);
    });
    $('li.menu-item-active').parents('li').addClass('menu-item-here menu-item-open');

    $('.kt-selectpicker').selectpicker();

    $('input[maxlength]').maxlength({
        warningClass: "label label-info label-rounded label-inline",
        limitReachedClass: "label label-success label-rounded label-inline"
    });
    autosize($('textarea.autosize'));

    $('input[datepicker="true"]').datepicker({
        rtl: KTUtil.isRTL(),
        todayHighlight: true,
        orientation: "bottom left",
        templates: {
            leftArrow: '<i class="la la-angle-right"></i>',
            rightArrow: '<i class="la la-angle-left"></i>'
        }
    });

    $('input[datetimepicker="true"]').datetimepicker();

    new ClipboardJS('[data-clipboard=true]').on('success', function(e) {
        e.clearSelection();
        toastr.options.positionClass = 'toast-top-left';
        toastr.info("Copy to clipboard");
    });
    $('[data-toggle="tooltip"]').tooltip()
});
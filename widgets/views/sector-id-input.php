<?php

use yii\helpers\Url;

$sector_id_checker_url = Url::to(['find-by-selector-id']);

$this->registerJs(<<<JS

var timeoutId;

$("body").on("input", "#database-sector_id", function(event){
    var dis = $(this);
    var inputId = $(this).attr('id');
    var sector_id = $(this).val();
    var form_id = $(this).closest('form') ? "#" + $(this).closest('form').attr('id') : false;

    if(form_id === false){
        return;
    }
        
    dis.closest('.spinner-container').addClass('spinner spinner-success spinner-right')

    clearTimeout(timeoutId);

    timeoutId = setTimeout(function() {
        // Runs 1 second (1000 ms) after the last change   

        $.ajax({
            url: '{$sector_id_checker_url}',
            method: 'get',
            data: {
                priority_sector: "{$priority_sector}",
                sector_id: sector_id,
            },
            dataType: 'json',
            success: function(s) {

                if(s !== null){			
                    dis.addClass('has-existing');	
                    $(form_id).yiiActiveForm('updateAttribute', inputId, ["Sector ID already exists."]);
                }else{
                    dis.removeClass('has-existing');	
                    $(form_id).yiiActiveForm('updateAttribute', inputId, "");
                }
                        
                dis.closest('.spinner-container').removeClass('spinner spinner-success spinner-right')

            },
            error: function(e) {
                dis.closest('.spinner-container').removeClass('spinner spinner-success spinner-right')
            }
        })

    }, 1000);

})


$("#database-form").on('beforeSubmit', function(e){
    if ($(this).find('.has-existing').length > 0) {
        $(this).yiiActiveForm('updateAttribute', "database-sector_id", ["Sector ID already exists."]);
        $('html,body').animate({ scrollTop: 0 }, 'fast');
        return false;
    }else{
        $(this).yiiActiveForm('updateAttribute', "database-sector_id", "");
        return true;
    }
})

JS);

$this->registerCss(<<<CSS

.spinner.spinner-right:before {
    top: 45px;
    /* right: 3rem; */
}

CSS);

?>

<div class="spinner-container"> <!-- spinner spinner-success spinner-right -->
    <?= $form->field($model, 'sector_id')->textInput([
        'maxlength' => true
    ])->label($label) ?>
</div>
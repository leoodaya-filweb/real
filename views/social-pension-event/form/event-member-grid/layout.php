<?php

use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\models\CivilStatus;
use app\models\EducationalAttainment;
use app\models\EventMember;
use app\models\Household;
use app\models\Member;
use app\models\Sex;
use app\widgets\ActiveForm;
use app\widgets\Filter;
use yii\helpers\ArrayHelper;

$url = Url::to(['event/remove-members', 'token' => $model->token]);

$this->registerJs(<<< JS
    $('.bulk-action-label').click(function() {
        let widgetId = $('.table-responsive').attr('id');
        var checkedBoxes = $('#' + widgetId).yiiGridView('getSelectedRows');

        Swal.fire({
            title: "Are you sure?",
            text: "You are going to remove " + checkedBoxes.length + ' member(s).',
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, remove it!",
            cancelButtonText: "No, cancel!",
            reverseButtons: true
        }).then(function(result) {
            if (result.value) {
                KTApp.block('body', {
                    overlayColor: '#000',
                    state: 'warning',
                    message: 'Please wait ....'    
                })
                $.ajax({
                    url: '{$url}',
                    data: {member_ids: checkedBoxes},
                    method: 'post',
                    dataType: 'json',
                    success: function(s) {
                        if(s.status == 'success') {
                            Swal.fire({
                                icon: "success",
                                title: "Removed Successfully",
                                showConfirmButton: false,
                                timer: 3000
                            });

                            window.location.reload();
                        }
                        else {
                            Swal.fire('Error', s.error, 'error');
                        }
                        KTApp.unblock('body');
                    },
                    error: function(e) {
                        Swal.fire('Error', e.responseText, 'error');
                        KTApp.unblock('body');
                    }
                })
            } 
        });
    });
JS);
?>
<div class="d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
    <div class="d-flex align-items-center flex-wrap">
        <div class="mr-2">
            {summary}
        </div>
        <?= Html::if($dataProvider->totalCount > $searchModel->pagination,
            function() use($searchModel, $paginations) {
                return $this->render('show', [
                    'paginations' => $paginations,
                    'searchModel' => $searchModel,
                ]);
            }
        ) ?>
        &nbsp;
        <button class="bulk-action-label btn btn-outline-secondary btn-sm" style="display: none;">
            Remove
        </button>
    </div>
    <div class="">
        <form action="<?= Url::current() ?>" method="get">
            <input type="hidden" name="tab" value="create-list">
            <input type="hidden" name="token" value="<?= $model->token ?>">
            <?= $searchModel->getAutocompleteInput($model) ?>
        </form>
    </div>
</div>
<div class="my-2">
    {items}
</div>
<div class="d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
    <div class="d-flex align-items-center flex-wrap">
        <div class="mr-2">
            {summary}
        </div>
    </div>
    <div class="">
        {pager}
    </div>
</div>

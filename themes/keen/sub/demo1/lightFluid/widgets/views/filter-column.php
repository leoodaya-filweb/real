<?php

use app\helpers\App;
use app\models\UserMeta;
use app\helpers\Html;
use yii\helpers\Inflector;

$model = new UserMeta();
$js = <<< JS
    let filter = function(form, success) {
        KTApp.blockPage();
        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: form.serialize(),
            dataType: 'json',
            success: success,
            error: function(e) {
                toastr.error(e.responseText);
                KTApp.unblockPage();
            }
        });
    }

    $('.check-all-filter').on('change', function() {
        let input = $(this),
            is_checked = input.is(':checked'),
            inputs = input.parents('.dropdown-menu').find('input._filter_column_checkbox'),
            form = input.closest('form'),
            th = $('th.table-th'),
            td = $('td.table-td');

        if (is_checked) {
            inputs.prop('checked', true);
        }
        else {
            inputs.prop('checked', false);
        }

        filter(form, function(s) {
            if(s.status == 'success') {
                if(is_checked) {
                    th.show();
                    td.show();
                }
                else {
                    th.hide();
                    td.hide();
                }
            }
            else {
                toastr.error(s.error);
            }
            KTApp.unblockPage();
        });
        
    });

    $('._filter_column_checkbox').on('change', function() {

        let input = $(this),
            key = input.data('key'),
            th = $('th[data-key="'+ key +'"]'),
            td = $('td[data-key="'+ key +'"]'),
            is_checked = input.is(':checked'),
            form = input.closest('form');

        filter(form, function(s) {
            if(s.status == 'success') {
                if(is_checked) {
                    th.show();
                    td.show();
                }
                else {
                    th.hide();
                    td.hide();
                }
            }
            else {
                toastr.error(s.error);
            }
            KTApp.unblockPage();
        });
        
    });


JS;
$this->registerWidgetJs($widgetFunction, $js);


?>
<div data-widget_id="<?= $id ?>" class="dropdown dropdown-inline filter-column-container" data-toggle="tooltip" title="" data-placement="top" data-original-title="" style="<?= $style ?>"> 
    <div class="d-flex align-items-center">
        <?php if (!$searchModelOnly): ?>
            <a href="#!" class="btn btn-fixed-height btn-bg-white btn-text-dark-50 btn-hover-text-primary btn-icon-primary font-weight-bolder font-size-sm  mr-3 btn-sm _filter_columns"  aria-haspopup="true" aria-expanded="false" style="border: 1px solid #ccc;" data-toggle="dropdown">
                <span class="svg-icon svg-icon-md">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24"></rect>
                            <path d="M5,8.6862915 L5,5 L8.6862915,5 L11.5857864,2.10050506 L14.4852814,5 L19,5 L19,9.51471863 L21.4852814,12 L19,14.4852814 L19,19 L14.4852814,19 L11.5857864,21.8994949 L8.6862915,19 L5,19 L5,15.3137085 L1.6862915,12 L5,8.6862915 Z M12,15 C13.6568542,15 15,13.6568542 15,12 C15,10.3431458 13.6568542,9 12,9 C10.3431458,9 9,10.3431458 9,12 C9,13.6568542 10.3431458,15 12,15 Z" fill="#000000"></path>
                        </g>
                    </svg><!--end::Svg Icon-->
                </span>
                <?= $title ?>
            </a>
            <div data-widget_id="<?= $id ?>" class="dropdown-menu dropdown-menu-sm dropdown-menu-right p-0 m-0" style="">
                <!--begin::Navigation-->
                <?= Html::beginForm(['user-meta/filter'], 'post',  [
                    'style' => 'max-height: 56vh; overflow: auto;',
                    'class' => "app-scroll"
                ]); ?>
                    <?= Html::activeInput('hidden', $model, 'table_name', [
                        'value' => $customTblname?$customTblname:App::tableName($searchModel, false)
                    ]) ?>
                    
                    
                    <?php 
                    //echo $customTblname;
                   // echo  App::tableName($searchModel, false); 
                    ?>
                    
                    <ul class="navi navi-hover" style="padding: 10px;">
                        <li class="navi-item"> 
                            <div class="checkbox-list ">
                                <label class="checkbox ">
                                    <input type="checkbox" class="check-all-filter">
                                    <span></span>
                                    CHECK ALL
                                </label>
                            </div>
                            <hr>
                        </li>
                        <li class="navi-item">
                            <div class="checkbox-list">
                                <?= Html::foreach($searchModel->tableColumns, function($value, $key) use ($model, $filterColumns) {
                                    return '<label class="checkbox">
                                        '. Html::activeInput('checkbox', $model, 'columns[]', [
                                            'value' => $key,
                                            'class' => '_filter_column_checkbox',
                                            'data-key' => $key,
                                            'checked' => in_array($key,  $filterColumns)
                                        ]) .'
                                        <span></span>
                                        '. Inflector::humanize(strtoupper($key)) .'
                                    </label>';
                                }) ?>
                            </div>
                        </li>
                    </ul>
                <?= Html::endForm(); ?> 
                <!--end::Navigation-->
            </div>
        <?php endif ?>
        <?= App::if($searchModel, <<< HTML
            <a href="#!" class="btn btn-fixed-height btn-bg-white btn-text-dark-50 btn-hover-text-primary btn-icon-primary font-weight-bolder font-size-sm mr-3 btn-sm"  style="border: 1px solid #ccc;" id="kt_quick_panel_toggle">
                <span class="svg-icon svg-icon-md">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24"/>
                            <path d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                            <path d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z" fill="#000000" fill-rule="nonzero"/>
                        </g>
                    </svg>
                </span>
                 Advanced Search
            </a>
        HTML) ?>
    </div>
</div>
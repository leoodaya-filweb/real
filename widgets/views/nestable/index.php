<?php

use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;

$js = <<< JS
    let index = 0;
    $(document).on('click', '.btn-remove-menu', function() {
        var confirm_dialog = confirm('Are you sure?');
        if (confirm_dialog) {
            $(this).closest('li').remove();
        }
    })

    $('#add-main-navigation-{$id}').on('click', function() {
        index++;
        var html = '';
        html += '<li class="dd-item dd3-item" data-id="'+ (index) +'-new">';
            html += '<div class="dd-handle dd3-handle"> <i class="flaticon-squares"></i></div>';
            html += '<div class="dd3-content">';
                html += '<div class="row">';
                    html += '<div class="col-md-3">';
                        html += '<input data-id="label" type="text" class="form-control"  placeholder="Label" required>';
                    html += '</div>';
                    html += '<div class="col-md-3">';
                        html += '<input list="link-list-{$id}" data-id="link" type="text" class="form-control"  placeholder="Link" required>';
                    html += '</div>';
                    html += '<div class="col-md-3">';
                        html += '<textarea  data-id="icon" type="text" class="form-control"  placeholder="Icon" rows="1" required></textarea>';
                    html += '</div>';
                    html += '<div class="col-md-2">';
                       html += '<div class="checkbox-list">';
                           html += '<label class="checkbox">';
                                html += '<input class="checkbox" data-id="new_tab" type="checkbox"value="1">';
                                    html += '<span></span>New Tab';
                            html += '</label>';
                            html += '<label class="checkbox">';
                                html += '<input class="checkbox" data-id="group_menu" type="checkbox"value="1">';
                                    html += '<span></span>Group Menu';
                            html += '</label>';
                        html += '</div>';
                    html += '</div>';
                    html += '<span  style="position: absolute; right: 5px;">';
                        html += '<a href="#!" class="btn btn-danger btn-sm btn-icon mr-2 btn-remove-menu">';
                            html += '<i class="fa fa-trash"></i>';
                        html += '</a>';
                    html += '</span>';
                html += '</div>';
            html += '</div>';
        html += '</li>';
        $('#ol-dd-list-{$id}').prepend(html);
        $('#dd-{$id}').trigger('change');
        // initNestable();
    })

    var initNestable = function() {
        if ($('#nestable-menu-{$id}').length) {
            $('#nestable-menu-{$id}').on('click', function(e) {
                var target = $(e.target),
                    action = target.data('action');
                if (action === 'expand-all') {
                    $('#dd-{$id}').nestable('expandAll');
                }
                if (action === 'collapse-all') {
                    $('#dd-{$id}').nestable('collapseAll');
                }
            });
            $('#dd-{$id}').nestable({maxDepth: 4, default_name: '{$defaultName}'});
            $('#dd-{$id}').nestable('createName')

            $('#dd-{$id}').on('change', function() {
                $(this).nestable('createName')
            });
        }
    }
    initNestable();
JS;
$this->registerWidgetJs($widgetFunction, $js);

$this->registerCss(<<< CSS
    #nestable-menu-{$id} {margin-top: 0; padding-left: 0;}
CSS);
?>
<datalist id="link-list-<?= $id ?>">
    <?= Html::foreach($controller_actions, function($actions, $controller) {
        return Html::foreach($actions, function($action) use ($controller) {
            return '<option value="'. str_replace(App::baseUrl(), '', Url::to(["{$controller}/{$action}"])) .'"> </option>';
        });
    }) ?>
</datalist>
<div class="row">
    <div class="col-md-12">
        <menu id="nestable-menu-<?= $id ?>" class="btn btn-group">
            <a href="#!" class="btn btn-secondary btn-linkedin btn-sm" id="add-main-navigation-<?= $id ?>">
                Add Menu
            </a>
            <button class="btn btn-outline-secondary btn-sm" type="button" data-action="collapse-all">
                <i class="fas fa-compress"></i> Collapse
            </button>
            <button class="btn btn-outline-secondary btn-sm" type="button" data-action="expand-all">
                <i class="fas fa-expand"></i> Expand
            </button>
        </menu>
        <div class="dd" id="dd-<?= $id ?>">
            <ol class="dd-list" id="ol-dd-list-<?= $id ?>">
                <?= $this->render('_navigation', [
                    'data_id' => [],
                    'navigations' => $navigations,
                    'id' => $id,
                ]) ?>
            </ol>
        </div>
    </div>
</div>
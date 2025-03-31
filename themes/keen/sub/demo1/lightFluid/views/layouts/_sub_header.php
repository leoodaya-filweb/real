<?php

use app\helpers\Html;
use app\widgets\AnchorBack;
use app\widgets\Breadcrumbs;
?>

<div class="container-fluid">
    <div class=" d-flex align-items-center justify-content-between my-7" style="padding-bottom: 18px;border-bottom: solid 1px #e1e1e1;">
        <div class="d-flex align-items-center">
            <div>
                <?= AnchorBack::widget([
                    'title' => '<i class="fa fa-angle-left"></i>',
                    'options' => [
                        'class' => 'btn btn-secondary btn-sm',
                        'data-original-title' => 'Go back',
                        'data-toggle' => "tooltip",
                        'data-theme' => "dark",
                    ]
                ]) ?>
            </div>
            <h5 class="font-weight-bolder mr-5 page-title ml-2 mt-2">
                <?= $this->title ?>
            </h5>
        </div>
        <div>
            <div class="d-flex align-items-center justify-content-between">
                <?= $this->render('_header_menu_wrapper') ?>
                <?= $this->render('_toolbar') ?>
            </div>
        </div>
    </div>

    <div class="d-flex mb-5 align-items-center justify-content-between" >
        <div>
            <?= Breadcrumbs::widget([
                'homeLink' => [
                    'label' => 'Dashboard',
                    'url' => ['dashboard/index']
                ],
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                'options' => [
                    'class' => 'breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm',
                ],
                'itemTemplate' => "<li class='breadcrumb-item'>{link}</li>\n",
                'activeItemTemplate' => "<li class=\"breadcrumb-item\">{link}</li>\n",
                'anchorClass' => 'text-muted'
            ]); ?>
        </div>
        <div class="d-flex">
            <?= Html::exportButton($this->params) ?>
            <?= Html::createButton($this->params) ?>
            <div><?= $this->params['headerButtons'] ?? '' ?></div>
        </div>
    </div>
</div>

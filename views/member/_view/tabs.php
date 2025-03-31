<?php

use app\helpers\Url;
use app\helpers\Html;
use app\widgets\AppIcon;
?>
<div class="card card-custom gutter-b card-stretch">
    <div class="card-body pt-8">
        <!-- <div class="d-flex justify-content-end">
            <div class="dropdown dropdown-inline">
                <a href="#" class="btn btn-icon-primary btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="svg-icon svg-icon-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1">
                                <rect x="14" y="9" width="6" height="6" rx="3" fill="black"></rect>
                                <rect x="3" y="9" width="6" height="6" rx="3" fill="black" fill-opacity="0.7"></rect>
                            </g>
                        </svg>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                    <ul class="navi navi-hover py-5">
                        <li class="navi-item">
                            <a href="#" class="navi-link" data-clipboard="true" data-clipboard-text="<?= $model->qr_id ?>">
                                <i class="far fa-copy"></i>
                                <span class="navi-text ml-2">
                                    Copy QR Code
                                </span>
                            </a>
                        </li>
                      
                        <li class="navi-separator my-3"></li>
                        <li class="navi-item">
                            <a href="<?= $model->downloadQrCodeUrl ?>" class="navi-link">
                                <i class="fas fa-download"></i>
                                <span class="navi-text ml-2">
                                    Download QR
                                </span>
                            </a>
                        </li>
                        <li class="navi-separator my-3"></li>

                        <li class="navi-item">
                            <a href="<?= $model->createTransactionLink ?>" class="navi-link">
                                <i class="fas fa-plus-square"></i>
                                
                                <span class="navi-text ml-2">
                                    Add Transaction
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div> -->
        <div class="d-flex align-items-center">
            <div class="symbol symbol-60 symbol-xxl-90 mr-5 align-self-start align-self-xxl-center">
                <div class="symbol-label" style="background-image:url('<?= Url::image($model->photo, ['w' => 90]) ?>')"></div>
            </div>
            <div>
                <a href="#" class="font-weight-bolder font-size-h5 text-dark-75 text-hover-primary">
                    <?= $model->fullname ?>
                </a>
                <?= $model->headBadge ?>
                <div class="mt-2">
                    <a title="Copy QR Code" data-toggle="tooltip" href="#" class="btn btn-sm btn-default btn-hover-light-primary mr-1" data-clipboard="true" data-clipboard-text="<?= $model->qr_id ?>">
                        <i class="far fa-copy"></i>
                    </a>

                    <?= Html::a('<i class="fas fa-download"></i>', $model->downloadQrCodeUrl, [
                        'title' => 'Download QR Code',
                        'data-toggle' => 'tooltip',
                        'class' => 'btn btn-sm btn-default btn-hover-light-primary mr-1',
                    ]) ?>

                    <?= Html::a('<i class="fas fa-plus-square"></i>', $model->createTransactionLink, [
                        'title' => 'Add New Transaction',
                        'data-toggle' => 'tooltip',
                        'class' => 'btn btn-sm btn-default btn-hover-light-primary mr-1',
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="navi navi-bold navi-hover navi-active navi-link-rounded mt-10">
            <?= Html::foreach($model->viewTabs, function($view_tab) use($tab, $model) {
                $url = Url::to(['member/view', 'qr_id' => $model->qr_id, 'tab' => $view_tab['slug']]);
                $class = ($tab == $view_tab['slug'])? 'active': '';
                $icon = $view_tab['icon'] != strip_tags($view_tab['icon']) ? $view_tab['icon']: AppIcon::widget(['icon' => $view_tab['icon']]);
                return <<< HTML
                    <div class="navi-item mb-2">
                        <a href="{$url}" class="navi-link py-4 {$class}">
                            <span class="navi-icon mr-2">
                                {$icon}
                            </span>
                            <span class="navi-text font-size-lg">
                                {$view_tab['title']}
                            </span>
                        </a>
                    </div>
                HTML;
            }) ?>
        </div>
    </div>
</div>
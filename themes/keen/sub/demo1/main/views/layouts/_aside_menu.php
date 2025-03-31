<?php

use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\widgets\Anchor;
use app\widgets\Menu;

$identity = App::identity();
$identity->setTheProfile();
?>
<!-- <section class="user-side-nav mt-5">
    <div class="d-flex align-items-center" style="padding: 0 15px;">
        <div class="symbol symbol-60 mr-5">
            <div id="profile-image-dropdown" class="symbol-label" 
                style="background-image:url('<?= Url::image($identity->photo, ['w' => 200]) ?>')">
            </div>
        </div>
        <div class="d-flex flex-column" style="word-break: break-all;">
            <?= Anchor::widget([
                'title' => ucwords($identity->firstname ?: $identity->username),
                'link' => ['user/my-account'],
                'text' => true,
                'options' => [
                    'class' => 'font-weight-bold font-size-h5 text-dark-75 text-hover-primary',
                ]
            ]) ?>
            
            <div class="text-muted mt-1">
                <?= Anchor::widget([
                    'title' => $identity->position ?: $identity->roleName,
                    'link' => ['user/my-account'],
                    'text' => true,
                    'options' => [
                        'class' => 'text-hover-warning text-muted',
                    ]
                ]) ?>
            </div>
        </div>
    </div>
</section> -->
<div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">
    <!--begin::Menu Container-->
    
    <div id="kt_aside_menu" class="aside-menu my-4" data-menu-vertical="1" data-menu-scroll="1" data-menu-dropdown-timeout="500">
        <!--begin::Menu Nav-->
        <?= Menu::widget([
            'viewParams' => $this->params
        ]) ?>
        <!--end::Menu Nav-->
    </div>
    <!--end::Menu Container-->
</div>
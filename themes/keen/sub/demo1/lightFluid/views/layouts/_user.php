<?php

use app\helpers\App;
use app\helpers\Url;

$identity = App::identity();

$this->registerCss(<<< CSS
    .user-name {
        color: #0B274F;
    }
    .online-badge {
        width: 10px;
        height: 10px;
        background: #1BC5BD;
        position: absolute;
        border-radius: 10px;
        right: 2px;
        bottom: 1px;
    }
    .image-container {
        position: relative;
    }
    .dark-theme:before {
        background-color: #c9c9c9 !important;
    }
    .light-theme:before {
        background-color: #c9c9c9 !important;
    }
CSS);

$this->registerJs(<<< JS
    $('.theme-switcher').change(function() {
        const isChecked = $(this).is(':checked');
        
        if (isChecked) {
            $('body').addClass('dark-theme');
            $('.span-theme').removeClass('dark-theme').addClass('light-theme');
            localStorage.setItem('theme', "dark-theme");
        }
        else {
            $('body').removeClass('dark-theme');
            $('.span-theme').removeClass('light-theme').addClass('dark-theme');
            localStorage.setItem('theme', "");
        }
    })

    const appTheme = localStorage.getItem("theme");
    if (appTheme) {
        $('.theme-switcher').prop('checked', true)
    }
JS);

$this->registerJs(<<< JS
    const appTheme = localStorage.getItem("theme");
    $('body').addClass(appTheme)
JS,\yii\web\View::POS_BEGIN);

if(Yii::$app->request->get('test')=='1'){
// echo  $identity->profile->position  ;
}

?>
<div class="topbar-item" >
    <div class="d-flex align-items-center justify-content-between " >
        
        <div id="kt_quick_user_toggle" class="d-flex">
        <div  class="image-container btn btn-icon btn-dropdown ">
            <img src="<?= Url::image(App::identity('photo'), ['w' => 40]) ?>" class="symbol symbol-circle" alt="" />
            <div class="online-badge"></div>
        </div>
        <div class="line-height-sm mx-2" style="cursor: pointer;">
            <div class="font-weight-bolder user-name ">
                <?= $identity->profile->first_name ?: $identity->username ?> 
            </div>
            <div class="text-muted"><?= ($identity->profile->position?$identity->profile->position.'<br/>('.$identity->roleName.')':$identity->roleName) ?></div>
         </div>
        </div>
        
        
        <div class="ml-5">
            <span class="switch switch-primary switch-icon">
                <label>
                    <input type="checkbox" name="select" class="theme-switcher">
                    <span class="span-theme light-theme"></span>
                </label>
            </span>
        </div>
    </div>
</div>
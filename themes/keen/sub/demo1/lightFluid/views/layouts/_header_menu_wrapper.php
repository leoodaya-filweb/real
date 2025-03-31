<?php

use app\helpers\Html;
use app\widgets\AnchorBack;
?>

<div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
    <div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">
        <?= Html::if(($searchModel = $this->params['searchModel'] ?? '') != NULL, 
            function() use($searchModel) {
                return $this->render('_header_menu_wrapper-content', [
                    'searchModel' => $searchModel,
                    'searchAction' => $searchModel->searchAction ?? ['index'],
                ]);
            }
        ) ?>
    </div>
</div>
<?php
use app\helpers\App;

$this->registerCss(<<< CSS
    .widget-left {
        margin: 0 auto !important;
        margin-bottom: 20px !important;
    }
    .topbar {
        align-items: center !important;
    }
    .btn-icon:hover {
        background-color: #ffffff;
        border: 1px solid #ccc;
    }
    
    
CSS);

?>

<div class="topbar">
    
    <!--begin::Search-->
    <?php # $this->render('_search_layout') ?>
    <!--end::Search-->
    <!--begin::Quick panel-->
    <?php # $this->render('_quick_panel') ?>
    <!--end::Quick panel-->
    <!--begin::Notifications-->
    <?= $this->render('_notifications') ?>
    <!--end::Notifications-->

    <?php // $this->render('_settings') ?>
    <!--begin::Quick Actions-->
    <?php # $this->render('_quick_actions') ?>
    <!--end::Quick Actions-->
    <!--begin::Chat-->
    <?php
   // if(App::identity('id')==17){
    echo $this->render('_chat');
    //}
    ?>
    <!--end::Chat-->
    <!--begin::Languages-->
    <?php # $this->render('_languages') ?>
    <!--end::Languages-->
    <!--begin::User-->
    <?= $this->render('_user') ?>
    <!--end::User-->
</div>
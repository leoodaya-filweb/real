<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\Notification;

$this->registerCss(<<< CSS
    .notification-badge-container {
        position: absolute;
        top: 10px;
    }
    .notification-badge-container:hover {
        cursor: pointer;
    }
CSS);

$this->registerJs(<<< JS
    var loadNotification = function(row = false) {
        
        $.ajax({
            url: app.baseUrl + 'notification/load',
            dataType: 'json',
            method: 'get',
            success: function(s) {
                $('.notification-badge-container').html(s.badge);

                if(row) {
                    $(document).find('.notification-rows-container').html(s.rows);
                }
                KTApp.unblock('.notification-rows-container');
            },
            error: function(e) {
                console.log(e);
                KTApp.unblock('.notification-rows-container');
            }
        });
    }

    setInterval(loadNotification, 10000);

    $('.notification-badge-menu').on('click', function() {
        KTApp.block('.notification-rows-container', {
            overlayColor: '#000000',
            state: 'danger',
            message: 'Please wait...'
        });
        $(document).find('.notification-rows-container').html("Loading...");
        loadNotification(true);
    });

    $(document).on('click', '.btn-read-all', function() {
        $('.notification-badge-container').html("");
        $.ajax({
            url: app.baseUrl + 'notification/read-all',
            dataType: 'json',
            method: 'get',
            contentType: false,
            processData: false,
            cache: false,
            success: function(s) {
                $(document).find('.notification-rows-container').html("");
                KTApp.unblock('body');
            },
            error: function(e) {
                console.log(e);
                KTApp.unblock('body');
            }
        });
    });
JS);
?>

<div class="dropdown mx-5">
    <div class="topbar-item notification-badge-menu" data-toggle="dropdown" data-offset="10px,0px">
        <div class="btn btn-icon btn-dropdown pulse pulse-primary">
            <span class="svg-icon svg-icon-xl svg-icon-primary">
                <svg width="27" height="28" viewBox="0 0 27 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M26.7083 7.04167V14.7917H24.125V7.04167M24.125 17.375H26.7083V19.9583H24.125M12.5 0.583336C11.8149 0.583336 11.1578 0.855508 10.6733 1.33998C10.1888 1.82445 9.91667 2.48153 9.91667 3.16667C9.9076 3.29137 9.9076 3.41656 9.91667 3.54125C6.19667 4.63917 3.45833 8.10084 3.45833 12.2083V19.9583L0.875 22.5417V23.8333H24.125V22.5417L21.5417 19.9583V12.2083C21.5417 8.10084 18.8033 4.63917 15.0833 3.54125C15.0924 3.41656 15.0924 3.29137 15.0833 3.16667C15.0833 2.48153 14.8112 1.82445 14.3267 1.33998C13.8422 0.855508 13.1851 0.583336 12.5 0.583336ZM9.91667 25.125C9.91667 25.8101 10.1888 26.4672 10.6733 26.9517C11.1578 27.4362 11.8149 27.7083 12.5 27.7083C13.1851 27.7083 13.8422 27.4362 14.3267 26.9517C14.8112 26.4672 15.0833 25.8101 15.0833 25.125H9.91667Z" fill="#0B274F"/>
                </svg>
            </span>
            <span class="pulse-ring"></span>
        </div>
        <span class="notification-badge-container" style="cursor: pointer;">
            <?= Html::if(($total = Notification::totalUnread()) > 0, function() use($total) {
                return Html::tag('label', $total, [
                    'class' => 'badge badge-danger badge-pill notification-badge'
                ]);
            }) ?>
        </span>
    </div>
    <div class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg">
        <div class="d-flex flex-column pt-12 bgi-size-cover bgi-no-repeat rounded-top" style="background-image: url(<?= App::publishedUrl('/media/misc/bg3.png') ?>)">
            <h4 class="d-flex flex-center rounded-top pb-7">
                <span class="text-white">Message Center</span>
            </h4>
        </div>
            
        <div class="scroll p-7 notification-rows-container" data-scroll="true" data-height="300" data-mobile-height="200">
            
        </div>
    </div>
</div>
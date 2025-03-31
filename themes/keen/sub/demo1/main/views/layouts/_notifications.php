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

<div class="dropdown mr-1">
    <div class="topbar-item notification-badge-menu" data-toggle="dropdown" data-offset="10px,0px">
        <div class="btn btn-icon btn-clean btn-dropdown btn-lg pulse pulse-primary">
            <span class="svg-icon svg-icon-xl svg-icon-primary">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect x="0" y="0" width="24" height="24" />
                        <path d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z" fill="#000000" opacity="0.3" />
                        <path d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z" fill="#000000" />
                    </g>
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
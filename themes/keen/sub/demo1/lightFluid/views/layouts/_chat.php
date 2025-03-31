<?php
use app\helpers\App;
use app\helpers\Html;

$this->registerCss(<<< CSS
    .notification-badge-container {
        position: absolute;
        top: 10px;
    }
    .notification-badge-container:hover {
        cursor: pointer;
    }
    
  .chat-icon  {position: relative;}
  .count-notif {
    position: absolute;
    top: 0px;
    right: 0px;
    display:none;
    line-height: 21px;
   }
    
CSS);

$this->registerJs(<<< JS


    var loadNotification = function(row = false) {
        
        $.ajax({
            url: app.baseUrl + 'community-board/default/notif',
            dataType: 'json',
            method: 'get',
            success: function(s) {
                
                $('.count-notif').html((s.total>=100?'99+':s.total));
                if(s.total>=1){
                  $('.count-notif').show();
                }else{
                  $('.count-notif').hide();  
                }

                if(row) {
                    $(document).find('.notification-rows-container').html(s.rows);
                }
               // KTApp.unblock('.notification-rows-container');
            },
            error: function(e) {
                console.log(e);
               // KTApp.unblock('.notification-rows-container');
            }
        });
    }
    
    loadNotification();

    setInterval(loadNotification, 15000);

    $('.chat-icon').on('click', function() {
        window.location.href = app.baseUrl + 'community-board';
        /*
        KTApp.block('.notification-rows-container', {
            overlayColor: '#000000',
            state: 'danger',
            message: 'Please wait...'
        });
        $(document).find('.notification-rows-container').html("Loading...");
        loadNotification(true);
        */
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



<div class="topbar-item mr-5">
    
    <div class="btn btn-icon chat-icon btn-clean btn-lg mr-1" data-toggle="modal" data-target="#kt_chat_modal">
        <span class="label label-danger count-notif"></span>
        <span class="svg-icon svg-icon-xl svg-icon-primary">
            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Chat6.svg-->
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <rect x="0" y="0" width="24" height="24" />
                    <path opacity="0.3" fill-rule="evenodd" clip-rule="evenodd" d="M14.4862 18L12.7975 21.0566C12.5304 21.54 11.922 21.7153 11.4386 21.4483C11.2977 21.3704 11.1777 21.2597 11.0887 21.1255L9.01653 18H5C3.34315 18 2 16.6569 2 15V6C2 4.34315 3.34315 3 5 3H19C20.6569 3 22 4.34315 22 6V15C22 16.6569 20.6569 18 19 18H14.4862Z" fill="black" />
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M6 7H15C15.5523 7 16 7.44772 16 8C16 8.55228 15.5523 9 15 9H6C5.44772 9 5 8.55228 5 8C5 7.44772 5.44772 7 6 7ZM6 11H11C11.5523 11 12 11.4477 12 12C12 12.5523 11.5523 13 11 13H6C5.44772 13 5 12.5523 5 12C5 11.4477 5.44772 11 6 11Z" fill="black" />
                </g>
            </svg>
            <!--end::Svg Icon-->
        </span>
    </div>
</div>
<?php

$this->registerCss(<<< CSS
    .widget-left {
        margin: 0 auto !important;
        margin-bottom: 20px !important;
    }
    .topbar {
        align-items: center !important;
    }
CSS);

$this->registerJs(<<< JS
    const doDate = () => {
        var str = "";

        var days = new Array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
        var months = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

        var now = new Date();
        var amOrPm = (now.getHours() < 12) ? "AM" : "PM";
        var hour = (now.getHours() < 12) ? now.getHours() : now.getHours() - 12;

        hour = hour < 10 ? "0" + hour: hour;

        var minutes = now.getMinutes() < 10 ? "0" + now.getMinutes(): now.getMinutes();
        var seconds = now.getSeconds() < 10 ? "0" + now.getSeconds(): now.getSeconds();

        str += "<div class='lead font-weight-bold' style='font-size: 1.4rem !important'>"+ hour +":" + minutes + ":" + seconds + " " + amOrPm +"</div>";
        str += "<div class='font-weight-bold' style='text-transform: capitalize; font-size: 1.1rem'>"+ days[now.getDay()] + ", " + now.getDate() + " " + months[now.getMonth()] + " " + now.getFullYear() +"</div>";

        document.getElementById("todaysDate").innerHTML = str;
    }

    setInterval(doDate, 1000);
JS);
?>

<div class="topbar">
    <div class="mr-5">
        <div class='text-center' style="line-height: 1.7rem;">
            <div class='font-weight-bold' style='text-transform: uppercase;'>PHILIPPINES STANDARD TIME</div>
            <div id="todaysDate">
                <div class='lead font-weight-bold' style='font-size: 1.4rem !important; filter: blur(4px);opacity: 0.5;border-radius: 2px'>03:40:54 PM</div>
                <div class='font-weight-bold' style='text-transform: capitalize; font-size: 1.1rem;filter: blur(4px);opacity: 0.5;border-radius: 2px'>Monday, 24 October 2022</div>
            </div>
        </div>
    </div>
    <!--begin::Search-->
    <?php # $this->render('_search_layout') ?>
    <!--end::Search-->
    <!--begin::Quick panel-->
    <?= $this->render('_quick_panel') ?>
    <!--end::Quick panel-->
    <!--begin::Notifications-->
    <?= $this->render('_notifications') ?>
    <!--end::Notifications-->
    <!--begin::Quick Actions-->
    <?php # $this->render('_quick_actions') ?>
    <!--end::Quick Actions-->
    <!--begin::Chat-->
    <?php #$this->render('_chat') ?>
    <!--end::Chat-->
    <!--begin::Languages-->
    <?php # $this->render('_languages') ?>
    <!--end::Languages-->
    <!--begin::User-->
    <?= $this->render('_user') ?>
    <!--end::User-->
</div>
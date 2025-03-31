<?php

use app\helpers\App;
use yii\helpers\Html;
use app\helpers\Html as AppHtml;

$this->title = 'AccessGov';

$this->registerCss(<<< CSS
    .hero {
        background-color: #0a4dc2;
        height: 700px;
    }
    .hero .col-md-7 {
        margin: auto;
    }
    .hero .logo {
        width: 200px;
    }
    .hero .header-actions a:hover {
        border-bottom: 2px solid #fff;
    }
    .hero .header-actions a {
        color: #fff;
        text-transform: uppercase;
        margin: auto;
        letter-spacing: 1px;
    }
    .hero .btn-login:hover {
        background: #fff;
        color: #0a4dc2;
    }

    .hero .btn-login {
        border: 1px solid #fff;
        padding: 10px 15px;
        border-radius: 50px;
    }

    .hero .text-container {
        padding: 9rem 7rem;
        color: #fff;
    }
    .hero .text-container h1 {
        font-size: 4rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }
    .hero .text-container p {
        line-height: 25px;
        font-size: 15px;
        font-weight: 100;
    }
    .hero .text-container a:hover {
        background: #fff;
        color: #0a4dc2;
    }

    .hero .text-container a {
        margin-top: 2rem;
        text-transform: uppercase;
        padding: 10px 25px;
        border-radius: 50px;
        color: #0a4dc2;
        background: #51f5c8;
        font-weight: 600;
        font-size: 1.3rem;
        letter-spacing: 1px;
    }
    .hero .mobile-menu {
        display: none;
    }

    .how-it-works {
        padding-top: 7rem;
        background: #fff
    }
    .how-it-works p.lead {
        text-transform: uppercase;
        color: #144395;
        font-weight: 500;
    }
    .how-it-works h2 {
        font-weight: 700;
        font-size: 2.6rem;
        color: #144393;
    }
    .how-it-works p.lead {
        margin-bottom: 0.5rem;
    }
    .how-it-works p.paragraph {
        margin-top: 2rem;
        color: #0e4cc5;
        font-size: 1.2rem;
        padding: 0 16rem;
        font-weight: 300;
    }

    .how-it-works .icon {
        margin: 5rem 0;
    }

    .how-it-works .btn-icon {
        background-color: #649bfe;
        font-size: 3rem;
    }
    .how-it-works .btn-icon i {
        color: #fff;
        font-size: 3rem;
    }
    .how-it-works .icon p {
        margin-top: 2rem;
        color: #0a4dc2;
        text-transform: capitalize;
        font-size: 1.2rem;
    }

    .sneak-peak {
        padding: 9rem 0;
        background: #f4f6f5;
        color: #0a4dc2;
    }
    .sneak-peak h2 {
        font-weight: 700;
        font-size: 2.6rem;
        color: #144393;
    }
    .sneak-peak p.lead {
        margin-bottom: 0.5rem;
    }
    .sneak-peak p.paragraph {
        font-size: 1.2rem;
        font-weight: 100;
        margin-top: 2rem;
    }


    .faq {
        padding: 9rem 0;
        background: #fff;
        color: #0a4dc2;
    }
    .faq h2 {
        font-weight: 700;
        font-size: 2.6rem;
        color: #144393;
    }
    .faq p.lead {
        margin-bottom: 0.5rem;
    }

    .faq .faq-rows {
        margin-top: 4rem;
        padding: 0 16rem;
    }

    .faq .faq-rows .item {
        margin-bottom: 2rem;
    }

    .faq .faq-rows .item .number {
        padding: 20px 0;
        background: #c3dad4;
        color: #fff;
        font-weight: 600;
        width: 70px;
    }
    .faq .faq-rows .item .title {
        padding: 20px 27px;
        background: #f4f6f5;
        width: 100%;
        text-align: left;
        font-weight: 500;
        font-size: 1.2rem;
        cursor: pointer;

        -webkit-touch-callout: none; /* iOS Safari */
        -webkit-user-select: none; /* Safari */
        -khtml-user-select: none; /* Konqueror HTML */
        -moz-user-select: none; /* Firefox */
        -ms-user-select: none; /* Internet Explorer/Edge */
        user-select: none; /* Non-prefixed version, currently
        supported by Chrome and Opera */
    }

    .faq .faq-rows .collapse-content {
        font-size: 1.1rem;
        padding: 20px 4.5rem;
        line-height: 2rem;
    }


    .help {
        padding: 9rem 0;
        background: #f4f6f5;
        color: #0a4dc2;
    }
    .help h2 {
        font-weight: 700;
        font-size: 2.6rem;
        color: #144393;
    }
    .help p.lead {
        margin-bottom: 0.5rem;
    }
    .help p.paragraph {
        font-size: 1.2rem;
        font-weight: 100;
        margin-top: 2rem;
        padding: 0 16rem;
    }

    .copyright  {
        padding: 3rem 0;
        background-color: #0a4dc2;
    }
    .copyright img {
        width: 50px;
    }
    .copyright p {
        color: #fff;
        margin-bottom: 0;
        margin-left: 1.5rem;
    }
    .copyright .text {
        margin: auto;
    }
    .copyright .icon {
        margin: auto;
    }
    .copyright .icon i {
        color: #fff;
        font-size: 2rem;
    }
    .copyright .img-container {
        margin: auto;
    }
    .carousel-control.left,
    .carousel-control.right {
        background-image: unset;
    }

    .carousel-control.left:hover {
        background-image: linear-gradient(to left,rgba(0,0,0,.1) 0,rgba(0,0,0,.0001) 100%);
    }
    .carousel-control.right:hover {
        background-image: linear-gradient(to right,rgba(0,0,0,.1) 0,rgba(0,0,0,.0001) 100%);
    }
    .carousel-indicators {
        margin: auto;
    }
    #myCarousel {
        box-shadow: rgb(0 0 0 / 30%) 0px 1px 2px 0 !important;
        border-radius: 4px;
    }
    .carousel-inner {
        border-radius: 4px;
    }


    @media only screen and (max-width: 1024px) {
        .hero .text-container {
            padding: 7rem 0rem;
        }
        /*.hero .text-container .col-md-7 {
            -webkit-box-flex: 0;
            -ms-flex: 0 0 100%;
            flex: 0 0 100%;
            max-width: 100%;
        }*/
        .hero .text-container h1 {
            font-size: 3rem;
        }
        .how-it-works p.paragraph,
        .faq .faq-rows,
        .help p.paragraph {
            padding: 0 9rem;
        }
    }

    @media (max-width: 991.98px) {
        .container, 
        .container-fluid, 
        .container-sm, 
        .container-md, 
        .container-lg, 
        .container-xl, 
        .container-xxl {
            padding: 0 30px;
        }
    }

    @media only screen and (max-width: 768px) {
        .hero .text-container h1 {
            font-size: 2rem;
        }
        .hero .header-actions {
            display: none;
        }
        .hero .mobile-menu {
            display: block;
        }
        .hero .mobile-menu i {
            color: #fff;
            font-size: 2rem;
            cursor: pointer;
        }
        .hero #mobile-menu {
            display: none;
            position: absolute;
            left: 0px;
            background: rgb(255, 255, 255);
            width: 100%;
            z-index: 9;
            top: 0;
        }
        .hero #mobile-menu ul {
            list-style: none;
        }
        .hero #mobile-menu li {
            text-transform: uppercase;
            padding: 1rem 0;
        }

        .hero .mobile-menu .fa-window-close {
            color: #0a4dc2;
            font-size: 2rem;
        }

        .hero .text-container {
            padding: 7rem 0rem;
        }

        .how-it-works p.paragraph,
        .faq .faq-rows,
        .help p.paragraph,
        .sneak-peak p.paragraph {
            padding: 0 3rem;
        }

        .sneak-peak .col-md-6 {
            -webkit-box-flex: 0;
            -ms-flex: 0 0 100%;
            flex: 0 0 100%;
            max-width: 100%;
        }

        .sneak-peak {
            text-align: center;
        }

        .sneak-peak #myCarousel {
            margin: 0 3rem;
            margin-top: 4rem;
        }
    }

    @media only screen and (max-width: 767px) {
        .hero .accessgov {
            max-height: 20rem;
        }
        .hero .img-container {
            margin: 0 auto;
            text-align: center;
            padding: 1rem;
        }
    }

    @media only screen and (max-width: 425px) {
        .hero {
            height: 900px;
        }
        .hero .text-container {
            padding: 5rem 0rem;
        }
        .how-it-works p.paragraph, .faq .faq-rows, .help p.paragraph, .sneak-peak p.paragraph {
            padding: 0 0rem;
        }
        .copyright .icon a {
            margin: 0 !important;
        }
        .hero .accessgov {
            margin-top: 5rem;
        }
    }
CSS);


$this->registerJs(<<< JS
    $('.mobile-menu i').on('click', function() {
        $('#mobile-menu').slideToggle();
    });
JS);

?>

<div class="hero">
    <div class="container">
        <div class="d-flex justify-content-between pt-20">
            <div>
                <img src="/default/brand-logo-colored.png" class="logo">
            </div>

            <div class="d-flex">
                <div class="header-actions">
                    <?= Html::a('about', '#', [
                        'class' => 'font-size-h5 font-weight-bold mr-7'
                    ]) ?>
                    <?= Html::a('contact us', ['site/contact'], [
                        'class' => 'font-size-h5 font-weight-bold mx-7'
                    ]) ?>

                    <?= AppHtml::ifElse(App::isGuest(), Html::a('login to agap', ['site/login'], [
                        'class' => 'font-size-h5 font-weight-bold ml-7 btn-login'
                    ]), AppHtml::a('dashboard', ['dashboard/index'], [
                        'class' => 'font-size-h5 font-weight-bold ml-7 btn-login'
                    ])) ?>
                </div>

                <div class="mobile-menu">
                    <i class="fas fa-bars"></i>

                    <div id="mobile-menu">
                        <div class="d-flex justify-content-between pt-8">
                            <div>
                                <ul>
                                    <li>
                                        <?= Html::a('about', '#', [
                                            'class' => 'font-size-h5 font-weight-bold'
                                        ]) ?>
                                    </li>
                                    <li>
                                        <?= Html::a('contact us', ['site/contact'], [
                                            'class' => 'font-size-h5 font-weight-bold'
                                        ]) ?>
                                    </li>
                                    <li>
                                        <?= AppHtml::ifElse(App::isGuest(), Html::a('login to agap', ['site/login'], [
                                            'class' => 'font-size-h5 font-weight-bold'
                                        ]), AppHtml::a('dashboard', ['dashboard/index'], [
                                            'class' => 'font-size-h5 font-weight-bold'
                                        ])) ?>
                                    </li>
                                </ul>
                            </div>
                            <div class="mr-10">
                                <i class="far fa-window-close"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-container">
            <div class="row">
                <div class="col-md-7">
                    <h1>What is AccessGov?</h1>
                    <p>
                        AccessGov.ph is a collection of information systems prudently and expertly 
                        prepared for use by local government units. Manage activities and projects, 
                        perform tasks and function - all with the convenience of organized files and records on cloud storage. Digitally transform public service and hit
                        development targets with AccessGov.
                    </p>

                    <div class="mt-20">
                        <a href="#">
                            request a demo
                        </a>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="img-container">
                        <img src="/default/accessgov.png" class="img-fluid accessgov">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="how-it-works">
    <div class="container">
        <p class="lead text-center">how it works</p>
        <h2 class="text-center">AccessGov Assistance Portal (AGAP)</h2>
        <p class="text-center paragraph">
            AccessGov Assistance Portal (AGAP) is the first of its kind information system in the country that integrates the management of local social welfare and development programs and services in one feature packed platform.
            <br>Public sector managers for social development projects can build and monitor events for implementation using the portal while keeping track of daily and weekly reports.
        </p>

        <div class="row icon">
            <div class="col-md-3">
                <div class="text-center">
                    <a href="#" class="btn btn-icon btn-circle">
                        <img src="/default/how-it-works/database management.png" class="img-fluid">
                    </a>
                </div>
                <p class="text-center">Database Management</p>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <a href="#" class="btn btn-icon btn-circle">
                        <img src="/default/how-it-works/reports generation.png" class="img-fluid">
                    </a>
                </div>
                <p class="text-center">Reports Generation</p>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <a href="#" class="btn btn-icon btn-circle">
                        <img src="/default/how-it-works/budget management.png" class="img-fluid">
                    </a>
                </div>
                <p class="text-center">Budget Management</p>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <a href="#" class="btn btn-icon btn-circle">
                        <img src="/default/how-it-works/digital transactions.png" class="img-fluid">
                    </a>
                </div>
                <p class="text-center">Digital Transactions</p>
            </div>
        </div>
    </div>
</div>


<div class="sneak-peak">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <p class="lead font-weight-bold">APP SNEAK PEEK</p>
                <h2>Some cue headline </h2>
                <p class="paragraph">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                    consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                    cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                    proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </p>
            </div>
            <div class="col-md-6">
                <div id="myCarousel" class="carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    <!-- <ol class="carousel-indicators">
                        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                        <li data-target="#myCarousel" data-slide-to="1"></li>
                        <li data-target="#myCarousel" data-slide-to="2"></li>
                        <li data-target="#myCarousel" data-slide-to="3"></li>
                    </ol> -->

                    <!-- Wrapper for slides -->
                    <div class="carousel-inner" role="listbox">
                        <div class="item active">
                            <img src="/default/sneak-peek/certification.png">
                        </div>
                        <div class="item">
                            <img src="/default/sneak-peek/household.png">
                        </div>
                        <div class="item">
                            <img src="/default/sneak-peek/transaction.png">
                        </div>
                        <div class="item">
                            <img src="/default/sneak-peek/budget.png">
                        </div>
                    </div>
                    <!-- Left and right controls -->
                    <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="faq">
    <div class="container">
        <div class="text-center">
            <p class="lead font-weight-bold">LEARN MORE</p>
            <h2>Frequently Asked Questions</h2>


            <div class="faq-rows">
                <div class="item">
                    <div class="d-flex">
                        <div class="number">1</div>
                        <div class="title" data-toggle="collapse" data-target="#faq-q1" aria-expanded="false" aria-controls="faq-q1">
                            We have a mixed staff of veteran and newly recruited personnel in the SWDO, will this be easy to learn?
                        </div>
                    </div>
                    <div class="collapse collapse-content" id="faq-q1">
                        AGAP is designed with the skillsets of social work and development practitioners in mind. The user friendly and easy to navigate pages are developed together with social work experts. Purchase of the system also includes an operational manual and training. An optional upgrade for customer and technical support is also available.
                    </div>
                </div>

                <div class="item">
                    <div class="d-flex">
                        <div class="number">2</div>
                        <div class="title" data-toggle="collapse" data-target="#faq-q2" aria-expanded="false" aria-controls="faq-q2">
                            We don’t have a local MIS office, can we still avail? We already have PCs and laptops, what other hardware do we need?
                        </div>
                    </div>
                    <div class="collapse collapse-content" id="faq-q2">
                        Outsource the difficult and resource consuming task of maintaining the system and resolving software issues through chat, phone and email support. The technical and customer support package is available as an optional upgrade. Contract scope and limitations apply. 
                    </div>
                </div>

                <div class="item">
                    <div class="d-flex">
                        <div class="number">3</div>
                        <div class="title" data-toggle="collapse" data-target="#faq-q3" aria-expanded="false" aria-controls="faq-q3">
                            We have our own local program or project, can this be included in the system?
                        </div>
                    </div>
                    <div class="collapse collapse-content" id="faq-q3">
                        A multiyear subscription to AGAP comes with standard updates for select national SWD programs. Local projects and programs may be available for inclusion subject to assessment and compatibility. 
                    </div>
                </div>

                <div class="item">
                    <div class="d-flex">
                        <div class="number">4</div>
                        <div class="title" data-toggle="collapse" data-target="#faq-q4" aria-expanded="false" aria-controls="faq-q4">
                            We would like to add more systems in the other departments of our unit, can you develop?
                        </div>
                    </div>
                    <div class="collapse collapse-content" id="faq-q4">
                        The AccessGov suite of management information systems provide a range of solutions in upgrading your operations and project management activities. Get your unique, tailored fit I.T. based solutions specifically for your department, unit or agency program. Let us set a meeting to evaluate and talk about your operational requirements. 
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="help">
    <div class="container">
        <div class="text-center">
            <p class="lead font-weight-bold">LET US HELP YOU</p>
            <h2>Some closing headline </h2>
                <p class="paragraph">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                    consequat. Duis aute irure dol anim id est laborum.
                </p>
            </p>
        </div>
    </div>
</div>


<div class="copyright">
    <div class="container">
        <div class="d-flex justify-content-between">
            <div class="d-flex">
                <div class="img-container">
                    <img src="/default/icon-white-blue.png">
                </div>
                <div class="text">
                    <p>
                        Copyright © <?= date('Y', strtotime(App::formatter()->asDateToTimezone())) ?> AccessGov.ph. All rights reserved.
                    </p>
                </div>
            </div>

            <div class="d-flex">
                <div class="icon">
                    <a href="#" class="mr-2">
                        <i class="fab fa-facebook-square"></i>
                    </a>
                    <a href="#" class="ml-2">
                        <i class="fab fa-linkedin"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
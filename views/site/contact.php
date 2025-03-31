<?php
/* @var $form yii\bootstrap\KeenActiveForm */
/* @var $model app\models\LoginForm */
use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\widgets\ActiveForm;
use app\widgets\Alert;
use app\widgets\KeenActiveForm;
use yii\captcha\Captcha;

$this->title = 'Contact';
$this->params['breadcrumbs'][] = $this->title;

$publishedUrl = App::publishedUrl();
?>
<div class="d-flex flex-column flex-root">
    <div class="login login-1 login-signin-on d-flex flex-column flex-lg-row flex-column-fluid bg-white" id="kt_login">
        <div class="login-aside d-flex flex-column flex-row-auto" style="background-color: #7EBFDB;">
            <div class="d-flex flex-column-auto flex-column pt-lg-40 pt-15">
                <a href="<?= Url::to(['site/home']) ?>" class="text-center mb-15" title="Homepage">
                    <img src="/default/icon-white-blue.png" alt="logo" class="h-70px" />
                </a>
                <h3 class="font-weight-bolder text-center font-size-h4 font-size-h1-lg text-white">
                    AccessGov.ph
                </h3>
            </div>
            <div class="aside-img d-flex flex-row-fluid bgi-no-repeat bgi-position-y-bottom bgi-position-x-center" style="background-image: url(<?= $publishedUrl . '/media/svg/illustrations/payment.svg' ?>)"></div>
        </div>
        <div class="login-content flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden p-7 mx-auto">
            <div class="d-flex flex-column-fluid flex-center">
                <div class="site-contact">
                    <h1><?= Html::encode($this->title) ?></h1>
                    <?= Html::ifElse(
                        Yii::$app->session->hasFlash('contactFormSubmitted'),
                        $this->render('contact/_submitted', ['model' => $model]),
                        $this->render('contact/_form', ['model' => $model])
                    ) ?>
                </div>
            </div>
        </div>
    </div>
</div>
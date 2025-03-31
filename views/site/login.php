<?php

/* @var $form app\widgets\ActiveForm */
/* @var $model app\models\LoginForm */
use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\widgets\ActiveForm;
use app\widgets\Alert;
$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;

$publishedUrl = App::publishedUrl();

$this->registerCss(<<< CSS
    @media only screen and (max-width: 768px) {
        .login-content {
            padding: 0 !important;
            margin: 3rem !important;
        }
        .login-aside {
            display: none !important;
        }
    }
    .login-aside{
    background-image: url(default/Agapito.png);
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center center;
    }
    
    h3{
 color: #000;
//font-family: "Plus Jakarta Sans";
font-size: 32px;
font-style: normal;
font-weight: 700;
line-height: normal;  
    }
    
    .font-size-h4
    {
 color: #7A7A7A;
font-size: 22px;
font-style: normal;
font-weight: 500;
line-height: normal;
    }
    
CSS);




$this->registerJs(<<< JS

     const togglePassword = $('#togglePassword');
   const password = $('#loginform-password');

  togglePassword.on('click', function (e) {
     
    // toggle the type attribute
    const type = password.attr('type') === 'password' ? 'text' : 'password';
   //  password.setAttribute('type', type);
     password.attr('type', type);
    // toggle the eye slash icon
    //this.classList.toggle('fa-eye-slash');
  });
  
  //alert();

JS);
?>


<div class="d-flex flex-column flex-root">
    <div class="login login-1 login-signin-on d-flex flex-column flex-lg-row flex-column-fluid bg-white" id="kt_login">
        <div class="login-aside d-flex flex-column flex-row-auto" style="background-color: #0a4dc2;">
            
            <?php /*
            <div class="d-flex flex-column-auto flex-column pt-lg-40 pt-15">
                <a href="<?= Url::to(['site/home']) ?>" class="text-center mb-15">
                    <img src="/default/icon-white-blue.png" alt="logo" class="h-70px" />
                </a>
                <h3 class="font-weight-bolder text-center font-size-h4 font-size-h1-lg text-white">AccessGov Assistance Portal</h3>
            </div>
            <div class="aside-img d-flex flex-row-fluid bgi-no-repeat bgi-position-y-bottom bgi-position-x-center"></div>
            */ ?>
        </div>
        <div class="login-content flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden p-7 mx-auto">
            <div class="d-flex flex-column-fluid flex-center">
                <div class="login-form login-signin">
                    <?= Alert::widget() ?>
                    <?php $form = ActiveForm::begin([
                        'id' => 'kt_login_signin_form',
                        'errorCssClass' => 'is-invalid',
                        'successCssClass' => 'is-valid',
                        'validationStateOn' => 'input',
                        'options' => [
                            'class' => 'form',
                            'novalidate' => 'novalidate'
                        ]
                    ]); ?>
                        <div class="pb-13 pt-lg-0 pt-5 text-center">
                            <div class="mb-5">
                                
                             <?php if( strpos($_SERVER['REQUEST_URI'], "demo") == false) { ?> 
                            <img src="/default/Real-Logo-01.png" alt="real-logo" class="h-130px" style="margin-right: 20px;">
                            <img  src="/default/Real-logo.png" alt="real-logo" class="h-130px">
                            </div>
                            <h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">Welcome to Real <span style="color:#0A4DC2;">AGAP</span></h3>
                            <?php } else{ ?> 
                            
                            <img src="https://real.accessgov.ph/demo/assets/images/a3/a33a1a_icon-white-blue.png-XCtHBmh-Jd-1722993424.png" alt="real-logo" class="h-130px" >
                          
                            </div>
                            <h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">Welcome to demo <span style="color:#0A4DC2;">AGAP</span></h3>
                            
                            <?php } ?>
                            
                             <span class="text-muted font-weight-bold font-size-h4">Log in your account</span>
                            <!-- <span class="text-muted font-weight-bold font-size-h4">New Here?
                            <a href="javascript:;" id="kt_login_signup" class="text-primary font-weight-bolder">Create an Account</a></span> -->
                        </div>
                        <?= $form->field($model, 'username', [
                            'template' => '
                                <label class="font-size-h6 font-weight-bolder text-dark">
                                    Username or Email
                                </label>
                                 <div style="position: relative;">
                                {input}
                                <i class="flaticon2-user" style="position: absolute;top: 20px; left: 20px;" ></i>
                                </div>
                                {error}
                            '
                        ])->textInput([
                            'autofocus' => true, 
                            'class' => 'form-control form-control-solid h-auto p-6 rounded-lg',
                            'style' => 'border: solid 1px #C2C2C2;padding-left: 45px!important;'
                        ]) ?>
                        <?= $form->field($model, 'password', [
                            'template' => '
                                <div class="d-flex justify-content-between mt-n5">
                                    <label class="font-size-h6 font-weight-bolder text-dark pt-5">Password</label>
                                    <a href="#" class="text-primary font-size-h6 font-weight-bolder text-hover-primary pt-5" id="kt_login_forgot">Forgot Password ?</a>
                                </div>
                                <div style="position: relative;">
                                {input}
                                <i class="flaticon2-lock " style="position: absolute;top: 20px; left: 20px;"></i>
                                <i title="Show password" class="far fa-eye" id="togglePassword" style="cursor: pointer; position: absolute;top: 20px;right: 20px;"></i>
                                </div>
                                {error}
                            '
                        ])->passwordInput([
                            'class' => 'form-control form-control-solid h-auto p-6 rounded-lg',
                            'style' => 'border: solid 1px #C2C2C2;padding-left: 45px!important;'
                        ]) ?>

                        <!-- <div class="checkbox-inline mb-5">
                            <label class="checkbox">
                            <input type="checkbox" name="LoginForm[rememberMe]" value="1">
                            <span></span>Remember Me</label>
                        </div> -->

                        <div class="pb-lg-0 pb-5">
                            <button type="submit" id="kt_login_signin_submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3">Log In</button>
                        </div>
                    <?php ActiveForm::end(); ?>
                </div>
                <div class="login-form login-signup">
                    <form class="form" novalidate="novalidate" id="kt_login_signup_form">
                        <div class="pb-13 pt-lg-0 pt-5">
                            <h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">Sign Up</h3>
                            <p class="text-muted font-weight-bold font-size-h4">Enter your details to create your account</p>
                        </div>
                        <div class="form-group">
                            <input class="form-control form-control-solid h-auto p-6 rounded-lg font-size-h6" type="text" placeholder="Fullname" name="fullname" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <input class="form-control form-control-solid h-auto p-6 rounded-lg font-size-h6" type="email" placeholder="Email" name="email" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <input class="form-control form-control-solid h-auto p-6 rounded-lg font-size-h6" type="password" placeholder="Password" name="password" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <input class="form-control form-control-solid h-auto p-6 rounded-lg font-size-h6" type="password" placeholder="Confirm password" name="cpassword" autocomplete="off" />
                        </div>
                        <div class="form-group d-flex align-items-center">
                            <label class="checkbox mb-0">
                                <input type="checkbox" name="agree" />
                                <span></span>
                            </label>
                            <div class="pl-2">I Agree the
                            <a href="#" class="ml-1">terms and conditions</a></div>
                        </div>
                        <div class="form-group d-flex flex-wrap pb-lg-0 pb-3">
                        <button type="button" id="kt_login_signup_submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-4">Submit</button>
                            <button type="button" id="kt_login_signup_cancel" class="btn btn-light-primary font-weight-bolder font-size-h6 px-8 py-4 my-3">Cancel</button>
                        </div>
                    </form>
                </div>
                <div class="login-form login-forgot">
                    <?php $form = ActiveForm::begin([
                        'id' => 'kt_login_forgot_form',
                        'errorCssClass' => 'is-invalid',
                        'successCssClass' => 'is-valid',
                        'validationStateOn' => 'input',
                        'options' => [
                            'class' => 'form',
                            'novalidate' => 'novalidate'
                        ],
                        'action' => ['reset-password']
                    ]); ?>
                        <div class="pb-13 pt-lg-0 pt-5">
                            <h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">Forgotten Password ?</h3>
                            <p class="text-muted font-weight-bold font-size-h4">Enter your email to reset your password</p>
                        </div>
                        <?= $form->field($PSR, 'email')->textInput([
                            'class' => 'form-control form-control-solid h-auto p-6 rounded-lg font-size-h6',
                            'type' => 'email',
                            'placeholder' => 'Email',
                            'autocomplete' => 'off',
                        ])->label(false) ?>
                        <div class="form-group">
                            <div class="checkbox-list"> 
                                <label class="checkbox">
                                    <input type="checkbox" 
                                        value="1" 
                                        name="PasswordResetForm[hint]"> 
                                    <span></span>
                                    Show password hint instead.
                                </label>
                            </div>
                        </div>
                        <div class="form-group d-flex flex-wrap pb-lg-0">
                        <button type="submit" id="" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-4">Submit</button>
                            <button type="button" id="kt_login_forgot_cancel" class="btn btn-light-primary font-weight-bolder font-size-h6 px-8 py-4 my-3">Cancel</button>
                        </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
        
           <div style="position: absolute;bottom: 20px; right: 20px;">
            <a href="<?= Url::to(['site/home']) ?>" class="text-center mb-15">
                    <img src="/default/AccessGov-Logo-V2.png" alt="logo" class="h-35px" />
             </a>
           </div>
       
        
    </div>
</div>
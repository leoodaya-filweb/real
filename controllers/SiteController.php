<?php

namespace app\controllers;

use Yii;
use app\helpers\App;
use app\helpers\Html;
use app\models\Transaction;
use app\models\User;
use app\models\VisitLog;
use app\models\form\ContactForm;
use app\models\form\LoginForm;
use app\models\form\PasswordResetForm;
use yii\web\Response;

class SiteController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['AccessControl'] = [
            'class' => 'app\filters\AccessControl',
            'publicActions' => ['login', 'reset-password', 'passwordprotect', 'contact', 'index', 'about', 'home', 'certificate-verification','images']
        ];
        $behaviors['VerbFilter'] = [
            'class' => 'app\filters\VerbFilter',
            'verbActions' => [
                'logout' => ['post'],
            ]
        ];

        unset($behaviors['SettingFilter']);

        return $behaviors;
    }

    public function actionCertificateVerification($token='')
    {
        $this->layout = false;
        $model = Transaction::findOne(['token' => $token]);

        if (!$model) {
            return $this->render('verify-certificate-fake');
        }

        return $this->render('verify-certificate', [
            'model' => $model
        ]);
    }

    public function beforeAction($action)
    {
        switch ($action->id) {
            case 'about':
            case 'index':
            case 'home':
                $this->layout = 'home';
                break;
            case 'login':
            case 'reset-password':
            case 'contact':
                $this->layout = 'login';
                break;
            
            default:
                # code...
                break;
        }
        return parent::beforeAction($action);
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'layout' => 'error'
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionResetPassword($password_reset_token='')
    {
        if ($password_reset_token) {
            $user = User::findOne(['password_reset_token' => $password_reset_token]);

            if (!$user) {
                App::danger('Password reset link is expired.');
                return $this->redirect(['login']);
            }

            $model = new PasswordResetForm([
                'email' => $user->email,
                'scenario' => 'reset'
            ]);


            if ($model->load(App::post())) {
                if (($user = $model->changePassword()) != null) {
                    App::success("Password was reset.");
                    return $this->redirect(['login']);
                }
                else {
                    App::danger(Html::errorSummary($model));
                    return $this->redirect(['reset-password', 'password_reset_token' => $password_reset_token]);
                }
            }

            return $this->render('reset-password', [
                'model' => $model,
                'user' => $user,
            ]);

        }


        $model = new PasswordResetForm();
        if ($model->load(App::post())) {
            if (($user = $model->process()) != null) {
                if ($model->hint) {
                    App::success("Your password hint is: '{$user->password_hint}'.");
                }
                else {
                    App::success("Email sent.");
                }
            }
            else {
                App::danger(Html::errorSummary($model));
            }
        }

        return $this->redirect(['login']);
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (App::isLogin()) {
            return $this->redirect(['dashboard/index']);
        }

        return $this->redirect(['login']);
        //return $this->render('index');
    }

    public function actionHome()
    {
        return $this->redirect(['login']);
       // return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
   
        if (!App::isGuest()) {
            return $this->redirect(['dashboard/index']);
        }

        $model = new LoginForm();
        $PSR = new PasswordResetForm();
        if ($model->load(App::post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
            'PSR' => $PSR,
        ]);
    }
    
    
    
    public function actionPasswordprotect()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        
        $msg=['error'=>1,'message'=>'Incorect or ininvalid password'];

        if (!App::isGuest() && ($data = App::post()) && $data['password'] ) {
             $password =trim($data['password']);
             $user = Yii::$app->user->identity;
            if($user->validatePassword($password)){
                $msg=['error'=>0,'message'=>'Success password'];
               return $msg;
            }
           
        }

       return $msg;
    }


    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        App::logout();

        return $this->goHome();
    }
    
    
     public function actionImages()
    {
        $request_folder = Yii::$app->request->get('f');
         $img = Yii::$app->request->get('img');
        
       // $fullpath  = App::publishedUrl();
        
          if ( $request_folder ){
             $fullpath  = Yii::getAlias('@'.$request_folder);    
          }else{
             $fullpath  = Yii::getAlias('@defaultimg');  
          }



        return $this->renderAjax('display_image', [
            'fullpath' => $fullpath,
            'img'=>$img
            
        ]);
        
       
    }
    
   

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(App::post()) && $model->contact()) {
            App::success('Thank you for contacting us. We will respond to you as soon as possible.');
            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
<?php

namespace app\models\form;

use Yii;
use app\helpers\App;
use app\models\User;
use app\models\form\CustomEmailForm;
use app\helpers\Url;
use app\jobs\EmailJob;
use app\jobs\NotificationJob;
use app\models\Queue;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class PasswordResetForm extends \yii\base\Model
{
    public $email; 
    public $new_password; 
    public $confirm_password; 

    public $hint = false; 

    public $_user; 

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            ['email', 'required'],
            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'exist', 'targetClass' => 'app\models\User', 'targetAttribute' => 'email'],
            ['email', 'validateEmail'],
            ['hint', 'safe'],
            [['new_password', 'confirm_password'], 'required', 'on' => 'reset'],
            ['confirm_password', 'compare', 'compareAttribute' => 'new_password'],
        ];
    }

    public function validateEmail($attribute, $params)
    {
        if (($user = $this->getUser()) != null) {
            if ($user->is_blocked == User::BLOCKED) {
                $this->addError($attribute, 'User is blocked.');
            }

            if ($user->status == User::STATUS_DELETED) {
                $this->addError($attribute, 'User is deleted.');
            }

            if ($user->status == User::STATUS_INACTIVE) {
                $this->addError($attribute, 'User is inactive.');
            }

            if ($user->record_status == User::RECORD_INACTIVE) {
                $this->addError($attribute, 'User record is inactive.');
            }
        }
    }

    public function getUser()
    {
        if ($this->_user == null) {
            $this->_user = User::findByEmail($this->email);
        }

        return $this->_user;
    }

    public function process()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            if ($this->hint) {
                return $user;
            }

            $mail = new CustomEmailForm([
                'subject' => 'Password Reset',
                'template' => 'password_reset',
                'parameters' => ['user' => $user],
                'to' => $this->email
            ]);
            if ($mail->send()) {
                return $user;
            }
            else {
                $this->addError('mail', $mail->errors);
            }
        }

        return false;
    }

    public function changePassword()
    {
        if ($this->validate()) {
            $user = $this->getUser();

            $user->setPassword($this->new_password);
            $user->password_reset_token = App::randomString(10) . time();

            if ($user->save()) {

                Queue::push(new NotificationJob([
                    'user_id' => $user->id,
                    'type' => 'notification_change_password',
                    'message' => App::setting('notification')->notification_change_password,
                    'link' => Url::to(['user/my-password']),
                ]));

                Queue::push(new EmailJob([
                    'to' => $user->email,
                    'subject' => 'Password Change',
                    'content' => App::setting('email')->email_change_password,
                ]));

                return $user;
            }
            else {
                $this->addError('user', $user->errors);
            }
        }

        return false;
    }
}
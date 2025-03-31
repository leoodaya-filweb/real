<?php

namespace app\components;

use app\models\Email;

class MailerComponent extends \yii\swiftmailer\Mailer
{
    public $useFileTransport = false;

    public function send($message)
    {
        $isSuccessful = parent::send($message);

        if ($isSuccessful) {
            $swift = $message->getSwiftMessage();

            $to_email = array_key_first($message->to);
            $from_email = array_key_first($message->from);
            $from_name = $message->from[$from_email];

            $email = new Email([
                'to' => $to_email,
                'from_email' => $from_email,
                'from_name' => $from_name,
                'subject' => $message->subject,
                'body' => $swift->getBody(),
            ]);

            if ($email->save()) {
               return $isSuccessful;
            }
            else {
               return false;
            }
        }

        return $isSuccessful;
    }
}
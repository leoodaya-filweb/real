<?php

use app\helpers\Url;
use yii\helpers\Html;

$url = Url::to(['site/reset-password', 'password_reset_token' => $user->password_reset_token], true);
?>

<p>You may change your password by clicking the link below.</p>

<p><?= Html::a($url, $url) ?></p>
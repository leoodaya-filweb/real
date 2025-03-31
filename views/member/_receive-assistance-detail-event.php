<?php

use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\widgets\Value;
use app\widgets\Webcam;
?>

<div class="row receive-assistance-form">
    <div class="col-md-7">
        <div class="d-flex justify-content-between">
            <p class="lead font-weight-bold">Event Information</p>
            <div>
                <?= Html::a('View Event', $eventMember->eventViewUrl, [
                    'class' => 'btn btn-light-primary font-weight-bold',
                    'target' => '_blank'
                ]) ?>
            </div>
        </div>
       
        <?= $eventMember->eventDetailView ?>
    </div>
    <div class="col">
        <p class="lead font-weight-bold">Proof</p>

        <?= Html::ifElse($eventMember->isClaimOrAttended, function() use($eventMember) {
            $receivedAt = Value::widget([
                'label' => 'Received At',
                'content' => App::formatter('asFulldate', $eventMember->updated_at)
            ]);
            $photo = Html::image($eventMember->photo, ['w' => 500], [
                'class' => 'img-fluid'
            ]);
            return <<< HTML
                {$receivedAt}
                <div class="mt-5"></div>
               {$photo}
            HTML;
        }, function() use($eventMember) {
            return $eventMember->statusBadge;
        }) ?>
    </div>
</div>
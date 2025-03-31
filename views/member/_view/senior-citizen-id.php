<?php

use app\helpers\App;
use app\helpers\Url;
use app\helpers\Html;
use app\widgets\Value;
?>
<div class="card card-custom card-stretch gutter-b">
    <div class="card-header border-0 pt-6">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label font-weight-bolder font-size-h4 text-dark-75">
                <?= $tabData['title'] ?>
            </span>
            <span class="text-muted mt-3 font-weight-bold font-size-lg">
                <?= $tabData['description'] ?>
            </span>
        </h3>
    </div>
    <div class="card-body pt-7">
       <?= Html::ifElse($model->senior_citizen_id, function() use($model) {
            $img = Html::image($model->senior_citizen_id, ['w' => 500], [
                'class' => 'img-fluid img-senior-citizen-id'
            ]);
            $download = Html::a('Download', $model->downloadSeniorCitizenIdUrl, [
                'class' => 'btn btn-light-success font-weight-bolder mt-10'
            ]);
            return <<< HTML
                <div class="text-center">
                    {$img}
                    <p>
                        {$download}
                    </p>
                </div>
            HTML;
       }, function() use($model) {
            $create = Html::a('Apply for Senior Citizen ID', $model->getCreateTransactionLink('senior-citizen-id-application'), [
                'class' => 'btn btn-light-primary font-weight-bolder'
            ]);
            return <<< HTML
                <div class="text-center">
                    <h4>No ID yet!</h4>
                    {$create}
                </div>
            HTML;
       }) ?>
    </div>
</div>
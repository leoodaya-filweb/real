<?php

use app\helpers\Html;

$this->registerCss(<<< CSS
    .label-light-facebook {
        background-color: #3b5998;
        color: #fff;
    }

    .label-light-twitter {
        background-color: #1da1f2;
        color: #fff;
    }
CSS);
?>

<div class="timeline timeline-3">
    <div class="timeline-items">
        <?= Html::foreach($model->transactionLogs, function($log) {
            return <<< HTML
                <div class="timeline-item">
                    <div class="timeline-media">
                        <img alt="Pic" src="{$log->creatorImage}"/>
                    </div>
                    <div class="timeline-content">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="mr-2">
                                <a href="#" class="text-dark-75 text-hover-primary font-weight-bold">
                                    {$log->createdByEmail}
                                </a>
                                <span class="text-muted ml-2">
                                    {$log->ago}
                                </span>
                                {$log->label}
                            </div>
                        </div>
                        <p class="p-0">
                            {$log->remarks}
                        </p>
                    </div>
                </div>
            HTML;
        }) ?>
    </div>
</div>
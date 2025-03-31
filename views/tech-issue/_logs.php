<?php

use app\helpers\Html;
?>
<div class="timeline timeline-3 scroll scroll-pull" data-scroll="true" data-wheel-propagation="true" style="height: 80vh">
    <div class="timeline-items">
        <?= Html::foreach($model->techIssueLogs, function($log) {
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
                                {$log->statusLabel}
                            </div>
                        </div>
                        <p class="p-0">
                            {$log->remarks}
                            <div class="mt-3">
                                {$log->filePreviews}
                            </div>
                        </p>
                    </div>
                </div>
            HTML;
        }) ?>
    </div>
</div>
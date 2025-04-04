<?php

use app\helpers\Html;
use app\models\Notification;
?>

<?= Html::ifElse($notifications, function() use($notifications) {
    $data = Html::foreach($notifications, function($notification) {
        return <<< HTML
            <div class="d-flex align-items-center mb-6">
                <div class="symbol symbol-40 symbol-light-primary mr-5">
                    <span class="symbol-label">
                        <span class="svg-icon svg-icon-lg svg-icon-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24" />
                                    <path d="M12,10.9996338 C12.8356605,10.3719448 13.8743941,10 15,10 C17.7614237,10 20,12.2385763 20,15 C20,17.7614237 17.7614237,20 15,20 C13.8743941,20 12.8356605,19.6280552 12,19.0003662 C11.1643395,19.6280552 10.1256059,20 9,20 C6.23857625,20 4,17.7614237 4,15 C4,12.2385763 6.23857625,10 9,10 C10.1256059,10 11.1643395,10.3719448 12,10.9996338 Z M13.3336047,12.504354 C13.757474,13.2388026 14,14.0910788 14,15 C14,15.9088933 13.7574889,16.761145 13.3336438,17.4955783 C13.8188886,17.8206693 14.3938466,18 15,18 C16.6568542,18 18,16.6568542 18,15 C18,13.3431458 16.6568542,12 15,12 C14.3930587,12 13.8175971,12.18044 13.3336047,12.504354 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                    <circle fill="#000000" cx="12" cy="9" r="5" />
                                </g>
                            </svg>
                        </span>
                    </span>
                </div>
                <div class="d-flex flex-column font-weight-bold">
                    <a href="{$notification->viewUrl}" class="text-dark text-hover-primary mb-1 font-size-lg">
                        {$notification->secondaryLabel}
                        <span class="text-right badge badge-secondary">
                            {$notification->createdAgo}
                        </span>
                    </a>
                    <span class="text-muted">
                        {$notification->message}
                    </span>
                </div>
            </div>
        HTML;
    });

    
                        

    return implode(' ', [
        $data,
        Html::a('View All', (new Notification())->indexUrl, [
            'class' => 'btn btn-outline-primary btn-sm font-weight-bolder font-size-sm'
        ]),
        // Html::a('Read All', '#', [
        //     'class' => 'btn btn-outline-success btn-sm btn-read-all'
        // ])
    ]);
}, <<< HTML
    <div class="text-center">
        <p>
            <i class="far fa-envelope-open display-1"></i>
        </p>
        <p class="lead font-weight-bold">No new notifications !</p>
    </div>
HTML) ?>
<?php

use app\helpers\Html;

$this->registerCss(<<< CSS
    .pointer {
        cursor: pointer;
    }
    .pointer .code {
        font-size: 16px;
    }
CSS);
?>
<div class="px-3">
    <?= Html::a(<<< HTML
        <label class="badge badge-white text-center pointer pt-5 priority-filter">
            <span class="font-weight-bold code">
                {$code}
            </span>
            <div class="text-muted text-center mt-2">
                M: {$male_active}    
                | F: {$female_active}   
            </div>
        </label>
        <div class="text-center"> 
            <span class="badge badge-pill badge-{$class} badge-count">
                {$total_active}  
            </span>
        </div>
    HTML, 
        $url, 
        [ 'class' => "priority-filter", 'title' => 'View details']
    ) ?>
</div>
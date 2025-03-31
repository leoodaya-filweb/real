<?php

use app\helpers\Html;
?>

<div class="btn-group">
    <button type="button" class="btn btn-<?= $event->statusClass ?> dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    	<?= $event->statusLabel ?>
    </button>

    <div class="dropdown-menu">
    	<?= Html::foreach($dropdownMenu, function($a) {
    		return $a;
    	}) ?>
    </div>
</div>


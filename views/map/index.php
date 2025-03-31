<?php

use app\models\search\DashboardSearch;
use app\widgets\Mapbox;
$this->title = 'Map';
$this->params['searchModel'] = new DashboardSearch(['searchLabel' => 'Map']); 
$this->registerCss(<<< CSS
	.card.card-custom {height:  54em;}
CSS);
$this->params['createController'] = 'user'; 
$this->params['showCreateButton'] = false; 
$this->params['showExportButton'] = true;
?>
<div class="map-page">
	<?= Mapbox::widget([
        'enableClick' => false,
        'draggableMarker' => false,
    ]) ?>
</div>
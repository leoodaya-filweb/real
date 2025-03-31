<?php

use app\helpers\Html;
?>
<ul class="breadcrumb">
	<?= Html::foreach($breadcrumbs, function($folder) use($breadcrumbs) {
		return Html::tag('li', Html::a($folder['folderName'], '#'), [
			'data-path' => $folder['folderPath'],
			'class' => 'breadcrumb-link'
		]);
		
		return ($folder['folderName'] === array_key_last($breadcrumbs))? 
			Html::tag('li', $folder['folderName']):
			Html::tag('li', Html::a($folder['folderName'], '#'), [
				'data-path' => $folder['folderPath'],
				'class' => 'breadcrumb-link'
			]);
	}) ?>
</ul>
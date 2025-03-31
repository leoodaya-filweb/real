<?php

use app\helpers\Html;
?>

<?= $form->field($model, $attribute)->textInput([
	'maxlength' => true,
	'list' => "datalist-{$widgetId}",
	'autocomplete' => 'off'
]) ?>
<datalist id="datalist-<?= $widgetId ?>">
	<?= Html::foreach($data, function($o) {
		return Html::tag('option', $o);
	}) ?>
</datalist>
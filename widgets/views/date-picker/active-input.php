<?php

$this->registerWidgetJs($widgetFunction, <<< JS
	$('.datepicker-{$widgetId}').datepicker({
		rtl: false,
		todayBtn: "linked",
		clearBtn: true,
		todayHighlight: true,
		templates: {
			leftArrow: '<i class="la la-angle-left"></i>',
			rightArrow: '<i class="la la-angle-right"></i>'
		},
		endDate: "today",
		autoclose: true
	});
JS);
?>

<?= $form->field($model, $attribute)->textInput([
	'class' => "form-control datepicker-{$widgetId}",
	'autocomplete' => 'off',
	'readonly' => true
]) ?>
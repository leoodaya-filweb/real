<?php

use app\helpers\App;
use app\helpers\Html;

$this->registerWidgetJsFile('input-list');
$this->addJsFile('sortable/Sortable.min');

$this->registerCss(<<< CSS
	.handle-sortable:hover {
	    cursor: grab !important;
	}
CSS);

$this->registerJs(<<< JS
	new InputListWidget({
		widgetId: '{$widgetId}',
		name: '{$name}',
		label: '{$label}',
		type: '{$type}',
		inputType: '{$inputType}',
	}).init();

	new Sortable(document.getElementById('{$widgetId}-container'), {
        handle: '.handle-sortable', // handle's class
        animation: 150,
        ghostClass: 'bg-light-primary'
    });
JS);
?>

<div id="<?= $widgetId ?>">

	<div class="list-container" id="<?= $widgetId ?>-container">
		<?= App::foreach($data, function($value) use($name, $label, $type, $inputType) {
			$input = $type == 'input' ? Html::tag('input', '', [
				'type' => $inputType, 
				'name' => $name,
				'class' => 'form-control',
				'value' => $value,
				'placeholder' => "Enter {$label}",
				'required' => true
			]): Html::tag('textarea', $value, [
				'name' => $name,
				'class' => 'form-control',
				'value' => $value,
				'placeholder' => "Enter {$label}",
				'required' => true
			]);
			return <<< HTML
				<div class="input-group mb-2">
					<div class="input-group-prepend">
						<button class="btn btn-secondary handle-sortable" type="button">
							<i class="fas fa-arrows-alt"></i>
						</button>
					</div>
					{$input}
					<div class="input-group-append">
						<button class="btn btn-danger btn-remove" type="button">
							<i class="fa fa-trash"></i>
						</button>
					</div>
				</div>
			HTML;
		}) ?>
	</div>
	<div class="input-group">
		<div class="input-group-prepend">
			<button class="btn btn-secondary" type="button">
				<i class="fa fa-edit"></i>
			</button>
		</div>
		<?= App::ifElse(
			$type == 'input', 
			Html::tag('input', '', [
				'type' => $inputType, 
				'name' => 'input',
				'class' => 'form-control',
				'placeholder' => "Enter {$label}",
			]),
			Html::tag('textarea', '', [
				'name' => 'input',
				'class' => 'form-control',
				'placeholder' => "Enter {$label}",
			])
		) ?>
		<div class="input-group-append">
			<button class="btn btn-success btn-add" type="button">
				<i class="fa fa-plus-circle"></i>
			</button>
		</div>
	</div>
</div>

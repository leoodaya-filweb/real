<?php

use app\helpers\App;
use app\helpers\Html;
?>

<tr>
	<?= Html::foreach($columns, function($column, $key) use($model, $index) {
		$format = $column['format'] ?? 'raw';
		$format = "as" . ucwords($format);

		if ($key == 'serial') {
			$value = $index + 1;
		}
		else {
			if (isset($column['attribute'])) {
				$attribute = $column['attribute'];
			}

			if (isset($column['value'])) {
				if (is_callable($column['value'])) {
					$value = call_user_func($column['value'], $model, $key, $index);
				}
				else {
					$attribute = $column['value'];
				}
			}

			$value = $value ?? $model->{$attribute};
		}

		

		return Html::tag('td', App::formatter($format, $value));
	}) ?>
</tr>
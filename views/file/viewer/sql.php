<?php

use app\helpers\App;
use app\helpers\Html;

$this->title = $model->nameWithExtension;

$this->registerCss(<<< CSS
	pre {
		background: white;
	    margin: 20px;
	    border-radius: 10px;
	    padding: 10px;
	}
CSS);
?>

<pre>
	<?= Html::encode(file_get_contents($model->locationPath)) ?>
</pre>

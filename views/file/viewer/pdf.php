<?php

use app\helpers\App;

$this->title = $model->nameWithExtension;
?>
<iframe src="<?= App::baseUrl($model->location) ?>" width="100%" height="100%" frameborder="0">

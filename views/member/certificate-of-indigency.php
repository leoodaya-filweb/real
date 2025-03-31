<?php

use app\widgets\CertificateOfIndigency;

$this->registerJs(<<< JS
	window.print();
JS)
?>

<?= CertificateOfIndigency::widget([
	'model' => $model,
]) ?>
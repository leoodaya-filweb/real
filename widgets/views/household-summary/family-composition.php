<?php

use app\helpers\Html;
?>

<?= Html::foreach($members, function($member, $key) use($action, $household) {
	$a = $this->render('family-head', [
		'key' => $key + 1,
		'member' => $member,
		'action' => $action,
		'household' => $household,
	]);
	return <<< HTML
		{$a}
		<div class="separator separator-dashed my-5"></div>
	HTML;
});
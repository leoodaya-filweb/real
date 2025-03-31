<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
	'title' => 'Database Priority Sector'
]) ?>
	<?= $this->render('gridview', [
		'dataProvider' => $dataProvider,
		'priority_sector' => $priority_sector,
		'enableSorting' => $enableSorting,
	]) ?>
<?php $this->endContent() ?>
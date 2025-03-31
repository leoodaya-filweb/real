<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
	'stretch' => true,
	'title' => \app\helpers\Html::tag('span', 'Database Priority Sector', ['class' => 'font-weight-bolder text-dark'])
]) ?>
	<?= $this->render('gridview', [
		'dataProvider' => $dataProvider,
		'priority_sector' => $priority_sector,
		'enableSorting' => $enableSorting,
	]) ?>
<?php $this->endContent() ?>
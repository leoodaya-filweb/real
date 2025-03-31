<?php

use app\helpers\App;
use app\helpers\Html;

$this->registerCss(<<< CSS
	#event-summary-{$widgetId} table.table {
		/* width: fit-content; */
	}
CSS);
?>

<div id="event-summary-<?= $widgetId ?>" class="fs-13pt">

	<p class="lead text-dark-75 font-weight-bold fs-13pt"> PRIMARY DETAILS </p>
	<table class="table table-bordered fs-13pt">
		<tbody>
			<?= Html::tag('tr', implode('', [
				Html::tag('th', 'name'),
				Html::tag('td', $event->name)
			])) ?>

			<?= Html::tag('tr', implode('', [
				Html::tag('th', 'Category'),
				Html::tag('td', $event->categoryLabel)
			])) ?>

			<?= Html::tag('tr', implode('', [
				Html::tag('th', 'date'),
				Html::tag('td', implode(' ', [
					Html::tag('b', 'From:'),
					date('F d, Y h:i:s A', strtotime($event->date_from)),
					'<br>',
					Html::tag('b', 'To:'),
					date('F d, Y h:i:s A', strtotime($event->date_to))
				]))
			])) ?>

			<?= Html::tag('tr', implode('', [
				Html::tag('th', 'Type'),
				Html::tag('td', $event->eventTypeLabel)
			])) ?>

			<?= Html::if($event->isAssistance, Html::tag('tr', implode('', [
				Html::tag('th', 'Assistance Type'),
				Html::tag('td', $event->assistanceTypeLabel)
			]))) ?>

			<?= Html::if($event->isAssistance, Html::tag('tr', implode('', [
				Html::tag('th', 'Amount'),
				Html::tag('td', App::formatter('asNumber', $event->amount))
			]))) ?>
		</tbody>
	</table>

	<p class="lead text-dark-75 mt-10 font-weight-bold fs-13pt"> PARTICIPATION DATA </p>
	<?= Html::if(($summaries = $event->summaryData) != null, function() use($summaries, $event) {
		return Html::foreach($summaries, function($summary, $key) use($event) {
			$tr = '';
			foreach ($summary['total'] as $attribute => $total) {
				if ($key == 'family_head') {
					$attributeTitle = App::keyMapParams('family_head')[$attribute] ?? '';
				}
				elseif ($key == 'solo_parent') {
					$attributeTitle = App::keyMapParams('solo_parent')[$attribute] ?? '';
				}
				elseif ($key == 'pwd') {
					$attributeTitle = App::keyMapParams('pwd')[$attribute] ?? '';
				}
				else {
					$attributeTitle = str_replace('_', ' ', $attribute);
				}

				$attributeTitle = $attributeTitle ?: 'Not set';

				$completed = Html::number($summary['completed'][$attribute] ?? 0);
				
				$tr .= <<< HTML
					<tr>
						<td>{$attributeTitle}</td>
						<td class="text-right">{$completed}</td>
					</tr>
				HTML;
			}
			$label = str_replace('_', ' ', $key);
			if ($key == 'purok_no') {
				$label = 'Purok';
			}
			return <<< HTML
				<table class="table table-bordered fs-13pt">
				<thead>
					<th width="80%">{$label}</th>
					<th class="text-right">{$event->completedTabName}</th>
				</thead>
				<tbody>{$tr}</tbody>
				</table>
			HTML;
		});
	}) ?>
	
	<?php if (($allFiles = $event->allFiles) != null): ?>
		<p class="lead text-dark-75 mt-10 font-weight-bold fs-13pt"> FILES / DOCUMENTS </p>
		<table class="table table-bordered app-iconbox fs-13pt" id="table-file">
			<thead>
				<tr>
					<th class="th-file">File</th>
				</tr>
			</thead>
			<tbody class="files-container">
				<?= Html::foreach($allFiles, function($file) {
					return $this->render('/file/_row', [
						'model' => $file,
						'withAction' => false
					]);
				}) ?>
			</tbody>
		</table>
	<?php endif ?>
</div>

<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\Event;
use app\widgets\PieChart;

$this->registerJs(<<< JS

JS);
?>


<p class="lead text-dark-75 font-weight-bold"> PRIMARY DETAILS </p>
<table class="table table-bordered fs-13pt">
	<tbody>
		<tr>
			<th>name</th>
			<td><?= $event->name ?></td>
		</tr>

		<?= Html::if($event->category_type != Event::SOCIAL_PENSION_CATEGORY, Html::tag('tr', implode('', [
			Html::tag('th', 'Category'),
			Html::tag('td', $event->categoryLabel)
		]))) ?>


		<tr>
			<th>date</th>
			<td>
                <b>From:</b> <?= date('F d, Y h:i:s A', strtotime($event->date_from)) ?>
                <br><b>To:</b> <?= date('F d, Y h:i:s A', strtotime($event->date_to)) ?>
			</td>
		</tr>

		<?= Html::if($event->category_type != Event::SOCIAL_PENSION_CATEGORY, Html::tag('tr', implode('', [
			Html::tag('th', 'Type'),
			Html::tag('td', $event->eventTypeLabel)
		]))) ?>

		<?= Html::if($event->isAssistance, Html::tag('tr', implode('', [
			Html::tag('th', 'Assistance Type'),
			Html::tag('td', $event->assistanceTypeLabel)
		]))) ?>

		<?= Html::if($event->category_type == Event::SOCIAL_PENSION_CATEGORY, Html::tag('tr', implode('', [
			Html::tag('th', 'Social Pension Fund'),
			Html::tag('td', $event->fundLabel)
		]))) ?>

		<?= Html::if($event->category_type == Event::SOCIAL_PENSION_CATEGORY, Html::tag('tr', implode('', [
			Html::tag('th', 'No. of Pensioner'),
			Html::tag('td', App::formatter('asNumber', $event->no_of_pensioner))
		]))) ?>

		<?= Html::if($event->amount, Html::tag('tr', implode('', [
			Html::tag('th', 'Amount'),
			Html::tag('td', App::formatter('asNumber', $event->amount))
		]))) ?>

	</tbody>
</table>

<?php if ($withBeneficiaries): ?>
	<p class="lead text-dark-75 mt-10 font-weight-bold fs-13pt"> BENEFICIARIES </p>
	<table class="table table-bordered fs-13pt">
	    <?= Html::if(($beneficiaries = $event->beneficiaryData) != null, function() use($beneficiaries) {
	        $th = Html::foreach($beneficiaries, function($beneficiary, $key) {
				return Html::tag('th', $key);
	        });

	        $td = Html::foreach($beneficiaries, function($beneficiary, $key) {
	            $value = is_array($beneficiary)? implode(', ', $beneficiary): $beneficiary;
				return Html::tag('td', $value);
	        });

	        $tr = Html::foreach($beneficiaries, function($beneficiary, $key) {
	        	$th = Html::tag('th', $key, ['style' => 'white-space: nowrap;']);
	            $value = is_array($beneficiary)? implode(', ', $beneficiary): $beneficiary;
				$td = Html::tag('td', $value);

				return Html::tag('tr', implode('', [$th, $td]));
	        });

			return <<< HTML
				<tobdy> {$tr}</tobdy>
			HTML;
	    }) ?>
	</table>
<?php endif ?>

<p class="lead text-dark-75 mt-10 font-weight-bold fs-13pt"> PARTICIPATION SUMMARY </p>
<table class="table table-bordered fs-13pt">
	<thead>
		<th>beneficiaries</th>
		<th><?= $event->pendingTabName ?></th>
		<th><?= $event->completedTabName ?></th>
	</thead>
	<tbody>
		<tr>
			<td width="80%"> <?= App::formatter('asNumber', $event->totalEventMembers) ?> </td>
			<td> <?= App::formatter('asNumber', $event->totalPending) ?> </td>
			<td> <?= App::formatter('asNumber', $event->totalCompleted) ?> </td>
		</tr>
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

			$pending = Html::number($summary['pending'][$attribute] ?? 0);
			$completed = Html::number($summary['completed'][$attribute] ?? 0);

			$tr .= <<< HTML
				<tr>
					<td width="80%">{$attributeTitle}</td>
					<td class="text-right">{$pending}</td>
					<td class="text-right">{$completed}</td>
				</tr>
			HTML;
		}

		
		$label = str_replace('_', ' ', $key);
		return <<< HTML
			<table class="table table-bordered fs-13pt">
			<thead>
					<th width="80%">{$label}</th>
					<th class="text-right">{$event->pendingTabName}</th>
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
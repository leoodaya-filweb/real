<?php

use app\helpers\Html;
?>

<section>
	<div class="row">
		<div class="col-md-12">
			<h3 class="card-label">VII. Kasapian sa mga Organisasyon, Samahan, etc.</h3>
			<hr/>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<table class="table">
				<thead>
					<tr>
						<th>Pangalan ng Organisasyon</th>
						<th>Posisyong hinawakan kung mayroon</th>
						<th>Taon</th>
					</tr>
				</thead>
				<tbody>
					<?= Html::foreach($model->organizations, function($data, $key) {
						return <<< HTML
							<tr>
								<td> 
									<input class="form-control" type="hidden" value="{$data['no']}" name="Database[organizations][{$key}][no]">

									<input class="form-control" type="text" value="{$data['name']}" name="Database[organizations][{$key}][name]">
								</td>

								<td> 
									<input class="form-control" type="text" value="{$data['position']}"name="Database[organizations][{$key}][position]"> 
								</td>
								<td> 
									<input class="form-control" type="number" value="{$data['year']}"name="Database[organizations][{$key}][year]"> 
								</td>
							</tr>
						HTML;
					}) ?>
				</tbody>
			</table>
		</div>
	</div>
</section>
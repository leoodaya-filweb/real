<?php

use app\helpers\App;
use app\helpers\Url;

$image = $image ?: App::setting('image');
?>
<table class="mce-item-table" style="width: 100%;" data-mce-style="width: 100%;">
	<tbody>
		<tr>
			<td style="border-style: hidden;width: 15%;" data-mce-style="width: 15%;">
				<img style="box-sizing: border-box; vertical-align: middle; border-style: none;" src="<?= Url::image($image->doh_logo, ['w' => 120], true) ?>" alt="" data-mce-src="" data-mce-style="box-sizing: border-box; vertical-align: middle; border-style: none;">
			</td>
			<td style="width: 70%;border-style: hidden;" data-mce-style="width: 70%;">
				<h3 style="text-align: center;" data-mce-style="text-align: center;">
					<span style="font-size: 14pt;" data-mce-style="font-size: 14pt;">DEPARTMENT OF HEALTH</span>
				</h3>
				<div style="text-align: center;" data-mce-style="text-align: center;">
					<span style="font-size: 18pt;" data-mce-style="font-size: 18pt;">
						<strong>Application Form</strong>
					</span>
				</div>
			</td>
			<td style="width: 15%;border-style: hidden;" data-mce-style="width: 15%;">
				<h3><br></h3>
			</td>
		</tr>
	</tbody>
</table>
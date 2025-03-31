<?php

use app\helpers\App;
use app\helpers\Html;

$this->title = "File Viewer: {$model->name}";

$this->registerJs(<<< JS

	var rotation = 0;
    function rotateImg() {
		document.querySelector("#img").style.transform = 'rotate('+ rotation +'deg)';;
    }

	$('.rotate-left-btn').click(function() {
		rotation = rotation - 90;
		rotateImg();
	});

	$('.rotate-right-btn').click(function() {
		rotation = rotation + 90;
		rotateImg();
	});
JS);

$this->registerCss(<<< CSS
	.table-bordered th {
	    white-space: nowrap;
	}
	.table-bordered td {
	    overflow-wrap: anywhere;
	}
CSS);
?>

<div class="container py-15 m-auto">

	<div class="row">
		<div class="col-md-8 text-center m-auto">
			<div>
				<?= Html::img($location ?? $model->displayPath, [
					'class' => 'img-fluid',
					'id' => 'img',
					'style' => 'max-height: 85vh'
				]) ?>
			</div>

		</div>

		<div class="col-md-4">
			<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
                'title' => 'Image Properties',
                'toolbar' => <<< HTML
                	<div class="card-toolbar">
                		<a href="{$model->displayPath}" class="font-weight-bolder btn btn-sm btn-outline-primary">Full Screen</a>
                	</div>
                HTML
            ]); ?>
	            <table class="table-bordered table">
	                <tbody>
	                    <tr>
	                        <th>Name</th>
	                        <td> <?= $model->name ?> </td>
	                    </tr>
	                    <tr>
	                        <th>Extension</th>
	                        <td> <?= $model->extension ?> </td>
	                    </tr>
	                    <tr>
	                        <th>Size</th>
	                        <td> <?= $model->fileSize ?> </td>
	                    </tr>
	                    <tr>
	                        <th>Width</th>
	                        <td> <?= $model->width ?> </td>
	                    </tr>
	                    <tr>
	                        <th>Height</th>
	                        <td> <?= $model->height ?> </td>
	                    </tr>
	                    <tr>
	                        <th>Location</th>
	                        <td> <?= $model->location ?> </td>
	                    </tr>
	                    <tr>
	                        <th>Token</th>
	                        <td> <?= $model->token ?> </td>
	                    </tr>
	                    <tr>
	                        <th>Created At</th>
	                        <td> <?= App::formatter('asFulldate', $model->created_at) ?> </td>
	                    </tr>
	                    <tr>
	                        <th>Created by</th>
	                        <td> <?= $model->createdByEmail ?> </td>
	                    </tr>
	                </tbody>
	            </table>

				<div class="action-buttons mt-5">
					<div class="btn-group">
						<div>
							<?= Html::a('Download', $model->downloadUrl, [
					            'class' => 'btn btn-success font-weight-bolder',
					            'id' => 'download-whitecard'
					        ]) ?>
						</div>
						<div>
							<div class="btn-group btn-options ml-2" style="">
		                        <button type="button" class="btn btn-primary font-weight-bolder rotate-left-btn" title="Rotate Left">
		                            <span data-toggle="tooltip" title="" data-original-title="Rotate Left">
		                                <span class="fa fa-undo-alt"></span>
		                                Rotate Left
		                            </span>
		                        </button>
		                        <button type="button" class="btn btn-primary font-weight-bolder rotate-right-btn" title="Rotate Right">
		                            <span data-toggle="tooltip" title="" data-original-title="Rotate Right">
		                                Rotate Right
		                                <span class="fa fa-redo-alt"></span>
		                            </span>
		                        </button>
		                    </div>
						</div>
					</div>
				</div>
			<?php $this->endContent(); ?>
		</div>
	</div>
</div>

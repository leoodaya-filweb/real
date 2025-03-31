<?php

use app\helpers\Html;
use app\models\Database;
use app\models\form\PrioritySectorForm;
use app\widgets\ActiveForm;
use app\widgets\DataList;
use app\widgets\Value;

$sector = new PrioritySectorForm();


$this->registerCss(<<< CSS
    .app-iconbox {
        box-shadow: rgb(0 0 0 / 30%) 0px 1px 4px 0
    }
    
    #tbl-priority-sector_filter {
        text-align: right;
    }
    #tbl-priority-sector_filter label,
    #tbl-priority-sector_length label {
        display: inline-flex;
    }

    #tbl-priority-sector_filter input {
        margin-top: -5px;
        margin-left: 5px;
    }
    #tbl-priority-sector_length select{
        margin-top: -5px;
        margin-left: 5px;
        margin-right: 5px;
    }
    #tbl-priority-sector_paginate {
        float: right;
    }
    .th-file {
        width: 80% !important;
    }
CSS);

$this->registerJs(<<< JS
	$('.btn-edit-priority-sector').click(function() {
		let tr = $(this).closest('tr'),
			id = tr.find('.td-id').html(),
			code = tr.find('.td-code').html(),
			label = tr.find('.td-label').html(),
			v_class = tr.find('.td-class').html(),
			user_access = tr.find('.td-user-access').html();


		$(".field-prioritysectorform-id").hide();
		$("#setting-priority-sector-form").yiiActiveForm('resetForm');

		$('#prioritysectorform-id').val(id);
		$('#prioritysectorform-code').val(code);
		$('#prioritysectorform-label').val(label);
		$('#prioritysectorform-class').val(v_class);
		$('#prioritysectorform-user_access').val(user_access);
		

		$('#modal-new-sector .modal-title').html('Update Priority Sector');
		$('#modal-new-sector').modal('show');
	});

	$('.btn-add-new-sector').click(function() {
		$(".field-prioritysectorform-id").show();
		$("#setting-priority-sector-form")[0].reset();
		$("#setting-priority-sector-form").yiiActiveForm('resetForm');
		$('#modal-new-sector .modal-title').html('Add Priority Sector');
		$('#modal-new-sector').modal('show');
	});

	$('#tbl-priority-sector').DataTable({
        pageLength: 10,
    });
JS);
?>
<?= Html::if($withHeader = $withHeader ?? true, <<< HTML
	<h4 class="mb-10 font-weight-bold text-dark">
		Priority Sector
		<button class="btn btn-success font-weight-bold ml-2 btn-add-new-sector">
			Add Priority Sector
		</button>
	</h4>
HTML) ?>

<table class="table table-bordered" id="tbl-priority-sector">
	<thead>
		<tr>
			<th>ID</th>
			<th>code</th>
			<th>label</th>
			<th>class</th>
			<th>User Access</th>
			<th width="150">action</th>
		</tr>
	</thead>
	<tbody>
		<?= Html::foreach($model->data, function($sector) {
			$deleteBtn = Database::findOne(['priority_sector' => $sector['id']]) ? '': Html::a('<i class="fa fa-trash"></i>', ['database/delete-priority-sector', 'id' => $sector['id']], [
				'class' => 'btn btn-light-danger btn-sm btn-icon',
				'data-confirm' => 'Delete '. $sector['code'] .' Priority Sector',
				'data-method' => 'post'
			]);
			
			if($sector['user_access']){
			   $user_access= json_decode($sector['user_access']);
			}
			
			
			return <<< HTML
				<tr>
					<td class="td-id">{$sector['id']}</td>
					<td class="td-code">{$sector['code']}</td>
					<td class="td-label">{$sector['label']}</td>
					<td class="td-class">{$sector['class']}</td>
					<td class="td-user-access">{$sector['user_access']}</td>
					<td class="text-center">
						<button type="button" class="btn btn-icon btn-sm btn-light-primary btn-edit-priority-sector">
							<i class="fa fa-edit"></i>
						</button>
						{$deleteBtn}
					</td>
				</tr>
			HTML;
		}) ?>
	</tbody>
</table>


<div class="modal fade" id="modal-new-sector" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Priority Sector</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin([
			    	'id' => 'setting-priority-sector-form',
			    	'action' => ['database/save-priority-sector']
			    ]); ?>
			    	<div class="mb-5">
			    		<?= Value::widget([
				    		'label' => 'Notice',
				    		'content' => 'Same "id" value will overwrite the existing priority sector.',
				    	]) ?>
			    	</div>
					<?= $form->field($sector, 'id')->textInput(['type' => 'number']) ?>
					<?= $form->field($sector, 'code')->textInput(['maxlength' => true]) ?>
					<?= $form->field($sector, 'label')->textInput(['maxlength' => true]) ?>
					<?= DataList::widget([
						'form' => $form,
						'model' => $sector,
						'attribute' => 'class',
						'data' => [
							'success' => 'success',
							'primary' => 'primary',
							'info' => 'info',
							'warning' => 'warning',
							'danger' => 'danger',
							'secondary' => 'secondary',
							'dark' => 'dark',
							'light' => 'light',
						]
					]) ?>
					<small>Limit for specific user ex. ["juan", "pedro"]</small>
					<?= $form->field($sector, 'user_access')->textInput(['maxlength' => true]) ?>

					<div class="mt-10">
						<?= Html::submitButton('Save Priority Sector', ['class' => 'btn btn-success font-weight-bold']) ?>
						<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
					</div>
				<?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
	
<?php

use app\helpers\Html;

$this->registerCss(<<< CSS
    #table-fc_filter,
    #table-ifc_filter {
        text-align: right;
    }
    #table-fc_filter label,
    #table-fc_length label,
	#table-ifc_filter label,
    #table-ifc_length label {
        display: inline-flex;
    }

    #table-fc_filter input,
    #table-ifc_filter input {
        margin-top: -5px;
        margin-left: 5px;
    }
    #table-fc_length select,
    #table-ifc_length select {
        margin-top: -5px;
        margin-left: 5px;
        margin-right: 5px;
    }
    #table-fc_paginate,
    #table-ifc_paginate {
        float: right;
    }
    .btn-profile {
        cursor: pointer;
    }
    .btn-profile:hover {
        text-decoration: underline;
    }
CSS);
$this->registerJs(<<< JS
	$('.container-fc .btn-profile').click(function() {
		let qr_id = $(this).data('qr_id');
		KTApp.block('.container-fc', {
			overlayColor: '#000000',
			state: 'warning',
			message: 'Please wait...'
		});
		$.ajax({
			url: app.baseUrl + 'member/detail',
			data: {qr_id: qr_id},
			dataType: 'json',
			success: function(s) {
				if (s.status == 'success') {
					$('#modal-member-profile .modal-body').html(s.detailView);
					$('#modal-member-profile').modal('show')
				}
				else {
					Swal.fire('Error', s.error, 'error');
				}
				KTApp.unblock('.container-fc');
			},
			error: function(e) {
				Swal.fire('Error', e.responseText, 'error');
				KTApp.unblock('.container-fc');
			}
		});
	});
    $('#table-fc').DataTable();
    $('#table-ifc').DataTable();
JS);
?>

<div class="card card-custom card-stretch gutter-b container-fc">
    <div class="card-header border-0 pt-6">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label font-weight-bolder font-size-h4 text-dark-75">
                <?= $tabData['title'] ?> (<?= $model->totalFamilyComposition ?>)
            </span>
            <span class="text-muted mt-3 font-weight-bold font-size-lg">
                <?= $tabData['description'] ?>
            </span>
        </h3>
    </div>
    <div class="card-body pt-7">
    	<ul class="nav nav-tabs nav-bold nav-tabs-line">
			<li class="nav-item">
				<a class="nav-link active" data-toggle="tab" href="#tab-active">
					<span class="nav-icon">
						<i class="flaticon2-chat-1"></i>
					</span>
					<span class="nav-text">Active</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#tab-inactive">
					<span class="nav-icon">
						<i class="flaticon2-drop"></i>
					</span>
					<span class="nav-text">In-active</span>
				</a>
			</li>
		</ul>

		<div class="tab-content">
			<div class="tab-pane fade show active" id="tab-active" role="tabpanel" aria-labelledby="tab-active">
				<div class="mt-10">
				  
					<table class="table table-head-solid" id="table-fc_TEMP">
				        <thead>
				            <tr>
				                <th>#</th>
				                <th>name</th>
				                <th class="text-center">sex</th>
				                <th class="text-center">Date of Birth</th>
				                <th class="text-center">age</th>
				                <th style="text-align: center;">RELATIONSHIP</th>
				                 <th style="text-align: center;">CIVIL STATUS</th>
				                <th width="100">action</th>
				            </tr>
				        </thead>
				        <tbody>
				            <?php $member = $model; ?>
				            <?= Html::ifElse(($fc = $model->familyCompositions) != null, function() use($fc, $member) {
				                return Html::foreach($fc, function($model, $key) use($member){
				                    $action = implode(' ', [
			                        	Html::a('View Profile', $model->viewUrl, [
				                            'class' => 'dropdown-item',
			                            	'target' => '_blank'
				                        ]),
				                        Html::a('Edit Profile', $model->updateUrl, [
				                            'class' => 'dropdown-item'
				                        ]),
				                        Html::a('Add Transaction', $model->createTransactionLink, [
				                            'class' => 'dropdown-item',
				                            'target' => '_blank'
				                        ]),
				                        Html::a('Download QR', $model->downloadQrCodeUrl, [
				                            'class' => 'dropdown-item'
				                        ])
				                    ]);
				                    $serial = $key + 1;
				                    $photo = Html::image($model->photo, ['w' => 30], [
				                        'class' => 'img-thumbnail'
				                    ]);
									return <<< HTML
										<tr>
											<td>{$serial}</td>
											<td>
												<div class="d-flex">
													<div>{$photo} &nbsp;</div>
													<div>
														<span class="btn-profile" data-qr_id="{$model->qr_id}">
															{$model->fullname}
														</span>
														<br><small>{$model->qr_id} | {$model->positionTag}</small>
													</div>
												</div>
											</td>
											<td class="text-center">{$model->genderName}</td>
											<td class="text-center">{$model->birthDate}</td>
											<td class="text-center">{$model->currentAge}</td>
											<td class="text-center">{$member->relationTo($model)}</td>
											<td style="text-align: center;">{$model->civilStatusName}</td>
											
											<td>
												<div class="dropdown">
													<button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
														Action
													</button>
													<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
														{$action}
													</div>
												</div>
											</td>
										</tr>
									HTML;
				                });
				                
				            }, '<p class="lead"> No data found. </p>') ?>
				        </tbody>
				    </table>
				</div>
			</div>
			<div class="tab-pane fade" id="tab-inactive" role="tabpanel" aria-labelledby="tab-inactive">
				<div class="mt-10">
					<table class="table table-bordered table-head-solid" id="table-ifc_TEMP">
				        <thead>
				            <tr>
				                <th>#</th>
				                <th>name</th>
				                <th>sex</th>
				                <th class="text-right">age</th>
				                <th width="100">action</th>
				            </tr>
				        </thead>
				        <tbody>
				            <?= Html::ifElse(($fc = $model->inactiveFamilyCompositions) != null, function() use($fc) {
				                return Html::foreach($fc, function($model, $key) {
				                    $action = implode(' ', [
			                        	Html::a('View Profile', $model->viewUrl, [
				                            'class' => 'dropdown-item',
			                            	'target' => '_blank'
				                        ]),
				                        Html::a('Edit Profile', $model->updateUrl, [
				                            'class' => 'dropdown-item'
				                        ]),
				                        Html::a('Add Transaction', $model->createTransactionLink, [
				                            'class' => 'dropdown-item',
				                            'target' => '_blank'
				                        ]),
				                        Html::a('Download QR', $model->downloadQrCodeUrl, [
				                            'class' => 'dropdown-item'
				                        ])
				                    ]);
				                    $serial = $key + 1;
				                    $photo = Html::image($model->photo, ['w' => 30], [
				                        'class' => 'img-thumbnail'
				                    ]);
									return <<< HTML
										<tr>
											<td>{$serial}</td>
											<td>
												<div class="d-flex">
													<div>{$photo} &nbsp;</div>
													<div>
														<span class="btn-profile" data-qr_id="{$model->qr_id}">
															{$model->name}
														</span>
														<br><small>{$model->qr_id} | {$model->householdNo}</small>
													</div>
												</div>
											</td>
											<td>{$model->genderName}</td>
											<td class="text-right">{$model->currentAge}</td>
											<td>
												<div class="dropdown">
													<button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
														Action
													</button>
													<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
														{$action}
													</div>
												</div>
											</td>
										</tr>
									HTML;
				                });
				                
				            }, '<p class="lead"> No data found. </p>') ?>
				        </tbody>
				    </table>
				</div>
			</div>
		</div>
    </div>
</div>


<div class="modal fade" id="modal-member-profile" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-xl  modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                	Member's Information
                </h5>
                <button type="button" class="close btn-close-modal" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" data-scroll="true">
                
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold btn-close-search-qr-id" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

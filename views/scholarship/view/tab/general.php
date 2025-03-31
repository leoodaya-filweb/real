<?php

use app\helpers\App;
use app\helpers\Html;
?>

<div class="tab-pane fade show active">
    <?php $this->beginContent('@app/views/layouts/_card_wrapper.php') ?>
        <div class="d-flex">
            <div class="flex-shrink-0 mr-7">
                <div class="symbol symbol-150 symbol-lg-150">
                    <?= Html::image($model->photo, ['w' => 200], [
                        'class' => 'img-fluid'
                    ]) ?>
                </div>
            </div>
            <div class="flex-grow-1">
                <div class="d-flex align-items-center justify-content-between flex-wrap mt-2">
                    <div class="mr-3">
                        <a href="#" class="d-flex align-items-center text-dark text-hover-primary font-size-h5 font-weight-bold mr-3"> <?= $model->name ?> 
                        </a>
                        <div class="d-flex flex-wrap my-2">
                            <a href="#" class="text-muted text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
                                <i class="fa fa-calendar"></i> 
                                <?= $model->birth_date ?>
                            </a>
                            <a href="#" class="text-muted text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
                                <span class="svg-icon svg-icon-md svg-icon-gray-500 mr-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <mask fill="white">
                                                <use xlink:href="#path-1"></use>
                                            </mask>
                                            <g></g>
                                            <path d="M7,10 L7,8 C7,5.23857625 9.23857625,3 12,3 C14.7614237,3 17,5.23857625 17,8 L17,10 L18,10 C19.1045695,10 20,10.8954305 20,12 L20,18 C20,19.1045695 19.1045695,20 18,20 L6,20 C4.8954305,20 4,19.1045695 4,18 L4,12 C4,10.8954305 4.8954305,10 6,10 L7,10 Z M12,5 C10.3431458,5 9,6.34314575 9,8 L9,10 L15,10 L15,8 C15,6.34314575 13.6568542,5 12,5 Z" fill="#000000"></path>
                                        </g>
                                    </svg>
                                </span> 
                                <?= $model->course ?>
                            </a>
                            <a href="#" class="text-muted text-hover-primary font-weight-bold">
                                <span class="svg-icon svg-icon-md svg-icon-gray-500 mr-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"></rect>
                                            <path d="M9.82829464,16.6565893 C7.02541569,15.7427556 5,13.1079084 5,10 C5,6.13400675 8.13400675,3 12,3 C15.8659932,3 19,6.13400675 19,10 C19,13.1079084 16.9745843,15.7427556 14.1717054,16.6565893 L12,21 L9.82829464,16.6565893 Z M12,12 C13.1045695,12 14,11.1045695 14,10 C14,8.8954305 13.1045695,8 12,8 C10.8954305,8 10,8.8954305 10,10 C10,11.1045695 10.8954305,12 12,12 Z" fill="#000000"></path>
                                        </g>
                                    </svg>
                                </span> Barangay <?= $model->barangayName ?>, <?= $model->street_address ?> </a>
                        </div>
                    </div>
                    <div class="my-lg-0 my-1">
                        <?= Html::a('<i class="fa fa-edit"></i> Update Information', $model->updateUrl, [
                            'class' => 'btn btn-sm btn-primary font-weight-bold mr-2'
                        ]) ?>
                        <?= Html::a('<i class="fa fa-trash"></i> Remove Scholarship', $model->deleteUrl, [
                            'class' => 'btn btn-sm btn-light-danger font-weight-bold mr-2',
                            'data-confirm' => 'Are you sure?',
                            'data-method' => 'post'
                        ]) ?>
                    </div>
                </div>
                <div class="d-flex align-items-center flex-wrap">
                    <div class="flex-grow-1 font-weight-bold text-dark-50 py-2 py-lg-2 mr-5">
                        <div class="row">
                            <div class="col-md-6">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>Email</td>
                                            <td>: <?= $model->email ?: 'None' ?></td>
                                        </tr>
                                        <tr>
                                            <td>Alternate Email</td>
                                            <td>: <?= $model->alternate_email ?: 'None' ?></td>
                                        </tr>
                                        <tr>
                                            <td>Contact No</td>
                                            <td>: <?= $model->contact_no ?: 'None' ?></td>
                                        </tr>
                                        <tr>
                                            <td>Alternate Contact No</td>
                                            <td>: <?= $model->alternate_contact_no ?: 'None' ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>House No</td>
                                            <td>: <?= $model->house_no ?: 'None' ?></td>
                                        </tr>
                                        <tr>
                                            <td>Parent</td>
                                            <td>: <?= $model->guardian ?: 'None' ?></td>
                                        </tr>
                                        <tr>
                                            <td>First Enrollment</td>
                                            <td>: <?= $model->first_enrollment ?: 'None' ?></td>
                                        </tr>
                                        <tr>
                                            <td>Expected Graduation</td>
                                            <td>: <?= $model->expected_graduation ?: 'None' ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex mt-4 mt-sm-0 align-items-center">
                        <div class="font-weight-bold mr-4">Total Allowance: </div>
                        <div class="display-4 font-weight-bolder text-black-75 totalAllowance">
                            <?= App::formatter()->asPeso($model->totalAllowance) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="separator separator-solid my-7"></div>
        <div class="d-flex align-items-center flex-wrap">
            <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                <span class="mr-4">
                    <span class="svg-icon svg-icon-2x">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"/>
                                <path d="M13.6855025,18.7082217 C15.9113859,17.8189707 18.682885,17.2495635 22,17 C22,16.9325178 22,13.1012863 22,5.50630526 L21.9999762,5.50630526 C21.9999762,5.23017604 21.7761292,5.00632908 21.5,5.00632908 C21.4957817,5.00632908 21.4915635,5.00638247 21.4873465,5.00648922 C18.658231,5.07811173 15.8291155,5.74261533 13,7 C13,7.04449645 13,10.79246 13,18.2438906 L12.9999854,18.2438906 C12.9999854,18.520041 13.2238496,18.7439052 13.5,18.7439052 C13.5635398,18.7439052 13.6264972,18.7317946 13.6855025,18.7082217 Z" fill="#000000"/>
                                <path d="M10.3144829,18.7082217 C8.08859955,17.8189707 5.31710038,17.2495635 1.99998542,17 C1.99998542,16.9325178 1.99998542,13.1012863 1.99998542,5.50630526 L2.00000925,5.50630526 C2.00000925,5.23017604 2.22385621,5.00632908 2.49998542,5.00632908 C2.50420375,5.00632908 2.5084219,5.00638247 2.51263888,5.00648922 C5.34175439,5.07811173 8.17086991,5.74261533 10.9999854,7 C10.9999854,7.04449645 10.9999854,10.79246 10.9999854,18.2438906 L11,18.2438906 C11,18.520041 10.7761358,18.7439052 10.4999854,18.7439052 C10.4364457,18.7439052 10.3734882,18.7317946 10.3144829,18.7082217 Z" fill="#000000" opacity="0.3"/>
                            </g>
                        </svg>
                    </span>
                </span>
                <div class="d-flex flex-column text-dark-75">
                    <span class="font-weight-bolder font-size-sm">Year Level</span>
                    <span class="font-weight-bolder font-size-h5 current_year_level">
                        <?= App::formatter('asOrdinal', $model->current_year_level) ?> 
                        Year
                    </span>
                </div>
            </div>
            <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                <span class="mr-4">
                    <span class="svg-icon svg-icon-2x">
                        <!--begin::Svg Icon | path:assets/media/svg/icons/Shopping/Sale2.svg-->
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"></rect>
                                <polygon fill="#000000" opacity="0.3" points="12 20.0218549 8.47346039 21.7286168 6.86905972 18.1543453 3.07048824 17.1949849 4.13894342 13.4256452 1.84573388 10.2490577 5.08710286 8.04836581 5.3722735 4.14091196 9.2698837 4.53859595 12 1.72861679 14.7301163 4.53859595 18.6277265 4.14091196 18.9128971 8.04836581 22.1542661 10.2490577 19.8610566 13.4256452 20.9295118 17.1949849 17.1309403 18.1543453 15.5265396 21.7286168"></polygon>
                                <polygon fill="#000000" points="14.0890818 8.60255815 8.36079737 14.7014391 9.70868621 16.049328 15.4369707 9.950447"></polygon>
                                <path d="M10.8543431,9.1753866 C10.8543431,10.1252593 10.085524,10.8938719 9.13585777,10.8938719 C8.18793881,10.8938719 7.41737243,10.1252593 7.41737243,9.1753866 C7.41737243,8.22551387 8.18793881,7.45690126 9.13585777,7.45690126 C10.085524,7.45690126 10.8543431,8.22551387 10.8543431,9.1753866" fill="#000000" opacity="0.3"></path>
                                <path d="M14.8641422,16.6221564 C13.9162233,16.6221564 13.1456569,15.8535438 13.1456569,14.9036711 C13.1456569,13.9520555 13.9162233,13.1851857 14.8641422,13.1851857 C15.8138085,13.1851857 16.5826276,13.9520555 16.5826276,14.9036711 C16.5826276,15.8535438 15.8138085,16.6221564 14.8641422,16.6221564 Z" fill="#000000" opacity="0.3"></path>
                            </g>
                        </svg>
                        <!--end::Svg Icon-->
                    </span>
                </span>
                <div class="d-flex flex-column text-dark-75">
                    <span class="font-weight-bolder font-size-sm">Course / Program</span>
                    <span class="font-weight-bolder font-size-h5 course">
                        <?= $model->course ?>
                    </span>
                </div>
            </div>
            <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                <span class="mr-4">
                    <span class="svg-icon svg-icon-2x">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"/>
                                <path d="M13.5,21 L13.5,18 C13.5,17.4477153 13.0522847,17 12.5,17 L11.5,17 C10.9477153,17 10.5,17.4477153 10.5,18 L10.5,21 L5,21 L5,4 C5,2.8954305 5.8954305,2 7,2 L17,2 C18.1045695,2 19,2.8954305 19,4 L19,21 L13.5,21 Z M9,4 C8.44771525,4 8,4.44771525 8,5 L8,6 C8,6.55228475 8.44771525,7 9,7 L10,7 C10.5522847,7 11,6.55228475 11,6 L11,5 C11,4.44771525 10.5522847,4 10,4 L9,4 Z M14,4 C13.4477153,4 13,4.44771525 13,5 L13,6 C13,6.55228475 13.4477153,7 14,7 L15,7 C15.5522847,7 16,6.55228475 16,6 L16,5 C16,4.44771525 15.5522847,4 15,4 L14,4 Z M9,8 C8.44771525,8 8,8.44771525 8,9 L8,10 C8,10.5522847 8.44771525,11 9,11 L10,11 C10.5522847,11 11,10.5522847 11,10 L11,9 C11,8.44771525 10.5522847,8 10,8 L9,8 Z M9,12 C8.44771525,12 8,12.4477153 8,13 L8,14 C8,14.5522847 8.44771525,15 9,15 L10,15 C10.5522847,15 11,14.5522847 11,14 L11,13 C11,12.4477153 10.5522847,12 10,12 L9,12 Z M14,12 C13.4477153,12 13,12.4477153 13,13 L13,14 C13,14.5522847 13.4477153,15 14,15 L15,15 C15.5522847,15 16,14.5522847 16,14 L16,13 C16,12.4477153 15.5522847,12 15,12 L14,12 Z" fill="#000000"/>
                                <rect fill="#FFFFFF" x="13" y="8" width="3" height="3" rx="1"/>
                                <path d="M4,21 L20,21 C20.5522847,21 21,21.4477153 21,22 L21,22.4 C21,22.7313708 20.7313708,23 20.4,23 L3.6,23 C3.26862915,23 3,22.7313708 3,22.4 L3,22 C3,21.4477153 3.44771525,21 4,21 Z" fill="#000000" opacity="0.3"/>
                            </g>
                        </svg>
                    </span>
                </span>
                <div class="d-flex flex-column text-dark-75">
                    <span class="font-weight-bolder font-size-sm">School / Learning Center</span>
                    <span class="font-weight-bolder font-size-h5 school_name">
                        <?= $model->school_name ?>
                    </span>
                </div>
            </div>
            <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                <span class="mr-4">
                    <span class="svg-icon svg-icon-2x">
                        <!--begin::Svg Icon | path:assets/media/svg/icons/Tools/Hummer.svg-->
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"/>
                                <rect fill="#000000" x="2" y="5" width="19" height="4" rx="1"/>
                                <rect fill="#000000" opacity="0.3" x="2" y="11" width="19" height="10" rx="1"/>
                            </g>
                        </svg>
                        <!--end::Svg Icon-->
                    </span>
                </span>
                <div class="d-flex flex-column text-dark-75">
                    <span class="font-weight-bolder font-size-sm">School Year</span>
                    <span class="font-weight-bolder font-size-h5 school_year">
                        <?= $model->school_year ?>
                    </span>
                </div>
            </div>
        </div>
    <?php $this->endContent() ?>

    <div class="row">
        <div class="col-md-4">
            <?= $this->render('../allowance-history', [
                'model' => $model
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= $this->render('../education-history', [
                'model' => $model
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= $this->render('../documents', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
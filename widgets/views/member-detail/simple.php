<?php

use app\helpers\App;
use app\helpers\Url;
use app\helpers\Html;
?>

<section class="section">
    <?= Html::a(Html::image($model->photo, ['w' => 120], [
        'title' => 'Member\'s Photo',
        'data-content' => 'Click to download',
        'data-toggle' => 'popover',
        'data-placement' => 'top',
        'class' => 'img-thumbnail'
    ]), Url::to(['file/download', 'token' => $model->photo ?: App::setting('image')->image_holder])) ?>
    <?= Html::a(Html::img($model->qrCode, [
        'width' => 120,
        'title' => 'Member\'s QR Code',
        'data-content' => 'Click to download',
        'data-toggle' => 'popover',
        'data-placement' => 'top',
        'class' => 'img-thumbnail'
    ]), $model->downloadQrCodeUrl) ?>
</section>

<section class="section">
    <h6 class="font-weight-bolder mb-3">Primary Information:</h6>
    <div class="text-dark-50 line-height-lg">
        <table>
            <tbody>
                <tr>
                    <th><?= $model->getAttributeLabel('householdNo') ?></th>
                    <td>: <?= $model->householdNo ?></td>
                </tr>
                <tr>
                    <th><?= $model->getAttributeLabel('qr_id') ?></th>
                    <td>: <?= $model->qr_id ?></td>
                </tr>
                <tr>
                    <th><?= $model->getAttributeLabel('last_name') ?></th>
                    <td>: <?= $model->last_name ?></td>
                </tr>
                <tr>
                    <th><?= $model->getAttributeLabel('middleName') ?></th>
                    <td>: <?= $model->middleName ?></td>
                </tr>
                <tr>
                    <th><?= $model->getAttributeLabel('first_name') ?></th>
                    <td>: <?= $model->first_name ?></td>
                </tr>
                <tr>
                    <th><?= $model->getAttributeLabel('sexLabel') ?></th>
                    <td>: <?= $model->sexLabel ?></td>
                </tr>
                <tr>
                    <th><?= $model->getAttributeLabel('civilStatusName') ?></th>
                    <td>: <?= $model->civilStatusName ?></td>
                </tr>
                <tr>
                    <th><?= $model->getAttributeLabel('educationalAttainmentLabel') ?></th>
                    <td>: <?= $model->educationalAttainmentLabel ?: 'None' ?></td>
                </tr>
                <tr>
                    <th><?= $model->getAttributeLabel('birth_date') ?></th>
                    <td>: <?= $model->birth_date ?></td>
                </tr>
                <tr>
                    <th><?= $model->getAttributeLabel('currentAge') ?></th>
                    <td>: <?= $model->currentAge ?></td>
                </tr>
                <tr>
                    <th><?= $model->getAttributeLabel('birth_place') ?></th>
                    <td>: <?= $model->birth_place ?: 'None' ?></td>
                </tr>
            </tbody>
        </table>
    </div>
        
</section>


<section class="section">
    <h6 class="font-weight-bolder mb-3">Address:</h6>
    <div class="text-dark-50 line-height-lg">
        <table>
            <tbody>
                <tr>
                    <th><?= $household->getAttributeLabel('barangayName') ?></th>
                    <td>: <?= $household->barangayName ?: 'None' ?></td>
                </tr>
                <tr>
                    <th><?= $household->getAttributeLabel('purok_no') ?></th>
                    <td>: <?= $household->purok_no ?: 'None' ?></td>
                </tr>
                <tr>
                    <th><?= $household->getAttributeLabel('blk_no') ?></th>
                    <td>: <?= $household->blk_no ?: 'None' ?></td>
                </tr>
                <tr>
                    <th><?= $household->getAttributeLabel('lot_no') ?></th>
                    <td>: <?= $household->lot_no ?: 'None' ?></td>
                </tr>
                <tr>
                    <th><?= $household->getAttributeLabel('street') ?></th>
                    <td>: <?= $household->street ?: 'None' ?></td>
                </tr>
                <tr>
                    <th><?= $household->getAttributeLabel('zone_no') ?></th>
                    <td>: <?= $household->zone_no ?: 'None' ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<section class="section">
    <h6 class="font-weight-bolder mb-3">Contact:</h6>
    <div class="text-dark-50 line-height-lg">
        <table>
            <tbody>
                <tr>
                    <th><?= $model->getAttributeLabel('email') ?></th>
                    <td>: <?= $model->email ?: 'None' ?></td>
                </tr>
                <tr>
                    <th><?= $model->getAttributeLabel('contact_no') ?></th>
                    <td>: <?= $model->contact_no ?: 'None' ?></td>
                </tr>
                <tr>
                    <th><?= $model->getAttributeLabel('telephone_no') ?></th>
                    <td>: <?= $model->telephone_no ?: 'None' ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<section class="section">
    <h6 class="font-weight-bolder mb-3">Occupation:</h6>
    <div class="text-dark-50 line-height-lg">
        <table>
            <tbody>
                <tr>
                    <th><?= $model->getAttributeLabel('occupation') ?></th>
                    <td>: <?= $model->occupation ?: 'None' ?></td>
                </tr>
                <tr>
                    <th><?= $model->getAttributeLabel('monthlyIncome') ?></th>
                    <td>: <?= $model->monthlyIncome ?></td>
                </tr>
                <tr>
                    <th><?= $model->getAttributeLabel('source_of_income') ?></th>
                    <td>: <?= $model->source_of_income ?: 'None' ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<section class="section">
    <h6 class="font-weight-bolder mb-3">Pension:</h6>
    <div class="text-dark-50 line-height-lg">
        <table>
            <tbody>
                <tr>
                    <th><?= $model->getAttributeLabel('pensionerTag') ?></th>
                    <td>: <?= $model->pensionerTag ?></td>
                </tr>
                <tr>
                    <th><?= $model->getAttributeLabel('pensioner_from') ?></th>
                    <td>: <?= $model->pensioner_from ?: 'None' ?></td>
                </tr>
                <tr>
                    <th><?= $model->getAttributeLabel('monthlyPensionAmount') ?></th>
                    <td>: <?= $model->monthlyPensionAmount ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>


<section class="section">
    <h6 class="font-weight-bolder mb-3">PWD:</h6>
    <div class="text-dark-50 line-height-lg">
        <table>
            <tbody>
                <tr>
                    <th><?= $model->getAttributeLabel('pwdLabel') ?></th>
                    <td>: <?= $model->pwdLabel ?></td>
                </tr>
                <tr>
                    <th><?= $model->getAttributeLabel('pwdTypeName') ?></th>
                    <td>: <?= $model->pwdTypeName ?: 'None' ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<section class="section">
    <h6 class="font-weight-bolder mb-3">Others:</h6>
    <div class="text-dark-50 line-height-lg">
        <table>
            <tbody>
                <tr>
                    <th><?= $model->getAttributeLabel('skillsList') ?></th>
                    <td>: <?= $model->skillsList ?: 'None' ?></td>
                </tr>
                <tr>
                    <th><?= $model->getAttributeLabel('soloParentLabel') ?></th>
                    <td>: <?= $model->soloParentLabel ?></td>
                </tr>
                <tr>
                    <th><?= $model->getAttributeLabel('livingStatusLabel') ?></th>
                    <td>: <?= $model->livingStatusLabel ?></td>
                </tr>
                <tr>
                    <th><?= $model->getAttributeLabel('fourPsLabel') ?></th>
                    <td>: <?= $model->fourPsLabel ?></td>
                </tr>
                <tr>
                    <th><?= $model->getAttributeLabel('voterLabel') ?></th>
                    <td>: <?= $model->voterLabel ?></td>
                </tr>
                <tr>
                    <th><?= $model->getAttributeLabel('soloMemberLabel') ?></th>
                    <td>: <?= $model->soloMemberLabel ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

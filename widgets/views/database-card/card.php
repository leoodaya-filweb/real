<?php

use app\helpers\Html;
use app\widgets\Anchor;
?>

<div class="col-md-4 mb-10">
    <div class="card card-custom card-stretch bg-diagonal bg-diagonal-light-<?= $row['class'] ?> app-border database-card" data-url="<?= $total_active_url ?>">
        <div class="card-body">

            <?= Html::a($row['label'], 
                        ['database/member', 'priority_sector' => $row['id']], 
                        [
                        'class' => "h4 text-dark text-hover-{$row['class']}"
                    ]) ?>
            
            <div class="row my-5">
                <div class="col-md-6 m-auto">
                    <div class="text-dark-50 mt-3" style="font-size: 14px;">
                        <div>
                            <b>Total:</b> 
                            <?= Anchor::widget([
                                'title' => $total_active,
                                'link' => $total_active_url,
                                'text' => true,
                                'options' => ['class' => 'font-weight-bolder']
                            ]) ?>
                        </div>
                        <div>
                            <b>Male:</b>
                            <?= Anchor::widget([
                                'title' => $male_active,
                                'link' => $total_male_active_url,
                                'text' => true,
                                'options' => ['class' => 'font-weight-bolder']
                            ]) ?>
                        </div>
                        <div>
                            <b>Female:</b> 
                            <?= Anchor::widget([
                                'title' => $female_active,
                                'link' => $total_female_active_url,
                                'text' => true,
                                'options' => ['class' => 'font-weight-bolder']
                            ]) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 m-auto">
                    <?= Html::a(
                        '<i class="fa fa-plus-circle"></i> ADD RECORD', 
                        ['database/create', 'priority_sector' => $row['id']], 
                        [
                        'class' => "btn font-weight-bolder text-uppercase btn-outline-{$row['class']} btn-lg float-right"
                    ]) ?>
                </div>
            </div>

            <div class="font-weight-bolder text-black-50 text-right" style="font-size:12px">
                <em>Last Updated: <?= $last_updated ?: 'N/A' ?></em>
            </div>
        </div>
    </div>
</div>
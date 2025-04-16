<?php

use yii\helpers\Html;

foreach ($datas as $data): ?>
    <tr class="text-start">
        <td><?= Html::encode($data['barangay']) ?></td>
        <td><?= Html::encode($data['total_voters']) ?></td>
        <td><?= Html::encode($data['total_registered']) ?></td>
        <td><?= Html::encode($data['support_voters']) ?></td>
        <td><?= Html::encode(number_format($data['support_rate'], 2)) ?>%</td>
        <td><?= Html::encode($data['projected_votes']) ?></td>
    </tr>
<?php endforeach; ?>

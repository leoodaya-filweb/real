<?php

use app\helpers\App;
?>

<tr>
    <td>
        <span class="font-weight-bolder">
            <?= $education['school_name'] ?>
        </span>
        <div class="text-muted font-weight-bold"><?= App::formatter('asOrdinal', $education['year_level']) ?> Year</div>
    </td>
    <td><?= $education['course'] ?> </td>
    <td><?= $education['school_year'] ?> </td>
</tr>
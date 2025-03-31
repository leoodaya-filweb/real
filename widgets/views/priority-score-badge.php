<?php

$this->registerCss(<<< CSS
    .popover-body ul {
        padding-left: 15px;
    }
CSS);
?>
<label data-toggle="popover" title="Priority Score Details (<?= $totalScore ?>)" data-html="true" data-content="
	<ul>
        <li>PWD: <strong><?= $model->pwdScore ?></strong> / <?= $PWD_SCORE ?></li>
        <li>Senior Citizen: <strong><?= $model->seniorScore ?></strong> / <?= $SENIOR_SCORE ?></li>
        <li>Solo Parent: <strong><?= $model->soloParentScore ?></strong> / <?= $SOLO_PARENT_SCORE ?></li>
        <li>Solo Member: <strong><?= $model->soloMemberScore ?></strong> / <?= $SOLO_MEMBER_SCORE ?></li>
        <li>Resources Accessibility: <strong><?= $model->accessibilityScore ?></strong> / <?= $ACCESSIBILITY_SCORE ?></li>
    </ul>
">
	<?= $totalScore ?>
	<i class="fas fa-info-circle text-info"></i>
</label>
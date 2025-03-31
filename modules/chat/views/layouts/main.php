<?php

use app\modules\chat\assets\ThemeAsset;

ThemeAsset::register($this);
?>

<?php $this->beginContent('@app/views/layouts/main.php') ?>
    <?= $content ?>
<?php $this->endContent() ?>
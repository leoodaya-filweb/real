<?php

namespace app\components;

class AssetManagerComponent extends \yii\web\AssetManager
{
    public $appendTimestamp = false;
    public $linkAssets = true;
}
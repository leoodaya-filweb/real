<?php

use app\helpers\App;
use app\helpers\Url;

$image = $image ?: App::setting('image');
$address = $address ?: App::setting('address');
?>

<div style="box-sizing: border-box; display: flex !important; flex-wrap: nowrap !important; -webkit-box-pack: justify !important; justify-content: space-between !important; -webkit-box-align: center !important; align-items: center !important;">
  
    <div style="box-sizing: border-box; text-align: center !important; width: 100%;">
        <img style="box-sizing: border-box; vertical-align: middle; border-style: none;" src="<?= Url::image($image->municipality_logo, ['w' => 120], true) ?>" alt="" />
        <img style="box-sizing: border-box; vertical-align: middle; border-style: none;" src="<?= Url::image($image->social_welfare_logo, ['w' => 120], true) ?>" alt="" />
        <img style="box-sizing: border-box; vertical-align: middle; border-style: none;" src="<?= Url::image($image->province_logo, ['w' => 108], true) ?>" alt="" />
        <img style="box-sizing: border-box; vertical-align: middle; border-style: none;" src="<?= Url::image($image->philippines_logo, ['w' => 120], true) ?>" alt="" />
      
        <h4 style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0; font-weight: 600; color:#222222; line-height: 1.5; font-size: 18pt; font-family: 'times new roman', times, serif;">
            <span style="">
               MUNICIPAL SOCIAL WELFARE AND DEVELOPMENT OFFICE
            </span>
            
        </h4>
    </div>

</div>

<div style="box-sizing: border-box; height: 10px; border-bottom: 3px solid #3f4253; margin-top: 0px !important; padding-bottom: 0.5rem !important; text-align: center !important; font-size: 16pt !important;"></div>

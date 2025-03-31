<?php

use app\helpers\App;
use app\helpers\Url;

$image = $image ?: App::setting('image');
$address = $address ?: App::setting('address');
?>

<div style="box-sizing: border-box; display: flex !important; flex-wrap: nowrap !important; -webkit-box-pack: justify !important; justify-content: space-between !important; -webkit-box-align: center !important; align-items: center !important;">
    <div style="box-sizing: border-box;">
        <span style="">
            <img style="box-sizing: border-box; vertical-align: middle; border-style: none;" src="<?= Url::image($image->municipality_logo, ['w' => 150], true) ?>" alt="" />
        </span>
    </div>

    <div style="box-sizing: border-box; text-align: center !important;">
        <h4 style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0; font-weight: 500; line-height: 1.2; font-size: 15pt;">
            <span style="">
                Republic of the Philippines 
            </span>
            <br style="box-sizing: border-box;" />
            <span style="">
                Province of <?= ucwords(strtolower($address->provinceName)) ?>
            </span>
            <br style="box-sizing: border-box;" />
            
            <?php if( strpos($_SERVER['REQUEST_URI'], "demo") == false) { ?> 
            <span style="box-sizing: border-box;font-weight: bolder; ">
                <?= ucwords(strtoupper($address->municipalityName)) ?> QUEZON
            </span>
            <br style="box-sizing: border-box;" />
            <span style="box-sizing: border-box;font-weight: bolder; ">
                METRO REINA
            </span>
            <?php } ?> 
            
            <div style="box-sizing: border-box;font-weight: bolder; font-size: 8pt;">
               -oOo-
            </div>
        </h4>
    </div>

    <div style="box-sizing: border-box;">
        <span style="">
            <img style="box-sizing: border-box; vertical-align: middle; border-style: none;" src="<?= Url::image($image->social_welfare_logo, ['w' => 150], true) ?>" alt="" />
        </span>
    </div>

</div>

<div style="box-sizing: border-box; border-bottom: 2px solid #000000; margin-top: 1.25rem !important; padding-bottom: 0.5rem !important; text-align: center !important; font-size: 16pt !important;">
    <span style="">
        <em style="box-sizing: border-box;"> 
            OFFICE OF THE MUNICIPAL SOCIAL WELFARE AND DEVELOPMENT 
        </em>
    </span>
</div>
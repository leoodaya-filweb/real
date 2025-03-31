<?php

use app\helpers\Url;
?>
<table style="border-collapse: collapse; width: 100%;border-style: hidden;font-size: 12pt;" border="1">
    <tbody>
        <tr>
            <td style="width: 60%;border-style: hidden;">
                <div style="line-height: 1.3rem;">
                    <img src="<?= Url::base() . '/default/certificate-footer-images/telephone.png' ?>" width="20" height="20" alt="" data-mce-src="<?= Url::base() . '/default/certificate-footer-images/telephone.png' ?>"> 331-1631

                    <br>
                    <img src="<?= Url::base() . '/default/certificate-footer-images/globe.png' ?>" width="20" height="20" alt="" data-mce-src="<?= Url::base() . '/default/certificate-footer-images/globe.png' ?>">
                    www.realquezon.ph
                    
                    <br>
                    <img src="<?= Url::base() . '/default/certificate-footer-images/envelop.png' ?>" width="20" height="20" alt="" data-mce-src="<?= Url::base() . '/default/certificate-footer-images/envelop.png' ?>">
                    mswdoreal22@gmail.com

                    <br>
                    <img src="<?= Url::base() . '/default/certificate-footer-images/facebook.png' ?>" width="20" height="20" alt="" data-mce-src="<?= Url::base() . '/default/certificate-footer-images/facebook.png' ?>"> 
                    Municipality of <?= ucwords(strtolower($address->municipalityName)) ?>
                </div>
            </td>
            <td style="width: 20%;border-style: hidden;">
                <img style="box-sizing: border-box; vertical-align: middle; border-style: none;" src="<?= Url::image($image->secondary_logo, ['w' => 150], true) ?>" alt="" />
            </td>
            <td style="width: 20%;border-style: hidden;">
                <img style="box-sizing: border-box; vertical-align: middle; border-style: none;" src="<?= Url::image($image->other_logo, ['w' => 150], true) ?>" alt="" />
            </td>
        </tr>
    </tbody>
</table>
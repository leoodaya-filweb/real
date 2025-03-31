<?php

namespace app\widgets;

use app\helpers\App;
use app\models\File;
use chillerlan\QRCode\QRCode As QRCLass;
use app\helpers\qrcode\{QRImageWithLogo, LogoOptions};

class QRCode extends BaseWidget
{
    public $model;
    public $qr;
    public $img = false;
    public $photo;
    public $token;

    public function init()
    {
        parent::init();

        $options = new LogoOptions;

        $options->version          = 7;
        $options->eccLevel         = QRCLass::ECC_H;
        $options->imageBase64      = true;
        $options->logoSpaceWidth   = 15;
        $options->logoSpaceHeight  = 15;
        $options->scale            = 5;
        $options->imageTransparent = false;

        try {
            if ($this->token) {
                $this->qr = new QRImageWithLogo($options, (new QRCLass($options))->getMatrix($this->token));
            }
            else {
                $this->qr = new QRImageWithLogo($options, (new QRCLass($options))->getMatrix($this->model->qr_id));
            }
        } catch (Exception $e) {
            
        }

        $this->setPhoto();
    }

    public function setPhoto()
    {
        $this->photo = App::setting()->getQrCodePath();
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if (!$this->qr) {
            return;
        }
        if($this->img == false) {
            return $this->qr->dump(null, $this->photo);
        }

        return $this->render('qrcode', [
            'model' => $this->model,
            'qr' => $this->qr,
        ]);
    }
}

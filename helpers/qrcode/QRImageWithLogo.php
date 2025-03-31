<?php
/**
 * Class QRImageWithLogo
 *
 * @filesource   QRImageWithLogo.php
 * @created      18.11.2020
 * @package      chillerlan\QRCodeExamples
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2020 smiley
 * @license      MIT
 *
 * @noinspection PhpComposerExtensionStubsInspection
 */

namespace app\helpers\qrcode;

use chillerlan\QRCode\Output\{QRCodeOutputException, QRImage};

use function imagecopyresampled, imagecreatefrompng, imagesx, imagesy, is_file, is_readable;

/**
 * @property \chillerlan\QRCodeExamples\LogoOptions $options
 */
class QRImageWithLogo extends QRImage{

	/**
	 * @param string|null $file
	 * @param string|null $logo
	 *
	 * @return string
	 * @throws \chillerlan\QRCode\Output\QRCodeOutputException
	 */
	public function dump(string $file = null, string $logo = null):string{
		// set returnResource to true to skip further processing for now
		$this->options->returnResource = true;

		// of course you could accept other formats too (such as resource or Imagick)
		// i'm not checking for the file type either for simplicity reasons (assuming PNG)
		if(!is_file($logo) || !is_readable($logo)){
			throw new QRCodeOutputException('invalid logo');
		}

		$this->matrix->setLogoSpace(
			$this->options->logoSpaceWidth,
			$this->options->logoSpaceHeight
			// not utilizing the position here
		);

		// there's no need to save the result of dump() into $this->image here
		parent::dump($file);

        switch ($this->getFileExtension($logo)) {
            case 'png': $im = imagecreatefrompng($logo);  break;
            case 'jpeg': case 'jpg': $im = imagecreatefromjpeg($logo); break;
            case 'avif': $im = imagecreatefromavif($logo); break;
            case 'bmp': $im = imagecreatefrombmp($logo); break;
            case 'gd2': $im = imagecreatefromgd2($logo); break;
            case 'gd2part': $im = imagecreatefromgd2part($logo); break;
            case 'gd': $im = imagecreatefromgd($logo); break;
            case 'gif': $im = imagecreatefromgif($logo); break;
            case 'string': $im = imagecreatefromstring($logo); break;
            case 'tga': $im = imagecreatefromtga($logo); break;
            case 'wbmp': $im = imagecreatefromwbmp($logo); break;
            case 'webp': $im = imagecreatefromwebp($logo); break;
            case 'xbm': $im = imagecreatefromxbm($logo); break;
            case 'xpm': $im = imagecreatefromxpm($logo); break;
            default: $im = imagecreatefrompng($logo); break;
        }

		// get logo image size
		$w = imagesx($im);
		$h = imagesy($im);

		// set new logo size, leave a border of 1 module (no proportional resize/centering)
		$lw = ($this->options->logoSpaceWidth - 2) * $this->options->scale;
		$lh = ($this->options->logoSpaceHeight - 2) * $this->options->scale;

		// get the qrcode size
		$ql = $this->matrix->size() * $this->options->scale;

		// scale the logo and copy it over. done!
		imagecopyresampled($this->image, $im, ($ql - $lw) / 2, ($ql - $lh) / 2, 0, 0, $lw, $lh, $w, $h);

		$imageData = $this->dumpImage();

		if($file !== null){
			$this->saveToFile($imageData, $file);
		}

		if($this->options->imageBase64){
			$imageData = 'data:image/'.$this->options->outputType.';base64,'.base64_encode($imageData);
		}

		return $imageData;
	}

    public function getFileExtension($logo)
    {
        $f = explode('.', $logo);

        return end($f);
    }

}

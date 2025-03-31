<?php

namespace app\helpers\qrcode;

use chillerlan\QRCode\{QRCode, QROptions};

class LogoOptions extends QROptions {
	// size in QR modules, multiply with QROptions::$scale for pixel size
	protected int $logoSpaceWidth;
	protected int $logoSpaceHeight;
}
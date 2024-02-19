<?php
require_once  "../vendor/autoload.php";

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

// Create a new instance of QrCode and provide the URL as an argument
$qrCode = new QrCode(
    "http://salabahter.eu/skripte/skripta.php?skripta_id=' . $skripta_id",
    new Encoding('UTF-8')
);

$pisac = new PngWriter();
$rezultat = $pisac->write($qrCode);

header('Content-Type: ' . $rezultat->getMimeType());
echo $rezultat->getString();
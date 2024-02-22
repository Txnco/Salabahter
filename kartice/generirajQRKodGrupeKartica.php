<?php
require_once  "../vendor/autoload.php";

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;

$grupa_id = $_GET['grupa_id'];

// Create a new instance of QrCode and provide the URL as an argument
$qrCode = new QrCode(
    "http://salabahter.eu/kartice/grupa?grupa_id=" . $grupa_id,
    new Encoding('UTF-8')
);

// Set the foreground and background colors
$qrCode->setForegroundColor(new Color(0, 0, 0)); // Black
$qrCode->setBackgroundColor(new Color(255, 255, 255)); // White

$qrCode->setSize(200);
$qrCode->setMargin(0);

$pisac = new PngWriter();
$rezultat = $pisac->write($qrCode);

// Convert the QR code to a data URL
$dataUrl = 'data:' . $rezultat->getMimeType() . ';base64,' . base64_encode($rezultat->getString());

// Echo the QR code image
echo '<img src="' . $dataUrl . '">';
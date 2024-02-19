<?php
require_once  "../vendor/autoload.php";

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;

$skripta_id = $_GET['skripta_id'];

// Create a new instance of QrCode and provide the URL as an argument
$qrCode = new QrCode(
    "http://salabahter.eu/skripte/skripta.php?skripta_id=" . $skripta_id,
    new Encoding('UTF-8')
);

// Set the foreground and background colors
$qrCode->setForegroundColor(new Color(0, 0, 0)); // Black
$qrCode->setBackgroundColor(new Color(255, 255, 255)); // White

$qrCode->setSize(200);

$pisac = new PngWriter();
$rezultat = $pisac->write($qrCode);

// Convert the QR code to a data URL
$dataUrl = 'data:' . $rezultat->getMimeType() . ';base64,' . base64_encode($rezultat->getString());

// Echo the QR code image
echo '<img src="' . $dataUrl . '">';
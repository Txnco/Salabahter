<?php
require_once  "../vendor/autoload.php";

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;

$skripta_id = $_GET['skripta_id'];


$qrCode = new QrCode(
    "http://salabahter.eu/skripte/skripta.php?skripta_id=" . $skripta_id,
    new Encoding('UTF-8')
);


$qrCode->setForegroundColor(new Color(0, 0, 0)); 
$qrCode->setBackgroundColor(new Color(255, 255, 255)); 

$qrCode->setSize(200);

$pisac = new PngWriter();
$rezultat = $pisac->write($qrCode);


$dataUrl = 'data:' . $rezultat->getMimeType() . ';base64,' . base64_encode($rezultat->getString());


echo '<img src="' . $dataUrl . '">';
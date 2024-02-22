<?php
$con = require_once "../ukljucivanje/connection/spajanje.php";
include_once("../ukljucivanje/functions/funkcije.php");
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == "GET") {



    $eposta = $_GET['unosEposte'];

    $zeton = bin2hex(random_bytes(16));

    $zeton_hash = hash('sha256', $zeton);

    $istice = date("Y-m-d H:i:s", time() + 60 * 30);

    $sqlUnosZetona = "UPDATE korisnik SET zeton_lozinke = ?, zeton_istice = ? WHERE email = ?";

    $stmt = $con->prepare($sqlUnosZetona);

    $stmt->bind_param("sss", $zeton_hash, $istice, $eposta);

    $stmt->execute();

    if ($con->affected_rows > 0) {


        $mail = new PHPMailer(true);

        try {

            $poveznicaZaPonovnoPostavljanjeLozinke = "
            
            <a href='salabahter.eu/racun/postavi-lozinku.php?zeton=" . $zeton . "' target='_blank' class='v-button' style='box-sizing: border-box;display: inline-block;text-decoration: none;-webkit-text-size-adjust: none;text-align: center;color: #FFFFFF; background-color: #cd54f2; border-radius: 4px;-webkit-border-radius: 4px; -moz-border-radius: 4px; width:auto; max-width:100%; overflow-wrap: break-word; word-break: break-word; word-wrap:break-word; mso-border-alt: none;font-size: 14px;'>
            <span style='display:block;padding:14px 44px 13px;line-height:120%;'><strong>Ponovno postavite lozinku</strong></span></a>";

            $body = file_get_contents('../assets/css/promjenaLozinkeeposta.html');
            $body = str_replace('{POVEZNICA}', $poveznicaZaPonovnoPostavljanjeLozinke, $body);

            //Server settings
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host       = 'smtp.zoho.eu';  // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;                             // Enable SMTP authentication
            $mail->Username   = 'podrska@salabahter.eu';              // SMTP username
            $mail->Password   = 'Salabahter1!';                  // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Use ::ENCRYPTION_STARTTLS for port 587
            $mail->Port = 465; // Use 587 for TLS                           // TCP port to connect to

            //Recipients
            $mail->setFrom('podrska@salabahter.eu', 'Salabahter');
            $mail->addAddress($eposta, "");     // Add a recipient

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Ponovno postavljanje lozinke';
            $mail->Body    = $body;
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            session_start();

            $_SESSION['poslano'] = true;
            header("Location: zaboravljena-lozinka.php");
            exit();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}

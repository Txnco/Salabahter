<?php

$trenutnaStranica = "račun";
$trenutnaStranica2 = "Zahtjevi";

$putanjaDoPocetne = "../";
$putanjaDoInstruktora = "../instruktori.php";
$putanjaDoSkripta = "../skripte/";
$putanjaDoKartica = "../kartice.php";
$putanjaDoOnama = "../onama.php";

$putanjaDoPrijave = "../racun/prijava.php";
$putanjaDoRegistracije = "../racun/registracija.php";

$putanjaDoRacuna = "../nadzornaploca";
$putanjaDoOdjave = "../racun/odjava.php";

session_start();
$con = require "../ukljucivanje/connection/spajanje.php";
include("../ukljucivanje/functions/funkcije.php");

require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$user = provjeri_prijavu($con); // Provjera prijave
if (!$user) {
    header("Location: ../racun/prijava.php");
    die;
}

$sqlProvjeraInstruktora = "SELECT * FROM instruktori WHERE korisnik_id = {$_SESSION['user_id']}"; // Provjeri da li je korisnik instruktor
$rezultatInstruktor = $con->query($sqlProvjeraInstruktora);
$instruktor = $rezultatInstruktor->fetch_assoc();

$sqlDohvatiSveZahtjeveInstrukcijaZaInstruktora = "SELECT * FROM zahtjevzainstrukcije WHERE instruktor_id = {$instruktor['instruktor_id']}";
$rezultatSviZahtjeviInstrukcijaZaInstruktora = $con->query($sqlDohvatiSveZahtjeveInstrukcijaZaInstruktora);

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    if (isset($_POST['posaljiOdgovor'])) {

        $odgovorTekst = $_POST['odgovorTekst'];
        $nacinKomunikacije = $_POST['nacinKomunikacije'];

        $zahtjev_id = $_POST['zahtjev_id'];
        $predmet = $_POST['predmet'];
        $email = $_POST['email'];



        $sqlObrisiZahtjev = "DELETE FROM zahtjevzainstrukcije WHERE zahtjev_id = $zahtjev_id";
        $con->query($sqlObrisiZahtjev);


        $mail = new PHPMailer(true);

        try {
            $body = file_get_contents('../assets/css/odgovorNaZahtjevZaInstrukcije.html');
            $body = str_replace('{IME}', $user['ime'], $body);
            $body = str_replace('{PREZIME}', $user['prezime'], $body);

            $body = str_replace('{EMAIL}', $user['email'], $body);
            $body = str_replace('{PREBIVALISTE}', $user['prebivaliste'], $body);

            $body = str_replace('{PREDMET}', $predmet, $body);

            $body = str_replace('{PORUKA}', $odgovorTekst, $body);
            $body = str_replace('{KOMUNIKACIJA}', $nacinKomunikacije, $body);

            //Server settings
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host       = 'smtp.zoho.eu';  // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;                             // Enable SMTP authentication
            $mail->Username   = 'instrukcije@salabahter.eu';              // SMTP username
            $mail->Password   = 'Salabahter1!';                  // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Use ::ENCRYPTION_STARTTLS for port 587
            $mail->Port = 465; // Use 587 for TLS                           // TCP port to connect to

            //Recipients
            $mail->setFrom('instrukcije@salabahter.eu', 'Salabahter');
            $mail->addAddress($email, $user['ime']);     // Add a recipient

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Odgovor na zahtjev za instrukcije';
            $mail->Body    = $body;
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();


            header('Location: zahtjevi-za-instrukcije.php');
            exit();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
    if (isset($_POST['odbijZahtjev'])) {
        $odgovorTekst = $_POST['odgovorTekst'];

        $zahtjev_id = $_POST['zahtjev_id'];
        $email = $_POST['email'];

        $sqlObrisiZahtjev = "DELETE FROM zahtjevzainstrukcije WHERE zahtjev_id = $zahtjev_id";
        $con->query($sqlObrisiZahtjev);


        $mail = new PHPMailer(true);

        try {
            $body = file_get_contents('../assets/css/odbijodgovorNaZahtjevZaInstrukcije.html');
            $body = str_replace('{IME}', $user['ime'], $body);
            $body = str_replace('{PREZIME}', $user['prezime'], $body);

            $body = str_replace('{EMAIL}', $user['email'], $body);

            $body = str_replace('{PORUKA}', $odgovorTekst, $body);


            //Server settings
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host       = 'smtp.zoho.eu';  // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;                             // Enable SMTP authentication
            $mail->Username   = 'instrukcije@salabahter.eu';              // SMTP username
            $mail->Password   = 'Salabahter1!';                  // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Use ::ENCRYPTION_STARTTLS for port 587
            $mail->Port = 465; // Use 587 for TLS                           // TCP port to connect to

            //Recipients
            $mail->setFrom('instrukcije@salabahter.eu', 'Salabahter');
            $mail->addAddress($email, $user['ime']);     // Add a recipient

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Odgovor na zahtjev za instrukcije';
            $mail->Body    = $body;
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();


            header('Location: zahtjevi-za-instrukcije.php');
            exit();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Zahtjevi za instrukcije</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <?php include '../assets/css/stiliranjeSporedno.php'; ?> <!-- Sve poveznice za stil web stranice -->

    <link href="../assets/css/nadzornaploca.css" rel="stylesheet">

</head>

<body>

    <?php include '../ukljucivanje/header.php'; ?>

    <div class="container">
        <div class="main-body">



            <div class="row gutters-sm">

                <div class="row">
                    <div class="col d-flex justify-content-start align-items-center mb-2">

                        <a class="btn text" href="index"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="mr-2">
                                <path d="M10.78 19.03a.75.75 0 0 1-1.06 0l-6.25-6.25a.75.75 0 0 1 0-1.06l6.25-6.25a.749.749 0 0 1 1.275.326.749.749 0 0 1-.215.734L5.81 11.5h14.44a.75.75 0 0 1 0 1.5H5.81l4.97 4.97a.75.75 0 0 1 0 1.06Z"></path>
                            </svg>Vrati se na račun</a>
                    </div>
                </div>

                <div class="col-sm">
                    <div class="card">
                        <div class="card-body p-0">
                            <h2 class="text-center mt-3">Zahtjevi za instrukcije</h2>
                            <br>
                            <div class="row m-2 mx-auto">
                                <div class="col-sm-2 text-center my-auto d-none d-sm-block">
                                    <span style="font-size: 1em;">Profilna slika</span>
                                </div>

                                <div class="col-sm-2 text-center my-auto d-none d-sm-block">
                                    <span style="font-size: 1em;">Ime i prezime</span>
                                </div>

                                <div class="col-sm-2 text-center my-auto d-none d-sm-block">
                                    <span style="font-size: 1em;">Predmet</span>
                                </div>

                                <div class="col-sm-2 text-center my-auto d-none d-sm-block">
                                    <span style="font-size: 1em;">Opis potrebe za instrukcijama</span>
                                </div>

                                <div class="col-sm-2 text-center my-auto d-none d-sm-block">
                                    <span style="font-size: 1em;">Predloženi datum i vrijeme</span>
                                </div>


                            </div>
                            <hr class="m-2">
                        </div>

                        <?php if (isset($rezultatSviZahtjeviInstrukcijaZaInstruktora) && $rezultatSviZahtjeviInstrukcijaZaInstruktora->num_rows > 0) :
                            while ($row = $rezultatSviZahtjeviInstrukcijaZaInstruktora->fetch_assoc()) : // Prikaz svih zahtjeva za instrukcije

                                $sqlDohvatiSveInformacijeOzahtjevima = "SELECT zahtjevzainstrukcije.zahtjev_id,korisnik.korisnik_id, korisnik.email, statuskorisnika.status_naziv, korisnik.ime, korisnik.prezime, zahtjevzainstrukcije.opisZahtjeva, zahtjevzainstrukcije.predlozeniDatum, predmeti.naziv_predmeta
                                FROM zahtjevzainstrukcije
                                INNER JOIN korisnik ON zahtjevzainstrukcije.poslaoKorisnik = korisnik.korisnik_id
                                INNER JOIN statuskorisnika ON korisnik.status_korisnika = statuskorisnika.status_id
                                INNER JOIN instruktori ON zahtjevzainstrukcije.instruktor_id = instruktori.instruktor_id
                                INNER JOIN predmeti ON zahtjevzainstrukcije.predmetInstruktora_id = predmeti.predmet_id
                                WHERE instruktori.korisnik_id = {$_SESSION['user_id']}";

                                $rezultat = $con->query($sqlDohvatiSveInformacijeOzahtjevima);
                                $rezultat = $rezultat->fetch_assoc();

                        ?>

                                <div class="p-1">
                                    <div class="row m-2 mx-auto">

                                        <div class="col-6 col-sm-2 text-center my-auto">

                                            <?php

                                            $sqlDohvatiProfilnuSliku = "SELECT slika_korisnika FROM korisnik WHERE korisnik_id = {$rezultat['korisnik_id']}";
                                            $rezultatProfilnaSlika = $con->query($sqlDohvatiProfilnuSliku);
                                            $profilnaSlika = $rezultatProfilnaSlika->fetch_assoc();


                                            if ($profilnaSlika['slika_korisnika'] != null) {
                                                $profilnaSlika['slika_korisnika'] = $profilnaSlika['slika_korisnika'];

                                                echo "<div  class='ml-3' style='width: 100px; height: 100px; overflow: hidden; border-radius: 50%; display: flex; align-items: center; justify-content: center;'><img src='{$profilnaSlika['slika_korisnika']}' alt='Profilna slika' style='width: 100%; height: 100%; object-fit: cover;'  /></div>";
                                            } else {
                                                echo '<img  src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle" width="100px">';
                                            }

                                            ?>

                                        </div>


                                        <!-- Ime i prezime -->
                                        <div class="col-6 col-sm-2 text-center my-auto">
                                            <h6 class="card-text"><a class="link" href="../../profil?korisnik=<?php echo $rezultat['korisnik_id'] ?>"><?php echo $rezultat["ime"] . " " . $rezultat["prezime"] . "<br>" ?></a><?php echo $rezultat["status_naziv"] ?></h6>
                                        </div>

                                        <!-- Predmet -->
                                        <div class="col-6 col-sm-2 text-center my-auto">
                                            <h6 class="card-text"><?php echo $rezultat['naziv_predmeta'] ?></h6>
                                        </div>

                                        <!-- Opis potrebe za instrukcijama -->
                                        <div class="col-6 col-sm-2 text-center my-auto">
                                            <h6 class="card-text"><?php echo $rezultat['opisZahtjeva'] ?></h6>
                                        </div>

                                        <!-- Predloženi datum i vrijeme -->
                                        <div class="col-6 col-sm-2 text-center my-auto">
                                            <h6 class="card-text"><?php
                                                                    $datum = date_create($rezultat['predlozeniDatum']);
                                                                    $formatiranidatum = date_format($datum, 'd.m.Y H:i');
                                                                    echo $formatiranidatum ?></h6>
                                        </div>


                                        <div class="col-6 col-sm-2 text-center my-auto">
                                            <div class="col-12 text-center p-1 my-auto">
                                                <button class="btn btn-racun" data-id="<?php echo $rezultat['korisnik_id'] ?>" data-toggle="modal" data-target="#posaljiOdgovor<?php echo $rezultat['korisnik_id'] ?>">Prihvati</button>
                                            </div>

                                            <div class="col-12 text-center p-1 my-auto">
                                                <button class="btn btn-danger" data-id="<?php echo $rezultat['korisnik_id'] ?>" data-toggle="modal" data-target="#odbijZahtjevModal<?php echo $rezultat['korisnik_id'] ?>">Odbij</button>
                                            </div>


                                            <!-- Modal za slanje opdgovora za instrukcije -->
                                            <div class="modal fade" id="posaljiOdgovor<?php echo $rezultat['korisnik_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="zahtjev" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">

                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="zahtjev">Pošalji odgovor korisniku</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form method="POST">
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col">



                                                                        <div class="form-group">
                                                                            <label for="predmet">Odgovor korisniku za instrukcije</label>
                                                                            <textarea class="form-control" name="odgovorTekst" id="odgovorTekst" maxlength="255" style="max-height: 100px;" placeholder="Napišite odgovor korisniku" required></textarea>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="nacinKomunikacije">Navedi način daljnje komunikacije</label>
                                                                            <textarea class="form-control" id="nacinKomunikacije" maxlength="100" style="max-height: 100px;" name="nacinKomunikacije" placeholder="npr. Broj telefona, email, WhatsApp..." required></textarea>
                                                                        </div>


                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <input type="hidden" name="email" value="<?php echo $rezultat['email']; ?>">
                                                                <input type="hidden" name="predmet" value="<?php echo $rezultat['naziv_predmeta']; ?>">
                                                                <input type="hidden" name="zahtjev_id" value="<?php echo $rezultat['zahtjev_id']; ?>">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Zatvori</button>
                                                                <button id="submit-button" type="submit" class="btn btn-racun" name="posaljiOdgovor">Pošalji odgovor</button>
                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>

                                            </div>



                                            <!-- Modal za slanje odgovora korisniku -->
                                            <div class="modal fade" id="odbijZahtjevModal<?php echo $rezultat['korisnik_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="zahtjev" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">

                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="zahtjev">Pošalji odgovor korisniku</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form method="POST">
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col">

                                                                        <div class="form-group">
                                                                            <label for="predmet">Razlog odbijanja instrukcija</label>
                                                                            <textarea class="form-control" name="odgovorTekst" id="odgovorTekst" maxlength="255" style="max-height: 100px;" placeholder="Napišite odgovor korisniku" required></textarea>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <input type="hidden" name="email" value="<?php echo $rezultat['email']; ?>">

                                                                <input type="hidden" name="zahtjev_id" value="<?php echo $rezultat['zahtjev_id']; ?>">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Zatvori</button>
                                                                <button id="submit-button" type="submit" class="btn btn-racun" name="odbijZahtjev">Pošalji odgovor</button>
                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>

                                            </div>

                                        </div>

                                    </div>
                                </div>

                        <?php
                            endwhile;
                        else :
                            echo "<span class='text m-4'>Trenutno nema zahtjeva</span>";
                        endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>





    <script>
        document.getElementById('submit-button').addEventListener('click', function(event) {
            var button = this;
            setTimeout(function() {
                button.disabled = true;
                button.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Slanje zahtjeva...';
            }, 100);
        });
    </script>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <script src="../assets/js/main.js"></script>

</body>

</html>
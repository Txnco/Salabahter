<?php
session_start();
$trenutnaStranica = "instruktori";

$putanjaDoPocetne = "../";
$putanjaDoInstruktora = "../instruktori.php";
$putanjaDoSkripta = "../skripte/";
$putanjaDoKartica = "../kartice.php";
$putanjaDoOnama = "../onama.php";

$putanjaDoPrijave = "../racun/prijava.php";
$putanjaDoRegistracije = "../racun/registracija.php";

$putanjaDoRacuna = "../nadzornaploca";
$putanjaDoOdjave = "../racun/odjava.php";

$con = require "../ukljucivanje/connection/spajanje.php";
include("../ukljucivanje/functions/funkcije.php");
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$korisnikID = $_GET['korisnik']; // ID korisnika kojeg gledamo


$user = provjeri_prijavu($con); // Provjeri da li je korisnik prijavljen
if ($user) { // Ako je korisnik prijavljen provjeri da li gleda svoj profil, ako gleda svoj profil preusmjeri ga na dashboard
  $profileViewId = $korisnikID;
  if ($user['korisnik_id'] == $profileViewId) {
    header("Location: ../nadzornaploca");
    die;
  }
}

check_privilegeUser($con); // Provjeri da li je korisnik administrator
if (isset($_SESSION["isAdmin"])) {

  $administartor = $_SESSION["isAdmin"];
}


// Dohvati korisnika po ID-u 
$sqlOdabraniKorisnik = "SELECT korisnik.korisnik_id,  ime,prezime,email,adresa,prebivaliste,naziv_grada, grad_id, status_naziv FROM korisnik, statuskorisnika, gradovi WHERE korisnik.korisnik_id={$korisnikID} AND  korisnik.status_korisnika=statuskorisnika.status_id AND korisnik.mjesto=gradovi.grad_id";
$rezultatOdabraniKorisnik = $con->query($sqlOdabraniKorisnik);

$korisnik = $rezultatOdabraniKorisnik->fetch_assoc(); // Dohvati korisnika iz baze 

$sqlProvjeraInstruktora = "SELECT * FROM instruktori WHERE korisnik_id =  {$korisnikID}"; // Provjeri da li je korisnik instruktor
$rezultatInstruktor = $con->query($sqlProvjeraInstruktora);
$instruktor = $rezultatInstruktor->fetch_assoc();
if ($rezultatInstruktor->num_rows > 0) { // Ako je korisnik instruktor onda se prikažu predmeti koje predaje i njegove skripte
  $korisnikJeInstruktor = true;
} else {
  $korisnikJeInstruktor = false;
}

if ($korisnikJeInstruktor) { // Ako je korisnik instruktor onda se dohvaćaju predmeti koje predaje i njegove skripte

  // Dohvati predmete koje predaje instruktor
  $sqlInstruktoroviPredmeti = "SELECT naziv_predmeta, predmeti.predmet_id FROM instruktorovipredmeti,predmeti WHERE instruktorovipredmeti.predmet_id=predmeti.predmet_id AND instruktorovipredmeti.instruktor_id = {$instruktor['instruktor_id']}";
  $rezultatInstruktoroviPredmeti = $con->query($sqlInstruktoroviPredmeti);

  // Dohvati skripte instruktora
  $sqlSkripteKorisnika = "SELECT * FROM skripte WHERE korisnik_id = {$korisnikID}";
  $resultSkripteKorisnika = $con->query($sqlSkripteKorisnika);
  if ($resultSkripteKorisnika->num_rows > 0) {
    $korisnikImaSkripte = true;
  } else {
    $korisnikImaSkripte = false;
  }
}

$sqlDohvatiRecenzije = "SELECT * FROM recenzije WHERE zaKorisnika = {$korisnikID}"; // Dohvati recenzije korisnika
$rezultatRecenzije = $con->query($sqlDohvatiRecenzije);
if ($rezultatRecenzije->num_rows > 0) { // Ako je korisnik instruktor onda se prikažu predmeti koje predaje i njegove skripte
  $imaRecenzije = true;
} else {
  $imaRecenzije = false;
}

if (isset($user)) {
  $sqlAkojeKorisnikVecNapisaoRecenziju = "SELECT * FROM recenzije WHERE odKorisnika = {$_SESSION['user_id']} AND zaKorisnika = {$korisnikID}";
  $rezultatAkojeKorisnikVecNapisaoRecenziju = $con->query($sqlAkojeKorisnikVecNapisaoRecenziju);
  if ($rezultatAkojeKorisnikVecNapisaoRecenziju->num_rows > 0) {
    $korisnikVecNapisaoRecenziju = true;
  } else {
    $korisnikVecNapisaoRecenziju = false;
  }
}





if ($_SERVER['REQUEST_METHOD'] == "POST") {
  if (isset($_POST['prijavaRecenzije'])) {

    $prijavioKorisnik = $_SESSION['user_id'];
    $prijavljenaRecenzija = $_POST['prijavljenaRecenzija'];
    $razlogPrijave = $_POST['razlogPrijave'];

    $stmt = $con->prepare("INSERT INTO prijavarecenzije (prijavljenaRecenzija, prijavioKorisnik, opisPrijave) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $prijavljenaRecenzija, $prijavioKorisnik, $razlogPrijave);

    $stmt->execute();
    if ($stmt->num_rows > 0) {
      $_SESSION['poruka'] = 'Recenzija je već prijavljena!';
      $_SESSION['tipPoruke'] = false;
    } else {
      $_SESSION['poruka'] = 'Recenzija je uspešno prijavljena!';
      $_SESSION['tipPoruke'] = true;
    }
    $stmt->close();
  }
  if (isset($_POST['obrisiPredmet'])) {

    $predmetId = $_POST['predmetId'];


    $stmt = $con->prepare("DELETE FROM instruktorovipredmeti WHERE predmet_id = ? AND instruktor_id = {$instruktor['instruktor_id']}");
    $stmt->bind_param("i", $predmetId);
    $stmt->execute();


    header("Location: index.php?korisnik=" . $korisnikID);
    die;
  }
  if (isset($_POST['upisPromjena'])) {
    $emailPromjena = $_POST['emailPromjena'];
    $sqlPromjenaEmail = "UPDATE korisnik SET email = '{$emailPromjena}' WHERE korisnik_id = {$korisnikID}";
    $con->query($sqlPromjenaEmail);
    header("Location: index.php?korisnik=" . $korisnikID);
    die;
  }
  if (isset($_POST['zahtjevZaInstrukcije'])) {

    $instruktorID = $_POST['instruktor'];
    $poslaoKorisnik = $_POST['korisnik'];

    $ime = $_POST['ime'];
    $prezime = $_POST['prezime'];
    $email = $_POST['email'];

    list($predmet_id, $naziv_predmeta) = explode('|', $_POST['predmet']);
    $opisZahtjeva = $_POST['opisZahtjeva'];
    $datum = $_POST['datum'];


    if (!empty($datum)) {
      $format = DateTime::createFromFormat('d.m.Y H:i', $datum);
      $formatiraniDatum = $format->format('Y-m-d H:i:s');
    } else {
      $formatiraniDatum = null; // or any default value
    }

    $sqlUpisiZahtjev = "INSERT INTO zahtjevzainstrukcije (poslaoKorisnik, instruktor_id, predmetInstruktora_id, opisZahtjeva, predlozeniDatum) VALUES ('$poslaoKorisnik', '$instruktorID', '$predmet_id', '$opisZahtjeva', '$formatiraniDatum')";
    $con->query($sqlUpisiZahtjev);



    $mail = new PHPMailer(true);

    try {
      $body = file_get_contents('../assets/css/zahtjevZaInstrukcije.html');
      $body = str_replace('{IME}', $ime, $body);
      $body = str_replace('{PREZIME}', $prezime, $body);

      $body = str_replace('{PREDMET}', $naziv_predmeta, $body);
      $body = str_replace('{OPIS}', $opisZahtjeva, $body);
      $body = str_replace('{DATUM}', $formatiraniDatum, $body);
      $naziv_predmeta = strtolower($naziv_predmeta);
      $body = str_replace('{PREDMETnaslov}', $naziv_predmeta, $body);

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
      $mail->addAddress($email, $ime);     // Add a recipient

      // Content
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = 'Novi zahtjev za instrukcije';
      $mail->Body    = $body;
      $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

      $mail->send();

      $_SESSION['verifikacija'] = time();
      $_SESSION['kodPoslan'] = true;


      header('Location: index?korisnik=' . $korisnikID);
      exit();
    } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }


    header("Location: index.php?korisnik=" . $korisnikID);
    die;
  }
}



?>

<!DOCTYPE html>
<html lang="hr">

<head>

  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Pregled profila</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <?php include '../assets/css/stiliranjeSporedno.php'; ?> <!-- Sve poveznice za stil web stranice -->

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Ikone -->

  <link href="../assets/css/recenzije.css" rel="stylesheet">

  <link href="../assets/css/nadzornaploca.css" rel="stylesheet">


</head>

<body>

  <?php include '../ukljucivanje/header.php'; ?>



  <div class="container">
    <div class="main-body ">


      <div class="row gutters-sm">
        <div class="col-md-4 mb-3 ">
          <div class="card">
            <div class="card-body">
              <div class="d-flex flex-column align-items-center text-center">


                <?php

                $sqlDohvatiProfilnuSliku = "SELECT slika_korisnika FROM korisnik WHERE korisnik_id = {$korisnikID}";
                $rezultatProfilnaSlika = $con->query($sqlDohvatiProfilnuSliku);
                $profilnaSlika = $rezultatProfilnaSlika->fetch_assoc();


                if ($profilnaSlika['slika_korisnika'] != null) {
                  $profilnaSlika['slika_korisnika'] = "../nadzornaploca/" . $profilnaSlika['slika_korisnika'];

                  echo "<div  style='width: 150px; height: 150px; overflow: hidden; border-radius: 50%; display: flex; align-items: center; justify-content: center;'>
    <img src='{$profilnaSlika['slika_korisnika']}' alt='Profilna slika' style='width: 100%; height: 100%; object-fit: cover;' />
  </div>";
                } else {
                  echo '<img id="profile-pic" src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle" width="150">';
                }

                ?>

                <div class="mt-3">
                  <!-- Ispis podataka o korisniku -->

                  <h4> <?php echo $korisnik["ime"] . " " . $korisnik["prezime"] ?> </h4> <!-- Ispis korisnikova imena i prezimena -->

                  <?php if ($korisnikJeInstruktor) : // Ako je korisnik instruktor, ispiše se natpis Instruktor 
                  ?>
                    <label class="text">Instruktor</label>
                  <?php endif; ?>

                  <p class="text-secondary mb-1">
                    <?php echo $korisnik['status_naziv']; ?>
                  </p>

                  <p class="text-muted font-size-sm"><?php echo $korisnik["adresa"] . ",  ";
                                                      echo $korisnik['prebivaliste']; ?>
                  </p>

                  <?php

                  if (isset($_SESSION['user_id'])) {
                    if($instruktor){

                      $sqlProvjeraZahtjevaZaInstrukcije = "SELECT * FROM zahtjevzainstrukcije WHERE poslaoKorisnik = {$_SESSION['user_id']} AND instruktor_id = {$instruktor['instruktor_id']}";
                      $rezultatProvjeraZahtjevaZaInstrukcije = $con->query($sqlProvjeraZahtjevaZaInstrukcije);
                      
                      if ($rezultatProvjeraZahtjevaZaInstrukcije->num_rows > 0) {
                        $korisnikVecPoslaoZahtjevZaInstrukcije = true;
                      } else if ($rezultatProvjeraZahtjevaZaInstrukcije->num_rows == 0) {
                        $korisnikVecPoslaoZahtjevZaInstrukcije = false;
                      }
                    }
                  }
                  if (isset($_SESSION['user_id']) && $instruktor && !$korisnikVecPoslaoZahtjevZaInstrukcije) {

                  ?>
                    <button class="btn btn-racun" data-id="<?php echo $instruktor['instruktor_id'] ?>" data-toggle="modal" data-target="#posaljiZahtjevZaInstukcije<?php echo $instruktor['instruktor_id'] ?>">Pošalji zahtjev za instrukcije</button>

                    <!-- Modal za slanje zahtjeva za instrukcije -->
                    <div class="modal fade" id="posaljiZahtjevZaInstukcije<?php echo $instruktor['instruktor_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="zahtjev" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">

                          <div class="modal-header">
                            <h5 class="modal-title" id="zahtjev">Pošaljite zahtjev za instrukcije</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <form method="POST">
                            <div class="modal-body">
                              <div class="row">
                                <div class="col">

                                  <div class="d-flex flex-column align-items-center text-center">


                                    <?php

                                    $sqlDohvatiProfilnuSliku = "SELECT slika_korisnika FROM korisnik WHERE korisnik_id = {$korisnikID}";
                                    $rezultatProfilnaSlika = $con->query($sqlDohvatiProfilnuSliku);
                                    $profilnaSlika = $rezultatProfilnaSlika->fetch_assoc();


                                    if ($profilnaSlika['slika_korisnika'] != null) {
                                      $profilnaSlika['slika_korisnika'] = "../nadzornaploca/" . $profilnaSlika['slika_korisnika'];

                                      echo "<div  style='width: 75px; height: 75px; overflow: hidden; border-radius: 50%; display: flex; align-items: center; justify-content: center;'>
<img src='{$profilnaSlika['slika_korisnika']}' alt='Profilna slika' style='width: 100%; height: 100%; object-fit: cover;' />
</div>";
                                    } else {
                                      echo '<img id="profile-pic" src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle" width="75">';
                                    }

                                    ?>

                                    <div class="mt-3">
                                      <!-- Ispis podataka o korisniku -->

                                      <h4> <?php echo $korisnik["ime"] . " " . $korisnik["prezime"] ?> </h4> <!-- Ispis korisnikova imena i prezimena -->


                                      <p class="text-secondary mb-1">
                                        <label class="text">Instruktor - </label> <?php echo $korisnik['status_naziv']; ?>
                                      </p>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label for="predmet">Odaberi predmet za koji želite instrukcije</label>
                                    <select class="form-control" id="predmet" name="predmet" required>
                                      <?php
                                      while ($row = $rezultatInstruktoroviPredmeti->fetch_assoc()) : ?>
                                        <option value="<?php echo $row['predmet_id'] . '|' . $row['naziv_predmeta']; ?>" required><?php echo $row['naziv_predmeta']; ?> </option>
                                      <?php endwhile; ?>
                                    </select>
                                  </div>
                                  <div class="form-group">
                                    <label for="opisZahtjeva">Opis potrebe za instrukcijama</label>
                                    <textarea class="form-control" id="opisZahtjeva" maxlength="255" style="max-height: 100px;" name="opisZahtjeva" required></textarea>
                                  </div>

                                  <div class="form-group">
                                    <label for="datum">Predložite datum i vrijeme instrukcija</label>
                                    <input type="text" class="form-control" id="datum" name="datum"  readonly required>
                                  </div>

                                </div>
                              </div>
                            </div>


                            <input type="hidden" id="email" name="email" value="<?php echo $korisnik["email"] ?>">
                            <input type="hidden" id="ime" name="ime" value="<?php echo $user["ime"] ?>">
                            <input type="hidden" id="prezime" name="prezime" value="<?php echo $user["prezime"] ?>">
                            <input type="hidden" id="instruktor" name="instruktor" value="<?php echo $instruktor['instruktor_id'] ?>">
                            <input type="hidden" id="korisnik" name="korisnik" value="<?php echo $_SESSION['user_id'] ?>">
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Odustani</button>
                              <button type="submit" class="btn gumb" name="zahtjevZaInstrukcije">Pošalji zahtjev</button>
                            </div>

                          </form>
                        </div>
                      </div>
                    </div>

                  <?php
                  } else if (isset($_SESSION['user_id']) && $instruktor && $korisnikVecPoslaoZahtjevZaInstrukcije) {

                    echo "<button class='btn gumb'>Zahtjev je poslan!</button>";
                  } ?>

                </div>
              </div>
            </div>
          </div>

          <div class="card mt-3">
            <div class="card-body ">
              <div class="row">
                <div class="col-12 ">
                  <div class="content text-center">

                    <div class="ratings">
                      <?php if ($imaRecenzije) : ?>
                        <?php

                        $sqlDohvatiOcjene = "SELECT ROUND(AVG(ocjena),1) as prosjek, COUNT(ocjena) as brojOcjena FROM recenzije WHERE zaKorisnika = {$korisnikID} ";
                        $rezultatOcjene = $con->query($sqlDohvatiOcjene);
                        $ocjene = $rezultatOcjene->fetch_assoc();
                        $ocjene = floatval($ocjene['prosjek']);

                        $sqlBrojRecenzija = "SELECT COUNT(ocjena) as brojOcjena FROM recenzije WHERE zaKorisnika = {$korisnikID} ";
                        $rezultatBrojRecenzija = $con->query($sqlBrojRecenzija);
                        $brojRecenzija = $rezultatBrojRecenzija->fetch_assoc();
                        $brojRecenzija = $brojRecenzija['brojOcjena'];

                        ?>

                        <span style="font-size: 2em;"><?php echo $ocjene; ?></span><span>/5 <br></span>

                        <?php
                        for ($i = 1; $i <= 5; $i++) {
                          if ($i <= $ocjene) {
                            // Full star
                            echo '<i class="fa fa-star" style="color:gold;"></i>';
                          } else {
                            // Empty star
                            echo '<i class="fa fa-star-o"></i>';
                          }
                        }
                        ?>

                        <div class="rating-text">
                          <span><?php echo $brojRecenzija ?> recenzija</span>
                          <br>
                          <?php
                          $sqlCountRecenzije = "SELECT COUNT(*) as count FROM recenzije WHERE zaKorisnika = {$korisnikID}";
                          $resultCountRecenzije = $con->query($sqlCountRecenzije);
                          $countRecenzije = $resultCountRecenzije->fetch_assoc();

                          if ($countRecenzije['count'] > 6) :
                          ?>
                            <a class="link" href="../recenzije/sve.php?korisnik=<?php echo $korisnikID; ?>">Prikaži sve recenzije</a>
                          <?php
                          endif;
                          ?>
                        </div>

                      <?php endif; ?>

                      <?php if (isset($_SESSION['user_id']) && !$korisnikVecNapisaoRecenziju) : ?>

                        <div class="row mt-2">
                          <div class="col ">
                            <a class="mr-auto text-secondary" href="../recenzije/?korisnik=<?php echo $korisnikID ?>">Napiši recenziju za <?php
                                                                                                                                          if ($korisnik['status_naziv'] == "Instruktor") {
                                                                                                                                            echo "instruktora";
                                                                                                                                          } else if ($korisnik['status_naziv'] == "Student") {
                                                                                                                                            echo "studenta";
                                                                                                                                          } else if ($korisnik['status_naziv'] == "Profesor") {
                                                                                                                                            echo "profesora";
                                                                                                                                          } else {
                                                                                                                                            echo "korisnika";
                                                                                                                                          }


                                                                                                                                          ?></a>
                            <svg class="ml-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                              <path d="M8.72 18.78a.75.75 0 0 1 0-1.06L14.44 12 8.72 6.28a.751.751 0 0 1 .018-1.042.751.751 0 0 1 1.042-.018l6.25 6.25a.75.75 0 0 1 0 1.06l-6.25 6.25a.75.75 0 0 1-1.06 0Z"></path>
                            </svg>
                          </div>
                        </div>



                      <?php
                      else : "";
                      endif; ?>


                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>



        </div>

        <div class="col-md-8">
          <div class="card mb-3">
            <div class="card-body">

              <form method="POST">
                <div class="row">

                  <div class="col-sm-3">
                    <h6 class="mb-0">Ime</h6>
                  </div>
                  <div class="col-sm-5 text-secondary">

                    <label type="text"><?php echo $korisnik["ime"] . " " . $korisnik["prezime"] // Ipis kosinikova imena 
                                        ?></label>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-3">
                    <h6 class="mb-0">Email</h6>
                  </div>

                  <?php if (isset($administartor)) : ?> <!-- Administrator može mijenjati email korisnicima -->
                    <div class="col-sm-9 text-secondary">
                      <input type="text" class="form-control" name="emailPromjena" id="emailPromjena" value="<?php echo $korisnik["email"] ?>">
                    </div>
                  <?php else : ?>
                    <div class="col-sm-9 text-secondary">
                      <label type="text"><?php echo $korisnik["email"] ?></label>
                    </div>
                  <?php endif; ?>

                </div>

                <hr>
                <div class="row">
                  <div class="col-sm-3">
                    <h6 class="mb-0">Adresa</h6>
                  </div>
                  <div class="col-sm-7 text-secondary">
                    <label type="text"><?php echo $korisnik["adresa"] . ", " .  $korisnik["prebivaliste"] // Ispis koriskinove adrese stanovanja 
                                        ?></label>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-3">
                    <h6 class="mb-0">Obližnji grad</h6>
                  </div>
                  <div class="col-sm-9 text-secondary">
                    <label type="text"><?php echo $korisnik["naziv_grada"]; // Ispis grada iz kojeg je korisnik
                                        ?></label>
                  </div>
                </div>

                <?php if (isset($administartor)) : ?>
                  <div class="row">
                    <div class="col-sm-12">
                      <button class="btn btn-racun" name="upisPromjena" type="submit">Spremi promjene</button>
                    </div>
                  </div>
                <?php endif; ?>
              </form>
            </div>
          </div>



          <?php if ($korisnikJeInstruktor) : ?> <!-- Ako je korisnik instruktor onda se prikazuju predmeti koje predaje -->
            <div class="row gutters-sm">
              <div class="col-sm-6 mb-3">
                <div class="card h-100">
                  <div class="card-body">
                    <h6 class="d-flex align-items-center mb-3">Predmeti</h6> <!-- Ispis predmeta koje predaje instruktor -->
                    <?php
                    $rezultatInstruktoroviPredmeti->data_seek(0);
                    $brojReda = $rezultatInstruktoroviPredmeti->num_rows;
                    $sakrijTipkuIzbrisi = $brojReda <= 1;

                    while ($row = $rezultatInstruktoroviPredmeti->fetch_assoc()) : ?>

                      <small>

                        <?php

                        if (isset($administartor) && !$sakrijTipkuIzbrisi) : ?>
                          <button class='btn btn-link text-danger' data-id="<?php echo $row['predmet_id'] ?>" data-toggle="modal" data-target="#obrisiPredmetModal<?php echo $row['predmet_id'] ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                              <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
                            </svg>
                          </button>

                          <!-- Modal za brisanje predmeta -->
                          <div class="modal fade" id="obrisiPredmetModal<?php echo $row['predmet_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="obrisiPredmetLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="obrisiPredmetLabel">Jeste li sigurni da želite obrisati ovaj instruktorov predmet?</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <form method="POST">
                                  <input type="hidden" id="predmetId" name="predmetId" value="<?php echo $row['predmet_id'] ?>">
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Odustani</button>
                                    <button type="submit" class="btn btn-danger" name="obrisiPredmet">Obriši</button>
                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>

                        <?php endif; ?>

                        <?php echo $row['naziv_predmeta']; ?>
                      </small>

                      <div class="progress mb-3" style="height: 5px">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 100%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>

                      </div>

                    <?php endwhile; ?>

                  </div>
                </div>
              </div>

              <div class="col-sm-6 mb-3">
                <div class="card h-100">
                  <div class="card-body">
                    <h6 class="d-flex align-items-center mb-3">Skripte</h6> <!-- Ispis skripti koje je instruktor dodao -->
                    <div class="row overflow-auto" style="max-height: 300px;">
                      <div class="col-sm mb-3">
                        <?php
                        if ($korisnikImaSkripte) :
                          while ($row = $resultSkripteKorisnika->fetch_assoc()) : ?>

                            <div class="card-body">
                              <small><?php echo $row['naziv_skripte']; ?></small>
                              <div class="progress mb-3" style="height: 5px">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                              </div>
                              <a href="<?php echo "../skripte/" . $row['skripta_putanja']; ?>" class="btn btn-primary" download>Preuzmi</a>
                            </div>

                        <?php endwhile;
                        else : echo "Instruktor još nije dodao skripte";
                        endif; ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <?php if ($imaRecenzije) : ?>
        <div class="row">
          <div class="col-md-12">
            <div class="card mb-3">
              <div class="card-body">
                <div class="row">
                  <div class="col d-flex justify-content-center">
                    <h6 class="mb-0">Recenzije</h6>
                  </div>
                </div>
                <hr>

                <div class="row">

                  <?php
                  $sqlCountRecenzije = "SELECT COUNT(*) as count FROM recenzije WHERE zaKorisnika = {$korisnikID}";
                  $resultCountRecenzije = $con->query($sqlCountRecenzije);
                  $countRecenzije = $resultCountRecenzije->fetch_assoc();

                  if ($countRecenzije['count'] > 6) :
                  ?>
                    <a class="link" href="../recenzije/sve.php?korisnik=<?php echo $korisnikID; ?>">Prikaži sve recenzije</a>
                  <?php
                  endif;
                  ?>

                  <?php


                  $sqlSveRecnezije = "SELECT * FROM recenzije WHERE zaKorisnika = {$korisnikID} LIMIT 6";
                  $rezultatSveRecenzije = $con->query($sqlSveRecnezije);
                  if ($rezultatSveRecenzije->num_rows > 0) :
                    while ($red = $rezultatSveRecenzije->fetch_assoc()) :
                      $sqlPrijavljenaRecenzija = "SELECT * FROM prijavarecenzije WHERE prijavljenaRecenzija = {$red['recenzija_id']}";
                      $rezultatPrijavljenaRecenzija = $con->query($sqlPrijavljenaRecenzija);
                      if ($rezultatPrijavljenaRecenzija->num_rows > 0) {
                        $prijavljenaRecenzija = false;
                      } else {
                        $prijavljenaRecenzija = true;
                      }

                      $sqlKorisnik = "SELECT korisnik_id, korisnik.ime, korisnik.prezime, recenzije.ocjena, recenzije.komentar 
                        FROM korisnik 
                        JOIN recenzije ON korisnik.korisnik_id = recenzije.odKorisnika 
                        WHERE recenzije.recenzija_id = {$red['recenzija_id']}";
                      $rezultatKorisnik = $con->query($sqlKorisnik);
                      $korisnik = $rezultatKorisnik->fetch_assoc();

                  ?>
                      <div class="col-sm-4">
                        <div class="card mt-4 ">
                          <div class="card-body">
                            <div class="row">
                              <div class="col">
                                <h5><a class="link" href="?korisnik=<?php echo $korisnik['korisnik_id'] ?>"><?php echo $korisnik['ime'] . " " . $korisnik['prezime'] ?></a></h5>

                              </div>
                            </div>
                            <div class="row">
                              <div class="col">
                                <?php
                                for ($i = 1; $i <= 5; $i++) {
                                  if ($i <= $korisnik['ocjena']) {
                                    echo '<i class="fa fa-star" style="color:gold;"></i>';
                                  } else {
                                    echo '<i class="fa fa-star-o"></i>';
                                  }
                                }
                                ?>

                              </div>
                            </div>
                            <div class="row">
                              <div class="col">
                                <p><?php echo $korisnik['komentar'] ?></p>

                              </div>
                            </div>

                            <?php if (isset($_SESSION['user_id']) && $prijavljenaRecenzija) : ?>
                              <div class="row">
                                <div class="col d-flex justify-content-end">
                                  <a type="button" id="otvoriPrijavu" class="text text-danger" style="font-size: 0.9rem;" data-toggle="modal" data-target="#prijavaRecenzije<?php echo $red['recenzija_id']; ?>">Prijavi recenziju!</a>
                                </div>
                              </div>
                            <?php elseif (isset($_SESSION['user_id']) && !$prijavljenaRecenzija) : ?>
                              <div class="row">
                                <div class="col d-flex justify-content-end">
                                  <a class="text text-success" style="font-size: 0.9rem;">Recenzija prijavljena!</a>
                                </div>
                              </div>
                            <?php endif; ?>

                            <!-- Modal -->
                            <div class="modal fade" id="prijavaRecenzije<?php echo $red['recenzija_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="prijavaRecenzijeTitle" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Prijava recenzije</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                    <form method="POST">

                                      <h5><?php echo $korisnik['ime'] . " " . $korisnik['prezime'] ?></h5>
                                      <div class="row">
                                        <div class="col">
                                          <?php
                                          for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $korisnik['ocjena']) {
                                              echo '<i class="fa fa-star" style="color:gold;"></i>';
                                            } else {
                                              echo '<i class="fa fa-star-o"></i>';
                                            }
                                          }
                                          ?>

                                        </div>
                                      </div>
                                      <p><?php echo $korisnik['komentar'] ?></p>

                                      <div class="form-group">
                                        <label for="razlogPrijave" class="col-form-label">Razlog prijave:</label>
                                        <textarea class="form-control" id="razlogPrijave" name="razlogPrijave"></textarea>
                                        <input name="prijavljenaRecenzija" value="<?php echo $red['recenzija_id'] ?>" hidden>
                                      </div>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Zatvori</button>
                                    <button type="submit" class="btn btn-danger" name="prijavaRecenzije">Prijavi</button>
                                  </div>


                                  </form>
                                </div>
                              </div>
                            </div>


                          </div>
                        </div>
                      </div>
                  <?php endwhile;
                  else : echo "Nema recenzija";
                  endif; ?>



                </div>
              </div>
            </div>
          </div>

        </div>
      <?php endif; ?>

    </div>
  </div>


  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script> <!-- Birač za datum i vrijeme -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css" />

  <script>
    // Birač za datum i vrijeme
    $.datetimepicker.setLocale('hr');
    $(document).ready(function() {
      var now = new Date();
      var formattedNow = moment(now).format('DD.MM.GGGG HH:mm');
      $('#datum').attr('placeholder', formattedNow);

      $('#datum').datetimepicker({
        format: 'd.m.Y H:i',
        dayOfWeekStart: 1,
        step: 15,
        hours12: false
      });
    });
  </script>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <script src="../assets/js/main.js"></script>

</body>

</html>
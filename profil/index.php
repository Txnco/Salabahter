<?php
session_start();
$putanjaDoPocetna = '../';
$putanjaDoSkripta = "../skripta.php";
$putanjaDoInstruktora = '../instruktori.php';

$pathToLogin = "../account/login.php";
$pathToRegister = "../account/register.php";
$pathToRacun = "../dashboard";
$pathToLogout = "../account/logout.php";

$con = require "../includes/connection/spajanje.php";
include("../includes/functions/funkcije.php");


$korisnikID = $_GET['korisnik']; // ID korisnika kojeg gledamo


$user = provjeri_prijavu($con); // Provjeri da li je korisnik prijavljen
if ($user) { // Ako je korisnik prijavljen provjeri da li gleda svoj profil, ako gleda svoj profil preusmjeri ga na dashboard
  $profileViewId = $korisnikID;
  if ($user['korisnik_id'] == $profileViewId) {
    header("Location: ../dashboard");
    die;
  }
}

// Dohvati korisnika po ID-u 
$sqlOdabraniKorisnik = "SELECT korisnik.korisnik_id,  ime,prezime,email,adresa,naziv_grada, status_naziv FROM korisnik, statuskorisnika, gradovi WHERE korisnik.korisnik_id={$korisnikID} AND  korisnik.status_korisnika=statuskorisnika.status_id AND korisnik.mjesto=gradovi.grad_id";
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
  $sqlInstruktoroviPredmeti = "SELECT naziv_predmeta FROM instruktorovipredmeti,predmeti WHERE instruktorovipredmeti.predmet_id=predmeti.predmet_id AND instruktorovipredmeti.instruktor_id = {$instruktor['instruktor_id']}";
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



?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Pregled profila</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <?php include '../assets/css/stiliranjeSporedno.php'; ?> <!-- Sve poveznice za stil web stranice -->

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Ikone -->

  <link href="../assets/css/dashboard.css" rel="stylesheet">
  <link href="../assets/css/recenzije.css" rel="stylesheet">

</head>

<body>

  <?php include '../includes/header.php'; ?>

  <div class="container">
    <div class="main-body">

      <!-- Breadcrumb -->
      <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="../">Početna</a></li>
          <li class="breadcrumb-item active"><a href="javascript:void(0)" aria-current="page">Račun</a></li>
        </ol>
      </nav>
      <!-- /Breadcrumb -->

      <div class="row gutters-sm">
        <div class="col-md-4 mb-3 ">
          <div class="card">
            <div class="card-body">
              <div class="d-flex flex-column align-items-center text-center">
                <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle" width="150">
                <div class="mt-3">
                  <!-- Ispis podataka o korisniku -->
                  <h4> <?php echo $korisnik["ime"] . " " . $korisnik["prezime"] ?> </h4>
                  <p class="text-secondary mb-1">
                    <?php echo $korisnik['status_naziv']; ?></p>
                  <p class="text-muted font-size-sm"><?php echo $korisnik["adresa"] . ",  ";
                                                      echo $korisnik['naziv_grada']; ?></p>

                  <?php if ($korisnikJeInstruktor) : // Ako je korisnik instruktor, ispiše se natpis Instruktor 
                  ?>
                    <label class="text">Instruktor</label>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>

          <?php if ($imaRecenzije) : ?>
            <div class="card mt-3">
              <div class="card-body ">
                <div class="row">
                  <div class="col-12 ">
                    <div class="content text-center">

                      <div class="ratings">
                        <?php

                        $sqlDohvatiOcjene = "SELECT ROUND(AVG(ocjena),1) as prosjek, COUNT(ocjena) as brojOcjena FROM recenzije WHERE zaKorisnika = {$korisnikID}";
                        $rezultatOcjene = $con->query($sqlDohvatiOcjene);
                        $ocjene = $rezultatOcjene->fetch_assoc();
                        $ocjene = floatval($ocjene['prosjek']);

                        $sqlBrojRecenzija = "SELECT COUNT(ocjena) as brojOcjena FROM recenzije WHERE zaKorisnika = {$korisnikID}";
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

                        </div>

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php endif; ?>

          <div class="card mt-3">
            <div class="card-body ">
              <div class="row">
                <div class="col-17 d-flex justify-content-center align-items-center">
                  <?php if (isset($_SESSION['user_id'])) : ?>
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
                  <?php endif; ?>
                </div>
              </div>


            </div>
          </div>
        </div>

        <div class="col-md-8">
          <div class="card mb-3">
            <div class="card-body">

              <div class="row">
                <div class="col-sm-3">
                  <h6 class="mb-0">Ime</h6>
                </div>
                <div class="col-sm-5 text-secondary">
                  <label type="text"><?php echo $korisnik["ime"] // Ipis kosinikova imena 
                                      ?></label>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-sm-3">
                  <h6 class="mb-0">Prezime</h6>
                </div>
                <div class="col-sm-5 text-secondary">
                  <label type="text"><?php echo $korisnik["prezime"] // Ispis kosinikova prezimena 
                                      ?></label>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-sm-3">
                  <h6 class="mb-0">Email</h6>
                </div>
                <div class="col-sm-9 text-secondary">
                  <label type="text"><?php echo $korisnik["email"] // Ispis korisnikove elektroničke pošte
                                      ?></label>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-sm-3">
                  <h6 class="mb-0">Phone</h6>
                </div>
                <div class="col-sm-9 text-secondary">
                  Ako ocemo dodamo
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-sm-3">
                  <h6 class="mb-0">Adresa</h6>
                </div>
                <div class="col-sm-9 text-secondary">
                  <label type="text"><?php echo $korisnik["adresa"] // Ispis koriskinove adrese stanovanja
                                      ?></label>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-sm-3">
                  <h6 class="mb-0">Grad</h6>
                </div>
                <div class="col-sm-9 text-secondary">
                  <label type="text"><?php echo $korisnik["naziv_grada"]; // Ispis grada iz kojeg je korisnik
                                      ?></label>
                </div>
              </div>

            </div>
          </div>



          <?php if ($korisnikJeInstruktor) : ?> <!-- Ako je korisnik instruktor onda se prikazuju predmeti koje predaje -->
            <div class="row gutters-sm">
              <div class="col-sm-6 mb-3">
                <div class="card h-100">
                  <div class="card-body">
                    <h6 class="d-flex align-items-center mb-3">Predmeti</h6> <!-- Ispis predmeta koje predaje instruktor -->
                    <?php while ($row = $rezultatInstruktoroviPredmeti->fetch_assoc()) : ?>
                      <small><?php echo $row['naziv_predmeta']; ?></small>
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
                              <a href="<?php echo "../skripte/".$row['skripta_putanja']; ?>" class="btn btn-primary" download>Preuzmi</a>
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
                  $sqlSveRecnezije = "SELECT * FROM recenzije WHERE zaKorisnika = {$korisnikID}";
                  $rezultatSveRecenzije = $con->query($sqlSveRecnezije);
                  if ($rezultatSveRecenzije->num_rows > 0) :
                    while ($red = $rezultatSveRecenzije->fetch_assoc()) :
                      $sqlKorisnik = "SELECT korisnik.ime, korisnik.prezime, recenzije.ocjena, recenzije.komentar 
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
                                <h5><?php echo $korisnik['ime'] . " " . $korisnik['prezime'] ?></h5>

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

                            <div class="row">
                              <div class="col d-flex justify-content-end">
                                <a href="#" class="text text-danger" style="font-size: 0.9rem;">Prijavi recenziju!</a>
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


</body>

</html>
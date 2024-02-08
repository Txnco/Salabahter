<?php
session_start();
$putanjaDoPocetna = '../';
$pathToAutomobili = 'automobili.php';
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

$sqlProvjeraInstruktora = "SELECT * FROM instruktori WHERE korisnik_id =  {$korisnikID}" ; // Provjeri da li je korisnik instruktor
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
  $sqlSkripteKorisnika = "SELECT * FROM skripte WHERE korisnik_id = {$instruktor['instruktor_id']}";
  $resultSkripteKorisnika = $con->query($sqlSkripteKorisnika);
  if ($resultSkripteKorisnika->num_rows > 0) {
    $korisnikImaSkripte = true;
  } else {
    $korisnikImaSkripte = false;
  }
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

  <!-- Favicons -->
  <link href="../assets/img/favicon.png" rel="icon">
  <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Bootstrap CSS include -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="../assets/css/style.css" rel="stylesheet">

  <link href="../assets/css/dashboard.css" rel="stylesheet">

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
                      <div class="col-sm-8 mb-3">
                        <?php
                        if ($korisnikImaSkripte) :
                          while ($row = $resultSkripteKorisnika->fetch_assoc()) : ?>
                            <div class="card h-100">
                              <div class="card-body">
                                <h6 class="d-flex align-items-center mb-3">Skripte</h6>
                                <small><?php echo $row['naziv_skripte']; ?></small>
                                <div class="progress mb-3" style="height: 5px">
                                  <div class="progress-bar bg-primary" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>

                                <a href="<?php echo $row['skripta_putanja']; ?>" class="btn btn-primary" download>Preuzmi</a>
                              </div>
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

</body>

</html>
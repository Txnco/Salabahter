<?php

$trenutnaStranica = "račun";

$putanjaDoPocetne = "../../";
$putanjaDoInstruktora = "../../instruktori.php";
$putanjaDoSkripta = "../../skripte/";
$putanjaDoKartica = "../../kartice.php";
$putanjaDoOnama = "../../onama.php";

$putanjaDoPrijave = "../../racun/prijava.php";
$putanjaDoRegistracije = "../../racun/registracija.php";

$putanjaDoRacuna = "../../nadzornaploca";
$putanjaDoOdjave = "../../racun/odjava.php";

session_start();
$con = require "../../ukljucivanje/connection/spajanje.php";
include("../../ukljucivanje/functions/funkcije.php");

$user = provjeri_prijavu($con);
if (!$user) {
  header("Location: ../../racun/prijava.php");
  die;
}
$user = check_privilegeUser($con);
if ($user['status_korisnika'] == 5) {
  $isAdmin = $_SESSION['isAdmin'];
} else if ($user['status_korisnika'] != 5) {
  header("Location: ../");
  die;
}


$zahtjevZaInstruktora = "SELECT zahtjevzainstruktora.zahtjev_id,korisnik.korisnik_id,statuskorisnika.status_id,motivacija,opisInstruktora,autentikacija, ime, prezime, email, status_naziv  FROM zahtjevzainstruktora,korisnik,statuskorisnika WHERE zahtjevzainstruktora.korisnik_id = korisnik.korisnik_id AND zahtjevzainstruktora.status_id = statuskorisnika.status_id ";
$rezultatZahtjeva = $con->query($zahtjevZaInstruktora);

while ($result = $rezultatZahtjeva->fetch_assoc()) {
  $brojZahtjeva = $rezultatZahtjeva->num_rows;
}



if ($_SERVER['REQUEST_METHOD'] === "POST") {

  if (isset($_POST['upisPredmet'])) { // Upisivanje predmeta u bazu

    $upisPredmeta = $_POST['upisPredmeta'];

    $sql = sprintf("INSERT INTO predmeti (naziv_predmeta) VALUES (?)");

    $rezultat = $con->stmt_init();

    if (!$rezultat->prepare($sql)) {
      die("SQL error:" . $con->error);
    }

    $rezultat->bind_param(
      "s",
      $upisPredmeta
    );

    $rezultat->execute();

    header("Location: ../admin");
    die;
  }  if (isset($_POST['upisPromjena'])) { // Upisivanje promjena korisničkih podataka u bazu

    $promjenaImena = $_POST['imePromjena'];
    $promjenaPrezimena = $_POST['prezimePromjena'];
    $promjenaEmaila = $_POST['emailPromjena'];
    $promjenaAdrese = $_POST['adresaPromjena'];
    $promjenaPrebivalista = $_POST['prebivalistePromjena'];
    $promjenaMjesta = $_POST['mjestoPromjena'];

    $sql = sprintf("UPDATE korisnik SET ime='$promjenaImena', prezime='$promjenaPrezimena', email='$promjenaEmaila', adresa='$promjenaAdrese', prebivaliste ='$promjenaPrebivalista', mjesto='$promjenaMjesta' WHERE korisnik_id = {$_SESSION['user_id']}");

    $result = $con->query($sql);

    header("Location: /nadzornaploca");
    die;
  }
}


// Dohvati prijavljenog korisnika iz baze
$sqlOdabraniKorisnik = "SELECT korisnik.korisnik_id,  ime,prezime,email,adresa, prebivaliste,naziv_grada ,grad_id, status_naziv FROM korisnik, statuskorisnika, gradovi WHERE korisnik.korisnik_id={$_SESSION['user_id']} AND  korisnik.status_korisnika=statuskorisnika.status_id AND korisnik.mjesto=gradovi.grad_id";
$rezultatOdabraniKorisnik = $con->query($sqlOdabraniKorisnik);

$korisnik = $rezultatOdabraniKorisnik->fetch_assoc(); // Dohvati korisnika iz baze 


$sqlProvjeraInstruktora = "SELECT * FROM instruktori WHERE korisnik_id = {$_SESSION['user_id']}"; // Provjeri da li je korisnik instruktor
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
  $sqlSkripteKorisnika = "SELECT * FROM skripte WHERE korisnik_id = {$korisnik['korisnik_id']}";
  $resultSkripteKorisnika = $con->query($sqlSkripteKorisnika);
  if ($resultSkripteKorisnika->num_rows > 0) {
    $korisnikImaSkripte = true;
  } else {
    $korisnikImaSkripte = false;
  }
}

$sqlPoslanZahtjev = "SELECT * FROM zahtjevzainstruktora WHERE korisnik_id = " . $user['korisnik_id'];
$rezultatPoslanZahtjev = $con->query($sqlPoslanZahtjev);
if ($rezultatPoslanZahtjev) {

  $poslanZahtjevRow = $rezultatPoslanZahtjev->fetch_assoc();

  if ($poslanZahtjevRow) {
    // Check if there is a row returned

    // Access the 'status_naziv' value from the associative array
    $zahtjev = 1;
  }
}

$sqlPrijaveRecenzija = "SELECT * FROM prijavarecenzije";
$rezultatPrijaveRecenzija = $con->query($sqlPrijaveRecenzija);
while ($result = $rezultatPrijaveRecenzija->fetch_assoc()) {
  $brojPrijavaRecenzija = $rezultatPrijaveRecenzija->num_rows;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Shuffle Bootstrap Template - Index</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Ikone -->
  <link href="../../assets/img/writing.png" rel="icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css">

  <!-- Vendor CSS datoteke -->
  <link href="../../assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="../../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Glavni prefložak za CSS  -->
  <link href="../../assets/css/style.css" rel="stylesheet">

  <link href="../../assets/css/dashboard.css" rel="stylesheet">

</head>

<body>

  <?php include '../../ukljucivanje/header.php'; ?>

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
        <div class="col-md-4 mb-3">
          <div class="card">
            <div class="card-body">
              <div class="d-flex flex-column align-items-center text-center">
                <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle" width="150">
                <div class="mt-3">
                  <h4> <?php echo $korisnik["ime"] . " " . $korisnik["prezime"] ?></h4>
                  <?php if ($isAdmin == 1) : //provjerava se ako je korisnik admin 
                  ?>
                    <p class="text-muted font-size-sm"><i>Admin</i></p>
                  <?php endif; ?>
                  <p class="text-secondary mb-1">
                    <?php echo $korisnik['status_naziv']; ?></p>
                  <p class="text-muted font-size-sm"><?php echo $korisnik["adresa"] . ",  ";
                                                      echo $korisnik['prebivaliste']; ?></p>
                  <?php if ($korisnikJeInstruktor) : ?> <!-- Ako je korisnik instruktor makne se tipka postani instruktor -->
                    <label class="btn btn-outline-primary">Instruktor</label>
                  <?php elseif (!isset($zahtjev)) : ?>
                    <a class="btn btn-outline-primary" name="postaniInstruktor" href="../zahtjev.php">Postani instruktor</a>
                  <?php else : ?>
                    <label class="btn btn-outline-primary">Zahtjev poslan</label>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>


          <div class="card mt-3">
            <div class="card-body d-flex justify-content-center align-items-center">
              <a class="mr-auto text-secondary " href="zahtjevi.php">Zahtjevi za instruktora</a>
              <span class="ml-auto text-danger align-middle"> <?php if (isset($brojZahtjeva) > 0) {
                                                                echo $brojZahtjeva;
                                                              } else echo '0'; ?> </span>
              <svg class="ml-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                <path d="M8.72 18.78a.75.75 0 0 1 0-1.06L14.44 12 8.72 6.28a.751.751 0 0 1 .018-1.042.751.751 0 0 1 1.042-.018l6.25 6.25a.75.75 0 0 1 0 1.06l-6.25 6.25a.75.75 0 0 1-1.06 0Z"></path>
              </svg>
            </div>
          </div>

          <div class="card mt-3">
            <div class="card-body d-flex justify-content-center align-items-center">
              <a class="mr-auto text-secondary " href="prijaverecenzija.php">Prijave recenzija</a>
              <span class="ml-auto text-danger align-middle"> <?php if (isset($brojPrijavaRecenzija) > 0) {
                                                                echo $brojPrijavaRecenzija;
                                                              } else echo '0'; ?> </span>
              <svg class="ml-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                <path d="M8.72 18.78a.75.75 0 0 1 0-1.06L14.44 12 8.72 6.28a.751.751 0 0 1 .018-1.042.751.751 0 0 1 1.042-.018l6.25 6.25a.75.75 0 0 1 0 1.06l-6.25 6.25a.75.75 0 0 1-1.06 0Z"></path>
              </svg>
            </div>
          </div>


          <?php if ($isAdmin == 1) : ?> <!-- Ako je korisnik Admin onda može dodati predmet -->
            <div class="card mt-3">
              <form method="POST">
                <p class="text-muted font-size-sm m-2"><i>Dodavanje predmeta</i></p>

                <div class="col-sm-9 text-secondary mb-3 mt-3">
                  <label for="upisPredmetaL">Naziv predmeta</label>
                  <input type="text" class="form-control" name="upisPredmeta" id="upisPredmeta">
                </div>
                <div class="col-sm-12 mb-4">
                  <button class="btn btn-info" name="upisPredmet" type="submit">Upisi predmet</button>
                </div>
                <form>
            </div>
          <?php endif; ?>

        </div>
        <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-body">
              <form method="post">
                <div class="row">
                  <div class="col-sm-3">
                    <h6 class="mb-0">Ime</h6>
                  </div>
                  <div class="col-sm-5 text-secondary">
                    <input type="text" class="form-control" name="imePromjena" id="imePromjena" value="<?php echo $korisnik["ime"] ?>" required>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-3">
                    <h6 class="mb-0">Prezime</h6>
                  </div>
                  <div class="col-sm-5 text-secondary">
                    <input type="text" class="form-control" name="prezimePromjena" id="prezimePromjena" value="<?php echo $korisnik["prezime"] ?>" required>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-3">
                    <h6 class="mb-0">Email</h6>
                  </div>
                  <div class="col-sm-9 text-secondary">
                    <input type="text" class="form-control" name="emailPromjena" id="emailPromjena" value="<?php echo $korisnik["email"] ?>" required>
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
                  <div class="col-sm-4 text-secondary">
                    <input type="text" class="form-control" name="adresaPromjena" id="adresaPromjena" value="<?php echo $korisnik["adresa"] ?>" required>
                  </div>
                  <div class="col-sm-4 text-secondary">
                    <input type="text" class="form-control" name="prebivalistePromjena" id="prebivalistePromjena" value="<?php echo $korisnik["prebivaliste"] ?>" required>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-3">
                    <h6 class="mb-0">Obližnji grad</h6>
                  </div>
                  <div class="col-sm-9 text-secondary">
                    <select type="text" class="form-control" name="mjestoPromjena" id="mjestoPromjena" required>
                      <?php
                      $sql = "SELECT * FROM gradovi";
                      $result = $con->query($sql);
                      while ($row = $result->fetch_assoc()) {
                        $selected = ($korisnik['grad_id'] == $row['grad_id']) ? 'selected' : ''; ?>
                        <option value="<?php echo $row["grad_id"]; ?>" <?php echo $selected; ?>> <!-- Za promijenu grada korisnika -->
                          <?php echo $row["naziv_grada"]; ?>
                        </option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-12">
                    <button class="btn btn-info" name="upisPromjena" type="submit">Spremi promjene</button>
                  </div>
                </div>
              </form>
            </div>
          </div>

          <?php if ($korisnikJeInstruktor) : ?> <!-- Ako je korisnik instruktor onda se prikazuju predmeti koje predaje -->
            <div class="row gutters-sm">
              <div class="col-sm-6 mb-3">
                <div class="card h-100">
                  <div class="card-body">
                    <h6 class="d-flex align-items-center mb-3">Predmeti</h6>
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
                              <a href="<?php echo $row['skripta_putanja']; ?>" class="btn btn-primary" download>Preuzmi</a>
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

    </div>
  </div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <script src="../../assets/js/main.js"></script>

</body>

</html>
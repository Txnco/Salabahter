<?php

$trenutnaStranica = "račun";
$trenutnaStranica2 = "Račun";

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
if ($user['status_korisnika'] == 3678) {
  $isAdmin = $_SESSION['isAdmin'];
} else if ($user['status_korisnika'] != 3678) {
  header("Location: ../");
  die;
}

// Dohvaćanje zahtjeva za instruktora
$zahtjevZaInstruktora = "SELECT zahtjevzainstruktora.zahtjev_id,korisnik.korisnik_id,statuskorisnika.status_id,motivacija,opisInstruktora,autentikacija, ime, prezime, email, status_naziv  FROM zahtjevzainstruktora,korisnik,statuskorisnika WHERE zahtjevzainstruktora.korisnik_id = korisnik.korisnik_id AND zahtjevzainstruktora.status_id = statuskorisnika.status_id ";
$rezultatZahtjeva = $con->query($zahtjevZaInstruktora);

$brojZahtjeva = $rezultatZahtjeva->num_rows;

$sqlPrijavljeneRecenzije = "SELECT * FROM prijavarecenzije"; // Dohvaćanje svih prijavljenih recenzija
$rezultatPrijava = $con->query($sqlPrijavljeneRecenzije);  // Izvršavanje upita

$brojPrijavaRecenzija = $rezultatPrijava->num_rows;

$sqlPrijavljeneSkripte = "SELECT * FROM prijavaskripte"; // Dohvaćanje svih prijavljenih skripti
$rezultatPrijaveSkripte = $con->query($sqlPrijavljeneSkripte);  // Izvršavanje upita

$brojPrijavaSkripte = $rezultatPrijaveSkripte->num_rows;

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
  }
  if (isset($_POST['upisPromjena'])) { // Upisivanje promjena korisničkih podataka u bazu

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
  if (isset($_POST['postavljanjeSlike'])) {

    $promjenaSlike = $_FILES['slika'];


    $prijenosnaMapa = "profilneslike/";
    $jedinstvenoIme = uniqid() . "_" . $_FILES["slika"]["name"];
    $putanjaSlike = $prijenosnaMapa . $jedinstvenoIme;

    if (move_uploaded_file($_FILES["slika"]["tmp_name"], "../" . $putanjaSlike)) {

      $sqlDohvatiProfilnuSliku = "SELECT slika_korisnika FROM korisnik WHERE korisnik_id = {$_SESSION['user_id']}"; // Dohvati trenutnu sliku korisnika
      $rezultatProfilnaSlika = $con->query($sqlDohvatiProfilnuSliku);
      $profilnaSlika = $rezultatProfilnaSlika->fetch_assoc();
      if ($profilnaSlika['slika_korisnika'] != null) {
        unlink("../" . $profilnaSlika['slika_korisnika']); // Obriši staru sliku
      }

      $sqlUpisSlike = "UPDATE korisnik SET slika_korisnika =  '{$putanjaSlike}' WHERE korisnik_id = {$_SESSION['user_id']}";
      $rezultatUpisSlike = $con->query($sqlUpisSlike);
      header("Location: /nadzornaploca/admin");
      die;
    } else {
      echo "Prijenos slike nije uspio";
    }
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




?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Administrator</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Ikone -->
  <link href="../../assets/img/writing.png" rel="icon">

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap">

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
  <link href="../../assets/css/nadzornaploca.css" rel="stylesheet">

</head>

<body>

  <?php include '../../ukljucivanje/header.php'; ?>

  <div class="container ">
    <div class="main-body">


      <div class="row gutters-sm">

        <?php include 'izbornik.php'; ?>

        <div class="col-sm-3 mb-3">
          <div class="card">
            <div class="card-body" style="height: 361px;">
              <div class="d-flex flex-column align-items-center text-center">
                <div class="profile-pic-container">


                  <?php

                  $sqlDohvatiProfilnuSliku = "SELECT slika_korisnika FROM korisnik WHERE korisnik_id = {$_SESSION['user_id']}";
                  $rezultatProfilnaSlika = $con->query($sqlDohvatiProfilnuSliku);
                  $profilnaSlika = $rezultatProfilnaSlika->fetch_assoc();


                  if ($profilnaSlika['slika_korisnika'] != null) {
                    $profilnaSlika['slika_korisnika'] = "../" . $profilnaSlika['slika_korisnika'];

                    echo "<div  style='width: 150px; height: 150px; overflow: hidden; border-radius: 50%; display: flex; align-items: center; justify-content: center;'>
                    <img src='{$profilnaSlika['slika_korisnika']}' alt='Profilna slika' style='width: 100%; height: 100%; object-fit: cover;' />
                  </div>";
                  } else {
                    echo '<img id="profile-pic" src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle" width="150">';
                  }

                  ?>

                  <div id="change-pic" class="overlay">
                    <button id="otvoriPrijenosSlike" class="overlay"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="28" height="28">
                        <path d="M15 3c.55 0 1 .45 1 1v9c0 .55-.45 1-1 1H1c-.55 0-1-.45-1-1V4c0-.55.45-1 1-1 0-.55.45-1 1-1h4c.55 0 1 .45 1 1Zm-4.5 9c1.94 0 3.5-1.56 3.5-3.5S12.44 5 10.5 5 7 6.56 7 8.5 8.56 12 10.5 12ZM13 8.5c0 1.38-1.13 2.5-2.5 2.5S8 9.87 8 8.5 9.13 6 10.5 6 13 7.13 13 8.5ZM6 5V4H2v1Z"></path>
                      </svg></button>
                  </div>
                </div>

                <!-- Prozor za promjenu slike -->
                <div class="modal fade" id="novaSlika" tabindex="-1" role="dialog" aria-labelledby="novaSlika" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="profilnaSlika">
                    <div class="modal-content">
                      <form method="POST" enctype="multipart/form-data">
                        <div class="modal-header">
                          <h5 class="modal-title">Postavi profilnu sliku</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <div class="file-upload-wrapper">
                            <input type="file" name="slika" id="unosSlike" class="file-upload" data-height="500" accept=".jpg, .jpeg" />
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button id="zatvoriPrijenosSlike" type="button" class="btn btn-secondary" data-dismiss="modal">Zatvori</button>
                          <button type="submit" class="btn btn-danger" name="postavljanjeSlike">Postavi sliku</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>

                <div class="mt-3">
                  <h4>
                    <?php echo $korisnik["ime"] . " " . $korisnik["prezime"] ?>
                  </h4>
                  <?php if ($isAdmin == 1) : //provjerava se ako je korisnik admin 
                  ?>
                    <p class="text-muted font-size-sm"><i>Administrator</i></p>
                  <?php endif; ?>
                  <p class="text-muted font-size-sm">
                    <?php echo $korisnik["adresa"] . ",  ";
                    echo $korisnik['prebivaliste']; ?>
                  </p>
                  <?php if ($korisnikJeInstruktor) : ?>
                    <!-- Ako je korisnik instruktor makne se tipka postani instruktor -->
                    <label class="btn btn-racun">Instruktor</label>
                  <?php elseif (!isset($zahtjev)) : ?>
                    <a class="btn btn-racun" name="postaniInstruktor" href="../zahtjev.php">Postani instruktor</a>
                  <?php else : ?>
                    <label class="btn btn-racun">Zahtjev poslan</label>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>

        </div>
        <div class="col-sm-6">
          <div class="card mb-3">
            <div class="card-body">
              <form method="post">
                <div class="row">
                  <div class="col-sm-3 align-self-center">
                    <h6 class="mb-0">Ime</h6>
                  </div>
                  <div class="col-sm-3 text-secondary">
                    <input type="text" class="form-control" name="imePromjena" id="imePromjena" value="<?php echo $korisnik["ime"] ?>" required>
                  </div>
                  <div class="col-sm-2 align-self-center">
                    <h6 class="mb-0">Prezime</h6>
                  </div>
                  <div class="col-sm-3 text-secondary">
                    <input type="text" class="form-control" name="prezimePromjena" id="prezimePromjena" value="<?php echo $korisnik["prezime"] ?>" required>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-3 align-self-center">
                    <h6 class="mb-0">Email</h6>
                  </div>
                  <div class="col-sm-5 text-secondary">
                    <input type="text" class="form-control" name="emailPromjena" id="emailPromjena" value="<?php echo $korisnik["email"] ?>" required>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-3 align-self-center">
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
                  <div class="col-sm-3 align-self-center">
                    <h6 class="mb-0">Obližnji grad</h6>
                  </div>
                  <div class="col-sm-5 text-secondary">
                    <select type="text" class="form-control" name="mjestoPromjena" id="mjestoPromjena" required>
                      <?php
                      $sql = "SELECT * FROM gradovi";
                      $result = $con->query($sql);
                      while ($row = $result->fetch_assoc()) {
                        $selected = ($korisnik['grad_id'] == $row['grad_id']) ? 'selected' : ''; ?>
                        <option value="<?php echo $row["grad_id"]; ?>" <?php echo $selected; ?>>
                          <!-- Za promijenu grada korisnika -->
                          <?php echo $row["naziv_grada"]; ?>
                        </option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-12">
                    <button class="btn btn-racun" name="upisPromjena" type="submit">Spremi promjene</button>
                  </div>
                </div>
              </form>
            </div>
          </div>

          <?php if ($korisnikJeInstruktor) : ?>
            <!-- Ako je korisnik instruktor onda se prikazuju predmeti koje predaje -->
            <div class="row gutters-sm">
              <div class="col-sm-6 mb-3">
                <div class="card h-100">
                  <div class="card-body">
                    <h6 class="d-flex align-items-center mb-3">Predmeti</h6>
                    <?php while ($row = $rezultatInstruktoroviPredmeti->fetch_assoc()) : ?>
                      <small>
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
                    <h6 class="d-flex align-items-center mb-3">Skripte</h6>
                    <!-- Ispis skripti koje je instruktor dodao -->
                    <div class="row overflow-auto" style="max-height: 300px;">
                      <div class="col-sm mb-3">
                        <?php
                        if ($korisnikImaSkripte) :
                          while ($row = $resultSkripteKorisnika->fetch_assoc()) : ?>

                            <div class="card-body">
                              <small>
                                <?php echo (strlen($row["naziv_skripte"]) > 20) ? substr($row["naziv_skripte"], 0, 20) . '...' : $row["naziv_skripte"]; ?>
                              </small>
                              <div class="progress mb-3" style="height: 5px">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                              </div>
                              <a href="<?php echo $row['skripta_putanja']; ?>" class="btn btn-primary" download>Preuzmi</a>
                            </div>

                        <?php endwhile;
                        else :
                          echo "Instruktor još nije dodao skripte";
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


  <div class="row gutters-sm">

  </div>


  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <script src="https://code.jquery.com/jquery-3.7.1.js"> </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../../assets/js/main.js"></script>

  <script>
    $(document).ready(function() {
      // Open the modal
      $('#otvoriPrijenosSlike').click(function() {
        $('#novaSlika').modal('show');
      });

      // Close the modal
      $('#zatvoriPrijenosSlike').click(function() {
        $('#novaSlika').modal('hide');
      });

    });
  </script>

</body>

</html>
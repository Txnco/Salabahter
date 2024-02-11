<?php
$trenutnaStranica = "račun";

$putanjaDoPocetne = "../";
$putanjaDoInstruktora = "../instruktori.php";
$putanjaDoSkripta = "../skripte/";
$putanjaDoKartica = "../kartice.php";
$putanjaDoOnama = "../onama.php";

$putanjaDoPrijave = "../racun/login.php";
$putanjaDoRegistracije = "../racun/register.php";

$putanjaDoRacuna = "../nadzornaploca";
$putanjaDoOdjave = "../racun/odjava.php";

session_start();
$con = require "../includes/connection/spajanje.php";
include("../includes/functions/funkcije.php");

$user = provjeri_prijavu($con); // Provjeri da li je korisnik prijavljen
if (!$user) {
  header("Location: ../racun/prijava.php");
  die;
}

$userPrava = check_privilegeUser($con); // Provjeri da li je korisnik admin
if (isset($userPrava)) { // Ako je korisnik admin onda se preusmjeri na admin dashboard
  if ($userPrava['status_korisnika'] == 5) {
    $isAdmin = $_SESSION['isAdmin'];
    header("Location: admin/");
    die;
  }
}

if ($_SERVER['REQUEST_METHOD'] === "POST") { // Provjeri da li je korisnik poslao podatke za promjenu 
  if (isset($_POST['upisPromjena'])) { // Upisivanje promjena korisničkih podataka u bazu

    $promjenaImena = $_POST['imePromjena'];
    $promjenaPrezimena = $_POST['prezimePromjena'];
    $promjenaEmaila = $_POST['emailPromjena'];
    $promjenaAdrese = $_POST['adresaPromjena'];
    $promjenaMjesta = $_POST['mjestoPromjena'];

    $sql = sprintf("UPDATE korisnik SET ime='$promjenaImena', prezime='$promjenaPrezimena', email='$promjenaEmaila', adresa='$promjenaAdrese', mjesto='$promjenaMjesta' WHERE korisnik_id = {$_SESSION['user_id']}");

    $result = $con->query($sql);

    header("Location: ../racun");
    die;
  }
}

// Dohvati prijavljenog korisnika iz baze
$sqlOdabraniKorisnik = "SELECT korisnik.korisnik_id,  ime,prezime,email,adresa,naziv_grada, status_naziv FROM korisnik, statuskorisnika, gradovi WHERE korisnik.korisnik_id={$_SESSION['user_id']} AND  korisnik.status_korisnika=statuskorisnika.status_id AND korisnik.mjesto=gradovi.grad_id";
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

$sqlPoslanZahtjev = "SELECT * FROM zahtjevzainstruktora WHERE korisnik_id =  {$korisnik['korisnik_id']}";
$rezultatPoslanZahtjev = $con->query($sqlPoslanZahtjev);
if ($rezultatPoslanZahtjev) {

  $poslanZahtjevRow = $rezultatPoslanZahtjev->fetch_assoc();

  if ($poslanZahtjevRow) {
    // Check if there is a row returned

    // Access the 'status_naziv' value from the associative array
    $zahtjev = 1;
  }
}

$sqlDohvatiRecenzije = "SELECT * FROM recenzije WHERE zaKorisnika = {$korisnik['korisnik_id']}"; // Dohvati recenzije korisnika
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

  <title>Shuffle Bootstrap Template - Index</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <?php include '../assets/css/stiliranjeSporedno.php'; ?> <!-- Sve poveznice za stil web stranice -->

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Ikone -->

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
        <div class="col-md-4 mb-3">
          <div class="card">
            <div class="card-body">
              <div class="d-flex flex-column align-items-center text-center">
                <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle" width="150">
                <div class="mt-3">
                  <h4> <?php echo $korisnik["ime"] . " " . $korisnik["prezime"] ?></h4>
                  <p class="text-secondary mb-1">
                    <?php echo $korisnik['status_naziv']; ?></p>
                  <p class="text-muted font-size-sm"><?php echo $korisnik["adresa"] . ",  ";
                                                      echo $korisnik['naziv_grada']; ?></p>
                  <?php if ($korisnikJeInstruktor) : ?> <!-- Ako je korisnik instruktor makne se tipka postani instruktor -->
                    <label class="btn btn-outline-primary">Instruktor</label>
                  <?php elseif (!isset($zahtjev)) : ?>
                    <a class="btn btn-outline-primary" name="postaniInstruktor" href="zahtjev.php">Postani instruktor</a>
                  <?php else : ?>
                    <label class="btn btn-outline-primary">Zahtjev poslan</label>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
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
                    <input type="text" class="form-control" name="imePromjena" id="imePromjena" value="<?php echo $user["ime"] ?>" required>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-3">
                    <h6 class="mb-0">Prezime</h6>
                  </div>
                  <div class="col-sm-5 text-secondary">
                    <input type="text" class="form-control" name="prezimePromjena" id="prezimePromjena" value="<?php echo $user["prezime"] ?>" required>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-3">
                    <h6 class="mb-0">Email</h6>
                  </div>
                  <div class="col-sm-9 text-secondary">
                    <input type="text" class="form-control" name="emailPromjena" id="emailPromjena" value="<?php echo $user["email"] ?>" required>
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
                    <input type="text" class="form-control" name="adresaPromjena" id="adresaPromjena" value="<?php echo $user["adresa"] ?>" required>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-3">
                    <h6 class="mb-0">Grad</h6>
                  </div>
                  <div class="col-sm-9 text-secondary">
                    <select type="text" class="form-control" name="mjestoPromjena" id="mjestoPromjena" required>
                      <?php
                      $sql = "SELECT * FROM gradovi";
                      $result = $con->query($sql);
                      while ($row = $result->fetch_assoc()) {
                        $selected = ($user['mjesto'] == $row['grad_id']) ? 'selected' : ''; ?>
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
                  $sqlSveRecnezije = "SELECT * FROM recenzije WHERE zaKorisnika = {$korisnik['korisnik_id']}";
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
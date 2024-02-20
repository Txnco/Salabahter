<?php
$trenutnaStranica = "račun";

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

$user = provjeri_prijavu($con); // Provjeri da li je korisnik prijavljen
if (!$user) {
  header("Location: ../racun/prijava.php");
  die;
}

$userPrava = check_privilegeUser($con); // Provjeri da li je korisnik admin
if (isset($userPrava)) { // Ako je korisnik admin onda se preusmjeri na admin dashboard
  if ($userPrava['status_korisnika'] == 3678) {
    $isAdmin = $_SESSION['isAdmin'];
    header("Location: admin/");
    die;
  }
}

if ($_SERVER['REQUEST_METHOD'] === "POST") { // Provjeri da li je korisnik poslao podatke za promjenu 
  if (isset($_POST['upisPromjena'])) { // Upisivanje promjena korisničkih podataka u bazu

    $promjenaImena = $_POST['imePromjena'];
    $promjenaPrezimena = $_POST['prezimePromjena'];
    $promjenaAdrese = $_POST['adresaPromjena'];
    $promjenaPrebivalista = $_POST['prebivalistePromjena'];
    $promjenaMjesta = $_POST['mjestoPromjena'];

    $sql = sprintf("UPDATE korisnik SET ime='$promjenaImena', prezime='$promjenaPrezimena', adresa='$promjenaAdrese', prebivaliste ='$promjenaPrebivalista', mjesto='$promjenaMjesta' WHERE korisnik_id = {$_SESSION['user_id']}");

    $result = $con->query($sql);

    header("Location: /nadzornaploca");
    die;
  }
  if (isset($_POST['postavljanjeSlike'])) {

    $promjenaSlike = $_FILES['slika'];


    $prijenosnaMapa = "profilneslike/";
    $jedinstvenoIme = uniqid() . "_" . $_FILES["slika"]["name"];
    $putanjaSlike = $prijenosnaMapa . $jedinstvenoIme;

    if (move_uploaded_file($_FILES["slika"]["tmp_name"],  $putanjaSlike)) {

      $sqlDohvatiProfilnuSliku = "SELECT slika_korisnika FROM korisnik WHERE korisnik_id = {$_SESSION['user_id']}"; // Dohvati trenutnu sliku korisnika
      $rezultatProfilnaSlika = $con->query($sqlDohvatiProfilnuSliku);
      $profilnaSlika = $rezultatProfilnaSlika->fetch_assoc();
      if ($profilnaSlika['slika_korisnika'] != null) {
        unlink($profilnaSlika['slika_korisnika']); // Obriši staru sliku
      }

      $sqlUpisSlike = "UPDATE korisnik SET slika_korisnika =  '{$putanjaSlike}' WHERE korisnik_id = {$_SESSION['user_id']}";
      $rezultatUpisSlike = $con->query($sqlUpisSlike);
      header("Location: index.php");
      die;
    } else {
      echo "Prijenos slike nije uspio";
    }
  }
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
}

// Dohvati prijavljenog korisnika iz baze
$sqlOdabraniKorisnik = "SELECT korisnik.korisnik_id,  ime,prezime,email,adresa, prebivaliste, naziv_grada, grad_id, status_naziv FROM korisnik, statuskorisnika, gradovi WHERE korisnik.korisnik_id={$_SESSION['user_id']} AND  korisnik.status_korisnika=statuskorisnika.status_id AND korisnik.mjesto=gradovi.grad_id";
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

  <title>Račun</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <?php include '../assets/css/stiliranjeSporedno.php'; ?> <!-- Sve poveznice za stil web stranice -->

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Ikone -->


  <link href="../assets/css/nadzornaploca.css" rel="stylesheet">


</head>

<body>

  <?php include '../ukljucivanje/header.php'; ?>

  <div class="container">
    <div class="main-body">



      <div class="row gutters-sm">
        <div class="col-md-4 mb-3">
          <div class="card">
            <div class="card-body">
              <div class="d-flex flex-column align-items-center text-center">
                <div class="profile-pic-container">


                  <?php

                  $sqlDohvatiProfilnuSliku = "SELECT slika_korisnika FROM korisnik WHERE korisnik_id = {$_SESSION['user_id']}";
                  $rezultatProfilnaSlika = $con->query($sqlDohvatiProfilnuSliku);
                  $profilnaSlika = $rezultatProfilnaSlika->fetch_assoc();


                  if ($profilnaSlika['slika_korisnika'] != null) {


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
                  <h4> <?php echo $korisnik["ime"] . " " . $korisnik["prezime"] ?></h4>
                  <p class="text-secondary mb-1">
                    <?php echo $korisnik['status_naziv']; ?></p>
                  <p class="text-muted font-size-sm"><?php echo $korisnik["adresa"] . ",  ";
                                                      echo $korisnik['prebivaliste']; ?></p>
                  <?php if ($korisnikJeInstruktor) : ?> <!-- Ako je korisnik instruktor makne se tipka postani instruktor -->
                    <label class="btn btn-racun">Instruktor</label>
                  <?php elseif (!isset($zahtjev)) : ?>
                    <a class="btn btn-racun" name="postaniInstruktor" href="zahtjev.php">Postani instruktor</a>
                  <?php else : ?>
                    <label class="btn btn-racun">Zahtjev poslan</label>
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

                        $sqlDohvatiOcjene = "SELECT ROUND(AVG(ocjena),1) as prosjek, COUNT(ocjena) as brojOcjena FROM recenzije WHERE zaKorisnika = {$_SESSION['user_id']} ";
                        $rezultatOcjene = $con->query($sqlDohvatiOcjene);
                        $ocjene = $rezultatOcjene->fetch_assoc();
                        $ocjene = floatval($ocjene['prosjek']);

                        $sqlBrojRecenzija = "SELECT COUNT(ocjena) as brojOcjena FROM recenzije WHERE zaKorisnika = {$_SESSION['user_id']} ";
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
                          $sqlCountRecenzije = "SELECT COUNT(*) as count FROM recenzije WHERE zaKorisnika = {$_SESSION['user_id']}";
                          $resultCountRecenzije = $con->query($sqlCountRecenzije);
                          $countRecenzije = $resultCountRecenzije->fetch_assoc();

                          if ($countRecenzije['count'] > 6) :
                          ?>
                            <a class="link" href="../recenzije/sve.php?korisnik=<?php echo $_SESSION['user_id']; ?>">Prikaži sve recenzije</a>
                          <?php
                          endif;
                          ?>
                        </div>




                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php endif; ?>

        </div>

        <div class="col-md-8">
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
                  <div class="col-sm-9 text-secondary">
                    <input type="text" class="form-control" name="emailPromjena" id="emailPromjena" value="<?php echo $korisnik["email"] ?>" disabled>
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




          <?php if ($korisnikJeInstruktor) : ?> <!-- Ako je korisnik instruktor onda se prikazuju predmeti koje predaje -->
            <div class="row gutters-sm">
              <div class="col-sm-6 mb-3">
                <div class="card h-100">
                  <div class="card-body">
                    <h6 class="d-flex align-items-center mb-3">Predmeti</h6>
                    <?php while ($row = $rezultatInstruktoroviPredmeti->fetch_assoc()) : ?>
                      <small><?php echo $row['naziv_predmeta']; ?></small>
                      <div class="progress mb-3" style="height: 5px">
                        <div class="progress-bar progress" role="progressbar" style="width: 100%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
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
                              <small><?php echo (strlen($row["naziv_skripte"]) > 20) ? substr($row["naziv_skripte"], 0, 20) . '...' : $row["naziv_skripte"]; ?></small>
                              <div class="progress mb-3" style="height: 5px">
                                <div class="progress-bar progress" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                              </div>
                              <a href="<?php echo $row['skripta_putanja']; ?>" class="btn btn-racun" download>Preuzmi</a>
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
                  $sqlCountRecenzije = "SELECT COUNT(*) as count FROM recenzije WHERE zaKorisnika = {$_SESSION['user_id']}";
                  $resultCountRecenzije = $con->query($sqlCountRecenzije);
                  $countRecenzije = $resultCountRecenzije->fetch_assoc();

                  if ($countRecenzije['count'] > 6) :
                  ?>
                    <a class="link" href="../recenzije/sve.php?korisnik=<?php echo $_SESSION['user_id']; ?>">Prikaži sve recenzije</a>
                  <?php
                  endif;
                  ?>

                  <?php


                  $sqlSveRecnezije = "SELECT * FROM recenzije WHERE zaKorisnika = {$_SESSION['user_id']} LIMIT 6";
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
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <script src="../assets/js/main.js"></script>

  <script src="https://code.jquery.com/jquery-3.7.1.js"> </script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
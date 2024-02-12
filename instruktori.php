<?php

$con = require "ukljucivanje/connection/spajanje.php";
include("ukljucivanje/functions/funkcije.php");
session_start();
$trenutnaStranica = "instruktori";

$putanjaDoPocetne = "/";
$putanjaDoInstruktora = "instruktori.php";
$putanjaDoSkripta = "skripte/";
$putanjaDoKartica = "kartice.php";
$putanjaDoOnama = "onama.php";

$putanjaDoPrijave = "racun/prijava.php";
$putanjaDoRegistracije = "racun/registracija.php";

$putanjaDoRacuna = "nadzornaploca";
$putanjaDoOdjave = "racun/odjava.php";


$sqlSviInstruktori = "SELECT instruktori.instruktor_id, korisnik.korisnik_id, ime, prezime, email, adresa, naziv_grada, status_naziv FROM instruktori, korisnik, statuskorisnika, gradovi WHERE instruktori.korisnik_id=korisnik.korisnik_id AND korisnik.status_korisnika=statuskorisnika.status_id AND korisnik.mjesto=gradovi.grad_id";
$rezultatSviInstruktori = $con->query($sqlSviInstruktori);

$rezultat;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $query = "SELECT instruktori.instruktor_id, korisnik.korisnik_id, ime, prezime, email, adresa, naziv_grada, status_naziv, naziv_zupanije FROM instruktori, korisnik, statuskorisnika, gradovi, zupanija WHERE instruktori.korisnik_id=korisnik.korisnik_id AND korisnik.status_korisnika=statuskorisnika.status_id AND korisnik.mjesto=gradovi.grad_id AND gradovi.zupanija_id=zupanija.zupanija_id";

    if (isset($_POST['pretraga']) && $_POST['pretraga'] != '') {
        $searchTerm = $con->real_escape_string($_POST['pretraga']);
        $query .= " AND (ime LIKE '%$searchTerm%' OR prezime LIKE '%$searchTerm%' OR naziv_grada LIKE '%$searchTerm%' OR status_naziv LIKE '%$searchTerm%')";
    }

    if (isset($_POST['predmet']) && $_POST['predmet'] != '') {
        $predmetId = $_POST['predmet'];
        $query .= " AND instruktor_id IN (SELECT instruktor_id FROM instruktorovipredmeti WHERE predmet_id = $predmetId)";
    }

    if (isset($_POST['grad']) && $_POST['grad'] != '') {
        $gradId = $_POST['grad'];
        $query .= " AND korisnik.mjesto = $gradId";
    }

    if (isset($_POST['zupanija']) && $_POST['zupanija'] != '') {
        $zupanijaId = $_POST['zupanija'];
        $query .= " AND gradovi.zupanija_id = $zupanijaId";
    }

    $rezultatSviInstruktori = $con->query($query);
    if (isset($rezultatSviInstruktori) && $rezultatSviInstruktori->num_rows > 0) {
        $rezultat = true;
    } else {
        $rezultat = false;
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instruktori</title>

    <?php include 'assets/css/stiliranjeGlavno.php'; ?> <!-- Sve poveznice za stil web stranice -->
    <link href="assets/css/instruktori.css" rel="stylesheet">


</head>

<body>

    <?php include 'ukljucivanje/header.php'; ?>

    <div class="justify-content-md-center mb-4 mt-5">
        <div class="hero-section text-center">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mx-auto">
                        <h1 class="display-4">Naši instruktori</h1>
                        <p class="lead">Naši instruktori su tu da vam pomognu da savladate gradivo i položite ispite.
                            Pronađite instruktora koji vam najviše odgovara.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="main-body m-5">

            <div class="row">
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST">

                                <div class="row justify-content-md-center mb-4">
                                    <div class="col-sm-8">
                                        <input class="form-control" type="search" placeholder="Pretraži instruktore"
                                            aria-label="Search" name="pretraga">
                                    </div>
                                    <div class="col-sm">
                                        <button class="btn btn-outline-success" type="submit">Pretraži</button>
                                    </div>
                                </div>

                                <span> Filtriraj </span>
                                <div class="row justify-content-md-center mt-2">
                                    <div class="col-sm">
                                        <select class="form-control" name="predmet">
                                            <option value="">Odaberi predmet</option>
                                            <?php
                                            $rezultatPredmeti = $con->query("SELECT * FROM predmeti");
                                            while ($red = $rezultatPredmeti->fetch_assoc()) {
                                                $selected = isset($_POST['predmet']) && $_POST['predmet'] == $red['predmet_id'] ? 'selected' : '';
                                                echo '<option value="' . $red['predmet_id'] . '" ' . $selected . '>' . $red['naziv_predmeta'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row justify-content-md-center mt-2">
                                    <div class="col-sm">
                                        <select class="form-control" name="grad">
                                            <option value="">Odaberi grad</option>
                                            <?php
                                            $rezultatGradovi = $con->query("SELECT * FROM gradovi");
                                            while ($red = $rezultatGradovi->fetch_assoc()) {
                                                $selected = isset($_POST['grad']) && $_POST['grad'] == $red['grad_id'] ? 'selected' : '';
                                                echo '<option value="' . $red['grad_id'] . '" ' . $selected . '>' . $red['naziv_grada'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row justify-content-md-center mt-2">
                                    <div class="col-sm">
                                        <select class="form-control" name="zupanija">
                                            <option value="">Odaberi zupaniju</option>
                                            <?php
                                            $rezultatZupanije = $con->query("SELECT naziv_zupanije,zupanija.zupanija_id FROM zupanija");
                                            while ($red = $rezultatZupanije->fetch_assoc()) {
                                                $selected = isset($_POST['zupanija']) && $_POST['zupanija'] == $red['zupanija_id'] ? 'selected' : '';
                                                echo '<option value="' . $red['zupanija_id'] . '" ' . $selected . '>' . $red['naziv_zupanije'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row justify-content-md-center mt-2">
                                    <div class="col-sm">
                                        <a href="instruktori.php" class="btn btn-primary">Izbriši filter</a>
                                    </div>
                                </div>


                            </form>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="row instruktori-container h-100">
                        <?php if (isset($rezultatSviInstruktori) > 0): // Ako je rezultatSviInstruktori veći od 0, prikaži sve instruktore
                                while ($red = $rezultatSviInstruktori->fetch_assoc()): //
                            

                                    ?>
                                <div class="col-sm-4 mb-3">
                                    <div class="card" style="height: 390px;">
                                        <div class="card-body">
                                            <div class="d-flex flex-column align-items-center text-center">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin"
                                                    class="rounded-circle" width="100">
                                                <div class="mt-3">
                                                    <h4>
                                                        <?php echo $red["ime"] . " " . $red["prezime"] ?>
                                                    </h4>
                                                    <?php
                                                    $sviPredmetiInstruktora = "SELECT instruktor_id, predmeti.predmet_id, naziv_predmeta, predmet_boja FROM instruktorovipredmeti, predmeti WHERE instruktorovipredmeti.predmet_id=predmeti.predmet_id AND instruktorovipredmeti.instruktor_id= {$red['instruktor_id']}";
                                                    $rezultatPredmeta = $con->query($sviPredmetiInstruktora); // Iz baze uzima instruktor_id, predmeti.predmet_id i naziv_predmeta i sprema u $rezultatPredmeta
                                            
                                                    if ($rezultatPredmeta->num_rows > 0) {
                                                        while ($predmetRed = $rezultatPredmeta->fetch_assoc()) {
                                                            $naziv_predmeta = $predmetRed['naziv_predmeta'];
                                                            $predmet_id = $predmetRed['predmet_id'];
                                                            $predmetBoja = $predmetRed['predmet_boja'];
                                                            echo '<span class="badge" style="background-color: ' . $predmetBoja . ';">' . $naziv_predmeta . '</span> ';
                                                        }
                                                    }


                                                    ?>
                                                    <p class="text-secondary mb-1">
                                                        <?php echo $red['status_naziv']; ?>
                                                    </p>
                                                    <p class="text-muted font-size-sm">
                                                        <?php echo $red["naziv_grada"]; ?>
                                                    </p>
                                                    <a class="btn btn-primary"
                                                        href="profil?korisnik=<?php echo $red['korisnik_id'] ?>">Pogledaj
                                                        profil</a>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php endwhile;
                            else:
                                echo "Nema rezultata za određenu filtraciju"; // ne radi
                            endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'ukljucivanje/footer.php'; ?>

    <!-- Vendor JS datoteke -->
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>

    <!-- Glavni predložak za JS -->
    <script src="assets/js/instruktori.js"></script>

</body>

</html>
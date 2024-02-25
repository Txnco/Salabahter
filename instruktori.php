<?php

$con = require "ukljucivanje/connection/spajanje.php";
include("ukljucivanje/functions/funkcije.php");
session_start();
$trenutnaStranica = "instruktori";

$putanjaDoPocetne = "/";
$putanjaDoInstruktora = "instruktori.php";
$putanjaDoSkripta = "skripte/";
$putanjaDoKartica = "kartice/";
$putanjaDoOnama = "onama.php";

$putanjaDoPrijave = "racun/prijava.php";
$putanjaDoRegistracije = "racun/registracija.php";

$putanjaDoRacuna = "nadzornaploca";
$putanjaDoOdjave = "racun/odjava.php";


$sqlSviInstruktori = "SELECT instruktori.instruktor_id, korisnik.korisnik_id, ime, prezime, email, adresa, prebivaliste, naziv_grada, grad_id, status_naziv FROM instruktori, korisnik, statuskorisnika, gradovi WHERE instruktori.korisnik_id=korisnik.korisnik_id AND korisnik.status_korisnika=statuskorisnika.status_id AND korisnik.mjesto=gradovi.grad_id";
$rezultatSviInstruktori = $con->query($sqlSviInstruktori);

$rezultat;
$query = "SELECT instruktori.instruktor_id, korisnik.korisnik_id, ime, prezime, email, adresa, prebivaliste, naziv_grada, grad_id, status_naziv, naziv_zupanije FROM instruktori, korisnik, statuskorisnika, gradovi, zupanija WHERE instruktori.korisnik_id=korisnik.korisnik_id AND korisnik.status_korisnika=statuskorisnika.status_id AND korisnik.mjesto=gradovi.grad_id AND gradovi.zupanija_id=zupanija.zupanija_id";



if ($_SERVER['REQUEST_METHOD'] == 'POST') {


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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <?php include 'assets/css/stiliranjeGlavno.php'; ?> <!-- Sve poveznice za stil web stranice -->
    <link href="assets/css/instruktori.css" rel="stylesheet">


</head>

<body>

    <?php include 'ukljucivanje/header.php'; ?>

    <div class="justify-content-md-center mb-4">
        <div class=" text-center" style="background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.2)), url(assets/img/more-service-3.jpg);">
            <div class="container ">
                <div class="row">
                    <div class="col-lg-6 mx-auto mt-5">
                        <h1 class="display-4 " style="color: #FFFFFF;">Naši instruktori</h1>
                        <p class="lead" style="color: #FFFFFF;">Naši instruktori su tu da vam pomognu da svladate gradivo i položite ispite.
                            Pronađite instruktora koji vam najviše odgovara.</p>
                    </div>
                </div>

                <div class="row d-flex justify-content-center align-items-center m-2">
                    <div class="col-sm-8 ">
                        <div class="card mb-3">
                            <div class="card-body m-2">
                                <form method="POST">

                                    <div class="row d-flex justify-content-center align-items-center mb-2">
                                        <div class="col-sm-8">
                                            <input class="form-control mt-2 mb-2" type="search" placeholder="Pretražite instruktore" aria-label="Search" name="pretraga">
                                        </div>
                                        <div class="col-sm">
                                            <button class="btn btn-success mt-2 mr-2 mb-2" id="pretrazi" type="submit">Pretraživanje</button>

                                            <a href="#postavkeTrazilice" class="btn" data-toggle="collapse" aria-expanded="false" aria-controls="postavkeTrazilice" id="filtrirajTipka">Filter
                                                <svg class="arrow-up" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16" style="display: none;">
                                                    <path d="M3.22 10.53a.749.749 0 0 1 0-1.06l4.25-4.25a.749.749 0 0 1 1.06 0l4.25 4.25a.749.749 0 1 1-1.06 1.06L8 6.811 4.28 10.53a.749.749 0 0 1-1.06 0Z"></path>
                                                </svg>
                                                <svg class="arrow-down" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                                                    <path d="M12.78 5.22a.749.749 0 0 1 0 1.06l-4.25 4.25a.749.749 0 0 1-1.06 0L3.22 6.28a.749.749 0 1 1 1.06-1.06L8 8.939l3.72-3.719a.749.749 0 0 1 1.06 0Z"></path>
                                                </svg>
                                            </a>

                                        </div>
                                    </div>

                                    <div class="collapse animate__animated animate__slideinDown" id="postavkeTrazilice">
                                        <div class="row justify-content-sm-center mt-3">

                                            <div class="col-sm">
                                                <span class="text-muted">Županija</span>
                                                <select class="form-control" name="zupanija">
                                                    <option value="">Odaberite županiju</option>
                                                    <?php
                                                    $rezultatZupanije = $con->query("SELECT naziv_zupanije,zupanija.zupanija_id FROM zupanija");
                                                    while ($red = $rezultatZupanije->fetch_assoc()) {
                                                        $selected = isset($_POST['zupanija']) && $_POST['zupanija'] == $red['zupanija_id'] ? 'selected' : '';
                                                        echo '<option value="' . $red['zupanija_id'] . '" ' . $selected . '>' . $red['naziv_zupanije'] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="col-sm">
                                                <span class="text-muted">Grad</span>
                                                <select class="form-control" name="grad">
                                                    <option value="">Odaberite grad</option>
                                                    <?php
                                                    $rezultatGradovi = $con->query("SELECT * FROM gradovi");
                                                    while ($red = $rezultatGradovi->fetch_assoc()) {
                                                        $selected = isset($_POST['grad']) && $_POST['grad'] == $red['grad_id'] ? 'selected' : '';
                                                        echo '<option value="' . $red['grad_id'] . '" ' . $selected . '>' . $red['naziv_grada'] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="col-sm">
                                                <span class="text-muted">Predmet</span>
                                                <select class="form-control" name="predmet">
                                                    <option value="">Odaberite predmet</option>
                                                    <?php
                                                    $rezultatPredmeti = $con->query("SELECT * FROM predmeti");
                                                    while ($red = $rezultatPredmeti->fetch_assoc()) {
                                                        $selected = isset($_POST['predmet']) && $_POST['predmet'] == $red['predmet_id'] ? 'selected' : '';
                                                        echo '<option value="' . $red['predmet_id'] . '" ' . $selected . '>' . $red['naziv_predmeta'] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="row mt-2 d-flex align-items-center justify-content-center">
                                                <div class="col" id="trazilica">
                                                    <a href="instruktori.php" class="btn btn-outline-danger mt-2 ml-2 mr-2" id="izbrisi">Izbrišite filter</a>

                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="main-body mt-3">
                <div class="row">
                    <div class="col">
                        <div class="row instruktori-container h-100">
                            <?php if (isset($rezultatSviInstruktori) > 0) :
                                while ($red = $rezultatSviInstruktori->fetch_assoc()) :
                            ?>
                                    <div class="col-sm-3 mb-3 ">
                                        <div class="card" style="height: 390px;">
                                            <div class="card-body">
                                                <div class="d-flex flex-column align-items-center text-center">
                                                    <?php

                                                    $sqlDohvatiProfilnuSliku = "SELECT slika_korisnika FROM korisnik WHERE korisnik_id = {$red['korisnik_id']}";
                                                    $rezultatProfilnaSlika = $con->query($sqlDohvatiProfilnuSliku);
                                                    $profilnaSlika = $rezultatProfilnaSlika->fetch_assoc();

                                                    if ($profilnaSlika['slika_korisnika'] != null) {
                                                        $profilnaSlika['slika_korisnika'] =  "nadzornaploca/" . $profilnaSlika['slika_korisnika'];

                                                        echo "<div class='ml-3' style='width: 100px; height: 100px; overflow: hidden; border-radius: 50%; display: flex; align-items: center; justify-content: center;'>
                                                            <img src='{$profilnaSlika['slika_korisnika']}' alt='Profilna slika' style='width: 100%; height: 100%; object-fit: cover;' />
                                                             </div>";
                                                    } else {
                                                        echo '<img  src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle" width="100px">';
                                                    }

                                                    ?>
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
                                                        <p class="text-secondary mb-1">
                                                            <?php echo $red["prebivaliste"]; ?>
                                                        </p>


                                                        <a class="btn btn-primary" href="profil?korisnik=<?php echo $red['korisnik_id']; ?>">Pogledaj profil</a>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                            <?php endwhile;
                            else :
                                echo "Nema rezultata za određenu filtraciju";
                            endif; ?>
                        </div>
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

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#postavkeTrazilice').on('show.bs.collapse', function() {
                $('.arrow-down').hide();
                $('.arrow-up').show();
            });

            $('#postavkeTrazilice').on('hide.bs.collapse', function() {
                $('.arrow-down').show();
                $('.arrow-up').hide();
            });

            <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') : ?>
                $('#postavkeTrazilice').collapse('show');
            <?php endif; ?>
        });
    </script>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <script src="assets/js/main.js"></script>

</body>

</html>
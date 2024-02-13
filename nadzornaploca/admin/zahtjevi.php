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

$user = provjeri_prijavu($con); // Provjera prijave
if (!$user) {
    header("Location: ../../racun/prijava.php");
    die;
}
$user = check_privilegeUser($con); // Provjera privilegija
if ($user['status_korisnika'] == 5) {
    $isAdmin = $_SESSION['isAdmin'];
} else if ($user['status_korisnika'] != 5) {
    header("Location: ../");
    die;
}

// Dohvaćanje zahtjeva za instruktora
$zahtjevZaInstruktora = "SELECT zahtjevzainstruktora.zahtjev_id,korisnik.korisnik_id,statuskorisnika.status_id,motivacija,opisInstruktora,autentikacija, ime, prezime, email, status_naziv  FROM zahtjevzainstruktora,korisnik,statuskorisnika WHERE zahtjevzainstruktora.korisnik_id = korisnik.korisnik_id AND zahtjevzainstruktora.status_id = statuskorisnika.status_id ";
$rezultatZahtjeva = $con->query($zahtjevZaInstruktora);


if ($_SERVER['REQUEST_METHOD'] === "POST") {

    if (isset($_POST['prihvatiZahtjev'])) { // Prihvaćanje zahtjeva za instruktora

        $zahtjevID = $_POST['zahtjev_id'];
        $korisnikID = $_POST['korisnik_id'];

        // Dohvaćanje podataka iz zahtjeva
        $dohvatiOdobreniZahtjev = "SELECT korisnik_id,motivacija,opisInstruktora,autentikacija  FROM zahtjevzainstruktora WHERE zahtjevzainstruktora.zahtjev_id = {$zahtjevID} AND zahtjevzainstruktora.korisnik_id = {$korisnikID} ";
        $rezultatZahtjeva = $con->query($dohvatiOdobreniZahtjev);
        $test = $rezultatZahtjeva->fetch_assoc();

        echo $test['korisnik_id'] . " " . $test['motivacija'] . " " . $test['opisInstruktora'] . " " . $test['autentikacija'] . " <br><br> ";

        $opisInstruktora = $test['opisInstruktora'];
        $autentikacija = $test['autentikacija'];

        // Dohvaćanje predmeta iz zahtjeva
        $zahtjevZaPredmete = "SELECT predmetizahtjeva.predmet_id FROM predmetizahtjeva,zahtjevzainstruktora,korisnik WHERE  predmetizahtjeva.zahtjev_id = {$zahtjevID} AND zahtjevzainstruktora.korisnik_id = korisnik.korisnik_id AND korisnik.korisnik_id = {$korisnikID}";
        $rezultatPredmeta = $con->query($zahtjevZaPredmete);

        // Spremanje predmeta u polje jer instruktor može predavati više predmeta
        $predmeti = array();
        if ($rezultatPredmeta !== false) {
            if ($rezultatPredmeta->num_rows > 0) {
                while ($predmetRow = $rezultatPredmeta->fetch_assoc()) {
                    $predmeti[] = $predmetRow['predmet_id'];
                }
            }
        } else {
            echo "Error: " . $con->error;
        }

        // Upis instruktora u bazu u tablicu instruktori
        $upisInstruktora = "INSERT INTO instruktori (korisnik_id, opisInstruktora, autentikacija) VALUES ('$korisnikID', '$opisInstruktora','$autentikacija')"; // Upis instruktora u bazu
        $con->query($upisInstruktora);

        // Dohvaćanje ID-a instruktora
        $dohvatiInstruktorovId = "SELECT instruktor_id FROM instruktori WHERE korisnik_id = $korisnikID"; // Dohvaćanje ID-a instruktora
        $rezultat2 = $con->query($dohvatiInstruktorovId);
        $instuktorovID = $rezultat2->fetch_assoc();

        // Upis predmeta koje instruktor predaje u tablicu instruktorovipredmeti
        foreach ($predmeti as $predmet) {
            echo $predmet . " ";
            $upisPredmeta = "INSERT INTO instruktorovipredmeti (instruktor_id, predmet_id) VALUES ('{$instuktorovID['instruktor_id']}','$predmet')"; // Upis predmeta koje instruktor predaje
            $con->query($upisPredmeta);
        }

        echo $korisnikID . " "  . $opisInstruktora . "  " . $instuktorovID['instruktor_id'];

        // Brisanje zahtjeva iz baze iz tablice predmetizahtjeva
        $izbrisiPredmeteZahtjeva = "DELETE FROM predmetizahtjeva WHERE zahtjev_id = {$zahtjevID}"; // Brisanje predmeta iz zahtjeva
        $con->query($izbrisiPredmeteZahtjeva);

        // Brisanje zahtjeva iz baze iz tablice zahtjevzainstruktora
        $izbriziZahtjev = "DELETE FROM zahtjevzainstruktora WHERE zahtjevzainstruktora.zahtjev_id = {$zahtjevID}"; // Brisanje zahtjeva iz baze
        $con->query($izbriziZahtjev);
        if ($con->error) {
            echo "Error: " . $con->error;
        }

        if (isset($rezultatZahtjeva) && $rezultatZahtjeva->num_rows > 0) {
            header("Location: ../admin");
            die;
        }
        header("Location: ../admin/zahtjevi.php");
        die;
    } else if (isset($_POST['odbijZahtjev'])) { // Odbijanje zahtjeva za instruktora

        $zahtjevID = $_POST['zahtjev_id'];
        $korisnikID = $_POST['korisnik_id'];

        // Dohvaćanje autentikacije za brisanje
        $sql = "SELECT autentikacija FROM zahtjevzainstruktora WHERE zahtjev_id = $zahtjevID"; // Dohvaćanje autentikacije za brisanje
        $result = $con->query($sql);
        $row = $result->fetch_assoc();
        $putanja = "../" . $row['autentikacija']; // Spremanje autentikacije u varijablu

        if (file_exists($putanja)) {
            unlink($putanja); // Brisanje autentikacije iz direktorija
        }

        // Brisanje zahtjeva iz baze iz tablice predmetizahtjeva
        $izbrisiPredmeteZahtjeva = "DELETE FROM predmetizahtjeva WHERE zahtjev_id = {$zahtjevID}"; // Brisanje predmeta iz zahtjeva
        $con->query($izbrisiPredmeteZahtjeva);

        // Brisanje zahtjeva iz baze iz tablice zahtjevzainstruktora
        $izbriziZahtjev = "DELETE FROM zahtjevzainstruktora WHERE zahtjevzainstruktora.zahtjev_id = {$zahtjevID}"; // Brisanje zahtjeva iz baze
        $con->query($izbriziZahtjev);
        if ($con->error) {
            echo "Error: " . $con->error;
        }

        if (isset($rezultatZahtjeva) && $rezultatZahtjeva->num_rows > 0) {
            header("Location: ../admin");
            die;
        }
        header("Location: ../admin/zahtjevi.php");
        die;
    }
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

    <!-- Favicons -->
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
                    <li class="breadcrumb-item"><a href="../../">Početna</a></li>
                    <li class="breadcrumb-item active"><a href="../admin/" aria-current="page">Račun</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)" aria-current="page">Zahtjevi</a></li>
                </ol>
            </nav>
            <!-- /Breadcrumb -->



            <div class="card mt-3">
                <div class="card-body p-0">
                    <h4 class="text-center display-4">Zahtjevi za instruktora</h4>
                    <br>
                    <div class="row m-2 mx-auto">
                        <div class="col-sm-2 text-center my-auto">
                            <span style="font-size: 1.3em;">Profilna slika</span>
                        </div>

                        <div class="col-sm-2 text-center my-auto">
                            <span style="font-size: 1.3em;">Ime i prezime</span>
                        </div>

                        <div class="col-sm-2 text-center my-auto">
                            <span style="font-size: 1.3em;">Status</span>
                        </div>

                        <div class="col-sm-2 text-center my-auto">
                            <span style="font-size: 1.3em;">Predmeti</span>
                        </div>

                        <div class="col-sm-2 text-center my-auto">
                            <span style="font-size: 1.3em;">Autentikacija</span>
                        </div>
                    </div>
                    <hr class="m-2">
                </div>

                <?php if (isset($rezultatZahtjeva) && $rezultatZahtjeva->num_rows > 0) :
                    while ($row = $rezultatZahtjeva->fetch_assoc()) : // Prikaz svih zahtjeva za instruktora

                        // Dohvaćanje predmeta za pojedini zahtjev za instruktora
                        $zahtjevZaPredmete = "SELECT naziv_predmeta FROM predmetizahtjeva,predmeti,zahtjevzainstruktora,korisnik WHERE predmetizahtjeva.predmet_id = predmeti.predmet_id AND  predmetizahtjeva.zahtjev_id = {$row['zahtjev_id']} AND zahtjevzainstruktora.korisnik_id = korisnik.korisnik_id AND korisnik.korisnik_id = {$row['korisnik_id']}   ";
                        $rezultatPredmeta = $con->query($zahtjevZaPredmete);

                        // Spremanje predmeta u polje jer instruktor može predavati više predmeta
                        $predmeti = array();
                        if ($rezultatPredmeta->num_rows > 0) {
                            while ($predmetRow = $rezultatPredmeta->fetch_assoc()) {
                                $predmeti[] = $predmetRow['naziv_predmeta'];
                            }
                        }

                ?>

                        <form method="POST">
                            <div class="row m-2 mx-auto">

                                <div class="col-sm-2 text-center my-auto">
                                    <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle" width="100rem">
                                </div>

                                <div class="col-sm-2 text-center my-auto">
                                    <h6 class="card-text"><a class="link" href="../../profil?korisnik=<?php echo $row['korisnik_id'] ?>"><?php echo $row["ime"] . " " . $row["prezime"] . "<br>" ?></a><?php echo $row["status_naziv"] ?></h6>
                                </div>

                                <div class="col-sm-2 text-center my-auto">
                                    <h6 class="card-text"><?php echo $row['motivacija']?></h6>
                                </div>

                                <div class="col-sm-2 text-center my-auto">
                                    <h6 class="card-text"><?php foreach ($predmeti as $predmet) {
                                                                echo $predmet . ", ";
                                                            } ?></h6>
                                </div>

                                <div class="col-sm-2 text-center my-auto">
                                    <a class="btn btn-primary" href="../<?php echo $row["autentikacija"] ?>" download>Preuzmi</a>

                                </div>
                                <div class="col-sm-2 text-center my-auto">
                                    <input type="hidden" name="korisnik_id" value="<?php echo $row['korisnik_id']; ?>">
                                    <input type="hidden" name="zahtjev_id" value="<?php echo $row['zahtjev_id']; ?>">
                                    <div class="col-sm-2 text-center p-1 my-auto">
                                        <button class="btn btn-success" name="prihvatiZahtjev" type="submit">Prihvati</button>
                                    </div> <!-- Svaki zahtjev ima svoj ID, treba za svaki ID zahtjeva sloziti LOOP da se prihvati/odbaci samo onaj koji je stisnuti a ne svi koji su u formu -->
                                    <div class="col-sm-2 text-center p-1 my-auto">
                                        <button class="btn btn-danger" name="odbijZahtjev" type="submit">Odbij</button>
                                    </div>
                                </div>

                            </div>
                        </form>


                        <hr>

                <?php
                    endwhile;
                else :
                    echo "Nema rezultata za zahtjev";
                endif; ?>
            </div>
        </div>
    </div>


    <script>
        window.onload = function() {
            var forms = document.getElementsByTagName('form');
            for (var i = 0; i < forms.length; i++) {
                forms[i].id = 'form' + (i + 1); // Assign a unique id to each form
                var buttons = forms[i].getElementsByTagName('button');
                for (var j = 0; j < buttons.length; j++) {
                    buttons[j].value = i + 1; // Assign the form index to each button
                }
            }
        }
    </script>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <script src="../../assets/js/main.js"></script>
</body>

</html>
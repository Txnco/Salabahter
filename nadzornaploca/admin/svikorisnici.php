<?php

$trenutnaStranica = "račun";
$trenutnaStranica2 = "korisnici";

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
if ($user['status_korisnika'] == 3678) {
    $isAdmin = $_SESSION['isAdmin'];
} else if ($user['status_korisnika'] != 3678) {
    header("Location: ../");
    die;
}

$sqlSviKorisnici = "SELECT * FROM korisnik, statuskorisnika, gradovi WHERE korisnik.status_korisnika = statuskorisnika.status_id AND korisnik.mjesto = gradovi.grad_id"; // Dohvaćanje svih prijavljenih recenzija
$rezultatKorisnika = $con->query($sqlSviKorisnici);  // Izvršavanje upita

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if (isset($_POST['izbrisiZahtjev'])) {

        $prijaveljnaRecenzija = $_POST['prijavljenaRecenzija'];

        $sqlIzbrisiZahtjev = "DELETE FROM prijavarecenzije WHERE prijavljenaRecenzija = {$prijaveljnaRecenzija}";
        $con->query($sqlIzbrisiZahtjev);  // Izvršavanje upita
        if ($con->affected_rows > 0) {
            header("Location: prijaverecenzija.php");
        }
    } elseif (isset($_POST['izbrisiRecenziju'])) {

        $prijaveljnaRecenzija = $_POST['prijavljenaRecenzija'];

        $sqlIzbrisiZahtjev = "DELETE FROM prijavarecenzije WHERE prijavljenaRecenzija = {$prijaveljnaRecenzija}";
        $con->query($sqlIzbrisiZahtjev);  // Izvršavanje upita


        $sqlIzbrisiRecenziju = "DELETE FROM recenzije WHERE recenzija_id = {$prijaveljnaRecenzija}";
        $con->query($sqlIzbrisiRecenziju);  // Izvršavanje upita
        if ($con->affected_rows > 0) {
            header("Location: prijaverecenzija.php");
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Prijave recenzija</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="../../assets/img/writing.png" rel="icon">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Ikone -->

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

    <link href="../../assets/css/nadzornaploca.css" rel="stylesheet">

    <link href="../../assets/css/recenzije.css" rel="stylesheet">

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
                    <li class="breadcrumb-item active"><a href="javascript:void(0)" aria-current="page">Prijave recenzija</a></li>
                </ol>
            </nav>
            <!-- /Breadcrumb -->

            <div class="row gutters-sm">
                <?php include 'izbornik.php'; ?>

                <div class="col-sm-9">
                    <div class="card ">
                        <div class="card-body p-0">
                            <h2 class="text-center mt-3">Svi korisnici</h2>
                            <br>
                            
                            <hr class="m-2">
                        </div>


                        <div class="container">
                            <div class="main-body mt-3">
                                <div class="row">
                                    <div class="col">
                                        <div class="row instruktori-container h-100">
                                            <?php if (isset($rezultatKorisnika) > 0) :
                                                while ($red = $rezultatKorisnika->fetch_assoc()) :
                                            ?>
                                                    <div class="col-sm-3 mb-3 ">
                                                        <div class="card" style="height: 390px;">
                                                            <div class="card-body">
                                                                <div class="d-flex flex-column align-items-center text-center">
                                                                    <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle" width="100">
                                                                    <div class="mt-3">
                                                                        <h4>
                                                                            <?php echo $red["ime"] . " " . $red["prezime"] ?>
                                                                        </h4>
                                                                        <?php

                                                                        $sqlProvjeraJeLiInstruktor = "SELECT * FROM instruktori WHERE instruktor_id = {$red['korisnik_id']}";
                                                                        $rezultatProvjere = $con->query($sqlProvjeraJeLiInstruktor); // Iz baze uzima instruktor_id, predmeti.predmet_id i naziv_predmeta i sprema u $rezultatPredmeta
                                                                        if ($rezultatProvjere->num_rows > 0) {
                                                                            
                                                                            $idInstruktora =$rezultatProvjere->fetch_assoc();
                                                                            $sviPredmetiInstruktora = "SELECT instruktori.instruktor_id, predmeti.predmet_id, naziv_predmeta, predmet_boja FROM instruktori, instruktorovipredmeti, predmeti WHERE instruktorovipredmeti.predmet_id=predmeti.predmet_id AND instruktorovipredmeti.instruktor_id= instruktori.instruktor_id AND instruktorovipredmeti.instruktor_id= {$idInstruktora['instruktor_id']} ";
                                                                            $rezultatPredmeta = $con->query($sviPredmetiInstruktora); // Iz baze uzima instruktor_id, predmeti.predmet_id i naziv_predmeta i sprema u $rezultatPredmeta
    
                                                                            if ($rezultatPredmeta->num_rows > 0) {
                                                                                while ($predmetRed = $rezultatPredmeta->fetch_assoc()) {
                                                                                    $naziv_predmeta = $predmetRed['naziv_predmeta'];
                                                                                    $predmet_id = $predmetRed['predmet_id'];
                                                                                    $predmetBoja = $predmetRed['predmet_boja'];
                                                                                    echo '<span class="badge" style="background-color: ' . $predmetBoja . ';">' . $naziv_predmeta . '</span> ';
                                                                                }
                                                                            }
                                                                        }
                                                                        else {
                                                                            echo '<span class="badge bg-danger">Nije instruktor</span>';
                                                                        } 
                                                                        
                                                                        ?>
                                                                        <p class="text-secondary mb-1">
                                                                            <?php echo $red['status_naziv']; ?>
                                                                        </p>
                                                                        <p class="text-secondary mb-1">
                                                                            <?php echo $red["prebivaliste"]; ?>
                                                                        </p>
                                                                       

                                                                        <a class="btn btn-primary" href="../../profil?korisnik=<?php echo $red['korisnik_id']; ?>">Pogledaj profil</a>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                            <?php endwhile;
                                            else :
                                                echo "<span class='text m-4'>Trenutno nema zahtjeva</span>";
                                            endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
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

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <script src="../../assets/js/main.js"></script>

</body>

</html>
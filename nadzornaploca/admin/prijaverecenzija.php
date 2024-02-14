<?php

$trenutnaStranica = "račun";
$trenutnaStranica2 = "recenzije";

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

$sqlPrijavljeneRecenzije = "SELECT * FROM prijavarecenzije"; // Dohvaćanje svih prijavljenih recenzija
$rezultatPrijava = $con->query($sqlPrijavljeneRecenzije);  // Izvršavanje upita

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
                            <h2 class="text-center mt-3">Prijave recenzija</h2>
                            <br>
                            <div class="row m-2 mx-auto">
                                <div class="col-sm-2 text-center my-auto">
                                    <span style="font-size: 1em;">Autor</span>
                                </div>

                                <div class="col-sm-2 text-center my-auto">
                                    <span style="font-size: 1em;">Komentar</span>
                                </div>

                                <div class="col-sm-2 text-center my-auto">
                                    <span style="font-size: 1em;">Ocjena</span>
                                </div>

                                <div class="col-sm-2 text-center my-auto">
                                    <span style="font-size: 1em;">Prijavio korisnik</span>
                                </div>

                                <div class="col-sm-2 text-center my-auto">
                                    <span style="font-size: 1em;">Razlog prijave</span>
                                </div>
                            </div>
                            <hr class="m-2">
                        </div>


                        <?php if (isset($rezultatPrijava) && $rezultatPrijava->num_rows > 0) :
                            while ($red = $rezultatPrijava->fetch_assoc()) : // Prikaz svih prijava recenzija

                                $sqlSveRecenzije = "SELECT * FROM recenzije WHERE recenzija_id = {$red['prijavljenaRecenzija']}"; // Dohvaćanje svih recenzija
                                $rezultatRecenzija = $con->query($sqlSveRecenzije);  // Izvršavanje upita

                                $recenzija = $rezultatRecenzija->fetch_assoc();

                                $sqlKorisnik = "SELECT korisnik_id, korisnik.ime, korisnik.prezime, recenzije.ocjena, recenzije.komentar 
                        FROM korisnik 
                        JOIN recenzije ON korisnik.korisnik_id = recenzije.odKorisnika 
                        WHERE recenzije.recenzija_id = {$red['prijavljenaRecenzija']}";
                                $rezultatKorisnik = $con->query($sqlKorisnik);
                                $korisnik = $rezultatKorisnik->fetch_assoc();

                                $sqlKorisnikPrijavio = "SELECT korisnik_id, korisnik.ime, korisnik.prezime FROM korisnik WHERE korisnik_id = {$red['prijavioKorisnik']}";
                                $rezultatKorisnikPrijavio = $con->query($sqlKorisnikPrijavio);
                                $korisnikPrijavio = $rezultatKorisnikPrijavio->fetch_assoc();
                        ?>
                                <form method="POST">
                                    <div class="row m-2 mx-auto">



                                        <div class="col-sm-2 text-center my-auto">
                                            <h6><a class="link" href="../../profil/?korisnik=<?php echo $korisnik['korisnik_id'] ?>"><?php echo $korisnik['ime'] . " " . $korisnik['prezime'] ?></a></h6>

                                        </div>

                                        <div class="col-sm-2 text-center my-auto">
                                            <p><?php echo $korisnik['komentar'] ?></p>

                                        </div>

                                        <div class="col-sm-2 text-center my-auto">
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

                                        <div class="col-sm-2 text-center my-auto">
                                            <h6>
                                                <a class="link" href="../../profil/?korisnik=<?php echo $korisnikPrijavio['korisnik_id'] ?>"><?php echo $korisnikPrijavio['ime'] . " " . $korisnikPrijavio['prezime']; ?></a>
                                            </h6>

                                        </div>



                                        <div class="col-sm-2 text-center my-auto">
                                            <p>
                                                <?php echo $red['opisPrijave']; ?>
                                            </p>

                                        </div>

                                        <!-- Svaki zahtjev ima svoj ID,  svaki ID prijave mora imati petlju da se prihvati/odbaci samo onaj koji je pritisnut, a ne svi koji su u formu -->

                                        <div class="col-sm-2 text-center my-auto">
                                            <button class="btn btn-secondary m-2" name="izbrisiZahtjev" type="submit">Izbriši zahtjev</button>

                                            <button class="btn btn-danger m-2" name="izbrisiRecenziju" type="submit">Izbriši recenziju</button>

                                            <input type="hidden" name="prijavljenaRecenzija" value="<?php echo $red['prijavljenaRecenzija']; ?>">


                                        </div>




                                    </div>


                                </form>
                                <hr>

                        <?php
                            endwhile;
                        else :
                            echo "<span class='text m-4'>Trenutno nema zahtjeva</span>";
                        endif; ?>
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
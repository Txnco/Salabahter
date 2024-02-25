<?php

$con = require "ukljucivanje/connection/spajanje.php";
include("ukljucivanje/functions/funkcije.php");
session_start();

$trenutnaStranica = "onama";

$putanjaDoPocetne = "/";
$putanjaDoInstruktora = "instruktori.php";
$putanjaDoSkripta = "skripte/";
$putanjaDoKartica = "kartice.php";
$putanjaDoOnama = "onama.php";

$putanjaDoPrijave = "racun/prijava.php";
$putanjaDoRegistracije = "racun/registracija.php";

$putanjaDoRacuna = "nadzornaploca";
$putanjaDoOdjave = "racun/odjava.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>O nama</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <?php include 'assets/css/stiliranjeGlavno.php'; ?> <!-- Sve poveznice za stil web stranice -->


</head>

<body>

    <?php include 'ukljucivanje/header.php'; ?>


    <section id="about" class="about">
        <div class="container">

            <div class="section-title">
                <h2>O nama</h2>
                <div class="row">
                    <div class="col-sm-8 mx-auto">
                        <h4 class="lead">Šalabahter je web stranica koja je osmišljena kako bi pomogla učenicima i studentima u pronalaženju instruktora za željeni predmet. Znamo da pronalazak instruktora ponekad može postati pravi problem te smo s ovim web rješenjem odlučili pomoći svima. </h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <img src="assets/img/about.jpg" class="img-fluid" alt="">
                </div>
                <div class="col-lg-6 pt-4 pt-lg-0 content">
                    <h3>Šalabahter Vam omogućava <strong>lakši pronalazak instruktora</strong></h3>
                    <p class="fst-italic">
                        Samo s nekoliko klikova možete pronaći instruktora koji Vam odgovara.
                    </p>
                    <p>
                        Web stranica dizajnirana je na način da korisnicima omogućava jednostavno pretraživanje instruktora, pregled njihovih profila i kontaktiranje istih. Jednostavno rečeno - sve to kako bi korisnici mogli pronaći instruktora koji im najbolje odgovara.
                    </p>


                </div>
            </div>

        </div>

    </section>

    <!--  Brojač  -->
    <section class="counts section-bg">
        <div class="container">

            <div class="row no-gutters">

                <div class="col-lg-3 col-md-6 d-md-flex align-items-md-stretch">
                    <div class="count-box">
                        <i class="bi bi-emoji-smile"></i>
                        <span data-purecounter-start="0" data-purecounter-end="<?php // SQL upit za dohvaćanje svih korisnika
                                                                                $sql = "SELECT * FROM korisnik";

                                                                                // izvršavanje SQL upita
                                                                                $rezultat = $con->query($sql);

                                                                                // dohvati broj korisnika
                                                                                $brojKorisnik = mysqli_num_rows($rezultat);

                                                                                echo  $brojKorisnik; ?>" data-purecounter-duration="1" class="purecounter"></span>
                        <p><strong>Korisnika</strong> koristi naše web rješenje</p>
                        <a href="/racun/registracija.php">Postanite i vi korisnik! &raquo;</a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 d-md-flex align-items-md-stretch">
                    <div class="count-box">
                        <i class="bi bi-journal-richtext"></i>
                        <span data-purecounter-start="0" data-purecounter-end="<?php // SQL upit za dohvaćanje svih instruktora
                                                                                $sql = "SELECT * FROM instruktori";

                                                                                // izvršavanje SQL upita
                                                                                $rezultat = $con->query($sql);

                                                                                // dohvaćanje broja instruktora
                                                                                $brojInstruktori = mysqli_num_rows($rezultat);

                                                                                echo $brojInstruktori; ?>" data-purecounter-duration="1" class="purecounter"></span>
                        <p ><strong>Instruktora</strong> koristi našu web stranicu kako bi pružali pomoć drugima</p>
                        <a href="#">Postanite instruktor &raquo;</a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 d-md-flex align-items-md-stretch">
                    <div class="count-box">
                        <i class="bi bi-headset"></i>
                        <span data-purecounter-start="0" data-purecounter-end="<?php // SQL upit za dohvaćanje svih skripti
                                                                                $sql = "SELECT * FROM skripte";

                                                                                // izvršavanje SQL upita
                                                                                $rezultat = $con->query($sql);

                                                                                // dohvaćanje broja skripti
                                                                                $brojSkripte = mysqli_num_rows($rezultat);

                                                                                echo  $brojSkripte; ?>" data-purecounter-duration="1" class="purecounter"></span>
                        <p><strong>Skripta</strong> koje možete preuzeti i učiti sa njih!</p>
                        <a href="/skripte/">Odvedi me do skripta &raquo;</a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 d-md-flex align-items-md-stretch">
                    <div class="count-box">
                        <i class="bi bi-people"></i>
                        <span data-purecounter-start="0" data-purecounter-end="15" data-purecounter-duration="1" class="purecounter"></span>
                        <p><strong>Kartica za ponavljanje</strong> uči i izradi svoju karticu!</p>
                        <a href="#">Odvedi me na kratice! &raquo;</a>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <?php include 'ukljucivanje/footer.php'; ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS  -->
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>


    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <script src="assets/js/main.js"></script>

</body>

</html>
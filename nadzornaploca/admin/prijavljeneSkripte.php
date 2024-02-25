<?php

$trenutnaStranica = "račun";
$trenutnaStranica2 = "Skripte";

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

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if (isset($_POST['izbrisiZahtjev'])) {

        $prijaveljnaSkripta = $_POST['skripta_id'];

        $sqlIzbrisiZahtjev = "DELETE FROM prijavaskripte WHERE skripta_id = {$prijaveljnaSkripta}";
        $con->query($sqlIzbrisiZahtjev);  // Izvršavanje upita

        if ($con->affected_rows > 0) {
            header("Location: prijaverecenzija.php");
        }
    } elseif (isset($_POST['izbrisiSkriptu'])) {

        $prijaveljnaSkripta = $_POST['skripta_id'];

        $sqlIzbrisiZahtjev = "DELETE FROM prijavaskripte WHERE skripta_id = {$prijaveljnaSkripta}";
        $con->query($sqlIzbrisiZahtjev);  // Izvršavanje upita

        $sqlPutanjaSkripte = "SELECT skripta_putanja FROM skripte WHERE skripta_id = {$prijaveljnaSkripta}";
        $rezultatPutanjeSkripte = $con->query($sqlPutanjaSkripte);
        $putanjaSkripte = $rezultatPutanjeSkripte->fetch_assoc();

        $putanja = $putanjaSkripte['skripta_putanja'];
        $cijelaPutanja = "../../skripte/" . $putanja;
        unlink($cijelaPutanja);


        $izbrisiSkriptu = "DELETE FROM skripte WHERE skripta_id = {$prijaveljnaSkripta}";
        $con->query($izbrisiSkriptu);  // Izvršavanje upita
        if ($con->affected_rows > 0) {
            header("Location: prijavljeneSkripte.php");
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
    <link href="../../assets/css/stil.css" rel="stylesheet">

    <link href="../../assets/css/nadzornaploca.css" rel="stylesheet">

    <link href="../../assets/css/recenzije.css" rel="stylesheet">

</head>

<body>

    <?php include '../../ukljucivanje/header.php'; ?>

    <div class="container">
        <div class="main-body">

            <div class="row gutters-sm">
                <?php include 'izbornik.php'; ?>

                <div class="col-sm-9">
                    <div class="card ">
                        <div class="card-body p-0">
                            <h2 class="text-center mt-3">Prijave skripta</h2>
                            <br>
                            <div class="row m-2 mx-auto">
                                <div class="col-sm-2 text-center my-auto d-none d-sm-block">
                                    <span style="font-size: 1em;">Autor</span>
                                </div>

                                <div class="col-sm-2 text-center my-auto d-none d-sm-block">
                                    <span style="font-size: 1em;">Naslov skripte</span>
                                </div>

                                <div class="col-sm-2 text-center my-auto d-none d-sm-block">
                                    <span style="font-size: 1em;">Opis skripte</span>
                                </div>

                                <div class="col-sm-2 text-center my-auto d-none d-sm-block">
                                    <span style="font-size: 1em;">Prijavio korisnik</span>
                                </div>

                                <div class="col-sm-2 text-center my-auto d-none d-sm-block">
                                    <span style="font-size: 1em;">Razlog prijave</span>
                                </div>
                            </div>
                            <hr class="m-2">
                        </div>


                        <?php if (isset($rezultatPrijaveSkripte) && $rezultatPrijaveSkripte->num_rows > 0) :
                            while ($red = $rezultatPrijaveSkripte->fetch_assoc()) : // Prikaz svih prijava recenzija

                                $sqlSveSkripte = "SELECT * FROM skripte WHERE skripta_id = {$red['skripta_id']}"; // Dohvaćanje svih skripti
                                $rezultatSkripte = $con->query($sqlSveSkripte);  // Izvršavanje upita

                                $skripta = $rezultatSkripte->fetch_assoc();

                                $sqlAutorSkripte = "SELECT korisnik.korisnik_id, korisnik.ime, korisnik.prezime, skripte.naziv_skripte, skripte.opis_skripte, skripte.predmet_id, skripte.skripta_id, predmeti.naziv_predmeta FROM korisnik,skripte,predmeti WHERE korisnik.korisnik_id = skripte.korisnik_id AND skripte.predmet_id = predmeti.predmet_id AND skripte.skripta_id = {$red['skripta_id']}";
                                $rezultatAutorSkripte = $con->query($sqlAutorSkripte);
                                $autorSkripte = $rezultatAutorSkripte->fetch_assoc();
                                $sqlPrijavioKorisnik = "SELECT korisnik.korisnik_id, korisnik.ime, korisnik.prezime, prijavaskripte.opisPrijave, skripte.naziv_skripte AS naziv_skripte,skripte.opis_skripte AS opis_skripte
                                FROM korisnik JOIN prijavaskripte ON korisnik.korisnik_id = prijavaskripte.prijavioKorisnik
                                JOIN skripte ON prijavaskripte.skripta_id = skripte.skripta_id
                                WHERE korisnik.korisnik_id = {$red['prijavioKorisnik']} AND skripte.skripta_id = {$red['skripta_id']}";
                                $rezultatPrijavioKorisnik = $con->query($sqlPrijavioKorisnik);
                                $prijavioKorisnik = $rezultatPrijavioKorisnik->fetch_assoc();

                        ?>
                                <form method="POST">
                                    <div class="row m-2 mx-auto">



                                        <div class="col-sm-2 text-center my-auto">
                                            <h6><a class="link" href="../../profil/?korisnik=<?php echo $autorSkripte['korisnik_id'] ?>"><?php echo $autorSkripte['ime'] . " " . $autorSkripte['prezime'] ?></a></h6>

                                        </div>

                                        <div class="col-sm-2 text-center my-auto">
                                            <p><?php echo $autorSkripte['naziv_skripte'] ?></p>

                                        </div>

                                        <div class="col-sm-2 text-center my-auto">
                                            <p><?php echo $autorSkripte['opis_skripte'] ?></p>

                                        </div>

                                        <div class="col-sm-2 text-center my-auto">
                                            <h6>
                                                <a class="link" href="../../profil/?korisnik=<?php echo $prijavioKorisnik['korisnik_id'] ?>"><?php echo $prijavioKorisnik['ime'] . " " . $prijavioKorisnik['prezime']; ?></a>
                                            </h6>

                                        </div>



                                        <div class="col-sm-2 text-center my-auto">
                                            <p>
                                                <?php echo $prijavioKorisnik['opisPrijave']; ?>
                                            </p>

                                        </div>

                                        <!-- Svaki zahtjev ima svoj ID,  svaki ID prijave mora imati petlju da se prihvati/odbaci samo onaj koji je pritisnut, a ne svi koji su u formu -->

                                        <div class="col-sm-2 text-center my-auto">
                                            <button class="btn btn-secondary m-2" name="izbrisiZahtjev" type="submit">Odbij zahtjev</button>

                                            <button class="btn btn-danger m-2" name="izbrisiSkriptu" type="submit">Izbriši skriptu</button>

                                            <input type="hidden" name="skripta_id" value="<?php echo $red['skripta_id']; ?>">


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

    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <script src="../../assets/js/main.js"></script>

</body>

</html>
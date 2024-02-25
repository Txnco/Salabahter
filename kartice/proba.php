<?php
session_start();
$con = require "../ukljucivanje/connection/spajanje.php";
include("../ukljucivanje/functions/funkcije.php");

$trenutnaStranica = "kartice";

$putanjaDoPocetne = "../";
$putanjaDoInstruktora = "../instruktori.php";
$putanjaDoSkripta = "../skripte/";
$putanjaDoKartica = "../kartice/";
$putanjaDoOnama = "../onama.php";
$putanjaDoPrijave = "../racun/prijava.php";
$putanjaDoRegistracije = "../racun/registracija.php";
$putanjaDoRacuna = "../nadzornaploca";
$putanjaDoOdjave = "../racun/odjava.php";

$sqlPredmeti = "SELECT * FROM predmeti";
$rezultatPredmeti = $con->query($sqlPredmeti);

$user = provjeri_prijavu($con);
if (!isset($_SESSION["user_id"])) {
    header("Location: ../racun/prijava.php");
    exit;
} else {
    $korisnikId = $_SESSION["user_id"];
}



$grupa_id = "0";
$grupa_id = $_GET['grupa_id'];
$sqlGrupa = "SELECT * FROM grupekartica WHERE grupa_id = $grupa_id";
$rezultatGrupa = $con->query($sqlGrupa);

if ($redGrupa = $rezultatGrupa->fetch_assoc()) {
    $nazivGrupe = $redGrupa['grupa_naziv'];
    $opisGrupe = $redGrupa['grupa_opis'];
    $vlasnikId = $redGrupa['vlasnik_id'];
    $javno = $redGrupa['javno'];
    $predmetId = $redGrupa['predmet_id'];
    $datumKreiranja = $redGrupa['datum_kreiranja'];
    if ($vlasnikId == $korisnikId) {
        $admin = true;
    } else {
        $admin = false;
    }
    $datumKreiranja = date('d.m.Y', strtotime($redGrupa['datum_kreiranja']));
    $imePrezimeKorisnika = dohvatipodatkevlasnika($vlasnikId);

    $sqlPredmet = "SELECT naziv_predmeta, predmet_boja FROM predmeti WHERE predmet_id =  $predmetId";
    $rezultatPredmet = $con->query($sqlPredmet);
    $redPredmet = $rezultatPredmet->fetch_assoc();
    $predmetNaziv = $redPredmet['naziv_predmeta'];
    $predmetBoja = $redPredmet['predmet_boja'];
}

function dohvatipodatkevlasnika($vlasnik_id)
{

    $con = require "../ukljucivanje/connection/spajanje.php";
    $sqlKorisnik = "SELECT ime, prezime FROM korisnik WHERE korisnik_id = $vlasnik_id";
    $resultKorisnik = $con->query($sqlKorisnik);
    $rowKorisnik = $resultKorisnik->fetch_assoc();
    $ime = $rowKorisnik['ime'];
    $prezime = $rowKorisnik['prezime'];
    return $ime . " " . $prezime;
}

$listaPodataka = array();

$sqlKartice = "SELECT * FROM kartice WHERE grupa_id = $grupa_id";
$rezultatKartice = $con->query($sqlKartice);

if ($rezultatKartice->num_rows > 0) {
    while ($redKartice = $rezultatKartice->fetch_assoc()) {
        $podatak = array(
            'pitanje' => $redKartice['pitanje'],
            'odgovor' => $redKartice['odgovor'],
            'kartica_id' => $redKartice['kartica_id'],
            'tocno' => 0
        );
        $listaPodataka[] = $podatak;
    }
} else {
    echo "Nema dostupnih kartica za ovu grupu.";
}



?>
<!DOCTYPE html>
<html lang="hr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo "$nazivGrupe"; ?>
    </title>
    <?php include '../assets/css/stiliranjeSporedno.php'; ?>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="../assets/css/kartice.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>



    <style>
        h3,
        h4 {
            font-family: 'Poppins', sans-serif;
            color: #000000;
        }
    </style>
</head>

<body>
    <?php include '../ukljucivanje/header.php'; ?>

    <br>
    <br>
    <br>

    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-lg-6">


                <div class="card mt-2 mb-2 mt-1" id="GrupaKartica">
                    <?php
                    echo  '<img src="../assets/img/predmeti/novipredmet.jpg" style="width: 100%; height: 150px; object-fit: cover;">';
                    ?>
                    <div class="card-body">

                        <div class="col">
                            <div class="d-flex flex-column align-items-center text-center">
                                <h4>
                                    <div id="nazivTekst">
                                        <?php echo $nazivGrupe; ?>
                                    </div>

                                </h4>

                                <h6 class="card-info" style="font-family: Poppins; text-align: left;"> <a class="link" href="../profil?korisnik=<?php echo $vlasnikId ?>">
                                        <?php echo $imePrezimeKorisnika; ?>
                                    </a>,
                                    <?php echo "$datumKreiranja"; ?>
                                </h6>

                                <?php echo '<span class="badge" style="background-color: ' . $predmetBoja . ';">' . $predmetNaziv . '</span> '; ?>

                            </div>
                            <div class="d-flex flex-column align-items-start ml-2 mt-4 mb-4 mr-4">
                                <h6 class="m-0" style="font-size: 1.125rem;"><strong>Opis kartica</strong></h6>


                                <?php echo $opisGrupe; ?>



                            </div>


                        </div>

                        </form>

                        <div class="d-flex justify-content-between ml-3 mr-3 mb-3">
                            <button type="button" class="btn btn-primary" id="zapocni">Započni</button>
                        </div>

                    </div>
                </div>



                <div class="card mx-3 mb-2 mt-1" id="podrucjeakcije" style="display: none;">

                    <div class="card-body">
                        <h2 class="text-center mt-3"><?php echo $nazivGrupe; ?></h2>
                    </div>

                    <!-- OVDJE PRIKAŽI SAMO JEDNO PITANJE -->
                    <div class=" card-body d-flex flex-column justify-content-center align-items-center text-center mt-3" id="kartica" style="height: 400px;">
                        <h3 class="card-title" id="pitanje"></h3>
                        <h3 class="card-title" id="odgovor" style="display: none;"></h3>
                        <p class="text-primary mt-5" id="prikaziLink">Prikaži odgovor</p>
                    </div>

                    <div class="card-body">
                        <div class="d-flex justify-content-between mt-2 ml-5 mr-5">
                            <button type="button" class="btn gumb mx-1" id="tocno" style="width: 150px; font-size: 1rem;">Točno</button>
                            <button type="button" class="btn btn-danger mx-1" id="netocno" style="width: 150px; font-size: 1rem;">Netočno</button>
                        </div>
                    </div>

                    <div class="card-footer">

                        <div class="d-flex justify-content-between mt-2">
                            <button type="button" class="btn btn-light" id="prethodno"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8" />
                                </svg> Prethodno </button>
                            <button type="button" class="btn btn-light" id="dalje"><b>Dalje <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8" />
                                    </svg></b></button>
                        </div>
                    </div>

                </div>
                <div class="card mx-3 mb-2" id="statistika" style="display: none;">
                    <div class="card-body">
                        <h3 class="card-title text-center">Statistika</h3>
                        <div class="text-center">
                            <p class="text-left">Vrijeme rješavanja: <span id="vrijemeRjesavanja"></span></p>
                            <p class="text-left">Točnih odgovora: <span id="tocnihOdgovora"></span></p>
                            <p class="text-left">Netočnih odgovora: <span id="netocnihOdgovora"></span></p>
                        </div>
                        <div class="text-center">
                            <button type="button" class="btn btn-primary" id="ponoviNetocno">Ponovi netočne</button>
                            <button type="button" class="btn btn-primary" id="ponovi">Ponovi ponovno</button>
                        </div>
                    </div>

                </div>

                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col text-center">

                            <button style="border: none; background-color: #ffff;" href="generirajQRKodGrupeKartica.php/?grupa_id=<?php echo $grupa_id ?>" data-toggle="modal" data-target="#qrKodModal"><svg fill="#1499ff" width="48px" height="48px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" stroke="#1499ff" stroke-width="0.00024000000000000003">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path d="M16.1666667,6 C16.0746192,6 16,6.07461921 16,6.16666667 L16,7.83333333 C16,7.92538079 16.0746192,8 16.1666667,8 L17.8333333,8 C17.9253808,8 18,7.92538079 18,7.83333333 L18,6.16666667 C18,6.07461921 17.9253808,6 17.8333333,6 L16.1666667,6 Z M16,18 L16,17.5 C16,17.2238576 16.2238576,17 16.5,17 C16.7761424,17 17,17.2238576 17,17.5 L17,18 L18,18 L18,17.5 C18,17.2238576 18.2238576,17 18.5,17 C18.7761424,17 19,17.2238576 19,17.5 L19,18.5 C19,18.7761424 18.7761424,19 18.5,19 L14.5,19 C14.2238576,19 14,18.7761424 14,18.5 L14,17.5 C14,17.2238576 14.2238576,17 14.5,17 C14.7761424,17 15,17.2238576 15,17.5 L15,18 L16,18 L16,18 Z M13,11 L13.5,11 C13.7761424,11 14,11.2238576 14,11.5 C14,11.7761424 13.7761424,12 13.5,12 L11.5,12 C11.2238576,12 11,11.7761424 11,11.5 C11,11.2238576 11.2238576,11 11.5,11 L12,11 L12,10 L10.5,10 C10.2238576,10 10,9.77614237 10,9.5 C10,9.22385763 10.2238576,9 10.5,9 L13.5,9 C13.7761424,9 14,9.22385763 14,9.5 C14,9.77614237 13.7761424,10 13.5,10 L13,10 L13,11 Z M18,12 L17.5,12 C17.2238576,12 17,11.7761424 17,11.5 C17,11.2238576 17.2238576,11 17.5,11 L18,11 L18,10.5 C18,10.2238576 18.2238576,10 18.5,10 C18.7761424,10 19,10.2238576 19,10.5 L19,12.5 C19,12.7761424 18.7761424,13 18.5,13 C18.2238576,13 18,12.7761424 18,12.5 L18,12 Z M13,14 L12.5,14 C12.2238576,14 12,13.7761424 12,13.5 C12,13.2238576 12.2238576,13 12.5,13 L13.5,13 C13.7761424,13 14,13.2238576 14,13.5 L14,15.5 C14,15.7761424 13.7761424,16 13.5,16 L10.5,16 C10.2238576,16 10,15.7761424 10,15.5 C10,15.2238576 10.2238576,15 10.5,15 L13,15 L13,14 L13,14 Z M16.1666667,5 L17.8333333,5 C18.4776655,5 19,5.52233446 19,6.16666667 L19,7.83333333 C19,8.47766554 18.4776655,9 17.8333333,9 L16.1666667,9 C15.5223345,9 15,8.47766554 15,7.83333333 L15,6.16666667 C15,5.52233446 15.5223345,5 16.1666667,5 Z M6.16666667,5 L7.83333333,5 C8.47766554,5 9,5.52233446 9,6.16666667 L9,7.83333333 C9,8.47766554 8.47766554,9 7.83333333,9 L6.16666667,9 C5.52233446,9 5,8.47766554 5,7.83333333 L5,6.16666667 C5,5.52233446 5.52233446,5 6.16666667,5 Z M6.16666667,6 C6.07461921,6 6,6.07461921 6,6.16666667 L6,7.83333333 C6,7.92538079 6.07461921,8 6.16666667,8 L7.83333333,8 C7.92538079,8 8,7.92538079 8,7.83333333 L8,6.16666667 C8,6.07461921 7.92538079,6 7.83333333,6 L6.16666667,6 Z M6.16666667,15 L7.83333333,15 C8.47766554,15 9,15.5223345 9,16.1666667 L9,17.8333333 C9,18.4776655 8.47766554,19 7.83333333,19 L6.16666667,19 C5.52233446,19 5,18.4776655 5,17.8333333 L5,16.1666667 C5,15.5223345 5.52233446,15 6.16666667,15 Z M6.16666667,16 C6.07461921,16 6,16.0746192 6,16.1666667 L6,17.8333333 C6,17.9253808 6.07461921,18 6.16666667,18 L7.83333333,18 C7.92538079,18 8,17.9253808 8,17.8333333 L8,16.1666667 C8,16.0746192 7.92538079,16 7.83333333,16 L6.16666667,16 Z M13,6 L10.5,6 C10.2238576,6 10,5.77614237 10,5.5 C10,5.22385763 10.2238576,5 10.5,5 L13.5,5 C13.7761424,5 14,5.22385763 14,5.5 L14,7.5 C14,7.77614237 13.7761424,8 13.5,8 C13.2238576,8 13,7.77614237 13,7.5 L13,6 Z M10.5,8 C10.2238576,8 10,7.77614237 10,7.5 C10,7.22385763 10.2238576,7 10.5,7 L11.5,7 C11.7761424,7 12,7.22385763 12,7.5 C12,7.77614237 11.7761424,8 11.5,8 L10.5,8 Z M5.5,14 C5.22385763,14 5,13.7761424 5,13.5 C5,13.2238576 5.22385763,13 5.5,13 L7.5,13 C7.77614237,13 8,13.2238576 8,13.5 C8,13.7761424 7.77614237,14 7.5,14 L5.5,14 Z M9.5,14 C9.22385763,14 9,13.7761424 9,13.5 C9,13.2238576 9.22385763,13 9.5,13 L10.5,13 C10.7761424,13 11,13.2238576 11,13.5 C11,13.7761424 10.7761424,14 10.5,14 L9.5,14 Z M11,18 L11,18.5 C11,18.7761424 10.7761424,19 10.5,19 C10.2238576,19 10,18.7761424 10,18.5 L10,17.5 C10,17.2238576 10.2238576,17 10.5,17 L12.5,17 C12.7761424,17 13,17.2238576 13,17.5 C13,17.7761424 12.7761424,18 12.5,18 L11,18 Z M9,11 L9.5,11 C9.77614237,11 10,11.2238576 10,11.5 C10,11.7761424 9.77614237,12 9.5,12 L8.5,12 C8.22385763,12 8,11.7761424 8,11.5 L8,11 L7.5,11 C7.22385763,11 7,10.7761424 7,10.5 C7,10.2238576 7.22385763,10 7.5,10 L8.5,10 C8.77614237,10 9,10.2238576 9,10.5 L9,11 Z M5,10.5 C5,10.2238576 5.22385763,10 5.5,10 C5.77614237,10 6,10.2238576 6,10.5 L6,11.5 C6,11.7761424 5.77614237,12 5.5,12 C5.22385763,12 5,11.7761424 5,11.5 L5,10.5 Z M15,10.5 C15,10.2238576 15.2238576,10 15.5,10 C15.7761424,10 16,10.2238576 16,10.5 L16,12.5 C16,12.7761424 15.7761424,13 15.5,13 C15.2238576,13 15,12.7761424 15,12.5 L15,10.5 Z M17,15 L17,14.5 C17,14.2238576 17.2238576,14 17.5,14 L18.5,14 C18.7761424,14 19,14.2238576 19,14.5 C19,14.7761424 18.7761424,15 18.5,15 L18,15 L18,15.5 C18,15.7761424 17.7761424,16 17.5,16 L15.5,16 C15.2238576,16 15,15.7761424 15,15.5 L15,14.5 C15,14.2238576 15.2238576,14 15.5,14 C15.7761424,14 16,14.2238576 16,14.5 L16,15 L17,15 Z M3,6.5 C3,6.77614237 2.77614237,7 2.5,7 C2.22385763,7 2,6.77614237 2,6.5 L2,4.5 C2,3.11928813 3.11928813,2 4.5,2 L6.5,2 C6.77614237,2 7,2.22385763 7,2.5 C7,2.77614237 6.77614237,3 6.5,3 L4.5,3 C3.67157288,3 3,3.67157288 3,4.5 L3,6.5 Z M17.5,3 C17.2238576,3 17,2.77614237 17,2.5 C17,2.22385763 17.2238576,2 17.5,2 L19.5,2 C20.8807119,2 22,3.11928813 22,4.5 L22,6.5 C22,6.77614237 21.7761424,7 21.5,7 C21.2238576,7 21,6.77614237 21,6.5 L21,4.5 C21,3.67157288 20.3284271,3 19.5,3 L17.5,3 Z M6.5,21 C6.77614237,21 7,21.2238576 7,21.5 C7,21.7761424 6.77614237,22 6.5,22 L4.5,22 C3.11928813,22 2,20.8807119 2,19.5 L2,17.5 C2,17.2238576 2.22385763,17 2.5,17 C2.77614237,17 3,17.2238576 3,17.5 L3,19.5 C3,20.3284271 3.67157288,21 4.5,21 L6.5,21 Z M21,17.5 C21,17.2238576 21.2238576,17 21.5,17 C21.7761424,17 22,17.2238576 22,17.5 L22,19.5 C22,20.8807119 20.8807119,22 19.5,22 L17.5,22 C17.2238576,22 17,21.7761424 17,21.5 C17,21.2238576 17.2238576,21 17.5,21 L19.5,21 C20.3284271,21 21,20.3284271 21,19.5 L21,17.5 Z"></path>
                                    </g>
                                </svg></button>
                            <span class="text">Podijelite ove kartice sa drugima</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        var listaPodataka = <?php echo json_encode($listaPodataka); ?>;
        var trenutniIndeks = 0;
        var karticaElement = document.getElementById("kartica");
        var pitanjeElement = document.getElementById("pitanje");
        var odgovorElement = document.getElementById("odgovor");
        var prikaziLink = document.getElementById("prikaziLink");
        var statistikaElement = document.getElementById("statistika");
        var tocnihOdgovoraElement = document.getElementById("tocnihOdgovora");
        var netocnihOdgovoraElement = document.getElementById("netocnihOdgovora");
        var ponoviNetocno = document.getElementById("ponoviNetocno");
        var ponovi = document.getElementById("ponovi");
        var ukupnoPitanja = listaPodataka.length;

        document.getElementById("zapocni").onclick = function() {
            document.getElementById("podrucjeakcije").style.display = "block";
            document.getElementById("zapocni").style.display = "none";
            pocetakVremena = new Date().getTime();
            prikaziPitanje();
        };

        function prikaziPitanje() {
            pitanjeElement.style.display = "block";
            odgovorElement.style.display = "none";
            document.getElementById("prikaziLink").textContent = "Prikaži odgovor";
            var podatak = listaPodataka[trenutniIndeks];
            document.getElementById("pitanje").textContent = podatak.pitanje;
            document.getElementById("odgovor").textContent = podatak.odgovor;
            document.getElementById("kartica_id").textContent = podatak.kartica_id;

        }

        window.onload = function() {
            prikaziPitanje();
        };

        document.getElementById("ponovi").onclick = function() {
            location.reload();
        };

        karticaElement.onclick = function() {
            if (pitanjeElement.style.display === "none") {
                pitanjeElement.style.display = "block";
                odgovorElement.style.display = "none";
                document.getElementById("prikaziLink").textContent = "Prikaži odgovor";
            } else {
                pitanjeElement.style.display = "none";
                odgovorElement.style.display = "block";
                document.getElementById("prikaziLink").textContent = "Prikaži pitanje";
            }
        };
        // Funkcija koja se izvršava pritiskom na gumb "Dalje"
        document.getElementById("dalje").onclick = function() {
            if (trenutniIndeks < listaPodataka.length - 1) {
                trenutniIndeks++;
                prikaziPitanje();
                prikaziTrenutnoPitanje(); // Ažuriraj trenutno pitanje nakon što se prikaže sljedeće pitanje
            } else {
                // Ako smo došli do kraja, prikaži statistiku
                zavrsiTajmer(); // Završi tajmer kada se dođe do kraja
                prikaziStatistiku();
            }
        };

        document.getElementById("prethodno").onclick = function() {
            if (trenutniIndeks > 0) {
                trenutniIndeks--;
                prikaziPitanje();
            }
        };

        document.getElementById("tocno").onclick = function() {
            listaPodataka[trenutniIndeks].tocno = 1;
            if (trenutniIndeks < listaPodataka.length - 1) {
                trenutniIndeks++;
                prikaziPitanje();
                prikaziTrenutnoPitanje();
            } else {
                zavrsiTajmer();
                prikaziStatistiku();
            }
        };
        document.getElementById("netocno").onclick = function() {
            listaPodataka[trenutniIndeks].tocno = 0;
            if (trenutniIndeks < listaPodataka.length - 1) {
                trenutniIndeks++;
                prikaziPitanje();
                prikaziTrenutnoPitanje();
            } else {
                zavrsiTajmer();
                prikaziStatistiku();
            }
        };

        function prikaziStatistiku() {
            document.getElementById("podrucjeakcije").style.display = "none";
            var tocnihOdgovora = 0;
            var netocnihOdgovora = 0;
            for (var i = 0; i < listaPodataka.length; i++) {
                if (listaPodataka[i].tocno === 1) {
                    tocnihOdgovora++;
                } else {
                    netocnihOdgovora++;
                }
            }
            tocnihOdgovoraElement.textContent = tocnihOdgovora;
            netocnihOdgovoraElement.textContent = netocnihOdgovora;
            statistikaElement.style.display = "block";
            if (netocnihOdgovora > 0) {
                ponoviNetocno.style.display = "block";
                ponovi.style.display = "none";
            } else {
                ponoviNetocno.style.display = "none";
                ponovi.style.display = "block";
            }


        }

        document.getElementById("ponoviNetocno").onclick = function() {

            trenutniIndeks = 0;
            var netocneKartice = listaPodataka.filter(function(kartica) {
                return kartica.tocno === 0;
            });
            listaPodataka = netocneKartice.length > 0 ? netocneKartice : listaPodataka;
            document.getElementById("podrucjeakcije").style.display = "block";
            statistikaElement.style.display = "none";
            prikaziPitanje();
        };

        var pocetakVremena;
        var krajVremena;



        function zavrsiTajmer() {
            krajVremena = new Date().getTime();
            var ukupnoVrijeme = krajVremena - pocetakVremena;
            var sekunde = Math.floor((ukupnoVrijeme / 1000) % 60);
            var minute = Math.floor((ukupnoVrijeme / (1000 * 60)) % 60);
            var sati = Math.floor((ukupnoVrijeme / (1000 * 60 * 60)) % 24);
            var vrijemeRjesavanja = sati + "h " + minute + "m " + sekunde + "s";
            document.getElementById("vrijemeRjesavanja").textContent = vrijemeRjesavanja;
        }

        document.getElementById('zapocni').addEventListener('click', function() {
            document.getElementById('GrupaKartica').style.display = 'none';
        });

        $(document).ready(function() {

            $('#qrKodModal').on('show.bs.modal', function(e) {
                $('#qrKodSlika').attr('src', 'generirajQRKodGrupeKartica.php');
            });
        });
    </script>

    <!-- Modal za QR Kod -->
    <div class="modal fade" id="qrKodModal" tabindex="-1" role="dialog" aria-labelledby="qrKodModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">QR KOD GRUPE KARTICA</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body pt-0">

                    <div class="row">
                        <div class="col d-flex flex-column justify-content-center">
                            <?php
                            // Get the data URL of the QR code
                            $qrCodeDataUrl = require 'generirajQRKodGrupeKartica.php';
                            ?>

                        </div>
                        <div class="col d-flex flex-column justify-content-center align-items-center">

                            <div class="text-center">
                                <h3><?php echo $nazivGrupe; ?></h3>
                            </div>

                            <h6 class="card-info text-center" style="font-family: Poppins;">
                                <a class="link" href="../profil?korisnik=<?php echo $vlasnikId ?>">
                                    <?php echo $imePrezimeKorisnika; ?>
                                </a>,
                                <?php echo "$datumKreiranja"; ?>
                            </h6>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"> </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="../assets/js/main.js"></script>
</body>

</html>
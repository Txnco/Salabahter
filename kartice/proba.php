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
    <link rel="stylesheet" href="css/style.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <script defer src="js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
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
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <h1 class="text-center mt-1">
                        <?php echo "$nazivGrupe"; ?>
                    </h1></br>
                    <button type="button" class="btn btn-primary" id="zapocni">Započni</button>
                    <div class="card mx-3 mb-2" id="podrucjeakcije" style="display: none;">


                        <!-- OVDJE PRIKAŽI SAMO JEDNO PITANJE -->
                        <div class="card-body " id="kartica">
                            <h3 class="card-title text-center" id="pitanje"></h3>
                            <h3 class="card-title text-center" id="odgovor" style="display: none;"></h3>
                            <p class="text-center text-primary " id="prikaziLink">Prikaži odgovor</p>
                        </div>
                        <div class="d-flex justify-content-center mb-2">
                            <button type="button" class="btn btn-success mx-1" id="tocno">Točno</button>
                            <button type="button" class="btn btn-danger mx-1" id="netocno">Netočno</button>
                        </div>

                        <div class="card-footer">

                            <div class="d-flex justify-content-between mt-2">
                                <button type="button" class="btn btn-light" id="prethodno">Prethodno</button>
                                <button type="button" class="btn btn-light" id="dalje"><b>Dalje</b></button>
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

        document.getElementById("zapocni").onclick = function () {
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

        window.onload = function () {
            prikaziPitanje();
        };

        document.getElementById("ponovi").onclick = function () {
            location.reload();
        };

        karticaElement.onclick = function () {
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
        document.getElementById("dalje").onclick = function () {
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

        document.getElementById("prethodno").onclick = function () {
            if (trenutniIndeks > 0) {
                trenutniIndeks--;
                prikaziPitanje();
            }
        };

        document.getElementById("tocno").onclick = function () {
            listaPodataka[trenutniIndeks].tocno = 1;
        };
        document.getElementById("netocno").onclick = function () {
            listaPodataka[trenutniIndeks].tocno = 0;
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

        document.getElementById("ponoviNetocno").onclick = function () {

            trenutniIndeks = 0;
            var netocneKartice = listaPodataka.filter(function (kartica) {
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

    </script>
</body>

</html>
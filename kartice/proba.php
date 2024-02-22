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
    <link rel="stylesheet" href="css/style.css">
    <link href="../assets/css/kartice.css" rel="stylesheet">
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


                <div class="card mt-2 mb-5 mt-5" id="GrupaKartica">
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



                <div class="card mx-3 mb-2 mt-5" id="podrucjeakcije" style="display: none;">

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
                            <button type="button" class="btn btn-success mx-1" id="tocno" style="width: 150px; font-size: 1rem;">Točno</button>
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
    </script>
</body>

</html>
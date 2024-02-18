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


$grupa_id = $_GET['grupa_id'];
$sqlGrupa = "SELECT * FROM grupekartica WHERE grupa_id = $grupa_id";
$rezultatSkripta = $con->query($sqlGrupa);

if($redSkripta = $rezultatSkripta->fetch_assoc()){

    $nazivGrupe = $redSkripta['grupa_naziv'];
    $opisGrupe = $redSkripta['grupa_opis'];
    $predmet_id = $redSkripta['predmet_id'];

    $datum = date('d.m.Y', strtotime($redSkripta['datum_kreiranja']));
    
    $kreator_id = $redSkripta['vlasnik_id'];
    $imePrezimeKorisnika = dohvatipodatkevlasnika($kreator_id);
    
    $sqlPredmet = "SELECT naziv_predmeta, predmet_boja FROM predmeti WHERE predmet_id = $predmet_id";
    $rezultatPredmet = $con->query($sqlPredmet);
    $redPredmet = $rezultatPredmet->fetch_assoc();
    $predmetNaziv = $redPredmet['naziv_predmeta'];
    $predmetBoja = $redPredmet['predmet_boja'];
    
}

    $sqlUpdatePregleda = "UPDATE grupekartica SET broj_pregleda = broj_pregleda + 1 WHERE grupa_id = $grupa_id"; 
    $resultUpdatePregleda = $con->query($sqlUpdatePregleda);
    
    $sqlBrojPregleda = "SELECT broj_pregleda FROM grupekartica WHERE grupa_id = $grupa_id";
    $resultBrojPregleda = $con->query($sqlBrojPregleda);
    $rowBrojPregleda = $resultBrojPregleda->fetch_assoc();
    $brojpregleda = $rowBrojPregleda['broj_pregleda'];
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

?>

<!DOCTYPE html>
<html lang="hr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skripte</title>

    <?php include '../assets/css/stiliranjeSporedno.php'; ?>

</head>

<body>

    <?php include '../ukljucivanje/header.php'; ?>
    <div class="container mt-3">
        <div class="container">
            <div class="card mx-auto mt-3" style="width: 85%;">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <p class="card-text">Broj pregleda:
                                <?php echo " $brojpregleda ";?>
                            </p>
                        </div>
                        <div class="col text-right">
                            <a href="../dashboard/report.php?grupa_id=<?php echo $grupa_id; ?>"
                            class="text text-danger" style="font-size: 0.9rem;" aria-readonly="true">Prijavi skriptu!</a>
                                
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h2 class="card-title" style="font-family: Poppins; text-align: center;"><b>
                            <?php echo "$nazivGrupe"; ?>
                        </b></h2>
                    <h6 class="card-info" style="font-family: Poppins; text-align: center;"> <a class="link"
                            href="../profil?korisnik=<?php echo $kreator_id ?>">
                            <?php echo $imePrezimeKorisnika ?>
                        </a>,
                        <?php echo "$datum   ";  echo '<span class="badge" style="background-color: ' . $predmetBoja . ';">' . $predmetNaziv . '</span> '; ?>
                    </h6>
                    <h5 class="card-text mt-3" style="font-family: Poppins; text-align: center;">
                        <?php echo "$opisGrupe"; ?>
                    </h5>

                    
                    <div>
                   

                </div>
            </div>
        </div>
    </div>


</body>
</html>
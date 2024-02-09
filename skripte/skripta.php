<?php
session_start();
$con = require "../includes/connection/spajanje.php";
include("../includes/functions/funkcije.php");

$trenutnaStranica = "skripte";

$putanjaDoPocetna = '../index.php';
$putanjaDoInstruktora = '../instruktori.php';

$pathToLogin = "../account/login.php";
$pathToRegister = "../account/register.php";
$pathToRacun = "../dashboard/";
$pathToLogout = "../account/logout.php";


$skripta_id = $_GET['skripta_id'];
$sqlSkripta = "SELECT * FROM skripte WHERE skripta_id = $skripta_id";
$resultSkripta = $con->query($sqlSkripta);
$rowSkripta = $resultSkripta->fetch_assoc();
$nazivSkripte = $rowSkripta['naziv_skripte'];
$skripta_putanja = $rowSkripta['skripta_putanja'];
$opisSkripte = $rowSkripta['opis_skripte'];
$predmet_id = $rowSkripta['predmet_id'];
$brojpregleda= $rowSkripta['broj_pregleda'];
$datum = date('d.m.Y', strtotime($rowSkripta['datum_kreiranja']));

$putanjaDoOdabraneSkripte = $rowSkripta['skripta_putanja'];
$kreator_id = $rowSkripta['korisnik_id'];
$imePrezimeKorisnika = dohvatipodatkekreatora($kreator_id);

$sqlPredmet = "SELECT naziv_predmeta, predmet_boja FROM predmeti WHERE predmet_id = $predmet_id";
                        $resultPredmet = $con->query($sqlPredmet);
                        $redPredmet = $resultPredmet->fetch_assoc();
                        $predmetNaziv = $redPredmet['naziv_predmeta'];
                        $predmetBoja = $redPredmet['predmet_boja'];



$sqlUpdatePregleda = "UPDATE skripte SET broj_pregleda = broj_pregleda + 1 WHERE skripta_id = $skripta_id"; 
$resultUpdatePregleda = $con->query($sqlUpdatePregleda);

$sqlBrojPregleda = "SELECT broj_pregleda FROM skripte WHERE skripta_id = $skripta_id";
$resultBrojPregleda = $con->query($sqlBrojPregleda);
$rowBrojPregleda = $resultBrojPregleda->fetch_assoc();
$brojpregleda = $rowBrojPregleda['broj_pregleda'];

function dohvatiinstruktore($predmet_id){
    $con = require "../includes/connection/spajanje.php";
    $sqlInstruktori = "SELECT instruktor_id  FROM instruktorovipredmeti WHERE predmet_id = " . $predmet_id ;
    $resultInstruktori = $con->query($sqlInstruktori);
    return $resultInstruktori; 
}

$sviInstruktori= dohvatiinstruktore($predmet_id);




function dohvatipodatkekreatora($kreator_id)
{

    $con = require "../includes/connection/spajanje.php";
    $sqlKorisnik = "SELECT ime, prezime FROM korisnik WHERE korisnik_id = $kreator_id";
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

    <?php include '../includes/header.php'; ?>
    <div class="container mt-3">
        <div class="container">
            <div class="card mx-auto mt-3" style="width: 85%;">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <p class="card-text">Broj pregleda:
                                <?php echo "$brojpregleda"; ?>
                            </p>
                        </div>
                        <div class="col text-right">
                            <a href="../dashboard/report.php?skripta_id=<?php echo $skripta_id; ?>"
                                style="color: red;">Prijavi</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h2 class="card-title" style="font-family: Poppins; text-align: center;"><b>
                            <?php echo "$nazivSkripte"; ?>
                        </b></h2>
                    <h6 class="card-info" style="font-family: Poppins; text-align: center;"> <a class="link"
                            href="../profil?korisnik=<?php echo $kreator_id ?>">
                            <?php echo $imePrezimeKorisnika ?>
                        </a>,
                        <?php echo "$datum   ";  echo '<span class="badge" style="background-color: ' . $predmetBoja . ';">' . $predmetNaziv . '</span> '; ?>
                    </h6>
                  
                    <div class="text-center">
                        <a href="<?php echo $putanjaDoOdabraneSkripte; ?>" class="btn btn-primary">Preuzmi</a>
                        
                    </div>

                    <h5 class="card-text mt-3" style="font-family: Poppins; text-align: center;">
                        <?php echo "$opisSkripte"; ?>
                    </h5>
                    <div>
                        
                    </br>
                    <?php if (!empty($putanjaDoOdabraneSkripte)) { ?>
                        <embed src="<?php echo $putanjaDoOdabraneSkripte; ?>" type="application/pdf" width="75%" height="600px" class="mx-auto d-block" />
                    <?php } else { ?>
                        <p>Nažalost, nešto je pošlo po krivu.</p>
                        <a href="../dashboard/report.php?skripta_id=<?php echo $skripta_id; ?>" style="color: red;">Prijavi</a>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

<div class="container mt-5s">
<div class="card mx-auto mt-3" style="width: 85%;">
<div class="card-header"></div>
<div class="card-body">
    <h5>Slične skripte</h5>
   </div> 
    <div class="row mx-1">
        <?php
        $sqlSlicneSkripte = "SELECT * FROM skripte WHERE predmet_id = $predmet_id AND skripta_id != $skripta_id ORDER BY RAND() LIMIT 3";
        $resultSlicneSkripte = $con->query($sqlSlicneSkripte);
        while ($rowSlicneSkripte = $resultSlicneSkripte->fetch_assoc()) {
            $nazivSlicneSkripte = $rowSlicneSkripte['naziv_skripte'];
            $skripta_putanja = $rowSlicneSkripte['skripta_putanja'];
            $opisSlicneSkripte = $rowSlicneSkripte['opis_skripte'];
            $skripta_id = $rowSlicneSkripte['skripta_id'];
            $kreator_id = $rowSlicneSkripte['korisnik_id'];
            $imePrezimeKorisnika = dohvatipodatkekreatora($kreator_id);
            $datum = date('d.m.Y', strtotime($rowSlicneSkripte['datum_kreiranja']));
        ?>
        <div class="col-md-4 ">
            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $nazivSlicneSkripte; ?></h5>  
                    <p class="card-text "><?php echo $opisSlicneSkripte; ?></p>
                    <a href="skripta.php?skripta_id=<?php echo $skripta_id; ?>" class="btn btn-primary">Pregledaj</a>
                </div>
            </div>
        </div>
        <?php
        }?>
    </div>
</div>
 
 
 
    <!--
                        <h5 class="card-text mt-3" style="font-family: Poppins; text-align: center;">Instruktori koji
                            predaju ovaj predmet:</h5>
                        <div class="row">
                        <?php
                            /*
                            while ($redInstruktori = $sviInstruktori->fetch_assoc()) {
                                $instruktor_id = $redInstruktori['instruktor_id'];
                                $sqlInstruktor = "SELECT korisnik_id, opis FROM instruktori WHERE instruktor_id = $instruktor_id";
                                
                                $resultInstruktor = $con->query($sqlInstruktor);
                                $redInstruktor = $resultInstruktor->fetch_assoc();
                                $korisnik_id = $redInstruktor['korisnik_id'];

                                $sqlKorisnik = "SELECT ime, prezime FROM korisnik WHERE korisnik_id = $korisnik_id";
                                $resultKorisnik = $con->query($sqlKorisnik);
                                $rowKorisnik = $resultKorisnik->fetch_assoc();

                                $imeInstruktora = $rowKorisnik['ime'];
                                $prezimeInstruktora = $rowKorisnik['prezime'];
                                $opisInstruktora = $redInstruktor['opis'];
                            ?>
                            <div class="col-md-4">
                                <div class="card" style="width: 18rem;">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $imeInstruktora . " " . $prezimeInstruktora; ?></h5>
                                        <p class="card-text"><?php echo $opisInstruktora; ?></p>    
                                    </div>
                                </div>
                            </div>
                            <?php
                            }*/?>
                    </div>-->
</body>

</html>
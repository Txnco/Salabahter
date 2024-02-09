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
$brojpreuzimanja = $rowSkripta['broj_preuzimanja'];
$datum = date('d.m.Y', strtotime($rowSkripta['datum_kreiranja']));

$putanjaDoOdabraneSkripte = $rowSkripta['skripta_putanja'];
$kreator_id = $rowSkripta['korisnik_id'];
$imePrezimeKorisnika = dohvatipodatkekreatora($kreator_id);

$sqlPredmet = "SELECT naziv_predmeta, predmet_boja FROM predmeti WHERE predmet_id = $predmet_id";
                        $resultPredmet = $con->query($sqlPredmet);
                        $redPredmet = $resultPredmet->fetch_assoc();
                        $predmetNaziv = $redPredmet['naziv_predmeta'];
                        $predmetBoja = $redPredmet['predmet_boja'];
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
                            <p class="card-text">Broj preuzimanja:
                                <?php echo "$brojpreuzimanja"; ?>
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
                    
                    </br>
                    <embed src="<?php echo $putanjaDoOdabraneSkripte; ?>" type="application/pdf" width="75%" height="600px" class="mx-auto d-block" />

                </div>
            </div>
        </div>
    </div>


    <h5>hasjkdhih</h5>
</body>

</html>
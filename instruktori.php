<?php

$con = require "includes/connection/spajanje.php";
include("includes/functions/funkcije.php");
session_start();
$trenutnaStranica = "instruktori";

$putanjaDoPocetna = 'index.php';
$putanjaDoSkripta = 'skripta.php';
$putanjaDoInstruktora = 'instruktori.php';

$pathToLogin = "account/login.php";
$pathToRegister = "account/register.php";
$pathToRacun = "dashboard/";
$pathToLogout = "account/logout.php";


$sqlSviInstruktori = "SELECT instruktori.instruktor_id, korisnik.korisnik_id, ime, prezime, email, adresa, naziv_grada, status_naziv FROM instruktori, korisnik, statuskorisnika, gradovi WHERE instruktori.korisnik_id=korisnik.korisnik_id AND korisnik.status_korisnika=statuskorisnika.status_id AND korisnik.mjesto=gradovi.grad_id";
$rezultatSviInstruktori = $con->query($sqlSviInstruktori);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instruktori</title>

    <?php include 'assets/css/stiliranje.php'; ?>  <!-- Sve poveznice za stil web stranice -->
    <link href="assets/css/dashboard.css" rel="stylesheet">
</head>

<body>

    <?php include 'includes/header.php'; ?>

    <div class="container">
        <div class="main-body">
            <div class="row gutters-sm">
                <?php if (isset($rezultatSviInstruktori) > 0) : // Ako je rezultatSviInstruktori veći od 0, prikaži sve instruktore
                    while ($red = $rezultatSviInstruktori->fetch_assoc()) : //

                        $sviPredmetiInstruktora = "SELECT instruktor_id, predmeti.predmet_id, naziv_predmeta FROM instruktorovipredmeti, predmeti WHERE instruktorovipredmeti.predmet_id=predmeti.predmet_id AND instruktorovipredmeti.instruktor_id= {$red['instruktor_id']}";
                        $rezultatPredmeta = $con->query($sviPredmetiInstruktora); // Iz baze uzima instruktor_id, predmeti.predmet_id i naziv_predmeta i sprema u $rezultatPredmeta

                        $predmeti = array(); // Kreira prazan niz $predmeti
                        if ($rezultatPredmeta->num_rows > 0) {
                            while ($predmetRed = $rezultatPredmeta->fetch_assoc()) { // Ako ima više od 0 redova, uzima red i sprema u $predmetRed
                                $predmeti[] = $predmetRed['naziv_predmeta']; // Sprema naziv_predmeta u niz $predmeti
                            }
                        }

                ?>
                        <div class="col-md-4 mb-5 mt-5">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-column align-items-center text-center">
                                        <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle" width="100">
                                        <div class="mt-3">
                                            <h4> <?php echo $red["ime"] . " " . $red["prezime"] ?></h4>
                                            <?php
                                            foreach ($predmeti as $predmet) {
                                                echo $predmet . " ";
                                            }
                                            ?>
                                            <p class="text-secondary mb-1">
                                                <?php echo $red['status_naziv']; ?> </p>
                                            <p class="text-muted font-size-sm"><?php echo $red["naziv_grada"]; ?></p>
                                            <a class="btn btn-primary" href="profil?korisnik=<?php echo $red['korisnik_id'] ?>">Pogledaj profil</a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                <?php
                    endwhile;
                endif; ?>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

</body>

</html>
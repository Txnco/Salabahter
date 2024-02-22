<?php
session_start();
$con = require "../ukljucivanje/connection/spajanje.php";
include("../ukljucivanje/functions/funkcije.php");



$trenutnaStranica = "skripte";

$putanjaDoPocetne = "../";
$putanjaDoInstruktora = "../instruktori.php";
$putanjaDoSkripta = "../skripte/";
$putanjaDoKartica = "../kartice/";
$putanjaDoOnama = "../onama.php";

$putanjaDoPrijave = "../racun/prijava.php";
$putanjaDoRegistracije = "../racun/registracija.php";

$putanjaDoRacuna = "../nadzornaploca";
$putanjaDoOdjave = "../racun/odjava.php";


$skripta_id = $_GET['skripta_id'];
$sqlGrupa = "SELECT * FROM skripte WHERE skripta_id = $skripta_id";
$rezultatSkripta = $con->query($sqlGrupa);

if ($redSkripta = $rezultatSkripta->fetch_assoc()) {

    $nazivSkripte = $redSkripta['naziv_skripte'];
    $skripta_putanja = $redSkripta['skripta_putanja'];
    $opisSkripte = $redSkripta['opis_skripte'];
    $predmet_id = $redSkripta['predmet_id'];
    $brojpregleda = $redSkripta['broj_pregleda'];
    $datum = date('d.m.Y', strtotime($redSkripta['datum_kreiranja']));

    $putanjaDoOdabraneSkripte = $redSkripta['skripta_putanja'];
    $kreator_id = $redSkripta['korisnik_id'];
    $imePrezimeKorisnika = dohvatipodatkevlasnika($kreator_id);

    $sqlPredmet = "SELECT naziv_predmeta, predmet_boja FROM predmeti WHERE predmet_id = $predmet_id";
    $rezultatPredmet = $con->query($sqlPredmet);
    $redPredmet = $rezultatPredmet->fetch_assoc();
    $predmetNaziv = $redPredmet['naziv_predmeta'];
    $predmetBoja = $redPredmet['predmet_boja'];



    $sqlUpdatePregleda = "UPDATE skripte SET broj_pregleda = broj_pregleda + 1 WHERE skripta_id = $skripta_id";
    $resultUpdatePregleda = $con->query($sqlUpdatePregleda);

    $sqlBrojPregleda = "SELECT broj_pregleda FROM skripte WHERE skripta_id = $skripta_id";
    $resultBrojPregleda = $con->query($sqlBrojPregleda);
    $rowBrojPregleda = $resultBrojPregleda->fetch_assoc();
    $brojpregleda = $rowBrojPregleda['broj_pregleda'];

    function dohvatiinstruktore($predmet_id)
    {
        $con = require "../ukljucivanje/connection/spajanje.php";
        $sqlInstruktori = "SELECT instruktor_id  FROM instruktorovipredmeti WHERE predmet_id = " . $predmet_id;
        $resultInstruktori = $con->query($sqlInstruktori);
        return $resultInstruktori;
    }

    $sviInstruktori = dohvatiinstruktore($predmet_id);
}

function dohvatipodatkevlasnika($kreator_id)
{

    $con = require "../ukljucivanje/connection/spajanje.php";
    $sqlKorisnik = "SELECT ime, prezime FROM korisnik WHERE korisnik_id = $kreator_id";
    $resultKorisnik = $con->query($sqlKorisnik);
    $rowKorisnik = $resultKorisnik->fetch_assoc();
    $ime = $rowKorisnik['ime'];
    $prezime = $rowKorisnik['prezime'];
    return $ime . " " . $prezime;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if (isset($_POST['prijavi'])) {

        $razlogPrijave = $_POST['razlogPrijave'];
        $prijavljenaSkripta = $_POST['prijavljenaSkripta'];
        $prijavioKorisnik = $_SESSION['user_id'];

        $sqlPrijaviSkriptu = "INSERT INTO prijavaskripte (skripta_id, prijavioKorisnik, opisPrijave) VALUES (?, ?, ?)";
        $stmt = $con->prepare($sqlPrijaviSkriptu);
        $stmt->bind_param("iis",  $prijavljenaSkripta, $prijavioKorisnik, $razlogPrijave);

        $stmt->execute();


        if ($stmt->error) {
            echo "Error: " . $stmt->error;
        }
        header("Location: skripta.php?skripta_id=" . $prijavljenaSkripta);
        die;
    }
}

?>

<!DOCTYPE html>
<html lang="hr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skripte</title>

    <?php include '../assets/css/stiliranjeSporedno.php'; ?> <!-- Sve poveznice za stil web stranice -->
    <link href="../assets/css/nadzornaploca.css" rel="stylesheet">

    <style>
        @media only screen and (max-width: 600px) {
            embed {
                width: 100%;
                height: 500px;
            }
        }
    </style>

</head>

<body>

    <?php include '../ukljucivanje/header.php'; ?>

    <div class="container">
        <div class="main-body ">


            <div class="row gutters-sm">
                <div class="card mx-auto mt-3 mb-3 p-0" style="width: 85%;">

                    <div class="row m-2">
                        <div class="col d-flex justify-content-start align-items-center">

                            <a class="btn text" href="index"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="mr-2">
                                    <path d="M10.78 19.03a.75.75 0 0 1-1.06 0l-6.25-6.25a.75.75 0 0 1 0-1.06l6.25-6.25a.749.749 0 0 1 1.275.326.749.749 0 0 1-.215.734L5.81 11.5h14.44a.75.75 0 0 1 0 1.5H5.81l4.97 4.97a.75.75 0 0 1 0 1.06Z"></path>
                                </svg>Vrati se na skripte</a>
                        </div>
                    </div>
                    <div class="card-body">

                        <h2 class="card-title" style="font-family: Poppins; text-align: center;"><b>
                                <?php echo "$nazivSkripte"; ?>
                            </b></h2>
                        <h6 class="card-info" style="font-family: Poppins; text-align: center;"> <a class="link" href="../profil?korisnik=<?php echo $kreator_id ?>">
                                <?php echo $imePrezimeKorisnika ?>
                            </a>,
                            <?php echo "$datum   ";
                            echo '<span class="badge" style="background-color: ' . $predmetBoja . ';">' . $predmetNaziv . '</span> '; ?>
                        </h6>



                        <h5 class="card-text mt-3 mb-5" style="font-family: Poppins; text-align: center;">
                            <?php echo "$opisSkripte"; ?>
                        </h5>

                        <div class="row justify-content-center">
                            <div class="col">
                                <div class="card-title text-center">
                                    <button style="border: none; background-color: white;" href="generirajQRKodSkripte.php/?skripta_id=<?php echo $skripta_id ?>" data-toggle="modal" data-target="#qrKodModal"><svg fill="#1499ff" width="48px" height="48px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" stroke="#1499ff" stroke-width="0.00024000000000000003">
                                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                            <g id="SVGRepo_iconCarrier">
                                                <path d="M16.1666667,6 C16.0746192,6 16,6.07461921 16,6.16666667 L16,7.83333333 C16,7.92538079 16.0746192,8 16.1666667,8 L17.8333333,8 C17.9253808,8 18,7.92538079 18,7.83333333 L18,6.16666667 C18,6.07461921 17.9253808,6 17.8333333,6 L16.1666667,6 Z M16,18 L16,17.5 C16,17.2238576 16.2238576,17 16.5,17 C16.7761424,17 17,17.2238576 17,17.5 L17,18 L18,18 L18,17.5 C18,17.2238576 18.2238576,17 18.5,17 C18.7761424,17 19,17.2238576 19,17.5 L19,18.5 C19,18.7761424 18.7761424,19 18.5,19 L14.5,19 C14.2238576,19 14,18.7761424 14,18.5 L14,17.5 C14,17.2238576 14.2238576,17 14.5,17 C14.7761424,17 15,17.2238576 15,17.5 L15,18 L16,18 L16,18 Z M13,11 L13.5,11 C13.7761424,11 14,11.2238576 14,11.5 C14,11.7761424 13.7761424,12 13.5,12 L11.5,12 C11.2238576,12 11,11.7761424 11,11.5 C11,11.2238576 11.2238576,11 11.5,11 L12,11 L12,10 L10.5,10 C10.2238576,10 10,9.77614237 10,9.5 C10,9.22385763 10.2238576,9 10.5,9 L13.5,9 C13.7761424,9 14,9.22385763 14,9.5 C14,9.77614237 13.7761424,10 13.5,10 L13,10 L13,11 Z M18,12 L17.5,12 C17.2238576,12 17,11.7761424 17,11.5 C17,11.2238576 17.2238576,11 17.5,11 L18,11 L18,10.5 C18,10.2238576 18.2238576,10 18.5,10 C18.7761424,10 19,10.2238576 19,10.5 L19,12.5 C19,12.7761424 18.7761424,13 18.5,13 C18.2238576,13 18,12.7761424 18,12.5 L18,12 Z M13,14 L12.5,14 C12.2238576,14 12,13.7761424 12,13.5 C12,13.2238576 12.2238576,13 12.5,13 L13.5,13 C13.7761424,13 14,13.2238576 14,13.5 L14,15.5 C14,15.7761424 13.7761424,16 13.5,16 L10.5,16 C10.2238576,16 10,15.7761424 10,15.5 C10,15.2238576 10.2238576,15 10.5,15 L13,15 L13,14 L13,14 Z M16.1666667,5 L17.8333333,5 C18.4776655,5 19,5.52233446 19,6.16666667 L19,7.83333333 C19,8.47766554 18.4776655,9 17.8333333,9 L16.1666667,9 C15.5223345,9 15,8.47766554 15,7.83333333 L15,6.16666667 C15,5.52233446 15.5223345,5 16.1666667,5 Z M6.16666667,5 L7.83333333,5 C8.47766554,5 9,5.52233446 9,6.16666667 L9,7.83333333 C9,8.47766554 8.47766554,9 7.83333333,9 L6.16666667,9 C5.52233446,9 5,8.47766554 5,7.83333333 L5,6.16666667 C5,5.52233446 5.52233446,5 6.16666667,5 Z M6.16666667,6 C6.07461921,6 6,6.07461921 6,6.16666667 L6,7.83333333 C6,7.92538079 6.07461921,8 6.16666667,8 L7.83333333,8 C7.92538079,8 8,7.92538079 8,7.83333333 L8,6.16666667 C8,6.07461921 7.92538079,6 7.83333333,6 L6.16666667,6 Z M6.16666667,15 L7.83333333,15 C8.47766554,15 9,15.5223345 9,16.1666667 L9,17.8333333 C9,18.4776655 8.47766554,19 7.83333333,19 L6.16666667,19 C5.52233446,19 5,18.4776655 5,17.8333333 L5,16.1666667 C5,15.5223345 5.52233446,15 6.16666667,15 Z M6.16666667,16 C6.07461921,16 6,16.0746192 6,16.1666667 L6,17.8333333 C6,17.9253808 6.07461921,18 6.16666667,18 L7.83333333,18 C7.92538079,18 8,17.9253808 8,17.8333333 L8,16.1666667 C8,16.0746192 7.92538079,16 7.83333333,16 L6.16666667,16 Z M13,6 L10.5,6 C10.2238576,6 10,5.77614237 10,5.5 C10,5.22385763 10.2238576,5 10.5,5 L13.5,5 C13.7761424,5 14,5.22385763 14,5.5 L14,7.5 C14,7.77614237 13.7761424,8 13.5,8 C13.2238576,8 13,7.77614237 13,7.5 L13,6 Z M10.5,8 C10.2238576,8 10,7.77614237 10,7.5 C10,7.22385763 10.2238576,7 10.5,7 L11.5,7 C11.7761424,7 12,7.22385763 12,7.5 C12,7.77614237 11.7761424,8 11.5,8 L10.5,8 Z M5.5,14 C5.22385763,14 5,13.7761424 5,13.5 C5,13.2238576 5.22385763,13 5.5,13 L7.5,13 C7.77614237,13 8,13.2238576 8,13.5 C8,13.7761424 7.77614237,14 7.5,14 L5.5,14 Z M9.5,14 C9.22385763,14 9,13.7761424 9,13.5 C9,13.2238576 9.22385763,13 9.5,13 L10.5,13 C10.7761424,13 11,13.2238576 11,13.5 C11,13.7761424 10.7761424,14 10.5,14 L9.5,14 Z M11,18 L11,18.5 C11,18.7761424 10.7761424,19 10.5,19 C10.2238576,19 10,18.7761424 10,18.5 L10,17.5 C10,17.2238576 10.2238576,17 10.5,17 L12.5,17 C12.7761424,17 13,17.2238576 13,17.5 C13,17.7761424 12.7761424,18 12.5,18 L11,18 Z M9,11 L9.5,11 C9.77614237,11 10,11.2238576 10,11.5 C10,11.7761424 9.77614237,12 9.5,12 L8.5,12 C8.22385763,12 8,11.7761424 8,11.5 L8,11 L7.5,11 C7.22385763,11 7,10.7761424 7,10.5 C7,10.2238576 7.22385763,10 7.5,10 L8.5,10 C8.77614237,10 9,10.2238576 9,10.5 L9,11 Z M5,10.5 C5,10.2238576 5.22385763,10 5.5,10 C5.77614237,10 6,10.2238576 6,10.5 L6,11.5 C6,11.7761424 5.77614237,12 5.5,12 C5.22385763,12 5,11.7761424 5,11.5 L5,10.5 Z M15,10.5 C15,10.2238576 15.2238576,10 15.5,10 C15.7761424,10 16,10.2238576 16,10.5 L16,12.5 C16,12.7761424 15.7761424,13 15.5,13 C15.2238576,13 15,12.7761424 15,12.5 L15,10.5 Z M17,15 L17,14.5 C17,14.2238576 17.2238576,14 17.5,14 L18.5,14 C18.7761424,14 19,14.2238576 19,14.5 C19,14.7761424 18.7761424,15 18.5,15 L18,15 L18,15.5 C18,15.7761424 17.7761424,16 17.5,16 L15.5,16 C15.2238576,16 15,15.7761424 15,15.5 L15,14.5 C15,14.2238576 15.2238576,14 15.5,14 C15.7761424,14 16,14.2238576 16,14.5 L16,15 L17,15 Z M3,6.5 C3,6.77614237 2.77614237,7 2.5,7 C2.22385763,7 2,6.77614237 2,6.5 L2,4.5 C2,3.11928813 3.11928813,2 4.5,2 L6.5,2 C6.77614237,2 7,2.22385763 7,2.5 C7,2.77614237 6.77614237,3 6.5,3 L4.5,3 C3.67157288,3 3,3.67157288 3,4.5 L3,6.5 Z M17.5,3 C17.2238576,3 17,2.77614237 17,2.5 C17,2.22385763 17.2238576,2 17.5,2 L19.5,2 C20.8807119,2 22,3.11928813 22,4.5 L22,6.5 C22,6.77614237 21.7761424,7 21.5,7 C21.2238576,7 21,6.77614237 21,6.5 L21,4.5 C21,3.67157288 20.3284271,3 19.5,3 L17.5,3 Z M6.5,21 C6.77614237,21 7,21.2238576 7,21.5 C7,21.7761424 6.77614237,22 6.5,22 L4.5,22 C3.11928813,22 2,20.8807119 2,19.5 L2,17.5 C2,17.2238576 2.22385763,17 2.5,17 C2.77614237,17 3,17.2238576 3,17.5 L3,19.5 C3,20.3284271 3.67157288,21 4.5,21 L6.5,21 Z M21,17.5 C21,17.2238576 21.2238576,17 21.5,17 C21.7761424,17 22,17.2238576 22,17.5 L22,19.5 C22,20.8807119 20.8807119,22 19.5,22 L17.5,22 C17.2238576,22 17,21.7761424 17,21.5 C17,21.2238576 17.2238576,21 17.5,21 L19.5,21 C20.3284271,21 21,20.3284271 21,19.5 L21,17.5 Z"></path>
                                            </g>
                                        </svg></button>
                                    <a href="<?php echo $putanjaDoOdabraneSkripte; ?>" class="btn btn-success">Preuzmi</a>


                                </div>
                            </div>
                        </div>


                        </br>
                        <div class="row justify-content-center">
                            <div class="col">
                                <?php if (!empty($putanjaDoOdabraneSkripte)) { ?>
                                    <div class="mx-1" style="width: 100%; height: 800px; overflow: auto; -webkit-overflow-scrolling: touch;">
                                        <script>
                                            if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                                                window.location.href = "<?php echo $putanjaDoOdabraneSkripte; ?>";
                                            } else {
                                                document.write('<object data="<?php echo $putanjaDoOdabraneSkripte; ?>" type="application/pdf" width="100%" height="100%"><p>It appears you don\'t have a PDF plugin for this browser. No biggie... you can <a href="<?php echo $putanjaDoOdabraneSkripte; ?>">click here to download the PDF file.</a></p></object>');
                                            }
                                        </script>
                                    </div>
                                <?php } else { ?>
                                    <p>Nažalost, nešto je pošlo po krivu.</p>
                                    <a href="../dashboard/report.php?skripta_id=<?php echo $skripta_id; ?>" style="color: red;">Prijavi</a>
                                <?php } ?>

                                <div class="row m-2 mt-3">
                                    <div class="col">
                                        <p class="card-text">Broj pregleda:
                                            <?php echo "$brojpregleda"; ?>
                                        </p>
                                    </div>
                                    <div class="col text-right">

                                        <?php
                                        $sqlPrijavljenaSkripta = "SELECT * FROM prijavaskripte WHERE skripta_id = $skripta_id";
                                        $rezultatPrijavljenaSkripta = $con->query($sqlPrijavljenaSkripta);
                                        if ($rezultatPrijavljenaSkripta->num_rows > 0) {
                                            $prijavljenaSkripta = false;
                                        } else {
                                            $prijavljenaSkripta = true;
                                        }

                                        if (isset($_SESSION['user_id']) && $prijavljenaSkripta) : ?>
                                            <div class="row">
                                                <div class="col d-flex justify-content-end">
                                                    <a type="button" id="otvoriPrijavu" class="text text-danger" style="font-size: 0.9rem;" data-toggle="modal" data-target="#prijavaSkripte<?php echo $skripta_id ?>">Prijavi skriptu!</a>

                                                    <!-- Modal za prijavu skripte -->
                                                    <div class="modal fade" id="prijavaSkripte<?php echo $skripta_id ?>" tabindex="-1" role="dialog" aria-labelledby="prijavaSkripte<?php echo $skripta_id ?>" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Prijavi skriptu</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <form method="POST">
                                                                        <div class="form-group">
                                                                            <label for="razlogPrijave" class="col-form-label">Razlog prijave:</label>
                                                                            <textarea class="form-control" id="razlogPrijave" name="razlogPrijave"></textarea>
                                                                            <input name="prijavljenaSkripta" value="<?php echo $skripta_id; ?>" hidden>
                                                                        </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Zatvori</button>
                                                                    <button type="submit" class="btn btn-danger" name="prijavi">Prijavi</button>
                                                                </div>


                                                                </form>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php elseif (isset($_SESSION['user_id']) && !$prijavljenaSkripta) : ?>
                                            <div class="row">
                                                <div class="col d-flex justify-content-end">
                                                    <a class="text text-success" style="font-size: 0.9rem;">Skripta prijavljena!</a>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>



                <?php

                $sqlSlicneSkripte = "SELECT * FROM skripte WHERE predmet_id = $predmet_id AND skripta_id != $skripta_id ORDER BY RAND() LIMIT 3";
                $resultSlicneSkripte = $con->query($sqlSlicneSkripte);
                if ($resultSlicneSkripte->num_rows > 0) :
                ?>
                    <div class="row mx-1">
                        <div class="card mx-auto mt-4 mb-4 p-0" style="width: 85%;">

                            <div class="card-body d-flex justify-content-center align-items-center">
                                <div class="row">
                                    <div class="col">
                                        <h5>Slične skripte</h5>
                                    </div>
                                </div>
                            </div>

                            <div class="row mx-1">
                                <?php

                                while ($rowSlicneSkripte = $resultSlicneSkripte->fetch_assoc()) :

                                    $nazivSlicneSkripte = $rowSlicneSkripte['naziv_skripte'];
                                    $skripta_putanja = $rowSlicneSkripte['skripta_putanja'];
                                    $opisSlicneSkripte = $rowSlicneSkripte['opis_skripte'];
                                    $slicna_skripta_id = $rowSlicneSkripte['skripta_id'];
                                    $kreator_id = $rowSlicneSkripte['korisnik_id'];
                                    $imePrezimeKorisnika = dohvatipodatkevlasnika($kreator_id);
                                    $datum = date('d.m.Y', strtotime($rowSlicneSkripte['datum_kreiranja']));
                                ?>
                                    <div class="col-md-4">
                                        <div class="card mb-2">

                                            <?php
                                            $predmet_id = $rowSlicneSkripte['predmet_id'];
                                            $predmetGrupe = "SELECT * FROM  predmeti WHERE predmet_id = $predmet_id";
                                            $rezultatPredmeta = $con->query($predmetGrupe);

                                            while ($red2 = $rezultatPredmeta->fetch_assoc()) :
                                                if ($red2['slika_predmeta'] != null) : ?>
                                                    <img src="<?php echo '../assets/img/predmeti/' . $red2["slika_predmeta"]; ?>" alt="Slika za <?php echo $red2["naziv_predmeta"]; ?>" style="width: 100%; height: 150px; object-fit: cover;">
                                            <?php else :
                                                    echo  '<img src="../assets/img/predmeti/novipredmet.jpg" style="width: 100%; height: 150px; object-fit: cover;">';
                                                endif;
                                            endwhile;
                                            ?>

                                            <div class="card-body" style="height: 250px;">
                                                <h5 class="card-title"><?php echo ((strlen($nazivSlicneSkripte) > 40) ? substr($nazivSlicneSkripte, 0, 40) . '...' : $nazivSlicneSkripte); ?></h5>
                                                <?php
                                                $predmet_id = $rowSlicneSkripte['predmet_id'];
                                                $predmetGrupe = "SELECT  naziv_predmeta, predmet_boja FROM  predmeti WHERE predmet_id = $predmet_id";
                                                $rezultatPredmeta = $con->query($predmetGrupe);

                                                if ($rezultatPredmeta->num_rows > 0) :
                                                    while ($predmetRed = $rezultatPredmeta->fetch_assoc()) :
                                                        $naziv_predmeta = $predmetRed['naziv_predmeta'];
                                                        $predmetBoja = $predmetRed['predmet_boja'];
                                                ?>
                                                        <p class="badge " style="background-color: <?php echo $predmetBoja; ?>;"><?php echo $naziv_predmeta; ?></p>
                                                <?php
                                                    endwhile;
                                                endif;
                                                ?>
                                                <p class="card-text"><?php echo ((strlen($opisSlicneSkripte) > 80) ? substr($opisSlicneSkripte, 0, 80) . '...' : $opisSlicneSkripte); ?></p>
                                                <a href="skripta.php?skripta_id=<?php echo $slicna_skripta_id; ?>" class="btn btn-primary">Pregledaj</a>
                                                <a href="<?php echo $skripta_putanja; ?>" class="btn btn-primary" download>Preuzmi PDF</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                endwhile;
                                ?>
                            </div>
                        </div>
                    </div>
                <?php
                endif;
                ?>
            </div>

        </div>
    </div>
    </div>




    <!-- Modal za QR Kod -->
    <div class="modal fade" id="qrKodModal" tabindex="-1" role="dialog" aria-labelledby="qrKodModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">QR KOD SKRIPTE</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">


                    <div class="row">


                        <div class="col d-flex flex-column justify-content-center">
                            <?php
                            // Get the data URL of the QR code
                            $qrCodeDataUrl = require 'generirajQRKodSkripte.php';
                            ?>

                        </div>
                        <div class="col d-flex flex-column justify-content-center align-items-center">

                            <div class="text-center">
                                <h3><?php echo $nazivSkripte; ?></h3>
                            </div>

                            <h6 class="card-info text-center" style="font-family: Poppins;">
                                <a class="link" href="../profil?korisnik=<?php echo $kreator_id ?>">
                                    <?php echo $imePrezimeKorisnika; ?>
                                </a>,
                                <?php echo "$datum   <br>";
                                echo '<span class="badge" style="background-color: ' . $predmetBoja . ';">' . $predmetNaziv . '</span> '; ?>
                            </h6>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
    <?php include '../ukljucivanje/footer.php'; ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <script src="https://code.jquery.com/jquery-3.7.1.js"> </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../../assets/js/main.js"></script>

    <script>
        $(document).ready(function() {
            $('#qrKodModal').on('show.bs.modal', function(e) {
                $('#qrKodSlika').attr('src', 'generirajQRKodSkripte.php');
            });
        });
    </script>

</body>

</html>
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
                <div class="card mx-auto mt-3 p-0" style="width: 85%;">

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
                                    <a type="button" id="qrKod" class="text text-info" style="font-size: 0.9rem;" data-toggle="modal" data-target="#qrKod">Podijeli skriptu!</a>
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
                                        <a href="../dashboard/report.php?skripta_id=<?php echo $skripta_id; ?>" class="btn btn-danger" style="font-size: 0.9rem;">Prijavi skriptu!</a>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>




                <div class="card mx-auto mt-3 p-0" style="width: 85%;">

                    <div class="card-body d-flex justify-content-center align-items-center">
                        <div class="row">
                            <div class="col">
                                <h5>Slične skripte</h5>
                            </div>
                        </div>
                    </div>

                    <div class="row mx-1">
                        <?php
                        $sqlSlicneSkripte = "SELECT * FROM skripte WHERE predmet_id = $predmet_id AND skripta_id != $skripta_id ORDER BY RAND() LIMIT 3";
                        $resultSlicneSkripte = $con->query($sqlSlicneSkripte);
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

        </div>
    </div>
    </div>


    <div class="modal fade" id="qrKodModal" tabindex="-1" role="dialog" aria-labelledby="qrKodModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Prijava recenzije</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                 <p> BOKKK</p>
                    <div class="modal-body">
                        <img id="qrKodSlika" src="" alt="QR Code">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Zatvori</button>

                </div>



            </div>
        </div>
    </div>


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
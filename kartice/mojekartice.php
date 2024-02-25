<?php
session_start();
$con = require "../ukljucivanje/connection/spajanje.php";
include("../ukljucivanje/functions/funkcije.php");

$trenutnaStranica = "kartice1";

$putanjaDoPocetne = "../";
$putanjaDoInstruktora = "../instruktori.php";
$putanjaDoSkripta = "../skripte/";
$putanjaDoKartica = "../kartice/";
$putanjaDoOnama = "../onama.php";

$putanjaDoPrijave = "../racun/prijava.php";
$putanjaDoRegistracije = "../racun/registracija.php";

$putanjaDoRacuna = "../nadzornaploca";
$putanjaDoOdjave = "../racun/odjava.php";

// Dohvaćanje svih predmeta iz baze podataka
$sqlPredmeti = "SELECT * FROM predmeti";
$rezultatPredmeti = $con->query($sqlPredmeti);


$user = provjeri_prijavu($con);
if (!isset($_SESSION["user_id"])) {
    header("Location: ../racun/prijava.php");
    exit;
} else {
    $korisnikId = $_SESSION["user_id"];
}
// SQL upit za dohvaćanje svih skripti
$sql = "SELECT * FROM grupekartica WHERE vlasnik_id = " . $korisnikId . " ORDER BY grupa_id DESC;";
$result = $con->query($sql);




?>

<!DOCTYPE html>
<html lang="hr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartice za ponavljanje</title>

    <?php include '../assets/css/stiliranjeSporedno.php'; ?>
    <link href="../assets/css/kartice.css" rel="stylesheet">

</head>

<body>

    <?php include '../ukljucivanje/header.php';
    ?>



    <div class="justify-content-md-center mb-4">

        <div class="hero-section text-center" style="background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.2)), url(../assets/img/about.jpg);">
            <div class="container ">
                <div class="row">
                    <div class="col-lg-6 mx-auto mt-2">
                        <h1 class="display-4 " style="color: #FFFFFF;">Vaše kartice za ponavljanje</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="main-body mt-3">
                <?php if (isset($_SESSION['user_id'])) : ?>
                    <div class="col-sm p-0 mb-2">
                        <a class="btn btn-success" href="nova_grupa.php" type="submit"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16" fill="white">
                                <path d="M7.75 2a.75.75 0 0 1 .75.75V7h4.25a.75.75 0 0 1 0 1.5H8.5v4.25a.75.75 0 0 1-1.5 0V8.5H2.75a.75.75 0 0 1 0-1.5H7V2.75A.75.75 0 0 1 7.75 2Z"></path>
                            </svg> Izradi kartice</a>

                    </div>
                <?php endif; ?>
                <div class="row">
                    <div class="col">
                        <div class="row">
                            <?php

                            if (isset($result) > 0) :
                                while ($row = $result->fetch_assoc()) :
                                    $predmet_id = $row['predmet_id'];
                                    $predmetGrupe = "SELECT  naziv_predmeta, predmet_boja FROM  predmeti WHERE predmet_id = $predmet_id";
                                    $rezultatPredmeta = $con->query($predmetGrupe);
                            ?>
                                    <div class="col-sm-3 mb-3 ">
                                        <div class="card klik-za-kartice" style="height: 280px;">


                                            <?php
                                            echo  '<a href="grupa.php?grupa_id=' . $row['grupa_id']  . '"><img src="../assets/img/predmeti/novipredmet.jpg" style="width: 100%; height: 112px; object-fit: cover;"></a>';
                                            ?>

                                            <div class="card-body d-flex flex-column justify-content-between p-0 ">
                                                <div class="d-flex flex-column align-items-center text-center ml-2 mr-2" style="height: 100%; display: flex; flex-direction: column; ">


                                                    <h5 class="card-title pt-2">
                                                        <?php echo (strlen($row["grupa_naziv"]) > 40) ? substr($row["grupa_naziv"], 0, 40) . '...' : $row["grupa_naziv"]; ?>
                                                    </h5>

                                                    <?php
                                                    if ($rezultatPredmeta->num_rows > 0) :
                                                        while ($predmetRed = $rezultatPredmeta->fetch_assoc()) :
                                                            $naziv_predmeta = $predmetRed['naziv_predmeta'];
                                                            $predmetBoja = $predmetRed['predmet_boja'];
                                                    ?>
                                                            <p class="badge mb-2" style="background-color: <?php echo $predmetBoja; ?>;"><?php echo $naziv_predmeta; ?></p>
                                                    <?php
                                                        endwhile;
                                                    endif;
                                                    ?>

                                                    <p class="card-text poppins-light mb-0">
                                                        <?php echo (strlen($row["grupa_opis"]) > 75) ? substr($row["grupa_opis"], 0, 75) . '...' : $row["grupa_opis"]; ?>
                                                    </p>

                                                    <?php if ($row['javno'] == 0) : ?>
                                                        <p class="badge " style="background-color: #687EFF; border-radius: 1.25rem; font-size: 0.7rem;">Privatno</p>
                                                    <?php endif; ?>

                                                </div>

                                            </div>


                                        </div>
                                    </div>

                            <?php
                                endwhile;
                            else :
                                echo "<p class='col'>Nema rezultata.</p>";
                            endif;

                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php

    include '../ukljucivanje/footer.php'; ?>


    <script src="../ukljucivanje/javascript/skripte.js"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <script>
        $(document).ready(function() {
            $('#postavkeTrazilice').on('show.bs.collapse', function() {
                $('.arrow-down').hide();
                $('.arrow-up').show();
            });

            $('#postavkeTrazilice').on('hide.bs.collapse', function() {
                $('.arrow-down').show();
                $('.arrow-up').hide();
            });

            <?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['selectedSubject'])) : ?>
                $('#postavkeTrazilice').collapse('show');
            <?php endif; ?>
        });
    </script>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <script src="../assets/js/main.js"></script>

</body>

</html>
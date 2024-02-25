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

// Dohvaćanje svih predmeta iz baze podataka
$sqlPredmeti = "SELECT * FROM predmeti";
$rezultatPredmeti = $con->query($sqlPredmeti);

// Pretraživanje skripti
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the search term and selected subject from the POST request
    $searchTerm = $_POST["searchTerm"];
    $selectedSubject = $_POST["selectedSubject"];

    // Početni SQL upit za pretraživanje skripti
    $sql = "SELECT * FROM grupekartica WHERE javno = 1";

    // Dodavanje uvjeta pretrage na osnovu unesenog termina pretrage
    if (!empty($searchTerm)) {
        $sql .= " AND (grupa_naziv LIKE '%$searchTerm%' OR grupa_opis LIKE '%$searchTerm%')";
    }

    // Dodavanje uvjeta pretrage na osnovu odabranog predmeta
    if (!empty($selectedSubject)) {
        $sql .= " AND predmet_id = '$selectedSubject'";
    }

    // Izvršavanje SQL upita za pretragu skripti
    $result = $con->query($sql);
} else {
    // Izvršavanje SQL upita za dohvaćanje svih skripti (bez pretrage)
    $sql = "SELECT * FROM grupekartica WHERE javno = 1";
    $result = $con->query($sql);
}



?>

<!DOCTYPE html>
<html lang="hr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pretraži grupe kartica za ponavljanje</title>

    <?php include '../assets/css/stiliranjeSporedno.php'; ?>
    <link href="../assets/css/kartice.css" rel="stylesheet">

</head>

<body>

    <?php include '../ukljucivanje/header.php';
    ?>

    <div class="justify-content-md-center mb-4">

        <div class="hero-section text-center" style="background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.5)), url(../assets/img/leanv2.jpg);">
            <div class="container ">
                <div class="row">
                    <div class="col-lg-6 mx-auto mt-5">
                        <h1 class="display-4 " style="color: #FFFFFF;">Pretražite kartice za ponavljanje</h1>
                        <p class="lead" style="color: #FFFFFF;">Pretražite grupe kartica za ponavljanje iz nekog područja</br> koje su objavili naši korisnici ili učite i <a href="nova_grupa.php" style="color: #ff70fa;">izradite kartice </a> samostalno.</p>
                    </div>
                </div>

                <div class="row d-flex justify-content-center align-items-center m-2">

                    <div class="col-sm-8 ">
                        <div class="card mb-3">
                            <div class="card-body m-2">
                                <form method="POST">

                                    <div class="row d-flex justify-content-center align-items-center mb-2">
                                        <div class="col-sm-8">
                                            <input class="form-control mt-2 mb-2" type="search" placeholder="Pretražite kartice" name="searchTerm">
                                        </div>
                                        <div class="col-sm">
                                            <button class="btn gumb mr-4" type="submit">Pretraživanje</button>
                                            <a href="#postavkeTrazilice" class="btn" data-toggle="collapse" aria-expanded="false" aria-controls="postavkeTrazilice" id="filtrirajTipka">Filter
                                                <svg class="arrow-up" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16" style="display: none;">
                                                    <path d="M3.22 10.53a.749.749 0 0 1 0-1.06l4.25-4.25a.749.749 0 0 1 1.06 0l4.25 4.25a.749.749 0 1 1-1.06 1.06L8 6.811 4.28 10.53a.749.749 0 0 1-1.06 0Z"></path>
                                                </svg>
                                                <svg class="arrow-down" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                                                    <path d="M12.78 5.22a.749.749 0 0 1 0 1.06l-4.25 4.25a.749.749 0 0 1-1.06 0L3.22 6.28a.749.749 0 1 1 1.06-1.06L8 8.939l3.72-3.719a.749.749 0 0 1 1.06 0Z"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>


                                    <div class="collapse animate__animated animate__slideinDown" id="postavkeTrazilice">
                                        <div class="row justify-content-md-center mt-3">
                                            <div class="col-sm">
                                                <span class="text-muted">Predmet</span>
                                                <select class="form-select" id="selectedSubject" name="selectedSubject">
                                                    <option value="">Odaberite predmet</option>
                                                    <?php

                                                    while ($row = $rezultatPredmeti->fetch_assoc()) {
                                                        echo "<option value='" . $row['predmet_id'] . "'>" . $row['naziv_predmeta'] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="col-sm d-flex justify-content-center align-self-end">
                                                <a href="../kartice/" class="btn btn-outline-danger">Izbrišite filter</a>
                                            </div>
                                        </div>
                                    </div>


                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container pt-2">
            <?php if (isset($_SESSION['user_id'])) : ?>
                <div class="col-sm mb-1 ">
                    <a class="btn gumb" href="nova_grupa.php" type="submit"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16" fill="white">
                            <path d="M7.75 2a.75.75 0 0 1 .75.75V7h4.25a.75.75 0 0 1 0 1.5H8.5v4.25a.75.75 0 0 1-1.5 0V8.5H2.75a.75.75 0 0 1 0-1.5H7V2.75A.75.75 0 0 1 7.75 2Z"></path>
                        </svg> Izradite kartice</a>

                </div>
            <?php endif; ?>
            <div class="main-body">
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
                                        <a href="grupa.php?grupa_id='<?php echo $row['grupa_id'];  ?> '">
                                            <div class="card" style="height: 280px;">


                                                <?php
                                                echo  '<img src="../assets/img/predmeti/novipredmet.jpg" style="width: 100%; height: 112px; object-fit: cover;">';
                                                ?>

                                                <div class="card-body d-flex flex-column justify-content-between p-0 ">
                                                    <div class="d-flex flex-column align-items-center text-center ml-2 mr-2" style="height: 100%; display: flex; flex-direction: column; ">


                                                        <h5 class="card-title pt-2" style="color: #980c94;">
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
                                                            <?php echo (strlen($row["grupa_opis"]) > 120) ? substr($row["grupa_opis"], 0, 120) . '...' : $row["grupa_opis"]; ?>
                                                        </p>


                                                    </div>

                                                </div>


                                            </div>
                                        </a>
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

    <?php include '../ukljucivanje/footer.php'; ?>


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
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

// Dohvaćanje svih predmeta iz baze podataka
$sqlPredmeti = "SELECT * FROM predmeti";
$resultPredmeti = $con->query($sqlPredmeti);

// Pretraživanje skripti
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the search term and selected subject from the POST request
    $searchTerm = $_POST["searchTerm"];
    $selectedSubject = $_POST["selectedSubject"];

    // SQL query to search scripts
    $sql = "SELECT skripte.*, predmeti.naziv_predmeta, predmeti.predmet_boja FROM skripte INNER JOIN predmeti ON skripte.predmet_id = predmeti.predmet_id WHERE 1=1";

    if (!empty($searchTerm)) {
        $sql .= " AND (skripte.naziv_skripte LIKE '%$searchTerm%' OR skripte.opis_skripte LIKE '%$searchTerm%' OR predmeti.naziv_predmeta LIKE '%$searchTerm%')";
    }

    if (!empty($selectedSubject)) {
        $sql .= " AND skripte.predmet_id = $selectedSubject";
    }

    // Execute the SQL query to search scripts
    $result = $con->query($sql);
} else {
    // SQL upit za dohvaćanje svih skripti
    $sql = "SELECT * FROM skripte";
    $result = $con->query($sql);
}


?>

<!DOCTYPE html>
<html lang="hr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pretraži Skripte</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <?php include '../assets/css/stiliranjeSporedno.php'; ?> <!-- Sve poveznice za stil web stranice -->

    <link href="../assets/css/skripte.css" rel="stylesheet">

</head>

<body>

    <?php include '../ukljucivanje/header.php'; ?>

    <div class="justify-content-md-center mb-4">

        <div class="hero-section text-center" style="background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.2)), url(../assets/img/about.jpg);">
            <div class="container ">
                <div class="row">
                    <div class="col-sm-8 mx-auto mt-5">
                        <h1 class="display-4 " style="color: #FFFFFF;">Pretražite skripte</h1>
                        <p class="lead" style="color: #FFFFFF;">Pretražite skripte koje su objavili naši korisnici i uživajte u besplatnim </br> pogodnostima platforme Šalabahter ili <a href="nova_skripta.phps">objavite skriptu samostalno</a> i pomognite ostalima.</p>
                    </div>
                </div>

                <div class="row d-flex justify-content-center align-items-center m-2">
                    <div class="col-sm-6 ">
                        <div class="card mb-3">
                            <div class="card-body m-2">
                                <form method="POST">

                                    <div class="row d-flex justify-content-center align-items-center mb-2">
                                        <div class="col-sm-6">
                                            <input class="form-control mt-2 mb-2" type="search" placeholder="Pretražite skripte" name="searchTerm">
                                        </div>
                                        <div class="col-sm">

                                            <button class="btn btn-success mt-2 mr-5 mb-2" id="pretrazi" type="submit">Pretraživanje</button>

                                            <a href="#postavkeTrazilice" data-toggle="collapse" aria-expanded="false" aria-controls="postavkeTrazilice" id="filtrirajTipka">Filter
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

                                                    while ($row = $resultPredmeti->fetch_assoc()) {
                                                        echo "<option value='" . $row['predmet_id'] . "'>" . $row['naziv_predmeta'] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="row mt-2 d-flex align-items-center justify-content-center">
                                                <div class="col" id="trazilica">
                                                    <a href="../skripte/" class="btn btn-outline-danger mt-2 ml-2 mr-2" id="izbrisi">Izbrišite filter</a>

                                                </div>
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
                <div class="col-sm mb-1">
                    <a class="btn btn-success" href="nova_skripta.php" type="submit"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16" fill="white">
                            <path d="M7.75 2a.75.75 0 0 1 .75.75V7h4.25a.75.75 0 0 1 0 1.5H8.5v4.25a.75.75 0 0 1-1.5 0V8.5H2.75a.75.75 0 0 1 0-1.5H7V2.75A.75.75 0 0 1 7.75 2Z"></path>
                        </svg> Dodajte skriptu</a>
                </div>
            <?php endif; ?>
            <div class="main-body">
                <div class="row">
                    <div class="col">
                        <div class="row">
                            <?php
                            // Prikaz rezultata
                            if (isset($result) > 0) :
                                while ($red = $result->fetch_assoc()) :
                                    $predmet_id = $red['predmet_id'];
                                    $predmetGrupe = "SELECT * FROM  predmeti WHERE predmet_id = $predmet_id";
                                    $rezultatPredmeta = $con->query($predmetGrupe);
                            ?>
                                    <div class="col-sm-3 mb-3 ">
                                        <a href="skripta.php?skripta_id=<?php echo $red["skripta_id"]; ?>">
                                            <div class="card">

                                                <?php
                                                while ($red2 = $rezultatPredmeta->fetch_assoc()) :
                                                    if ($red2['slika_predmeta'] != null) : ?>
                                                        <img src="<?php echo '../assets/img/predmeti/' . $red2["slika_predmeta"]; ?>" alt="Slika za <?php echo $red2["naziv_predmeta"]; ?>" style="width: 100%; height: 150px; object-fit: cover;">
                                                <?php else :
                                                        echo  '<img src="../assets/img/predmeti/novipredmet.jpg" style="width: 100%; height: 150px; object-fit: cover;">';
                                                    endif;
                                                endwhile;
                                                ?>
                                                <div class="card-body d-flex flex-column justify-content-between">
                                                    <div class="d-flex flex-column align-items-center text-center" style="height: 100%; display: flex; flex-direction: column; justify-content: space-between;">
                                                        <div>

                                                            <?php
                                                            $rezultatPredmeta->data_seek(0);
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

                                                            <h5 class="card-title">
                                                                <?php echo (strlen($red["naziv_skripte"]) > 20) ? substr($red["naziv_skripte"], 0, 20) . '...' : $red["naziv_skripte"]; ?>
                                                            </h5>

                                                        </div>

                                                        <div class="row mt-auto">
                                                            <div class="col">
                                                                <a href="<?php echo $red["skripta_putanja"]; ?>" class="text-success" download><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                                                                        <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5" />
                                                                        <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z" />
                                                                    </svg></a>
                                                            </div>
                                                        </div>

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
<?php
session_start();
$con = require "../ukljucivanje/connection/spajanje.php";
include("../ukljucivanje/functions/funkcije.php");


$trenutnaStranica = "skripte";

$putanjaDoPocetne = "../";
$putanjaDoInstruktora = "../instruktori.php";
$putanjaDoSkripta = "../skripte/";
$putanjaDoKartica = "../kartice.php";
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

    <?php include '../assets/css/stiliranjeSporedno.php'; ?> <!-- Sve poveznice za stil web stranice -->

</head>

<body>

    <?php include '../ukljucivanje/header.php'; ?>

    <div class="justify-content-md-center mb-4">

        <div class="hero-section text-center" style="background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.2)), url(../assets/img/about.jpg);">
            <div class="container ">
                <div class="row">
                    <div class="col-lg-6 mx-auto mt-5">
                        <h1 class="display-4 " style="color: #FFFFFF;">Pretraži Skripte</h1>
                        <p class="lead" style="color: #FFFFFF;">Pretražite skripte koje su objavili naši korisnici i uživajte u besplatnim </br> pogodnostima platforme Šalabahter, ili <a href="nova_skripta.phps">objavite skriptu samostalno</a> i pomognite ostalima.</p>
                    </div>
                </div>

                <div class="row d-flex justify-content-center align-items-center m-2">
                    <div class="col-sm-6 ">
                        <div class="card mb-3">
                            <div class="card-body m-2">
                                <form method="POST">

                                    <div class="row d-flex justify-content-center align-items-center mb-2">
                                        <div class="col-sm-5">
                                            <input class="form-control mt-2 mb-2" type="search" placeholder="Pretraži skripte" name="searchTerm">
                                        </div>
                                        <div class="col-sm-5 p-1">
                                            <button class="btn btn-success" type="submit">Pretraži</button>
                                            <a href="#postavkeTrazilice" class="btn" data-toggle="collapse" aria-expanded="false" aria-controls="postavkeTrazilice" id="filtrirajTipka">Filtriraj
                                                <svg class="arrow-up" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16" style="display: none;">
                                                    <path d="M3.22 10.53a.749.749 0 0 1 0-1.06l4.25-4.25a.749.749 0 0 1 1.06 0l4.25 4.25a.749.749 0 1 1-1.06 1.06L8 6.811 4.28 10.53a.749.749 0 0 1-1.06 0Z"></path>
                                                </svg>
                                                <svg class="arrow-down" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                                                    <path d="M12.78 5.22a.749.749 0 0 1 0 1.06l-4.25 4.25a.749.749 0 0 1-1.06 0L3.22 6.28a.749.749 0 1 1 1.06-1.06L8 8.939l3.72-3.719a.749.749 0 0 1 1.06 0Z"></path>
                                                </svg>
                                            </a>
                                        </div>

                                        <?php if(isset($_SESSION['user_id'])):?>
                                        <div class="col-sm p-1">
                                            <a class="btn btn-success" href="nova_skripta.php" type="submit"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16" fill="white">
                                                    <path d="M7.75 2a.75.75 0 0 1 .75.75V7h4.25a.75.75 0 0 1 0 1.5H8.5v4.25a.75.75 0 0 1-1.5 0V8.5H2.75a.75.75 0 0 1 0-1.5H7V2.75A.75.75 0 0 1 7.75 2Z"></path>
                                                </svg></a>

                                        </div>
                                        <?php endif; ?>


                                    </div>


                                    <div class="collapse animate__animated animate__slideinDown" id="postavkeTrazilice">
                                        <div class="row justify-content-md-center mt-3">
                                            <div class="col-sm">
                                                <span class="text-muted">Predmet</span>
                                                <select class="form-select" id="selectedSubject" name="selectedSubject">
                                                    <option value="">Odaberi predmet</option>
                                                    <?php

                                                    while ($row = $resultPredmeti->fetch_assoc()) {
                                                        echo "<option value='" . $row['predmet_id'] . "'>" . $row['naziv_predmeta'] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="col-sm d-flex justify-content-center align-self-end">
                                                <a href="../skripte/" class="btn btn-outline-danger">Izbriši filter</a>
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

        <div class="container">
            <div class="main-body mt-3">
                <div class="row">
                    <div class="col">
                        <div class="row">
                            <?php
                            // Prikaz rezultata
                            if (isset($result) > 0) :
                                while ($row = $result->fetch_assoc()) :
                                    $predmet_id = $row['predmet_id'];
                                    $predmetSkripte = "SELECT  naziv_predmeta, predmet_boja FROM  predmeti WHERE predmet_id = $predmet_id";
                                    $rezultatPredmeta = $con->query($predmetSkripte);
                            ?>
                                    <div class="col-sm-3 mb-3 ">
                                        <div class="card" style="height: 270px;">
                                            <div class="card-body d-flex flex-column justify-content-between">
                                                <div class="d-flex flex-column align-items-center text-center" style="height: 100%; display: flex; flex-direction: column; justify-content: space-between;">
                                                    <div class="mt-3">

                                                        <h5 class="card-title">
                                                            <?php echo (strlen($row["naziv_skripte"]) > 40) ? substr($row["naziv_skripte"], 0, 40) . '...' : $row["naziv_skripte"]; ?>
                                                        </h5>

                                                        <?php
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
                                                        <p class="card-text">
                                                            <?php echo (strlen($row["opis_skripte"]) > 120) ? substr($row["opis_skripte"], 0, 120) . '...' : $row["opis_skripte"]; ?>
                                                        </p>
                                                    </div>

                                                    <div class="row mt-auto">
                                                        <div class="col">
                                                            <a href="skripta.php?skripta_id=<?php echo $row["skripta_id"]; ?>" class="btn btn-primary">Pregledaj</a>
                                                            <a href="<?php echo $row["skripta_putanja"]; ?>" class="btn btn-primary" download>Preuzmi PDF</a>
                                                        </div>
                                                    </div>

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
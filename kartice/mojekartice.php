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

// Pretraživanje skripti
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the search term and selected subject from the POST request
    $searchTerm = $_POST["searchTerm"];
    $selectedSubject = $_POST["selectedSubject"];

    // SQL query to search scripts
    $sql = "SELECT * FROM grupekartica";

    if (!empty($searchTerm)) {
        $sql .= " WHERE (grupa_naziv LIKE '%$searchTerm%' OR grupa_opis LIKE '%$searchTerm%')";
    }

    if (!empty($selectedSubject)) {
        if (strpos($sql, 'WHERE') !== false) {
            
            $sql .= " AND predmet_id = '$selectedSubject'";
        } else {
            
            $sql .= " WHERE predmet_id = '$selectedSubject'";
        }
    }


    // Execute the SQL query to search scripts
    $result = $con->query($sql);
} else {
    // SQL upit za dohvaćanje svih skripti
    $sql = "SELECT * FROM grupekartica";
    $result = $con->query($sql);
}


?>

<!DOCTYPE html>
<html lang="hr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pretraži grupe kartica za ponavljanje</title>

    <?php include '../assets/css/stiliranjeSporedno.php'; ?> <!-- Sve poveznice za stil web stranice -->

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
                                        <div class="card" style="height: 270px;">
                                            <div class="card-body d-flex flex-column justify-content-between">
                                                <div class="d-flex flex-column align-items-center text-center" style="height: 100%; display: flex; flex-direction: column; justify-content: space-between;">
                                                    <div class="mt-3">

                                                        <h5 class="card-title">
                                                            <?php echo (strlen($row["grupa_naziv"]) > 40) ? substr($row["grupa_naziv"], 0, 40) . '...' : $row["grupa_naziv"]; ?>
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
                                                            <?php echo (strlen($row["grupa_opis"]) > 120) ? substr($row["grupa_opis"], 0, 120) . '...' : $row["grupa_opis"]; ?>
                                                        </p>
                                                    </div>

                                                    <div class="row mt-auto">
                                                        <div class="col">
                                                            <a href="grupa.php?grupa_id=<?php echo $row["grupa_id"]; ?>" class="btn btn-primary">Pregledaj</a>
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
<?php
session_start();
$con = require "../includes/connection/spajanje.php";
include("../includes/functions/funkcije.php");

$trenutnaStranica = "skripte";

$putanjaDoPocetne = "../";
$putanjaDoInstruktora = "../instruktori.php";
$putanjaDoSkripta = "../skripte/";
$putanjaDoKartica = "../kartice.php";
$putanjaDoOnama = "../onama.php";

$putanjaDoPrijave = "../racun/login.php";
$putanjaDoRegistracije = "../racun/register.php";

$putanjaDoRacuna = "../nadzornaploca";
$putanjaDoOdjave = "../racun/odjava.php";

// Dohvaćanje svih predmeta iz baze podataka
$sqlPredmeti = "SELECT * FROM predmeti";
$resultPredmeti = $con->query($sqlPredmeti);
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pretraži Skripte</title>
    
    <?php include '../assets/css/stiliranjeSporedno.php'; ?>  <!-- Sve poveznice za stil web stranice -->

</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="container mt-5 mb-4">
    <h1 class="text-center mb-4">Pretraži Skripte</h1>
  
    <div class="container text-center mb-4">
        <h5>Pretražite skripte koje su objavili naši korisnici i uživajte u besplatnim </br> pogodnostima platforme Šalabahter, ili <a href="nova_skripta.phps">objavite skriptu samostalno</a> i pomognite ostalima.</h5>
    </div>  

    <form class="mb-4" method="post" action="">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Pretraži skripte..." name="searchTerm">
            <select class="form-select" id="selectedSubject" name="selectedSubject">
                <option value="">Odaberi predmet</option>
                <?php
                
                while ($row = $resultPredmeti->fetch_assoc()) {
                    echo "<option value='".$row['predmet_id']."'>".$row['naziv_predmeta']."</option>";
                }
                ?>
            </select>
            <button class="btn btn-primary" type="submit">Pretraži</button>
        </div>
    </form>
    
    <div class="row">
        <?php
        // Pretraživanje skripti
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Dohvati parametre pretraživanja iz POST zahtjeva
            $searchTerm = $_POST["searchTerm"];
            $selectedSubject = $_POST["selectedSubject"];

            // SQL upit za pretraživanje skripti
            $sql = "SELECT * FROM skripte";
            if (!empty($searchTerm) && empty($selectedSubject)) {
                $sql .= " WHERE naziv_skripte OR opis_skripte LIKE '%$searchTerm%'";
            } elseif (!empty($searchTerm) && !empty($selectedSubject)) {
                $sql .= " WHERE naziv_skripte opis_skripte LIKE '%$searchTerm%' AND predmet_id = $selectedSubject";
            } elseif (empty($searchTerm) && !empty($selectedSubject)) {
                $sql .= " WHERE predmet_id = $selectedSubject";
            }

            // Izvršite SQL upit za pretraživanje skripti
            $result = $con->query($sql);

            // Prikaz rezultata
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Prikaz skripte (npr. koristeći kartice)
                    echo '
                    <div class="col-md-4">
                    <div class="card mb-2">
                        <div class="card-body" style="height: 230px;">
                        <h5 class="card-title">' . ((strlen($row["naziv_skripte"]) > 40) ? substr($row["naziv_skripte"], 0, 40) . '...' : $row["naziv_skripte"]) . '</h5>
                        ';
                        $predmet_id = $row['predmet_id'];
                        $predmetSkripte= "SELECT  naziv_predmeta, predmet_boja FROM  predmeti WHERE predmet_id = $predmet_id";
                        $rezultatPredmeta = $con->query($predmetSkripte);
                                                
                        if ($rezultatPredmeta->num_rows > 0) {
                            while ($predmetRed = $rezultatPredmeta->fetch_assoc()) {
                                $naziv_predmeta = $predmetRed['naziv_predmeta'];
                                $predmetBoja = $predmetRed['predmet_boja'];
                                echo '<p class="badge " style="background-color: ' . $predmetBoja . ';">' . $naziv_predmeta . '</p> ';
                            }
                        }
                        echo'    
                            <p class="card-text">' . ((strlen($row["opis_skripte"]) > 120) ? substr($row["opis_skripte"], 0, 120) . '...' : $row["opis_skripte"]) . '</p>
                            <a href="skripta.php?skripta_id=' . $row["skripta_id"] . '" class="btn btn-primary">Pregledaj</a>
                            <a href="' . $row["skripta_putanja"] . '" class="btn btn-primary" download>Preuzmi PDF</a>
                        </div>
                    </div>
                </div>';             
                }
            } else {
                echo "<p class='col'>Nema rezultata.</p>";
            }
        } else {
            // Prikaz prvih 10 skripti ako korisnik nije još ništa pretraživao
            $sql = "SELECT * FROM skripte LIMIT 25";
            $result = $con->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Prikaz skripte (npr. koristeći kartice)
                    echo '
                    <div class="col-md-4">
                        <div class="card mb-2">
                            <div class="card-body" style="height: 230px;">
                            <h5 class="card-title">' . ((strlen($row["naziv_skripte"]) > 40) ? substr($row["naziv_skripte"], 0, 40) . '...' : $row["naziv_skripte"]) . '</h5>
                            ';
                            $predmet_id = $row['predmet_id'];
                            $predmetSkripte= "SELECT  naziv_predmeta, predmet_boja FROM  predmeti WHERE predmet_id = $predmet_id";
                            $rezultatPredmeta = $con->query($predmetSkripte);
                                                    
                            if ($rezultatPredmeta->num_rows > 0) {
                                while ($predmetRed = $rezultatPredmeta->fetch_assoc()) {
                                    $naziv_predmeta = $predmetRed['naziv_predmeta'];
                                    $predmetBoja = $predmetRed['predmet_boja'];
                                    echo '<p class="badge " style="background-color: ' . $predmetBoja . ';">' . $naziv_predmeta . '</p> ';
                                }
                            }
                            echo'    
                                <p class="card-text">' . ((strlen($row["opis_skripte"]) > 120) ? substr($row["opis_skripte"], 0, 120) . '...' : $row["opis_skripte"]) . '</p>
                                <a href="skripta.php?skripta_id=' . $row["skripta_id"] . '" class="btn btn-primary">Pregledaj</a>
                                <a href="' . $row["skripta_putanja"] . '" class="btn btn-primary" download>Preuzmi PDF</a>
                            </div>
                        </div>
                    </div>';

                  
                }
            } else {
                echo "<p class='col'>Nema dostupnih skripti.</p>";
            }
        }
        ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include '../includes/footer.php'; ?>

</body>
</html>

<?php
// Početak sesije
session_start();

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

// Uključivanje datoteke za spajanje na bazu podataka i funkcija
$con = require "../ukljucivanje/connection/spajanje.php";
include("../ukljucivanje/functions/funkcije.php");

$user = provjeri_prijavu($con);
if (!isset($_SESSION["user_id"])) {
    header("Location: ../racun/prijava.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["skripta"]) && $_FILES["skripta"]["error"] == 0) {
        $uploadDir = "skripte/";
        $uniqueFilename = uniqid() . "_" . $_FILES["skripta"]["name"];
        $skripta_putanja = $uploadDir . $uniqueFilename;

        if (move_uploaded_file($_FILES["skripta"]["tmp_name"], $skripta_putanja)) {

            $nazivSkripte = $_POST["naziv_skripte"];
            $opisSkripte = $_POST["opis_skripte"];
            $korisnikId = $_SESSION["user_id"];
            if (isset($_POST["predmet_id"]) && !empty($_POST["predmet_id"])) {
                $predmetId = $_POST["predmet_id"];
                $datumKreiranja = date('Y-m-d');

                $sqlInsertSkripta = "INSERT INTO skripte (naziv_skripte, opis_skripte, skripta_putanja, korisnik_id, predmet_id, datum_kreiranja) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $con->prepare($sqlInsertSkripta);
                $stmt->bind_param("sssiis", $nazivSkripte, $opisSkripte, $skripta_putanja, $korisnikId, $predmetId, $datumKreiranja);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    echo "Skripta je uspješno prenesena i pohranjena.";
                    header("Location: skripta.php?skripta_id=" . $stmt->insert_id);
                    exit;
                } else {
                    echo "Došlo je do greške pri unosu informacija o skripti.";
                }
            } else {
                echo "Molimo odaberite predmet.";
            }
        } else {
            echo "Došlo je do greške pri prijenosu datoteke.";
        }
    } else {
        echo "Prijenos datoteke nije uspio.";
    }
}
?>

<!DOCTYPE html>
<html lang="hr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prijenos Skripte</title>
    <?php include '../assets/css/stiliranjeSporedno.php'; ?>

    <link href="../assets/css/skripte.css" rel="stylesheet">

</head>

<body>
    <?php include '../ukljucivanje/header.php'; ?>

    <div class="hero-section text-center" style="background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.2)), url(../assets/img/about.jpg);">
        <div class="row justify-content-center ">
            <div class="col-sm-8 mt-3 mb-3">
            <h1 class="display-4 " style="color: #FFFFFF;">Prenesite svoju skriptu</h1>
            <p class="lead" style="color: #FFFFFF;">Radite svoje skripte za učenje? Slobodno ih podijelite i sa drugima.</p>
            </div>
        </div>
    </div>

    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-8 mt-4">

                <div class="card">
                    <div class="card-body">

                        <h2 class="text-center mb-4">Prijenos skripte</h2>

                        <form method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="naziv_skripte">Naziv skripte</label>
                                <input type="text" class="form-control" id="naziv_skripte" name="naziv_skripte" maxlength="150" required>
                            </div>
                            <div class="form-group">
                                <label for="opis_skripte">Opis skripte</label>
                                <textarea class="form-control" id="opis_skripte" name="opis_skripte" rows="3" maxlength="255" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="predmet_id">Predmet</label>
                                <select class="form-control" id="predmet_id" name="predmet_id" required>
                                    <option value="">Odaberite predmet</option>
                                    <?php
                                    // Dohvat svih predmeta iz baze podataka
                                    $sqlPredmeti = "SELECT * FROM predmeti";
                                    $resultPredmeti = $con->query($sqlPredmeti);

                                    // Iteracija kroz rezultat i prikaz opcija u select elementu
                                    while ($row = $resultPredmeti->fetch_assoc()) {
                                        echo "<option value='" . $row['predmet_id'] . "'>" . $row['naziv_predmeta'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="skripta">Skripta (PDF)</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="skripta" name="skripta" accept=".pdf" required>
                                    <label class="custom-file-label" for="skripta" data-browse="Traži">Odaberite PDF datoteku</label>
                                </div>
                            </div>

                            <script>
                                var skriptaInput = document.getElementById('skripta');
                                skriptaInput.addEventListener('dragover', handleDragOver, false);
                                skriptaInput.addEventListener('drop', handleFileSelect, false);

                                function handleDragOver(event) {
                                    event.stopPropagation();
                                    event.preventDefault();
                                    event.dataTransfer.dropEffect = 'copy';
                                }

                                function handleFileSelect(event) {
                                    event.stopPropagation();
                                    event.preventDefault();

                                    var files = event.dataTransfer.files;
                                    skriptaInput.files = files;
                                }

                                document.querySelector('.custom-file-input').addEventListener('change', function(e) {
                                    // Get the selected file name
                                    var imeDatoteke = e.target.files[0].name;

                                    // Update the label text
                                    var labela = e.target.nextElementSibling;
                                    labela.innerText = imeDatoteke;
                                });
                            </script>
                            <button type="submit" class="btn btn-primary mt-3">Prijenos</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <script src="../assets/js/main.js"></script>

</body>

</html>
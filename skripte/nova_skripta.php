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

// Provjera prijave korisnika
$user = provjeri_prijavu($con);
if (!isset($_SESSION["user_id"])) {
    header("Location: ../racun/prijava.php");
    exit;
}

// Provjera podnesenog obrasca za prijenos datoteka
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Provjera uspješnosti prijenosa datoteke
    if (isset($_FILES["skripta"]) && $_FILES["skripta"]["error"] == 0) {
        // Direktorij za pohranu skripti
        $uploadDir = "skripte/";

        // Generiranje jedinstvenog imena datoteke
        $uniqueFilename = uniqid() . "_" . $_FILES["skripta"]["name"];

        // Putanja do pohranjene datoteke
        $skripta_putanja = $uploadDir . $uniqueFilename;

        // Pohrana datoteke
        if (move_uploaded_file($_FILES["skripta"]["tmp_name"], $skripta_putanja)) {
            // Informacije o skripti
            $nazivSkripte = $_POST["naziv_skripte"];
            $opisSkripte = $_POST["opis_skripte"];
            $korisnikId = $_SESSION["user_id"];

            // Provjera odabranog predmeta
            if (isset($_POST["predmet_id"]) && !empty($_POST["predmet_id"])) {
                $predmetId = $_POST["predmet_id"];

                // Datum kreiranja
                $datumKreiranja = date('Y-m-d'); 

                // Priprema SQL upita za unos informacija o skripti u bazu podataka
                $sqlInsertSkripta = "INSERT INTO skripte (naziv_skripte, opis_skripte, skripta_putanja, korisnik_id, predmet_id, datum_kreiranja) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $con->prepare($sqlInsertSkripta);
                $stmt->bind_param("sssiis", $nazivSkripte, $opisSkripte, $skripta_putanja, $korisnikId, $predmetId, $datumKreiranja);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    // Uspješno dodana skripta
                    echo "Skripta je uspješno prenesena i pohranjena.";
                    header("Location: skripta.php?skripta_id=" . $stmt->insert_id);
                    exit;
                } else {
                    // Greška pri unosu informacija o skripti
                    echo "Došlo je do greške pri unosu informacija o skripti.";
                }
            } else {
                // Poruka ako predmet nije odabran
                echo "Molimo odaberite predmet.";
            }
        } else {
            // Greška pri prijenosu datoteke
            echo "Došlo je do greške pri prijenosu datoteke.";
        }
    } else {
        // Greška pri prijenosu datoteke
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



</head>

<body>
    <?php include '../ukljucivanje/header.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Prijenos Skripte</h2>
                    </div>

                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="naziv_skripte">Naziv Skripte</label>
                                <input type="text" class="form-control" id="naziv_skripte" name="naziv_skripte" maxlength="254" required>
                            </div>
                            <div class="form-group">
                                <label for="opis_skripte">Opis Skripte</label>
                                <textarea class="form-control" id="opis_skripte" name="opis_skripte" rows="3"
                                    required></textarea>
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
                                <div class="card">
                                    <input type="file" class="form-control-file" id="skripta" name="skripta" accept=".pdf" required>
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
                            </script>
                            <button type="submit" class="btn btn-primary">Prijenos</button>
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
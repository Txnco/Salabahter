<?php
// Početak sesije
session_start();

// Uključivanje veze s bazom podataka i funkcija
$con = require "includes/connection/spajanje.php";
include("includes/functions/funkcije.php");

// Putanje do različitih stranica
$trenutnaStranica = "skripte";

$putanjaDoPocetna ='index.php';
$pathToAutomobili='automobili.php';
$putanjaDoInstruktora ='instruktori.php';
$pathToLogin="account/login.php";
$pathToRegister="account/register.php";
$pathToRacun="dashboard/";
$pathToLogout="account/logout.php";

// SQL upit za dohvaćanje svih instruktora
$sqlSviInstruktori = "SELECT korisnik.korisnik_id, instruktori.instruktor_id, predmeti.predmet_id, ime,prezime,email,adresa,naziv_grada, status_naziv, naziv_predmeta FROM korisnik, statuskorisnika, instruktori,instruktorovipredmeti,predmeti,gradovi WHERE korisnik.korisnik_id=instruktori.korisnik_id AND instruktori.instruktor_id=instruktorovipredmeti.instruktor_id AND instruktorovipredmeti.predmet_id=predmeti.predmet_id AND korisnik.status_korisnika=statuskorisnika.status_id AND korisnik.mjesto=gradovi.grad_id";
$resultSviInstruktori = $con->query($sqlSviInstruktori);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skripte</title>
    
    <?php include 'assets/css/stiliranjeGlavno.php'; ?>   <!-- Sve poveznice za stil web stranice -->
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php
        // Provjera ako je obrazac za prijenos datoteka podnesen
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Provjera uspješnosti prijenosa datoteke
            if (isset($_FILES["skripta"]) && $_FILES["skripta"]["error"] == 0) {
                // Direktorij za pohranu skripti
                $uploadDir = "skripte/";
                
                // Generiranje jedinstvenog imena datoteke
                $uniqueFilename = uniqid() . "_" . $_FILES["skripta"]["name"];
                
                // Putanja do pohranjene datoteke
                $uploadPath = $uploadDir . $uniqueFilename;
                
                // Pohrana datoteke
                if (move_uploaded_file($_FILES["skripta"]["tmp_name"], $uploadPath)) {
                    // Informacije o skripti
                    $nazivSkripte = $_POST["naziv_skripte"];
                    $opisSkripte = $_POST["opis_skripte"];
                    $korisnikId = $_SESSION["user_id"]; // ID trenutno prijavljenog korisnika
                    
                    // Provjera odabranog predmeta
                    if (isset($_POST["predmet_id"]) && !empty($_POST["predmet_id"])) {
                        $predmetId = $_POST["predmet_id"];
                        
                        // Datum kreiranja
                        $datumKreiranja = date('Y-m-d'); // Formatirani datum (npr. "2024-02-05")
                        
                        // SQL upit za unos informacija o skripti u bazu podataka
                        $sqlInsertSkripta = "INSERT INTO skripte (naziv_skripte, opis_skripte, skripta_putanja, korisnik_id, predmet_id, datum_kreiranja) VALUES ('$nazivSkripte', '$opisSkripte', '$uploadPath', '$korisnikId', '$predmetId', '$datumKreiranja')";
                        
                        // Izvršenje SQL upita
                        if ($con->query($sqlInsertSkripta) === TRUE) {
                            // Uspješno dodana skripta
                            echo "Skripta je uspješno prenesena i pohranjena.";
                            header("Location: skripta.php");
                            exit;
                        } else {
                            // Greška pri unosu informacija o skripti
                            echo "Error: " . $sqlInsertSkripta . "<br>" . $con->error;
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
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="mb-3">Upload Skripte</h2>
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="naziv_skripte" class="form-label">Naziv Skripte</label>
                        <input type="text" class="form-control" id="naziv_skripte" name="naziv_skripte" required>
                    </div>
                    <div class="mb-3">
                        <label for="opis_skripte" class="form-label">Opis Skripte</label>
                        <textarea class="form-control" id="opis_skripte" name="opis_skripte" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="predmet_id" class="form-label">Predmet</label>
                        <select class="form-select" id="predmet_id" name="predmet_id" required>
                            <option value="">Odaberite predmet</option>
                            <?php
                            // Dohvat svih predmeta iz baze podataka
                            $sqlPredmeti = "SELECT * FROM predmeti";
                            $resultPredmeti = $con->query($sqlPredmeti);
                            
                            // Iteracija kroz rezultat i prikaz opcija u select elementu
                            while ($row = $resultPredmeti->fetch_assoc()) {
                                echo "<option value='".$row['predmet_id']."'>".$row['naziv_predmeta']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="skripta" class="form-label">Skripta (PDF)</label>
                        <input type="file" class="form-control" id="skripta" name="skripta" accept=".pdf" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<?php
session_start();
$con = require "../ukljucivanje/connection/spajanje.php";
include("../ukljucivanje/functions/funkcije.php");

$trenutnaStranica = "kartice2";

$putanjaDoPocetne = "../";
$putanjaDoInstruktora = "../instruktori.php";
$putanjaDoSkripta = "../skripte/";
$putanjaDoKartica = "../kartice/";
$putanjaDoOnama = "../onama.php";

$putanjaDoPrijave = "../racun/prijava.php";
$putanjaDoRegistracije = "../racun/registracija.php";

$putanjaDoRacuna = "../nadzornaploca";
$putanjaDoOdjave = "../racun/odjava.php";

$sqlPredmeti = "SELECT * FROM predmeti";
$rezultatPredmeti = $con->query($sqlPredmeti);

$user = provjeri_prijavu($con);
if (!isset($_SESSION["user_id"])) {
    header("Location: ../racun/prijava.php");
    exit;
}
$korisnikId = $_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['kreiraj_grupu'])) {

        $nazivGrupe = $_POST["naziv_grupe"];
        $opisGrupe = $_POST["opis_grupe"];
        $vlasnikId = $korisnikId;
        $javno = $_POST["javno"];

        if (isset($_POST["predmet_id"]) && !empty($_POST["predmet_id"])) {
            $predmetId = $_POST["predmet_id"];

            $datumKreiranja = date('Y-m-d H:i:s');

            $sqlInsertGrupa = "INSERT INTO grupekartica (grupa_naziv, grupa_opis, vlasnik_id, javno, predmet_id, datum_kreiranja) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $con->prepare($sqlInsertGrupa);
            $stmt->bind_param("sssiss", $nazivGrupe, $opisGrupe, $korisnikId, $javno, $predmetId, $datumKreiranja);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $grupaId = $stmt->insert_id;
                header("Location: proba.php?grupa_id=$grupaId");
                
            } else {
                echo "Došlo je do greške pri unosu informacija o grupi kartica.";
            }
        } else {
            echo "Molimo odaberite predmet.";
        }
    } else {
        echo "Došlo je do greške pri obradi obrasca.";
    }
}

?>

<!DOCTYPE html>
<html lang="hr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Izradi nove kartice za ponavljanje</title>

    <?php include '../assets/css/stiliranjeSporedno.php'; ?> 
    <script src="../assets/js/main.js"></script>
</head>

<body>

    <?php include '../ukljucivanje/header.php'; ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 mt-3">

                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Izrada nove grupe kartica za ponavljanje</h2>
                        
                    </div>

                    <div class="card-body">
                        <form method="post" class="needs-validation"  enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="naziv_grupe" >Naziv Grupe Kartica</label>
                                <input type="text" class="form-control" id="naziv_grupe" name="naziv_grupe" required>
                            </div>
                            <div class="form-group">
                                <label for="opis_grupe">Opis Grupe Kartica</label>
                                <textarea class="form-control" id="opis_grupe" name="opis_grupe" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="predmet_id">Predmet</label>
                                <select class="form-control" id="predmet_id" name="predmet_id" required>
                                    <option value="">Odaberite predmet</option>
                                    <?php
                                    // Dohvat svih predmeta iz baze podataka
                                    $sqlPredmeti = "SELECT * FROM predmeti";
                                    $rezultatPredmeti = $con->query($sqlPredmeti);

                                    // Iteracija kroz rezultat i prikaz opcija u select elementu
                                    while ($row = $rezultatPredmeti->fetch_assoc()) {
                                        echo "<option value='" . $row['predmet_id'] . "'>" . $row['naziv_predmeta'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="javno" name="javno" value="1" checked>
                                    <label class="form-check-label" for="javno">
                                        Javno dostupno
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="privatno" name="javno" value="0">
                                    <label class="form-check-label" for="privatno">
                                        Privatno
                                    </label>
                                </div>
                            </div>
                            <button type="submit" name="kreiraj_grupu" class="btn btn-primary">Kreiraj grupu kartica</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    
</body>

</html>

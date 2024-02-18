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
$resultPredmeti = $con->query($sqlPredmeti);

$user = provjeri_prijavu($con);
if (!isset($_SESSION["user_id"])) {
    header("Location: ../racun/prijava.php");
    exit;
}
$korisnikId = $user["korisnik_id"];

// Provjera podnesenog obrasca za prijenos datoteka
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['kreiraj_grupu'])) {
        // Informacije o grupi kartica
        $nazivGrupe = $_POST["naziv_grupe"];
        $opisGrupe = $_POST["opis_grupe"];
        $vlasnikId = $user["korisnik_id"];
        $javno = isset($_POST["javno"]) ? 1 : 0;

        if (isset($_POST["predmet_id"]) && !empty($_POST["predmet_id"])) {
            $predmetId = $_POST["predmet_id"];

            $datumKreiranja = date('Y-m-d H:i:s');

            // Dobivanje najvećeg dostupnog ID-a za grupu kartica
            $sqlMaxGrupaId = "SELECT MAX(grupa_id) AS max_grupa_id FROM grupekartica";
            $resultMaxGrupaId = $con->query($sqlMaxGrupaId);
            $rowMaxGrupaId = $resultMaxGrupaId->fetch_assoc();
            $maxGrupaId = $rowMaxGrupaId['max_grupa_id'];
            $grupaId = $maxGrupaId + 1;

            // Umetanje nove grupe kartica
            $sqlInsertGrupa = "INSERT INTO grupekartica (grupa_id, grupa_naziv, grupa_opis, vlasnik_id, javno, predmet_id, datum_kreiranja) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $con->prepare($sqlInsertGrupa);
            $stmt->bind_param("isssiss", $grupaId, $nazivGrupe, $opisGrupe, $vlasnikId, $javno, $predmetId, $datumKreiranja);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                // Uspješno dodana grupa kartica
                echo "Grupa je uspješno prenesena i pohranjena.";
            } else {
                // Greška pri unosu informacija o grupi kartica
                echo "Došlo je do greške pri unosu informacija o grupi kartica.";
            }
        } else {
            // Poruka ako predmet nije odabran
            echo "Molimo odaberite predmet.";
        }
    } elseif (isset($_POST['dodaj_karticu'])) {
        // Informacije o kartici
        $pitanje = $_POST["pitanje"];
        $odgovor = $_POST["odgovor"];

        // Provjeri postoji li grupa_id
        if (!isset($_POST["grupa_id"])) {
            echo "Nema dostupne grupe za dodavanje kartice.";
            exit;
        }

        $grupaId = $_POST["grupa_id"];

        // Umetanje nove kartice
        $sqlInsertKartica = "INSERT INTO kartice (pitanje, odgovor, grupa_id) VALUES (?, ?, ?)";
        $stmt = $con->prepare($sqlInsertKartica);
        $stmt->bind_param("ssi", $pitanje, $odgovor, $grupaId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Uspješno dodana kartica
            echo "Kartica je uspješno dodana.";
        } else {
            // Greška pri unosu kartice
            echo "Došlo je do greške pri dodavanju kartice.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="hr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Izradi nove kartice za ponavljanje</title>

    <?php include '../assets/css/stiliranjeSporedno.php'; ?> <!-- Sve poveznice za stil web stranice -->

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
                        <form method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="naziv_grupe">Naziv Grupe Kartica</label>
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
                                    $resultPredmeti = $con->query($sqlPredmeti);

                                    // Iteracija kroz rezultat i prikaz opcija u select elementu
                                    while ($row = $resultPredmeti->fetch_assoc()) {
                                        echo "<option value='" . $row['predmet_id'] . "'>" . $row['naziv_predmeta'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="javno" name="javno">
                                    <label class="form-check-label" for="javno">
                                        Javno dostupno
                                    </label>
                                </div>
                            </div>
                            <button type="submit" name="kreiraj_grupu" class="btn btn-primary">Kreiraj grupu kartica</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Dodaj karticu</h2>
                    </div>
                    <div class="card-body">
                        <!-- Forma za dodavanje nove kartice -->
                        <form method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="pitanje">Pitanje</label>
                                <textarea class="form-control" id="pitanje" name="pitanje" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="odgovor">Odgovor</label>
                                <textarea class="form-control" id="odgovor" name="odgovor" rows="3" required></textarea>
                            </div>
                            <!-- Skriveno polje za grupa_id -->
                            <input type="hidden" name="grupa_id" id="grupa_id" value="<?php echo $grupaId; ?>">
                            <button type="submit" name="dodaj_karticu" class="btn btn-primary">Dodaj karticu</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <script src="../assets/js/main.js"></script>
</body>

</html>

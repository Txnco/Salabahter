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

$sqlPredmeti = "SELECT * FROM predmeti";
$resultPredmeti = $con->query($sqlPredmeti);

$user = provjeri_prijavu($con);
if (!isset($_SESSION["user_id"])) {
    header("Location: ../racun/prijava.php");
    exit;
} else {
    $korisnikId = $_SESSION["user_id"];
}

$grupa_id = "0";
$grupa_id = $_GET['grupa_id'];
$sqlGrupa = "SELECT * FROM grupekartica WHERE grupa_id = $grupa_id";
$rezultatGrupa = $con->query($sqlGrupa);

if ($redGrupa = $rezultatGrupa->fetch_assoc()) {
    $nazivGrupe = $redGrupa['grupa_naziv'];
    $opisGrupe = $redGrupa['grupa_opis'];
    $vlasnikId = $redGrupa['vlasnik_id'];
    $javno = $redGrupa['javno'];
    $predmetId = $redGrupa['predmet_id'];
    $datumKreiranja = $redGrupa['datum_kreiranja'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['kreiraj_karticu'])) {
        // Podaci o kartici
        $pitanje = $_POST["pitanje"];
        $odgovor = $_POST["odgovor"];
        $grupaId = $_POST["grupa_id"];

        // Provjera da li je grupa_id pravilno postavljen
        if (!empty($grupaId)) {
            // Umetanje nove kartice
            $sqlInsertKartica = "INSERT INTO kartice (pitanje, odgovor, grupa_id) VALUES (?, ?, ?)";
            $stmt = $con->prepare($sqlInsertKartica);
            $stmt->bind_param("ssi", $pitanje, $odgovor, $grupaId);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
            } else {
                echo "Došlo je do greške pri kreiranju kartice.";
            }
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

    <?php include '../assets/css/stiliranjeSporedno.php'; ?>
    <link rel="stylesheet" href="css/style.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <script defer src="js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
    <style>
        h3,
        h4 {
            font-family: 'Poppins', sans-serif;
            color: #000000;
        }
    </style>
</head>

<body>

    <?php include '../ukljucivanje/header.php'; ?>
  
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">Izradi nove kartice za ponavljanje <?php echo "$grupa_id"; ?></h4>
                        </div>
                        <div class="card-body">
                        <form class="needs-validation" method="post">
                                <div class="form-group">
                                    <label for="pitanje">Pitanje</label>
                                    <input type="text" class="form-control" id="pitanje" name="pitanje" required>
                                </div>
                                <div class="form-group">
                                    <label for="odgovor">Odgovor</label>
                                    <input type="text" class="form-control" id="odgovor" name="odgovor" required>
                                </div>
                                <div class="form-group">
                                    <label for="predmet_id">Predmet</label>
                                    <select class="form-control" id="predmet_id" name="predmet_id" required>
                                        <option value="">Odaberite predmet</option>
                                        <?php
                                        while ($row = $resultPredmeti->fetch_assoc()) {
                                            echo "<option value='" . $row['predmet_id'] . "'>" . $row['naziv_predmeta'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <input type="hidden" name="grupa_id" value="<?php echo $grupa_id; ?>">
                                <button type="submit" name="kreiraj_karticu" class="btn btn-primary">Kreiraj karticu</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>


</body>

</html>

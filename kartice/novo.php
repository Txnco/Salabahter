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



// Provjeri je li poslan zahtjev za brisanje kartice
if (isset($_POST['delete_card'])) {
    // Provjeri je li poslan ID kartice i ID grupe
    if (isset($_POST['kartica_id']) && isset( $_GET['grupa_id'])) {
        $karticaId = $_POST['kartica_id'];
        $grupa_id = $_GET['grupa_id'];
        
        // Pripremi upit za brisanje kartice
        $sql = "DELETE FROM kartice WHERE kartica_id = $karticaId AND grupa_id = $grupaId";
        
        // Izvrši upit za brisanje kartice
        if (mysqli_query($con, $sql)) {
            echo "Kartica je uspješno obrisana.";
        } else {
            echo "Došlo je do greške prilikom brisanja kartice: " . mysqli_error($con);
        }
    } else {
        echo "Nisu poslani potrebni podaci za brisanje kartice.";
    }
} else {
    echo "Zahtjev za brisanje kartice nije poslan.";
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
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    

</head>

<body>
    <?php include '../ukljucivanje/header.php'; ?>

    <div class="container mt-2">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card mb-4">
                    <div class="card-header py-3">
                        <h4 class="mt-3 mb-2 d-inline">Događaj</h4>
                        <a href="#" id="editEvent">uredi</a>
                    </div>

                    <div class="card-body">

                        <form action="action.php" method="post" id="eventForm">
                            <div class="row mb-4">
                                <div class="col">
                                    <div class="form-outline">
                                        <label for="grupa_naziv" class="form-label">Naziv grupe:</label>
                                        <input type="text" class="form-control" id="grupa_naziv" name="grupa_naziv" value="<?php echo "$nazivGrupe"; ?>" readonly>
                                    </div>
                                </div>

                            </div>
                            <div class="form-outline mb-4">
                                <label for="grupa_opis" class="form-label">Opis grupe:</label>
                                <textarea class="form-control" id="grupa_opis" name="grupa_opis" rows="3" readonly><?php echo "$opisGrupe"; ?></textarea>
                            </div>

                            <div class="form-outline mb-4">
                                <label for="subject" class="form-label">Predmet:</label>
                                <?php
                                if ($rezultatPredmeti->num_rows > 0) {
                                    echo '<select class="form-control" id="subject" name="subject" required disabled>';
                                    echo '<option value="">Odaberite predmet</option>';
                                    while ($red_predmet = $rezultatPredmeti->fetch_assoc()) {
                                        $selected = ($red_predmet['predmet_id'] == $predmetId) ? "selected" : "";
                                        echo '<option value="' . $red_predmet['predmet_id'] . '" ' . $selected . '>' . $red_predmet['naziv_predmeta'] . '</option>';
                                    }
                                    echo '</select>';
                                } else {
                                    echo 'Nema dostupnih predmeta.';
                                }
                                ?>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="javno" name="javno" value="1" <?php echo ($javno == 1) ? 'checked disabled' : 'disabled'; ?>>
                                <label class="form-check-label" for="javno">
                                    Javno dostupno
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="privatno" name="javno" value="0" <?php echo ($javno == 0) ? 'checked disabled' : 'disabled'; ?>>
                                <label class="form-check-label" for="privatno">
                                    Privatno
                                </label>
                            </div>

                            <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                            <input type="hidden" name="update_event_submit" value="1">
                            <button id="saveButton" type="submit" class="btn btn-success" style="display:none;">Spremi</button>
                        </form>

                    </div>
                </div>
            </div>

            <div class="col-md-8 mb-4">
                <form action="action.php" method="get">
                    <input type="hidden" name="create_competition" value="1">
                    <input type="hidden" name="grupa_id" value="<?php echo $grupa_id; ?>">
                    <button type="submit" class="btn btn-success">Dodaj novu karticu</button>
                </form>
                <div class="mb-3 mt-2 overflow-auto" style="max-height: 70vh;">

                    <?php
                    $sql = "SELECT * FROM kartice WHERE grupa_id = $grupa_id";
                    $result = mysqli_query($con, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                            <div class="card mb-4">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading<?php echo $row['kartica_id']; ?>">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $row['kartica_id']; ?>" aria-expanded="false" aria-controls="collapse<?php echo $row['kartica_id']; ?>">
                                            <?php echo $row['pitanje']; ?>
                                        </button>
                                    </h2>
                                    <div id="collapse<?php echo $row['kartica_id']; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $row['kartica_id']; ?>" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <p><?php echo $row['odgovor']; ?></p>
                                            <a href="novo.php?grupa_id=<?php echo $grupa_id; ?>" onclick="deleteCard(<?php echo $row['kartica_id']; ?>, <?php echo $grupa_id; ?>)" class="text text-danger" style="font-size: 0.9rem;">Obriši</a>

                                        </div>
                                    </div>
                                </div>
                            </div>

                    <?php
                        }
                    } else {
                        echo "Nema kartica";
                    }
                    ?>


                </div>
            </div>

        </div>


    </div>
    <script>
   function deleteCard(karticaId, grupaId) {
    var confirmDelete = confirm("Jeste li sigurni da želite obrisati ovu karticu?");
    if (confirmDelete) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Ažuriraj sadržaj stranice ili prikaži poruku
                alert(this.responseText);
                location.reload(); // Osvježi stranicu nakon brisanja kartice
            }
        };
        xhttp.open("POST", "novo.php?grupa_id=" + grupaId, true); // Pass grupa_id in the URL
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("delete_card=1&kartica_id=" + karticaId + "&grupa_id=" + grupaId);
    }
}

</script>
</body>

</html>

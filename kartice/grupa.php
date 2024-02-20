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

if ($rezultatGrupa->num_rows == 0) {
    header("Location: index.php");
    exit;
}

if ($redGrupa = $rezultatGrupa->fetch_assoc()) {
    $nazivGrupe = $redGrupa['grupa_naziv'];
    $opisGrupe = $redGrupa['grupa_opis'];
    $vlasnikId = $redGrupa['vlasnik_id'];
    if ($vlasnikId == $korisnikId) {
        $admin = true;
    } else {
        $admin = false;
    }
    $javno = $redGrupa['javno'];
    $predmetId = $redGrupa['predmet_id'];
    $datumKreiranja = date('d.m.Y', strtotime($redGrupa['datum_kreiranja']));
    $imePrezimeKorisnika = dohvatipodatkevlasnika($vlasnikId);

    $sqlPredmet = "SELECT naziv_predmeta, predmet_boja FROM predmeti WHERE predmet_id =  $predmetId";
    $rezultatPredmet = $con->query($sqlPredmet);
    $redPredmet = $rezultatPredmet->fetch_assoc();
    $predmetNaziv = $redPredmet['naziv_predmeta'];
    $predmetBoja = $redPredmet['predmet_boja'];
}
$sqlUpdatePregleda = "UPDATE grupekartica SET broj_pregleda = broj_pregleda + 1 WHERE grupa_id = $grupa_id";
$resultUpdatePregleda = $con->query($sqlUpdatePregleda);

$sqlBrojPregleda = "SELECT broj_pregleda FROM grupekartica WHERE grupa_id = $grupa_id";
$resultBrojPregleda = $con->query($sqlBrojPregleda);
$rowBrojPregleda = $resultBrojPregleda->fetch_assoc();
$brojpregleda = $rowBrojPregleda['broj_pregleda'];
function dohvatipodatkevlasnika($vlasnik_id)
{

    $con = require "../ukljucivanje/connection/spajanje.php";
    $sqlKorisnik = "SELECT ime, prezime FROM korisnik WHERE korisnik_id = $vlasnik_id";
    $resultKorisnik = $con->query($sqlKorisnik);
    $rowKorisnik = $resultKorisnik->fetch_assoc();
    $ime = $rowKorisnik['ime'];
    $prezime = $rowKorisnik['prezime'];
    return $ime . " " . $prezime;
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
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"> </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <?php include '../ukljucivanje/header.php'; ?>

    <div class="container mt-2">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card mb-4">
                    <div class="card-body">
                    <?php if ($admin){ ?>
                            <div style="text-align: right;">
                                <form action='akcije.php' method='GET'>
                                    <input type='hidden' name='grupa_id' value='<?php echo $grupa_id; ?>' />
                                    <input type='hidden' name='action' value='brisi_grupu' />
                                    <input type='submit' class='btn btn-link text-danger' name='Submit' value='Briši' />
                                </form>
                            </div>
                        <?php } ?>
                        <form action="akcije.php" method="GET" id="fromagrupa">
                        
                            <div class="row mb-4">
                                <h4 class="mb-2 d-inline">
                                    <?php echo "$nazivGrupe"; ?>
                                </h4>
                                <h6 class="card-info" style="font-family: Poppins; text-align: left;"> <a class="link"
                                        href="../profil?korisnik=<?php echo $vlasnikId ?>">
                                        <?php echo $imePrezimeKorisnika; ?>
                                    </a>,
                                    <?php echo "$datumKreiranja"; ?>
                                    <?php echo '<span class="badge" style="background-color: ' . $predmetBoja . ';">' . $predmetNaziv . '</span> '; ?>
                            </div>
                            <div class="row mb-4">
                                <div class="col">
                                    <div class="form-outline">
                                        <label for="grupa_naziv" class="form-label">Naziv grupe:</label>
                                        <input type="text" class="form-control" id="grupa_naziv" name="grupa_naziv"
                                            value="<?php echo "$nazivGrupe"; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-outline mb-4">
                                <label for="grupa_opis" class="form-label">Opis grupe:</label>
                                <textarea class="form-control" id="grupa_opis" name="grupa_opis" rows="3"
                                    readonly><?php echo "$opisGrupe"; ?></textarea>
                            </div>
                            <div class="form-outline mb-4">
                                <label for="predmet" class="form-label">Predmet:</label>
                                <?php
                                if ($rezultatPredmeti->num_rows > 0) {
                                    echo '<select class="form-control" id="predmet" name="predmet" required disabled>';
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
                            <input type="hidden" name="uredi_grupu" value="1">
                            <button id="spremi" type="submit" class="btn btn-success"
                                style="display:none;">Spremi</button>
                        </form>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary">Započni</button>
                            <?php if ($admin){ ?>
                            <button type="submit" class="btn btn-secondary" id="uredigrupu">uredi</button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>



            <div class="col-md-8 mb-4">
                <?php if ($admin){ ?>
                <button type="button" class="btn btn-success mt-2" data-toggle="modal"
                    data-target="#novaKarticaModal">Dodaj novu karticu</button>
                <?php } ?>

                <div class="modal fade" id="novaKarticaModal" tabindex="-1" aria-labelledby="novaKarticaModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="novaKarticaModalLabel">Dodaj novu karticu</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <form action="akcije.php" method="GET">
                                    <input type="hidden" name="action" value="dodaj_karticu" />
                                    <label for="pitanje">Pitanje:</label>
                                    <input type="text" name="pitanje" id="pitanje" class="form-control" required><br>
                                    <label for="odgovor">Odgovor:</label>
                                    <textarea name="odgovor" id="odgovor" class="form-control" required></textarea><br>
                                    <input type='hidden' name='grupa_id' value="<?php echo $grupa_id; ?>" />
                                    <button type="submit" class="btn btn-primary">Dodaj karticu</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


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
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse<?php echo $row['kartica_id']; ?>" aria-expanded="false"
                                            aria-controls="collapse<?php echo $row['kartica_id']; ?>">
                                            <?php echo $row['pitanje']; ?>
                                        </button>
                                    </h2>
                                    <div id="collapse<?php echo $row['kartica_id']; ?>" class="accordion-collapse collapse"
                                        aria-labelledby="heading<?php echo $row['kartica_id']; ?>"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <p>
                                                <?php echo $row['odgovor']; ?>
                                            </p>

                                            <form action='akcije.php' method='GET'>
                                                <input type='hidden' name='kartica_id' value=<?php echo "'{$row['kartica_id']}'"; ?> />
                                                <input type='hidden' name='action' value='brisi_karticu' />
                                                <input type='submit' class='btn btn-danger mt-2' name='Submit' value='Briši' />
                                            </form>
                                            <button type="button" class="btn btn-secondary mt-2" data-toggle="modal"
                                                data-target="#urediKarticuModal">Uredi</button>
                                            <div class="modal fade" id="urediKarticuModal" tabindex="-1"
                                                aria-labelledby="urediKarticuModal" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="urediKarticuModalLabel">Dodaj novu
                                                                karticu</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="akcije.php" method="GET">
                                                                <input type="hidden" name="action" value="uredi_karticu" />
                                                                <label for="pitanje">Pitanje:</label>
                                                                <input type="text" name="pitanje" id="pitanje"
                                                                    class="form-control" value="<?php echo $row['pitanje']; ?>"
                                                                    required><br>
                                                                <label for="odgovor">Odgovor:</label>
                                                                <textarea name="odgovor" id="odgovor" class="form-control"
                                                                    required><?php echo $row['odgovor']; ?></textarea><br>
                                                                <input type='hidden' name='grupa_id'
                                                                    value="<?php echo $grupa_id; ?>" />
                                                                <input type='hidden' name='kartica_id'
                                                                    value="<?php echo "{$row['kartica_id']}"; ?>" />
                                                                <button type="submit" class="btn btn-primary">Uredi
                                                                    karticu</button>
                                                            </form>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
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

</body>
<script>
    $(document).ready(function () {
        $("#uredigrupu").click(function () {
            $("#grupa_naziv").prop('readonly', false);
            $("#grupa_opis").prop('readonly', false);
            $("#javno").prop('readonly', false);
            $("#javno").prop('disabled', false);
            $("#privatno").prop('disabled', false);
            $("#spremi").show();
        });
    });
</script>


</html>
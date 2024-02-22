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

$jeAdministrator;
$korisnikrPrava = check_privilegeUser($con); // Provjeri da li je korisnik admin
if (isset($korisnikrPravaPrava)) { 
  if ($korisnikrPravaPrava['status_korisnika'] == 3678) {
    $jeAdministrator = $_SESSION['isAdmin'];
    
  }
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
    <title>
        <?php echo "$nazivGrupe"; ?>
    </title>

    <?php include '../assets/css/stiliranjeSporedno.php'; ?>
    
    <link href="../assets/css/kartice.css" rel="stylesheet">
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"> </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <?php include '../ukljucivanje/header.php'; ?>

    
    <div class="row d-flex justify-content-center">
        <div class="col" style="background: linear-gradient(to right, #687EFF, #98E4FF); padding: 20px;">
            <h1 class="text-center" style="color: white;">Grupa kartica</h1>
        </div>
    </div>

    <div class="container mt-2">

        <div class="row">

        <?php if ($admin || $_SESSION['isAdmin']) : ?>
            <div style="text-align: right;">
                <button class='btn btn-link text-danger' data-toggle="modal" data-target="#deleteModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                    </svg></button>
            </div>
        <?php endif; ?>

            <div class="col-md-4 mb-4">

                <div class="card mt-2 mb-5">
                    <?php
                    echo  '<img src="../assets/img/predmeti/novipredmet.jpg" style="width: 100%; height: 150px; object-fit: cover;">';
                    ?>
                    <div class="card-body">

                        <?php if ($admin || $_SESSION['isAdmin']) : ?>
                            <form id="deleteForm" action='akcije.php' method='GET'>
                                <input type='hidden' name='grupa_id' value='<?php echo $grupa_id; ?>' />
                                <input type='hidden' name='action' value='brisi_grupu' />
                            </form>

                            <!-- Modal -->
                            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel">Jeste li sigurni da želite
                                                obrisati ovu grupu kartica?</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Odustani</button>
                                            <button type="button" class="btn btn-danger" id="confirmDelete">Obriši</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <script>
                                document.getElementById("confirmDelete").addEventListener("click", function() {
                                    document.getElementById("deleteForm").submit();
                                });
                            </script>
                        <?php endif; ?>
                        <form action="akcije.php" method="GET" id="fromagrupa">



                            <div class="col">
                                <div class="d-flex flex-column align-items-center text-center">
                                    <h4>
                                        <div id="nazivTekst">
                                            <?php echo $nazivGrupe; ?>
                                        </div>
                                        <div id="urediNaziv" style="display: none;">
                                            <input type="text" class="form-control" style="font-size: 24px;" id="grupa_naziv" name="grupa_naziv" value="<?php echo "$nazivGrupe"; ?>" readonly>
                                        </div>
                                    </h4>

                                    <h6 class="card-info" style="font-family: Poppins; text-align: left;"> <a class="link" href="../profil?korisnik=<?php echo $vlasnikId ?>">
                                            <?php echo $imePrezimeKorisnika; ?>
                                        </a>,
                                        <?php echo "$datumKreiranja"; ?>
                                    </h6>

                                    <?php echo '<span class="badge" style="background-color: ' . $predmetBoja . ';">' . $predmetNaziv . '</span> '; ?>

                                </div>
                                <div class="d-flex flex-column align-items-start ml-2 mt-4 mb-4 mr-4">
                                    <h6 class="m-0" style="font-size: 1.125rem;"><strong>Opis kartica</strong></h6>

                                    <div id="opisTekst" class=" pt-1">
                                        <?php echo $opisGrupe; ?>
                                    </div>
                                    <div class=" pt-1" id="urediOpis" style="display: none;">
                                        <textarea type="text" class="form-control" style="font-size: 16px; width: 334px; height:75px;" id="grupa_opis" name="grupa_opis" readonly><?php echo $opisGrupe; ?></textarea>
                                    </div>

                                    <div class=" pt-1 mt-3" style="display: none; width: 334px;" id="urediPredmet">
                                        <h6 class="m-0" style="font-size: 1.125rem;"><strong>Predmet</strong></h6>

                                        <?php
                                        if ($rezultatPredmeti->num_rows > 0) {
                                        ?> <select class="form-control" id="predmet" name="predmet" required disabled>
                                            <?php
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


                                            <div class="d-flex justify-content-center mt-3">
                                                <div class="form-check mr-3">
                                                    <input class="form-check-input" type="radio" id="javno" name="javno" value="1" <?php echo ($javno == 1) ? 'checked disabled' : 'disabled'; ?>>
                                                    <label class="form-check-label" for="javno">
                                                        Javno
                                                    </label>
                                                </div>

                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" id="privatno" name="javno" value="0" <?php echo ($javno == 0) ? 'checked disabled' : 'disabled'; ?>>
                                                    <label class="form-check-label" for="privatno">
                                                        Privatno
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-row align-items-center justify-content-center">
                                                <input type="hidden" name="grupa_id" value="<?php echo $grupa_id; ?>" hidden> </input>
                                                <input type="hidden" name="uredi_grupu" value="1" hidden></input>
                                                <input type='hidden' name='action' value='uredi_grupu' hidden></input>
                                                <button id="spremi" type="submit" class="btn btn-success mt-2 mr-2" style="display:none;">Spremi</button>
                                                <button type="button" id="odustani" class="btn btn-secondary  mt-2 ml-2" data-dismiss="modal">Odustani</button>
                                            </div>
                                    </div>
                                </div>


                            </div>

                        </form>

                        <div class="d-flex justify-content-between ml-3 mr-3 mb-3">
                            <a class="btn btn-success" href="proba.php?grupa_id=<?php echo $grupa_id; ?> ">Započni</a>
                            <?php if ($admin) : ?>
                                <button class="btn btn-success" id="urediKartice">Uredi</button>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>

            </div>

            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <script>
                $(document).ready(function() {
                    $("#urediKartice").click(function() {
                        $("#nazivTekst").hide();
                        $("#urediNaziv").show();
                        $("#grupa_naziv").prop('readonly', false);

                        $("#opisTekst").hide();
                        $("#urediOpis").show();
                        $("#grupa_opis").prop('readonly', false);

                        $("#urediPredmet").show();
                        $("#predmet").prop('disabled', false);

                        $("#javno").prop('disabled', false);
                        $("#privatno").prop('disabled', false);

                        $("#spremi").show();
                    });

                    $("#odustani").click(function() {
                        $("#nazivTekst").show();
                        $("#urediNaziv").hide();
                        $("#grupa_naziv").prop('readonly', true);

                        $("#opisTekst").show();
                        $("#urediOpis").hide();
                        $("#grupa_opis").prop('readonly', true);

                        $("#urediPredmet").hide();
                        $("#predmet").prop('disabled', true);

                        $("#javno").prop('disabled', true);
                        $("#privatno").prop('disabled', true);

                        $("#spremi").hide();
                    });
                });
            </script>


            <div class="col-md-8 mb-4 mt-2">
                <div class="card">
                    <div class="card-body">

                        <h2 class="text-center">Pojmovi kartica i odgovori</h2>

                        <?php if ($admin) { ?>
                            <button type="button" class="btn btn-success mt-2" data-toggle="modal" data-target="#novaKarticaModal">Dodaj novu karticu</button>
                        <?php } ?>

                        <div class="modal fade" id="novaKarticaModal" tabindex="-1" aria-labelledby="novaKarticaModalLabel" aria-hidden="true">
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
                                        <div class="accordion-item" style="background-color: white;">
                                            <h2 class="accordion-header" id="heading<?php echo $row['kartica_id']; ?>">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $row['kartica_id']; ?>" aria-expanded="false" aria-controls="collapse<?php echo $row['kartica_id']; ?>" style="background-color: white;">
                                                    <?php echo $row['pitanje']; ?>
                                                </button>
                                            </h2>
                                            <div id="collapse<?php echo $row['kartica_id']; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $row['kartica_id']; ?>" data-bs-parent="#accordionExample" style="background-color: #f8f9fa;">
                                                <div class="accordion-body">
                                                    <p>
                                                        <?php echo $row['odgovor']; ?>
                                                    </p>

                                                    <?php if ($admin) : ?>

                                                    <div class="d-flex justify-content-between">
                                                        <button type="button" class="btn btn-success mt-2" data-toggle="modal" data-target="#urediKarticuModal">Uredi</button>

                                                        <form action='akcije.php' method='GET'>
                                                            <input type='hidden' name='kartica_id' value=<?php echo "'{$row['kartica_id']}'"; ?> />
                                                            <input type='hidden' name='action' value='brisi_karticu' />
                                                            <button type="submit" class="btn btn-danger mt-2" name='Submit' value='Obriši'>Obriši</button>
                                                        </form>
                                                    </div>
                                                    <?php endif; ?>

                                                    <div class="modal fade" id="urediKarticuModal" tabindex="-1" aria-labelledby="urediKarticuModal" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="urediKarticuModalLabel">Dodaj novu karticu</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form action="akcije.php" method="GET">
                                                                        <input type="hidden" name="action" value="uredi_karticu" />
                                                                        <label for="pitanje">Pitanje:</label>
                                                                        <input type="text" name="pitanje" id="pitanje" class="form-control" value="<?php echo $row['pitanje']; ?>" required><br>
                                                                        <label for="odgovor">Odgovor:</label>
                                                                        <textarea name="odgovor" id="odgovor" class="form-control" required><?php echo $row['odgovor']; ?></textarea><br>
                                                                        <input type='hidden' name='grupa_id' value="<?php echo $grupa_id; ?>" />
                                                                        <input type='hidden' name='kartica_id' value="<?php echo "{$row['kartica_id']}"; ?>" />
                                                                        <button type="submit" class="btn btn-primary">Uredi karticu</button>
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
        </div>
    </div>





</body>



</html>
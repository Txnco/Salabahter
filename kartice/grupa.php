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
            <div class="col d-flex justify-content-start align-items-center">

                <a class="btn text" href="index"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="mr-2">
                        <path d="M10.78 19.03a.75.75 0 0 1-1.06 0l-6.25-6.25a.75.75 0 0 1 0-1.06l6.25-6.25a.749.749 0 0 1 1.275.326.749.749 0 0 1-.215.734L5.81 11.5h14.44a.75.75 0 0 1 0 1.5H5.81l4.97 4.97a.75.75 0 0 1 0 1.06Z"></path>
                    </svg>Vrati se na grupe kartica</a>
            </div>
        </div>

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

                <div class="card mt-2 mb-3">
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

                                    <h6 class="card-info text-center" style="font-family: Poppins; text-align: left;"> <a class="link" href="../profil?korisnik=<?php echo $vlasnikId ?>">
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

                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col text-center">

                            <button style="border: none;" href="generirajQRKodGrupeKartica.php/?grupa_id=<?php echo $grupa_id ?>" data-toggle="modal" data-target="#qrKodModal"><svg fill="#1499ff" width="48px" height="48px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" stroke="#1499ff" stroke-width="0.00024000000000000003">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path d="M16.1666667,6 C16.0746192,6 16,6.07461921 16,6.16666667 L16,7.83333333 C16,7.92538079 16.0746192,8 16.1666667,8 L17.8333333,8 C17.9253808,8 18,7.92538079 18,7.83333333 L18,6.16666667 C18,6.07461921 17.9253808,6 17.8333333,6 L16.1666667,6 Z M16,18 L16,17.5 C16,17.2238576 16.2238576,17 16.5,17 C16.7761424,17 17,17.2238576 17,17.5 L17,18 L18,18 L18,17.5 C18,17.2238576 18.2238576,17 18.5,17 C18.7761424,17 19,17.2238576 19,17.5 L19,18.5 C19,18.7761424 18.7761424,19 18.5,19 L14.5,19 C14.2238576,19 14,18.7761424 14,18.5 L14,17.5 C14,17.2238576 14.2238576,17 14.5,17 C14.7761424,17 15,17.2238576 15,17.5 L15,18 L16,18 L16,18 Z M13,11 L13.5,11 C13.7761424,11 14,11.2238576 14,11.5 C14,11.7761424 13.7761424,12 13.5,12 L11.5,12 C11.2238576,12 11,11.7761424 11,11.5 C11,11.2238576 11.2238576,11 11.5,11 L12,11 L12,10 L10.5,10 C10.2238576,10 10,9.77614237 10,9.5 C10,9.22385763 10.2238576,9 10.5,9 L13.5,9 C13.7761424,9 14,9.22385763 14,9.5 C14,9.77614237 13.7761424,10 13.5,10 L13,10 L13,11 Z M18,12 L17.5,12 C17.2238576,12 17,11.7761424 17,11.5 C17,11.2238576 17.2238576,11 17.5,11 L18,11 L18,10.5 C18,10.2238576 18.2238576,10 18.5,10 C18.7761424,10 19,10.2238576 19,10.5 L19,12.5 C19,12.7761424 18.7761424,13 18.5,13 C18.2238576,13 18,12.7761424 18,12.5 L18,12 Z M13,14 L12.5,14 C12.2238576,14 12,13.7761424 12,13.5 C12,13.2238576 12.2238576,13 12.5,13 L13.5,13 C13.7761424,13 14,13.2238576 14,13.5 L14,15.5 C14,15.7761424 13.7761424,16 13.5,16 L10.5,16 C10.2238576,16 10,15.7761424 10,15.5 C10,15.2238576 10.2238576,15 10.5,15 L13,15 L13,14 L13,14 Z M16.1666667,5 L17.8333333,5 C18.4776655,5 19,5.52233446 19,6.16666667 L19,7.83333333 C19,8.47766554 18.4776655,9 17.8333333,9 L16.1666667,9 C15.5223345,9 15,8.47766554 15,7.83333333 L15,6.16666667 C15,5.52233446 15.5223345,5 16.1666667,5 Z M6.16666667,5 L7.83333333,5 C8.47766554,5 9,5.52233446 9,6.16666667 L9,7.83333333 C9,8.47766554 8.47766554,9 7.83333333,9 L6.16666667,9 C5.52233446,9 5,8.47766554 5,7.83333333 L5,6.16666667 C5,5.52233446 5.52233446,5 6.16666667,5 Z M6.16666667,6 C6.07461921,6 6,6.07461921 6,6.16666667 L6,7.83333333 C6,7.92538079 6.07461921,8 6.16666667,8 L7.83333333,8 C7.92538079,8 8,7.92538079 8,7.83333333 L8,6.16666667 C8,6.07461921 7.92538079,6 7.83333333,6 L6.16666667,6 Z M6.16666667,15 L7.83333333,15 C8.47766554,15 9,15.5223345 9,16.1666667 L9,17.8333333 C9,18.4776655 8.47766554,19 7.83333333,19 L6.16666667,19 C5.52233446,19 5,18.4776655 5,17.8333333 L5,16.1666667 C5,15.5223345 5.52233446,15 6.16666667,15 Z M6.16666667,16 C6.07461921,16 6,16.0746192 6,16.1666667 L6,17.8333333 C6,17.9253808 6.07461921,18 6.16666667,18 L7.83333333,18 C7.92538079,18 8,17.9253808 8,17.8333333 L8,16.1666667 C8,16.0746192 7.92538079,16 7.83333333,16 L6.16666667,16 Z M13,6 L10.5,6 C10.2238576,6 10,5.77614237 10,5.5 C10,5.22385763 10.2238576,5 10.5,5 L13.5,5 C13.7761424,5 14,5.22385763 14,5.5 L14,7.5 C14,7.77614237 13.7761424,8 13.5,8 C13.2238576,8 13,7.77614237 13,7.5 L13,6 Z M10.5,8 C10.2238576,8 10,7.77614237 10,7.5 C10,7.22385763 10.2238576,7 10.5,7 L11.5,7 C11.7761424,7 12,7.22385763 12,7.5 C12,7.77614237 11.7761424,8 11.5,8 L10.5,8 Z M5.5,14 C5.22385763,14 5,13.7761424 5,13.5 C5,13.2238576 5.22385763,13 5.5,13 L7.5,13 C7.77614237,13 8,13.2238576 8,13.5 C8,13.7761424 7.77614237,14 7.5,14 L5.5,14 Z M9.5,14 C9.22385763,14 9,13.7761424 9,13.5 C9,13.2238576 9.22385763,13 9.5,13 L10.5,13 C10.7761424,13 11,13.2238576 11,13.5 C11,13.7761424 10.7761424,14 10.5,14 L9.5,14 Z M11,18 L11,18.5 C11,18.7761424 10.7761424,19 10.5,19 C10.2238576,19 10,18.7761424 10,18.5 L10,17.5 C10,17.2238576 10.2238576,17 10.5,17 L12.5,17 C12.7761424,17 13,17.2238576 13,17.5 C13,17.7761424 12.7761424,18 12.5,18 L11,18 Z M9,11 L9.5,11 C9.77614237,11 10,11.2238576 10,11.5 C10,11.7761424 9.77614237,12 9.5,12 L8.5,12 C8.22385763,12 8,11.7761424 8,11.5 L8,11 L7.5,11 C7.22385763,11 7,10.7761424 7,10.5 C7,10.2238576 7.22385763,10 7.5,10 L8.5,10 C8.77614237,10 9,10.2238576 9,10.5 L9,11 Z M5,10.5 C5,10.2238576 5.22385763,10 5.5,10 C5.77614237,10 6,10.2238576 6,10.5 L6,11.5 C6,11.7761424 5.77614237,12 5.5,12 C5.22385763,12 5,11.7761424 5,11.5 L5,10.5 Z M15,10.5 C15,10.2238576 15.2238576,10 15.5,10 C15.7761424,10 16,10.2238576 16,10.5 L16,12.5 C16,12.7761424 15.7761424,13 15.5,13 C15.2238576,13 15,12.7761424 15,12.5 L15,10.5 Z M17,15 L17,14.5 C17,14.2238576 17.2238576,14 17.5,14 L18.5,14 C18.7761424,14 19,14.2238576 19,14.5 C19,14.7761424 18.7761424,15 18.5,15 L18,15 L18,15.5 C18,15.7761424 17.7761424,16 17.5,16 L15.5,16 C15.2238576,16 15,15.7761424 15,15.5 L15,14.5 C15,14.2238576 15.2238576,14 15.5,14 C15.7761424,14 16,14.2238576 16,14.5 L16,15 L17,15 Z M3,6.5 C3,6.77614237 2.77614237,7 2.5,7 C2.22385763,7 2,6.77614237 2,6.5 L2,4.5 C2,3.11928813 3.11928813,2 4.5,2 L6.5,2 C6.77614237,2 7,2.22385763 7,2.5 C7,2.77614237 6.77614237,3 6.5,3 L4.5,3 C3.67157288,3 3,3.67157288 3,4.5 L3,6.5 Z M17.5,3 C17.2238576,3 17,2.77614237 17,2.5 C17,2.22385763 17.2238576,2 17.5,2 L19.5,2 C20.8807119,2 22,3.11928813 22,4.5 L22,6.5 C22,6.77614237 21.7761424,7 21.5,7 C21.2238576,7 21,6.77614237 21,6.5 L21,4.5 C21,3.67157288 20.3284271,3 19.5,3 L17.5,3 Z M6.5,21 C6.77614237,21 7,21.2238576 7,21.5 C7,21.7761424 6.77614237,22 6.5,22 L4.5,22 C3.11928813,22 2,20.8807119 2,19.5 L2,17.5 C2,17.2238576 2.22385763,17 2.5,17 C2.77614237,17 3,17.2238576 3,17.5 L3,19.5 C3,20.3284271 3.67157288,21 4.5,21 L6.5,21 Z M21,17.5 C21,17.2238576 21.2238576,17 21.5,17 C21.7761424,17 22,17.2238576 22,17.5 L22,19.5 C22,20.8807119 20.8807119,22 19.5,22 L17.5,22 C17.2238576,22 17,21.7761424 17,21.5 C17,21.2238576 17.2238576,21 17.5,21 L19.5,21 C20.3284271,21 21,20.3284271 21,19.5 L21,17.5 Z"></path>
                                    </g>
                                </svg></i></button>
                            <span class="text">Podijelite ove kartice sa drugima</span>
                        </div>
                    </div>
                </div>

            </div>

            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <script>
                $(document).ready(function() {

                    $('#qrKodModal').on('show.bs.modal', function(e) {
                        $('#qrKodSlika').attr('src', 'generirajQRKodGrupeKartica.php');
                    });

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





    <!-- Modal za QR Kod -->
    <div class="modal fade" id="qrKodModal" tabindex="-1" role="dialog" aria-labelledby="qrKodModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">QR KOD GRUPE KARTICA</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body pt-0">

                    <div class="row">
                        <div class="col d-flex flex-column justify-content-center">
                            <?php
                            // Get the data URL of the QR code
                            $qrCodeDataUrl = require 'generirajQRKodGrupeKartica.php';
                            ?>

                        </div>
                        <div class="col d-flex flex-column justify-content-center align-items-center">

                            <div class="text-center">
                                <h3><?php echo $nazivGrupe; ?></h3>
                            </div>

                            <h6 class="card-info text-center" style="font-family: Poppins;">
                                <a class="link" href="../profil?korisnik=<?php echo $vlasnikId ?>">
                                    <?php echo $imePrezimeKorisnika; ?>
                                </a>,
                                <?php echo "$datumKreiranja"; ?>
                            </h6>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>


</body>



</html>
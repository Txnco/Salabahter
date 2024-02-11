<?php

session_start();
$con = require "../ukljucivanje/connection/spajanje.php";
include("../ukljucivanje/functions/funkcije.php");

$user = provjeri_prijavu($con);
if (!$user) {
    header("Location: ../racun/prijava.php");
    die;
}

echo $user['korisnik_id'] . " " . $user['status_korisnika'] . " " . $user['ime'] . "<br>";

// šalju se podaci u bazu
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_FILES["autentikacija"]) && $_FILES["autentikacija"]["error"] == 0) {
        $prijenosnaMapa = "autentikacija/";
        $jedinstvenoIme = uniqid() . "_" . $_FILES["autentikacija"]["name"];
        $putanjaAutentikacije = $prijenosnaMapa . $jedinstvenoIme;

        if (move_uploaded_file($_FILES["autentikacija"]["tmp_name"], $putanjaAutentikacije)) {
            $idKorisnika = $user['korisnik_id'];
            $statusKorisnika = $user['status_korisnika'];
            $upisMotivacije = $_POST["upisMotivacije"];
            $upisOpisa = $_POST["opisInstruktora"];

            $stmt = $con->prepare("INSERT INTO zahtjevzainstruktora (korisnik_id, status_id, motivacija, opisInstruktora, autentikacija) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iisss", $idKorisnika, $statusKorisnika, $upisMotivacije, $upisOpisa, $putanjaAutentikacije);

            if (!$stmt->execute()) {
                echo "Error: " . $stmt->error;
            }

            $sqlZahtjevId = "SELECT zahtjev_id FROM zahtjevzainstruktora WHERE korisnik_id = " . $user['korisnik_id'];
            $rezultatZahtjevId = $con->query($sqlZahtjevId);
            $zahtjevId = $rezultatZahtjevId->fetch_assoc();

            if (isset($zahtjevId['zahtjev_id'])) {

                $predmeti = $_POST['predmetiUpis'];

                foreach ($predmeti as $selectedPredmetId) {
                    $sql = "INSERT INTO predmetizahtjeva (zahtjev_id, predmet_id) VALUES ({$zahtjevId['zahtjev_id']}, {$selectedPredmetId})";
                    if (!$con->query($sql)) {
                        echo "Error: " . $con->error;
                    }
                }
            } else echo "Nije pronađen zahtjev_id!";
        } else {
            echo "Došlo je do greške pri prijenosu datoteke.";
        }
    } else {
        echo "Prijenos datoteke nije uspio. Error: " . $_FILES["autentikacija"]["error"];
    }
}

$sqlPoslanZahtjev = "SELECT * FROM zahtjevzainstruktora WHERE korisnik_id = " . $user['korisnik_id'];
$rezultatPoslanZahtjev = $con->query($sqlPoslanZahtjev);
if ($rezultatPoslanZahtjev) {

    $poslanZahtjevRow = $rezultatPoslanZahtjev->fetch_assoc();

    if ($poslanZahtjevRow) {
        // Check if there is a row returned

        // Access the 'status_naziv' value from the associative array
        $zahtjev = 1;
        if ($zahtjev == 1) header("Location: ../nadzornaploca");
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zahtjev za instruktora</title>

    <?php include '../assets/css/stiliranjeSporedno.php'; ?> <!-- Sve poveznice za stil web stranice -->

</head>

<body>


    <div id="main-wrapper" class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="card border-0">
                    <div class="card-body p-0">
                        <div class="row no-gutters">
                            <div class="col-lg-8">
                                <div class="p-5">
                                    <div class="mb-3">
                                        <h2><a class="h2 font-weight-bold text-theme" href="index.php">Vrati se na račun </a></h2>
                                        <h3 class="h4 font-weight-bold text-theme">Zahtjev za instruktora</h3>
                                    </div>

                                    <div id="hidenUI" class="">

                                        <form class="needs-validation" method="post" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label for="upisMotivacije">Zašto bi htio postati instruktor?</label>
                                                <input type="text" class="form-control" name="upisMotivacije" id="upisMotivacije" required> <!-- Korisnik napiše ukratko zašto bi htio biti instruktor-->
                                            </div>

                                            <div class="form-group">
                                                <label for="opisInstruktora">Opis</label>
                                                <input type="text" class="form-control" name="opisInstruktora" id="opisInstruktora" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="unosPDF">Priložite valjanu verifikaciju</label>
                                                <div class="input-group mb-4 ">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Unesite</span>
                                                    </div>
                                                    <div class="custom-file">
                                                        <label class="custom-file-label" for="inputGroupFile01">Odaberite datoteku</label>
                                                        <input type="file" class="custom-file-input" id="inputGroupFile01" name="autentikacija" accept=".pdf" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php
                                            $predmeti = array();
                                            $sql = "SELECT * FROM predmeti";
                                            $result = $con->query($sql);
                                            while ($row = $result->fetch_array()) {
                                                $predmeti[] = $row;
                                            }
                                            ?>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-7 mb-2">
                                                        <select type="text" class="form-control predmeti-upis" name="predmetiUpis[]" required>
                                                            <?php
                                                            foreach ($predmeti as $predmet => $row) {
                                                                echo "<option value='" . $row['predmet_id'] . "'>" . $row['naziv_predmeta'] . "</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>



                                                <div class="zaljepi-nove-upise-predmeta">
                                                </div>

                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                <a href="javascript:void(0)" class="dodaj-novi-upis float-start">+ Dodaj predmet</a>
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-primary">Pošalji upit</button>

                                        </form>
                                    </div>
                                </div>

                                <div class="col-lg-6 d-none d-lg-inline-block">
                                    <div class="account-block rounded-right">
                                        <div class="overlay rounded-right"></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- end card-body -->
                    </div>
                    <!-- end card -->

                    <!-- end row -->

                </div>
                <!-- end col -->
            </div>
            <!-- Row -->
        </div>

        <script src="https://code.jquery.com/jquery-3.7.1.js"> </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            $(document).ready(function() {

                // Kada se pošalje upit omoguće se sva polja za predmete
                $('form').on('submit', function(e) {
                    $('.predmeti-upis').prop('disabled', false);

                    var formData = $(this).serializeArray(); // v tome je problem kaj se ne citaju disabled selecti
                    console.log(formData);
                });

                var selectedOptions = [];

                $(document).on('click', '.remove-btn', function() {
                    var removedSelectIndex = $(this).closest('.main-form').index();
                    $(this).closest('.main-form').remove();
                    selectedOptions.splice(removedSelectIndex, 1);
                    refreshSelectOptions();
                    disableAllPreviousSelects();
                });


                $(document).on('click', '.dodaj-novi-upis', function() {
                    var lastSelect = $('.predmeti-upis').last();
                    var numberOfPredmeti = <?php echo count($predmeti); ?>;
                    if (lastSelect.val() === null) {
                        alert('Morate odabrati predmet prije dodavanja novog predmeta!');
                        return;
                    }
                    if ($('.predmeti-upis').length >= numberOfPredmeti) {
                        alert('Ne možete dodati više predmeta!');
                        return;
                    }

                    // Create a new select element
                    var select = document.createElement("select");
                    select.type = "text";
                    select.className = "form-control predmeti-upis";
                    select.name = "predmetiUpis[]";
                    select.required = true;

                    // Create the new form group
                    var newForm = document.createElement("div");
                    newForm.className = "main-form"; // Add a class to the form group
                    newForm.innerHTML = '\
        <div class="row"> \
            <div class="col-md-7 mb-2">' + select.outerHTML + '</div> \
            <div class="col-md-4"> \
                <a href="javascript:void(0)" class="remove-btn float-end btn btn-danger">- Izbriši odabir</a> \
            </div> \
        </div>';

                    // Append the new form group to the form
                    var form = document.querySelector(".zaljepi-nove-upise-predmeta");
                    form.appendChild(newForm);

                    refreshSelectOptions();
                    disableAllPreviousSelects();
                });

                function disableAllPreviousSelects() {
                    var selects = $('.predmeti-upis');
                    selects.prop('disabled', true);
                    if (selects.length > 0) {
                        selects.last().prop('disabled', false);
                    }
                }

                function refreshSelectOptions() {
                    var selects = $('.predmeti-upis');
                    selects.each(function(index, select) {
                        var options = <?php echo json_encode($predmeti); ?>;
                        var select = $(select);
                        var selectedValue = select.val();
                        select.empty();
                        options.forEach(function(option) {
                            if (!selectedOptions.ukljucivanje(option.predmet_id) || option.predmet_id == selectedValue) {
                                var optionElement = $('<option>');
                                optionElement.attr('value', option.predmet_id);
                                optionElement.text(option.naziv_predmeta);
                                select.append(optionElement);
                            }
                        });
                        select.val(selectedValue);
                        selectedOptions[index] = selectedValue;
                    });
                }

                refreshSelectOptions();

                // Kada se postavi datoteka za autentikaciju, prikaže se ime datoteke
                $(document).ready(function() {
                    $('#inputGroupFile01').on('change', function() {
                        // Get the file name
                        var fileName = $(this).val().split('\\').pop();

                        // Replace the "Choose a file" label
                        $(this).parent('.custom-file').find('.custom-file-label').html(fileName);
                    });
                });
            });
        </script>

</body>

</html>
<!-- Forma kojom korisnik salje zahtjev za instruktora -->
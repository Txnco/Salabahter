<?php

$trenutnaStranica = "račun";
$trenutnaStranica2 = "Predmeti";

$putanjaDoPocetne = "../../";
$putanjaDoInstruktora = "../../instruktori.php";
$putanjaDoSkripta = "../../skripte/";
$putanjaDoKartica = "../../kartice.php";
$putanjaDoOnama = "../../onama.php";

$putanjaDoPrijave = "../../racun/prijava.php";
$putanjaDoRegistracije = "../../racun/registracija.php";

$putanjaDoRacuna = "../../nadzornaploca";
$putanjaDoOdjave = "../../racun/odjava.php";

session_start();
$con = require "../../ukljucivanje/connection/spajanje.php";
include("../../ukljucivanje/functions/funkcije.php");

$user = provjeri_prijavu($con);
if (!$user) {
    header("Location: ../../racun/prijava.php");
    die;
}
$user = check_privilegeUser($con);
if ($user['status_korisnika'] == 3678) {
    $isAdmin = $_SESSION['isAdmin'];
} else if ($user['status_korisnika'] != 3678) {
    header("Location: ../");
    die;
}

$sqlSviPredmeti = "SELECT * FROM predmeti";
$rezultatSviPredmeti = $con->query($sqlSviPredmeti);


$zahtjevZaInstruktora = "SELECT zahtjevzainstruktora.zahtjev_id,korisnik.korisnik_id,statuskorisnika.status_id,motivacija,opisInstruktora,autentikacija, ime, prezime, email, status_naziv  FROM zahtjevzainstruktora,korisnik,statuskorisnika WHERE zahtjevzainstruktora.korisnik_id = korisnik.korisnik_id AND zahtjevzainstruktora.status_id = statuskorisnika.status_id ";
$rezultatZahtjeva = $con->query($zahtjevZaInstruktora);

while ($result = $rezultatZahtjeva->fetch_assoc()) {
    $brojZahtjeva = $rezultatZahtjeva->num_rows;
}

$sqlPrijaveRecenzija = "SELECT * FROM prijavarecenzije";
$rezultatPrijaveRecenzija = $con->query($sqlPrijaveRecenzija);
while ($result = $rezultatPrijaveRecenzija->fetch_assoc()) {
    $brojPrijavaRecenzija = $rezultatPrijaveRecenzija->num_rows;
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    if (isset($_POST['upisPredmet'])) {
        $naziv_predmeta = $_POST['naziv_predmeta'];
        $predmet_boja = $_POST['predmet_boja'];
        $slika_predmeta = $_FILES['slika_predmeta']['name'];

        // Handle the file upload for 'slika_predmeta' here


        $sql = "INSERT INTO predmeti (naziv_predmeta, predmet_boja, slika_predmeta) VALUES (?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sss", $naziv_predmeta, $predmet_boja, $slika_predmeta);

        $stmt->execute();
        header("Location: dodavanjePredmeta.php");
        die;
    } else if (isset($_POST['promjenaPredmeta'])) {
        $predmet_id = $_POST['promjenaPredmeta']; // Assuming you have a hidden input field for 'predmet_id'
        $naziv_predmeta = $_POST['naziv_predmeta'];
        $predmet_boja = $_POST['predmet_boja'];
        $slika_predmeta = $_FILES['slika_predmeta']['name'];

        $sql = "UPDATE predmeti SET naziv_predmeta = ?, predmet_boja = ?, slika_predmeta = ? WHERE predmet_id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssi", $naziv_predmeta, $predmet_boja, $slika_predmeta, $predmet_id);
        $stmt->execute();
        header("Location: dodavanjePredmeta.php");
        die;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Administrator</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Ikone -->
    <link href="../../assets/img/writing.png" rel="icon">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css">

    <!-- Vendor CSS datoteke -->
    <link href="../../assets/vendor/animate.css/animate.min.css" rel="stylesheet">
    <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="../../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="../../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Glavni prefložak za CSS  -->

    <link href="../../assets/css/style.css" rel="stylesheet">
    <link href="../../assets/css/nadzornaploca.css" rel="stylesheet">

</head>

<body>

    <?php include '../../ukljucivanje/header.php'; ?>

    <div class="container ">
        <div class="main-body">


            <div class="row gutters-sm">

                <?php include 'izbornik.php'; ?>



                <div class="col-sm-9">
                    <div class="card ">
                        <div class="card-body p-0">
                            <h2 class="text-center mt-3">Svi predmeti</h2>
                            <br>

                            <div class="d-flex justify-content-center">
                                <a class="btn btn-primary" data-bs-toggle="collapse" href="#dodajPredmet" role="button" aria-expanded="false" aria-controls="dodajPredmet">
                                    Dodaj predmet
                                    <svg class="arrow-up" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16" fill="white" style="display: none;">
                                        <path d="M3.22 10.53a.749.749 0 0 1 0-1.06l4.25-4.25a.749.749 0 0 1 1.06 0l4.25 4.25a.749.749 0 1 1-1.06 1.06L8 6.811 4.28 10.53a.749.749 0 0 1-1.06 0Z"></path>
                                    </svg>
                                    <svg class="arrow-down" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16" fill="white">
                                        <path d="M12.78 5.22a.749.749 0 0 1 0 1.06l-4.25 4.25a.749.749 0 0 1-1.06 0L3.22 6.28a.749.749 0 1 1 1.06-1.06L8 8.939l3.72-3.719a.749.749 0 0 1 1.06 0Z"></path>
                                    </svg>
                                </a>
                            </div>


                            <div class="collapse animate__animated animate__slideinDown" id="dodajPredmet">
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="row ml-5 mr-5 text-center">

                                        <div class="col-md-6 text-secondary mb-3 mt-3">
                                            <label for="upisPredmetaL">Naziv predmeta</label>
                                            <input type="text" class="form-control" name="naziv_predmeta" id="upisPredmeta">
                                        </div>

                                        <div class="col-md-6 text-secondary mb-3 mt-3">
                                            <label for="predmet_boja">Boja predmeta</label>
                                            <input type="color" class="form-control" name="predmet_boja" id="predmet_boja">
                                        </div>

                                        <div class="col-sm-9 text-secondary mb-3 mt-3">
                                            <label for="slika_predmeta">Slika predmeta (JPG, JPEG)</label>
                                            <input type="file" class="form-control" name="slika_predmeta" id="slika_predmeta" accept=".jpg, .jpeg">
                                        </div>

                                        <div class="col-sm-12 mb-4">

                                            <button class="btn btn-racun" name="upisPredmet" type="submit">
                                                Upiši predmet
                                            </button>
                                            <button class="btn btn-racun" id="predmet_id" name="promjenaPredmeta" type="submit">
                                                Uredi predmet
                                            </button>
                                        </div>
                                    </div>

                                </form>
                            </div>



                            <hr class="m-2">
                        </div>


                        <div class="container">
                            <div class="main-body mt-3">
                                <div class="row">
                                    <div class="col">
                                        <div class="row instruktori-container h-100">
                                            <?php if ($rezultatSviPredmeti->num_rows > 0) :
                                                while ($red = $rezultatSviPredmeti->fetch_assoc()) :
                                            ?>
                                                    <div class="col-sm-3 mb-3 ">
                                                        <div class="card">
                                                            <?php if ($red['slika_predmeta'] != null) : ?>
                                                                <img src="<?php echo '../../assets/img/predmeti/' . $red["slika_predmeta"]; ?>" alt="Slika za <?php echo $red["naziv_predmeta"]; ?>" style="width: 100%; height: 150px; object-fit: cover;">
                                                            <?php else :
                                                                echo  '<img src="../../assets/img/predmeti/novipredmet.jpg" style="width: 100%; height: 150px; object-fit: cover;">';
                                                            endif; ?>
                                                            <div class="card-body">
                                                                <h5 class="card-title text-center" style="color: <?php echo $red["predmet_boja"]; ?>;"><?php echo $red["naziv_predmeta"]; ?></h5>
                                                                <div class="d-flex justify-content-center">
                                                                    <button class="btn urediPredmet" data-id="<?php echo $red["predmet_id"]; ?>" data-name="<?php echo $red["naziv_predmeta"]; ?>" data-color="<?php echo $red["predmet_boja"]; ?>" data-image="<?php echo $red["slika_predmeta"]; ?>">Uredi predmet</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            <?php 
                                                endwhile;
                                            else :
                                                echo "<span class='text m-4'>Trenutno nema zahtjeva</span>";
                                            endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


            </div>
        </div>

    </div>



    <div class="row gutters-sm">

    </div>


    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-beta1/js/bootstrap.bundle.min.js"></script>

    <script src="../../assets/js/main.js"></script>


    <script>
        $(document).ready(function() {
            $('#dodajPredmet').on('show.bs.collapse', function() {
                $(this).find('.arrow-down').hide();
                $(this).find('.arrow-up').show();
            });

            $('#dodajPredmet').on('hide.bs.collapse', function() {
                $(this).find('.arrow-down').show();
                $(this).find('.arrow-up').hide();
            });
        });

        $(document).ready(function() {
            $('.urediPredmet').click(function() {
                var predmet_id = $(this).data('id');
                var naziv_predmeta = $(this).data('name');
                var predmet_boja = $(this).data('color');
                var slika_predmeta = $(this).data('image');

                $('#predmet_id').val(predmet_id);
                $('#upisPredmeta').val(naziv_predmeta);
                $('#predmet_boja').val(predmet_boja);
                // For the image, you might want to display it instead of setting it as a value
                $('#slika_predmeta').attr('src', slika_predmeta);

                if ($('#dodajPredmet').hasClass('collapse') && !$('#dodajPredmet').hasClass('show')) {
                    $('#dodajPredmet').collapse('show');
                }

                // Enable the 'Uredi predmet' button and disable the 'Upiši predmet' button
                $('#predmet_id').prop('disabled', false);
                $('button[name="upisPredmet"]').prop('disabled', true);
            });

            $(document).ready(function() {
                // Disable the 'promjenaPredmeta' button on page load
                $('#predmet_id').prop('disabled', true);

                $('button[name="upisPredmet"]').click(function() {
                    // Enable the 'Upiši predmet' button and disable the 'Uredi predmet' button
                    $(this).prop('disabled', false);
                    $('#predmet_id').prop('disabled', true);
                });
            });
        });
    </script>

</body>

</html>
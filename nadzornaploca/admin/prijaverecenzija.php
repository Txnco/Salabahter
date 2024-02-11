<?php

$trenutnaStranica = "račun";

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

$user = provjeri_prijavu($con); // Provjera prijave
if (!$user) {
    header("Location: ../../racun/prijava.php");
    die;
}
$user = check_privilegeUser($con); // Provjera privilegija
if ($user['status_korisnika'] == 5) {
    $isAdmin = $_SESSION['isAdmin'];
} else if ($user['status_korisnika'] != 5) {
    header("Location: ../");
    die;
}

$sqlPrijavljeneRecenzije = "SELECT * FROM prijavarecenzije"; // Dohvaćanje svih prijavljenih recenzija
$rezultatPrijava = $con->query($sqlPrijavljeneRecenzije);  // Izvršavanje upita

$sqlSveRecenzije = "SELECT * FROM recenzije WHERE $rezultatPrijava"; // Dohvaćanje svih recenzija
$rezultatRecenzija = $con->query($sqlSveRecenzije);  // Izvršavanje upita

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Prijave recenzija</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="../../assets/img/writing.png" rel="icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

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

    <link href="../../assets/css/dashboard.css" rel="stylesheet">

</head>

<body>

    <?php include '../../ukljucivanje/header.php'; ?>

    <div class="container">
        <div class="main-body">

            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="main-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../../">Početna</a></li>
                    <li class="breadcrumb-item active"><a href="../admin/" aria-current="page">Račun</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)" aria-current="page">Prijave recenzija</a></li>
                </ol>
            </nav>
            <!-- /Breadcrumb -->



            <div class="card mt-3">
                <div class="card-body">
                    <h4 class="text-center display-4">Prijave recenzija</h4>
                    <br>
                    <div class="row">
                        <div class="col-sm-2">
                            <span style="font-size: 1.3em;">Autor</span>
                        </div>
                        <div class="col-sm-2">
                            <span style="font-size: 1.3em;">Komentar</span>
                        </div>

                        <div class="col-sm-2">
                            <span style="font-size: 1.3em;">Ocjena</span>
                        </div>

                        <div class="col-sm-2">
                            <span style="font-size: 1.3em;">Prijavio korisnik</span>
                        </div>

                        <div class="col-sm-2">
                            <span style="font-size: 1.3em;">Autentikacija</span>
                        </div>
                    </div>
                    <hr>
                </div>

                <?php if (isset($rezultatZahtjeva) && $rezultatZahtjeva->num_rows > 0) :
                    while ($row = $rezultatZahtjeva->fetch_assoc()) : // Prikaz svih zahtjeva za instruktora

                        // Dohvaćanje predmeta za pojedini zahtjev za instruktora
                        $zahtjevZaPredmete = "SELECT naziv_predmeta FROM predmetizahtjeva,predmeti,zahtjevzainstruktora,korisnik WHERE predmetizahtjeva.predmet_id = predmeti.predmet_id AND  predmetizahtjeva.zahtjev_id = {$row['zahtjev_id']} AND zahtjevzainstruktora.korisnik_id = korisnik.korisnik_id AND korisnik.korisnik_id = {$row['korisnik_id']}   ";
                        $rezultatPredmeta = $con->query($zahtjevZaPredmete);

                        // Spremanje predmeta u polje jer instruktor može predavati više predmeta
                        $predmeti = array();
                        if ($rezultatPredmeta->num_rows > 0) {
                            while ($predmetRow = $rezultatPredmeta->fetch_assoc()) {
                                $predmeti[] = $predmetRow['naziv_predmeta'];
                            }
                        }

                ?>
                        <div class="card mt-3">
                            <div class="card-body">
                                <form method="POST">
                                    <div class="row m-2 mx-auto">

                                        <div class="col-sm-2 text-center">
                                            <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle" width="100">
                                        </div>

                                        <div class="col-sm-2 text-center my-auto">
                                            <h6 class="card-text"><a class="link" href="../../profil?korisnik=<?php echo $row['korisnik_id'] ?>"><?php echo $row["ime"] . " " . $row["prezime"] ?></a></h6>
                                        </div>

                                        <div class="col-sm-2 text-center my-auto">
                                            <h6 class="card-text"><?php echo $row["status_naziv"] ?></h6>
                                        </div>

                                        <div class="col-sm-2 text-center my-auto">
                                            <h6 class="card-text"><?php foreach ($predmeti as $predmet) {
                                                                        echo $predmet . " ";
                                                                    } ?></h6>
                                        </div>

                                        <div class="col-sm-2 text-center my-auto">
                                            <a class="btn btn-primary" href="../<?php echo $row["autentikacija"] ?>" download>Preuzmi autentikaciju</a>

                                        </div>
                                        <div class="col-sm-2 text-center my-auto">
                                            <input type="hidden" name="korisnik_id" value="<?php echo $row['korisnik_id']; ?>">
                                            <input type="hidden" name="zahtjev_id" value="<?php echo $row['zahtjev_id']; ?>">
                                            <div class="col-sm-2 text-center p-1 my-auto">
                                                <button class="btn btn-success" name="prihvatiZahtjev" type="submit">Prihvati</button>
                                            </div> <!-- Svaki zahtjev ima svoj ID, treba za svaki ID zahtjeva sloziti LOOP da se prihvati/odbaci samo onaj koji je stisnuti a ne svi koji su u formu -->
                                            <div class="col-sm-2 text-center p-1 my-auto">
                                                <button class="btn btn-danger" name="odbijZahtjev" type="submit">Odbij</button>
                                            </div>
                                        </div>

                                    </div>


                                    <hr>
                                </form>
                            </div>
                        </div>
                <?php
                    endwhile;
                else :
                    echo "Nema rezultata za zahtjev";
                endif; ?>
            </div>
        </div>
    </div>


    <script>
        window.onload = function() {
            var forms = document.getElementsByTagName('form');
            for (var i = 0; i < forms.length; i++) {
                forms[i].id = 'form' + (i + 1); // Assign a unique id to each form
                var buttons = forms[i].getElementsByTagName('button');
                for (var j = 0; j < buttons.length; j++) {
                    buttons[j].value = i + 1; // Assign the form index to each button
                }
            }
        }
    </script>

</body>

</html>
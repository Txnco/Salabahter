<?php

$trenutnaStranica = "račun";
$trenutnaStranica2 = "Zahtjevi";

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
if ($user['status_korisnika'] == 3678) {
    $isAdmin = $_SESSION['isAdmin'];
} else if ($user['status_korisnika'] != 3678) {
    header("Location: ../");
    die;
}

// Dohvaćanje zahtjeva za instruktora
$zahtjevZaInstruktora = "SELECT zahtjevzainstruktora.zahtjev_id,korisnik.korisnik_id,statuskorisnika.status_id,motivacija,opisInstruktora,autentikacija, ime, prezime, email, status_naziv  FROM zahtjevzainstruktora,korisnik,statuskorisnika WHERE zahtjevzainstruktora.korisnik_id = korisnik.korisnik_id AND zahtjevzainstruktora.status_id = statuskorisnika.status_id ";
$rezultatZahtjeva = $con->query($zahtjevZaInstruktora);

$brojZahtjeva = $rezultatZahtjeva->num_rows;

$sqlPrijavljeneRecenzije = "SELECT * FROM prijavarecenzije"; // Dohvaćanje svih prijavljenih recenzija
$rezultatPrijava = $con->query($sqlPrijavljeneRecenzije);  // Izvršavanje upita

$brojPrijavaRecenzija = $rezultatPrijava->num_rows;

$sqlPrijavljeneSkripte = "SELECT * FROM prijavaskripte"; // Dohvaćanje svih prijavljenih skripti
$rezultatPrijaveSkripte = $con->query($sqlPrijavljeneSkripte);  // Izvršavanje upita

$brojPrijavaSkripte = $rezultatPrijaveSkripte->num_rows;


if ($_SERVER['REQUEST_METHOD'] === "POST") {

    if (isset($_POST['prihvatiZahtjev'])) { // Prihvaćanje zahtjeva za instruktora

        $zahtjevID = $_POST['zahtjev_id'];
        $korisnikID = $_POST['korisnik_id'];

        // Dohvaćanje podataka iz zahtjeva
        $dohvatiOdobreniZahtjev = "SELECT korisnik_id,motivacija,opisInstruktora,autentikacija  FROM zahtjevzainstruktora WHERE zahtjevzainstruktora.zahtjev_id = {$zahtjevID} AND zahtjevzainstruktora.korisnik_id = {$korisnikID} ";
        $rezultatZahtjeva = $con->query($dohvatiOdobreniZahtjev);
        $test = $rezultatZahtjeva->fetch_assoc();

        echo $test['korisnik_id'] . " " . $test['motivacija'] . " " . $test['opisInstruktora'] . " " . $test['autentikacija'] . " <br><br> ";

        $opisInstruktora = $test['opisInstruktora'];
        $autentikacija = $test['autentikacija'];

        // Dohvaćanje predmeta iz zahtjeva
        $zahtjevZaPredmete = "SELECT predmetizahtjeva.predmet_id FROM predmetizahtjeva,zahtjevzainstruktora,korisnik WHERE  predmetizahtjeva.zahtjev_id = {$zahtjevID} AND zahtjevzainstruktora.korisnik_id = korisnik.korisnik_id AND korisnik.korisnik_id = {$korisnikID}";
        $rezultatPredmeta = $con->query($zahtjevZaPredmete);

        // Spremanje predmeta u polje jer instruktor može predavati više predmeta
        $predmeti = array();
        if ($rezultatPredmeta !== false) {
            if ($rezultatPredmeta->num_rows > 0) {
                while ($predmetRow = $rezultatPredmeta->fetch_assoc()) {
                    $predmeti[] = $predmetRow['predmet_id'];
                }
            }
        } else {
            echo "Error: " . $con->error;
        }

        // Upis instruktora u bazu u tablicu instruktori
        $upisInstruktora = "INSERT INTO instruktori (korisnik_id, opisInstruktora, autentikacija) VALUES ('$korisnikID', '$opisInstruktora','$autentikacija')"; // Upis instruktora u bazu
        $con->query($upisInstruktora);

        // Dohvaćanje ID-a instruktora
        $dohvatiInstruktorovId = "SELECT instruktor_id FROM instruktori WHERE korisnik_id = $korisnikID"; // Dohvaćanje ID-a instruktora
        $rezultat2 = $con->query($dohvatiInstruktorovId);
        $instuktorovID = $rezultat2->fetch_assoc();

        // Upis predmeta koje instruktor predaje u tablicu instruktorovipredmeti
        foreach ($predmeti as $predmet) {
            echo $predmet . " ";
            $upisPredmeta = "INSERT INTO instruktorovipredmeti (instruktor_id, predmet_id) VALUES ('{$instuktorovID['instruktor_id']}','$predmet')"; // Upis predmeta koje instruktor predaje
            $con->query($upisPredmeta);
        }

        echo $korisnikID . " "  . $opisInstruktora . "  " . $instuktorovID['instruktor_id'];

        // Brisanje zahtjeva iz baze iz tablice predmetizahtjeva
        $izbrisiPredmeteZahtjeva = "DELETE FROM predmetizahtjeva WHERE zahtjev_id = {$zahtjevID}"; // Brisanje predmeta iz zahtjeva
        $con->query($izbrisiPredmeteZahtjeva);

        // Brisanje zahtjeva iz baze iz tablice zahtjevzainstruktora
        $izbriziZahtjev = "DELETE FROM zahtjevzainstruktora WHERE zahtjevzainstruktora.zahtjev_id = {$zahtjevID}"; // Brisanje zahtjeva iz baze
        $con->query($izbriziZahtjev);
        if ($con->error) {
            echo "Error: " . $con->error;
        }

        if (isset($rezultatZahtjeva) && $rezultatZahtjeva->num_rows < 0) {
            header("Location: ../admin");
            die;
        }
        header("Location: ../admin/zahtjevi.php");
        die;
    } else if (isset($_POST['odbijZahtjev'])) { // Odbijanje zahtjeva za instruktora

        $zahtjevID = $_POST['zahtjev_id'];
        $korisnikID = $_POST['korisnik_id'];

        // Dohvaćanje autentikacije za brisanje
        $sql = "SELECT autentikacija FROM zahtjevzainstruktora WHERE zahtjev_id = $zahtjevID"; // Dohvaćanje autentikacije za brisanje
        $result = $con->query($sql);
        $row = $result->fetch_assoc();
        $putanja = "../" . $row['autentikacija']; // Spremanje autentikacije u varijablu

        if (file_exists($putanja)) {
            unlink($putanja); // Brisanje autentikacije iz direktorija
        }

        // Brisanje zahtjeva iz baze iz tablice predmetizahtjeva
        $izbrisiPredmeteZahtjeva = "DELETE FROM predmetizahtjeva WHERE zahtjev_id = {$zahtjevID}"; // Brisanje predmeta iz zahtjeva
        $con->query($izbrisiPredmeteZahtjeva);

        // Brisanje zahtjeva iz baze iz tablice zahtjevzainstruktora
        $izbriziZahtjev = "DELETE FROM zahtjevzainstruktora WHERE zahtjevzainstruktora.zahtjev_id = {$zahtjevID}"; // Brisanje zahtjeva iz baze
        $con->query($izbriziZahtjev);
        if ($con->error) {
            echo "Error: " . $con->error;
        }

        if (isset($rezultatZahtjeva) && $rezultatZahtjeva->num_rows < 0) {
            header("Location: ../admin");
            die;
        }
        header("Location: ../admin/zahtjevi.php");
        die;
    }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Zahtjevi</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
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
    <link href="../../assets/css/stil.css" rel="stylesheet">

    <link href="../../assets/css/nadzornaploca.css" rel="stylesheet">

</head>

<body>

    <?php include '../../ukljucivanje/header.php'; ?>

    <div class="container">
        <div class="main-body">



            <div class="row gutters-sm">
                <?php include 'izbornik.php'; ?>


                <div class="col-sm">
                    <div class="card">
                        <div class="card-body p-0">
                            <h2 class="text-center mt-3">Zahtjevi za instruktora</h2>
                            <br>
                            <div class="row m-2 mx-auto">
                                <div class="col-sm-2 text-center my-auto d-none d-sm-block">
                                    <span style="font-size: 1em;">Profilna slika</span>
                                </div>

                                <div class="col-sm-2 text-center my-auto d-none d-sm-block">
                                    <span style="font-size: 1em;">Ime i prezime</span>
                                </div>

                                <div class="col-sm-2 text-center my-auto d-none d-sm-block">
                                    <span style="font-size: 1em;">Motivacija</span>
                                </div>

                                <div class="col-sm-2 text-center my-auto d-none d-sm-block">
                                    <span style="font-size: 1em;">Predmeti</span>
                                </div>

                                <div class="col-sm-2 text-center my-auto d-none d-sm-block">
                                    <span style="font-size: 1em;">Autentikacija</span>
                                </div>
                            </div>
                            <hr class="m-2">
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

                                <form method="POST">
                                    <div class="row m-2 mx-auto">

                                        <div class="col-6 col-sm-2 text-center my-auto">



                                            <?php

                                            $sqlDohvatiProfilnuSliku = "SELECT slika_korisnika FROM korisnik WHERE korisnik_id = {$row['korisnik_id']}";
                                            $rezultatProfilnaSlika = $con->query($sqlDohvatiProfilnuSliku);
                                            $profilnaSlika = $rezultatProfilnaSlika->fetch_assoc();


                                            if ($profilnaSlika['slika_korisnika'] != null) {
                                                $profilnaSlika['slika_korisnika'] = "../" . $profilnaSlika['slika_korisnika'];

                                                echo "<div  class='ml-3' style='width: 100px; height: 100px; overflow: hidden; border-radius: 50%; display: flex; align-items: center; justify-content: center;'>
  <img src='{$profilnaSlika['slika_korisnika']}' alt='Profilna slika' style='width: 100%; height: 100%; object-fit: cover;'  />
</div>";
                                            } else {
                                                echo '<img  src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle" width="100px">';
                                            }

                                            ?>

                                        </div>

                                        <div class="col-6 col-sm-2 text-center my-auto">
                                            <h6 class="card-text"><a class="link" href="../../profil?korisnik=<?php echo $row['korisnik_id'] ?>"><?php echo $row["ime"] . " " . $row["prezime"] . "<br>" ?></a><?php echo $row["status_naziv"] ?></h6>
                                        </div>

                                        <div class="col-6 col-sm-2 text-center my-auto">
                                            <h6 class="card-text"><?php echo $row['motivacija'] ?></h6>
                                        </div>

                                        <div class="col-6 col-sm-2 text-center my-auto">
                                            <h6 class="card-text"><?php foreach ($predmeti as $predmet) {
                                                                        echo $predmet . ", ";
                                                                    } ?></h6>
                                        </div>

                                        <div class="col-6 col-sm-2 text-center my-auto">
                                            <a class="btn btn-primary" href="../<?php echo $row["autentikacija"] ?>" download>Preuzmi</a>
                                        </div>

                                        <div class="col-6 col-sm-2 text-center my-auto">
                                            <input type="hidden" name="korisnik_id" value="<?php echo $row['korisnik_id']; ?>">
                                            <input type="hidden" name="zahtjev_id" value="<?php echo $row['zahtjev_id']; ?>">
                                            <div class="col-12 text-center p-1 my-auto">
                                                <button class="btn btn-success" name="prihvatiZahtjev" type="submit">Prihvati</button>
                                            </div>
                                            <div class="col-12 text-center p-1 my-auto">
                                                <button class="btn btn-danger" name="odbijZahtjev" type="submit">Odbij</button>
                                            </div>
                                        </div>

                                    </div>
                                </form>


                                <hr>

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

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <script src="../../assets/js/main.js"></script>

</body>

</html>
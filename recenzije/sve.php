<?php
session_start();
$trenutnaStranica = "instruktori";

$putanjaDoPocetne = "../";
$putanjaDoInstruktora = "../instruktori.php";
$putanjaDoSkripta = "../skripte/";
$putanjaDoKartica = "../kartice.php";
$putanjaDoOnama = "../onama.php";

$putanjaDoPrijave = "../racun/prijava.php";
$putanjaDoRegistracije = "../racun/registracija.php";

$putanjaDoRacuna = "../nadzornaploca";
$putanjaDoOdjave = "../racun/odjava.php";

$con = require "../ukljucivanje/connection/spajanje.php";
include("../ukljucivanje/functions/funkcije.php");


$korisnikID = $_GET['korisnik']; // ID korisnika kojeg gledamo




$sqlDohvatiRecenzije = "SELECT * FROM recenzije WHERE zaKorisnika = {$korisnikID}"; // Dohvati recenzije korisnika
$rezultatRecenzije = $con->query($sqlDohvatiRecenzije);

$sveRecenzije = $rezultatRecenzije->fetch_assoc();

if ($rezultatRecenzije->num_rows > 0) { // Ako je korisnik instruktor onda se prikažu predmeti koje predaje i njegove skripte
    $imaRecenzije = true;
} else {
    $imaRecenzije = false;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Pregled profila</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <?php include '../assets/css/stiliranjeSporedno.php'; ?> <!-- Sve poveznice za stil web stranice -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Ikone -->

    <link href="../assets/css/nadzornaploca.css" rel="stylesheet">
    <link href="../assets/css/recenzije.css" rel="stylesheet">
    <script src="../ukljucivanje/javascript/profil.js"></script>



</head>

<body>

    <?php include '../ukljucivanje/header.php'; ?>


    <div class="container">
        <div class="main-body">

            <?php if ($imaRecenzije) : ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row m-2">
                                    <div class="col d-flex justify-content-start align-items-center">

                                        <a class="btn text" href="../profil?korisnik=<?php echo $korisnikID ?>"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="mr-2">
                                                <path d="M10.78 19.03a.75.75 0 0 1-1.06 0l-6.25-6.25a.75.75 0 0 1 0-1.06l6.25-6.25a.749.749 0 0 1 1.275.326.749.749 0 0 1-.215.734L5.81 11.5h14.44a.75.75 0 0 1 0 1.5H5.81l4.97 4.97a.75.75 0 0 1 0 1.06Z"></path>
                                            </svg>Vrati se na unos podatakta</a>
                                    </div>
                                    <div class="row">
                                    </div>

                                    <div class="col d-flex justify-content-center">
                                        <h6 class="mb-0">Sve recenzije</h6>
                                    </div>
                                </div>
                                <hr>

                                <div class="row">

                                    <?php
                                    $zvjezdiceOcjenas = [];
                                    for ($i = 1; $i <= 5; $i++) {
                                        $sqlzvjezdiceOcjena = "SELECT COUNT(*) as count FROM recenzije WHERE zaKorisnika = {$korisnikID} AND ocjena = {$i}";
                                        $zvjezdiceOcjenaResult = $con->query($sqlzvjezdiceOcjena);
                                        $zvjezdiceOcjena = $zvjezdiceOcjenaResult->fetch_assoc();
                                        $zvjezdiceOcjenas[$i] = $zvjezdiceOcjena['count'];
                                    }
                                    for ($ocjena = 5; $ocjena >= 1; $ocjena--) {
                                        $count = isset($zvjezdiceOcjenas[$ocjena]) ? $zvjezdiceOcjenas[$ocjena] : 0;
                                        echo "<span class='pl-3'>";
                                        for ($i = 0; $i < $ocjena; $i++) {
                                            echo "<i class='fa fa-star' style='color:gold;'></i>";
                                        }
                                        echo ": {$count}</span><br>";
                                    }
                                    ?>

                                    <?php

                                    // Prikaz svih recenzija
                                    if ($rezultatRecenzije->num_rows > 0) :
                                        while ($red = $rezultatRecenzije->fetch_assoc()) :
                                            $sqlPrijavljenaRecenzija = "SELECT * FROM prijavarecenzije WHERE prijavljenaRecenzija = {$red['recenzija_id']}";
                                            $rezultatPrijavljenaRecenzija = $con->query($sqlPrijavljenaRecenzija);
                                            if ($rezultatPrijavljenaRecenzija->num_rows > 0) {
                                                $prijavljenaRecenzija = false;
                                            } else {
                                                $prijavljenaRecenzija = true;
                                            }

                                            $sqlKorisnik = "SELECT korisnik_id, korisnik.ime, korisnik.prezime, recenzije.ocjena, recenzije.komentar 
                        FROM korisnik 
                        JOIN recenzije ON korisnik.korisnik_id = recenzije.odKorisnika 
                        WHERE recenzije.recenzija_id = {$red['recenzija_id']}";
                                            $rezultatKorisnik = $con->query($sqlKorisnik);
                                            $korisnik = $rezultatKorisnik->fetch_assoc(); // Dohvati korisnika koji je napisao recenziju

                                    ?>
                                            <div class="col-sm-4">
                                                <div class="card mt-4 ">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col">
                                                                <h5><a class="link" href="?korisnik=<?php echo $korisnik['korisnik_id'] ?>"><?php echo $korisnik['ime'] . " " . $korisnik['prezime'] ?></a></h5>

                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <?php
                                                                for ($i = 1; $i <= 5; $i++) {
                                                                    if ($i <= $korisnik['ocjena']) {
                                                                        echo '<i class="fa fa-star" style="color:gold;"></i>';
                                                                    } else {
                                                                        echo '<i class="fa fa-star-o"></i>';
                                                                    }
                                                                }
                                                                ?>

                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <p><?php echo $korisnik['komentar'] ?></p>

                                                            </div>
                                                        </div>

                                                        <?php if (isset($_SESSION['user_id']) && $prijavljenaRecenzija) : ?>
                                                            <div class="row">
                                                                <div class="col d-flex justify-content-end">
                                                                    <a type="button" id="otvoriPrijavu" class="text text-danger" style="font-size: 0.9rem;" data-toggle="modal" data-target="#prijavaRecenzije<?php echo $red['recenzija_id']; ?>">Prijavi recenziju!</a>
                                                                </div>
                                                            </div>
                                                        <?php elseif (isset($_SESSION['user_id']) && !$prijavljenaRecenzija) : ?>
                                                            <div class="row">
                                                                <div class="col d-flex justify-content-end">
                                                                    <a class="text text-success" style="font-size: 0.9rem;">Recenzija prijavljena!</a>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="prijavaRecenzije<?php echo $red['recenzija_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="prijavaRecenzijeTitle" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLongTitle">Prijava recenzije</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form method="POST">

                                                                            <h5><?php echo $korisnik['ime'] . " " . $korisnik['prezime'] ?></h5>
                                                                            <div class="row">
                                                                                <div class="col">
                                                                                    <?php
                                                                                    for ($i = 1; $i <= 5; $i++) {
                                                                                        if ($i <= $korisnik['ocjena']) {
                                                                                            echo '<i class="fa fa-star" style="color:gold;"></i>';
                                                                                        } else {
                                                                                            echo '<i class="fa fa-star-o"></i>';
                                                                                        }
                                                                                    }
                                                                                    ?>

                                                                                </div>
                                                                            </div>
                                                                            <p><?php echo $korisnik['komentar'] ?></p>

                                                                            <div class="form-group">
                                                                                <label for="razlogPrijave" class="col-form-label">Razlog prijave:</label>
                                                                                <textarea class="form-control" id="razlogPrijave" name="razlogPrijave"></textarea>
                                                                                <input name="prijavljenaRecenzija" value="<?php echo $red['recenzija_id'] ?>" hidden>
                                                                            </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Zatvori</button>
                                                                        <button type="submit" class="btn btn-danger" name="prijavaRecenzije">Prijavi</button>
                                                                    </div>


                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                    <?php endwhile;
                                    else : echo "Nema recenzija";
                                    endif; ?>



                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            <?php endif; ?>

        </div>
    </div>


    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <script src="../assets/js/main.js"></script>


</body>

</html>
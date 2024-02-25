<?php
$con = require_once "../ukljucivanje/connection/spajanje.php";
include_once("../ukljucivanje/functions/funkcije.php");
require '../vendor/autoload.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $kod = $_POST['unosKoda'];
    $email = $_SESSION['email'];

    if (isset($_SESSION['kodPoslan']) == false) {

        header('Location: registracija.php');
        die;
    }
    $stmt = $con->prepare("SELECT * FROM neverificiranikorisnici WHERE email = ? AND verifikacijski_kod = ?");

    $stmt->bind_param("ss", $email, $kod);

    $stmt->execute();

    $rezultatNeVerificiranihKorisnika = $stmt->get_result();

    $neverificirani = $rezultatNeVerificiranihKorisnika->fetch_assoc();


    if ($stmt->error) {
        echo "Greška prilikom dohvaćanja korisnika!";
        echo $con->error;
    }



    if ($rezultatNeVerificiranihKorisnika->num_rows > 0) {


        $ime = $neverificirani['ime'];
        $prezime = $neverificirani['prezime'];
        $email = $neverificirani['email'];
        $lozinka = $neverificirani['lozinka'];
        $adresa = $neverificirani['adresa'];
        $prebivaliste = $neverificirani['prebivaliste'];
        $mjesto = $neverificirani['mjesto'];
        $status_korisnika = $neverificirani['status_korisnika'];

        $sqlRegistracijaKorisnika = "INSERT INTO korisnik (ime, prezime, email, lozinka, adresa, prebivaliste, mjesto, status_korisnika) VALUES ('$ime', '$prezime', '$email', '$lozinka', '$adresa', '$prebivaliste', '$mjesto', '$status_korisnika')";
        $rezultatRegistracijeKorisnika = $con->query($sqlRegistracijaKorisnika);

        if ($con->error) {
            echo "Greška prilikom upisa korisnika!";
            echo $con->error;
        }

        if ($rezultatRegistracijeKorisnika) {
            $stmt = $con->prepare("DELETE FROM neverificiranikorisnici WHERE email = ? AND verifikacijski_kod = ?");
            $stmt->bind_param("ss", $email, $kod);
            $stmt->execute();

            $_SESSION["registered"] = true;

            header('Location: prijava.php');
            die;
        } else {
            echo "Greška prilikom registracije korisnika!";
        }
    } else {
        $_SESSION['registered'] = false;
        header('Location: registracija.php');
        die;
    };
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikacija</title>

    <?php include '../assets/css/stiliranjeSporedno.php' ?>


    <script src="../ukljucivanje/javascript/registracija.js"></script>

</head>

<body>

    <div id="main-wrapper" class="container">
        <div class="d-flex flex-column vh-100">
            <div class="row justify-content-center my-auto">
                <div class="col-xl-10">
                    <div class="card border-0">
                        <div class="card-body p-0">
                            <div id="hidenUI" class="">
                                <div class="row no-gutters">
                                    <div class="col-lg-6">
                                        <div class="p-5">
                                            <div class="mb-3">
                                                <h2><a class="h2 font-weight-bold text-theme" href="../index.php">Šalabahter</a></h2>
                                                <h3 class="h4 font-weight-bold text-theme">Registracija</h3>
                                            </div>

                                            <h6 class="h5 mb-0">Dobrodošli!</h6>
                                            <p class="text-muted mt-2 mb-2">Unesite verifikacijski kod.</p>

                                            <form class="needs-validation" method="post">
                                                <div class="row">
                                                    <div class="col-sm-6">

                                                        <div class="form-group">
                                                            <label for="unosKoda">Unesite kod</label>
                                                            <input type="text" class="form-control" name="unosKoda" id="unosKoda" required>
                                                            <div class="invalid-feedback">Molimo unesite ime.</div>
                                                        </div>
                                                    </div>

                                                </div>

                                                <button type="submit" class="btn btn-theme mt-3" style="width: 100%; padding: 10px 0;" name="prijava">Prijavi se</button>

                                        </div>


                                    </div>

                                </div>
                            </div>

                            </form>

                        </div>
                        <!-- end card-body -->
                    </div>
                    <!-- end card -->

                    <p class="text-muted text-center mt-3 mb-0">Imate korisnički račun? <a href="prijava.php" class="text-primary ml-1">Prijavite se!</a></p>
                </div>
                <!-- end row -->

            </div>
            <!-- end col -->
        </div>
        <!-- Row -->
    </div>



</body>

</html>
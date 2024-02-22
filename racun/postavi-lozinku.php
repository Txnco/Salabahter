<?php

$con = require_once "../ukljucivanje/connection/spajanje.php";
include_once("../ukljucivanje/functions/funkcije.php");

$zeton = $_GET['zeton'];

$zeton_hash = hash('sha256', $zeton);

$sqlDohvatiKorisnikaSaTimZetonom = "SELECT * FROM korisnik WHERE zeton_lozinke = ?";

$stmt = $con->prepare($sqlDohvatiKorisnikaSaTimZetonom);

$stmt->bind_param("s", $zeton_hash);

$stmt->execute();

$rezultat = $stmt->get_result();

$korisnik = $rezultat->fetch_assoc();

if($korisnik === null){
    die("Nemoguće postaviti lozinku!");
}

if(strtotime($korisnik['zeton_istice']) < time()){
    die("Zahtjev za postavljanje lozinke je istekao!");
}

if($_SERVER['REQUEST_METHOD'] === "POST"){
    $novaLozinka = $_POST['unosLozinke'];
    

    $encrypt_password = password_hash($novaLozinka, PASSWORD_DEFAULT);

    $sqlPostaviNovuLozinku = "UPDATE korisnik SET lozinka = ?, zeton_lozinke = NULL, zeton_istice = NULL WHERE korisnik_id = " . $korisnik['korisnik_id'];

    $stmt = $con->prepare($sqlPostaviNovuLozinku);

    $stmt->bind_param("s", $encrypt_password);

    $stmt->execute();

    if($con->affected_rows > 0){
        header("Location: prijava.php");
        exit;
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postavljanje nove lozinke</title>

    <?php include '../assets/css/stiliranjeSporedno.php' ?>
    <link rel="stylesheet" type="text/css" href="..\style\prijava.css">

</head>

<body>

    <div id="main-wrapper" class="container">
        <div class="d-flex flex-column vh-100">
            <div class="row justify-content-center my-auto">
                <div class="col-xl-10">
                    <div class="card border-0">
                        <div class="card-body p-0">
                            <div class="row no-gutters">
                                <div class="col-lg-6">
                                    <div class="p-5">


                                        <div class="mb-3">
                                            <h2><a class="h2 font-weight-bold text-theme" href="../index.php">Šalabahter </a></h2>
                                            <h3 class="h4 font-weight-bold text-theme">Postavite novu lozinku</h3>
                                        </div>

                                        <h6 class="h5 mb-3">Ovdje ponovo postavite lozinku!</h6>

                                        <form method="POST" class="needs-validation">
                                            <div class="form-group">
                                                <label for="unosLozinke">Nova lozinka</label>
                                                <input type="password" class="form-control" name="unosLozinke" id="unosLozinke" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="unosLozinke">Ponovite novu lozinku</label>
                                                <input type="password" class="form-control" name="unosLozinke" id="unosLozinke" required>
                                            </div>

                                            
                                            <button type="submit" class="btn btn-theme">Promijeni lozinku</button>
                                        </form>

                                        <a href="prijava.php" class="forgot-link float-right text-primary">Sjetili ste se lozinke? Prijavite se ovdje!</a>
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

                    <p class="text-muted text-center mt-3 mb-0">Nemate račun? <a href="registracija.php" class="text-primary ml-1">Registrirajte se!</a></p>

                    <!-- end row -->

                </div>
                <!-- end col -->
            </div>
            <!-- Row -->
        </div>
    </div>






</body>

</html>
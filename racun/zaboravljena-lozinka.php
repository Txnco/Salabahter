<?php
session_start();

$con = require_once "../ukljucivanje/connection/spajanje.php";
include_once("../ukljucivanje/functions/funkcije.php");


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zahtjev za postavljlanje nove lozinke</title>

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

                                        <?php if (isset($_SESSION['poslano']) == false) : ?>

                                            <div class="mb-3">
                                                <h2><a class="h2 font-weight-bold text-theme" href="../index.php">Šalabahter </a></h2>

                                                <h3 class="h4 font-weight-bold text-theme">Zahtjev za ponovno postavljanje lozinke</h3>
                                            </div>

                                            <h6 class="h5 mb-0">Ovdje možete ponovno postaviti lozinku!</h6>
                                            <p class="text-muted mt-2 mb-3">Unesite svoju e-poštu za koju ste zaboravili lozinku.</p>

                                            <form method="GET" action="posalji-lozinku.php" class="needs-validation">
                                                <div class="form-group">
                                                    <label for="unosEposte">E-mail</label>
                                                    <input type="text" class="form-control" name="unosEposte" id="unosEposte" value="<?= htmlspecialchars($email ?? "") ?>" required>
                                                </div>
                                                <button type="submit" class="btn btn-theme">Pošalji</button>
                                            </form>

                                            <a href="prijava.php" class="forgot-link float-right text-primary">Sjetili ste se lozinke? Prijavite se ovdje!</a>

                                        <?php endif; ?>

                                        <?php if (isset($_SESSION['poslano']) == true) : session_destroy(); ?>

                                            <div class="mb-3">
                                                <h2><a class="h2 font-weight-bold text-theme" href="../index.php">Šalabahter </a></h2>

                                                <h3 class="h4 font-weight-bold text-theme">Zahtjev za ponovno postavljanje lozinke je poslan</h3>
                                            </div>

                                            <h6 class="h5 mb-0">Molimo Vas da provjerite Vašu e-poštu</h6>

                                        <?php endif; ?>

                                    </div>
                                </div>


                                <div class="col-lg-6 d-none d-lg-inline-block" style="padding: 0; margin: 0;">
                                        <div class="account-block rounded-right" style="width: 100%; height: 100%;">
                                            <div class="overlay rounded-right" style="width: 100%; height: 100%;">
                                                <img src="../assets/img/Lean-38.jpg" class="img-fluid" alt="prijava" style="width: 100%; height: 100%; object-fit: cover;">
                                            </div>
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
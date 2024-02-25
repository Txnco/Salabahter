<?php
session_start();
$neuspjelaPrijava = false;

$con = require_once "../ukljucivanje/connection/spajanje.php";
include_once("../ukljucivanje/functions/funkcije.php");

if ($_SERVER['REQUEST_METHOD'] === "POST") {  // === provjerava tip i podatke varijable

    $email = $_POST['emailInput'];
    $password = $_POST['passwordInput'];

    $encrypt_password = password_hash($password, PASSWORD_DEFAULT);

    $provjeraEmail = provjera_email($email, $con);

    if ($provjeraEmail == 1) {

        $sql = sprintf(
            "SELECT * FROM korisnik WHERE email = '%s'",
            $con->real_escape_string($email)
        ); // real_escape_string osigurava da korisnik ne može izvršiti SQL napad
        $result = $con->query($sql);
        $user = $result->fetch_assoc();

        $sqlProvjeraInstruktora = "SELECT instruktor_id FROM instruktori WHERE korisnik_id = " . $user['korisnik_id'];
        $rezultatInstruktor = $con->query($sqlProvjeraInstruktora);
        if (!$instruktor = $rezultatInstruktor->fetch_assoc()) {
            $instruktor = null;
        }


        if (isset($user)) {
            if (password_verify($password, $user['lozinka'])) {

                session_regenerate_id();
                $_SESSION["user_id"] = $user["korisnik_id"]; // Postavlja se SESSION
                $_SESSION["loggedin"] = true;                // Stavlja true da je uspjela prijava za poruku
                if (isset($instruktor)) {
                    $_SESSION["instruktor"] = $instruktor['instruktor_id'];
                }
                header("Location: ../index.php");
                exit;
            }
        }
    }


    $neuspjelaPrijava = true; // Ako prijava nije uspjesna, ispisat ce se poruka korisniku

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prijava</title>

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
                            <?php if (isset($_SESSION["registered"]) && @$_SESSION["registered"] == 'true') : @$_SESSION["registered"] = false; ?>
                                <div class="alert alert-success">Uspješna registriracija! </div>
                            <?php elseif ($neuspjelaPrijava) : ?>
                                <div id="alert" class="alert alert-danger">Prijava nije uspijela! </div>
                            <?php endif; ?>
                            <div class="row no-gutters">
                                <div class="col-lg-6">
                                    <div class="p-5">


                                        <div class="mb-3">
                                            <h2><a class="h2 font-weight-bold text-theme" href="../index.php">Šalabahter </a></h2>
                                            <h3 class="h4 font-weight-bold text-theme">Prijava</h3>
                                        </div>

                                        <h6 class="h5 mb-0">Dobrodošli ponovo!</h6>
                                        <p class="text-muted mt-2 mb-3">Unesite svoje korisnično ime i lozinku kako bi ste pristupili web stranici kao korisnik.</p>

                                        <form class="needs-validation" method="post">
                                            <div class="form-group">
                                                <label for="emailInput">E-mail</label>
                                                <input type="text" class="form-control" name="emailInput" id="emailInput" value="<?= htmlspecialchars($email ?? "") ?>" required> <!-- htmlspecialchars( $username ?? "") Korisnicko ime ostaje ispunjeno ako prijava nije uspjela -->
                                            </div>

                                            <div class="form-group mb-5">
                                                <label for="passwordInput">Lozinka</label>
                                                <input type="password" class="form-control" name="passwordInput" id="passwordInput" required>
                                            </div>
                                            <button type="submit" class="btn btn-theme">Prijavi se</button>
                                            <a href="zaboravljena-lozinka.php" class="forgot-link float-right text-primary">Zaboravili ste lozinku?</a>
                                        </form>
                                    </div>
                                </div>

                                <div class="col-lg-6 d-none d-lg-inline-block" style="padding: 0; margin: 0;">
                                    <div class="account-block rounded-right" style="width: 100%; height: 100%;">
                                        <div class="overlay rounded-right" style="width: 100%; height: 100%;">
                                            <img src="../assets/img/pocetna2.jpg" class="img-fluid" alt="prijava" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>


                    <p class="text-muted text-center mt-3 mb-0">Nemate račun? <a href="registracija.php" class="text-primary ml-1">Registrirajte se!</a></p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            $('#alert').hide().fadeIn(1000).delay(5000).fadeOut(1000);
        });
    </script>



</body>

</html>
<?php
    session_start();
    $is_invalid = false;

    $con = require_once "../ukljucivanje/connection/spajanje.php";
    include_once("../ukljucivanje/functions/funkcije.php");
    
    if($_SERVER['REQUEST_METHOD'] === "POST"){  // === provjerava tip i podatke varijable

        $email = $_POST['emailInput'];
        $password = $_POST['passwordInput'];

        $encrypt_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = sprintf("SELECT * FROM korisnik WHERE email = '%s'",
                        $con->real_escape_string($email)); // real_escape_string osigurava da korisnik ne može izvršiti SQL napad

        $result = $con->query($sql);
        $user = $result->fetch_assoc();

        $sqlProvjeraInstruktora = "SELECT instruktor_id FROM instruktori WHERE korisnik_id = " . $user['korisnik_id'];
        $rezultatInstruktor = $con->query($sqlProvjeraInstruktora);
        if(!$instruktor = $rezultatInstruktor->fetch_assoc()){
            $instruktor = null;
        }

        if($user){
            if(password_verify($password, $user['lozinka'])){
                
                session_regenerate_id();
                $_SESSION["user_id"] = $user["korisnik_id"]; // Postavlja se SESSION
                $_SESSION["loggedin"] = true;                // Stavlja true da je uspjela prijava za poruku
                if(isset($instruktor)){
                    $_SESSION["instruktor"] = $instruktor['instruktor_id'];
                }
                header("Location: ../index.php");
                exit;
            }
        }

        $is_invalid = true; // Ako prijava nije uspjesna, ispisat ce se poruka korisniku

    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prijava</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <link rel="stylesheet" type="text/css" href="..\style\prijava.css">

</head>
<body>
    
<div id="main-wrapper" class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card border-0">
                <div class="card-body p-0">
                                 <?php if (isset($_SESSION["registered"]) && @$_SESSION["registered"] == 'true'): @$_SESSION["registered"]=false; ?>
                                    <div class="alert alert-success">Uspješna registriracija! </div>
                                 <?php  elseif ($is_invalid): ?>
                                    <div class="alert alert-danger">Prijava nije uspijela! </div>
                                 <?php  endif; ?>
                    <div class="row no-gutters">
                        <div class="col-lg-6">
                            <div class="p-5">
                                

                                <div class="mb-3">
                                    <h2 ><a class="h2 font-weight-bold text-theme" href="../index.php">Šalabahter </a></h2>
                                    <h3 class="h4 font-weight-bold text-theme">Prijava</h3>
                                </div>

                                <h6 class="h5 mb-0">Dobrodošli ponovo!</h6>
                                <p class="text-muted mt-2 mb-3">Unesite svoje korisnično ime i lozinku kako bi ste pristupili web stranici kao korisnik.</p>

                                <form class="needs-validation" method="post">
                                    <div class="form-group">
                                        <label for="emailInput">E-mail</label>
                                        <input type="text" class="form-control" name="emailInput" id="emailInput" value="<?= htmlspecialchars( $email ?? "") ?>" required> <!-- htmlspecialchars( $username ?? "") Korisnicko ime ostaje ispunjeno ako prijava nije uspjela -->
                                    </div>

                                    <div class="form-group mb-5">
                                        <label for="passwordInput">Lozinka</label>
                                        <input type="password" class="form-control" name="passwordInput" id="passwordInput" required>
                                    </div>
                                    <button type="submit" class="btn btn-theme">Prijavi se</button>
                                    <a href="#l" class="forgot-link float-right text-primary">Zaboravili ste lozinku?</a>
                                </form>
                            </div>
                        </div>

                        <div class="col-lg-6 d-none d-lg-inline-block">
                            <div class="account-block rounded-right" >
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

</body>
</html>
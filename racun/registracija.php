<?php
$con = require_once "../ukljucivanje/connection/spajanje.php";
include_once("../ukljucivanje/functions/funkcije.php");

$sql = "SELECT * FROM statusKorisnika";

$status1 = $con->query($sql);


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST['nameInput'];
    $surname = $_POST['surnameInput'];
    $adresa = $_POST['adressInput'];
    $mjesto = $_POST['placeInput'];
    $datum = $_POST['dateInput'];
    $email = $_POST['emailInput'];
    $password = $_POST['passwordInput'];
    $status = $_POST['statusInput'];

    $encrypt_password = password_hash($password, PASSWORD_DEFAULT);

    if (!empty($email) && !empty($password)) {

        $provjeraEmail = provjera_email($email, $con);


        if ($provjeraEmail == 0) {
            $upis = "INSERT INTO korisnik (ime,prezime,email,lozinka,adresa,mjesto,datum_rodenja,status_korisnika) VALUES (?,?,?,?,?,?,?,?)";

            $stmt = $con->stmt_init();

            if (!$stmt->prepare($upis)) {
                die("SQL error:" . $con->error);
            }

            $stmt->bind_param(
                "ssssssss",
                $name,
                $surname,
                $email,
                $encrypt_password,
                $adresa,
                $mjesto,
                $datum,
                $status
            );

            $stmt->execute();
            session_start();
            $_SESSION["registered"] = true;
            header("Location: prijava.php");
            die;
        }
    } else echo "Krivi unos!";
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

    <!-- <link rel="stylesheet" type="text/css" href="../style/prijava.css">  Stil za prijavu -->
    <script src="../ukljucivanje/javascript/register.js"></script>

    <style>
        .hideme {
            display: none;
        }
    </style>

</head>

<body>

    <div id="main-wrapper" class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="card border-0">
                    <div class="card-body p-0">
                        <div class="row no-gutters">
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="mb-3">
                                        <h2><a class="h2 font-weight-bold text-theme" href="../index.php">Šalabahter</a></h2>
                                        <h3 class="h4 font-weight-bold text-theme">Registracija</h3>
                                    </div>

                                    <div id="hidenUI" class="">
                                        <h6 class="h5 mb-0">Dobrodošli!</h6>
                                        <p class="text-muted mt-2 mb-2">Unesite svoje podatke kako bi ste otvorili svoj račun.</p>

                                        <form class="needs-validation" method="post">
                                            <div class="form-group">
                                                <label for="nameInput">Ime</label>
                                                <input type="text" class="form-control" name="nameInput" id="nameInput" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="surnameInput">Prezime</label>
                                                <input type="text" class="form-control" name="surnameInput" id="surnameInput" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="adressInput">Adresa stanovanja</label>
                                                <input type="text" class="form-control" name="adressInput" id="adressInput" required>
                                            </div>



                                            <div class="form-group">
                                                <label for="placeInput">Mjesto stanovanja</label>
                                                <select type="text" class="form-control" name="placeInput" id="placeInput" required>
                                                    <?php
                                                    $sql = "SELECT * FROM gradovi";
                                                    $result = $con->query($sql);
                                                    while ($row = $result->fetch_assoc()) : ?>
                                                        <option value="<?php echo $row["grad_id"]; ?>"><?php echo $row["naziv_grada"]; ?></option>
                                                    <?php endwhile; ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="dateInput">Datum rođenja</label>
                                                <input type="text" class="form-control" name="dateInput" id="dateInput" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="emailInput">E-mail</label>
                                                <input type="email" class="form-control" name="emailInput" id="emailInput" required>
                                                <?php
                                                if (!empty($email) && $provjeraEmail == 1) {
                                                    print("<p style='color:red';>Email je vec registriran!</p>");
                                                }
                                                ?>
                                            </div>

                                            <div class="form-group mb-5">
                                                <label for="passwordInput">Lozinka</label>
                                                <input type="password" class="form-control" name="passwordInput" id="passwordInput" required>
                                            </div>

                                            <a class="btn btn-theme" onclick="toggleUI()">Sljedeći korak</a>

                                    </div>

                                    <div id="newButtons" class="hideme">
                                        <!-- New buttons go here -->
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-lg">

                                                    <?php
                                                    if($status1->num_rows > 0):
                                                        while ($row = $status1->fetch_assoc()) :
                                                            if ($row["status_id"] == 5) continue; // Skip the iteration if status_id is 5
                                                    ?>

                                                    <div class="form-check">
                                                        <input class="btn-check" type="radio" name="statusInput" value="<?php  echo $row["status_id"]; ?>" id="statusInput" required>
                                                        <label class="btn btn-primary" for="flexRadioDefault1">
                                                            <?php echo $row["status_naziv"]; ?>
                                                        </label>
                                                    </div>

                                                    <?php 
                                                        endwhile; 
                                                        else : "Nema statusa!";
                                                        endif;
                                                    ?>

                                                    <button type="submit" class="btn btn-theme" value="Signup">Prijavi se</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



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

                <p class="text-muted text-center mt-3 mb-0">Imate korisnički račun? <a href="prijava.php" class="text-primary ml-1">Prijavite se!</a></p>

                <!-- end row -->

            </div>
            <!-- end col -->
        </div>
        <!-- Row -->
    </div>

</body>

</html>
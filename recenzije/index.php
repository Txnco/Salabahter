<?php

session_start();
$con = require "../ukljucivanje/connection/spajanje.php";
include("../ukljucivanje/functions/funkcije.php");

$user = provjeri_prijavu($con);
if (!$user) {
    header("Location: ../account/login.php");
    die;
}

$korisnikID = $_GET['korisnik']; // Moved this line inside the if statement


if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $korisnikKojiRecenzira = $user['korisnik_id'];
    $upisKomentara = $_POST["upisKomentara"];
    $ocjenaRecenzije = $_POST["ocjenaRecenzije"];


    if ($_POST['slanje'] == 1) {
        $stmt = $con->prepare("INSERT INTO recenzije (ocjena, komentar, odKorisnika, zaKorisnika) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isii", $ocjenaRecenzije, $upisKomentara, $korisnikKojiRecenzira, $korisnikID);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            echo "Došlo je do greške prilikom pisanja recenzije";
            echo $stmt->error;
        }
    }

    $stmt->close();
    header("Location: ../profil?korisnik={$korisnikID}");
    die;
}



// Dohvati korisnika po ID-u 
$sqlOdabraniKorisnik = "SELECT korisnik.korisnik_id,  ime,prezime,email,adresa,naziv_grada, status_naziv FROM korisnik, statuskorisnika, gradovi WHERE korisnik.korisnik_id={$korisnikID} AND  korisnik.status_korisnika=statuskorisnika.status_id AND korisnik.mjesto=gradovi.grad_id";
$rezultatOdabraniKorisnik = $con->query($sqlOdabraniKorisnik);

$korisnik = $rezultatOdabraniKorisnik->fetch_assoc(); // Dohvati korisnika iz baze 

$sqlProvjeraInstruktora = "SELECT * FROM instruktori WHERE korisnik_id =  {$korisnikID}"; // Provjeri da li je korisnik instruktor
$rezultatInstruktor = $con->query($sqlProvjeraInstruktora);
$instruktor = $rezultatInstruktor->fetch_assoc();
if ($rezultatInstruktor->num_rows > 0) { // Ako je korisnik instruktor onda se prikažu predmeti koje predaje i njegove skripte
    $korisnikJeInstruktor = true;
} else {
    $korisnikJeInstruktor = false;
}


$sqlAkojeKorisnikVecNapisaoRecenziju = "SELECT * FROM recenzije WHERE odKorisnika = {$_SESSION['user_id']} AND zaKorisnika = {$korisnikID}";
$rezultatAkojeKorisnikVecNapisaoRecenziju = $con->query($sqlAkojeKorisnikVecNapisaoRecenziju);
if ($rezultatAkojeKorisnikVecNapisaoRecenziju->num_rows > 0) {
  $korisnikVecNapisaoRecenziju = true;
  header("Location: ../profil?korisnik={$korisnikID}");
  die;
} else {
  $korisnikVecNapisaoRecenziju = false;
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Napiši recenziju</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <?php include '../assets/css/stiliranjeSporedno.php'; ?> <!-- Sve poveznice za stil web stranice -->
    <link rel="stylesheet" href="../assets/css/recenzije.css">

</head>

<body>

    <div id="main-wrapper" class="container">
        <div class="d-flex flex-column vh-100">
            <div class="row justify-content-center my-auto">
                <div class="col-xl-10">
                    <div class="card border-0">
                        <div class="card-body p-0">
                            <div class="row no-gutters">
                                <div class="col-lg ">

                                    <div class="row m-2">
                                        <div class="col d-flex justify-content-start align-items-center">

                                            <a class="btn text" href="../profil?korisnik=<?php echo $korisnikID ?>"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="mr-2">
                                                    <path d="M10.78 19.03a.75.75 0 0 1-1.06 0l-6.25-6.25a.75.75 0 0 1 0-1.06l6.25-6.25a.749.749 0 0 1 1.275.326.749.749 0 0 1-.215.734L5.81 11.5h14.44a.75.75 0 0 1 0 1.5H5.81l4.97 4.97a.75.75 0 0 1 0 1.06Z"></path>
                                                </svg>Vrati se na unos podatakta</a>
                                        </div>
                                    </div>


                                    <div class="p-5 ">
                                        <div class="mb-3">

                                            <h3 class="h4 font-weight-bold text-theme">Napiši recenziju za instruktora</h3>
                                        </div>

                                        <form class="needs-validation" method="POST">
                                            <div class="form-group">
                                                <label for="upisMotivacije">Napiši svoj komentar za <?php
                                                                                                    if ($korisnikJeInstruktor) {
                                                                                                        echo "instruktora/icu -" . $korisnik['ime'] . " " . $korisnik['prezime'];
                                                                                                    } else if ($korisnik['status_naziv'] == "Student") {
                                                                                                        echo "studenta/icu -" . $korisnik['ime'] . " " . $korisnik['prezime'];
                                                                                                    } else if ($korisnik['status_naziv'] == "Profesor") {
                                                                                                        echo "profesora/ice -" . $korisnik['ime'] . " " . $korisnik['prezime'];
                                                                                                    } else if ($korisnik['status_naziv'] == "Učenik") {
                                                                                                        echo "učenika/icu -" . $korisnik['ime'] . " " . $korisnik['prezime'];
                                                                                                    } else {
                                                                                                        echo "korisnika/icu -" . $korisnik['ime'] . " " . $korisnik['prezime'];
                                                                                                    }
                                                                                                    ?></label>
                                                <textarea class="form-control" name="upisKomentara" id="upisKomentara" style="max-height: 150px;" maxlength="255" required></textarea> <!-- Korisnik napiše ukratko zašto bi htio biti instruktor-->
                                            </div>

                                            <div class="form-group">
                                                <div class="container">
                                                    <div class="row">
                                                        <div class="col d-flex justify-content-center">
                                                            <label for="ocjenaRecenzije">Ocjena</label>
                                                        </div>
                                                    </div>
                                                    <div class="row ">
                                                        <div class="col d-flex justify-content-center">
                                                            <div class="rating">
                                                                <input type="radio" id="star5" name="ocjenaRecenzije" value="5" /><label for="star5" title="Odlično"></label>
                                                                <input type="radio" id="star4" name="ocjenaRecenzije" value="4" /><label for="star4" title="Dobro"></label>
                                                                <input type="radio" id="star3" name="ocjenaRecenzije" value="3" /><label for="star3" title="Prosječno"></label>
                                                                <input type="radio" id="star2" name="ocjenaRecenzije" value="2" /><label for="star2" title="Ne baš dobro"></label>
                                                                <input type="radio" id="star1" name="ocjenaRecenzije" value="1" /><label for="star1" title="Loše"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="slanje" value="1">
                                            <button type="submit" class="btn btn-primary">Pošalji recenziju</button>

                                        </form>
                                    </div>
                                </div>

                                <div class="col-lg d-none d-lg-inline-block align-items-center">
                                    <div class="account-block d-flex justify-content-center">
                                        <div class="d-flex flex-column  text-center ">
                                            <div class="mt-5">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle" width="150">
                                                <!-- Ispis podataka o korisniku -->
                                                <h4 class="mt-3"> <?php echo $korisnik["ime"] . " " . $korisnik["prezime"] ?> </h4>
                                                <p class="text-secondary mb-1">
                                                    <?php echo $korisnik['status_naziv']; ?></p>
                                                <p class="text-muted font-size-sm"><?php echo $korisnik["adresa"] . ",  ";
                                                                                    echo $korisnik['naziv_grada']; ?></p>

                                                <?php if ($korisnikJeInstruktor) : // Ako je korisnik instruktor, ispiše se natpis Instruktor 
                                                ?>
                                                    <label class="text">Instruktor</label>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <!-- end card-body -->
                    </div>
                    <!-- end card -->

                    <!-- end row -->

                </div>
                <!-- end col -->
            </div>
            <!-- Row -->
        </div>

        <script src="https://code.jquery.com/jquery-3.7.1.js"> </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

        <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
        <script src="../assets/js/main.js"></script>

</body>

</html>
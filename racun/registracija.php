<?php
$con = require_once "../ukljucivanje/connection/spajanje.php";
include_once("../ukljucivanje/functions/funkcije.php");

$sql = "SELECT * FROM statusKorisnika";

$status1 = $con->query($sql);


if ($_SERVER['REQUEST_METHOD'] == "POST") {

    
    $status = $_POST['unosStatusa'];
    if (0 < $status && $status < 4) {

        $ime = $_POST['unosIme'];
        $prezime = $_POST['unosPrezime'];
        $adresa = $_POST['unosAdrese'];
        $prebivaliste = $_POST['unosMjesta'];
        $grad = $_POST['unosObliznjegGrada'];
        $email = $_POST['unosEposte'];
        $password = $_POST['unosLozinke'];


        if (isset($_POST['prijava'])) {


            do {
                $verifikacijski_kod = rand(100000, 999999);
            
                // Check if the verification code exists in the database
                $query = "SELECT * FROM neverificiranikorisnik WHERE verifikacijski_kod = ?";
                $stmt = $con->prepare($query);
                $stmt->bind_param("i", $verifikacijski_kod);
                $stmt->execute();
                $result = $stmt->get_result();
            } while ($result->num_rows < 0);
            
            $encrypt_password = password_hash($password, PASSWORD_DEFAULT);
                        
            if (!empty($email) && !empty($password)) {
                $provjeraEmail = provjera_email($email, $con);
                
                if ($provjeraEmail == 0) {
                    $upis = "INSERT INTO neverificiranikorisnik (ime,prezime,email,lozinka,adresa,prebivaliste,mjesto,status_korisnika,verifikacijski_kod) VALUES (?,?,?,?,?,?,?,?,?)";
                    
                    $stmt = $con->stmt_init();
                    
                    if (!$stmt->prepare($upis)) {
                        die("SQL error:" . $con->error);
                    }
                    
                    $stmt->bind_param(
                        "ssssssssi",
                        $ime,
                        $prezime,
                        $email,
                        $encrypt_password,
                        $adresa,
                        $prebivaliste,
                        $grad,
                        $status,
                        $verifikacijski_kod
                    );
                    
                    $stmt->execute();
                    session_start();
                    
                    
                    
                    header("Location: prijava.php");
                    die;
                }
            } else "Neuspješna registracija!";

//Treba skinuti PHPMailer na neku foru
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

// require 'vendor/autoload.php';

// $mail = new PHPMailer(true);

// try {
//     //Server settings
//     $mail->SMTPDebug = 2;                                 
//     $mail->isSMTP();                                      
//     $mail->Host       = 'smtp.zoho.eu';  // Specify main and backup SMTP servers
//     $mail->SMTPAuth   = true;                             // Enable SMTP authentication
//     $mail->Username   = 'info@slabahter.eu';              // SMTP username
//     $mail->Password   = 'Salabahter1!';                  // SMTP password
//     $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
//     $mail->Port       = 465;                              // TCP port to connect to

//     //Recipients
//     $mail->setFrom('info@slabahter.eu', 'Mailer');
//     $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient

//     // Content
//     $mail->isHTML(true);                                  // Set email format to HTML
//     $mail->Subject = 'Here is the subject';
//     $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
//     $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

//     $mail->send();
//     echo 'Message has been sent';
// } catch (Exception $e) {
//     echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
// }


           

            if($tocankod){

                
                $encrypt_password = password_hash($password, PASSWORD_DEFAULT);
                
                if (!empty($email) && !empty($password)) {
                    
                    $provjeraEmail = provjera_email($email, $con);
                    
                    
                    if ($provjeraEmail == 0) {
                        $upis = "INSERT INTO korisnik (ime,prezime,email,lozinka,adresa,prebivaliste,mjesto,status_korisnika) VALUES (?,?,?,?,?,?,?,?)";
                        
                        $stmt = $con->stmt_init();
                        
                        if (!$stmt->prepare($upis)) {
                            die("SQL error:" . $con->error);
                        }
                        
                        $stmt->bind_param(
                            "ssssssss",
                            $ime,
                            $prezime,
                            $email,
                            $encrypt_password,
                            $adresa,
                            $prebivaliste,
                            $grad,
                            $status
                        );
                        
                        $stmt->execute();
                        session_start();
                        $_SESSION["registered"] = true;
                        
                        echo $status;
                        header("Location: prijava.php");
                        die;
                    }
                } else echo "Krivi unos!";
            }
        }

    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prijava</title>

    <?php include '../assets/css/stiliranjeSporedno.php' ?>

    <!-- <link rel="stylesheet" type="text/css" href="../style/prijava.css">  Stil za prijavu -->
    <script src="../ukljucivanje/javascript/registracija.js"></script>

    <style>
        .hideme {
            display: none;
        }
    </style>

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
                                            <p class="text-muted mt-2 mb-2">Unesite svoje podatke kako bi ste otvorili svoj račun.</p>

                                            <form class="needs-validation" method="post" >
                                                <div class="row">
                                                    <div class="col-sm-6">

                                                        <div class="form-group">
                                                            <label for="unosIme">Ime</label>
                                                            <input type="text" class="form-control" name="unosIme" id="unosIme" required>
                                                            <div class="invalid-feedback">Molimo unesite ime.</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="unosPrezime">Prezime</label>
                                                            <input type="text" class="form-control" name="unosPrezime" id="unosPrezime" required>
                                                            <div class="invalid-feedback">Molimo unesite prezime.</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="unosAdrese">Adresa stanovanja</label>
                                                            <input type="text" class="form-control" name="unosAdrese" id="unosAdrese" required>
                                                            <div class="invalid-feedback">Molimo unesite adresu stanovanja.</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="unosMjesta">Mjesto</label>
                                                            <input type="text" class="form-control" name="unosMjesta" id="unosMjesta" required>
                                                            <div class="invalid-feedback">Molimo unesite mjesto stanovanja.</div>
                                                        </div>
                                                    </div>
                                                </div>



                                                <div class="form-group">
                                                    <label for="unosObliznjegGrada">Odaberite obližnji grad</label>
                                                    <select type="text" class="form-control" name="unosObliznjegGrada" id="unosObliznjegGrada" required>
                                                        <option value="" disabled selected>Odaberite grad</option>
                                                        <?php
                                                        $sql = "SELECT * FROM gradovi ORDER BY naziv_grada ASC";
                                                        $result = $con->query($sql);
                                                        while ($row = $result->fetch_assoc()) : ?>
                                                            <option value="<?php echo $row["grad_id"]; ?>"><?php echo $row["naziv_grada"]; ?></option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                    <p class="font-weight-light">Izaberite najbliži grad vašem prebivalištu kako bismo olakšali filtraciju korisnika</p>
                                                    <div class="invalid-feedback">Molimo odaberite obližnji grad.</div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="unosEposte">E-mail</label>
                                                    <input type="email" class="form-control" name="unosEposte" id="unosEposte" required>
                                                    <?php
                                                    if (!empty($email) && $provjeraEmail == 1) {
                                                        print("<p style='color:red';>Email je vec registriran!</p>");
                                                    }
                                                    ?>
                                                    <div class="invalid-feedback">Molimo unesite email.</div>
                                                </div>

                                                <div class="form-group mb-5">
                                                    <label for="unosLozinke">Lozinka</label>
                                                    <input type="password" class="form-control" name="unosLozinke" id="unosLozinke" required>
                                                    <div class="invalid-feedback">Molimo unesite lozniku.</div>
                                                </div>

                                                <a class="btn btn-secondary" id="sljedeciKorak">Sljedeći korak</a>

                                                <div class="col-lg-6 d-none d-lg-inline-block">
                                                    <div class="account-block rounded-right">
                                                        <div class="overlay rounded-right"></div>
                                                    </div>
                                                </div>

                                        </div>


                                    </div>

                                </div>
                            </div>


                            <div id="newButtons" class="hideme">

                                <div class="container  align-items-center justify-content-center ">
                                    <div class="row mt-2">
                                        <div class="col d-flex justify-content-start align-items-center">

                                            <a class="btn text" id="prosliKorak"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="mr-2">
                                                    <path d="M10.78 19.03a.75.75 0 0 1-1.06 0l-6.25-6.25a.75.75 0 0 1 0-1.06l6.25-6.25a.749.749 0 0 1 1.275.326.749.749 0 0 1-.215.734L5.81 11.5h14.44a.75.75 0 0 1 0 1.5H5.81l4.97 4.97a.75.75 0 0 1 0 1.06Z"></path>
                                                </svg>Vrati se na unos podatakta</a>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col d-flex justify-content-center align-items-center">
                                            <h2><a class="h2 font-weight-bold text-theme" href="../index.php">Šalabahter</a></h2>
                                        </div>
                                    </div>
                                    <div class="row justify-content-center">
                                        <div class="col-lg-6">
                                            <div class="p-5 text-center">
                                                <div class="mb-3">

                                                    <h3 class="h4 font-weight-bold text-theme">Odabir vašeg statusa</h3>
                                                </div>

                                                <div class="btn-group d-flex flex-column align-items-center" role="group" aria-label="Vertical button group">
                                                    <?php
                                                    if ($status1->num_rows > 0) :
                                                        while ($row = $status1->fetch_assoc()) :
                                                            if ($row["status_id"] == 3678) continue; // Skip the iteration if status_id is 5
                                                    ?>

                                                            <label class="btn btn-secondary mb-2 animate__animated animate__fadeIn" style="width: 100%; padding: 10px 0; border-radius: 8px;">
                                                                <input type="radio" id="customRadio<?php echo $row["status_id"]; ?>" name="unosStatusa" value="<?php echo $row["status_id"]; ?>" required hidden>
                                                                <?php echo $row["status_naziv"]; ?>
                                                            </label>

                                                    <?php
                                                        endwhile;
                                                    else : echo "Nema statusa!";
                                                    endif;
                                                    ?>
                                                </div>

                                                <script>
                                                    // Get all the buttons
                                                    var buttons = document.querySelectorAll('.btn');

                                                    // Add a click event listener to each button
                                                    buttons.forEach(function(button) {
                                                        button.addEventListener('click', function() {
                                                            // Remove the 'active' class from all buttons
                                                            buttons.forEach(function(btn) {
                                                                btn.classList.remove('active');
                                                            });

                                                            // Add the 'active' class to the clicked button
                                                            this.classList.add('active');
                                                        });
                                                    });
                                                </script>

                                                <button type="submit" class="btn btn-theme mt-3" style="width: 100%; padding: 10px 0;" name="prijava">Prijavi se</button>
                                            </div>
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
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


</body>

</html>
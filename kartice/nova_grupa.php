<?php
session_start();
$con = require "../ukljucivanje/connection/spajanje.php";
include("../ukljucivanje/functions/funkcije.php");

$trenutnaStranica = "kartice2";

$putanjaDoPocetne = "../";
$putanjaDoInstruktora = "../instruktori.php";
$putanjaDoSkripta = "../skripte/";
$putanjaDoKartica = "../kartice/";
$putanjaDoOnama = "../onama.php";

$putanjaDoPrijave = "../racun/prijava.php";
$putanjaDoRegistracije = "../racun/registracija.php";

$putanjaDoRacuna = "../nadzornaploca";
$putanjaDoOdjave = "../racun/odjava.php";

$sqlPredmeti = "SELECT * FROM predmeti";
$rezultatPredmeti = $con->query($sqlPredmeti);

$user = provjeri_prijavu($con);
if (!isset($_SESSION["user_id"])) {
    header("Location: ../racun/prijava.php");
    exit;
}
$korisnikId = $_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['kreiraj_grupu'])) {

        $nazivGrupe = $_POST["grupa_naziv"];
        $opisGrupe = $_POST["grupa_opis"];
        $vlasnikId = $korisnikId;
        $javno = $_POST["javno"];

        if (isset($_POST["predmet_id"]) && !empty($_POST["predmet_id"])) {
            $predmetId = $_POST["predmet_id"];

            $datumKreiranja = date('Y-m-d H:i:s');

            $sqlInsertGrupa = "INSERT INTO grupekartica (grupa_naziv, grupa_opis, vlasnik_id, javno, predmet_id, datum_kreiranja) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $con->prepare($sqlInsertGrupa);
            $stmt->bind_param("sssiss", $nazivGrupe, $opisGrupe, $korisnikId, $javno, $predmetId, $datumKreiranja);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $grupaId = $stmt->insert_id;
                header("Location: grupa.php?grupa_id=$grupaId");
            } else {
                echo "Došlo je do greške pri unosu informacija o grupi kartica.";
            }
        } else {
            echo "Molimo odaberite predmet.";
        }
    } else {
        echo "Došlo je do greške pri obradi obrasca.";
    }
}

?>

<!DOCTYPE html>
<html lang="hr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Izradi nove kartice za ponavljanje</title>

    <?php include '../assets/css/stiliranjeSporedno.php'; ?>
    <script src="../assets/js/main.js"></script>
</head>

<body>

    <?php include '../ukljucivanje/header.php'; ?>



    <div class="row d-flex justify-content-center">
        <div class="col" style="background: linear-gradient(to right, #687EFF, #98E4FF); padding: 20px;">
            <h1 class="text-center" style="color: white;">Kreiraj grupu kartica</h1>
        </div>
    </div>


    <div class="container">
        <div class="row justify-content-center">

            <div class="col-md-4 mb-4">


                <div class="row">
                    <div class="col d-flex justify-content-start align-items-center">

                        <a class="btn text" href="index"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="mr-2">
                                <path d="M10.78 19.03a.75.75 0 0 1-1.06 0l-6.25-6.25a.75.75 0 0 1 0-1.06l6.25-6.25a.749.749 0 0 1 1.275.326.749.749 0 0 1-.215.734L5.81 11.5h14.44a.75.75 0 0 1 0 1.5H5.81l4.97 4.97a.75.75 0 0 1 0 1.06Z"></path>
                            </svg>Vrati se na grupe kartica</a>
                    </div>
                </div>
                <div class="card mt-2 mb-5">
                    <?php
                    echo  '<img src="../assets/img/predmeti/novipredmet.jpg" style="width: 100%; height: 150px; object-fit: cover;">';
                    ?>
                    <div class="card-body">

                        <form method="post" class="needs-validation" enctype="multipart/form-data">

                            <div class="col">

                                <div class="d-flex flex-column align-items-start ml-2 mt-2 mb-4 mr-4">

                                    <h6 class="mt-2" style="font-size: 1.125rem;"> <strong>Naziv grupe kartica</strong></h6>
                                    <input type="text" class="form-control" style="font-size: 16px;" id="grupa_naziv" name="grupa_naziv" maxlength="40" placeholder="Upiši naziv grupe">

                                    <h6 class="mt-2" style="font-size: 1.125rem;"><strong>Opis kartica</strong></h6>


                                    <textarea type="text" class="form-control" style="font-size: 16px; width: 334px; height:75px;" maxlength="255" id="grupa_opis" name="grupa_opis" placeholder="Upiši opis grupe"></textarea>

                                    <h6 class="mt-2" style="font-size: 1.125rem;"><strong>Predmet</strong></h6>

                                    <?php
                                    if ($rezultatPredmeti->num_rows > 0) {
                                    ?> <select class="form-control" id="predmet_id" name="predmet_id" required>
                                        <?php
                                        echo '<option value="">Odaberite predmet</option>';
                                        while ($red_predmet = $rezultatPredmeti->fetch_assoc()) {
                                            $selected = ($red_predmet['predmet_id'] == $predmetId) ? "selected" : "";
                                            echo '<option value="' . $red_predmet['predmet_id'] . '" ' . $selected . '>' . $red_predmet['naziv_predmeta'] . '</option>';
                                        }
                                        echo '</select>';
                                    } else {
                                        echo 'Nema dostupnih predmeta.';
                                    }
                                        ?>


                                        <div class="d-flex justify-content-center mt-3">
                                            <div class="form-check mr-3">
                                                <input class="form-check-input" type="radio" id="javno" name="javno" value="1">
                                                <label class="form-check-label" for="javno">
                                                    Javno
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" id="privatno" name="javno" value="0">
                                                <label class="form-check-label" for="privatno">
                                                    Privatno
                                                </label>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-center mt-3">
                                            <button type="submit" name="kreiraj_grupu" class="btn btn-primary">Kreiraj grupu kartica</button>
                                        </div>


                                </div>


                            </div>

                        </form>

                    </div>
                </div>

            </div>

        </div>
    </div>

</body>

</html>
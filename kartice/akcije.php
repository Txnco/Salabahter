<?php
session_start();
$con = require "../ukljucivanje/connection/spajanje.php";
include("../ukljucivanje/functions/funkcije.php");

if (isset($_GET['action']) && $_GET['action'] === 'dodaj_karticu') {
    $grupa_id = $_GET['grupa_id'];
    $pitanje = $_GET['pitanje'];
    $odgovor = $_GET['odgovor'];

    // Prepare and execute the SQL query to insert new card
    $sql = "INSERT INTO kartice (grupa_id, pitanje, odgovor) VALUES (?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("iss", $grupa_id, $pitanje, $odgovor);

    if ($stmt->execute()) {
        echo "Nova kartica je uspješno dodana.";
    } else {
        echo "Došlo je do greške prilikom dodavanja nove kartice: " . $stmt->error;
    }

    $stmt->close();
    header("Location: grupa.php?grupa_id=$grupa_id");
}

if (isset($_GET['action']) && $_GET['action'] === 'uredi_grupu') {
    $grupa_id = $_GET['grupa_id'];
    $naziv_grupe = $_GET['grupa_naziv'];
    $predmet = $_GET['predmet'];
    $opis_grupe = $_GET['grupa_opis'];
    $javno = $_GET['javno'];

    $sql = "UPDATE grupekartica SET grupa_naziv = ?, grupa_opis = ?, javno = ?, predmet_id=? WHERE grupa_id = ?";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssii", $naziv_grupe, $opis_grupe, $javno, $predmet, $grupa_id);

    if ($stmt->execute()) {
        echo "Grupa je uspješno ažurirana.";
        header("Location: grupa.php?grupa_id=$grupa_id");
    } else {
        echo "Došlo je do greške prilikom ažuriranja grupe: " . $stmt->error;
    }

    $stmt->close();
}



if (isset($_GET['action']) && $_GET['action'] === 'uredi_karticu') {

    if (isset($_GET['kartica_id'], $_GET['grupa_id'], $_GET['pitanje'], $_GET['odgovor'])) {

        $kartica_id = $_GET['kartica_id'];
        $grupa_id = $_GET['grupa_id'];
        $pitanje = $_GET['pitanje'];
        $odgovor = $_GET['odgovor'];

        $sql = "UPDATE kartice SET pitanje = ?, odgovor = ? WHERE kartica_id = ?";

        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssi", $pitanje, $odgovor, $kartica_id);

        if ($stmt->execute()) {
            echo "Kartica je uspješno ažurirana.";
            header("Location: grupa.php?grupa_id=$grupa_id");
        } else {
            echo "Došlo je do greške prilikom ažuriranja kartice: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Nisu postavljene sve potrebne varijable.";
    }
}

if (isset($_GET["action"]) && $_GET["action"] == "brisi_grupu") {
    if (!mysqli_connect_errno()) {
        $grupa_id = $_GET['grupa_id'];
        $sql = "DELETE FROM grupekartica WHERE grupa_id = ?";

        if ($stmt = mysqli_prepare($con, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $grupa_id);

            if (mysqli_stmt_execute($stmt)) {
            } else {
                echo "Greška: " . mysqli_error($con);
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Greška: " . mysqli_error($con);
        }
    } else {
        $poruka = "Greška kod spajanja na bazu! " . mysqli_connect_error();
    }
    header("Location: mojekartice.php");
}
if (isset($_GET["action"]) && $_GET["action"] == "brisi_karticu") {
    if (!mysqli_connect_errno()) {
        $kartica_id = $_GET['kartica_id'];
        $sql = "SELECT grupa_id FROM kartice WHERE kartica_id=$kartica_id";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);
        $grupa_id = $row['grupa_id'];

        $sql = "DELETE FROM kartice WHERE kartica_id=$kartica_id";
        $results = mysqli_query($con, $sql);
    } else {
        $poruka = "Greška kod spajanja na bazu! " . mysqli_connect_error();
    }
    header("Location: grupa.php?grupa_id=$grupa_id");
}
?>
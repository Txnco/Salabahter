<?php
session_start();
$con = require "../ukljucivanje/connection/spajanje.php";
include("../ukljucivanje/functions/funkcije.php");

if (isset($_POST['action']) && $_POST['action'] === 'obrisi_karticu') {
    if (isset($_POST['grupa_id']) && isset($_POST['kartica_id'])) {
        $grupa_id = $_POST['grupa_id'];
        $kartica_id = $_POST['kartica_id'];

        // Prepare and execute the SQL query to delete the card
        $sql = "DELETE FROM kartice WHERE grupa_id = ? AND kartica_id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ii", $grupa_id, $kartica_id);

        if ($stmt->execute()) {
            echo "Kartica je uspješno obrisana.";
        } else {
            echo "Došlo je do greške prilikom brisanja kartice: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Nisu poslani potrebni podaci za brisanje kartice.";
    }
} 
   if(isset($_GET["action"]) && $_GET["action"]=="brisi_auto_iz_baze") {
 
      if(!mysqli_connect_errno()) {
           $sql = "DELETE FROM kartice WHERE kartica_id={$_GET['kartica_id']}";
           print($sql);
           $results = mysqli_query($con, $sql);
      } else {
          $poruka = "Greška kod spajanja na bazu! " . mysqli_connect_error();
      }
      header("Location: index.php?poruka=".rawurlencode($poruka));                                                 
  }
?>
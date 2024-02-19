<?php
session_start();
$con = require "../ukljucivanje/connection/spajanje.php";
include("../ukljucivanje/functions/funkcije.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'dodaj_karticu') {
    if (isset($_POST['grupa_id']) && isset($_POST['pitanje']) && isset($_POST['odgovor'])) {
        $grupa_id = $_POST['grupa_id'];
        $pitanje = $_POST['pitanje'];
        $odgovor = $_POST['odgovor'];

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
    } else {
        echo "Nisu poslani svi potrebni podaci za dodavanje nove kartice.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj novu karticu</title>
</head>

<body>
    <h1>Dodaj novu karticu</h1>
    <form action="novo.php" method="POST">
        <input type="hidden" name="action" value="dodaj_karticu" />
        <label for="grupa_id">ID Grupe:</label>
        <input type="text" name="grupa_id" id="grupa_id" required><br><br>
        <label for="pitanje">Pitanje:</label>
        <input type="text" name="pitanje" id="pitanje" required><br><br>
        <label for="odgovor">Odgovor:</label>
        <textarea name="odgovor" id="odgovor" required></textarea><br><br>
        <button type="submit">Dodaj karticu</button>
    </form>
</body>

</html>

<?php
session_start();
$con = require "../ukljucivanje/connection/spajanje.php";
include("../ukljucivanje/functions/funkcije.php");

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
        <label for="pitanje">Pitanje:</label>
        <input type="text" name="pitanje" id="pitanje" required><br><br>
        <label for="odgovor">Odgovor:</label>
        <textarea name="odgovor" id="odgovor" required></textarea><br><br>
        <input type='hidden' name='grupa_id' value=<?php $grupa_id=6; echo "$grupa_id";?>/> 
        <button type="submit">Dodaj karticu</button>
    </form>
</body>

</html>

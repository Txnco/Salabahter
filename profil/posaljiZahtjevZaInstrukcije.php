<?php
 echo var_dump($_POST);

if ($_SERVER['REQUEST_METHOD'] == "GET") {

   

        $instruktorID = $_GET['instruktor'];
        $korisnikID = $_GET['korisnik'];


        $predmet = $_GET['predmet'];
        $opisZahtjeva = $_GET['opisZahtjeva'];
        $datum = $_GET['datum'];

        $format = DateTime::createFromFormat('d.m.Y H:i', $datum);
        $formatiraniDatum = $format->format('Y-m-d H:i:s');

       // $sqlUpisiZahtjev = "INSERT INTO zahtjevzainstrukcije (poslaoKorisnik, instruktor_id, predmetInstruktora_id, opisZahtjeva, predlozeniDatum) VALUES ('$korisnikID', '$instruktorID', '$predmet', '$opisZahtjeva', '$formatiraniDatum')";
    
}

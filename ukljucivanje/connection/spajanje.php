<?php 

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "salabahter";

$con = new mysqli(hostname: $dbhost,
                     username: $dbuser,
                     password: $dbpass,
                     database: $dbname);

if($con->connect_errno){
    die("Pogreška kod povezivanja s bazom: " . $con->connect_error);
}

return $con;

?>
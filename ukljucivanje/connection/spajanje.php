<?php 

$dbhost = "localhost";
$dbuser = "u235543018_administratori";
$dbpass = "s67Ag6fiC3!";
$dbname = "u235543018_salabahter";

$con = new mysqli(hostname: $dbhost,
                     username: $dbuser,
                     password: $dbpass,
                     database: $dbname);

if($con->connect_errno){
    die("Pogreška kod povezivanja s bazom: " . $con->connect_error);
}

return $con;

?>
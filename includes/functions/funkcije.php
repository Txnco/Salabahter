<?php

function provjeri_prijavu($con){ //Funkcija za provjeru je li korisnik prijavljen ili ne

    if(isset($_SESSION["user_id"])){
    
        $sql = "SELECT * FROM korisnik
                WHERE korisnik_id = {$_SESSION['user_id']}";
    
        $result = $con->query($sql);
        $user = $result->fetch_assoc(); //Posprema sve korisnikove podatke u $user varijablu
        return $user;
      }

}

function check_privilegeUser($con){ //Funkcija za provjeru je li korisnik prijavljen ili ne

    if(isset($_SESSION["user_id"])){
    
        $sql = "SELECT * FROM korisnik
                WHERE status_korisnika = 5 AND korisnik_id = {$_SESSION['user_id']}";
    
        $result = $con->query($sql);
    
        $user = $result->fetch_assoc(); //Posprema sve korisnikove podatke u $user varijablu
        if(isset($user)){ // Ako korisnik ima prava administratora postavi mu session varijablu isAdmin na true
            if($user['status_korisnika']==5){
                $_SESSION["isAdmin"] = true;
            }
            
            return $user;
        }
        else {
            $_SESSION["isAdmin"] = false;
        }
        
      }

    // //Prebaci korisnika na stranicu prijave
    // header("Location: ../account/login.php");
    // die;
}


function provjera_email($email, $con){
    $upitEmail = "SELECT * FROM korisnik WHERE email = '$email'";

    $rezultat =mysqli_query($con ,$upitEmail);
    $check_row = mysqli_fetch_row($rezultat); // Provjeri ako ima dobivenih redaka iz baze

    if(empty($check_row)){ // Ako nema redaka znaci da nema tog email-a u bazi
         return 0;
    }
    else return 1;
     
}

function provjera_login($encypted_password, $email, $con){
    $queryLogin = "SELECT * FROM korisnik WHERE  email='$email', lozinka = '$encypted_password' LIMIT 1";

    $result =mysqli_query($con ,$queryLogin);
    $check_row = mysqli_fetch_row($result); // Provjeri ako ima dobivenih redaka iz baze

    if(empty($check_row)){ // Ako nema redaka znaci da podaci za prijavu nisu tocni.
         return 0;
    }
    else return 1;

}
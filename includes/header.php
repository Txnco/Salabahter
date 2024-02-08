 <?php

 
 ?>


 
 <!-- ======= Header ======= -->
 <header id="header" class="d-flex align-items-center">
    <div class="container d-flex align-items-center justify-content-between">

      <div class="logo">
        <h1 class="text-light"><a href="<?php echo $pathToPocetna?>"><span>Shuffle</span></a></h1>
        <!-- Uncomment below if you prefer to use an image logo -->
        <!-- <a href="index.html"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->
      </div>

      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link scrollto active" href="<?php echo $putanjaDoPocetna?>">Home</a></li>
          <li><a class="nav-link scrollto" href="<?php echo $putanjaDoInstruktora?>">Instruktori</a></li>
          <li><a class="nav-link scrollto" href="<?php echo $putanjaDoSkripta?>">Skripte</a></li>
          <li><a class="nav-link scrollto" href="<?php echo $pathToPocetna?>#portfolio">Portfolio</a></li>
          <li><a class="nav-link scrollto" href="<?php echo $pathToPocetna?>#team">Team</a></li>
          <li class="dropdown"><a href="#"><span>Drop Down</span> <i class="bi bi-chevron-down"></i></a>
            <ul>
              <li><a href="#">Drop Down 1</a></li>
              <li class="dropdown"><a href="#"><span>Deep Drop Down</span> <i class="bi bi-chevron-right"></i></a>
                <ul>
                  <li><a href="#">Deep Drop Down 1</a></li>
                  <li><a href="#">Deep Drop Down 2</a></li>
                  <li><a href="#">Deep Drop Down 3</a></li>
                  <li><a href="#">Deep Drop Down 4</a></li>
                  <li><a href="#">Deep Drop Down 5</a></li>
                </ul>
              </li>
              <li><a href="#">Drop Down 2</a></li>
              <li><a href="#">Drop Down 3</a></li>
              <li><a href="#">Drop Down 4</a></li>
            </ul>
          </li>
          <li><a class="nav-link scrollto" href="#contact">Contact</a></li>

          <?php if(!isset($_SESSION["user_id"])): ?>
              <a href="<?php echo $pathToLogin ?>" class="nav-link scrollto ml-3" role="button">Prijava</a>
              <a href="<?php echo $pathToRegister ?>" class="nav-link scrollto" role="button">Registracija</a>
      <?php else: ?>
              <a href="<?php echo $pathToRacun ?>" class="nav-link scrollto mr-2" role="button">Račun</a>
              <a href="<?php echo $pathToLogout ?>" id="logout" class="nav-link scrollto" role="button">Odjava</a>
      <?php endif; ?>

      </div>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

    </div>

   
      
    </header>

    <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true && isset($user)): $_SESSION["loggedin"]=false ; ?>
          <div id="loginSuccessAlert" class="alert alert-success alert-dismissible fade show login-success-message" role="alert">
              User logged in successfully!
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
    <?php endif; ?>

    
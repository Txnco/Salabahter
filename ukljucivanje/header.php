 

 <!-- ======= Header ======= -->
 <header id="header" class="d-flex align-items-center">
    <div class="container d-flex align-items-center justify-content-between">

      <div class="logo">
        <h1 class="text-light"><a href="<?php echo $pathToPocetna?>"><span>Šalabahter</span></a></h1>
        <!-- Uncomment below if you prefer to use an image logo -->
        <!-- <a href="index.html"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->
      </div>

      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link scrollto <?php echo $trenutnaStranica == "index" ? 'active' : '' ?>" href="<?php echo $putanjaDoPocetne?>">Home</a></li>
          <li><a class="nav-link scrollto <?php echo $trenutnaStranica == "instruktori" ? 'active' : '' ?>" href="<?php echo $putanjaDoInstruktora?>">Instruktori</a></li>
          <li><a class="nav-link scrollto <?php echo $trenutnaStranica == "skripte" ? 'active' : '' ?>" href="<?php echo $putanjaDoSkripta?>">Skripte</a></li>
          <li class="dropdown"><a class="nav-link scrollto <?php echo $trenutnaStranica == "kartice" ? 'active' : '' ?>" href="<?php echo $putanjaDoKartica?>"><span>Kartice</span> <i class="bi bi-chevron-down"></i></a>
            <ul>
              <li><a class="nav-link scrollto <?php echo $trenutnaStranica == "kartice" ? 'active' : '' ?>" href="<?php echo $putanjaDoKartica?>">Pretraži kartice</a></li>
              <li><a class="nav-link scrollto <?php echo $trenutnaStranica == "kartice" ? 'active' : '' ?>" href="<?php echo $putanjaDoKartica?>mojekartice.php">Moje kartice</a></li>
              <li><a class="nav-link scrollto <?php echo $trenutnaStranica == "kartice" ? 'active' : '' ?>" href="<?php echo $putanjaDoKartica?>nova_grupa.php">Napravi kartice</a></li>
            </ul>
          </li>
         
          <li><a class="nav-link scrollto <?php echo $trenutnaStranica == "onama" ? 'active' : '' ?>" href="<?php echo $putanjaDoOnama?>" >O nama</a></li>

          <?php if(!isset($_SESSION["user_id"])): ?>
              <a href="<?php echo $putanjaDoPrijave ?>" class="nav-link scrollto ml-3" role="button">Prijava</a>
              <a href="<?php echo $putanjaDoRegistracije ?>" class="nav-link scrollto" role="button">Registracija</a>
      <?php else: ?>
              <a href="<?php echo $putanjaDoRacuna ?>" class="nav-link scrollto mr-2 <?php echo $trenutnaStranica == "račun" ? 'active' : '' ?>" role="button">Račun</a>
              <a href="<?php echo $putanjaDoOdjave ?>" id="logout" class="nav-link scrollto " role="button">Odjava</a>
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

    
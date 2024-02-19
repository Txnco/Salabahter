<?php
session_start();
$con = require "ukljucivanje/connection/spajanje.php";
include("ukljucivanje/functions/funkcije.php");

$user = provjeri_prijavu($con);


$pathToPocetna = 'index.php';
$putanjaDoSkripta = "skripta.php";

$pathToLogin = "account/login.php";
$pathToRegister = "account/register.php";
$pathToRacun = "dashboard/";
$pathToLogout = "account/logout.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Šalabahter</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <?php include 'assets/css/stiliranjeGlavno.php'; ?> <!-- Sve poveznice za stil web stranice -->

  <link href="assets/css/izbornik.css" rel="stylesheet">
  <script src="ukljucivanje/javascript/izbornik.js"></script>

</head>

<body>

  <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true && isset($user)) : $_SESSION["loggedin"] = false; ?>
    <div id="loginSuccessAlert" class="alert alert-success alert-dismissible fade show login-success-message" role="alert">
      Uspješno ste se prijavili!
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  <?php endif; ?>


  <section id="hero">
    <div class="hero-container">
      <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">

        <ol class="carousel-indicators" id="hero-carousel-indicators"></ol>

        <div class="carousel-inner" role="listbox">


          <div class="carousel-item active" style="background-image: url(assets/img/pocetna2.jpg);">
            <div class="carousel-container">
              <div class="carousel-content">
                <h2 class="animate__animated animate__fadeInDown">Dobrodošli na <span>Šalabahter</span></h2>
                <p class="animate__animated animate__fadeInUp">Ovo je platforma koja promovira i spaja učeničke probleme i rješenja,učenici i studenti mogu pronaći instruktore koji odgovaraju njihovim potrebama i učiti od vršnjaka koji su već prošli kroz iste izazove i prepreke.</p>
                <a href="#about" class="btn-get-started animate__animated animate__fadeInUp scrollto">Započni</a>
              </div>
            </div>
          </div>


          <div class="carousel-item" style="background-image: url(assets/img/pocetna.jpg);">
            <div class="carousel-container">
              <div class="carousel-content">
                <h2 class="animate__animated animate__fadeInDown">Razvoj komunikacijskih vještina</h2>
                <p class="animate__animated animate__fadeInUp">Postavljanjem u ulogu instruktora, učenici i studenti razvijaju vještine komunikacije, poučavanja i suosjećanja, što im koristi ne samo u akademskom okruženju već i u životu općenito.</p>
                <a href="#about" class="btn-get-started animate__animated animate__fadeInUp scrollto">Započni</a>
              </div>
            </div>
          </div>

          <div class="carousel-item" style="background-image: url(assets/img/pocetna3.jpg);">
            <div class="carousel-container">
              <div class="carousel-content">
                <h2 class="animate__animated animate__fadeInDown">Transparentnost podrške</h2>
                <p class="animate__animated animate__fadeInUp">Platforma omogućuje korisnicima da jasno vide kako su drugi učenici i instruktori pružili pomoć i podršku, potičući atmosferu uzajamnog povjerenja i podrške.</p>
                <a href="#about" class="btn-get-started animate__animated animate__fadeInUp scrollto">Započni</a>
              </div>
            </div>
          </div>

          <div class="carousel-item" style="background-image: url(assets/img/slide/s4.jpg);">
            <div class="carousel-container">
              <div class="carousel-content">
                <h2 class="animate__animated animate__fadeInDown">Samostalno učenje</h2>
                <p class="animate__animated animate__fadeInUp">Učenici mogu dijeliti PDF skripte za učenje međusobno, stvarajući tako dinamičnu razmjenu resursa i povratnih informacija unutar zajednice.</p>
                <a href="#about" class="btn-get-started animate__animated animate__fadeInUp scrollto">Započni</a>
              </div>
            </div>
          </div>

        </div>

        <a class="carousel-control-prev" href="#heroCarousel" role="button" data-bs-slide="prev">
          <span class="carousel-control-prev-icon bi bi-chevron-double-left" aria-hidden="true"></span>
        </a>

        <a class="carousel-control-next" href="#heroCarousel" role="button" data-bs-slide="next">
          <span class="carousel-control-next-icon bi bi-chevron-double-right" aria-hidden="true"></span>
        </a>

      </div>
    </div>
  </section>


  <header id="header" class="d-flex align-items-center">
    <div class="container d-flex align-items-center justify-content-between">

      <div class="logo">
        <h1 class="text-light"><a href=""><span>Šalabahter</span></a></h1>
      </div>

      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link scrollto active" href="">Home</a></li>
          <li><a class="nav-link scrollto " href="instruktori.php">Instruktori</a></li>
          <li><a class="nav-link scrollto " href="skripte/">Skripte</a></li>
          <li class="dropdown"><a class="nav-link scrollto" href="kartice/"><span>Kartice</span> <i class="bi bi-chevron-down"></i></a>
            <ul>
              <li><a class="nav-link scrollto " href="kartice/">Pretraži kartice</a></li>
              <li><a class="nav-link scrollto " href="kartice/mojekartice.php">Moje kartice</a></li>
              <li><a class="nav-link scrollto " href="kartice/nova_grupa.php">Napravi kartice</a></li>
            </ul>
          </li>
          <li><a class="nav-link scrollto " href="onama.php">O nama</a></li>

          <?php if (!isset($_SESSION["user_id"])) : ?>
            <a href="racun/prijava.php" class="nav-link scrollto ml-3" role="button">Prijava</a>
            <a href="racun/registracija.php" class="nav-link scrollto" role="button">Registracija</a>
          <?php else : ?>
            <a href="nadzornaploca/index.php" class="nav-link scrollto mr-2" role="button">Račun</a>
            <a href="racun/odjava.php" id="logout" class="nav-link scrollto " role="button">Odjava</a>
          <?php endif; ?>

    </div>
    </ul>
    <i class="bi bi-list mobile-nav-toggle"></i>
    </nav>

    </div>



  </header>


  <main id="main">


    <!-- ======= Naše usluge ======= -->
    <section id="services" class="services">
      <div class="container">

        <div class="section-title">
          <h2>Naše usluge</h2>
          <p>Tražite instruktora za neki predmet? Došli ste na pravu stranicu za Vas. Naša web stranica olakšava Vam da pronađete instruktora. Također na našoj web stranici možete pronaći i skripte za učenje iz raznih predmeta i kartice za ponavljanje. </p>
        </div>

        <div class="row justify-content-center">
          <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
            <div class="icon-box">
              <div class="icon"><i class="bx bxl-dribbble"></i></div>
              <h4 class="title"><a href="">Instruktori</a></h4>
              <p class="description">Pronađite instruktora koji odgovara Vama za bilo koji ponuđeni predmet.</p>
            </div>
          </div>

          <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
            <div class="icon-box">
              <div class="icon"><i class="bx bx-file"></i></div>
              <h4 class="title"><a href="">Skripte</a></h4>
              <p class="description">Dijeljenje je znanje. Možete učuti iz raznih skripta za pojedini predmet.</p>
            </div>
          </div>

          <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
            <div class="icon-box">
              <div class="icon"><i class="bx bx-tachometer"></i></div>
              <h4 class="title"><a href="">Kartice za ponavljlanje</a></h4>
              <p class="description">Kako bi ste sve to usvojili, možete učenje učiniti zabavnim preko rješavanja kartica za ponavljanje!</p>
            </div>
          </div>

        </div>

      </div>
    </section>

    <!-- ======= Poziv na radnju ======= -->
    <section class="cta">
      <div class="container">

        <div class="text-center">
          <h3>Prijavite se i postanite instruktor</h3>
          <p> Imate dovoljno znanja iz nekog predmeta? Zašto ne podijeliti to znanje sa drugima. Prijavite se za instruktora te počnite pomagati drugima!</p>
          <a class="cta-btn" href="#">Postani instruktor</a>
        </div>

      </div>
    </section>

    <!-- ======= Više usluga ======= -->
    <!-- <section class="more-services section-bg">
      <div class="container">

        <div class="row">
          <div class="col-lg-4 col-md-6 d-flex align-items-stretch mb-5 mb-lg-0">
            <div class="card">
              <img src="assets/img/more-service-1.jpg" class="card-img-top" alt="...">
              <div class="card-body">
                <h5 class="card-title"><a href="">Autem sunt earum</a></h5>
                <p class="card-text">Et architecto provident deleniti facere repellat nobis iste. Id facere quia quae dolores dolorem tempore</p>
                <a href="#" class="btn">Explore more</a>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 d-flex align-items-stretch mb-5 mb-lg-0">
            <div class="card">
              <img src="assets/img/more-service-2.jpg" class="card-img-top" alt="...">
              <div class="card-body">
                <h5 class="card-title"><a href="">Nobis et tempore</a></h5>
                <p class="card-text">Ut quas omnis est. Non et aut tempora dignissimos similique in dignissimos. Sit incidunt et odit iusto</p>
                <a href="#" class="btn">Explore more</a>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 d-flex align-items-stretch mb-5 mb-lg-0">
            <div class="card">
              <img src="assets/img/more-service-3.jpg" class="card-img-top" alt="...">
              <div class="card-body">
                <h5 class="card-title"><a href="">Facere quia quae dolores</a></h5>
                <p class="card-text">Modi ut et delectus. Modi nobis saepe voluptates nostrum. Sed quod consequatur quia provident dera</p>
                <a href="#" class="btn">Explore more</a>
              </div>
            </div>
          </div>
        </div>

      </div>
    </section>End More Services Section -->

    <!-- ======= Informacije ======= -->
    <section class="info-box py-0">
      <div class="container-fluid">

        <div class="row">

          <div class="col-lg-7 d-flex flex-column justify-content-center align-items-stretch  order-2 order-lg-1">

            <div class="content">
              <h3>Kako mogu postati <strong>instruktor?</strong></h3>
              <p>
                Morate se prijaviti na našoj web stranici putem obrasca za prijavu. Nakon što se prijavite, možete postati instruktor i početi pomagati drugima.
              </p>
            </div>

            <div class="accordion-list">
              <ul>
                <li>
                  <a data-bs-toggle="collapse" class="collapse" data-bs-target="#accordion-list-1"><span>01</span> Registrirajte se i napravite korisnički račun <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i></a>
                  <div id="accordion-list-1" class="collapse show" data-bs-parent=".accordion-list">
                    <p>
                      Registraciju možete obaviti klikom na gumb "Registracija" u gornjem desnom kutu. Nakon što se registrirate, možete se prijaviti te na svom računu možete vidjeti tipku <strong>postani instruktor</strong>.
                    </p>
                  </div>
                </li>

                <li>
                  <a data-bs-toggle="collapse" data-bs-target="#accordion-list-2" class="collapsed"><span>02</span> Što kod zahtjeva? <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i></a>
                  <div id="accordion-list-2" class="collapse" data-bs-parent=".accordion-list">
                    <p>
                      Nakon pritiska na tipku postani instruktor morate ispuniti obrazac sa svim potrebnim informacijama. Nakon što se prijavite, administratori će pregledati vaš zahtjev i odobriti ga ili odbiti ovisno o vašim kvalifikacijama.
                    </p>
                  </div>
                </li>

                <li>
                  <a data-bs-toggle="collapse" data-bs-target="#accordion-list-3" class="collapsed"><span>03</span> Kako se kvalificirati za instruktora <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i></a>
                  <div id="accordion-list-3" class="collapse" data-bs-parent=".accordion-list">
                    <p>
                      Kako bi ste postali instruktori mi moramo biti sigurni da imate dovoljno znanja iz određenog predmeta. <br>
                      Ako ste <strong>učenik</strong> morat ćete nam poslati potvrdu o ocjeni iz određenog predmeta. <br>
                      Ako ste <strong>student</strong> morat ćete nam poslati potvrdu o ocjeni iz određenog predmeta te potvrdu o upisu na fakultet.<br>
                      Ako ste <strong>profesor ili učitelj</strong> morat ćete nam poslati potvrdu o zaposlenju ili završenom studiju.
                    </p>
                  </div>
                </li>

              </ul>
            </div>

          </div>

          <div class="col-lg-5 align-items-stretch order-1 order-lg-2 img" style="background-image: url(assets/img/info-box.jpg);">&nbsp;</div>
        </div>

      </div>
    </section>

    <!-- ======= Our Portfolio Section ======= -->

    <!-- <section id="portfolio" class="portfolio section-bg">
      <div class="container">

        <div class="section-title">
          <h2>Our Portfolio</h2>
          <p>Magnam dolores commodi suscipit. Necessitatibus eius consequatur ex aliquid fuga eum quidem. Sit sint consectetur velit. Quisquam quos quisquam cupiditate. Et nemo qui impedit suscipit alias ea. Quia fugiat sit in iste officiis commodi quidem hic quas.</p>
        </div>

        <div class="row">
          <div class="col-lg-12 d-flex justify-content-center">
            <ul id="portfolio-flters">
              <li data-filter="*" class="filter-active">All</li>
              <li data-filter=".filter-app">App</li>
              <li data-filter=".filter-card">Card</li>
              <li data-filter=".filter-web">Web</li>
            </ul>
          </div>
        </div>

        <div class="row portfolio-container">

          <div class="col-lg-4 col-md-6 portfolio-item filter-app">
            <div class="portfolio-wrap">
              <img src="assets/img/portfolio/portfolio-1.jpg" class="img-fluid" alt="">
              <div class="portfolio-info">
                <h4>App 1</h4>
                <p>App</p>
              </div>
              <div class="portfolio-links">
                <a href="assets/img/portfolio/portfolio-1.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox" title="App 1"><i class="bx bx-plus"></i></a>
                <a href="portfolio-details.html" title="More Details"><i class="bx bx-link"></i></a>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-web">
            <div class="portfolio-wrap">
              <img src="assets/img/portfolio/portfolio-2.jpg" class="img-fluid" alt="">
              <div class="portfolio-info">
                <h4>Web 3</h4>
                <p>Web</p>
              </div>
              <div class="portfolio-links">
                <a href="assets/img/portfolio/portfolio-2.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Web 3"><i class="bx bx-plus"></i></a>
                <a href="portfolio-details.html" title="More Details"><i class="bx bx-link"></i></a>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-app">
            <div class="portfolio-wrap">
              <img src="assets/img/portfolio/portfolio-3.jpg" class="img-fluid" alt="">
              <div class="portfolio-info">
                <h4>App 2</h4>
                <p>App</p>
              </div>
              <div class="portfolio-links">
                <a href="assets/img/portfolio/portfolio-3.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox" title="App 2"><i class="bx bx-plus"></i></a>
                <a href="portfolio-details.html" title="More Details"><i class="bx bx-link"></i></a>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-card">
            <div class="portfolio-wrap">
              <img src="assets/img/portfolio/portfolio-4.jpg" class="img-fluid" alt="">
              <div class="portfolio-info">
                <h4>Card 2</h4>
                <p>Card</p>
              </div>
              <div class="portfolio-links">
                <a href="assets/img/portfolio/portfolio-4.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Card 2"><i class="bx bx-plus"></i></a>
                <a href="portfolio-details.html" title="More Details"><i class="bx bx-link"></i></a>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-web">
            <div class="portfolio-wrap">
              <img src="assets/img/portfolio/portfolio-5.jpg" class="img-fluid" alt="">
              <div class="portfolio-info">
                <h4>Web 2</h4>
                <p>Web</p>
              </div>
              <div class="portfolio-links">
                <a href="assets/img/portfolio/portfolio-5.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Web 2"><i class="bx bx-plus"></i></a>
                <a href="portfolio-details.html" title="More Details"><i class="bx bx-link"></i></a>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-app">
            <div class="portfolio-wrap">
              <img src="assets/img/portfolio/portfolio-6.jpg" class="img-fluid" alt="">
              <div class="portfolio-info">
                <h4>App 3</h4>
                <p>App</p>
              </div>
              <div class="portfolio-links">
                <a href="assets/img/portfolio/portfolio-6.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox" title="App 3"><i class="bx bx-plus"></i></a>
                <a href="portfolio-details.html" title="More Details"><i class="bx bx-link"></i></a>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-card">
            <div class="portfolio-wrap">
              <img src="assets/img/portfolio/portfolio-7.jpg" class="img-fluid" alt="">
              <div class="portfolio-info">
                <h4>Card 1</h4>
                <p>Card</p>
              </div>
              <div class="portfolio-links">
                <a href="assets/img/portfolio/portfolio-7.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Card 1"><i class="bx bx-plus"></i></a>
                <a href="portfolio-details.html" title="More Details"><i class="bx bx-link"></i></a>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-card">
            <div class="portfolio-wrap">
              <img src="assets/img/portfolio/portfolio-8.jpg" class="img-fluid" alt="">
              <div class="portfolio-info">
                <h4>Card 3</h4>
                <p>Card</p>
              </div>
              <div class="portfolio-links">
                <a href="assets/img/portfolio/portfolio-8.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Card 3"><i class="bx bx-plus"></i></a>
                <a href="portfolio-details.html" title="More Details"><i class="bx bx-link"></i></a>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-web">
            <div class="portfolio-wrap">
              <img src="assets/img/portfolio/portfolio-9.jpg" class="img-fluid" alt="">
              <div class="portfolio-info">
                <h4>Web 3</h4>
                <p>Web</p>
              </div>
              <div class="portfolio-links">
                <a href="assets/img/portfolio/portfolio-9.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Web 3"><i class="bx bx-plus"></i></a>
                <a href="portfolio-details.html" title="More Details"><i class="bx bx-link"></i></a>
              </div>
            </div>
          </div>

        </div>

      </div>
    </section>End Our Portfolio Section -->

    <!-- ======= Our Team Section ======= -->
    <section id="team" class="team">
      <div class="container">

        <div class="section-title">
          <h2>Naš tim</h2>
          <p>Naš tim sastoji se od dva inovativna i poduzetna učenika koji su odlučuli olakšati jedan problem svakodnevice, a tako i u isto vrijeme napraviti učenje zabavnijim!</p>
        </div>

        <div class="row justify-content-center">

          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="member">
              <div style="width: 306px; height: 306px; overflow: hidden;">
                <img src="assets/img/noa.jpg" style="width: 100%; height: 100%; object-fit: cover;" alt="">
              </div>
              <div class="member-info">
                <div class="member-info-content">
                  <h4>Noa Turk</h4>
                  <span>Učenik</span>
                </div>
                <div class="social">
                  <a href=""><i class="bi bi-twitter"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="member">
              <div style="width: 306px; height: 306px; overflow: hidden;">
                <img src="assets/img/antonio.jpg" style="width: 100%; height: 100%; object-fit: cover;" alt="">
              </div>
              <div class="member-info">
                <div class="member-info-content">
                  <h4>Antonio Ivanović</h4>
                  <span>Učenik</span>
                </div>
                <div class="social">
                  <a href=""><i class="bi bi-twitter"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div>


        </div>

      </div>
    </section><!-- End Our Team Section -->

    <!-- ======= Contact Us Section ======= -->
    <section id="contact" class="contact section-bg">

      <div class="container">
        <div class="section-title">
          <h2>Kontaktirajte nas</h2>
          <p>Ako imate bilo kakvih pitanja ili nedoumica slobodno nas kontaktirajte preko sljedećeg obrasca.</p>
        </div>
      </div>

      <div class="container-fluid">

        <div class="row justify-content-center">


          <div class="col-lg-6 d-flex align-items-stretch contact-form-wrap">
            <form action="forms/contact.php" method="post" role="form" class="php-email-form">
              <div class="row">
                <div class="col-md-6 form-group">
                  <label for="name">Vaše ime</label>
                  <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" required>
                </div>
                <div class="col-md-6 form-group mt-3 mt-md-0">
                  <label for="email">Vaša e-pošta</label>
                  <input type="email" class="form-control" name="email" id="email" placeholder="Vaša e-pošta" required>
                </div>
              </div>
              <div class="form-group mt-3">
                <label for="subject">Tema</label>
                <input type="text" class="form-control" name="subject" id="subject" placeholder="Tema" required>
              </div>
              <div class="form-group mt-3">
                <label for="message">Poruka</label>
                <textarea class="form-control" name="message" rows="8" required></textarea>
              </div>
              <div class="my-3">
                <div class="loading">Loading</div>
                <div class="error-message"></div>
                <div class="sent-message">Your message has been sent. Thank you!</div>
              </div>
              <div class="text-center"><button type="submit">Pošalji poruku</button></div>
            </form>
          </div>

        </div>

      </div>
    </section><!-- End Contact Us Section -->

  </main>

  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="footer-top">
      <div class="container mx-auto">
        <div class="row justify-content-center">

          <div class="col-lg-3 col-md-6 footer-info">
            <h3 class="text">Šalabahter</h3>
            <p>
              Glavna ulica, Čakovec<br>
              40 000, Hrvatska<br><br>
              <strong>Phone:</strong> +385 98 9385 653<br>
              <strong>Email:</strong> info@salabahter.eu<br>
            </p>
            <div class="social-links mt-3">
              <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
              <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
            </div>
          </div>

          <div class="col-lg-2 col-md-6 footer-links">
            <h4>Korisne poveznice</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Edutorij</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">e-Dnevnik</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">e-Sfera</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Terms of service</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Privacy policy</a></li>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Naše usluge</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="instruktori.php">Instruktori</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="skripte/">Skripte</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="kartice">Kartice</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="onama.php">O nama</a></li>
            </ul>
          </div>

          <!-- <div class="col-lg-4 col-md-6 footer-newsletter">
            <h4>Our Newsletter</h4>
            <p>Tamen quem nulla quae legam multos aute sint culpa legam noster magna</p>
            <form action="" method="post">
              <input type="email" name="email"><input type="submit" value="Subscribe">
            </form>

          </div> -->

        </div>
      </div>
    </div>

    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong><span>Šalabahter</span></strong> pridržava sva prava
      </div>
      <div class="credits">
      </div>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>


  <script src="assets/js/main.js"></script>

</body>

</html>
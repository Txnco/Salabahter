<?php
session_start();
$con = require "ukljucivanje/connection/spajanje.php";
include("ukljucivanje/functions/funkcije.php");

$user = provjeri_prijavu($con);


$trenutnaStranica = "početna";

$putanjaDoPocetne = "";
$putanjaDoInstruktora = "instruktori.php";
$putanjaDoSkripta = "skripte/";
$putanjaDoKartica = "kartice/";
$putanjaDoOnama = "onama.php";

$putanjaDoPrijave = "racun/prijava.php";
$putanjaDoRegistracije = "racun/registracija.php";

$putanjaDoRacuna = "nadzornaploca";
$putanjaDoOdjave = "racun/odjava.php";
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




  <?php include 'ukljucivanje/header.php'; ?>

  <!-- Slike -->
  <section id="hero" >
    <div class="hero-container">
      <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">

        <ol class="carousel-indicators" id="hero-carousel-indicators"></ol>

        <div class="carousel-inner" role="listbox">


          <div class="carousel-item active" style="background-image: url(assets/img/pocetna2.jpg);">
            <div class="carousel-container">
              <div class="carousel-content ">
                <h2 class="animate__animated animate__fadeInDown">Dobrodošli na <span>Šalabahter</span></h2>
                <p class="animate__animated animate__fadeInUp">Ovo je platforma koja promovira i spaja učeničke probleme i rješenja, učenici i studenti mogu pronaći instruktore koji odgovaraju njihovim potrebama i učiti od vršnjaka koji su već prošli kroz iste izazove i prepreke.</p>
                <a href="/racun/registracija.php" class="btn btn-pocentna  animate__animated animate__fadeInUp scrollto">Registriraj se!</a>
                <a href="/racun/prijava.php" class="btn btn-pocentna animate__animated animate__fadeInUp scrollto">Prijavi se!</a>
              </div>
            </div>
          </div>


          <div class="carousel-item" style="background-image: url(assets/img/pocetna.jpg);">
            <div class="carousel-container">
              <div class="carousel-content">
                <h2 class="animate__animated animate__fadeInDown">Razvoj komunikacijskih vještina</h2>
                <p class="animate__animated animate__fadeInUp">Postavljanjem u ulogu instruktora, učenici i studenti razvijaju vještine komunikacije, poučavanja i suosjećanja, što im koristi ne samo u akademskom okruženju već i u životu općenito.</p>
                <a href="#usluge" class="btn btn-pocentna  animate__animated animate__fadeInUp scrollto">Započni</a>
              </div>
            </div>
          </div>

          <div class="carousel-item" style="background-image: url(assets/img/pocetna3.jpg);">
            <div class="carousel-container">
              <div class="carousel-content">
                <h2 class="animate__animated animate__fadeInDown">Transparentnost podrške</h2>
                <p class="animate__animated animate__fadeInUp">Platforma omogućava korisnicima da jasno vide kako su drugi učenici i instruktori pružili pomoć i podršku, potičući atmosferu uzajamnog povjerenja i podrške.</p>
                <a href="#instruktor" class="btn btn-pocentna  animate__animated animate__fadeInUp scrollto">Započni</a>
              </div>
            </div>
          </div>

          <div class="carousel-item" style="background-image: url(assets/img/Lean-38.jpg);">
            <div class="carousel-container">
              <div class="carousel-content">
                <h2 class="animate__animated animate__fadeInDown">Samostalno učenje</h2>
                <p class="animate__animated animate__fadeInUp">Učenici mogu međusobno dijeliti PDF skripte za učenje međusobno, stvarajući tako dinamičnu razmjenu resursa i povratnih informacija unutar zajednice.</p>
                <a href="skripte/" class="btn btn-pocentna  animate__animated animate__fadeInUp scrollto">Odvedi me na skripte</a>
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

  <main id="main">


    <!--  Naše usluge  -->
    <section id="usluge" class="services">
      <div class="container">

        <div class="section-title">
          <h2>Naše usluge</h2>
          <p>Tražite instruktora za neki predmet? Došli ste na pravu stranicu za Vas. Naša web stranica olakšava Vam da pronađete instruktora. Također na našoj web stranici možete pronaći i skripte za učenje iz raznih predmeta i kartice za ponavljanje. </p>
        </div>

        <div class="row justify-content-center">
          <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
            <div class="icon-box">
              <div class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                  <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
                </svg></i></div>
              <h4 class="title"><a href="instruktori.php">Instruktori</a></h4>
              <p class="description">Pronađite instruktora koji odgovara Vama za bilo koji ponuđeni predmet.</p>
            </div>
          </div>

          <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
            <div class="icon-box">
              <div class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" fill="currentColor" class="bi bi-file-earmark-pdf" viewBox="0 0 16 16">
                  <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z" />
                  <path d="M4.603 14.087a.8.8 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.7 7.7 0 0 1 1.482-.645 20 20 0 0 0 1.062-2.227 7.3 7.3 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a11 11 0 0 0 .98 1.686 5.8 5.8 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.86.86 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.7 5.7 0 0 1-.911-.95 11.7 11.7 0 0 0-1.997.406 11.3 11.3 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.8.8 0 0 1-.58.029m1.379-1.901q-.25.115-.459.238c-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361q.016.032.026.044l.035-.012c.137-.056.355-.235.635-.572a8 8 0 0 0 .45-.606m1.64-1.33a13 13 0 0 1 1.01-.193 12 12 0 0 1-.51-.858 21 21 0 0 1-.5 1.05zm2.446.45q.226.245.435.41c.24.19.407.253.498.256a.1.1 0 0 0 .07-.015.3.3 0 0 0 .094-.125.44.44 0 0 0 .059-.2.1.1 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a4 4 0 0 0-.612-.053zM8.078 7.8a7 7 0 0 0 .2-.828q.046-.282.038-.465a.6.6 0 0 0-.032-.198.5.5 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822q.036.167.09.346z" />
                </svg></div>
              <h4 class="title"><a href="skripte/">Skripte</a></h4>
              <p class="description">Dijeljenje je znanje. Možete učiti iz raznih skripti za pojedini predmet.</p>
            </div>
          </div>

          <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
            <div class="icon-box">
              <div class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" fill="currentColor" class="bi bi-card-list" viewBox="0 0 16 16">
                  <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z" />
                  <path d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8m0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0M4 8a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0m0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0" />
                </svg></div>
              <h4 class="title"><a href="kartice/">Kartice za ponavljlanje</a></h4>
              <p class="description">Kako bi ste sve to usvojili, možete učenje učiniti zabavnim preko rješavanja kartica za ponavljanje!</p>
            </div>
          </div>

        </div>

      </div>
    </section>

    <!--  Poziv na radnju  -->
    <section class="cta">
      <div class="container">

        <div class="text-center">
          <h3>Prijavite se i postanite instruktor</h3>
          <p> Imate dovoljno znanja iz nekog predmeta? Zašto ne podijeliti to znanje s drugima. Prijavite se za instruktora te počnite pomagati drugima!</p>
          <a class="btn btn-pocentna" href="racun/prijava.php">Postani instruktor</a>
        </div>

      </div>
    </section>



    <!-- Informacije  -->
    <section id="instruktor" class="info-box py-0">
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
                      Registraciju možete obaviti klikom na gumb <strong>"Registracija"</strong> u gornjem desnom kutu. Nakon što se registrirate, možete se prijaviti i na svom računu vidjeti gumb <strong>"Postani instruktor"</strong>.
                    </p>
                  </div>
                </li>

                <li>
                  <a data-bs-toggle="collapse" data-bs-target="#accordion-list-2" class="collapsed"><span>02</span> Kako poslati zahtjev? <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i></a>
                  <div id="accordion-list-2" class="collapse" data-bs-parent=".accordion-list">
                    <p>
                      Nakon pritiska na gumb <strong>"Postani instruktor"</strong> morate ispuniti obrazac sa svim potrebnim informacijama. Nakon prijave, administratori će pregledati vaš zahtjev i odobriti ga ili odbiti ovisno o vašim kvalifikacijama.
                    </p>
                  </div>
                </li>

                <li>
                  <a data-bs-toggle="collapse" data-bs-target="#accordion-list-3" class="collapsed"><span>03</span> Kako se kvalificirati za instruktora <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i></a>
                  <div id="accordion-list-3" class="collapse" data-bs-parent=".accordion-list">
                    <p>
                      Kako bi postali instruktori mi moramo biti sigurni da imate dovoljno znanja iz određenog predmeta. <br>
                      Ako ste <strong>učenik</strong> morat ćete nam poslati potvrdu o ocjeni iz određenog predmeta. <br>
                      Ako ste <strong>student</strong> morat ćete nam poslati potvrdu o ocjeni iz određenog predmeta i potvrdu o upisu na fakultet.<br>
                      Ako ste <strong>profesor ili učitelj</strong> morat ćete nam poslati potvrdu o zaposlenju ili završenom studiju.
                    </p>
                  </div>
                </li>

              </ul>
            </div>

          </div>

          <div class="col-lg-5 align-items-stretch order-1 order-lg-2 img" style="background-image: url(assets/img/Lean-251.jpg);">&nbsp;</div>
        </div>

      </div>
    </section>


    <!-- Naš tim  -->
    <section id="team" class="team">
      <div class="container">

        <div class="section-title">
          <h2>Naš tim</h2>
          <p>Tim koji razvija platformu „Šalabahter“ čine Antonio Ivanović koji se u slobodno vrijeme bavi fotografijom i Noa Turk koji se bavi radio-orijentacijskim trčanjem. Maturanti smo Tehničke Škole Čakovec, smjera tehničar za računalstvo. Odlučili smo kao zadnji zajednički projekt tijekom srednje škole razviti platformu koja će povezat učenike, studente i instruktore.Nadamo se da će naša ideja zaživjeti i uspjeti, te doprinijeti boljem iskustvu učenja i suradnje u obrazovnom sektoru.</p>
        </div>

        <div class="row justify-content-center">

          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="member">
              <div style="width: 306px; height: 306px; overflow: hidden;">
                <img src="assets/img/tim/Noa.jpg" style="width: 100%; height: 100%; object-fit: cover;" alt="">
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
                <img src="assets/img/tim/antonio.jpg" style="width: 100%; height: 100%; object-fit: cover;" alt="">
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
    </section>


  </main>

  <!-- ======= Podnožje ======= -->
  <footer id="footer">
    <div class="footer-top">
      <div class="container mx-auto">
        <div class="row justify-content-center">

          <div class="col-lg-3 col-md-6 footer-info">
            <h3 class="text">Šalabahter</h3>
            <strong>Adresa:</strong> 40 000, Čakovec, Hrvatska<br>
            <strong>Broj:</strong> +385 98 9385 653<br>
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
              <li><i class="bx bx-chevron-right"></i> <a href="https://edutorij.carnet.hr/">Edutorij</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="https://ocjene.skole.hr">e-Dnevnik</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="https://www.e-sfera.hr/">e-Sfera</a></li>
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

        </div>
      </div>
    </div>

    <div class="container">
      <div class="copyright">
        &copy; <strong><span>Šalabahter</span></strong> pridržava sva prava
      </div>
      <div class="credits">
      </div>
    </div>
  </footer>

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
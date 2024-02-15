<div class="col-sm-3">

    <div class="izbornik p-3" id="izbornikWeb">

        <div class="row text-center">
            <h4 class="text">Nadzorna ploča</h4>
        </div>

        <hr class="m-2">

        <ul class="nav flex-column mb-auto">
            <li class="nav-item">
                <a href="../admin/" class="nav-link nadlink <?php echo $trenutnaStranica2 == "Račun" ? 'aktivno' : '' ?>">Račun</a>
            </li>
            <li class="nav-item">
                <a href="zahtjevi.php" class="nav-link nadlink <?php echo $trenutnaStranica2 == "Zahtjevi" ? 'aktivno' : '' ?>">
                    Zahtjevi za instruktora
                    <span class="badge bg-danger float-end">
                        <?php echo isset($brojZahtjeva) && $brojZahtjeva > 0 ? $brojZahtjeva : '0'; ?>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a href="prijaverecenzija.php" class="nav-link nadlink <?php echo $trenutnaStranica2 == "Recenzije" ? 'aktivno' : '' ?>">
                    Prijave recenzija
                    <span class="badge bg-danger float-end">
                        <?php echo isset($brojPrijavaRecenzija) && $brojPrijavaRecenzija > 0 ? $brojPrijavaRecenzija : '0'; ?>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a href="svikorisnici.php" class="nav-link nadlink <?php echo $trenutnaStranica2 == "Korisnici" ? 'aktivno' : '' ?>">Svi
                    korisnici</a>
            </li>
            <li class="nav-item">
                <a href="" class="nav-link nadlink <?php echo $trenutnaStranica2 == "skripte" ? 'aktivno' : '' ?>">Sve
                    skripte</a>
            </li>
            <li class="nav-item">
                <a href="" class="nav-link nadlink <?php echo $trenutnaStranica2 == "grupekartica" ? 'aktivno' : '' ?>">Sve
                    grupe kartica</a>
            </li>
        </ul>
    </div>

    <div class="izbornik p-3" id="izbornikMobitel">

        <div class="row text-center">
            <h4 class="text">
                <a class="btn" aria-expanded="false" aria-controls="postavkeTrazilice" id="prikazi">
                    <?php echo $trenutnaStranica2 ?>
                    <svg class="arrow-up" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16" style="display: none;">
                        <path d="M3.22 10.53a.749.749 0 0 1 0-1.06l4.25-4.25a.749.749 0 0 1 1.06 0l4.25 4.25a.749.749 0 1 1-1.06 1.06L8 6.811 4.28 10.53a.749.749 0 0 1-1.06 0Z">
                        </path>
                    </svg>
                    <svg class="arrow-down" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                        <path d="M12.78 5.22a.749.749 0 0 1 0 1.06l-4.25 4.25a.749.749 0 0 1-1.06 0L3.22 6.28a.749.749 0 1 1 1.06-1.06L8 8.939l3.72-3.719a.749.749 0 0 1 1.06 0Z">
                        </path>
                    </svg>
                </a></i>
            </h4>
        </div>

        <hr class="m-2">

        <ul class="nav flex-column mb-auto collapse" id="padajuciIzbornik">

            <li class="nav-item">
                <a href="../admin/" class="nav-link nadlink <?php echo $trenutnaStranica2 == "Račun" ? 'aktivno' : '' ?>">Račun</a>
            </li>
            <li class="nav-item">
                <a href="zahtjevi.php" class="nav-link nadlink <?php echo $trenutnaStranica2 == "Zahtjevi" ? 'aktivno' : '' ?>">
                    Zahtjevi za instruktora
                    <span class="badge bg-danger float-end">
                        <?php echo isset($brojZahtjeva) && $brojZahtjeva > 0 ? $brojZahtjeva : '0'; ?>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a href="prijaverecenzija.php" class="nav-link nadlink <?php echo $trenutnaStranica2 == "Recenzije" ? 'aktivno' : '' ?>">
                    Prijave recenzija
                    <span class="badge bg-danger float-end">
                        <?php echo isset($brojPrijavaRecenzija) && $brojPrijavaRecenzija > 0 ? $brojPrijavaRecenzija : '0'; ?>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a href="svikorisnici.php" class="nav-link nadlink <?php echo $trenutnaStranica2 == "Korisnici" ? 'aktivno' : '' ?>">Svi
                    korisnici</a>
            </li>
            <li class="nav-item">
                <a href="" class="nav-link nadlink <?php echo $trenutnaStranica2 == "skripte" ? 'aktivno' : '' ?>">Sve
                    skripte</a>
            </li>
            <li class="nav-item">
                <a href="" class="nav-link nadlink <?php echo $trenutnaStranica2 == "grupekartica" ? 'aktivno' : '' ?>">Sve
                    grupe kartica</a>
            </li>

        </ul>
    </div>
</div>

<!-- Vendor JS datoteke -->
<script src="../../assets/vendor/purecounter/purecounter_vanilla.js"></script>
<script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="../../assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
<script src="../../assets/vendor/swiper/swiper-bundle.min.js"></script>
<script src="../../assets/vendor/waypoints/noframework.waypoints.js"></script>
<script src="../../assets/vendor/php-email-form/validate.js"></script>


<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


<script>
    $(document).ready(function() {
        $('#padajuciIzbornik').on('show.bs.collapse', function() {
            $('.arrow-down').hide();
            $('.arrow-up').show();
        });

        $('#padajuciIzbornik').on('hide.bs.collapse', function() {
            $('.arrow-down').show();
            $('.arrow-up').hide();
        });

        $(document).ready(function() {
            $('#prikazi').click(function() {
                $('#padajuciIzbornik').slideToggle();
            });
        });
       

    });
</script>
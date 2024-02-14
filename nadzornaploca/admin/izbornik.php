<div class="col-sm-3">
    <div class="card p-3">

        <div class="row text-center">
            <h4 class="text">Nadzorna ploča</h4>
        </div>

        <hr class="m-2">

        <ul class="nav flex-column mb-auto">
            <li class="nav-item">
                <a href="../admin/" class="nav-link <?php echo $trenutnaStranica2 == "račun" ? 'aktivno' : '' ?>">Račun</a>
            </li>
            <li class="nav-item">
                <a href="zahtjevi.php" class="nav-link <?php echo $trenutnaStranica2 == "zahtjevi" ? 'aktivno' : '' ?>">
                    Zahtjevi za instruktora
                    <span class="badge bg-danger float-end">
                        <?php echo isset($brojZahtjeva) && $brojZahtjeva > 0 ? $brojZahtjeva : '0'; ?>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a href="prijaverecenzija.php" class="nav-link <?php echo $trenutnaStranica2 == "recenzije" ? 'aktivno' : '' ?>">
                    Prijave recenzija
                    <span class="badge bg-danger float-end">
                        <?php echo isset($brojPrijavaRecenzija) && $brojPrijavaRecenzija > 0 ? $brojPrijavaRecenzija : '0'; ?>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a href="svikorisnici.php" class="nav-link <?php echo $trenutnaStranica2 == "korisnici" ? 'aktivno' : '' ?>">Svi korisnici</a>
            </li>
            <li class="nav-item">
                <a href="" class="nav-link <?php echo $trenutnaStranica2 == "skripte" ? 'aktivno' : '' ?>">Sve skripte</a>
            </li>
            <li class="nav-item">
                <a href="" class="nav-link <?php echo $trenutnaStranica2 == "grupekartica" ? 'aktivno' : '' ?>">Sve grupe kartica</a>
            </li>
        </ul>
    </div>
</div>
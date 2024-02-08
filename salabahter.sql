-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 08, 2024 at 02:27 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `salabahter`
--

-- --------------------------------------------------------

--
-- Table structure for table `gradovi`
--

CREATE TABLE `gradovi` (
  `grad_id` int(11) NOT NULL,
  `naziv_grada` varchar(255) DEFAULT NULL,
  `zupanija_id` int(11) DEFAULT NULL,
  `postanski_broj` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gradovi`
--

INSERT INTO `gradovi` (`grad_id`, `naziv_grada`, `zupanija_id`, `postanski_broj`) VALUES
(258, 'Zagreb', 129, 10000),
(259, 'Bjelovar', 142, 43000),
(260, 'Daruvar', 142, 43500),
(261, 'Garešnica', 142, 43280),
(262, 'Čazma', 142, 43240),
(263, 'Grubišno Polje', 142, 43290),
(264, 'Slavonski Brod', 134, 35000),
(265, 'Nova Gradiška', 134, 35400),
(266, 'Dubrovnik', 141, 20000),
(267, 'Metković', 141, 20350),
(268, 'Ploče', 141, 20340),
(269, 'Korčula', 141, 20260),
(270, 'Opuzen', 141, 20355),
(271, 'Pula', 137, 52100),
(272, 'Poreč', 137, 52440),
(273, 'Rovinj', 137, 52210),
(274, 'Umag', 137, 52470),
(275, 'Labin', 137, 52220),
(276, 'Pazin', 137, 52000),
(277, 'Buzet', 137, 52420),
(278, 'Vodnjan', 137, 52215),
(279, 'Buje', 137, 52460),
(280, 'Novigrad', 137, 52466),
(281, 'Karlovac', 136, 47000),
(282, 'Ogulin', 136, 47300),
(283, 'Duga Resa', 136, 47250),
(284, 'Ozalj', 136, 47280),
(285, 'Slunj', 136, 47240),
(286, 'Koprivnica', 144, 48000),
(287, 'Križevci', 144, 48260),
(288, 'Đurđevac', 144, 48350),
(289, 'Krapina', 148, 49000),
(290, 'Zabok', 148, 49210),
(291, 'Pregrada', 148, 49218),
(292, 'Zlatar', 148, 49250),
(293, 'Oroslavje', 148, 49243),
(294, 'Donja Stubica', 148, 49240),
(295, 'Klanjec', 148, 49290),
(296, 'Gospić', 147, 53000),
(297, 'Otočac', 147, 53220),
(298, 'Senj', 147, 53270),
(299, 'Novalja', 147, 53291),
(300, 'Čakovec', 145, 40000),
(301, 'Prelog', 145, 40323),
(302, 'Mursko Središće', 145, 40315),
(303, 'Osijek', 132, 31000),
(304, 'Đakovo', 132, 31400),
(305, 'Našice', 132, 31500),
(306, 'Valpovo', 132, 31550),
(307, 'Belišće', 132, 31551),
(308, 'Beli Manastir', 132, 31300),
(309, 'Donji Miholjac', 132, 31540),
(310, 'Požega', 146, 34000),
(311, 'Pleternica', 146, 34310),
(312, 'Pakrac', 146, 34550),
(313, 'Kutjevo', 146, 34340),
(314, 'Lipik', 146, 34551),
(315, 'Rijeka', 131, 51000),
(316, 'Opatija', 131, 51410),
(317, 'Crikvenica', 131, 51260),
(318, 'Rab', 131, 51280),
(319, 'Kastav', 131, 51215),
(320, 'Mali Lošinj', 131, 51550),
(321, 'Bakar', 131, 51222),
(322, 'Delnice', 131, 51300),
(323, 'Vrbovsko', 131, 51326),
(324, 'Krk', 131, 51500),
(325, 'Novi Vinodolski', 131, 51250),
(326, 'Kraljevica', 131, 51262),
(327, 'Čabar', 131, 51306),
(328, 'Cres', 131, 51557),
(329, 'Šibenik', 139, 22000),
(330, 'Knin', 139, 22300),
(331, 'Vodice', 139, 22211),
(332, 'Drniš', 139, 22320),
(333, 'Skradin', 139, 22222),
(334, 'Sisak', 138, 44000),
(335, 'Kutina', 138, 44320),
(336, 'Petrinja', 138, 44250),
(337, 'Novska', 138, 44330),
(338, 'Glina', 138, 44400),
(339, 'Hrvatska Kostajnica', 138, 44430),
(340, 'Split', 130, 21000),
(341, 'Kaštela', 130, 21212),
(342, 'Sinj', 130, 21230),
(343, 'Solin', 130, 21210),
(344, 'Omiš', 130, 21310),
(345, 'Makarska', 130, 21300),
(346, 'Trogir', 130, 21220),
(347, 'Trilj', 130, 21240),
(348, 'Imotski', 130, 21260),
(349, 'Vrgorac', 130, 21276),
(350, 'Hvar', 130, 21450),
(351, 'Supetar', 130, 21400),
(352, 'Stari Grad', 130, 21460),
(353, 'Vrlika', 130, 21236),
(354, 'Vis', 130, 21480),
(355, 'Komiža', 130, 21485),
(356, 'Varaždin', 140, 42000),
(357, 'Ivanec', 140, 42240),
(358, 'Novi Marof', 140, 42220),
(359, 'Lepoglava', 140, 42250),
(360, 'Ludbreg', 140, 42230),
(361, 'Varaždinske Toplice', 140, 42223),
(362, 'Virovitica', 149, 33000),
(363, 'Slatina', 149, 33520),
(364, 'Orahovica', 149, 33515),
(365, 'Vinkovci', 143, 32100),
(366, 'Vukovar', 143, 32000),
(367, 'Županja', 143, 32270),
(368, 'Ilok', 143, 32236),
(369, 'Otok', 143, 32252),
(370, 'Zadar', 133, 23000),
(371, 'Benkovac', 133, 23420),
(372, 'Biograd na Moru', 133, 23210),
(373, 'Nin', 133, 23232),
(374, 'Pag', 133, 23250),
(375, 'Obrovac', 133, 23450),
(376, 'Velika Gorica', 135, 10410),
(377, 'Samobor', 135, 10430),
(378, 'Zaprešić', 135, 10290),
(379, 'Jastrebarsko', 135, 10450),
(380, 'Sveti Ivan Zelina', 135, 10380),
(381, 'Sveta Nedelja', 135, 10431),
(382, 'Ivanić Grad', 135, 10310),
(383, 'Vrbovec', 135, 10340),
(384, 'Dugo Selo', 135, 10370);

-- --------------------------------------------------------

--
-- Table structure for table `instruktori`
--

CREATE TABLE `instruktori` (
  `instruktor_id` int(11) NOT NULL,
  `korisnik_id` int(11) DEFAULT NULL,
  `opisInstruktora` varchar(255) DEFAULT NULL,
  `autentikacija` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instruktori`
--

INSERT INTO `instruktori` (`instruktor_id`, `korisnik_id`, `opisInstruktora`, `autentikacija`) VALUES
(39, 21, 'Učenik Tehničke Srednje Škole Čakovec', 'autentikacija/65c4bc5c6281f_SKRIPTAB.pdf'),
(40, 23, 'Državni prvak u hrvatskom jeziku', 'autentikacija/65c4bd1c44f1d_2.pdf'),
(41, 22, 'Završio sam PMF i radio kao profesor 5 godina na fakultetu', 'autentikacija/65c4bcc9b8733_Sigurnost Crypto Walleta.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `instruktorovipredmeti`
--

CREATE TABLE `instruktorovipredmeti` (
  `id` int(11) NOT NULL,
  `instruktor_id` int(11) NOT NULL,
  `predmet_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instruktorovipredmeti`
--

INSERT INTO `instruktorovipredmeti` (`id`, `instruktor_id`, `predmet_id`) VALUES
(44, 39, 8),
(45, 39, 13),
(46, 40, 11),
(47, 41, 13),
(48, 41, 11),
(49, 41, 12);

-- --------------------------------------------------------

--
-- Table structure for table `korisnik`
--

CREATE TABLE `korisnik` (
  `korisnik_id` int(11) NOT NULL,
  `ime` varchar(50) DEFAULT NULL,
  `prezime` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `lozinka` varchar(255) DEFAULT NULL,
  `adresa` varchar(255) DEFAULT NULL,
  `mjesto` int(5) DEFAULT NULL,
  `datum_rodenja` date DEFAULT NULL,
  `slika_korisnika` varchar(255) DEFAULT NULL,
  `status_korisnika` int(20) DEFAULT NULL,
  `isAdmin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `korisnik`
--

INSERT INTO `korisnik` (`korisnik_id`, `ime`, `prezime`, `email`, `lozinka`, `adresa`, `mjesto`, `datum_rodenja`, `slika_korisnika`, `status_korisnika`, `isAdmin`) VALUES
(13, 'Antonio', 'Ivanović', 'tonco@ivanovic.com', '$2y$10$v2Tb7NnSPAOAbVTqR3uIOepOOLzC9YfkKgNDZQro6peXJ3X3CCitS', 'Skolska 3', 258, '0000-00-00', NULL, 5, 1),
(17, 'Proba', 'Probica', 'noaturkk@gmail.com', '$2y$10$CF1obYNKNAXTNq.BRKkU5eqfDoOopMGscu/7K.y067VAcBSaExQM.', 'IStarska', 258, '0000-00-00', NULL, 5, 0),
(20, 'Bruno', 'Miklin', 'bruno@miklin.com', '$2y$10$13iQE7Q1iSLlPAw4dJTQ/el.fiEddyaMJpf8/WUYUXKhNFlyvKTNG', 'Gajeva', 276, '0000-00-00', NULL, 2, 0),
(21, 'Ivek', 'Magdalenic', 'ivek@ivek.com', '$2y$10$VwmqsCGpgWdTkK1/f3wvf.PAWtGXGCQIO63VrMJzZP2TJJ68tFNZ2', 'Gradska 6', 300, '0000-00-00', NULL, 1, 0),
(22, 'Noa', 'Turk', 'noa@noa', '$2y$10$ZIRi7qZj4XChFI0Ed/L5S.Ak/edDcHRF6icWMDFBjd6jvZsnfLCZC', 'Čakovec', 274, '0000-00-00', NULL, 3, 0),
(23, 'Floki', 'Gotal', 'floki@floki', '$2y$10$fLuNASWv5pPeB7ctKpkuOu0a.ViQxkP7QbsZQ2wbyETd8Cm0grfu2', 'Kralja Tomislava 15', 300, '0000-00-00', NULL, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `predmeti`
--

CREATE TABLE `predmeti` (
  `predmet_id` int(11) NOT NULL,
  `naziv_predmeta` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `predmeti`
--

INSERT INTO `predmeti` (`predmet_id`, `naziv_predmeta`) VALUES
(12, 'Biologija'),
(10, 'Fizika'),
(11, 'Hrvatski jezik'),
(13, 'Informatika'),
(9, 'Kemija'),
(8, 'Matematika');

-- --------------------------------------------------------

--
-- Table structure for table `predmetizahtjeva`
--

CREATE TABLE `predmetizahtjeva` (
  `predmetiZahtjeva_id` int(11) NOT NULL,
  `zahtjev_id` int(11) NOT NULL,
  `predmet_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recenzije`
--

CREATE TABLE `recenzije` (
  `recenzija_id` int(11) NOT NULL,
  `ocjena` int(1) NOT NULL,
  `komentar` varchar(255) NOT NULL,
  `korisnik_id` int(11) NOT NULL,
  `instruktor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `skripte`
--

CREATE TABLE `skripte` (
  `skripta_id` int(11) NOT NULL,
  `predmet_id` int(11) NOT NULL,
  `naziv_skripte` varchar(255) NOT NULL,
  `opis_skripte` varchar(255) NOT NULL,
  `broj_preuzimanja` int(11) NOT NULL,
  `datum_kreiranja` date NOT NULL,
  `skripta_putanja` varchar(255) NOT NULL,
  `korisnik_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skripte`
--

INSERT INTO `skripte` (`skripta_id`, `predmet_id`, `naziv_skripte`, `opis_skripte`, `broj_preuzimanja`, `datum_kreiranja`, `skripta_putanja`, `korisnik_id`) VALUES
(4, 12, 'Proba', 'Ovo je probu', 0, '0000-00-00', 'skripte/65c0d07cdfad8_1612134504_Stranac_radni_listi__.pdf', 17),
(5, 12, 'Proba', 'Ovo je probu', 0, '2024-02-05', 'skripte/65c0d0eb5101c_1612134504_Stranac_radni_listi__.pdf', 17),
(6, 9, 'sfdg', 'sfdfe', 0, '2024-02-05', 'skripte/65c0d0f9a0e5c_00_3RT_DBP_VJEZBE-SVE.pdf', 17),
(7, 10, 'd', 'd', 0, '2024-02-05', 'skripte/65c0d115bb4ba_camus_stranac (1).pdf', 17),
(8, 12, 'AAA', 'a', 0, '2024-02-05', 'skripte/65c0d289de0d5_459044511-Insight-Upper-intermediate-WB-pdf.pdf', 17),
(9, 10, 'xyz', 'Ovo je neki opis', 0, '2024-02-05', 'skripte/65c0e34f9b5bc_459044511-Insight-Upper-intermediate-WB-pdf.pdf', 17),
(10, 9, 'abc', 'ž', 0, '2024-02-05', 'skripte/65c0e3ef1bde2_00_3RT_DBP_VJEZBE-SVE.pdf', 17);

-- --------------------------------------------------------

--
-- Table structure for table `statuskorisnika`
--

CREATE TABLE `statuskorisnika` (
  `status_id` int(11) NOT NULL,
  `status_naziv` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `statuskorisnika`
--

INSERT INTO `statuskorisnika` (`status_id`, `status_naziv`) VALUES
(1, 'Učenik'),
(2, 'Student'),
(3, 'Profesor'),
(5, 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `zahtjevzainstruktora`
--

CREATE TABLE `zahtjevzainstruktora` (
  `zahtjev_id` int(11) NOT NULL,
  `korisnik_id` int(11) DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `motivacija` varchar(255) NOT NULL,
  `opisInstruktora` varchar(255) NOT NULL,
  `autentikacija` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zupanija`
--

CREATE TABLE `zupanija` (
  `zupanija_id` int(11) NOT NULL,
  `naziv_zupanije` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `zupanija`
--

INSERT INTO `zupanija` (`zupanija_id`, `naziv_zupanije`) VALUES
(129, 'Zagreb'),
(130, 'Splitsko-dalmatinska'),
(131, 'Primorsko-goranska'),
(132, 'Osječko-baranjska'),
(133, 'Zadarska'),
(134, 'Brodsko-posavska'),
(135, 'Zagrebačka'),
(136, 'Karlovačka'),
(137, 'Istarska'),
(138, 'Sisačko-moslavačka'),
(139, 'Šibensko-kninska'),
(140, 'Varaždinska'),
(141, 'Dubrovačko-neretvanska'),
(142, 'Bjelovarsko-bilogorska'),
(143, 'Vukovarsko-srijemska'),
(144, 'Koprivničko-križevačka'),
(145, 'Međimurska'),
(146, 'Požeško-slavonska'),
(147, 'Ličko-senjska'),
(148, 'Krapinsko-zagorska'),
(149, 'Virovitičko-podravska');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gradovi`
--
ALTER TABLE `gradovi`
  ADD PRIMARY KEY (`grad_id`),
  ADD KEY `zupanija_id` (`zupanija_id`);

--
-- Indexes for table `instruktori`
--
ALTER TABLE `instruktori`
  ADD PRIMARY KEY (`instruktor_id`),
  ADD UNIQUE KEY `korisnik_id_2` (`korisnik_id`),
  ADD KEY `korisnik_id` (`korisnik_id`);

--
-- Indexes for table `instruktorovipredmeti`
--
ALTER TABLE `instruktorovipredmeti`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instruktor_id` (`instruktor_id`),
  ADD KEY `predmet_id` (`predmet_id`);

--
-- Indexes for table `korisnik`
--
ALTER TABLE `korisnik`
  ADD PRIMARY KEY (`korisnik_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `ime` (`ime`),
  ADD KEY `prezime` (`prezime`),
  ADD KEY `lozinka` (`lozinka`),
  ADD KEY `adresa` (`adresa`),
  ADD KEY `mjesto` (`mjesto`),
  ADD KEY `datum_rodenja` (`datum_rodenja`),
  ADD KEY `status_korisnika` (`status_korisnika`);

--
-- Indexes for table `predmeti`
--
ALTER TABLE `predmeti`
  ADD PRIMARY KEY (`predmet_id`),
  ADD UNIQUE KEY `naziv_predmeta_2` (`naziv_predmeta`),
  ADD KEY `naziv_predmeta` (`naziv_predmeta`);

--
-- Indexes for table `predmetizahtjeva`
--
ALTER TABLE `predmetizahtjeva`
  ADD PRIMARY KEY (`predmetiZahtjeva_id`),
  ADD KEY `FK_zahtjev` (`zahtjev_id`),
  ADD KEY `FK_predmet` (`predmet_id`);

--
-- Indexes for table `recenzije`
--
ALTER TABLE `recenzije`
  ADD PRIMARY KEY (`recenzija_id`),
  ADD KEY `ocjena` (`ocjena`),
  ADD KEY `komentar` (`komentar`),
  ADD KEY `instruktor_id` (`instruktor_id`),
  ADD KEY `korisnik_id` (`korisnik_id`);

--
-- Indexes for table `skripte`
--
ALTER TABLE `skripte`
  ADD PRIMARY KEY (`skripta_id`),
  ADD KEY `korisnik_id` (`korisnik_id`),
  ADD KEY `predmet_id` (`predmet_id`);

--
-- Indexes for table `statuskorisnika`
--
ALTER TABLE `statuskorisnika`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `zahtjevzainstruktora`
--
ALTER TABLE `zahtjevzainstruktora`
  ADD PRIMARY KEY (`zahtjev_id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `korisnik_id` (`korisnik_id`);

--
-- Indexes for table `zupanija`
--
ALTER TABLE `zupanija`
  ADD PRIMARY KEY (`zupanija_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gradovi`
--
ALTER TABLE `gradovi`
  MODIFY `grad_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=385;

--
-- AUTO_INCREMENT for table `instruktori`
--
ALTER TABLE `instruktori`
  MODIFY `instruktor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `instruktorovipredmeti`
--
ALTER TABLE `instruktorovipredmeti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `korisnik`
--
ALTER TABLE `korisnik`
  MODIFY `korisnik_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `predmeti`
--
ALTER TABLE `predmeti`
  MODIFY `predmet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `predmetizahtjeva`
--
ALTER TABLE `predmetizahtjeva`
  MODIFY `predmetiZahtjeva_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `recenzije`
--
ALTER TABLE `recenzije`
  MODIFY `recenzija_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `skripte`
--
ALTER TABLE `skripte`
  MODIFY `skripta_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `zahtjevzainstruktora`
--
ALTER TABLE `zahtjevzainstruktora`
  MODIFY `zahtjev_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `zupanija`
--
ALTER TABLE `zupanija`
  MODIFY `zupanija_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=150;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `gradovi`
--
ALTER TABLE `gradovi`
  ADD CONSTRAINT `gradovi_ibfk_1` FOREIGN KEY (`zupanija_id`) REFERENCES `zupanija` (`zupanija_id`);

--
-- Constraints for table `instruktori`
--
ALTER TABLE `instruktori`
  ADD CONSTRAINT `instruktoriFK_korisnik` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnik` (`korisnik_id`);

--
-- Constraints for table `instruktorovipredmeti`
--
ALTER TABLE `instruktorovipredmeti`
  ADD CONSTRAINT `instruktorovipredmeti_ibfk_1` FOREIGN KEY (`instruktor_id`) REFERENCES `instruktori` (`instruktor_id`),
  ADD CONSTRAINT `instruktorovipredmeti_ibfk_2` FOREIGN KEY (`predmet_id`) REFERENCES `predmeti` (`predmet_id`);

--
-- Constraints for table `korisnik`
--
ALTER TABLE `korisnik`
  ADD CONSTRAINT `korisnik_ibfk_1` FOREIGN KEY (`status_korisnika`) REFERENCES `statuskorisnika` (`status_id`),
  ADD CONSTRAINT `korisnik_ibfk_2` FOREIGN KEY (`mjesto`) REFERENCES `gradovi` (`grad_id`);

--
-- Constraints for table `predmetizahtjeva`
--
ALTER TABLE `predmetizahtjeva`
  ADD CONSTRAINT `FK_predmet` FOREIGN KEY (`predmet_id`) REFERENCES `predmeti` (`predmet_id`),
  ADD CONSTRAINT `FK_zahtjev` FOREIGN KEY (`zahtjev_id`) REFERENCES `zahtjevzainstruktora` (`zahtjev_id`);

--
-- Constraints for table `recenzije`
--
ALTER TABLE `recenzije`
  ADD CONSTRAINT `recenzije_ibfk_1` FOREIGN KEY (`instruktor_id`) REFERENCES `instruktori` (`instruktor_id`),
  ADD CONSTRAINT `recenzije_ibfk_2` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnik` (`korisnik_id`);

--
-- Constraints for table `skripte`
--
ALTER TABLE `skripte`
  ADD CONSTRAINT `skripte_ibfk_1` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnik` (`korisnik_id`),
  ADD CONSTRAINT `skripte_ibfk_2` FOREIGN KEY (`predmet_id`) REFERENCES `predmeti` (`predmet_id`);

--
-- Constraints for table `zahtjevzainstruktora`
--
ALTER TABLE `zahtjevzainstruktora`
  ADD CONSTRAINT `FK_korisnik` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnik` (`korisnik_id`),
  ADD CONSTRAINT `FK_status` FOREIGN KEY (`status_id`) REFERENCES `statuskorisnika` (`status_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

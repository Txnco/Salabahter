-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 25, 2024 at 05:38 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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
-- Table structure for table `grupekartica`
--

CREATE TABLE `grupekartica` (
  `grupa_id` int(11) NOT NULL,
  `grupa_naziv` varchar(255) DEFAULT NULL,
  `grupa_opis` text DEFAULT NULL,
  `datum_kreiranja` timestamp NOT NULL DEFAULT current_timestamp(),
  `vlasnik_id` int(11) DEFAULT NULL,
  `predmet_id` int(11) DEFAULT NULL,
  `javno` tinyint(1) DEFAULT 0,
  `broj_pregleda` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grupekartica`
--

INSERT INTO `grupekartica` (`grupa_id`, `grupa_naziv`, `grupa_opis`, `datum_kreiranja`, `vlasnik_id`, `predmet_id`, `javno`, `broj_pregleda`) VALUES
(12, 'Zbrajanje', 'Ovo su kartice za ponavljanje zbrajanja ', '2024-02-24 16:23:55', 823, 8, 0, 61),
(15, 'Zabrajanje', 'Ponavljannje zbrajanja cijelih brojeva za niže razrede osnovne škole', '2024-02-25 10:36:11', 13, 8, 1, 112),
(18, 'Kartica', 'Test privatnosti', '2024-02-25 13:01:30', 13, 8, 0, 44),
(19, 'NOVA PRIVATNA', '2345', '2024-02-25 13:02:56', 823, 8, 0, 8),
(20, 'Oduzimanje', 'Ponavljanje matematike gradivo oduzimanje za niže razrede osnovne škole.', '2024-02-25 13:06:07', 17, 8, 1, 63),
(21, 'ff', 'ff', '2024-02-25 13:40:29', 13, 9, 0, 33),
(22, 'dgd', 'dgdgd', '2024-02-25 13:43:10', 13, 8, 0, 4),
(24, 'Oduzimanje cijelih brojeva', 'U ovoj grupi kartica možete ponoviti osnovnu radnju oduzimanja cijelih brojeva', '2024-02-25 14:59:28', 823, 8, 1, 27),
(25, 'Proba22', '22', '2024-02-25 15:01:22', 17, 8, 0, 6),
(26, 'a', 'a', '2024-02-25 15:01:50', 17, 8, 0, 4);

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
(52, 823, 'Profesor iz matematike studirao na FERu ', 'autentikacija/65da061803a90_raspored.pdf'),
(53, 828, 'Idem na natjecanja iz matematike, imam 5 iz matematike i fizike', 'autentikacija/65db5211a7297_Noa Turk-2023_2024.pdf'),
(54, 829, 'Učenik sam Tehničke Škole Čakovec, imam dovoljno temeljno znanje iz matematike i fizike', 'autentikacija/65db52c67d0e9_ocjeneIzFizikeiMatematike.pdf'),
(55, 826, 'Ovaj život je prolazan i želim svoje znanje da podijelim besplatno s drugima, onoliko koliko znam.', 'autentikacija/65db565d9cac7_Rjeavanje_problema.pdf');

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
(75, 52, 8),
(76, 52, 10),
(77, 52, 9),
(78, 53, 8),
(79, 53, 10),
(80, 54, 8),
(81, 54, 10),
(82, 55, 13);

-- --------------------------------------------------------

--
-- Table structure for table `kartice`
--

CREATE TABLE `kartice` (
  `kartica_id` int(11) NOT NULL,
  `pitanje` text DEFAULT NULL,
  `odgovor` text DEFAULT NULL,
  `datum_kreiranja` timestamp NOT NULL DEFAULT current_timestamp(),
  `grupa_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kartice`
--

INSERT INTO `kartice` (`kartica_id`, `pitanje`, `odgovor`, `datum_kreiranja`, `grupa_id`) VALUES
(16, '2+2', '4', '2024-02-24 16:24:17', 12),
(17, '3+5', '8', '2024-02-24 16:24:28', 12),
(18, '3+1', '4', '2024-02-24 16:24:44', 12),
(19, '5+2', '7', '2024-02-24 16:25:19', 12),
(21, '1+1=?', '2', '2024-02-25 12:16:46', 15),
(22, '4+4=?', '8', '2024-02-25 12:17:02', 15),
(23, '1+2=?', '3', '2024-02-25 12:17:12', 15),
(24, '2+7=?', '9', '2024-02-25 12:17:32', 15),
(27, 'gh', 'hh', '2024-02-25 13:04:39', 18),
(32, '3', '3', '2024-02-25 13:05:58', 15),
(34, '5-3=?', '2', '2024-02-25 13:06:12', 20),
(35, 'dd', 'ddd', '2024-02-25 13:24:02', 12),
(36, 'dfg', 'dfg', '2024-02-25 13:33:21', 18),
(37, 'ddf', 'dfd', '2024-02-25 13:41:57', 21),
(38, 'dgdg', 'dgdg', '2024-02-25 13:43:01', 21),
(39, 'sdgd', 'sdgsd', '2024-02-25 13:43:14', 22),
(40, 'sdgsd', 'sdgsdg', '2024-02-25 13:43:18', 22),
(44, '2-5=?', '-3', '2024-02-25 14:59:43', 24),
(45, '4-2=?', '2', '2024-02-25 14:59:51', 24),
(46, '1-1=?', '0', '2024-02-25 15:00:02', 24),
(47, '3-1=?', '2', '2024-02-25 15:00:28', 24),
(49, 'a', 'a', '2024-02-25 15:01:25', 25),
(50, 'aa', '444', '2024-02-25 15:01:54', 26),
(51, '5-2=?', '3', '2024-02-25 15:02:41', 20),
(52, '2-1=?', '1', '2024-02-25 15:02:49', 20),
(53, '40-30=?', '10', '2024-02-25 15:03:01', 20),
(54, '1', '1', '2024-02-25 15:06:43', 19),
(55, '3', '3', '2024-02-25 15:06:56', 19),
(56, 'bbbb', 'bbb', '2024-02-25 15:14:02', 25),
(57, 'ccc', 'ccc', '2024-02-25 15:14:05', 25);

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
  `zeton_lozinke` varchar(64) DEFAULT NULL,
  `zeton_istice` datetime DEFAULT NULL,
  `adresa` varchar(255) DEFAULT NULL,
  `prebivaliste` varchar(255) NOT NULL,
  `mjesto` int(5) DEFAULT NULL,
  `slika_korisnika` varchar(255) DEFAULT NULL,
  `status_korisnika` int(20) DEFAULT NULL,
  `isAdmin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `korisnik`
--

INSERT INTO `korisnik` (`korisnik_id`, `ime`, `prezime`, `email`, `lozinka`, `zeton_lozinke`, `zeton_istice`, `adresa`, `prebivaliste`, `mjesto`, `slika_korisnika`, `status_korisnika`, `isAdmin`) VALUES
(13, 'Antonio', 'Ivanović', 'tonco@ivanovic.com', '$2y$10$v2Tb7NnSPAOAbVTqR3uIOepOOLzC9YfkKgNDZQro6peXJ3X3CCitS', NULL, NULL, 'Skolska 2', 'Goričan', 258, 'profilneslike/65d37a99636d6_antonio.jpg', 3678, 1),
(17, 'Noa', 'Turk', 'noaturkk@gmail.com', '$2y$10$fEWnWIuvRXTWSaZ/HJQKUOwUY2bQLsntHDeGUEAgk3IwQoAzv8N1W', NULL, NULL, 'Istarska 12a', 'Čakovec', 300, NULL, 3678, 0),
(22, 'Noa', 'Turk', 'noa@noa', '$2y$10$ZIRi7qZj4XChFI0Ed/L5S.Ak/edDcHRF6icWMDFBjd6jvZsnfLCZC', NULL, NULL, 'Čakovec', 'Čakovec', 274, 'profilneslike/65d37aba01d87_Noa.jpg', 3678, 0),
(28, 'Florijan', 'Gotal', 'sunindark00@gmail.com', '$2y$10$STQBu1Cikfkd.q7EpybUeeljBnvZxnTZlMTyu0YFD2UKxiUisA/xG', NULL, NULL, 'Špičkovina', 'Bezobraznik', 300, NULL, 2, 0),
(36, 'Emily ', 'Ivanović', 'emilyivanovic456@gmail.com', '$2y$10$pnd6n.om2UxMu6AxYM/ww.TYlaX20/m.hl3pff1pHjBs9e7lXJsd2', NULL, NULL, 'Školska 2', 'Goričan', 301, NULL, 1, 0),
(820, 'Antonio', 'Ivanovic', 'toncooffical@gmail.com', '$2y$10$DkpBOzBn6Shc5KwyX30S2.g4gu1Eilc6zagrNvjpNQpv0pHJuCiU.', NULL, NULL, 'Skolska 2', 'Goričan', 300, NULL, 2, 0),
(821, 'Lucija', 'Banožić', 'lucy.banozic@gmail.com', '$2y$10$bY.kqGdAjIPuzurVEr.07O7BFEdi4oV0TcrKExtH1nAILBIxJvLke', NULL, NULL, 'Prvomajska 1', 'Mihovljan', 300, NULL, 2, 0),
(822, 'Ivano', 'Cajic', 'ivano.cajic@gmail.com', '$2y$10$hSkMScnisAw.xvSc8YstTuy.21ay1H3lGocUz/4wfFGeLRCAtN7F2', NULL, NULL, 'Ljudevita Gaja 9', 'Cakovec', 300, NULL, 2, 0),
(823, 'Tonco', 'Ivanović', 'toncoivanovic@gmail.com', '$2y$10$bwQfgn8iyump.RiVoVubQu9gbQs11BjbOHXbgmmkhNs6y0Pbaoupe', NULL, NULL, 'Školska', 'Goričan', 301, NULL, 2, 0),
(826, 'Valerija', 'Poljanec', 'valerija.poljanec@skole.hr', '$2y$10$.NVcpTdkvj7KfNrSohJlaO.qjbwedCm4SRZqwNEMR.FXg/fEYxYo2', NULL, NULL, 'Vukovarska 7', 'Čakovec', 300, NULL, 3, 0),
(827, 'Toncek', 'Salabahter', 'toncoivanovic6@gmail.com', '$2y$10$wzPu5ngeKc/afXDmagraH.NoBqJQDXqrY2JiyKH0pjMUBucplvT92', NULL, NULL, 'Ulica Kralja Kresimira 15', 'Goričan', 301, 'profilneslike/65da0b9a64ef7_more-service-3.jpg', 2, 0),
(828, 'Mateo', 'Šimić', '9a3tob@gmail.com', '$2y$10$woPkrsZ.TQZJhgE7qdIfeer5kNWhBhlz/F4J.RWg6fOQuF6IFVpp2', '4baf68d715e1c73cdd240d0777ba4d38ca79d11741d374217e26ec47cb99eb6d', '2024-02-24 23:57:28', 'Nepostojeća 19', 'Varaždin', 356, NULL, 1, 0),
(829, 'Antonio', 'Ivanović', 'salabahter.learning@gmail.com', '$2y$10$Y9oDdIwO3AsQI7G1C3x1zuFOIzh6yAYlMLp9rzXkSSp9GaQ6QvXnC', NULL, NULL, 'Školska 2', 'Goričan', 300, NULL, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `neverificiranikorisnici`
--

CREATE TABLE `neverificiranikorisnici` (
  `korisnik_id` int(11) NOT NULL,
  `ime` varchar(255) NOT NULL,
  `prezime` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `lozinka` varchar(255) NOT NULL,
  `adresa` varchar(255) NOT NULL,
  `prebivaliste` varchar(255) NOT NULL,
  `mjesto` int(11) NOT NULL,
  `status_korisnika` int(20) NOT NULL,
  `verifikacijski_kod` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `neverificiranikorisnici`
--

INSERT INTO `neverificiranikorisnici` (`korisnik_id`, `ime`, `prezime`, `email`, `lozinka`, `adresa`, `prebivaliste`, `mjesto`, `status_korisnika`, `verifikacijski_kod`) VALUES
(41, 'Dora', 'Horvat', '12dorahorvat@gmail.com', '$2y$10$FN6dH9ZhCYSQh1RtwoHtlO0SnIaEx39lEH.FF6WWcsjU3bVSOodCO', 'ulica Koče Racina 28', 'Čakovec', 300, 1, 821901),
(56, 'Noa', 'Turk', 'noaturk10@gmail.com', '$2y$10$IVFix4t8pRgbGZNgHbe8DOP7kGCf0PXKbWEKGzE9XKApYanTizdIa', 'Istarska 12a', 'Čakovec', 300, 1, 939000),
(57, 'Noa', 'Turk', 'noa20noa18@gmail.com', '$2y$10$MlsbfLyXCen5XrKm3pmzwecFZMipBhLv2nlqnf4td32L21e8uIpVu', 'Istarska 12a', 'Čakovec', 300, 1, 338430);

-- --------------------------------------------------------

--
-- Table structure for table `predmeti`
--

CREATE TABLE `predmeti` (
  `predmet_id` int(11) NOT NULL,
  `naziv_predmeta` varchar(255) DEFAULT NULL,
  `predmet_boja` varchar(255) NOT NULL,
  `slika_predmeta` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `predmeti`
--

INSERT INTO `predmeti` (`predmet_id`, `naziv_predmeta`, `predmet_boja`, `slika_predmeta`) VALUES
(8, 'Matematika', '#59bab7', 'matematika.jpg'),
(9, 'Kemija', '#10ac84', 'kemija.jpg'),
(10, 'Fizika', '#feca57', ''),
(11, 'Hrvatski jezik', '#0abde3', ''),
(12, 'Biologija', '#ff9ff3', 'biologija.jpg'),
(13, 'Informatika', '#01a3a4', ''),
(19, 'Povijest', '#ff29bb', '');

-- --------------------------------------------------------

--
-- Table structure for table `predmetizahtjeva`
--

CREATE TABLE `predmetizahtjeva` (
  `predmetiZahtjeva_id` int(11) NOT NULL,
  `zahtjev_id` int(11) NOT NULL,
  `predmet_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `predmetizahtjeva`
--

INSERT INTO `predmetizahtjeva` (`predmetiZahtjeva_id`, `zahtjev_id`, `predmet_id`) VALUES
(84, 69, 8),
(85, 69, 10);

-- --------------------------------------------------------

--
-- Table structure for table `prijavarecenzije`
--

CREATE TABLE `prijavarecenzije` (
  `prijava_id` int(11) NOT NULL,
  `prijavljenaRecenzija` int(11) NOT NULL,
  `prijavioKorisnik` int(11) NOT NULL,
  `opisPrijave` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prijavarecenzije`
--

INSERT INTO `prijavarecenzije` (`prijava_id`, `prijavljenaRecenzija`, `prijavioKorisnik`, `opisPrijave`) VALUES
(39, 32, 828, 'Primjer prijave recenzije');

-- --------------------------------------------------------

--
-- Table structure for table `prijavaskripte`
--

CREATE TABLE `prijavaskripte` (
  `prijava_id` int(11) NOT NULL,
  `skripta_id` int(11) DEFAULT NULL,
  `prijavioKorisnik` int(11) DEFAULT NULL,
  `opisPrijave` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prijavaskripte`
--

INSERT INTO `prijavaskripte` (`prijava_id`, `skripta_id`, `prijavioKorisnik`, `opisPrijave`) VALUES
(9, 38, 828, 'Primjer prijave skripte');

-- --------------------------------------------------------

--
-- Table structure for table `recenzije`
--

CREATE TABLE `recenzije` (
  `recenzija_id` int(11) NOT NULL,
  `ocjena` int(1) NOT NULL,
  `komentar` varchar(255) NOT NULL,
  `odKorisnika` int(11) NOT NULL,
  `zaKorisnika` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recenzije`
--

INSERT INTO `recenzije` (`recenzija_id`, `ocjena`, `komentar`, `odKorisnika`, `zaKorisnika`) VALUES
(32, 3, 'Odličan instruktor za klasičnu matematiku, ali ne zna dobro objasniti geometriju, ružno skicira...', 828, 823),
(33, 5, 'Odličan kao instruktor, sve objasni na životnim primjerima. Zahvljujući njemu dobio sam peticu.', 17, 823),
(34, 5, 'Odlične instrukcije, zadovoljan sam. Sve pohvale. Pomogao mi je da svladam gradivo iz matematike', 823, 829);

-- --------------------------------------------------------

--
-- Table structure for table `skripte`
--

CREATE TABLE `skripte` (
  `skripta_id` int(11) NOT NULL,
  `predmet_id` int(11) NOT NULL,
  `naziv_skripte` varchar(255) NOT NULL,
  `opis_skripte` longtext NOT NULL,
  `broj_pregleda` int(11) NOT NULL,
  `datum_kreiranja` date NOT NULL,
  `skripta_putanja` varchar(255) NOT NULL,
  `korisnik_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skripte`
--

INSERT INTO `skripte` (`skripta_id`, `predmet_id`, `naziv_skripte`, `opis_skripte`, `broj_pregleda`, `datum_kreiranja`, `skripta_putanja`, `korisnik_id`) VALUES
(37, 13, 'Micro:Bit skripta', 'Ovo je skripta koja sadrži osnovno o robotici te programiranju micro:bita. Logika if else grananja, varijable i slično', 22, '2024-02-25', 'skripte/65da7e694c2a6_MICROBIT.pdf', 828),
(38, 8, 'Formule za maturu iz Matematike A razina', 'Ovdje prilažem formule iz mature za Matematiku za A razinu', 7, '2024-02-25', 'skripte/65db54acb9f1b_MAT T A.pdf', 823),
(39, 11, 'Kasni modernizam i hrvatska književnost', 'Skripta za učenje, matura HRVATSKI', 2, '2024-02-25', 'skripte/65db556fef53c_350070685-Kasni-modernizam-i-hrvatska-knjizevnost-20-st-skripta.pdf', 828);

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
(4, 'Učitelj'),
(3678, 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `zahtjevzainstrukcije`
--

CREATE TABLE `zahtjevzainstrukcije` (
  `zahtjev_id` int(11) NOT NULL,
  `poslaoKorisnik` int(11) NOT NULL,
  `instruktor_id` int(11) NOT NULL,
  `predmetInstruktora_id` int(11) NOT NULL,
  `opisZahtjeva` varchar(255) NOT NULL,
  `predlozeniDatum` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `zahtjevzainstrukcije`
--

INSERT INTO `zahtjevzainstrukcije` (`zahtjev_id`, `poslaoKorisnik`, `instruktor_id`, `predmetInstruktora_id`, `opisZahtjeva`, `predlozeniDatum`) VALUES
(45, 13, 53, 8, 'Trebam pomoć za gradivo Geometrijski niz, ne razumijem opće članove. Radimo po udžebniku Matematika 4', '2024-02-26 16:00:00');

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

--
-- Dumping data for table `zahtjevzainstruktora`
--

INSERT INTO `zahtjevzainstruktora` (`zahtjev_id`, `korisnik_id`, `status_id`, `motivacija`, `opisInstruktora`, `autentikacija`) VALUES
(69, 17, 3678, 'Odličan sam učenik, želim dijeliti svoje znanje', 'Idem na natjecanja iz matematike, imam 5 iz matematike i fizike', 'autentikacija/65db58333bb25_Noa Turk-2023_2024.pdf');

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
-- Indexes for table `grupekartica`
--
ALTER TABLE `grupekartica`
  ADD PRIMARY KEY (`grupa_id`),
  ADD KEY `vlasnik_id` (`vlasnik_id`),
  ADD KEY `predmet_id` (`predmet_id`);

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
-- Indexes for table `kartice`
--
ALTER TABLE `kartice`
  ADD PRIMARY KEY (`kartica_id`),
  ADD KEY `grupa_id` (`grupa_id`);

--
-- Indexes for table `korisnik`
--
ALTER TABLE `korisnik`
  ADD PRIMARY KEY (`korisnik_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `zeton_lozinke` (`zeton_lozinke`),
  ADD KEY `ime` (`ime`),
  ADD KEY `prezime` (`prezime`),
  ADD KEY `lozinka` (`lozinka`),
  ADD KEY `adresa` (`adresa`),
  ADD KEY `mjesto` (`mjesto`),
  ADD KEY `status_korisnika` (`status_korisnika`);

--
-- Indexes for table `neverificiranikorisnici`
--
ALTER TABLE `neverificiranikorisnici`
  ADD PRIMARY KEY (`korisnik_id`),
  ADD UNIQUE KEY `verifikacijski_kod` (`verifikacijski_kod`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `FK_grad` (`mjesto`),
  ADD KEY `FK_statusKorisnika` (`status_korisnika`);

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
-- Indexes for table `prijavarecenzije`
--
ALTER TABLE `prijavarecenzije`
  ADD PRIMARY KEY (`prijava_id`),
  ADD UNIQUE KEY `prijavljenaRecenzija` (`prijavljenaRecenzija`),
  ADD KEY `FK_prijavioKorisnik` (`prijavioKorisnik`);

--
-- Indexes for table `prijavaskripte`
--
ALTER TABLE `prijavaskripte`
  ADD PRIMARY KEY (`prijava_id`),
  ADD KEY `skripta_id` (`skripta_id`),
  ADD KEY `prijavioKorisnik` (`prijavioKorisnik`);

--
-- Indexes for table `recenzije`
--
ALTER TABLE `recenzije`
  ADD PRIMARY KEY (`recenzija_id`),
  ADD KEY `ocjena` (`ocjena`),
  ADD KEY `komentar` (`komentar`),
  ADD KEY `instruktor_id` (`zaKorisnika`),
  ADD KEY `korisnik_id` (`odKorisnika`);

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
-- Indexes for table `zahtjevzainstrukcije`
--
ALTER TABLE `zahtjevzainstrukcije`
  ADD PRIMARY KEY (`zahtjev_id`),
  ADD KEY `instruktor_id` (`instruktor_id`),
  ADD KEY `poslaoKorisnik` (`poslaoKorisnik`),
  ADD KEY `predmetInstruktora_id` (`predmetInstruktora_id`);

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
-- AUTO_INCREMENT for table `grupekartica`
--
ALTER TABLE `grupekartica`
  MODIFY `grupa_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `instruktori`
--
ALTER TABLE `instruktori`
  MODIFY `instruktor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `instruktorovipredmeti`
--
ALTER TABLE `instruktorovipredmeti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `kartice`
--
ALTER TABLE `kartice`
  MODIFY `kartica_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `korisnik`
--
ALTER TABLE `korisnik`
  MODIFY `korisnik_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=830;

--
-- AUTO_INCREMENT for table `neverificiranikorisnici`
--
ALTER TABLE `neverificiranikorisnici`
  MODIFY `korisnik_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `predmeti`
--
ALTER TABLE `predmeti`
  MODIFY `predmet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `predmetizahtjeva`
--
ALTER TABLE `predmetizahtjeva`
  MODIFY `predmetiZahtjeva_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `prijavarecenzije`
--
ALTER TABLE `prijavarecenzije`
  MODIFY `prijava_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `prijavaskripte`
--
ALTER TABLE `prijavaskripte`
  MODIFY `prijava_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `recenzije`
--
ALTER TABLE `recenzije`
  MODIFY `recenzija_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `skripte`
--
ALTER TABLE `skripte`
  MODIFY `skripta_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `zahtjevzainstrukcije`
--
ALTER TABLE `zahtjevzainstrukcije`
  MODIFY `zahtjev_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `zahtjevzainstruktora`
--
ALTER TABLE `zahtjevzainstruktora`
  MODIFY `zahtjev_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

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
-- Constraints for table `grupekartica`
--
ALTER TABLE `grupekartica`
  ADD CONSTRAINT `grupekartica_ibfk_1` FOREIGN KEY (`vlasnik_id`) REFERENCES `korisnik` (`korisnik_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `grupekartica_ibfk_2` FOREIGN KEY (`predmet_id`) REFERENCES `predmeti` (`predmet_id`) ON DELETE CASCADE;

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
-- Constraints for table `kartice`
--
ALTER TABLE `kartice`
  ADD CONSTRAINT `kartice_ibfk_1` FOREIGN KEY (`grupa_id`) REFERENCES `grupekartica` (`grupa_id`) ON DELETE CASCADE;

--
-- Constraints for table `korisnik`
--
ALTER TABLE `korisnik`
  ADD CONSTRAINT `korisnik_ibfk_1` FOREIGN KEY (`status_korisnika`) REFERENCES `statuskorisnika` (`status_id`),
  ADD CONSTRAINT `korisnik_ibfk_2` FOREIGN KEY (`mjesto`) REFERENCES `gradovi` (`grad_id`);

--
-- Constraints for table `neverificiranikorisnici`
--
ALTER TABLE `neverificiranikorisnici`
  ADD CONSTRAINT `FK_grad` FOREIGN KEY (`mjesto`) REFERENCES `gradovi` (`grad_id`),
  ADD CONSTRAINT `FK_statusKorisnika` FOREIGN KEY (`status_korisnika`) REFERENCES `statuskorisnika` (`status_id`);

--
-- Constraints for table `predmetizahtjeva`
--
ALTER TABLE `predmetizahtjeva`
  ADD CONSTRAINT `FK_predmet` FOREIGN KEY (`predmet_id`) REFERENCES `predmeti` (`predmet_id`),
  ADD CONSTRAINT `FK_zahtjev` FOREIGN KEY (`zahtjev_id`) REFERENCES `zahtjevzainstruktora` (`zahtjev_id`);

--
-- Constraints for table `prijavarecenzije`
--
ALTER TABLE `prijavarecenzije`
  ADD CONSTRAINT `FK_prijavioKorisnik` FOREIGN KEY (`prijavioKorisnik`) REFERENCES `korisnik` (`korisnik_id`),
  ADD CONSTRAINT `FK_recenzije` FOREIGN KEY (`prijavljenaRecenzija`) REFERENCES `recenzije` (`recenzija_id`);

--
-- Constraints for table `prijavaskripte`
--
ALTER TABLE `prijavaskripte`
  ADD CONSTRAINT `prijavaskripte_ibfk_1` FOREIGN KEY (`skripta_id`) REFERENCES `skripte` (`skripta_id`),
  ADD CONSTRAINT `prijavaskripte_ibfk_2` FOREIGN KEY (`prijavioKorisnik`) REFERENCES `korisnik` (`korisnik_id`);

--
-- Constraints for table `recenzije`
--
ALTER TABLE `recenzije`
  ADD CONSTRAINT `recenzije_ibfk_1` FOREIGN KEY (`zaKorisnika`) REFERENCES `korisnik` (`korisnik_id`),
  ADD CONSTRAINT `recenzije_ibfk_2` FOREIGN KEY (`odKorisnika`) REFERENCES `korisnik` (`korisnik_id`);

--
-- Constraints for table `skripte`
--
ALTER TABLE `skripte`
  ADD CONSTRAINT `skripte_ibfk_1` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnik` (`korisnik_id`),
  ADD CONSTRAINT `skripte_ibfk_2` FOREIGN KEY (`predmet_id`) REFERENCES `predmeti` (`predmet_id`);

--
-- Constraints for table `zahtjevzainstrukcije`
--
ALTER TABLE `zahtjevzainstrukcije`
  ADD CONSTRAINT `FK_instruktor` FOREIGN KEY (`instruktor_id`) REFERENCES `instruktori` (`instruktor_id`),
  ADD CONSTRAINT `FK_poslaoKorisnik` FOREIGN KEY (`poslaoKorisnik`) REFERENCES `korisnik` (`korisnik_id`),
  ADD CONSTRAINT `FK_predmetZahtjev` FOREIGN KEY (`predmetInstruktora_id`) REFERENCES `instruktorovipredmeti` (`predmet_id`);

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

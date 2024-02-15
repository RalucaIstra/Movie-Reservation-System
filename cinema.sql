-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 18, 2024 at 10:44 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cinema`
--

-- --------------------------------------------------------

--
-- Table structure for table `categorievarsta`
--

CREATE TABLE `categorievarsta` (
  `VarstaID` int(11) NOT NULL,
  `Varsta` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Adult'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `categorievarsta`
--

INSERT INTO `categorievarsta` (`VarstaID`, `Varsta`) VALUES
(1, 'Child (0-3'),
(2, 'Student'),
(3, 'Adult'),
(4, 'Senior');

-- --------------------------------------------------------

--
-- Table structure for table `difuzarefilm`
--

CREATE TABLE `difuzarefilm` (
  `DifuzareID` int(11) NOT NULL,
  `TipFilmID` int(11) NOT NULL,
  `FilmID` int(11) NOT NULL,
  `Data` date NOT NULL,
  `Ora` time(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `difuzarefilm`
--

INSERT INTO `difuzarefilm` (`DifuzareID`, `TipFilmID`, `FilmID`, `Data`, `Ora`) VALUES
(4, 2, 5, '2024-01-03', '00:00:10.200000'),
(5, 2, 6, '2024-01-11', '16:40:00.000000'),
(8, 1, 16, '2024-02-02', '22:11:00.000000'),
(9, 1, 17, '2024-12-03', '12:12:00.000000');

-- --------------------------------------------------------

--
-- Table structure for table `film`
--

CREATE TABLE `film` (
  `FilmID` int(11) NOT NULL,
  `Denumire` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `Durata` time NOT NULL,
  `NumarLocuri` int(200) NOT NULL,
  `Categorie` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `film`
--

INSERT INTO `film` (`FilmID`, `Denumire`, `Durata`, `NumarLocuri`, `Categorie`) VALUES
(5, 'Singur Acasa 1', '00:00:20', 45, 'Familie'),
(6, 'Wonka', '01:20:12', 15, 'Musical'),
(8, 'Alba ca Zapada', '00:00:00', 12, 'Animatie'),
(16, 's', '00:00:05', 2, 'sv'),
(17, 'wer', '00:01:33', 133, 'fgf');

-- --------------------------------------------------------

--
-- Table structure for table `rezervare`
--

CREATE TABLE `rezervare` (
  `RezervareID` int(11) NOT NULL,
  `UtilizatorID` int(11) NOT NULL,
  `DifuzareID` int(11) NOT NULL,
  `Data` date NOT NULL,
  `NumarRand` int(3) NOT NULL,
  `NumarScaun` int(3) NOT NULL,
  `Pret` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `rezervare`
--

INSERT INTO `rezervare` (`RezervareID`, `UtilizatorID`, `DifuzareID`, `Data`, `NumarRand`, `NumarScaun`, `Pret`) VALUES
(1, 4, 4, '2024-01-16', 1, 1, 0),
(3, 9, 4, '2024-01-16', 5, 1, 20.5),
(4, 10, 4, '2024-01-16', 6, 1, 20.5),
(5, 9, 4, '2024-01-16', 5, 1, 20.5),
(6, 9, 8, '2024-01-16', 1, 1, 20.5),
(7, 4, 8, '2024-01-16', 2, 1, 20.5),
(8, 10, 9, '2024-01-16', 3, 9, 20.5),
(9, 8, 5, '2024-01-16', 3, 9, 20.5),
(10, 10, 4, '2024-01-16', 8, 7, 20.5);

-- --------------------------------------------------------

--
-- Table structure for table `tipfilm`
--

CREATE TABLE `tipfilm` (
  `TipFilmID` int(11) NOT NULL,
  `Tip` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '2D'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tipfilm`
--

INSERT INTO `tipfilm` (`TipFilmID`, `Tip`) VALUES
(1, '3D'),
(2, '2D'),
(3, '4DX'),
(4, 'VIP');

-- --------------------------------------------------------

--
-- Table structure for table `utilizator`
--

CREATE TABLE `utilizator` (
  `UtilizatorID` int(11) NOT NULL,
  `VarstaID` int(11) NOT NULL,
  `Nume` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `Prenume` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `Parola` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `Telefon` int(30) NOT NULL,
  `Email` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `utilizator`
--

INSERT INTO `utilizator` (`UtilizatorID`, `VarstaID`, `Nume`, `Prenume`, `Parola`, `Telefon`, `Email`) VALUES
(4, 2, 'Raluca', 'Istrati', 'g', 751057258, 'istratiraluca3@gmail.com'),
(5, 3, 'Maria', 'Ion', '$2y$10$j9BlcjmC5pMX0', 745331073, 'mariaion'),
(6, 2, 'mihnea', 'lascu', '$2y$10$rGY/wZbCIjY3G', 737299, 'lascumihnea'),
(7, 4, 'gjks', 'aa', '$2y$10$Dv8BBiRHZOv9K', 98, 'xdd'),
(8, 2, 'sdg', 'dvs', '$2y$10$mRRYD0L/WLdYc', 3948, 'fdfd@gmail.com'),
(9, 2, 'Ioana', 'Istrati', '$2y$10$VO0OdqBNHAkKl', 742539612, 'istratiioanaema@gmail.com'),
(10, 4, 'mm', 's', '$2y$10$pqVE2GrNVFNOg', 876, 'i@gmail.com'),
(11, 2, 'fbd', 'ssf', '$2y$10$n.T.42/WvWdOJ', 44, '25@'),
(12, 4, 'ss', 'aa', '$2y$10$AXPAfylam8cRv', 29828, 'skwj@styik.com');

-- --------------------------------------------------------

--
-- Table structure for table `utilizatorfilm`
--

CREATE TABLE `utilizatorfilm` (
  `UtilizatorID` int(11) NOT NULL,
  `FilmID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categorievarsta`
--
ALTER TABLE `categorievarsta`
  ADD PRIMARY KEY (`VarstaID`);

--
-- Indexes for table `difuzarefilm`
--
ALTER TABLE `difuzarefilm`
  ADD PRIMARY KEY (`DifuzareID`),
  ADD KEY `difuzare_film` (`FilmID`),
  ADD KEY `difuzare_tip film` (`TipFilmID`);

--
-- Indexes for table `film`
--
ALTER TABLE `film`
  ADD PRIMARY KEY (`FilmID`);

--
-- Indexes for table `rezervare`
--
ALTER TABLE `rezervare`
  ADD PRIMARY KEY (`RezervareID`),
  ADD KEY `rezervare_difuzare` (`DifuzareID`),
  ADD KEY `rezervare_utilizator` (`UtilizatorID`);

--
-- Indexes for table `tipfilm`
--
ALTER TABLE `tipfilm`
  ADD PRIMARY KEY (`TipFilmID`);

--
-- Indexes for table `utilizator`
--
ALTER TABLE `utilizator`
  ADD PRIMARY KEY (`UtilizatorID`),
  ADD KEY `varsta_utilizator` (`VarstaID`);

--
-- Indexes for table `utilizatorfilm`
--
ALTER TABLE `utilizatorfilm`
  ADD PRIMARY KEY (`UtilizatorID`,`FilmID`),
  ADD KEY `utilizatorfilm_film` (`FilmID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categorievarsta`
--
ALTER TABLE `categorievarsta`
  MODIFY `VarstaID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `difuzarefilm`
--
ALTER TABLE `difuzarefilm`
  MODIFY `DifuzareID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `film`
--
ALTER TABLE `film`
  MODIFY `FilmID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `rezervare`
--
ALTER TABLE `rezervare`
  MODIFY `RezervareID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tipfilm`
--
ALTER TABLE `tipfilm`
  MODIFY `TipFilmID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `utilizator`
--
ALTER TABLE `utilizator`
  MODIFY `UtilizatorID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `difuzarefilm`
--
ALTER TABLE `difuzarefilm`
  ADD CONSTRAINT `difuzare_film` FOREIGN KEY (`FilmID`) REFERENCES `film` (`FilmID`),
  ADD CONSTRAINT `difuzare_tip film` FOREIGN KEY (`TipFilmID`) REFERENCES `tipfilm` (`TipFilmID`);

--
-- Constraints for table `rezervare`
--
ALTER TABLE `rezervare`
  ADD CONSTRAINT `rezervare_difuzare` FOREIGN KEY (`DifuzareID`) REFERENCES `difuzarefilm` (`DifuzareID`),
  ADD CONSTRAINT `rezervare_utilizator` FOREIGN KEY (`UtilizatorID`) REFERENCES `utilizator` (`UtilizatorID`);

--
-- Constraints for table `utilizator`
--
ALTER TABLE `utilizator`
  ADD CONSTRAINT `varsta_utilizator` FOREIGN KEY (`VarstaID`) REFERENCES `categorievarsta` (`VarstaID`);

--
-- Constraints for table `utilizatorfilm`
--
ALTER TABLE `utilizatorfilm`
  ADD CONSTRAINT `utilizatorfilm_film` FOREIGN KEY (`FilmID`) REFERENCES `film` (`FilmID`),
  ADD CONSTRAINT `utilizatorfilm_utilizator` FOREIGN KEY (`UtilizatorID`) REFERENCES `utilizator` (`UtilizatorID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 01, 2025 at 01:16 PM
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
-- Database: `users`
--

-- --------------------------------------------------------

--
-- Table structure for table `diplome`
--

CREATE TABLE `diplome` (
  `id` int(11) NOT NULL,
  `Lib` varchar(20) DEFAULT NULL,
  `niveau` varchar(20) DEFAULT NULL,
  `etablissement` varchar(20) DEFAULT NULL,
  `anne` date DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `PPR` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hist_affectation`
--

CREATE TABLE `hist_affectation` (
  `id` int(11) NOT NULL,
  `Code` int(11) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date DEFAULT NULL,
  `PPR` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `us`
--

CREATE TABLE `us` (
  `Code` int(11) NOT NULL,
  `lib` varchar(25) NOT NULL,
  `cellule_mere` varchar(25) NOT NULL,
  `type` varchar(25) NOT NULL,
  `Batiment` varchar(25) NOT NULL,
  `PPR` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `PPR` int(25) NOT NULL,
  `Cin` int(25) NOT NULL,
  `prenom` varchar(25) NOT NULL,
  `nom` varchar(25) NOT NULL,
  `d_naiss` date NOT NULL,
  `d_recrutement` date NOT NULL,
  `sit_familliale` varchar(25) NOT NULL,
  `genre` varchar(25) NOT NULL,
  `role` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `diplome`
--
ALTER TABLE `diplome`
  ADD PRIMARY KEY (`id`),
  ADD KEY `PPR` (`PPR`);

--
-- Indexes for table `hist_affectation`
--
ALTER TABLE `hist_affectation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `PPR` (`PPR`);

--
-- Indexes for table `us`
--
ALTER TABLE `us`
  ADD PRIMARY KEY (`Code`),
  ADD KEY `PPR` (`PPR`);

--
-- Indexes for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`PPR`),
  ADD UNIQUE KEY `Cin` (`Cin`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `diplome`
--
ALTER TABLE `diplome`
  ADD CONSTRAINT `diplome_ibfk_1` FOREIGN KEY (`PPR`) REFERENCES `utilisateurs` (`ppr`);

--
-- Constraints for table `hist_affectation`
--
ALTER TABLE `hist_affectation`
  ADD CONSTRAINT `hist_affectation_ibfk_1` FOREIGN KEY (`PPR`) REFERENCES `utilisateurs` (`ppr`);

--
-- Constraints for table `us`
--
ALTER TABLE `us`
  ADD CONSTRAINT `us_ibfk_1` FOREIGN KEY (`PPR`) REFERENCES `utilisateurs` (`ppr`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

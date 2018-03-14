-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mar. 13 mars 2018 à 01:14
-- Version du serveur :  5.7.19
-- Version de PHP :  5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `suaps`
--

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
CREATE TABLE IF NOT EXISTS `reservations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_res` varchar(11) NOT NULL,
  `user` varchar(11) NOT NULL,
  `pos` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `date_reg` date NOT NULL,
  `date_last` date NOT NULL,
  `tickets_sem` int(4) NOT NULL,
  `tickets_we` int(4) NOT NULL,
  `parcours` int(4) NOT NULL,
  `reservations` int(4) NOT NULL,
  `annulations` int(4) NOT NULL,
  `invitations` int(4) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `fname`, `lname`, `password`, `email`, `date_reg`, `date_last`, `tickets_sem`, `tickets_we`, `parcours`, `reservations`, `annulations`, `invitations`, `admin`) VALUES
(6, 'kraive', 'Cyril', 'CyHu', '$2y$10$GSD/1ZDPl8lgR3Wk.Jpa/e8MFZa5Zz3nkkJUhDO6NAwcibCNG7vQi', 'e@f.g', '2018-03-12', '2018-03-12', 0, 0, 0, 0, 0, 0, 0),
(2, 'dreamcatcher', 'AnaÃ¯s', 'AnAl', '$2y$10$RaC6dmtdYre6gG97eYpgcuxLTDOtnQiYNKZW3GcSijobhk2SWjSR6', 'a@b.c', '2018-03-12', '2018-03-12', 0, 0, 0, 0, 0, 0, 0),
(3, 'tabouret', 'Laurent', 'LaSc', '$2y$10$LenPYP8lzQp7oWBI9d2cNepKwCA5ICEMlIiXCd3dCZo4lze0WbkTG', 'b@c.d', '2018-03-12', '2018-03-12', 0, 0, 0, 0, 0, 0, 1),
(4, 'orion', 'Julien', 'JuHe', '$2y$10$CzptcPFa.TJaF/q0wUWtM.S8k8Eu5fQCAvi2qcWUwfA/uyLX/01ES', 'c@d.e', '2018-03-12', '2018-03-13', 0, 0, 0, 0, 0, 0, 1),
(5, 'atserolf', 'David', 'DaDo', '$2y$10$loNUlOf4odluBPOc2SoWdeNl.FkSjef9bKk7HS.Fd8Ir/Bww8ZlKe', 'd@e.f', '2018-03-12', '2018-03-12', 0, 0, 0, 0, 0, 0, 0),
(7, 'jean', 'jean2', 'jean3', '$2y$10$GrfM50Yc6rq0Jm.jLpCH5OA.rWs37N5PaYC0cuIzgThkTkNVJPFpi', 'a@b.cc', '2018-03-13', '2018-03-13', 0, 0, 0, 0, 0, 0, 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Lun 28 Mars 2011 à 21:37
-- Version du serveur: 5.5.8
-- Version de PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `ogre`
--
CREATE DATABASE `ogre` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `ogre`;

-- --------------------------------------------------------

--
-- Structure de la table `compensation_semestre`
--

CREATE TABLE IF NOT EXISTS `compensation_semestre` (
  `id_semestre1` smallint(5) unsigned NOT NULL,
  `id_semestre2` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id_semestre1`,`id_semestre2`),
  KEY `fk_semestre_has_semestre_semestre1` (`id_semestre1`),
  KEY `fk_semestre_has_semestre_semestre2` (`id_semestre2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `compensation_semestre`
--


-- --------------------------------------------------------

--
-- Structure de la table `epreuve`
--

CREATE TABLE IF NOT EXISTS `epreuve` (
  `id_epreuve` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `id_ue` mediumint(8) unsigned NOT NULL,
  `coeff` tinyint(4) NOT NULL,
  `type_epreuve` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_epreuve`),
  KEY `fk_epreuve_ue1` (`id_ue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Contenu de la table `epreuve`
--


-- --------------------------------------------------------

--
-- Structure de la table `etudiants`
--

CREATE TABLE IF NOT EXISTS `etudiants` (
  `num_etudiant` mediumint(8) unsigned NOT NULL,
  `nom` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `prenom` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `nom_usuel` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sexe` enum('F','M') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`num_etudiant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `etudiants`
--


-- --------------------------------------------------------

--
-- Structure de la table `etudiants_semestre`
--

CREATE TABLE IF NOT EXISTS `etudiants_semestre` (
  `num_etudiant` mediumint(8) unsigned NOT NULL,
  `id_semestre` smallint(5) unsigned NOT NULL,
  `statut` char(3) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`num_etudiant`,`id_semestre`),
  KEY `fk_etudiants_has_semestre_etudiants1` (`num_etudiant`),
  KEY `fk_etudiants_has_semestre_semestre1` (`id_semestre`),
  KEY `fk_etudiants_semestre_statut_semestre1` (`statut`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `etudiants_semestre`
--


-- --------------------------------------------------------

--
-- Structure de la table `formation`
--

CREATE TABLE IF NOT EXISTS `formation` (
  `id_formation` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `code_formation` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `annee` char(9) COLLATE utf8_unicode_ci DEFAULT NULL,
  `libelle` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_formation`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Contenu de la table `formation`
--

INSERT INTO `formation` (`id_formation`, `code_formation`, `annee`, `libelle`) VALUES
(1, 'G1MAT', '2010-2011', 'Licence 1 Maths'),
(2, 'G1MAT', '2011-2012', 'Licence 1 Maths'),
(3, 'G1INF', '2010-2011', 'Licence 1 Informatique');

-- --------------------------------------------------------

--
-- Structure de la table `jlx_user`
--

CREATE TABLE IF NOT EXISTS `jlx_user` (
  `usr_login` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `usr_password` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `usr_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`usr_login`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `jlx_user`
--

INSERT INTO `jlx_user` (`usr_login`, `usr_password`, `usr_email`) VALUES
('user1', '3a5710ebc9e37a2cf339b8c584ccbe6bc0c2da06', ''),
('user2', 'e0707d7850a71a1bdcb58deb7469dd0eecde62dd', ''),
('user3', '0c85c05216680225ef7f08959d03e31b78dad5a6', '');

-- --------------------------------------------------------

--
-- Structure de la table `note`
--

CREATE TABLE IF NOT EXISTS `note` (
  `id_note` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_epreuve` mediumint(8) unsigned NOT NULL,
  `id_semestre` smallint(5) unsigned NOT NULL,
  `num_etudiant` mediumint(8) unsigned NOT NULL,
  `valeur` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id_note`),
  KEY `fk_note_epreuve1` (`id_epreuve`),
  KEY `fk_note_semestre1` (`id_semestre`),
  KEY `fk_note_etudiants1` (`num_etudiant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Contenu de la table `note`
--


-- --------------------------------------------------------

--
-- Structure de la table `semestre`
--

CREATE TABLE IF NOT EXISTS `semestre` (
  `id_semestre` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_formation` smallint(5) unsigned NOT NULL,
  `num_semestre` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_semestre`),
  KEY `fk_semestre_formation1` (`id_formation`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Contenu de la table `semestre`
--

INSERT INTO `semestre` (`id_semestre`, `id_formation`, `num_semestre`) VALUES
(1, 1, '1'),
(2, 1, '2'),
(3, 2, '1'),
(4, 2, '2'),
(5, 3, '1'),
(6, 3, '2');

-- --------------------------------------------------------

--
-- Structure de la table `semestre_ue`
--

CREATE TABLE IF NOT EXISTS `semestre_ue` (
  `id_ue` mediumint(8) unsigned NOT NULL,
  `id_semestre` smallint(5) unsigned NOT NULL,
  `optionelle` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_ue`,`id_semestre`),
  KEY `fk_formation_has_ue_ue1` (`id_ue`),
  KEY `fk_formation_has_ue_semestre1` (`id_semestre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `semestre_ue`
--

INSERT INTO `semestre_ue` (`id_ue`, `id_semestre`, `optionelle`) VALUES
(1, 1, 0),
(2, 1, 0),
(3, 2, 0),
(4, 1, 0),
(4, 2, 0),
(5, 1, 0),
(5, 2, 0);

-- --------------------------------------------------------

--
-- Structure de la table `statut_semestre`
--

CREATE TABLE IF NOT EXISTS `statut_semestre` (
  `statut` char(3) COLLATE utf8_unicode_ci NOT NULL,
  `libelle` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`statut`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `statut_semestre`
--

INSERT INTO `statut_semestre` (`statut`, `libelle`) VALUES
('NOK', 'Non Valide'),
('OK', 'Valide');

-- --------------------------------------------------------

--
-- Structure de la table `ue`
--

CREATE TABLE IF NOT EXISTS `ue` (
  `id_ue` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `formule` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `code_ue` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `coeff` tinyint(4) NOT NULL,
  `credits` tinyint(4) NOT NULL,
  `libelle` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `last_version` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_ue`),
  KEY `INDEX_code` (`code_ue`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Contenu de la table `ue`
--

INSERT INTO `ue` (`id_ue`, `formule`, `code_ue`, `coeff`, `credits`, `libelle`, `last_version`) VALUES
(1, NULL, 'G2MA1', 2, 2, 'Analyse et Algèbre 2', 1),
(2, NULL, 'G2MA3', 3, 3, 'Géometrie et Algèbre linéaire', 1),
(3, NULL, 'G2MA5', 2, 2, 'Algorithmique numérique', 1),
(4, NULL, 'ANG', 1, 1, 'Anglais', 1),
(5, NULL, 'TEC', 1, 1, 'Technique Expression Communica', 1);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `compensation_semestre`
--
ALTER TABLE `compensation_semestre`
  ADD CONSTRAINT `fk_semestre_has_semestre_semestre1` FOREIGN KEY (`id_semestre1`) REFERENCES `semestre` (`id_semestre`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_semestre_has_semestre_semestre2` FOREIGN KEY (`id_semestre2`) REFERENCES `semestre` (`id_semestre`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `epreuve`
--
ALTER TABLE `epreuve`
  ADD CONSTRAINT `fk_epreuve_ue1` FOREIGN KEY (`id_ue`) REFERENCES `ue` (`id_ue`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `etudiants_semestre`
--
ALTER TABLE `etudiants_semestre`
  ADD CONSTRAINT `fk_etudiants_has_semestre_etudiants1` FOREIGN KEY (`num_etudiant`) REFERENCES `etudiants` (`num_etudiant`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_etudiants_has_semestre_semestre1` FOREIGN KEY (`id_semestre`) REFERENCES `semestre` (`id_semestre`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_etudiants_semestre_statut_semestre1` FOREIGN KEY (`statut`) REFERENCES `statut_semestre` (`statut`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `note`
--
ALTER TABLE `note`
  ADD CONSTRAINT `fk_note_epreuve1` FOREIGN KEY (`id_epreuve`) REFERENCES `epreuve` (`id_epreuve`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_note_semestre1` FOREIGN KEY (`id_semestre`) REFERENCES `semestre` (`id_semestre`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_note_etudiants1` FOREIGN KEY (`num_etudiant`) REFERENCES `etudiants` (`num_etudiant`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `semestre`
--
ALTER TABLE `semestre`
  ADD CONSTRAINT `fk_semestre_formation1` FOREIGN KEY (`id_formation`) REFERENCES `formation` (`id_formation`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `semestre_ue`
--
ALTER TABLE `semestre_ue`
  ADD CONSTRAINT `fk_formation_has_ue_ue1` FOREIGN KEY (`id_ue`) REFERENCES `ue` (`id_ue`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_formation_has_ue_semestre1` FOREIGN KEY (`id_semestre`) REFERENCES `semestre` (`id_semestre`) ON DELETE NO ACTION ON UPDATE NO ACTION;

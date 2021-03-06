SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `ogre` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ;
USE `ogre`;

-- -----------------------------------------------------
-- Table `ogre`.`etudiants`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ogre`.`etudiants` (
  `num_etudiant` MEDIUMINT UNSIGNED NOT NULL ,
  `nom` VARCHAR(50) NOT NULL ,
  `prenom` VARCHAR(50) NULL ,
  `date_naissance` DATE NULL ,
  `nom_usuel` VARCHAR(50) NULL ,
  `sexe` ENUM('F', 'M', '?') NOT NULL ,
  `adresse` VARCHAR(100) NULL ,
  `code_postal` CHAR(5) NULL ,
  `ville` VARCHAR(50) NULL ,
  `email` VARCHAR(45) NULL ,
  `telephone` VARCHAR(20) NULL ,
  PRIMARY KEY (`num_etudiant`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ogre`.`formation`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ogre`.`formation` (
  `id_formation` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `code_formation` VARCHAR(15) NULL ,
  `annee` CHAR(9) NULL ,
  `libelle` VARCHAR(30) NULL ,
  PRIMARY KEY (`id_formation`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ogre`.`ue`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ogre`.`ue` (
  `id_ue` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `code_ue` VARCHAR(15) NOT NULL ,
  `coeff` TINYINT NOT NULL ,
  `credits` TINYINT NOT NULL ,
  `libelle` VARCHAR(50) NULL ,
  `annee` SMALLINT UNSIGNED NOT NULL ,
  `formule` VARCHAR(60) NULL ,
  `formule2` VARCHAR(60) NULL ,
  `formule_salarie` VARCHAR(60) NULL ,
  `formule_endette` VARCHAR(60) NULL ,
  PRIMARY KEY (`id_ue`) ,
  INDEX `INDEX_code` (`code_ue` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ogre`.`semestre`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ogre`.`semestre` (
  `id_semestre` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_formation` SMALLINT UNSIGNED NOT NULL ,
  `num_semestre` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id_semestre`) ,
  INDEX `fk_semestre_formation1` (`id_formation` ASC) ,
  CONSTRAINT `fk_semestre_formation1`
    FOREIGN KEY (`id_formation` )
    REFERENCES `ogre`.`formation` (`id_formation` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ogre`.`semestre_ue`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ogre`.`semestre_ue` (
  `id_ue` MEDIUMINT UNSIGNED NOT NULL ,
  `id_semestre` SMALLINT UNSIGNED NOT NULL ,
  `optionelle` TINYINT(1) NOT NULL ,
  PRIMARY KEY (`id_ue`, `id_semestre`) ,
  INDEX `fk_formation_has_ue_ue1` (`id_ue` ASC) ,
  INDEX `fk_formation_has_ue_semestre1` (`id_semestre` ASC) ,
  CONSTRAINT `fk_formation_has_ue_ue1`
    FOREIGN KEY (`id_ue` )
    REFERENCES `ogre`.`ue` (`id_ue` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_formation_has_ue_semestre1`
    FOREIGN KEY (`id_semestre` )
    REFERENCES `ogre`.`semestre` (`id_semestre` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ogre`.`epreuve`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ogre`.`epreuve` (
  `id_epreuve` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_ue` MEDIUMINT UNSIGNED NOT NULL ,
  `coeff` TINYINT NOT NULL ,
  `type_epreuve` VARCHAR(5) NOT NULL ,
  `rattrapage` TINYINT(1) NOT NULL ,
  PRIMARY KEY (`id_epreuve`) ,
  INDEX `fk_epreuve_ue1` (`id_ue` ASC) ,
  CONSTRAINT `fk_epreuve_ue1`
    FOREIGN KEY (`id_ue` )
    REFERENCES `ogre`.`ue` (`id_ue` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ogre`.`note`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ogre`.`note` (
  `id_epreuve` MEDIUMINT UNSIGNED NOT NULL ,
  `num_etudiant` MEDIUMINT UNSIGNED NOT NULL ,
  `id_semestre` SMALLINT UNSIGNED NOT NULL ,
  `valeur` DECIMAL(4,2) NOT NULL ,
  `statut` TINYINT NULL ,
  INDEX `fk_note_epreuve1` (`id_epreuve` ASC) ,
  INDEX `fk_note_semestre1` (`id_semestre` ASC) ,
  INDEX `fk_note_etudiants1` (`num_etudiant` ASC) ,
  PRIMARY KEY (`id_epreuve`, `id_semestre`, `num_etudiant`) ,
  CONSTRAINT `fk_note_epreuve1`
    FOREIGN KEY (`id_epreuve` )
    REFERENCES `ogre`.`epreuve` (`id_epreuve` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_note_semestre1`
    FOREIGN KEY (`id_semestre` )
    REFERENCES `ogre`.`semestre` (`id_semestre` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_note_etudiants1`
    FOREIGN KEY (`num_etudiant` )
    REFERENCES `ogre`.`etudiants` (`num_etudiant` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ogre`.`statut_semestre`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ogre`.`statut_semestre` (
  `statut` CHAR(3) NOT NULL ,
  `libelle` VARCHAR(30) NULL ,
  PRIMARY KEY (`statut`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ogre`.`etudiants_semestre`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ogre`.`etudiants_semestre` (
  `num_etudiant` MEDIUMINT UNSIGNED NOT NULL ,
  `id_semestre` SMALLINT UNSIGNED NOT NULL ,
  `statut` CHAR(3) NOT NULL ,
  `options` VARCHAR(40) NULL ,
  `salarie` TINYINT(1) NULL ,
  PRIMARY KEY (`num_etudiant`, `id_semestre`) ,
  INDEX `fk_etudiants_has_semestre_etudiants1` (`num_etudiant` ASC) ,
  INDEX `fk_etudiants_has_semestre_semestre1` (`id_semestre` ASC) ,
  INDEX `fk_etudiants_semestre_statut_semestre1` (`statut` ASC) ,
  CONSTRAINT `fk_etudiants_has_semestre_etudiants1`
    FOREIGN KEY (`num_etudiant` )
    REFERENCES `ogre`.`etudiants` (`num_etudiant` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_etudiants_has_semestre_semestre1`
    FOREIGN KEY (`id_semestre` )
    REFERENCES `ogre`.`semestre` (`id_semestre` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_etudiants_semestre_statut_semestre1`
    FOREIGN KEY (`statut` )
    REFERENCES `ogre`.`statut_semestre` (`statut` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ogre`.`compensation_semestre`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ogre`.`compensation_semestre` (
  `id_semestre1` SMALLINT UNSIGNED NOT NULL ,
  `id_semestre2` SMALLINT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_semestre1`, `id_semestre2`) ,
  INDEX `fk_semestre_has_semestre_semestre1` (`id_semestre1` ASC) ,
  INDEX `fk_semestre_has_semestre_semestre2` (`id_semestre2` ASC) ,
  CONSTRAINT `fk_semestre_has_semestre_semestre1`
    FOREIGN KEY (`id_semestre1` )
    REFERENCES `ogre`.`semestre` (`id_semestre` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_semestre_has_semestre_semestre2`
    FOREIGN KEY (`id_semestre2` )
    REFERENCES `ogre`.`semestre` (`id_semestre` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ogre`.`jlx_user`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ogre`.`jlx_user` (
  `usr_login` VARCHAR(50) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT '' ,
  `usr_password` VARCHAR(50) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT '' ,
  `usr_email` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT '' ,
  PRIMARY KEY (`usr_login`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `ogre`.`dispense_perso`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ogre`.`dispense_perso` (
  `id_epreuve` MEDIUMINT UNSIGNED NOT NULL ,
  `num_etudiant` MEDIUMINT UNSIGNED NOT NULL ,
  `id_semestre` SMALLINT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_epreuve`, `num_etudiant`, `id_semestre`) ,
  INDEX `fk_dispense_part_epreuve1` (`id_epreuve` ASC) ,
  INDEX `fk_dispense_part_etudiants1` (`num_etudiant` ASC) ,
  INDEX `fk_dispense_part_semestre1` (`id_semestre` ASC) ,
  CONSTRAINT `fk_dispense_part_epreuve1`
    FOREIGN KEY (`id_epreuve` )
    REFERENCES `ogre`.`epreuve` (`id_epreuve` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_dispense_part_etudiants1`
    FOREIGN KEY (`num_etudiant` )
    REFERENCES `ogre`.`etudiants` (`num_etudiant` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_dispense_part_semestre1`
    FOREIGN KEY (`id_semestre` )
    REFERENCES `ogre`.`semestre` (`id_semestre` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ogre`.`jacl2_group`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ogre`.`jacl2_group` (
  `id_aclgrp` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(150) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT '' ,
  `code` VARCHAR(30) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL ,
  `grouptype` TINYINT(4) NOT NULL DEFAULT '0' ,
  `ownerlogin` VARCHAR(50) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL ,
  PRIMARY KEY (`id_aclgrp`) )
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `ogre`.`jacl2_rights`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ogre`.`jacl2_rights` (
  `id_aclsbj` VARCHAR(100) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT '' ,
  `id_aclgrp` INT(11) NOT NULL DEFAULT '0' ,
  `id_aclres` VARCHAR(100) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT '' ,
  PRIMARY KEY (`id_aclsbj`, `id_aclgrp`, `id_aclres`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `ogre`.`jacl2_subject`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ogre`.`jacl2_subject` (
  `id_aclsbj` VARCHAR(100) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT '' ,
  `label_key` VARCHAR(100) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL ,
  PRIMARY KEY (`id_aclsbj`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `ogre`.`jacl2_user_group`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ogre`.`jacl2_user_group` (
  `login` VARCHAR(50) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT '' ,
  `id_aclgrp` INT(11) NOT NULL DEFAULT '0' ,
  INDEX `login` (`login` ASC, `id_aclgrp` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `ogre`.`dispense`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ogre`.`dispense` (
  `id_ue` MEDIUMINT UNSIGNED NOT NULL ,
  `num_etudiant` MEDIUMINT UNSIGNED NOT NULL ,
  `id_semestre` SMALLINT UNSIGNED NOT NULL ,
  `valide` TINYINT(1) NULL ,
  `salarie` TINYINT(1) NULL ,
  `endette` TINYINT(1) NULL ,
  `commentaire` VARCHAR(100) NULL ,
  PRIMARY KEY (`id_semestre`, `num_etudiant`, `id_ue`) ,
  INDEX `fk_dispense_semestre1` (`id_semestre` ASC) ,
  INDEX `fk_dispense_etudiants1` (`num_etudiant` ASC) ,
  INDEX `fk_dispense_ue1` (`id_ue` ASC) ,
  CONSTRAINT `fk_dispense_semestre1`
    FOREIGN KEY (`id_semestre` )
    REFERENCES `ogre`.`semestre` (`id_semestre` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_dispense_etudiants1`
    FOREIGN KEY (`num_etudiant` )
    REFERENCES `ogre`.`etudiants` (`num_etudiant` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_dispense_ue1`
    FOREIGN KEY (`id_ue` )
    REFERENCES `ogre`.`ue` (`id_ue` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ogre`.`jedisettings`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ogre`.`jedisettings` (
  `key` VARCHAR(30) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `value` VARCHAR(250) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `label` VARCHAR(100) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `last_update` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  PRIMARY KEY (`key`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

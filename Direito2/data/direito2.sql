SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET latin1 ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`tb_pais`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`tb_pais` (
  `cd_pais` INT NOT NULL AUTO_INCREMENT ,
  `sg_pais` VARCHAR(10) NOT NULL ,
  `nm_pais` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`cd_pais`) ,
  INDEX `tb_pais_sg_pais` (`sg_pais` ASC) ,
  INDEX `tb_pais_nm_pais` (`nm_pais` ASC) )
ENGINE = InnoDB
COMMENT = 'Paises' ;


-- -----------------------------------------------------
-- Table `mydb`.`tb_estado`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`tb_estado` (
  `cd_estado` INT NOT NULL AUTO_INCREMENT ,
  `cd_pais` INT NOT NULL ,
  `sg_estado` VARCHAR(45) NOT NULL ,
  `nm_estado` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`cd_estado`) ,
  INDEX `tb_estado_sg_estado` (`sg_estado` ASC) ,
  INDEX `tb_estado_nm_estado` (`nm_estado` ASC) ,
  INDEX `tb_estado_cd_pais` (`cd_pais` ASC) ,
  CONSTRAINT `tb_estado_cd_pais`
    FOREIGN KEY (`cd_pais` )
    REFERENCES `mydb`.`tb_pais` (`cd_pais` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Estados' ;


-- -----------------------------------------------------
-- Table `mydb`.`tb_cidade`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`tb_cidade` (
  `cd_cidade` INT NOT NULL AUTO_INCREMENT ,
  `cd_estado` INT NULL ,
  `nm_cidade` VARCHAR(100) NULL ,
  PRIMARY KEY (`cd_cidade`) ,
  INDEX `tb_cidade_nm_cidade` (`nm_cidade` ASC) ,
  INDEX `tb_cidade_cd_estado` (`cd_estado` ASC) ,
  CONSTRAINT `tb_cidade_cd_estado`
    FOREIGN KEY (`cd_estado` )
    REFERENCES `mydb`.`tb_estado` (`cd_estado` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Cidades' ;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

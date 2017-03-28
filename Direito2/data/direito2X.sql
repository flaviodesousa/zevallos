SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';


-- -----------------------------------------------------
-- Table `tb_fonte`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_fonte` ;

CREATE  TABLE IF NOT EXISTS `tb_fonte` (
  `cd_fonte` INT NOT NULL AUTO_INCREMENT ,
  `nm_fonte` VARCHAR(100) NOT NULL COMMENT 'Nome da fonte' ,
  `sg_fonte` VARCHAR(10) NOT NULL COMMENT 'Sigla da fonte' ,
  `ds_url` VARCHAR(100) NOT NULL COMMENT 'URL do site da fonte' ,
  `ds_icone` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Icone ou logo da fonte' ,
  `dt_inclusao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data e hora da inclusão' ,
  `fl_ativo` TINYINT NULL DEFAULT 1 COMMENT 'Se a fonte está ativa' ,
  `ds_merlim` TEXT NULL DEFAULT NULL COMMENT 'JSON dos parâmetros para o Merlim' ,
  `fl_frequencia` TINYINT NULL DEFAULT 0 COMMENT '0 - Padrão do Merlim\n1 - 15 minutos\n2 - 30 minutos\n3 - 60 minutos\n4 - 120 minutos' ,
  PRIMARY KEY (`cd_fonte`) ,
  INDEX `tb_fonte_sg_fonte` (`sg_fonte` ASC) ,
  INDEX `tb_fonte_fl_frequencia` (`fl_frequencia` ASC) )
ENGINE = InnoDB
COMMENT = 'Tabela das fontes de noticia' ;


-- -----------------------------------------------------
-- Table `tb_noticia`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_noticia` ;

CREATE  TABLE IF NOT EXISTS `tb_noticia` (
  `cd_noticia` INT NOT NULL AUTO_INCREMENT COMMENT 'PK Noticia' ,
  `cd_fonte` INT NULL DEFAULT NULL COMMENT 'FK da Fonte da noticia' ,
  `nm_noticia` VARCHAR(500) NOT NULL COMMENT 'Título da notícia' ,
  `ds_resumo` TEXT NULL DEFAULT NULL COMMENT 'Resumo para listagens' ,
  `ds_noticia` TEXT NULL DEFAULT NULL COMMENT 'Conteúdo da notícia' ,
  `dt_inclusao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data e hora da inclusão' ,
  `dt_referencia` DATETIME NULL DEFAULT NULL COMMENT 'Data e hora de referência da publicação real da notícia, caso não seja a data de inclusão' ,
  `fl_ativo` TINYINT NULL DEFAULT 1 COMMENT 'Se a notícia está ativa' ,
  `dt_inclusao_year` SMALLINT NULL DEFAULT NULL COMMENT 'Ano da Inclusão - Para fins de indexação' ,
  `dt_inclusao_month` TINYINT NULL DEFAULT NULL COMMENT 'Mes da Inclusão - Para fins de indexação' ,
  `dt_inclusao_day` TINYINT NULL DEFAULT NULL COMMENT 'Dia da Inclusão - Para fins de indexação' ,
  `dt_referencia_year` SMALLINT NULL DEFAULT NULL COMMENT 'Ano da data de referêcia- Para fins de indexação' ,
  `dt_referencia_month` TINYINT NULL DEFAULT NULL COMMENT 'Mes da data de referêcia- Para fins de indexação' ,
  `dt_referencia_day` TINYINT NULL DEFAULT NULL COMMENT 'Dia da data de referêcia- Para fins de indexação' ,
  `qt_visualizacao` INT NULL DEFAULT 0 COMMENT 'Quantidade total de vezes que foi visualizada' ,
  `dt_visualizacao` DATETIME NULL DEFAULT NULL COMMENT 'Data e hora da última visualização' ,
  PRIMARY KEY (`cd_noticia`) ,
  INDEX `tb_noticia_dt_inclusao` (`dt_inclusao` ASC) ,
  INDEX `tb_noticia_dt_referencia` (`dt_referencia` ASC) ,
  INDEX `tb_noticia_cd_fonte` (`cd_fonte` ASC) ,
  INDEX `tb_noticia_dt_inclusao_Y` (`dt_inclusao_year` ASC) ,
  INDEX `tb_noticia_dt_inclusao_YM` (`dt_inclusao_year` ASC, `dt_inclusao_month` ASC) ,
  INDEX `tb_noticia_dt_inclusao_YMD` (`dt_inclusao_year` ASC, `dt_inclusao_month` ASC, `dt_inclusao_day` ASC) ,
  INDEX `tb_noticia_dt_referencia_Y` (`dt_referencia_year` ASC) ,
  INDEX `tb_noticia_dt_referencia_YM` (`dt_referencia_year` ASC, `dt_referencia_month` ASC) ,
  INDEX `tb_noticia_dt_referencia_YMD` (`dt_referencia_year` ASC, `dt_referencia_month` ASC, `dt_referencia_day` ASC) ,
  INDEX `tb_noticia_qt_visualizacao` (`qt_visualizacao` ASC) ,
  CONSTRAINT `tb_noticia_cd_fonte`
    FOREIGN KEY (`cd_fonte` )
    REFERENCES `tb_fonte` (`cd_fonte` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Noticias ' ;


-- -----------------------------------------------------
-- Table `tb_noticia_hash`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_noticia_hash` ;

CREATE  TABLE IF NOT EXISTS `tb_noticia_hash` (
  `cd_noticia_hash` INT NOT NULL AUTO_INCREMENT COMMENT 'PK do Hash da notícia' ,
  `cd_noticia` INT NOT NULL COMMENT 'FK da notícia a que o Hash se refere' ,
  `hs_noticia` VARCHAR(32) NOT NULL COMMENT 'MD5 hash para a URL da noticia' ,
  `ds_url` VARCHAR(255) NULL DEFAULT NULL COMMENT 'URL a que o Hash se refere' ,
  `dt_inclusao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data e hora da inclusão' ,
  `fl_ativo` TINYINT NULL DEFAULT 1 COMMENT 'Se o Hash poderá ser utilizado' ,
  PRIMARY KEY (`cd_noticia_hash`) ,
  INDEX `tb_noticia_hash_hs_noticia` (`hs_noticia` ASC) ,
  INDEX `tb_noticia_hash_ds_url` (`ds_url` ASC) ,
  INDEX `tb_noticia_hash_cd_noticia` (`cd_noticia` ASC) ,
  CONSTRAINT `tb_noticia_hash_cd_noticia`
    FOREIGN KEY (`cd_noticia` )
    REFERENCES `tb_noticia` (`cd_noticia` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Tabela de URLs e Hash das noticias. Uma noticia poderá ter ' ;


-- -----------------------------------------------------
-- Table `tb_fornecedor`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_fornecedor` ;

CREATE  TABLE IF NOT EXISTS `tb_fornecedor` (
  `cd_fornecedor` INT NOT NULL AUTO_INCREMENT COMMENT 'PK do fornecedor de conteúdo' ,
  `hs_fornecedor` CHAR(32) NOT NULL COMMENT 'Hash MD5 de identificacao do fornecedor' ,
  `nm_fornecedor` VARCHAR(100) NOT NULL COMMENT 'Nome completo do Fornecedor' ,
  `sg_fornecedor` VARCHAR(20) NOT NULL COMMENT 'Sigla do fornecedor' ,
  `dt_inclusao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data e hora de inclusão no sistema' ,
  `fl_ativo` TINYINT NULL DEFAULT 1 COMMENT 'Se o fornecedor está ativo' ,
  PRIMARY KEY (`cd_fornecedor`) ,
  INDEX `tb_fornecedor_hs_fornecedor` (`hs_fornecedor` ASC) )
ENGINE = InnoDB, 
COMMENT = 'Identificacao do fornecedor de conteúdo' ;


-- -----------------------------------------------------
-- Table `tb_sessao`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_sessao` ;

CREATE  TABLE IF NOT EXISTS `tb_sessao` (
  `cd_sessao` INT NOT NULL AUTO_INCREMENT COMMENT 'PK da sessão do envio de noticias' ,
  `ds_sessao` VARCHAR(45) NULL DEFAULT NULL COMMENT 'Descrição da sessão usando JSON como Browser entre outras coisas' ,
  `dt_inclusao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data e hora do início da sessão' ,
  `cd_fornecedor` INT NULL DEFAULT NULL COMMENT 'Id do fornecedor, caso tenha sido ele que abriu a sessão' ,
  `ds_ip4` VARCHAR(15) NULL DEFAULT NULL COMMENT 'IP da sessao' ,
  `nu_ip4_1` TINYINT NULL DEFAULT NULL ,
  `nu_ip4_2` TINYINT NULL DEFAULT NULL ,
  `nu_ip4_3` TINYINT NULL DEFAULT NULL ,
  `nu_ip4_4` TINYINT NULL DEFAULT NULL ,
  PRIMARY KEY (`cd_sessao`) ,
  INDEX `tb_sessao_cd_fornecedor` (`cd_fornecedor` ASC) ,
  INDEX `tb_sessao_dt_inclusao` (`dt_inclusao` ASC) ,
  INDEX `tb_sessao_ip` (`nu_ip4_1` ASC, `nu_ip4_2` ASC, `nu_ip4_3` ASC, `nu_ip4_4` ASC) ,
  CONSTRAINT `tb_sessao_cd_fornecedor`
    FOREIGN KEY (`cd_fornecedor` )
    REFERENCES `tb_fornecedor` (`cd_fornecedor` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Sessão com o envio das noticias' ;


-- -----------------------------------------------------
-- Table `tb_noticia_update`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_noticia_update` ;

CREATE  TABLE IF NOT EXISTS `tb_noticia_update` (
  `cd_noticia_update` INT NOT NULL AUTO_INCREMENT COMMENT 'PK do Update' ,
  `cd_noticia` INT NULL DEFAULT NULL COMMENT 'FK da notícia a que se refere' ,
  `cd_sessao` INT NULL DEFAULT NULL COMMENT 'Sessão que foi usada' ,
  `dt_inclusao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data e hora do update' ,
  PRIMARY KEY (`cd_noticia_update`) ,
  INDEX `tb_noticia_update_cd_noticia` (`cd_noticia` ASC) ,
  INDEX `tb_noticia_update_cd_sessao` (`cd_sessao` ASC) ,
  CONSTRAINT `tb_noticia_update_cd_noticia`
    FOREIGN KEY (`cd_noticia` )
    REFERENCES `tb_noticia` (`cd_noticia` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `tb_noticia_update_cd_sessao`
    FOREIGN KEY (`cd_sessao` )
    REFERENCES `tb_sessao` (`cd_sessao` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Relação dos Updates que as noticias tiveram.' ;


-- -----------------------------------------------------
-- Table `tb_noticia_fullsearch`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_noticia_fullsearch` ;

CREATE  TABLE IF NOT EXISTS `tb_noticia_fullsearch` (
  `cd_noticia` INT NOT NULL COMMENT 'PK da noticia' ,
  `ds_noticia` TEXT NOT NULL COMMENT 'Conteúdo que será buscado' ,
  FULLTEXT INDEX `tb_noticia_fullsearch_ds_noticia` (`ds_noticia` ASC) ,
  INDEX `tb_noticia_fullsearch_cd_noticia` (`cd_noticia` ASC) ,
  CONSTRAINT `tb_noticia_fullsearch_cd_noticia`
    FOREIGN KEY (`cd_noticia` )
    REFERENCES `tb_noticia` (`cd_noticia` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM, 
COMMENT = 'Tabela para o full search das tabelas' ;


-- -----------------------------------------------------
-- Table `tb_noticia_mssql`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_noticia_mssql` ;

CREATE  TABLE IF NOT EXISTS `tb_noticia_mssql` (
  `cd_noticia` INT NOT NULL COMMENT 'PK da notícia' ,
  `cd_mssql` INT NULL DEFAULT NULL COMMENT 'Id no MSSQL' ,
  INDEX `tb_noticia_mssql_cd_mssql` (`cd_mssql` ASC) ,
  INDEX `tb_noticia_mssql_cd_noticia` (`cd_noticia` ASC) ,
  CONSTRAINT `tb_noticia_mssql_cd_noticia`
    FOREIGN KEY (`cd_noticia` )
    REFERENCES `tb_noticia` (`cd_noticia` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Referência com a tabela antiga do Direito 2 no MSSQL' ;


-- -----------------------------------------------------
-- Table `tb_usuario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_usuario` ;

CREATE  TABLE IF NOT EXISTS `tb_usuario` (
  `cd_usuario` INT NOT NULL AUTO_INCREMENT ,
  `hs_usuario` VARCHAR(32) NOT NULL COMMENT 'Hash MD5 de identificacao do usuário' ,
  `nm_usuario` VARCHAR(100) NOT NULL COMMENT 'Nome do usuário' ,
  `ds_email` VARCHAR(100) NOT NULL COMMENT 'E-mail do usuário' ,
  `ds_senha` VARCHAR(20) NULL DEFAULT NULL COMMENT 'Senha caso ele digite' ,
  `dt_inclusao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ,
  `dt_ultimo_acesso` DATETIME NULL DEFAULT NULL ,
  `fl_ativo` TINYINT NULL DEFAULT 1 ,
  PRIMARY KEY (`cd_usuario`) ,
  INDEX `tb_usuario_hs_usuario` (`hs_usuario` ASC) ,
  INDEX `tb_usuario_ds_email` (`ds_email` ASC) )
ENGINE = InnoDB, 
COMMENT = 'Tabela de usuários do sistema' ;


-- -----------------------------------------------------
-- Table `tb_comentario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_comentario` ;

CREATE  TABLE IF NOT EXISTS `tb_comentario` (
  `cd_comentario` INT NOT NULL AUTO_INCREMENT COMMENT 'PK dos comentários' ,
  `cd_comentario_pai` INT NULL DEFAULT NULL COMMENT 'Comentário a quem este se refere' ,
  `cd_noticia` INT NOT NULL COMMENT 'FK da noticia que foi comentada' ,
  `cd_sessao` INT NULL DEFAULT NULL COMMENT 'FK da sessão que gerou o comentário' ,
  `cd_usuario` INT NULL DEFAULT NULL COMMENT 'Usuário que está incluindo o comentário. Ele será criado automaticamente, com senha gerada e enviada par ao usuário.' ,
  `ds_url` VARCHAR(100) NULL DEFAULT NULL COMMENT 'URL que o usuário se refere' ,
  `ds_cidade` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Cidade do usuário' ,
  `ds_estado` VARCHAR(2) NULL DEFAULT NULL COMMENT 'Estado do usuário' ,
  `ds_atividade` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Atividade que ele desenvolve' ,
  `ds_comentario` TEXT NULL DEFAULT NULL COMMENT 'O comentário' ,
  `fl_notificar` TINYINT NULL DEFAULT NULL COMMENT 'Se ele receberá notícias de atualização dos comentários' ,
  `dt_validado` DATETIME NULL DEFAULT NULL COMMENT 'Data em que o comentário foi validado' ,
  `dt_inclusao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data e hora da inclusão' ,
  `ds_ip4` VARCHAR(15) NULL DEFAULT NULL COMMENT 'IP do envio do comentario, apesar que já está na sessão' ,
  `fl_ativo` TINYINT NULL DEFAULT 1 COMMENT 'Se o comentário está ativo' ,
  PRIMARY KEY (`cd_comentario`) ,
  INDEX `tb_comentario_dt_inclusao` (`dt_inclusao` ASC) ,
  INDEX `tb_comentario_cd_usuario` (`cd_usuario` ASC) ,
  INDEX `tb_comentario_cd_noticia` (`cd_noticia` ASC) ,
  INDEX `tb_comentario_cd_sessao` (`cd_sessao` ASC) ,
  CONSTRAINT `tb_comentario_cd_usuario`
    FOREIGN KEY (`cd_usuario` )
    REFERENCES `tb_usuario` (`cd_usuario` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `tb_comentario_cd_noticia`
    FOREIGN KEY (`cd_noticia` )
    REFERENCES `tb_noticia` (`cd_noticia` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `tb_comentario_cd_sessao`
    FOREIGN KEY (`cd_sessao` )
    REFERENCES `tb_sessao` (`cd_sessao` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Tabela de comentários' ;


-- -----------------------------------------------------
-- Table `tb_comentario_palavras`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_comentario_palavras` ;

CREATE  TABLE IF NOT EXISTS `tb_comentario_palavras` (
  `cd_comentario_palavras` INT NOT NULL COMMENT 'PK das palavras proibidas' ,
  `nm_comentario_palavras` VARCHAR(50) NULL DEFAULT NULL COMMENT 'Palavra' ,
  `fl_frase` TINYINT NULL DEFAULT NULL COMMENT '1 - Frase' ,
  `dt_inclusao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datam e hora da inclusão' ,
  PRIMARY KEY (`cd_comentario_palavras`) ,
  INDEX `tb_comentario_palavras_nm_comentario_palavra` (`nm_comentario_palavras` ASC) )
ENGINE = InnoDB, 
COMMENT = 'Tabela de palavras proibidas nos comentários' ;


-- -----------------------------------------------------
-- Table `tb_noticia_visualizacoes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_noticia_visualizacoes` ;

CREATE  TABLE IF NOT EXISTS `tb_noticia_visualizacoes` (
  `cd_noticia_visualizacoes` INT NOT NULL AUTO_INCREMENT COMMENT 'PK das visualizações' ,
  `cd_noticia` INT NOT NULL COMMENT 'FK da noticia' ,
  `nm_ano` SMALLINT NULL DEFAULT NULL COMMENT 'Identificação do ano' ,
  `nm_mes` TINYINT NULL DEFAULT NULL COMMENT 'Identificação do mês' ,
  `nm_dia` TINYINT NULL DEFAULT NULL ,
  `nm_hora` TINYINT NULL DEFAULT NULL ,
  `nm_quarter` TINYINT NULL DEFAULT NULL ,
  `nm_day_of_year` SMALLINT NULL DEFAULT NULL ,
  `nm_week` TINYINT NULL DEFAULT NULL ,
  `nm_week_day` TINYINT NULL DEFAULT NULL ,
  `nm_quantidade` INT NULL DEFAULT 0 COMMENT 'Quantidade para o período definir anteriormente' ,
  `dt_inclusao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data e hora da inclusão da referência' ,
  PRIMARY KEY (`cd_noticia_visualizacoes`) ,
  INDEX `tb_noticia_visualizacoes_cd_noticia` (`cd_noticia` ASC) ,
  INDEX `tb_noticia_visualizacoes_nm_ano` (`nm_ano` ASC) ,
  INDEX `tb_noticia_visualizacoes_nm_mes` (`nm_mes` ASC) ,
  CONSTRAINT `tb_noticia_visualizacoes_cd_noticia`
    FOREIGN KEY (`cd_noticia` )
    REFERENCES `tb_noticia` (`cd_noticia` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Visualizações de uma noticia' ;


-- -----------------------------------------------------
-- Table `tb_mes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_mes` ;

CREATE  TABLE IF NOT EXISTS `tb_mes` (
  `cd_mes` INT NOT NULL AUTO_INCREMENT ,
  `nm_mes` VARCHAR(20) NULL DEFAULT NULL ,
  `sg_mes1` CHAR(1) NULL DEFAULT NULL ,
  `sg_mes2` CHAR(2) NULL DEFAULT NULL ,
  `sg_mes3` CHAR(3) NULL DEFAULT NULL ,
  `nm_dias` TINYINT NULL DEFAULT NULL ,
  `nm_bisexto` TINYINT NULL DEFAULT NULL ,
  PRIMARY KEY (`cd_mes`) ,
  INDEX `tb_mes_nm_mes` (`nm_mes` ASC) )
ENGINE = InnoDB, 
COMMENT = 'Tabela com os meses do ano' ;


-- -----------------------------------------------------
-- Table `tb_newsletter`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_newsletter` ;

CREATE  TABLE IF NOT EXISTS `tb_newsletter` (
  `cd_newsletter` INT NOT NULL AUTO_INCREMENT COMMENT 'PK da newsletter' ,
  `sg_newsletter` VARCHAR(20) NOT NULL COMMENT 'Sigla da newsletter' ,
  `nm_newsletter` VARCHAR(50) NOT NULL COMMENT 'Nome da newsletter' ,
  `fl_ativo` TINYINT NULL DEFAULT 1 ,
  `dt_inclusao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data e hora da inclusão' ,
  PRIMARY KEY (`cd_newsletter`) )
ENGINE = InnoDB, 
COMMENT = 'Newsletters disponíveis' ;


-- -----------------------------------------------------
-- Table `tb_pessoa`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_pessoa` ;

CREATE  TABLE IF NOT EXISTS `tb_pessoa` (
  `cd_pessoa` INT NOT NULL AUTO_INCREMENT COMMENT 'PK das pessoas' ,
  `nm_pessoa` VARCHAR(100) NOT NULL COMMENT 'Nome da pessoa' ,
  `dt_inclusao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data e hora da inclusao' ,
  PRIMARY KEY (`cd_pessoa`) )
ENGINE = InnoDB, 
COMMENT = 'Tabela de pessoas no sistema' ;


-- -----------------------------------------------------
-- Table `tb_pessoa_newsletter`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_pessoa_newsletter` ;

CREATE  TABLE IF NOT EXISTS `tb_pessoa_newsletter` (
  `cd_pessoa_newsletter` INT NOT NULL AUTO_INCREMENT ,
  `cd_pessoa` INT NOT NULL ,
  `cd_newsletter` INT NOT NULL ,
  `fl_ativo` TINYINT NULL DEFAULT 1 ,
  `dt_inclusao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ,
  `dt_cancelamento` DATETIME NULL DEFAULT NULL ,
  `tp_prioridade` TINYINT NULL DEFAULT NULL ,
  PRIMARY KEY (`cd_pessoa_newsletter`) ,
  INDEX `tb_pessoa_newsletter_cd_pessoa` (`cd_pessoa` ASC) ,
  INDEX `tb_pessoa_newsletter_cd_newsletter` (`cd_newsletter` ASC) ,
  CONSTRAINT `tb_pessoa_newsletter_cd_pessoa`
    FOREIGN KEY (`cd_pessoa` )
    REFERENCES `tb_pessoa` (`cd_pessoa` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `tb_pessoa_newsletter_cd_newsletter`
    FOREIGN KEY (`cd_newsletter` )
    REFERENCES `tb_newsletter` (`cd_newsletter` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Relação entre a pessoa e uma Newsletter' ;


-- -----------------------------------------------------
-- Table `tb_newsletter_fonte`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_newsletter_fonte` ;

CREATE  TABLE IF NOT EXISTS `tb_newsletter_fonte` (
  `cd_newsletter_fonte` INT NOT NULL AUTO_INCREMENT ,
  `cd_newsletter` INT NOT NULL ,
  `cd_fonte` INT NOT NULL ,
  `fl_ativo` TINYINT NULL DEFAULT 1 ,
  `nu_ordem` TINYINT NULL DEFAULT NULL COMMENT 'Ordem da fonte para a sua utilização' ,
  `qt_noticia` TINYINT NULL DEFAULT 1 COMMENT 'Quantidade de notícias que serão selecionadas da fonte' ,
  PRIMARY KEY (`cd_newsletter_fonte`) ,
  INDEX `tb_newsletter_fonte_cd_fonte` (`cd_fonte` ASC) ,
  INDEX `tb_newsletter_fonte_cd_newsletter` (`cd_newsletter` ASC) ,
  CONSTRAINT `tb_newsletter_fonte_cd_fonte`
    FOREIGN KEY (`cd_fonte` )
    REFERENCES `tb_fonte` (`cd_fonte` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `tb_newsletter_fonte_cd_newsletter`
    FOREIGN KEY (`cd_newsletter` )
    REFERENCES `tb_newsletter` (`cd_newsletter` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Fontes de uma newsletter' ;


-- -----------------------------------------------------
-- Table `tb_newsletter_mensagem`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_newsletter_mensagem` ;

CREATE  TABLE IF NOT EXISTS `tb_newsletter_mensagem` (
  `cd_newsletter_mensagem` INT NOT NULL AUTO_INCREMENT ,
  `cd_newsletter` INT NOT NULL COMMENT 'Mensagem de qual newsletter' ,
  `dt_inclusao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ,
  `qt_pessoas` INT NULL DEFAULT NULL COMMENT 'Quantida de pessoas que receberão a mensagem' ,
  PRIMARY KEY (`cd_newsletter_mensagem`) ,
  INDEX `tb_newsletter_mensagem_cd_newsletter` (`cd_newsletter` ASC) ,
  CONSTRAINT `tb_newsletter_mensagem_cd_newsletter`
    FOREIGN KEY (`cd_newsletter` )
    REFERENCES `tb_newsletter` (`cd_newsletter` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Mensagens que foram geradas com base nos critérios da Newsl' ;


-- -----------------------------------------------------
-- Table `tb_newsletter_mensagem_noticia`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_newsletter_mensagem_noticia` ;

CREATE  TABLE IF NOT EXISTS `tb_newsletter_mensagem_noticia` (
  `cd_newsletter_mensagem_noticia` INT NOT NULL AUTO_INCREMENT ,
  `cd_mensagem` INT NOT NULL ,
  `cd_noticia` INT NOT NULL ,
  `nu_ordem` TINYINT NULL DEFAULT NULL COMMENT 'Ordem que será utilizada' ,
  PRIMARY KEY (`cd_newsletter_mensagem_noticia`) ,
  INDEX `tb_newsletter_mensagem_noticia_cd_noticia` (`cd_noticia` ASC) ,
  INDEX `tb_newsletter_mensagem_noticia_cd_mensagem` (`cd_mensagem` ASC) ,
  CONSTRAINT `tb_newsletter_mensagem_noticia_cd_noticia`
    FOREIGN KEY (`cd_noticia` )
    REFERENCES `tb_noticia` (`cd_noticia` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `tb_newsletter_mensagem_noticia_cd_mensagem`
    FOREIGN KEY (`cd_mensagem` )
    REFERENCES `tb_newsletter_mensagem` (`cd_newsletter_mensagem` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Relação entre a mensagem a notícia' ;


-- -----------------------------------------------------
-- Table `tb_newsletter_mensagem_pessoa`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_newsletter_mensagem_pessoa` ;

CREATE  TABLE IF NOT EXISTS `tb_newsletter_mensagem_pessoa` (
  `cd_newsletter_mensagem_pessoa` INT NOT NULL AUTO_INCREMENT ,
  `cd_mensagem` INT NOT NULL ,
  `cd_pessoa` INT NOT NULL ,
  `dt_inclusao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ,
  `qt_visualizacao` INT NULL DEFAULT 0 ,
  `fl_estado` INT NULL DEFAULT NULL ,
  `qt_tentativas` TINYINT NULL DEFAULT NULL ,
  `dt_ultimo_estado` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`cd_newsletter_mensagem_pessoa`) ,
  INDEX `tb_newsletter_mensagem_pessoa_cd_pessoa` (`cd_pessoa` ASC) ,
  INDEX `tb_newsletter_mensagem_pessoa_cd_mensagem` (`cd_mensagem` ASC) ,
  INDEX `tb_newsletter_mensagem_pessoa_fl_estado` (`fl_estado` ASC) ,
  CONSTRAINT `tb_newsletter_mensagem_pessoa_cd_pessoa`
    FOREIGN KEY (`cd_pessoa` )
    REFERENCES `tb_pessoa` (`cd_pessoa` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `tb_newsletter_mensagem_pessoa_cd_mensagem`
    FOREIGN KEY (`cd_mensagem` )
    REFERENCES `tb_newsletter_mensagem` (`cd_newsletter_mensagem` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Pessoas que vão receber a mensagem' ;


-- -----------------------------------------------------
-- Table `tb_pais`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_pais` ;

CREATE  TABLE IF NOT EXISTS `tb_pais` (
  `cd_pais` INT NOT NULL AUTO_INCREMENT ,
  `sg_pais` VARCHAR(10) NOT NULL ,
  `nm_pais` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`cd_pais`) ,
  INDEX `tb_pais_sg_pais` (`sg_pais` ASC) ,
  INDEX `tb_pais_nm_pais` (`nm_pais` ASC) )
ENGINE = InnoDB, 
COMMENT = 'Paises' ;


-- -----------------------------------------------------
-- Table `tb_estado`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_estado` ;

CREATE  TABLE IF NOT EXISTS `tb_estado` (
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
    REFERENCES `tb_pais` (`cd_pais` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Estados' ;


-- -----------------------------------------------------
-- Table `tb_cidade`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_cidade` ;

CREATE  TABLE IF NOT EXISTS `tb_cidade` (
  `cd_cidade` INT NOT NULL AUTO_INCREMENT ,
  `cd_estado` INT NULL ,
  `nm_cidade` VARCHAR(100) NULL ,
  `nu_latitude` DECIMAL(5,5) NULL ,
  `nu_longitude` DECIMAL(5,5) NULL ,
  `nu_altitude` INT NULL ,
  `nu_area` DECIMAL(5,5) NULL ,
  `nu_instalacao` SMALLINT NULL ,
  `fl_amazonia` TINYINT NULL ,
  `fl_fronteira` TINYINT NULL ,
  `fl_capital` TINYINT NULL ,
  `fl_mesoarea` SMALLINT NULL ,
  `fl_microarea` SMALLINT NULL ,
  `fl_ativo` TINYINT NULL ,
  PRIMARY KEY (`cd_cidade`) ,
  INDEX `tb_cidade_nm_cidade` (`nm_cidade` ASC) ,
  INDEX `tb_cidade_cd_estado` (`cd_estado` ASC) ,
  SPATIAL INDEX `tb_cidade_coordenadas` (`nu_latitude` ASC, `nu_longitude` ASC) ,
  INDEX `tb_cidade_fl_capital` (`fl_capital` ASC) ,
  CONSTRAINT `tb_cidade_cd_estado`
    FOREIGN KEY (`cd_estado` )
    REFERENCES `tb_estado` (`cd_estado` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Cidades' ;


-- -----------------------------------------------------
-- Table `tb_palavra_origem`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_palavra_origem` ;

CREATE  TABLE IF NOT EXISTS `tb_palavra_origem` (
  `cd_palavra_origem` INT NOT NULL AUTO_INCREMENT ,
  `sg_palavra_origem` VARCHAR(45) NULL ,
  `nm_palavra_origem` INT NULL ,
  `dt_inclusao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (`cd_palavra_origem`) ,
  INDEX `ix_palavra_origem_sg_palavra` (`sg_palavra_origem` ASC) ,
  INDEX `ix_palavra_origem_nm_palavra` (`nm_palavra_origem` ASC) )
ENGINE = InnoDB, 
COMMENT = 'Origem das palavras' ;


-- -----------------------------------------------------
-- Table `tb_palavra`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_palavra` ;

CREATE  TABLE IF NOT EXISTS `tb_palavra` (
  `cd_palavra` INT NOT NULL AUTO_INCREMENT ,
  `cd_palavra_correta` INT NULL ,
  `cd_palavra_origem` VARCHAR(45) NULL ,
  `nm_palavra` VARCHAR(100) NOT NULL ,
  `qt_palavra` INT NULL DEFAULT 0 ,
  `dt_inclusao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (`cd_palavra`) ,
  FULLTEXT INDEX `ix_palavra_nm_palavra` (`nm_palavra` ASC) ,
  INDEX `ix_palavra_qt_palavra` (`qt_palavra` ASC) ,
  INDEX `tb_palavra_origem` (`cd_palavra_origem` ASC) ,
  CONSTRAINT `tb_palavra_origem`
    FOREIGN KEY (`cd_palavra_origem` )
    REFERENCES `tb_palavra_origem` (`sg_palavra_origem` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM, 
COMMENT = 'Tabela de palavras' ;


-- -----------------------------------------------------
-- Table `tb_palavra_noticia`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_palavra_noticia` ;

CREATE  TABLE IF NOT EXISTS `tb_palavra_noticia` (
  `cd_palavra_noticia` INT NOT NULL AUTO_INCREMENT ,
  `cd_palavra` INT NULL ,
  `cd_noticia` INT NULL ,
  PRIMARY KEY (`cd_palavra_noticia`) ,
  INDEX `ix_palavra_noticia_cd_noticia` (`cd_noticia` ASC, `cd_palavra` ASC) ,
  INDEX `ix_palavra_noticia_cd_palavra` (`cd_palavra` ASC, `cd_noticia` ASC) ,
  INDEX `tb_palavra_noticia_cd_noticia` (`cd_noticia` ASC) ,
  INDEX `tb_palavra_noticia_cd_palavra` (`cd_palavra` ASC) ,
  CONSTRAINT `tb_palavra_noticia_cd_noticia`
    FOREIGN KEY (`cd_noticia` )
    REFERENCES `tb_noticia` (`cd_noticia` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `tb_palavra_noticia_cd_palavra`
    FOREIGN KEY (`cd_palavra` )
    REFERENCES `tb_palavra` (`cd_palavra` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Palavras das notícias' ;


-- -----------------------------------------------------
-- Table `tb_palavra_dia`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_palavra_dia` ;

CREATE  TABLE IF NOT EXISTS `tb_palavra_dia` (
  `cd_palavra_dia` INT NOT NULL AUTO_INCREMENT ,
  `cd_palavra` INT NULL ,
  `nu_ano` SMALLINT NULL ,
  `nu_mes` TINYINT NULL ,
  `nu_dia` TINYINT NULL ,
  `nu_hora` TINYINT NULL ,
  `nu_quarter` TINYINT NULL ,
  `nu_day_of_year` SMALLINT NULL ,
  `nu_week` TINYINT NULL ,
  `nu_week_day` TINYINT NULL ,
  `qt_palavra_dia` INT NULL DEFAULT 0 ,
  `dt_inclusao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (`cd_palavra_dia`) ,
  INDEX `ix_palavra_dia_cd_palavra` (`cd_palavra` ASC) ,
  INDEX `ix_palavra_dia_hora` (`nu_ano` ASC, `nu_mes` ASC, `nu_dia` ASC, `nu_hora` ASC) ,
  INDEX `ix_palavra_dia_week` (`nu_week` ASC) ,
  INDEX `ix_palavra_dia_inclusao` (`dt_inclusao` ASC) ,
  INDEX `tb_palavra_dia_cd_palavra` (`cd_palavra` ASC) ,
  CONSTRAINT `tb_palavra_dia_cd_palavra`
    FOREIGN KEY (`cd_palavra` )
    REFERENCES `tb_palavra` (`cd_palavra` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM, 
COMMENT = 'Palavras no dia' ;


-- -----------------------------------------------------
-- Table `tb_noticia_visualizacoes_copy1`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_noticia_visualizacoes_copy1` ;

CREATE  TABLE IF NOT EXISTS `tb_noticia_visualizacoes_copy1` (
  `cd_noticia_visualizacoes` INT NOT NULL AUTO_INCREMENT COMMENT 'PK das visualizações' ,
  `cd_noticia` INT NOT NULL COMMENT 'FK da noticia' ,
  `nm_ano` SMALLINT NULL DEFAULT NULL COMMENT 'Identificação do ano' ,
  `nm_mes` TINYINT NULL DEFAULT NULL COMMENT 'Identificação do mês' ,
  `nm_dia` TINYINT NULL DEFAULT NULL ,
  `nm_hora` TINYINT NULL DEFAULT NULL ,
  `nm_quarter` TINYINT NULL DEFAULT NULL ,
  `nm_day_of_year` SMALLINT NULL DEFAULT NULL ,
  `nm_week` TINYINT NULL DEFAULT NULL ,
  `nm_week_day` TINYINT NULL DEFAULT NULL ,
  `nm_quantidade` INT NULL DEFAULT 0 COMMENT 'Quantidade para o período definir anteriormente' ,
  `dt_inclusao` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data e hora da inclusão da referência' ,
  PRIMARY KEY (`cd_noticia_visualizacoes`) ,
  INDEX `tb_noticia_visualizacoes_cd_noticia` (`cd_noticia` ASC) ,
  INDEX `tb_noticia_visualizacoes_nm_ano` (`nm_ano` ASC) ,
  INDEX `tb_noticia_visualizacoes_nm_mes` (`nm_mes` ASC) ,
  CONSTRAINT `tb_noticia_visualizacoes_cd_noticia`
    FOREIGN KEY (`cd_noticia` )
    REFERENCES `tb_noticia` (`cd_noticia` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Visualizações de uma noticia' ;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

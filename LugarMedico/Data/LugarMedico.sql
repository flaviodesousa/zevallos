SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';


-- -----------------------------------------------------
-- Table `TB_PREFIXO`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `TB_PREFIXO` ;

CREATE  TABLE IF NOT EXISTS `TB_PREFIXO` (
  `CD_PREFIXO` INT NOT NULL AUTO_INCREMENT COMMENT 'PK do Prefixo' ,
  `NM_PREFIXO` VARCHAR(45) NOT NULL COMMENT 'Nome longo do prefixo' ,
  `DS_SIGLA` VARCHAR(20) NOT NULL COMMENT 'Sigla' ,
  `FL_ATIVO` TINYINT NULL DEFAULT 1 COMMENT 'Flag de ativo\n0 - Destativado\nnull - Desativado\n1 - Ativo' ,
  PRIMARY KEY (`CD_PREFIXO`) )
ENGINE = InnoDB, 
COMMENT = 'Prefixo das pessoas Sr. Sra. Dr. Dra. Etc' ;


-- -----------------------------------------------------
-- Table `TB_PESSOA`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `TB_PESSOA` ;

CREATE  TABLE IF NOT EXISTS `TB_PESSOA` (
  `CD_PESSOA` INT NOT NULL AUTO_INCREMENT COMMENT 'PK da pessoa' ,
  `CD_PREFIXO` INT NULL COMMENT 'Prefixo ao nome Ex. Dr. Sr. etc' ,
  `TP_TIPO` TINYINT NOT NULL COMMENT 'Tipo da pessoa:\n0 - Física\n1 - Jurídica' ,
  `NM_PESSOA` VARCHAR(50) NOT NULL COMMENT 'Nome completo da pessoa' ,
  `NM_PRIMEIRO` VARCHAR(30) NOT NULL COMMENT 'Primeiro nome' ,
  `NM_MEIO` CHAR(1) NULL COMMENT 'Inicial do meio' ,
  `NM_ULTIMO` VARCHAR(30) NULL COMMENT 'Último nome' ,
  `DS_URL_PERFIL` VARCHAR(100) NULL COMMENT 'Complemento da URL do Perfil público da pessoa' ,
  `DS_EMAIL` VARCHAR(100) NOT NULL COMMENT 'E-mail de contato e login' ,
  `DS_AVATAR` VARCHAR(100) NULL COMMENT 'Imagem do Avatar - Null usuará o Gravatar' ,
  `DT_INCLUSAO` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data de inclusão' ,
  `FL_ATIVO` TINYINT NULL DEFAULT 1 COMMENT 'Flag de ativo\n0 - Destativado\nnull - Desativado\n1 - Ativo' ,
  `DS_CONSELHO` VARCHAR(20) NULL ,
  `NM_DISPLAY` VARCHAR(20) NULL COMMENT 'Nome que será apresentado' ,
  `DT_ULTIMA_ATIVIDADE` TIMESTAMP NULL ,
  PRIMARY KEY (`CD_PESSOA`) ,
  INDEX `TB_PESSOA_DS_EMAIL` (`DS_EMAIL` ASC) ,
  INDEX `TB_PESSOA_DS_NOME` (`NM_PESSOA` ASC) ,
  INDEX `TB_PESSOA_CD_PREFIXO` (`CD_PREFIXO` ASC) ,
  INDEX `TB_PESSOA_DS_URL_PERFIL` (`DS_URL_PERFIL` ASC) ,
  CONSTRAINT `TB_PESSOA_CD_PREFIXO`
    FOREIGN KEY (`CD_PREFIXO` )
    REFERENCES `TB_PREFIXO` (`CD_PREFIXO` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Pessoas do sistema. Cada pessoa será única dentro da exist' /* comment truncated */ ;


-- -----------------------------------------------------
-- Table `TB_USUARIO`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `TB_USUARIO` ;

CREATE  TABLE IF NOT EXISTS `TB_USUARIO` (
  `CD_USUARIO` INT NOT NULL AUTO_INCREMENT COMMENT 'PK dos usuários' ,
  `CD_PESSOA` INT NULL COMMENT 'FK com a tabela de pessoa' ,
  `TP_AUTENTICACAO` TINYINT NULL COMMENT 'Tipo de autenticação \n1 - E-mail\n2 - Facebook\n3 - Google\nEtc' ,
  `DS_LOGIN` VARCHAR(100) NULL COMMENT 'Login, caso o tipo de login requerer' ,
  `DS_SENHA` VARCHAR(20) NULL COMMENT 'Senha caso o tipo de login requerer' ,
  PRIMARY KEY (`CD_USUARIO`) ,
  INDEX `TB_USUARIO_CD_PESSOA` (`CD_PESSOA` ASC) ,
  INDEX `DS_LOGIN` (`DS_LOGIN` ASC) ,
  CONSTRAINT `TB_USUARIO_CD_PESSOA`
    FOREIGN KEY (`CD_PESSOA` )
    REFERENCES `TB_PESSOA` (`CD_PESSOA` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Tabela do usuários do sistema, este será a forma de entrad' /* comment truncated */ ;


-- -----------------------------------------------------
-- Table `TB_BROWSER`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `TB_BROWSER` ;

CREATE  TABLE IF NOT EXISTS `TB_BROWSER` (
  `CD_BROWSER` INT NOT NULL AUTO_INCREMENT ,
  `NM_BROWSER` VARCHAR(50) NOT NULL COMMENT 'Nome completo do Browser' ,
  `DS_SIGLA` VARCHAR(10) NOT NULL COMMENT 'Sigla do Browser' ,
  `DS_URL` VARCHAR(100) NULL COMMENT 'URL do site do Browser' ,
  PRIMARY KEY (`CD_BROWSER`) )
ENGINE = InnoDB, 
COMMENT = 'Tabela de todos os Browsers' ;


-- -----------------------------------------------------
-- Table `TB_DISPOSITIVO`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `TB_DISPOSITIVO` ;

CREATE  TABLE IF NOT EXISTS `TB_DISPOSITIVO` (
  `CD_DISPOSITIVO` INT NOT NULL AUTO_INCREMENT COMMENT 'PK dos dispositivos' ,
  `NM_DISPOSITIVO` VARCHAR(100) NOT NULL COMMENT 'Nome completo do dispositivo' ,
  `DS_SIGLA` VARCHAR(20) NOT NULL COMMENT 'Sigla do dispositivo' ,
  PRIMARY KEY (`CD_DISPOSITIVO`) )
ENGINE = InnoDB, 
COMMENT = 'Tabela de tipos de dispositivos' ;


-- -----------------------------------------------------
-- Table `TB_SESSAO`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `TB_SESSAO` ;

CREATE  TABLE IF NOT EXISTS `TB_SESSAO` (
  `CD_SESSAO` INT NOT NULL AUTO_INCREMENT COMMENT 'PK da sessão' ,
  `DT_INICIO` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data e hora de início da sessão' ,
  `DT_FIM` DATETIME NULL COMMENT 'Data e hora do fim da sessão, que será todas as vezes que se fizer algum update.' ,
  `CD_PESSOA` INT NULL COMMENT 'FK de pessoa, quando autenticado' ,
  `CD_BROWSER` INT NULL COMMENT 'FK do tipo de browser' ,
  `CD_DISPOSITIVO` INT NULL COMMENT 'FK do tipo de dispositivo' ,
  PRIMARY KEY (`CD_SESSAO`) ,
  INDEX `TB_SESSAO_CD_PESSOA` (`CD_PESSOA` ASC) ,
  INDEX `TB_SESSAO_CD_BROWSER` (`CD_BROWSER` ASC) ,
  INDEX `TB_SESSAO_CD_DISPOSITIVO` (`CD_DISPOSITIVO` ASC) ,
  CONSTRAINT `TB_SESSAO_CD_PESSOA`
    FOREIGN KEY (`CD_PESSOA` )
    REFERENCES `TB_PESSOA` (`CD_PESSOA` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `TB_SESSAO_CD_BROWSER`
    FOREIGN KEY (`CD_BROWSER` )
    REFERENCES `TB_BROWSER` (`CD_BROWSER` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `TB_SESSAO_CD_DISPOSITIVO`
    FOREIGN KEY (`CD_DISPOSITIVO` )
    REFERENCES `TB_DISPOSITIVO` (`CD_DISPOSITIVO` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Controle de todas as sessões do sistema' ;


-- -----------------------------------------------------
-- Table `TB_PESSOA_FISICA`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `TB_PESSOA_FISICA` ;

CREATE  TABLE IF NOT EXISTS `TB_PESSOA_FISICA` (
  `CD_PESSOA_FISICA` INT NOT NULL AUTO_INCREMENT ,
  `CD_PESSOA` INT NULL COMMENT 'FK da pessoa' ,
  `NU_CPF` VARCHAR(20) NULL COMMENT 'CPF da pessoa' ,
  `NU_RG` VARCHAR(20) NULL COMMENT 'RG da pessoa' ,
  `DT_RG_EMISSAO` DATE NULL COMMENT 'Emissão da RG' ,
  PRIMARY KEY (`CD_PESSOA_FISICA`) ,
  INDEX `TB_PESSOA_FISICA_CD_PESSOA` (`CD_PESSOA` ASC) ,
  INDEX `NU_CPF` (`NU_CPF` ASC) ,
  CONSTRAINT `TB_PESSOA_FISICA_CD_PESSOA`
    FOREIGN KEY (`CD_PESSOA` )
    REFERENCES `TB_PESSOA` (`CD_PESSOA` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Dados pessoais da pessoa' ;


-- -----------------------------------------------------
-- Table `TB_PESSOA_JURIDICA`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `TB_PESSOA_JURIDICA` ;

CREATE  TABLE IF NOT EXISTS `TB_PESSOA_JURIDICA` (
  `CD_PESSOA_JURIDICA` INT NOT NULL AUTO_INCREMENT COMMENT 'PK dos dados jurídicos da pessoa' ,
  `CD_PESSOA` INT NOT NULL COMMENT 'FK da pessoa' ,
  `NU_CNPJ` VARCHAR(20) NULL COMMENT 'CNPJ da pessoa jurídica' ,
  `DS_RAZAO_SOCIAL` VARCHAR(100) NULL COMMENT 'Razão Social da pessoa jurídica' ,
  PRIMARY KEY (`CD_PESSOA_JURIDICA`) ,
  INDEX `TB_PESSOA_JURIDICA_CD_PESSOA` (`CD_PESSOA` ASC) ,
  CONSTRAINT `TB_PESSOA_JURIDICA_CD_PESSOA`
    FOREIGN KEY (`CD_PESSOA` )
    REFERENCES `TB_PESSOA` (`CD_PESSOA` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Dados jurídicos da pessoa' ;


-- -----------------------------------------------------
-- Table `TB_CIRCULO`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `TB_CIRCULO` ;

CREATE  TABLE IF NOT EXISTS `TB_CIRCULO` (
  `CD_CIRCULO` INT NOT NULL AUTO_INCREMENT ,
  `CD_PESSOA` INT NULL COMMENT 'FK da pessoa' ,
  `NM_CIRCULO` VARCHAR(100) NOT NULL COMMENT 'Nome do círculo' ,
  `DS_CIRCULO` VARCHAR(255) NULL COMMENT 'Descrição do círculo' ,
  `DT_INCLUSAO` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `FL_ATIVO` TINYINT NULL DEFAULT 1 COMMENT 'Flag de ativo\n0 - Destativado\nnull - Desativado\n1 - Ativo' ,
  PRIMARY KEY (`CD_CIRCULO`) ,
  INDEX `TB_CIRCULO_CD_PESSOA` (`CD_PESSOA` ASC) ,
  CONSTRAINT `TB_CIRCULO_CD_PESSOA`
    FOREIGN KEY (`CD_PESSOA` )
    REFERENCES `TB_PESSOA` (`CD_PESSOA` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Círculo de colegas' ;


-- -----------------------------------------------------
-- Table `TB_CIRCULO_PESSOA`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `TB_CIRCULO_PESSOA` ;

CREATE  TABLE IF NOT EXISTS `TB_CIRCULO_PESSOA` (
  `CD_CIRCULO_PESSOA` INT NOT NULL AUTO_INCREMENT COMMENT 'PK das pessoas do Círculo' ,
  `CD_CIRCULO` INT NULL COMMENT 'FK do círculo' ,
  `CD_PESSOA` INT NULL ,
  `FL_ATIVO` TINYINT NULL DEFAULT 1 COMMENT 'Flag de ativo\n0 - Destativado\nnull - Desativado\n1 - Ativo' ,
  `DT_INCLUSAO` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data de inclusão' ,
  PRIMARY KEY (`CD_CIRCULO_PESSOA`) ,
  INDEX `TB_CIRCULO_PESSOA_CD_CIRCULO` (`CD_CIRCULO` ASC) ,
  INDEX `TB_CIRCULO_PESSOA_CD_PESSOA` (`CD_PESSOA` ASC) ,
  CONSTRAINT `TB_CIRCULO_PESSOA_CD_CIRCULO`
    FOREIGN KEY (`CD_CIRCULO` )
    REFERENCES `TB_CIRCULO` (`CD_CIRCULO` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `TB_CIRCULO_PESSOA_CD_PESSOA`
    FOREIGN KEY (`CD_PESSOA` )
    REFERENCES `TB_PESSOA` (`CD_PESSOA` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Pessoas do círculo' ;


-- -----------------------------------------------------
-- Table `TB_COLEGA_CONVITE`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `TB_COLEGA_CONVITE` ;

CREATE  TABLE IF NOT EXISTS `TB_COLEGA_CONVITE` (
  `CD_COLEGA_CONVITE` INT NOT NULL AUTO_INCREMENT ,
  `CD_PESSOA` INT NULL COMMENT 'FK da pessoa' ,
  `DS_JUSTIFICATIVA` VARCHAR(255) NULL COMMENT 'Justificativa para o convite' ,
  `DT_INCLUSAO` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data de inclusão' ,
  `DT_ULTIMO_ENVIO` DATETIME NULL COMMENT 'Data e hora do último envio' ,
  `NU_VISUALIZACAO` INT NULL DEFAULT 0 COMMENT 'Quantidade de visualizações' ,
  `TP_ACEITE` TINYINT NULL COMMENT 'Tipo do aceite\nnull - indefinido\n0 - Não aceitou\n1 - Aceitou' ,
  `DT_ACEITE` DATETIME NULL COMMENT 'Data do aceite' ,
  `DS_ACEITE` VARCHAR(255) NULL COMMENT 'Comentários para o aceite' ,
  PRIMARY KEY (`CD_COLEGA_CONVITE`) ,
  INDEX `TB_COLEGA_CONVITE_CD_PESSOA` (`CD_PESSOA` ASC) ,
  CONSTRAINT `TB_COLEGA_CONVITE_CD_PESSOA`
    FOREIGN KEY (`CD_PESSOA` )
    REFERENCES `TB_PESSOA` (`CD_PESSOA` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Convite de coletas' ;


-- -----------------------------------------------------
-- Table `TB_COMENTARIO`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `TB_COMENTARIO` ;

CREATE  TABLE IF NOT EXISTS `TB_COMENTARIO` (
  `CD_COMENTARIO` INT NOT NULL AUTO_INCREMENT COMMENT 'PK dos comentários' ,
  `CD_COMENTARIO_PAI` INT NULL COMMENT 'FK do comentário' ,
  `CD_PESSOA` INT NULL COMMENT 'FK da pessoa que comentou' ,
  `CD_PESSOA_ORIGINAL` INT NULL COMMENT 'FK da pessoa que originou os comentários' ,
  `NM_COMENTARIO` VARCHAR(100) NULL COMMENT 'Título do comentário' ,
  `DS_COMENTARIO` TEXT NULL COMMENT 'Conteúdo do comentário' ,
  `FL_FILHO` TINYINT NULL COMMENT 'Null ou 0 - Se não tem\n1 - Se tem filho' ,
  `DT_INCLUSAO` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Inclusão do comentário' ,
  `FL_ATIVO` TINYINT NULL DEFAULT 1 COMMENT 'Flag de ativo\n0 - Destativado\nnull - Desativado\n1 - Ativo' ,
  PRIMARY KEY (`CD_COMENTARIO`) ,
  INDEX `TB_COMENTARIO_CD_PESSOA` (`CD_PESSOA` ASC) ,
  INDEX `TB_COMENTARIO_CD_COMENTARIO_PAI` (`CD_COMENTARIO_PAI` ASC) ,
  INDEX `TB_COMENTARIO_CD_PESSOA_ORIGINAL` (`CD_PESSOA_ORIGINAL` ASC) ,
  CONSTRAINT `TB_COMENTARIO_CD_PESSOA`
    FOREIGN KEY (`CD_PESSOA` )
    REFERENCES `TB_PESSOA` (`CD_PESSOA` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `TB_COMENTARIO_CD_COMENTARIO_PAI`
    FOREIGN KEY (`CD_COMENTARIO_PAI` )
    REFERENCES `TB_COMENTARIO` (`CD_COMENTARIO` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `TB_COMENTARIO_CD_PESSOA_ORIGINAL`
    FOREIGN KEY (`CD_PESSOA_ORIGINAL` )
    REFERENCES `TB_PESSOA` (`CD_PESSOA` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Comentários dos objetos' ;


-- -----------------------------------------------------
-- Table `TB_OBJETO`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `TB_OBJETO` ;

CREATE  TABLE IF NOT EXISTS `TB_OBJETO` (
  `CD_OBJETO` INT NOT NULL AUTO_INCREMENT ,
  `CD_PESSOA` INT NULL COMMENT 'FK da pessoa dona do objeto' ,
  `NM_OBJETO` VARCHAR(50) NOT NULL COMMENT 'Nome do objeto' ,
  `DS_OBJETO` TEXT NULL COMMENT 'Descrição do objeto' ,
  `TP_OBJETO` TINYINT NULL COMMENT 'Tipo do objeto:\n0 - Imagem\n1 - Vídeo\n2 - Arquivo' ,
  `DS_URL` VARCHAR(45) NULL COMMENT 'URL para visualização do objeto' ,
  `DS_INCLUSAO` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data da inclusão do objeto' ,
  `FL_ATIVO` TINYINT NULL DEFAULT 1 COMMENT 'Flag de ativo\n0 - Destativado\nnull - Desativado\n1 - Ativo' ,
  PRIMARY KEY (`CD_OBJETO`) ,
  INDEX `TB_OBJETO_CD_PESSOA` (`CD_PESSOA` ASC) ,
  CONSTRAINT `TB_OBJETO_CD_PESSOA`
    FOREIGN KEY (`CD_PESSOA` )
    REFERENCES `TB_PESSOA` (`CD_PESSOA` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Objetos do sistema' ;


-- -----------------------------------------------------
-- Table `TB_ALBUM`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `TB_ALBUM` ;

CREATE  TABLE IF NOT EXISTS `TB_ALBUM` (
  `CD_ALBUM` INT NOT NULL AUTO_INCREMENT COMMENT 'PK do album de objetos' ,
  `CD_PESSOA` INT NULL COMMENT 'FK da pessoa que criou o album' ,
  `NM_ALBUM` VARCHAR(100) NOT NULL COMMENT 'Nome do album' ,
  `DS_ALBUM` VARCHAR(255) NULL COMMENT 'Descrição do album' ,
  `DT_INCLUSAO` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data de criação do album' ,
  `FL_ATIVO` TINYINT NULL DEFAULT 1 COMMENT 'Flag de ativo\n0 - Destativado\nnull - Desativado\n1 - Ativo' ,
  `TP_PRIVACIDADE` TINYINT NULL DEFAULT 0 COMMENT 'Nível da privacidade\n0 - Somente o criador\n1 - Somente o círculo de amigos\n2 - Somente usuários logados\n3 - Público' ,
  PRIMARY KEY (`CD_ALBUM`) ,
  INDEX `TB_ALBUM_CD_PESSOA` (`CD_PESSOA` ASC) ,
  CONSTRAINT `TB_ALBUM_CD_PESSOA`
    FOREIGN KEY (`CD_PESSOA` )
    REFERENCES `TB_PESSOA` (`CD_PESSOA` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Album de objetos' ;


-- -----------------------------------------------------
-- Table `TB_ALBUM_OBJETO`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `TB_ALBUM_OBJETO` ;

CREATE  TABLE IF NOT EXISTS `TB_ALBUM_OBJETO` (
  `CD_ALBUM_OBJETO` INT NOT NULL AUTO_INCREMENT COMMENT 'PK dos objetos do Album' ,
  `CD_ALBUM` INT NULL COMMENT 'FK com o album' ,
  `CD_OBJETO` INT NULL COMMENT 'FK do objeto' ,
  `DS_CAPTION` VARCHAR(100) NULL COMMENT 'Resumo do objeto' ,
  `DS_ALBUM_OBJETO` VARCHAR(255) NULL COMMENT 'Descriçao do objeto' ,
  `DT_INCLUSAO` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data da inclusão no album' ,
  `FL_ATIVO` TINYINT NULL DEFAULT 1 COMMENT 'Flag de ativo\n0 - Destativado\nnull - Desativado\n1 - Ativo' ,
  `TP_PRIVACIDADE` TINYINT NULL DEFAULT 0 COMMENT 'Nível da privacidade\n0 - Somente o criador\n1 - Somente o círculo de amigos\n2 - Somente usuários logados\n3 - Público' ,
  PRIMARY KEY (`CD_ALBUM_OBJETO`) ,
  INDEX `TB_ALBUM_OBJETO_CD_OBJETO` (`CD_OBJETO` ASC) ,
  INDEX `TB_ALBUM_OBJETO_CD_ALBUM` (`CD_ALBUM` ASC) ,
  CONSTRAINT `TB_ALBUM_OBJETO_CD_OBJETO`
    FOREIGN KEY (`CD_OBJETO` )
    REFERENCES `TB_OBJETO` (`CD_OBJETO` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `TB_ALBUM_OBJETO_CD_ALBUM`
    FOREIGN KEY (`CD_ALBUM` )
    REFERENCES `TB_ALBUM` (`CD_ALBUM` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Objetos do Album' ;


-- -----------------------------------------------------
-- Table `TB_COMENTARIO_ALBUM_OBJETO`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `TB_COMENTARIO_ALBUM_OBJETO` ;

CREATE  TABLE IF NOT EXISTS `TB_COMENTARIO_ALBUM_OBJETO` (
  `CD_COMENTARIO_ALBUM_OBJETO` INT NOT NULL AUTO_INCREMENT COMMENT 'PK da relação entre objetos' ,
  `CD_COMENTARIO` INT NULL COMMENT 'FK do comentário' ,
  `CD_ALBUM_OBJETO` INT NULL COMMENT 'FK do objeto do album' ,
  `DT_INCLUSAO` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data de inclusão' ,
  PRIMARY KEY (`CD_COMENTARIO_ALBUM_OBJETO`) ,
  INDEX `CD_COMENTARIOTB_COMENTARIO_ALBUM_OBJETO_` (`CD_COMENTARIO` ASC) ,
  INDEX `CD_COMENTARIOTB_COMENTARIO_ALBUM_OBJETO_CD_ALBUM_OBJETO` (`CD_ALBUM_OBJETO` ASC) ,
  CONSTRAINT `CD_COMENTARIOTB_COMENTARIO_ALBUM_OBJETO_`
    FOREIGN KEY (`CD_COMENTARIO` )
    REFERENCES `TB_COMENTARIO` (`CD_COMENTARIO` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `CD_COMENTARIOTB_COMENTARIO_ALBUM_OBJETO_CD_ALBUM_OBJETO`
    FOREIGN KEY (`CD_ALBUM_OBJETO` )
    REFERENCES `TB_ALBUM_OBJETO` (`CD_ALBUM_OBJETO` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Comentários de objetos do Album' ;


-- -----------------------------------------------------
-- Table `TB_PESSOA_CONTATO`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `TB_PESSOA_CONTATO` ;

CREATE  TABLE IF NOT EXISTS `TB_PESSOA_CONTATO` (
  `CD_PESSOA_CONTATO` INT NOT NULL AUTO_INCREMENT COMMENT 'PK dos contatos da pessoa' ,
  `CD_PESSOA` INT NULL COMMENT 'FK da pessoa do contato' ,
  `TP_CONTATO` TINYINT NOT NULL COMMENT 'Tipo de contatos:\n0 - E-mail\n1 - Celular\n2 - Fax\n3 - Casa\n4 - Trabalho' ,
  `DT_INCLUSAO` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data da inclusão do contato' ,
  `DS_CONTATO` VARCHAR(100) NOT NULL COMMENT 'Conteúdo do contato:\n(99) 9999999 <-- Telefones\naaaaa@bbbb.ccc.ddd <-- E-mail' ,
  `FL_ATIVO` TINYINT NULL DEFAULT 1 COMMENT 'Flag de ativo\n0 - Destativado\nnull - Desativado\n1 - Ativo' ,
  `TP_PRIVACIDADE` TINYINT NOT NULL DEFAULT 0 COMMENT 'Nível da privacidade\n0 - Somente o criador\n1 - Somente o círculo de amigos\n2 - Somente usuários logados\n3 - Público' ,
  PRIMARY KEY (`CD_PESSOA_CONTATO`) ,
  INDEX `TB_PESSOA_CONTATO_CD_PESSOA` (`CD_PESSOA` ASC) ,
  CONSTRAINT `TB_PESSOA_CONTATO_CD_PESSOA`
    FOREIGN KEY (`CD_PESSOA` )
    REFERENCES `TB_PESSOA` (`CD_PESSOA` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Contatos da pessoa' ;


-- -----------------------------------------------------
-- Table `TB_LOGRADOURO`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `TB_LOGRADOURO` ;

CREATE  TABLE IF NOT EXISTS `TB_LOGRADOURO` (
  `CD_LOGRADOURO` INT NOT NULL AUTO_INCREMENT ,
  `DS_SIGLA` VARCHAR(20) NULL COMMENT 'Sigla do Logradouro' ,
  `NM_LOGRADOURO` VARCHAR(30) NULL COMMENT 'Nome completo do logradouro' ,
  PRIMARY KEY (`CD_LOGRADOURO`) )
ENGINE = InnoDB, 
COMMENT = 'Tipos de Logradouros' ;


-- -----------------------------------------------------
-- Table `TB_UF`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `TB_UF` ;

CREATE  TABLE IF NOT EXISTS `TB_UF` (
  `CD_UF` INT NOT NULL AUTO_INCREMENT COMMENT 'PK das UFs' ,
  `NM_UF` VARCHAR(50) NULL COMMENT 'Nome completo da UF' ,
  `DS_SIGLA` VARCHAR(2) NULL COMMENT 'Sigla da UF' ,
  PRIMARY KEY (`CD_UF`) ,
  INDEX `DS_SIGLA` (`DS_SIGLA` ASC) )
ENGINE = InnoDB, 
COMMENT = 'UF - Unidade da Federacao' ;


-- -----------------------------------------------------
-- Table `TB_CIDADE`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `TB_CIDADE` ;

CREATE  TABLE IF NOT EXISTS `TB_CIDADE` (
  `CD_CIDADE` INT NOT NULL AUTO_INCREMENT COMMENT 'PK das cidades' ,
  `CD_UF` INT NULL COMMENT 'UF a que a cidade pertence' ,
  `NM_CIDADE` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`CD_CIDADE`) ,
  INDEX `TB_CIDADE_CD_UF` (`CD_UF` ASC) ,
  CONSTRAINT `TB_CIDADE_CD_UF`
    FOREIGN KEY (`CD_UF` )
    REFERENCES `TB_UF` (`CD_UF` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Tabela de cidades' ;


-- -----------------------------------------------------
-- Table `TB_CEP`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `TB_CEP` ;

CREATE  TABLE IF NOT EXISTS `TB_CEP` (
  `CD_CEP` INT NOT NULL AUTO_INCREMENT COMMENT 'PK do CEP' ,
  `NU_CEP` INT NOT NULL COMMENT 'Número do CEP' ,
  PRIMARY KEY (`CD_CEP`) )
ENGINE = InnoDB, 
COMMENT = 'Tabela de CEP' ;


-- -----------------------------------------------------
-- Table `TB_PESSOA_ENDERECO`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `TB_PESSOA_ENDERECO` ;

CREATE  TABLE IF NOT EXISTS `TB_PESSOA_ENDERECO` (
  `CD_PESSOA_ENDERECO` INT NOT NULL AUTO_INCREMENT COMMENT 'PK do endereço da pessoa' ,
  `CD_PESSOA` INT NULL COMMENT 'FK da pessoa' ,
  `TP_ENDERECO` TINYINT NOT NULL DEFAULT 0 COMMENT 'Tipo de endreço:\n0 - Outros\n1 - Residência\n2 - Trabalho\n3 - Correspondência\n' ,
  `NU_CEP` INT NULL COMMENT 'CEP do endereço' ,
  `CD_CEP` INT NULL COMMENT 'FK do CEP se for escolhido do sistema' ,
  `CD_LOGRADOURO` INT NULL COMMENT 'FK do tipo de logradouro' ,
  `DS_ENDERECO` VARCHAR(100) NOT NULL COMMENT 'Conteúdo do endereço' ,
  `NU_NUMERO` VARCHAR(20) NULL COMMENT 'Número do endereço\n' ,
  `DS_COMPLEMENTO` VARCHAR(50) NULL COMMENT 'Complemento do Endereço' ,
  `DS_BAIRRO` VARCHAR(50) NULL COMMENT 'Bairro' ,
  `CD_CIDADE` INT NULL ,
  `CD_UF` INT NULL ,
  `DT_INCLUSAO` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `FL_ATIVO` TINYINT NULL COMMENT 'Flag de ativo\n0 - Destativado\nnull - Desativado\n1 - Ativo' ,
  `TP_PRIVACIDADE` TINYINT NOT NULL DEFAULT 0 COMMENT 'Nível da privacidade\n0 - Somente o criador\n1 - Somente o círculo de amigos\n2 - Somente usuários logados\n3 - Público' ,
  `FL_CORRESPONDENCIA` TINYINT NULL DEFAULT 0 COMMENT 'Se o endereço é de correspondência o TP_ENDERECO tem prevalência' ,
  PRIMARY KEY (`CD_PESSOA_ENDERECO`) ,
  INDEX `TB_PESSOA_ENDERECO_CD_PESSOA` (`CD_PESSOA` ASC) ,
  INDEX `TB_PESSOA_ENDERECO_CD_LOGRADOURO` (`CD_LOGRADOURO` ASC) ,
  INDEX `TB_PESSOA_ENDERECO_CD_UF` (`CD_UF` ASC) ,
  INDEX `TB_PESSOA_ENDERECO_CD_CIDADE` (`CD_CIDADE` ASC) ,
  INDEX `TB_PESSOA_ENDERECO_CD_CEP` (`CD_CEP` ASC) ,
  CONSTRAINT `TB_PESSOA_ENDERECO_CD_PESSOA`
    FOREIGN KEY (`CD_PESSOA` )
    REFERENCES `TB_PESSOA` (`CD_PESSOA` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `TB_PESSOA_ENDERECO_CD_LOGRADOURO`
    FOREIGN KEY (`CD_LOGRADOURO` )
    REFERENCES `TB_LOGRADOURO` (`CD_LOGRADOURO` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `TB_PESSOA_ENDERECO_CD_UF`
    FOREIGN KEY (`CD_UF` )
    REFERENCES `TB_UF` (`CD_UF` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `TB_PESSOA_ENDERECO_CD_CIDADE`
    FOREIGN KEY (`CD_CIDADE` )
    REFERENCES `TB_CIDADE` (`CD_CIDADE` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `TB_PESSOA_ENDERECO_CD_CEP`
    FOREIGN KEY (`CD_CEP` )
    REFERENCES `TB_CEP` (`CD_CEP` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Endereços da pessoa' ;


-- -----------------------------------------------------
-- Table `TB_PUBLICACAO`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `TB_PUBLICACAO` ;

CREATE  TABLE IF NOT EXISTS `TB_PUBLICACAO` (
  `CD_PUBLICACAO` INT NOT NULL AUTO_INCREMENT COMMENT 'PK da Publicação' ,
  `CD_PESSOA` INT NULL COMMENT 'FK da pessoa dono da publicação' ,
  `TP_PUBLICACAO` TINYINT NOT NULL DEFAULT 0 COMMENT 'Tipo de publicação:\n0 - Texto\n1 - Artigo\n2 - Tese\n3 - Pesquisa\n4 - Trabalho\n' ,
  `NM_PUBLICACAO` VARCHAR(200) NOT NULL COMMENT 'Nome da Publicação' ,
  `DS_RESUMO` VARCHAR(255) NULL COMMENT 'Resumo da publicação' ,
  `DS_PUBLICACAO` TEXT NULL COMMENT 'Conteúdo da publicação. Pode ter somente anexos' ,
  `DT_INCLUSAO` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data e hora da inclusão' ,
  `FL_ATIVO` TINYINT NOT NULL DEFAULT 1 COMMENT 'Flag de ativo\n0 - Destativado\nnull - Desativado\n1 - Ativo' ,
  `TP_PRIVACIDADE` TINYINT NULL DEFAULT 0 COMMENT 'Nível da privacidade\n0 - Somente o criador\n1 - Somente o círculo de amigos\n2 - Somente usuários logados\n3 - Público' ,
  `DT_ORIGINAL` DATE NULL COMMENT 'Data original' ,
  PRIMARY KEY (`CD_PUBLICACAO`) ,
  INDEX `TB_PUBLICACAO_CD_PESSOA` (`CD_PESSOA` ASC) ,
  INDEX `NM_PUBLICACAO` (`NM_PUBLICACAO` ASC) ,
  CONSTRAINT `TB_PUBLICACAO_CD_PESSOA`
    FOREIGN KEY (`CD_PESSOA` )
    REFERENCES `TB_PESSOA` (`CD_PESSOA` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Publicações da Pessoa' ;


-- -----------------------------------------------------
-- Table `TB_PUBLICACAO_ANEXO`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `TB_PUBLICACAO_ANEXO` ;

CREATE  TABLE IF NOT EXISTS `TB_PUBLICACAO_ANEXO` (
  `CD_PUBLICACAO_ANEXO` INT NOT NULL AUTO_INCREMENT COMMENT 'PK do anexo da publicação' ,
  `CD_PUBLICACAO` INT NULL COMMENT 'FK da Publicação' ,
  `CD_OBJETO` INT NULL COMMENT 'FK do objeto' ,
  `NM_PUBLICACAO_ANEXO` VARCHAR(100) NULL COMMENT 'Nome do anexo' ,
  `DS_RESUMO` VARCHAR(255) NULL COMMENT 'Resumo do anexo' ,
  `DT_INCLUSAO` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data de inclusão' ,
  `FL_ATIVO` TINYINT NULL DEFAULT 1 COMMENT 'Flag de ativo\n0 - Destativado\nnull - Desativado\n1 - Ativo' ,
  `TP_PRIVACIDADE` TINYINT NULL DEFAULT 0 COMMENT 'Nível da privacidade\n0 - Somente o criador\n1 - Somente o círculo de amigos\n2 - Somente usuários logados\n3 - Público' ,
  PRIMARY KEY (`CD_PUBLICACAO_ANEXO`) ,
  INDEX `TB_PUBLICACAO_ANEXO_CD_PUBLICACAO` (`CD_PUBLICACAO` ASC) ,
  INDEX `TB_PUBLICACAO_ANEXO_CD_OBJETO` (`CD_OBJETO` ASC) ,
  CONSTRAINT `TB_PUBLICACAO_ANEXO_CD_PUBLICACAO`
    FOREIGN KEY (`CD_PUBLICACAO` )
    REFERENCES `TB_PUBLICACAO` (`CD_PUBLICACAO` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `TB_PUBLICACAO_ANEXO_CD_OBJETO`
    FOREIGN KEY (`CD_OBJETO` )
    REFERENCES `TB_OBJETO` (`CD_OBJETO` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Anexos das publicação' ;


-- -----------------------------------------------------
-- Table `TB_COMENTARIO_PUBLICACAO`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `TB_COMENTARIO_PUBLICACAO` ;

CREATE  TABLE IF NOT EXISTS `TB_COMENTARIO_PUBLICACAO` (
  `CD_COMENTARIO_PUBLICACAO` INT NOT NULL AUTO_INCREMENT COMMENT 'PK do comentário da publicação' ,
  `CD_COMENTARIO` INT NULL COMMENT 'FK do comentário' ,
  `CD_PUBLICACAO` INT NULL COMMENT 'FK da publicação' ,
  PRIMARY KEY (`CD_COMENTARIO_PUBLICACAO`) ,
  INDEX `TB_COMENTARIO_PUBLICACAO_CD_COMENTARIO` (`CD_COMENTARIO` ASC) ,
  INDEX `TB_COMENTARIO_PUBLICACAO_CD_PUBLICACAO` (`CD_PUBLICACAO` ASC) ,
  CONSTRAINT `TB_COMENTARIO_PUBLICACAO_CD_COMENTARIO`
    FOREIGN KEY (`CD_COMENTARIO` )
    REFERENCES `TB_COMENTARIO` (`CD_COMENTARIO` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `TB_COMENTARIO_PUBLICACAO_CD_PUBLICACAO`
    FOREIGN KEY (`CD_PUBLICACAO` )
    REFERENCES `TB_PUBLICACAO` (`CD_PUBLICACAO` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Comentários de uma publicação' ;


-- -----------------------------------------------------
-- Table `TB_COLEGA`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `TB_COLEGA` ;

CREATE  TABLE IF NOT EXISTS `TB_COLEGA` (
  `CD_COLEGA` INT NOT NULL AUTO_INCREMENT COMMENT 'PK dos colegas da pessoa' ,
  `CD_PESSOA` INT NULL COMMENT 'FK da pessoa' ,
  `CD_PESSOA_COLEGA` INT NULL COMMENT 'FK do colega da pessoa' ,
  `NM_APELIDO` VARCHAR(45) NULL COMMENT 'Apelido do colega' ,
  `DT_INCLUSAO` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data de inclusão' ,
  `FL_ATIVO` TINYINT NULL DEFAULT 1 COMMENT 'Flag de ativo\n0 - Destativado\nnull - Desativado\n1 - Ativo' ,
  PRIMARY KEY (`CD_COLEGA`) ,
  INDEX `TB_COLEGA_CD_PESSOA` (`CD_PESSOA` ASC) ,
  INDEX `TB_COLEGA_CD_PESSOA_COLEGA` (`CD_PESSOA_COLEGA` ASC) ,
  CONSTRAINT `TB_COLEGA_CD_PESSOA`
    FOREIGN KEY (`CD_PESSOA` )
    REFERENCES `TB_PESSOA` (`CD_PESSOA` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `TB_COLEGA_CD_PESSOA_COLEGA`
    FOREIGN KEY (`CD_PESSOA_COLEGA` )
    REFERENCES `TB_PESSOA` (`CD_PESSOA` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Tabela de colegas' ;


-- -----------------------------------------------------
-- Table `TB_ESPECIALIDADE`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `TB_ESPECIALIDADE` ;

CREATE  TABLE IF NOT EXISTS `TB_ESPECIALIDADE` (
  `CD_ESPECIALIDADE` INT NOT NULL AUTO_INCREMENT ,
  `DS_SIGLA` VARCHAR(20) NULL COMMENT 'Sigla da especialidade médica' ,
  `NM_ESPECIALIDADE` VARCHAR(100) NULL COMMENT 'Nome da especialidade' ,
  `FL_ATIVO` TINYINT NULL DEFAULT 0 COMMENT 'Flag de ativo\n0 - Destativado\nnull - Desativado\n1 - Ativo' ,
  PRIMARY KEY (`CD_ESPECIALIDADE`) )
ENGINE = InnoDB, 
COMMENT = 'Tabela de despecialidades' ;


-- -----------------------------------------------------
-- Table `TB_SALA`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `TB_SALA` ;

CREATE  TABLE IF NOT EXISTS `TB_SALA` (
  `CD_SALA` INT NOT NULL AUTO_INCREMENT COMMENT 'PK da sala de discussão' ,
  `CD_PESSOA` INT NULL COMMENT 'PK da pessoa que criou' ,
  `CD_ESPECIALIDADE` INT NULL ,
  `TP_SALA` TINYINT NOT NULL DEFAULT 0 COMMENT 'Tipo de Sala\n0 - Sala\n1 - Auditório' ,
  `NM_SALA` VARCHAR(100) NOT NULL COMMENT 'Nome da sala' ,
  `DS_RESUMO` VARCHAR(255) NOT NULL COMMENT 'Resumo do tema da sala' ,
  `DS_SALA` TEXT NULL COMMENT 'Descrição da sala' ,
  `DT_INCLUSAO` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Inclusão da sala' ,
  `DT_INICIO` DATETIME NULL COMMENT 'Data e hora de início das atividades' ,
  `DT_FIM` DATETIME NULL COMMENT 'Data e hora do fim das atividades' ,
  `DS_RESULTADO` TEXT NULL COMMENT 'Resultado das discussões' ,
  `FL_ESTADO` TINYINT NOT NULL DEFAULT 0 COMMENT 'Flag de estado:\n0 - Aberta\n1 - Pausada\n2 - Fechada' ,
  `FL_ATIVO` TINYINT NULL DEFAULT 1 COMMENT 'Flag de ativo\n0 - Destativado\nnull - Desativado\n1 - Ativo' ,
  `TP_PRIVACIDADE` TINYINT NOT NULL DEFAULT 0 COMMENT 'Nível da privacidade\n0 - Somente o criador\n1 - Somente o círculo de amigos\n2 - Somente usuários logados\n3 - Público\n4 - Somente convidados\n5 - Invisível (adefinir)' ,
  PRIMARY KEY (`CD_SALA`) ,
  INDEX `IX_SALA_CD_PESSOA` (`CD_PESSOA` ASC) ,
  INDEX `IX_SALA_CD_ESPECIALIDADE` (`CD_ESPECIALIDADE` ASC) ,
  INDEX `IX_SALA_DT_INCLUSAO` (`DT_INCLUSAO` ASC) ,
  INDEX `IX_SALA_DT_INICIO` (`DT_INICIO` ASC) ,
  CONSTRAINT `FK_SALA_CD_PESSOA`
    FOREIGN KEY (`CD_PESSOA` )
    REFERENCES `TB_PESSOA` (`CD_PESSOA` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_SALA_CD_ESPECIALIDADE`
    FOREIGN KEY (`CD_ESPECIALIDADE` )
    REFERENCES `TB_ESPECIALIDADE` (`CD_ESPECIALIDADE` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Salas de discussão' ;


-- -----------------------------------------------------
-- Table `TB_SALA_PESSOAS`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `TB_SALA_PESSOAS` ;

CREATE  TABLE IF NOT EXISTS `TB_SALA_PESSOAS` (
  `CD_SALA_PESSOAS` INT NOT NULL AUTO_INCREMENT ,
  `CD_SALA` INT NULL COMMENT 'FK da Sala' ,
  `CD_PESSOA` INT NULL COMMENT 'FK da pessoa' ,
  `DT_INCLUSAO` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data e hora da inclusão' ,
  `FL_ATIVO` TINYINT NULL DEFAULT 1 COMMENT 'Flag de ativo\n0 - Destativado\nnull - Desativado\n1 - Ativo' ,
  `TP_PRIVACIDADE` TINYINT NULL DEFAULT 0 COMMENT 'Nível da privacidade\n0 - Somente o criador\n1 - Somente o círculo de amigos\n2 - Somente usuários logados\n3 - Público\n4 - Somente os participantes da sala' ,
  PRIMARY KEY (`CD_SALA_PESSOAS`) ,
  INDEX `TB_SALA_PESSOAS_CD_PESSOA` (`CD_PESSOA` ASC) ,
  INDEX `TB_SALA_PESSOAS_CD_SALA` (`CD_SALA` ASC) ,
  CONSTRAINT `TB_SALA_PESSOAS_CD_PESSOA`
    FOREIGN KEY (`CD_PESSOA` )
    REFERENCES `TB_PESSOA` (`CD_PESSOA` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `TB_SALA_PESSOAS_CD_SALA`
    FOREIGN KEY (`CD_SALA` )
    REFERENCES `TB_SALA` (`CD_SALA` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Participantes da sala' ;


-- -----------------------------------------------------
-- Table `TB_SALA_CONVITE`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `TB_SALA_CONVITE` ;

CREATE  TABLE IF NOT EXISTS `TB_SALA_CONVITE` (
  `CD_SALA_CONVITE` INT NOT NULL AUTO_INCREMENT COMMENT 'PK dos convites para particpar de uma sala' ,
  `CD_SALA` INT NULL COMMENT 'FK da sala' ,
  `CD_PESSOA` INT NULL COMMENT 'FK da pessoa' ,
  `DS_JUSTIFICATIVA` VARCHAR(255) NULL COMMENT 'Justificativa do convite' ,
  `DT_INCLUSAO` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data da criação do pedido' ,
  `DT_ULTIMO_ENVIO` DATETIME NULL COMMENT 'Data e hora do último envio' ,
  `NU_VISUALIZACAO` VARCHAR(45) NULL DEFAULT 0 COMMENT 'Vez que o convite foi visualizado' ,
  `TP_ACEITE` TINYINT NULL COMMENT 'Tipo do aceite\nnull - indefinido\n0 - Não aceitou\n1 - Aceitou' ,
  `DT_ACEITE` DATETIME NULL COMMENT 'Data do aceite' ,
  `DS_ACEITE` VARCHAR(255) NULL COMMENT 'Comentários do aceite' ,
  PRIMARY KEY (`CD_SALA_CONVITE`) ,
  INDEX `TB_SALA_CONVITE_CD_SALA` (`CD_SALA` ASC) ,
  INDEX `TB_SALA_CONVITE_CD_PESSOA` (`CD_PESSOA` ASC) ,
  CONSTRAINT `TB_SALA_CONVITE_CD_SALA`
    FOREIGN KEY (`CD_SALA` )
    REFERENCES `TB_SALA` (`CD_SALA` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `TB_SALA_CONVITE_CD_PESSOA`
    FOREIGN KEY (`CD_PESSOA` )
    REFERENCES `TB_PESSOA` (`CD_PESSOA` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Convite para participar de uma sala' ;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

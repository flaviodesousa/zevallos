-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 31, 2011 at 11:33 PM
-- Server version: 5.1.46
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `lugarmedico`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_album`
--

CREATE TABLE IF NOT EXISTS `tb_album` (
  `CD_ALBUM` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK do album de objetos',
  `CD_PESSOA` int(11) DEFAULT NULL COMMENT 'FK da pessoa que criou o album',
  `NM_ALBUM` varchar(100) NOT NULL COMMENT 'Nome do album',
  `DS_ALBUM` varchar(255) DEFAULT NULL COMMENT 'Descrição do album',
  `DT_INCLUSAO` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data de criação do album',
  `FL_ATIVO` tinyint(4) DEFAULT '1' COMMENT 'Flag de ativo\n0 - Destativado\nnull - Desativado\n1 - Ativo',
  `TP_PRIVACIDADE` tinyint(4) DEFAULT '0' COMMENT 'Nível da privacidade\n0 - Somente o criador\n1 - Somente o círculo de amigos\n2 - Somente usuários logados\n3 - Público',
  PRIMARY KEY (`CD_ALBUM`),
  KEY `TB_ALBUM_CD_PESSOA` (`CD_PESSOA`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Album de objetos' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tb_album`
--


-- --------------------------------------------------------

--
-- Table structure for table `tb_album_objeto`
--

CREATE TABLE IF NOT EXISTS `tb_album_objeto` (
  `CD_ALBUM_OBJETO` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK dos objetos do Album',
  `CD_ALBUM` int(11) DEFAULT NULL COMMENT 'FK com o album',
  `CD_OBJETO` int(11) DEFAULT NULL COMMENT 'FK do objeto',
  `DS_CAPTION` varchar(100) DEFAULT NULL COMMENT 'Resumo do objeto',
  `DS_ALBUM_OBJETO` varchar(255) DEFAULT NULL COMMENT 'Descriçao do objeto',
  `DT_INCLUSAO` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data da inclusão no album',
  `FL_ATIVO` tinyint(4) DEFAULT '1' COMMENT 'Flag de ativo\n0 - Destativado\nnull - Desativado\n1 - Ativo',
  `TP_PRIVACIDADE` tinyint(4) DEFAULT '0' COMMENT 'Nível da privacidade\n0 - Somente o criador\n1 - Somente o círculo de amigos\n2 - Somente usuários logados\n3 - Público',
  PRIMARY KEY (`CD_ALBUM_OBJETO`),
  KEY `TB_ALBUM_OBJETO_CD_OBJETO` (`CD_OBJETO`),
  KEY `TB_ALBUM_OBJETO_CD_ALBUM` (`CD_ALBUM`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Objetos do Album' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tb_album_objeto`
--


-- --------------------------------------------------------

--
-- Table structure for table `tb_browser`
--

CREATE TABLE IF NOT EXISTS `tb_browser` (
  `CD_BROWSER` int(11) NOT NULL AUTO_INCREMENT,
  `NM_BROWSER` varchar(50) NOT NULL COMMENT 'Nome completo do Browser',
  `DS_SIGLA` varchar(10) NOT NULL COMMENT 'Sigla do Browser',
  `DS_URL` varchar(100) DEFAULT NULL COMMENT 'URL do site do Browser',
  PRIMARY KEY (`CD_BROWSER`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabela de todos os Browsers' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tb_browser`
--


-- --------------------------------------------------------

--
-- Table structure for table `tb_cep`
--

CREATE TABLE IF NOT EXISTS `tb_cep` (
  `CD_CEP` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK do CEP',
  `NU_CEP` int(11) NOT NULL COMMENT 'Número do CEP',
  PRIMARY KEY (`CD_CEP`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabela de CEP' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tb_cep`
--


-- --------------------------------------------------------

--
-- Table structure for table `tb_cidade`
--

CREATE TABLE IF NOT EXISTS `tb_cidade` (
  `CD_CIDADE` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK das cidades',
  `CD_UF` int(11) DEFAULT NULL COMMENT 'UF a que a cidade pertence',
  `NM_CIDADE` varchar(100) NOT NULL,
  PRIMARY KEY (`CD_CIDADE`),
  KEY `TB_CIDADE_CD_UF` (`CD_UF`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabela de cidades' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tb_cidade`
--


-- --------------------------------------------------------

--
-- Table structure for table `tb_circulo`
--

CREATE TABLE IF NOT EXISTS `tb_circulo` (
  `CD_CIRCULO` int(11) NOT NULL AUTO_INCREMENT,
  `CD_PESSOA` int(11) DEFAULT NULL COMMENT 'FK da pessoa',
  `NM_CIRCULO` varchar(100) NOT NULL COMMENT 'Nome do círculo',
  `DS_CIRCULO` varchar(255) DEFAULT NULL COMMENT 'Descrição do círculo',
  `DT_INCLUSAO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `FL_ATIVO` tinyint(4) DEFAULT '1' COMMENT 'Flag de ativo\n0 - Destativado\nnull - Desativado\n1 - Ativo',
  PRIMARY KEY (`CD_CIRCULO`),
  KEY `TB_CIRCULO_CD_PESSOA` (`CD_PESSOA`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Círculo de colegas' AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tb_circulo`
--

INSERT INTO `tb_circulo` (`CD_CIRCULO`, `CD_PESSOA`, `NM_CIRCULO`, `DS_CIRCULO`, `DT_INCLUSAO`, `FL_ATIVO`) VALUES
(1, 1, 'Geral', 'Geral', '2011-03-29 11:43:12', 1),
(2, 2, 'Geral', 'Geral', '2011-03-29 11:43:12', 1),
(3, 3, 'Geral', 'Geral', '2011-03-29 12:09:53', 1),
(4, 1, 'Brasília', 'Amigos de Brasília', '2011-03-29 14:09:45', 1),
(5, 2, 'Goiania', 'Goiania', '2011-03-29 17:23:23', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_circulo_pessoa`
--

CREATE TABLE IF NOT EXISTS `tb_circulo_pessoa` (
  `CD_CIRCULO_PESSOA` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK das pessoas do Círculo',
  `CD_CIRCULO` int(11) DEFAULT NULL COMMENT 'FK do círculo',
  `CD_PESSOA` int(11) DEFAULT NULL,
  `FL_ATIVO` tinyint(4) DEFAULT '1' COMMENT 'Flag de ativo\n0 - Destativado\nnull - Desativado\n1 - Ativo',
  `DT_INCLUSAO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data de inclusão',
  PRIMARY KEY (`CD_CIRCULO_PESSOA`),
  KEY `TB_CIRCULO_PESSOA_CD_CIRCULO` (`CD_CIRCULO`),
  KEY `TB_CIRCULO_PESSOA_CD_PESSOA` (`CD_PESSOA`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Pessoas do círculo' AUTO_INCREMENT=9 ;

--
-- Dumping data for table `tb_circulo_pessoa`
--

INSERT INTO `tb_circulo_pessoa` (`CD_CIRCULO_PESSOA`, `CD_CIRCULO`, `CD_PESSOA`, `FL_ATIVO`, `DT_INCLUSAO`) VALUES
(1, 1, 2, 1, '2011-03-29 11:43:51'),
(2, 2, 1, 1, '2011-03-29 11:43:51'),
(3, 1, 3, 1, '2011-03-29 12:10:22'),
(4, 3, 2, 1, '2011-03-29 12:10:22'),
(5, 4, 3, 1, '2011-03-29 14:10:05'),
(6, 5, 1, 1, '2011-03-29 17:23:42'),
(7, 2, 3, 1, '2011-03-29 17:26:52'),
(8, 1, 4, 1, '2011-03-29 17:35:44');

-- --------------------------------------------------------

--
-- Table structure for table `tb_colega`
--

CREATE TABLE IF NOT EXISTS `tb_colega` (
  `CD_COLEGA` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK dos colegas da pessoa',
  `CD_PESSOA` int(11) DEFAULT NULL COMMENT 'FK da pessoa',
  `CD_PESSOA_COLEGA` int(11) DEFAULT NULL COMMENT 'FK do colega da pessoa',
  `NM_APELIDO` varchar(45) DEFAULT NULL COMMENT 'Apelido do colega',
  `DT_INCLUSAO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data de inclusão',
  `FL_ATIVO` tinyint(4) DEFAULT '1' COMMENT 'Flag de ativo\n0 - Destativado\nnull - Desativado\n1 - Ativo',
  PRIMARY KEY (`CD_COLEGA`),
  KEY `TB_COLEGA_CD_PESSOA` (`CD_PESSOA`),
  KEY `TB_COLEGA_CD_PESSOA_COLEGA` (`CD_PESSOA_COLEGA`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Tabela de colegas' AUTO_INCREMENT=7 ;

--
-- Dumping data for table `tb_colega`
--

INSERT INTO `tb_colega` (`CD_COLEGA`, `CD_PESSOA`, `CD_PESSOA_COLEGA`, `NM_APELIDO`, `DT_INCLUSAO`, `FL_ATIVO`) VALUES
(1, 1, 2, NULL, '2011-03-29 11:44:50', 1),
(2, 2, 1, NULL, '2011-03-29 11:44:50', 1),
(3, 3, 1, NULL, '2011-03-29 12:09:01', 1),
(4, 3, 2, NULL, '2011-03-29 12:09:01', 1),
(5, 1, 3, NULL, '2011-03-29 12:09:16', 1),
(6, 2, 3, NULL, '2011-03-29 12:09:16', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_colega_convite`
--

CREATE TABLE IF NOT EXISTS `tb_colega_convite` (
  `CD_COLEGA_CONVITE` int(11) NOT NULL AUTO_INCREMENT,
  `CD_PESSOA` int(11) DEFAULT NULL COMMENT 'FK da pessoa',
  `DS_JUSTIFICATIVA` varchar(255) DEFAULT NULL COMMENT 'Justificativa para o convite',
  `DT_INCLUSAO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data de inclusão',
  `DT_ULTIMO_ENVIO` datetime DEFAULT NULL COMMENT 'Data e hora do último envio',
  `NU_VISUALIZACAO` int(11) DEFAULT '0' COMMENT 'Quantidade de visualizações',
  `TP_ACEITE` tinyint(4) DEFAULT NULL COMMENT 'Tipo do aceite\nnull - indefinido\n0 - Não aceitou\n1 - Aceitou',
  `DT_ACEITE` datetime DEFAULT NULL COMMENT 'Data do aceite',
  `DS_ACEITE` varchar(255) DEFAULT NULL COMMENT 'Comentários para o aceite',
  PRIMARY KEY (`CD_COLEGA_CONVITE`),
  KEY `TB_COLEGA_CONVITE_CD_PESSOA` (`CD_PESSOA`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Convite de coletas' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tb_colega_convite`
--


-- --------------------------------------------------------

--
-- Table structure for table `tb_comentario`
--

CREATE TABLE IF NOT EXISTS `tb_comentario` (
  `CD_COMENTARIO` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK dos comentários',
  `CD_COMENTARIO_PAI` int(11) DEFAULT NULL COMMENT 'FK do comentário',
  `CD_PESSOA` int(11) DEFAULT NULL COMMENT 'FK da pessoa que comentou',
  `CD_PESSOA_ORIGINAL` int(11) DEFAULT NULL COMMENT 'FK da pessoa que originou os comentários',
  `NM_COMENTARIO` varchar(100) DEFAULT NULL COMMENT 'Título do comentário',
  `DS_COMENTARIO` text COMMENT 'Conteúdo do comentário',
  `FL_FILHO` tinyint(4) DEFAULT NULL COMMENT 'Null ou 0 - Se não tem\n1 - Se tem filho',
  `DT_INCLUSAO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Inclusão do comentário',
  `FL_ATIVO` tinyint(4) DEFAULT '1' COMMENT 'Flag de ativo\n0 - Destativado\nnull - Desativado\n1 - Ativo',
  PRIMARY KEY (`CD_COMENTARIO`),
  KEY `TB_COMENTARIO_CD_PESSOA` (`CD_PESSOA`),
  KEY `TB_COMENTARIO_CD_COMENTARIO_PAI` (`CD_COMENTARIO_PAI`),
  KEY `TB_COMENTARIO_CD_PESSOA_ORIGINAL` (`CD_PESSOA_ORIGINAL`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Comentários dos objetos' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tb_comentario`
--


-- --------------------------------------------------------

--
-- Table structure for table `tb_comentario_album_objeto`
--

CREATE TABLE IF NOT EXISTS `tb_comentario_album_objeto` (
  `CD_COMENTARIO_ALBUM_OBJETO` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK da relação entre objetos',
  `CD_COMENTARIO` int(11) DEFAULT NULL COMMENT 'FK do comentário',
  `CD_ALBUM_OBJETO` int(11) DEFAULT NULL COMMENT 'FK do objeto do album',
  `DT_INCLUSAO` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data de inclusão',
  PRIMARY KEY (`CD_COMENTARIO_ALBUM_OBJETO`),
  KEY `CD_COMENTARIOTB_COMENTARIO_ALBUM_OBJETO_` (`CD_COMENTARIO`),
  KEY `CD_COMENTARIOTB_COMENTARIO_ALBUM_OBJETO_CD_ALBUM_OBJETO` (`CD_ALBUM_OBJETO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Comentários de objetos do Album' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tb_comentario_album_objeto`
--


-- --------------------------------------------------------

--
-- Table structure for table `tb_comentario_publicacao`
--

CREATE TABLE IF NOT EXISTS `tb_comentario_publicacao` (
  `CD_COMENTARIO_PUBLICACAO` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK do comentário da publicação',
  `CD_COMENTARIO` int(11) DEFAULT NULL COMMENT 'FK do comentário',
  `CD_PUBLICACAO` int(11) DEFAULT NULL COMMENT 'FK da publicação',
  PRIMARY KEY (`CD_COMENTARIO_PUBLICACAO`),
  KEY `TB_COMENTARIO_PUBLICACAO_CD_COMENTARIO` (`CD_COMENTARIO`),
  KEY `TB_COMENTARIO_PUBLICACAO_CD_PUBLICACAO` (`CD_PUBLICACAO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Comentários de uma publicação' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tb_comentario_publicacao`
--


-- --------------------------------------------------------

--
-- Table structure for table `tb_convite`
--

CREATE TABLE IF NOT EXISTS `tb_convite` (
  `CD_CONVITE` int(11) NOT NULL AUTO_INCREMENT,
  `DS_EMAIL` varchar(100) NOT NULL COMMENT 'E-mail do convidado',
  `NM_PESSOA` varchar(50) NOT NULL COMMENT 'Nome do convidado',
  `NM_PRIMEIRO` varchar(30) NOT NULL,
  `NM_ULTIMO` varchar(30) DEFAULT NULL,
  `DS_CONSELHO` varchar(20) DEFAULT NULL COMMENT 'Número do conselho',
  `DT_INCLUSAO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `FL_ATIVO` tinyint(4) DEFAULT '1' COMMENT 'Flag de ativo\n0 - Destativado\nnull - Desativado\n1 - Ativo',
  `QT_VISUALIZACOES` tinyint(4) DEFAULT '0' COMMENT 'Quantidade de vezes que o convite foi visto',
  `FL_ACEITE` tinyint(4) DEFAULT NULL COMMENT 'Flag de aceite\nnull  - Não se pronunciou\n0 - Não aceitou\n1 - Aceitou\n',
  `DT_VISUALIZACAO` datetime DEFAULT NULL COMMENT 'Data e hora que ele foi visualizado pela última vez',
  `DT_ACEITE` datetime DEFAULT NULL COMMENT 'Data e hora do aceite',
  PRIMARY KEY (`CD_CONVITE`),
  KEY `TB_CONVITE_DS_EMAIL` (`DS_EMAIL`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Convites para participar do Lugar Médico' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tb_convite`
--

INSERT INTO `tb_convite` (`CD_CONVITE`, `DS_EMAIL`, `NM_PESSOA`, `NM_PRIMEIRO`, `NM_ULTIMO`, `DS_CONSELHO`, `DT_INCLUSAO`, `FL_ATIVO`, `QT_VISUALIZACOES`, `FL_ACEITE`, `DT_VISUALIZACAO`, `DT_ACEITE`) VALUES
(1, 'zevallos@zevallos.com.br', 'Ruben Zevallos', 'Ruben', 'Zevallos', '123123', '2011-03-22 13:58:04', 1, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_convite_pessoa`
--

CREATE TABLE IF NOT EXISTS `tb_convite_pessoa` (
  `CD_CONVITE_PESSOA` int(11) NOT NULL AUTO_INCREMENT,
  `CD_CONVITE` int(11) DEFAULT NULL,
  `CD_PESSOA` int(11) DEFAULT NULL,
  `FL_PRIMEIRO` tinyint(4) DEFAULT '0' COMMENT '0 - Não é o convite principal\n1 - Foi o 1o convite',
  `DT_INCLUSAO` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `FL_ATIVO` tinyint(4) DEFAULT '1' COMMENT 'Flag de ativo\n0 - Destativado\nnull - Desativado\n1 - Ativo',
  PRIMARY KEY (`CD_CONVITE_PESSOA`),
  KEY `TB_CONVITE_PESSOA_CD_PESSOA` (`CD_PESSOA`),
  KEY `TB_CONVITE_PESSOA_CD_CONVITE` (`CD_CONVITE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Convite de alguma pessoa para amigo externo' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tb_convite_pessoa`
--


-- --------------------------------------------------------

--
-- Table structure for table `tb_dispositivo`
--

CREATE TABLE IF NOT EXISTS `tb_dispositivo` (
  `CD_DISPOSITIVO` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK dos dispositivos',
  `NM_DISPOSITIVO` varchar(100) NOT NULL COMMENT 'Nome completo do dispositivo',
  `DS_SIGLA` varchar(20) NOT NULL COMMENT 'Sigla do dispositivo',
  PRIMARY KEY (`CD_DISPOSITIVO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabela de tipos de dispositivos' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tb_dispositivo`
--


-- --------------------------------------------------------

--
-- Table structure for table `tb_especialidade`
--

CREATE TABLE IF NOT EXISTS `tb_especialidade` (
  `CD_ESPECIALIDADE` int(11) NOT NULL AUTO_INCREMENT,
  `DS_SIGLA` varchar(20) DEFAULT NULL COMMENT 'Sigla da especialidade médica',
  `NM_ESPECIALIDADE` varchar(100) DEFAULT NULL COMMENT 'Nome da especialidade',
  `FL_ATIVO` tinyint(4) DEFAULT '0' COMMENT 'Flag de ativo\n0 - Destativado\nnull - Desativado\n1 - Ativo',
  PRIMARY KEY (`CD_ESPECIALIDADE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabela de despecialidades' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tb_especialidade`
--


-- --------------------------------------------------------

--
-- Table structure for table `tb_logradouro`
--

CREATE TABLE IF NOT EXISTS `tb_logradouro` (
  `CD_LOGRADOURO` int(11) NOT NULL AUTO_INCREMENT,
  `DS_SIGLA` varchar(20) DEFAULT NULL COMMENT 'Sigla do Logradouro',
  `NM_LOGRADOURO` varchar(30) DEFAULT NULL COMMENT 'Nome completo do logradouro',
  PRIMARY KEY (`CD_LOGRADOURO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tipos de Logradouros' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tb_logradouro`
--


-- --------------------------------------------------------

--
-- Table structure for table `tb_objeto`
--

CREATE TABLE IF NOT EXISTS `tb_objeto` (
  `CD_OBJETO` int(11) NOT NULL AUTO_INCREMENT,
  `CD_PESSOA` int(11) DEFAULT NULL COMMENT 'FK da pessoa dona do objeto',
  `NM_OBJETO` varchar(50) NOT NULL COMMENT 'Nome do objeto',
  `DS_OBJETO` text COMMENT 'Descrição do objeto',
  `TP_OBJETO` tinyint(4) DEFAULT NULL COMMENT 'Tipo do objeto:\n0 - Imagem\n1 - Vídeo\n2 - Arquivo',
  `DS_URL` varchar(45) DEFAULT NULL COMMENT 'URL para visualização do objeto',
  `DS_INCLUSAO` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data da inclusão do objeto',
  `FL_ATIVO` tinyint(4) DEFAULT '1' COMMENT 'Flag de ativo\n0 - Destativado\nnull - Desativado\n1 - Ativo',
  PRIMARY KEY (`CD_OBJETO`),
  KEY `TB_OBJETO_CD_PESSOA` (`CD_PESSOA`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Objetos do sistema' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tb_objeto`
--


-- --------------------------------------------------------

--
-- Table structure for table `tb_pessoa`
--

CREATE TABLE IF NOT EXISTS `tb_pessoa` (
  `CD_PESSOA` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK da pessoa',
  `CD_PREFIXO` int(11) DEFAULT NULL COMMENT 'Prefixo ao nome Ex. Dr. Sr. etc',
  `TP_TIPO` tinyint(4) NOT NULL COMMENT 'Tipo da pessoa:\n0 - Física\n1 - Jurídica',
  `NM_PESSOA` varchar(50) NOT NULL COMMENT 'Nome completo da pessoa',
  `NM_PRIMEIRO` varchar(30) NOT NULL COMMENT 'Primeiro nome',
  `NM_MEIO` char(1) DEFAULT NULL COMMENT 'Inicial do meio',
  `NM_ULTIMO` varchar(30) DEFAULT NULL COMMENT 'Último nome',
  `DS_CONSELHO` varchar(20) DEFAULT NULL COMMENT 'Número do conselho',
  `DS_URL_PERFIL` varchar(100) DEFAULT NULL COMMENT 'Complemento da URL do Perfil público da pessoa',
  `DS_EMAIL` varchar(100) NOT NULL COMMENT 'E-mail de contato e login',
  `DS_AVATAR` varchar(100) DEFAULT NULL COMMENT 'Imagem do Avatar - Null usuará o Gravatar',
  `DT_INCLUSAO` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data de inclusão',
  `FL_ATIVO` tinyint(4) DEFAULT '1' COMMENT 'Flag de ativo\n0 - Destativado\nnull - Desativado\n1 - Ativo',
  PRIMARY KEY (`CD_PESSOA`),
  KEY `TB_PESSOA_DS_EMAIL` (`DS_EMAIL`),
  KEY `TB_PESSOA_DS_NOME` (`NM_PESSOA`),
  KEY `TB_PESSOA_CD_PREFIXO` (`CD_PREFIXO`),
  KEY `TB_PESSOA_DS_URL_PERFIL` (`DS_URL_PERFIL`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Pessoas do sistema. Cada pessoa será única dentro da exist' AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tb_pessoa`
--

INSERT INTO `tb_pessoa` (`CD_PESSOA`, `CD_PREFIXO`, `TP_TIPO`, `NM_PESSOA`, `NM_PRIMEIRO`, `NM_MEIO`, `NM_ULTIMO`, `DS_CONSELHO`, `DS_URL_PERFIL`, `DS_EMAIL`, `DS_AVATAR`, `DT_INCLUSAO`, `FL_ATIVO`) VALUES
(1, NULL, 0, 'Ruben Zevallos', 'Ruben', NULL, 'Zevallos', '123123', NULL, 'zevallos@zevallos.com.br', '/avatar/df/Ruben_1.jpg', '2011-03-22 14:49:49', 1),
(2, NULL, 0, 'Vinicius Leonidas', 'Vinicius', NULL, 'Leonidas', '1234', NULL, 'vinnyspl@gmail.com', NULL, '2011-03-29 11:42:01', 1),
(3, NULL, 0, 'Antonio Carlos Abrantes', 'Antonio Carlos', NULL, 'Abrantes', '', NULL, 'cacauab@gmail.com', NULL, '2011-03-29 12:08:21', 1),
(4, NULL, 0, 'João Beze', 'João', NULL, 'Beze', '', NULL, 'beze@uol.com.br', NULL, '2011-03-29 17:29:25', 1),
(5, NULL, 0, 'Gustavo Costa', 'Gustavo', NULL, 'Costa', '', NULL, 'gustavo.marcelo@gmail.com', NULL, '2011-03-31 17:09:55', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_pessoa_contato`
--

CREATE TABLE IF NOT EXISTS `tb_pessoa_contato` (
  `CD_PESSOA_CONTATO` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK dos contatos da pessoa',
  `CD_PESSOA` int(11) DEFAULT NULL COMMENT 'FK da pessoa do contato',
  `TP_CONTATO` tinyint(4) NOT NULL COMMENT 'Tipo de contatos:\n0 - E-mail\n1 - Celular\n2 - Fax\n3 - Casa\n4 - Trabalho',
  `DT_INCLUSAO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data da inclusão do contato',
  `DS_CONTATO` varchar(100) NOT NULL COMMENT 'Conteúdo do contato:\n(99) 9999999 <-- Telefones\naaaaa@bbbb.ccc.ddd <-- E-mail',
  `FL_ATIVO` tinyint(4) DEFAULT '1' COMMENT 'Flag de ativo\n0 - Destativado\nnull - Desativado\n1 - Ativo',
  `TP_PRIVACIDADE` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Nível da privacidade\n0 - Somente o criador\n1 - Somente o círculo de amigos\n2 - Somente usuários logados\n3 - Público',
  PRIMARY KEY (`CD_PESSOA_CONTATO`),
  KEY `TB_PESSOA_CONTATO_CD_PESSOA` (`CD_PESSOA`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Contatos da pessoa' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tb_pessoa_contato`
--


-- --------------------------------------------------------

--
-- Table structure for table `tb_pessoa_endereco`
--

CREATE TABLE IF NOT EXISTS `tb_pessoa_endereco` (
  `CD_PESSOA_ENDERECO` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK do endereço da pessoa',
  `CD_PESSOA` int(11) DEFAULT NULL COMMENT 'FK da pessoa',
  `TP_ENDERECO` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Tipo de endreço:\n0 - Outros\n1 - Residência\n2 - Trabalho\n3 - Correspondência\n',
  `NU_CEP` int(11) DEFAULT NULL COMMENT 'CEP do endereço',
  `CD_CEP` int(11) DEFAULT NULL COMMENT 'FK do CEP se for escolhido do sistema',
  `CD_LOGRADOURO` int(11) DEFAULT NULL COMMENT 'FK do tipo de logradouro',
  `DS_ENDERECO` varchar(100) NOT NULL COMMENT 'Conteúdo do endereço',
  `NU_NUMERO` varchar(20) DEFAULT NULL COMMENT 'Número do endereço\n',
  `DS_COMPLEMENTO` varchar(50) DEFAULT NULL COMMENT 'Complemento do Endereço',
  `DS_BAIRRO` varchar(50) DEFAULT NULL COMMENT 'Bairro',
  `CD_CIDADE` int(11) DEFAULT NULL,
  `CD_UF` int(11) DEFAULT NULL,
  `DT_INCLUSAO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `FL_ATIVO` tinyint(4) DEFAULT NULL COMMENT 'Flag de ativo\n0 - Destativado\nnull - Desativado\n1 - Ativo',
  `TP_PRIVACIDADE` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Nível da privacidade\n0 - Somente o criador\n1 - Somente o círculo de amigos\n2 - Somente usuários logados\n3 - Público',
  `FL_CORRESPONDENCIA` tinyint(4) DEFAULT '0' COMMENT 'Se o endereço é de correspondência o TP_ENDERECO tem prevalência',
  PRIMARY KEY (`CD_PESSOA_ENDERECO`),
  KEY `TB_PESSOA_ENDERECO_CD_PESSOA` (`CD_PESSOA`),
  KEY `TB_PESSOA_ENDERECO_CD_LOGRADOURO` (`CD_LOGRADOURO`),
  KEY `TB_PESSOA_ENDERECO_CD_UF` (`CD_UF`),
  KEY `TB_PESSOA_ENDERECO_CD_CIDADE` (`CD_CIDADE`),
  KEY `TB_PESSOA_ENDERECO_CD_CEP` (`CD_CEP`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Endereços da pessoa' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tb_pessoa_endereco`
--


-- --------------------------------------------------------

--
-- Table structure for table `tb_pessoa_fisica`
--

CREATE TABLE IF NOT EXISTS `tb_pessoa_fisica` (
  `CD_PESSOA_FISICA` int(11) NOT NULL AUTO_INCREMENT,
  `CD_PESSOA` int(11) DEFAULT NULL COMMENT 'FK da pessoa',
  `NU_CPF` varchar(20) DEFAULT NULL COMMENT 'CPF da pessoa',
  `NU_RG` varchar(20) DEFAULT NULL COMMENT 'RG da pessoa',
  `DT_RG_EMISSAO` date DEFAULT NULL COMMENT 'Emissão da RG',
  PRIMARY KEY (`CD_PESSOA_FISICA`),
  KEY `TB_PESSOA_FISICA_CD_PESSOA` (`CD_PESSOA`),
  KEY `NU_CPF` (`NU_CPF`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Dados pessoais da pessoa' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tb_pessoa_fisica`
--


-- --------------------------------------------------------

--
-- Table structure for table `tb_pessoa_juridica`
--

CREATE TABLE IF NOT EXISTS `tb_pessoa_juridica` (
  `CD_PESSOA_JURIDICA` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK dos dados jurídicos da pessoa',
  `CD_PESSOA` int(11) NOT NULL COMMENT 'FK da pessoa',
  `NU_CNPJ` varchar(20) DEFAULT NULL COMMENT 'CNPJ da pessoa jurídica',
  `DS_RAZAO_SOCIAL` varchar(100) DEFAULT NULL COMMENT 'Razão Social da pessoa jurídica',
  PRIMARY KEY (`CD_PESSOA_JURIDICA`),
  KEY `TB_PESSOA_JURIDICA_CD_PESSOA` (`CD_PESSOA`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Dados jurídicos da pessoa' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tb_pessoa_juridica`
--


-- --------------------------------------------------------

--
-- Table structure for table `tb_prefixo`
--

CREATE TABLE IF NOT EXISTS `tb_prefixo` (
  `CD_PREFIXO` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK do Prefixo',
  `NM_PREFIXO` varchar(45) NOT NULL COMMENT 'Nome longo do prefixo',
  `DS_SIGLA` varchar(20) NOT NULL COMMENT 'Sigla',
  `FL_ATIVO` tinyint(4) DEFAULT '1' COMMENT 'Flag de ativo\n0 - Destativado\nnull - Desativado\n1 - Ativo',
  PRIMARY KEY (`CD_PREFIXO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Prefixo das pessoas Sr. Sra. Dr. Dra. Etc' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tb_prefixo`
--


-- --------------------------------------------------------

--
-- Table structure for table `tb_publicacao`
--

CREATE TABLE IF NOT EXISTS `tb_publicacao` (
  `CD_PUBLICACAO` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK da Publicação',
  `CD_PESSOA` int(11) DEFAULT NULL COMMENT 'FK da pessoa dono da publicação',
  `TP_PUBLICACAO` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Tipo de publicação:\n0 - Texto\n1 - Artigo\n2 - Tese\n3 - Pesquisa\n4 - Trabalho\n',
  `NM_PUBLICACAO` varchar(200) NOT NULL COMMENT 'Nome da Publicação',
  `DS_RESUMO` varchar(255) DEFAULT NULL COMMENT 'Resumo da publicação',
  `DS_PUBLICACAO` text COMMENT 'Conteúdo da publicação. Pode ter somente anexos',
  `DT_INCLUSAO` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data e hora da inclusão',
  `FL_ATIVO` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Flag de ativo\n0 - Destativado\nnull - Desativado\n1 - Ativo',
  `TP_PRIVACIDADE` tinyint(4) DEFAULT '0' COMMENT 'Nível da privacidade\n0 - Somente o criador\n1 - Somente o círculo de amigos\n2 - Somente usuários logados\n3 - Público',
  `DT_ORIGINAL` date DEFAULT NULL COMMENT 'Data original',
  PRIMARY KEY (`CD_PUBLICACAO`),
  KEY `TB_PUBLICACAO_CD_PESSOA` (`CD_PESSOA`),
  KEY `NM_PUBLICACAO` (`NM_PUBLICACAO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Publicações da Pessoa' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tb_publicacao`
--


-- --------------------------------------------------------

--
-- Table structure for table `tb_publicacao_anexo`
--

CREATE TABLE IF NOT EXISTS `tb_publicacao_anexo` (
  `CD_PUBLICACAO_ANEXO` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK do anexo da publicação',
  `CD_PUBLICACAO` int(11) DEFAULT NULL COMMENT 'FK da Publicação',
  `CD_OBJETO` int(11) DEFAULT NULL COMMENT 'FK do objeto',
  `NM_PUBLICACAO_ANEXO` varchar(100) DEFAULT NULL COMMENT 'Nome do anexo',
  `DS_RESUMO` varchar(255) DEFAULT NULL COMMENT 'Resumo do anexo',
  `DT_INCLUSAO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data de inclusão',
  `FL_ATIVO` tinyint(4) DEFAULT '1' COMMENT 'Flag de ativo\n0 - Destativado\nnull - Desativado\n1 - Ativo',
  `TP_PRIVACIDADE` tinyint(4) DEFAULT '0' COMMENT 'Nível da privacidade\n0 - Somente o criador\n1 - Somente o círculo de amigos\n2 - Somente usuários logados\n3 - Público',
  PRIMARY KEY (`CD_PUBLICACAO_ANEXO`),
  KEY `TB_PUBLICACAO_ANEXO_CD_PUBLICACAO` (`CD_PUBLICACAO`),
  KEY `TB_PUBLICACAO_ANEXO_CD_OBJETO` (`CD_OBJETO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Anexos das publicação' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tb_publicacao_anexo`
--


-- --------------------------------------------------------

--
-- Table structure for table `tb_sala`
--

CREATE TABLE IF NOT EXISTS `tb_sala` (
  `CD_SALA` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK da sala de discussão',
  `CD_PESSOA` int(11) DEFAULT NULL COMMENT 'PK da pessoa que criou',
  `CD_ESPECIALIDADE` int(11) DEFAULT NULL,
  `TP_SALA` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Tipo de Sala\n0 - Sala\n1 - Auditório',
  `NM_SALA` varchar(100) NOT NULL COMMENT 'Nome da sala',
  `DS_RESUMO` varchar(255) NOT NULL COMMENT 'Resumo do tema da sala',
  `DS_SALA` text COMMENT 'Descrição da sala',
  `DT_INCLUSAO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Inclusão da sala',
  `DT_INICIO` datetime DEFAULT NULL COMMENT 'Data e hora de início das atividades',
  `DT_FIM` datetime DEFAULT NULL COMMENT 'Data e hora do fim das atividades',
  `DS_RESULTADO` text COMMENT 'Resultado das discussões',
  `FL_ESTADO` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Flag de estado:\n0 - Aberta\n1 - Pausada\n2 - Fechada',
  `FL_ATIVO` tinyint(4) DEFAULT '1' COMMENT 'Flag de ativo\n0 - Destativado\nnull - Desativado\n1 - Ativo',
  `TP_PRIVACIDADE` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Nível da privacidade\n0 - Somente o criador\n1 - Somente o círculo de amigos\n2 - Somente usuários logados\n3 - Público\n4 - Somente convidados\n5 - Invisível (adefinir)',
  PRIMARY KEY (`CD_SALA`),
  KEY `IX_SALA_CD_PESSOA` (`CD_PESSOA`),
  KEY `IX_SALA_CD_ESPECIALIDADE` (`CD_ESPECIALIDADE`),
  KEY `IX_SALA_DT_INCLUSAO` (`DT_INCLUSAO`),
  KEY `IX_SALA_DT_INICIO` (`DT_INICIO`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Salas de discussão' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tb_sala`
--

INSERT INTO `tb_sala` (`CD_SALA`, `CD_PESSOA`, `CD_ESPECIALIDADE`, `TP_SALA`, `NM_SALA`, `DS_RESUMO`, `DS_SALA`, `DT_INCLUSAO`, `DT_INICIO`, `DT_FIM`, `DS_RESULTADO`, `FL_ESTADO`, `FL_ATIVO`, `TP_PRIVACIDADE`) VALUES
(1, 1, NULL, 1, 'Ruben', 'Ruben Zevallos Jr.', 'Windows path names are specified using forward slashes rather than ...... Since the IGNORE keyword is specified, when zip is set to NULL the row is ignored ...', '2011-03-30 16:17:13', '2011-03-30 16:16:31', '2011-05-25 16:16:37', NULL, 0, 1, 0),
(2, 2, NULL, 0, 'Vivi Teste', 'Teste', 'Teste do vinícios', '2011-03-30 16:34:50', '2011-03-31 16:34:34', '2011-05-26 16:34:40', NULL, 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tb_sala_convite`
--

CREATE TABLE IF NOT EXISTS `tb_sala_convite` (
  `CD_SALA_CONVITE` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK dos convites para particpar de uma sala',
  `CD_SALA` int(11) DEFAULT NULL COMMENT 'FK da sala',
  `CD_PESSOA` int(11) DEFAULT NULL COMMENT 'FK da pessoa',
  `DS_JUSTIFICATIVA` varchar(255) DEFAULT NULL COMMENT 'Justificativa do convite',
  `DT_INCLUSAO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data da criação do pedido',
  `DT_ULTIMO_ENVIO` datetime DEFAULT NULL COMMENT 'Data e hora do último envio',
  `NU_VISUALIZACAO` varchar(45) DEFAULT '0' COMMENT 'Vez que o convite foi visualizado',
  `TP_ACEITE` tinyint(4) DEFAULT NULL COMMENT 'Tipo do aceite\nnull - indefinido\n0 - Não aceitou\n1 - Aceitou',
  `DT_ACEITE` datetime DEFAULT NULL COMMENT 'Data do aceite',
  `DS_ACEITE` varchar(255) DEFAULT NULL COMMENT 'Comentários do aceite',
  PRIMARY KEY (`CD_SALA_CONVITE`),
  KEY `TB_SALA_CONVITE_CD_SALA` (`CD_SALA`),
  KEY `TB_SALA_CONVITE_CD_PESSOA` (`CD_PESSOA`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Convite para participar de uma sala' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tb_sala_convite`
--


-- --------------------------------------------------------

--
-- Table structure for table `tb_sala_pessoas`
--

CREATE TABLE IF NOT EXISTS `tb_sala_pessoas` (
  `CD_SALA_PESSOAS` int(11) NOT NULL AUTO_INCREMENT,
  `CD_SALA` int(11) DEFAULT NULL COMMENT 'FK da Sala',
  `CD_PESSOA` int(11) DEFAULT NULL COMMENT 'FK da pessoa',
  `DT_INCLUSAO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data e hora da inclusão',
  `FL_ATIVO` tinyint(4) DEFAULT '1' COMMENT 'Flag de ativo\n0 - Destativado\nnull - Desativado\n1 - Ativo',
  `TP_PRIVACIDADE` tinyint(4) DEFAULT '0' COMMENT 'Nível da privacidade\n0 - Somente o criador\n1 - Somente o círculo de amigos\n2 - Somente usuários logados\n3 - Público\n4 - Somente os participantes da sala',
  PRIMARY KEY (`CD_SALA_PESSOAS`),
  KEY `TB_SALA_PESSOAS_CD_PESSOA` (`CD_PESSOA`),
  KEY `TB_SALA_PESSOAS_CD_SALA` (`CD_SALA`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Participantes da sala' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tb_sala_pessoas`
--

INSERT INTO `tb_sala_pessoas` (`CD_SALA_PESSOAS`, `CD_SALA`, `CD_PESSOA`, `DT_INCLUSAO`, `FL_ATIVO`, `TP_PRIVACIDADE`) VALUES
(1, 1, 1, '2011-03-30 16:17:45', 1, 0),
(2, 1, 2, '2011-03-30 16:18:02', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tb_sessao`
--

CREATE TABLE IF NOT EXISTS `tb_sessao` (
  `CD_SESSAO` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK da sessão',
  `DT_INICIO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data e hora de início da sessão',
  `DT_FIM` datetime DEFAULT NULL COMMENT 'Data e hora do fim da sessão, que será todas as vezes que se fizer algum update.',
  `CD_PESSOA` int(11) DEFAULT NULL COMMENT 'FK de pessoa, quando autenticado',
  `CD_BROWSER` int(11) DEFAULT NULL COMMENT 'FK do tipo de browser',
  `CD_DISPOSITIVO` int(11) DEFAULT NULL COMMENT 'FK do tipo de dispositivo',
  PRIMARY KEY (`CD_SESSAO`),
  KEY `TB_SESSAO_CD_PESSOA` (`CD_PESSOA`),
  KEY `TB_SESSAO_CD_BROWSER` (`CD_BROWSER`),
  KEY `TB_SESSAO_CD_DISPOSITIVO` (`CD_DISPOSITIVO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Controle de todas as sessões do sistema' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tb_sessao`
--


-- --------------------------------------------------------

--
-- Table structure for table `tb_uf`
--

CREATE TABLE IF NOT EXISTS `tb_uf` (
  `CD_UF` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK das UFs',
  `NM_UF` varchar(50) DEFAULT NULL COMMENT 'Nome completo da UF',
  `DS_SIGLA` varchar(2) DEFAULT NULL COMMENT 'Sigla da UF',
  PRIMARY KEY (`CD_UF`),
  KEY `DS_SIGLA` (`DS_SIGLA`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='UF - Unidade da Federacao' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tb_uf`
--

INSERT INTO `tb_uf` (`CD_UF`, `NM_UF`, `DS_SIGLA`) VALUES
(1, 'Distrito Federal', 'DF'),
(2, 'Goiás', 'GO');

-- --------------------------------------------------------

--
-- Table structure for table `tb_usuario`
--

CREATE TABLE IF NOT EXISTS `tb_usuario` (
  `CD_USUARIO` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK dos usuários',
  `CD_PESSOA` int(11) DEFAULT NULL COMMENT 'FK com a tabela de pessoa',
  `TP_AUTENTICACAO` tinyint(4) DEFAULT NULL COMMENT 'Tipo de autenticação \n1 - E-mail\n2 - Facebook\n3 - Google\nEtc',
  `DS_LOGIN` varchar(100) DEFAULT NULL COMMENT 'Login, caso o tipo de login requerer',
  `DS_SENHA` varchar(20) DEFAULT NULL COMMENT 'Senha caso o tipo de login requerer',
  PRIMARY KEY (`CD_USUARIO`),
  KEY `TB_USUARIO_CD_PESSOA` (`CD_PESSOA`),
  KEY `DS_LOGIN` (`DS_LOGIN`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Tabela do usuários do sistema, este será a forma de entrad' AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tb_usuario`
--

INSERT INTO `tb_usuario` (`CD_USUARIO`, `CD_PESSOA`, `TP_AUTENTICACAO`, `DS_LOGIN`, `DS_SENHA`) VALUES
(1, 1, 1, 'zevallos@zevallos.com.br', '913947sa'),
(2, 2, 1, 'vinnyspl@gmail.com', '4321'),
(3, 3, 1, 'cacauab@gmail.com', '4321'),
(4, 4, 1, 'beze@uol.com.br', '4321'),
(5, 5, 1, 'gustavo.marcelo@gmail.com', '4321');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_album`
--
ALTER TABLE `tb_album`
  ADD CONSTRAINT `TB_ALBUM_CD_PESSOA` FOREIGN KEY (`CD_PESSOA`) REFERENCES `tb_pessoa` (`CD_PESSOA`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_album_objeto`
--
ALTER TABLE `tb_album_objeto`
  ADD CONSTRAINT `TB_ALBUM_OBJETO_CD_ALBUM` FOREIGN KEY (`CD_ALBUM`) REFERENCES `tb_album` (`CD_ALBUM`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `TB_ALBUM_OBJETO_CD_OBJETO` FOREIGN KEY (`CD_OBJETO`) REFERENCES `tb_objeto` (`CD_OBJETO`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_cidade`
--
ALTER TABLE `tb_cidade`
  ADD CONSTRAINT `TB_CIDADE_CD_UF` FOREIGN KEY (`CD_UF`) REFERENCES `tb_uf` (`CD_UF`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_circulo`
--
ALTER TABLE `tb_circulo`
  ADD CONSTRAINT `TB_CIRCULO_CD_PESSOA` FOREIGN KEY (`CD_PESSOA`) REFERENCES `tb_pessoa` (`CD_PESSOA`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_circulo_pessoa`
--
ALTER TABLE `tb_circulo_pessoa`
  ADD CONSTRAINT `TB_CIRCULO_PESSOA_CD_CIRCULO` FOREIGN KEY (`CD_CIRCULO`) REFERENCES `tb_circulo` (`CD_CIRCULO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `TB_CIRCULO_PESSOA_CD_PESSOA` FOREIGN KEY (`CD_PESSOA`) REFERENCES `tb_pessoa` (`CD_PESSOA`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_colega`
--
ALTER TABLE `tb_colega`
  ADD CONSTRAINT `TB_COLEGA_CD_PESSOA` FOREIGN KEY (`CD_PESSOA`) REFERENCES `tb_pessoa` (`CD_PESSOA`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `TB_COLEGA_CD_PESSOA_COLEGA` FOREIGN KEY (`CD_PESSOA_COLEGA`) REFERENCES `tb_pessoa` (`CD_PESSOA`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_colega_convite`
--
ALTER TABLE `tb_colega_convite`
  ADD CONSTRAINT `TB_COLEGA_CONVITE_CD_PESSOA` FOREIGN KEY (`CD_PESSOA`) REFERENCES `tb_pessoa` (`CD_PESSOA`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_comentario`
--
ALTER TABLE `tb_comentario`
  ADD CONSTRAINT `TB_COMENTARIO_CD_COMENTARIO_PAI` FOREIGN KEY (`CD_COMENTARIO_PAI`) REFERENCES `tb_comentario` (`CD_COMENTARIO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `TB_COMENTARIO_CD_PESSOA` FOREIGN KEY (`CD_PESSOA`) REFERENCES `tb_pessoa` (`CD_PESSOA`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `TB_COMENTARIO_CD_PESSOA_ORIGINAL` FOREIGN KEY (`CD_PESSOA_ORIGINAL`) REFERENCES `tb_pessoa` (`CD_PESSOA`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_comentario_album_objeto`
--
ALTER TABLE `tb_comentario_album_objeto`
  ADD CONSTRAINT `CD_COMENTARIOTB_COMENTARIO_ALBUM_OBJETO_` FOREIGN KEY (`CD_COMENTARIO`) REFERENCES `tb_comentario` (`CD_COMENTARIO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `CD_COMENTARIOTB_COMENTARIO_ALBUM_OBJETO_CD_ALBUM_OBJETO` FOREIGN KEY (`CD_ALBUM_OBJETO`) REFERENCES `tb_album_objeto` (`CD_ALBUM_OBJETO`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_comentario_publicacao`
--
ALTER TABLE `tb_comentario_publicacao`
  ADD CONSTRAINT `TB_COMENTARIO_PUBLICACAO_CD_COMENTARIO` FOREIGN KEY (`CD_COMENTARIO`) REFERENCES `tb_comentario` (`CD_COMENTARIO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `TB_COMENTARIO_PUBLICACAO_CD_PUBLICACAO` FOREIGN KEY (`CD_PUBLICACAO`) REFERENCES `tb_publicacao` (`CD_PUBLICACAO`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_convite_pessoa`
--
ALTER TABLE `tb_convite_pessoa`
  ADD CONSTRAINT `TB_CONVITE_PESSOA_CD_CONVITE` FOREIGN KEY (`CD_CONVITE`) REFERENCES `tb_convite` (`CD_CONVITE`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `TB_CONVITE_PESSOA_CD_PESSOA` FOREIGN KEY (`CD_PESSOA`) REFERENCES `tb_pessoa` (`CD_PESSOA`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_objeto`
--
ALTER TABLE `tb_objeto`
  ADD CONSTRAINT `TB_OBJETO_CD_PESSOA` FOREIGN KEY (`CD_PESSOA`) REFERENCES `tb_pessoa` (`CD_PESSOA`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_pessoa`
--
ALTER TABLE `tb_pessoa`
  ADD CONSTRAINT `TB_PESSOA_CD_PREFIXO` FOREIGN KEY (`CD_PREFIXO`) REFERENCES `tb_prefixo` (`CD_PREFIXO`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_pessoa_contato`
--
ALTER TABLE `tb_pessoa_contato`
  ADD CONSTRAINT `TB_PESSOA_CONTATO_CD_PESSOA` FOREIGN KEY (`CD_PESSOA`) REFERENCES `tb_pessoa` (`CD_PESSOA`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_pessoa_endereco`
--
ALTER TABLE `tb_pessoa_endereco`
  ADD CONSTRAINT `TB_PESSOA_ENDERECO_CD_CEP` FOREIGN KEY (`CD_CEP`) REFERENCES `tb_cep` (`CD_CEP`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `TB_PESSOA_ENDERECO_CD_CIDADE` FOREIGN KEY (`CD_CIDADE`) REFERENCES `tb_cidade` (`CD_CIDADE`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `TB_PESSOA_ENDERECO_CD_LOGRADOURO` FOREIGN KEY (`CD_LOGRADOURO`) REFERENCES `tb_logradouro` (`CD_LOGRADOURO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `TB_PESSOA_ENDERECO_CD_PESSOA` FOREIGN KEY (`CD_PESSOA`) REFERENCES `tb_pessoa` (`CD_PESSOA`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `TB_PESSOA_ENDERECO_CD_UF` FOREIGN KEY (`CD_UF`) REFERENCES `tb_uf` (`CD_UF`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_pessoa_fisica`
--
ALTER TABLE `tb_pessoa_fisica`
  ADD CONSTRAINT `TB_PESSOA_FISICA_CD_PESSOA` FOREIGN KEY (`CD_PESSOA`) REFERENCES `tb_pessoa` (`CD_PESSOA`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_pessoa_juridica`
--
ALTER TABLE `tb_pessoa_juridica`
  ADD CONSTRAINT `TB_PESSOA_JURIDICA_CD_PESSOA` FOREIGN KEY (`CD_PESSOA`) REFERENCES `tb_pessoa` (`CD_PESSOA`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_publicacao`
--
ALTER TABLE `tb_publicacao`
  ADD CONSTRAINT `TB_PUBLICACAO_CD_PESSOA` FOREIGN KEY (`CD_PESSOA`) REFERENCES `tb_pessoa` (`CD_PESSOA`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_publicacao_anexo`
--
ALTER TABLE `tb_publicacao_anexo`
  ADD CONSTRAINT `TB_PUBLICACAO_ANEXO_CD_OBJETO` FOREIGN KEY (`CD_OBJETO`) REFERENCES `tb_objeto` (`CD_OBJETO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `TB_PUBLICACAO_ANEXO_CD_PUBLICACAO` FOREIGN KEY (`CD_PUBLICACAO`) REFERENCES `tb_publicacao` (`CD_PUBLICACAO`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_sala`
--
ALTER TABLE `tb_sala`
  ADD CONSTRAINT `FK_SALA_CD_ESPECIALIDADE` FOREIGN KEY (`CD_ESPECIALIDADE`) REFERENCES `tb_especialidade` (`CD_ESPECIALIDADE`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_SALA_CD_PESSOA` FOREIGN KEY (`CD_PESSOA`) REFERENCES `tb_pessoa` (`CD_PESSOA`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_sala_convite`
--
ALTER TABLE `tb_sala_convite`
  ADD CONSTRAINT `TB_SALA_CONVITE_CD_PESSOA` FOREIGN KEY (`CD_PESSOA`) REFERENCES `tb_pessoa` (`CD_PESSOA`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `TB_SALA_CONVITE_CD_SALA` FOREIGN KEY (`CD_SALA`) REFERENCES `tb_sala` (`CD_SALA`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_sala_pessoas`
--
ALTER TABLE `tb_sala_pessoas`
  ADD CONSTRAINT `TB_SALA_PESSOAS_CD_PESSOA` FOREIGN KEY (`CD_PESSOA`) REFERENCES `tb_pessoa` (`CD_PESSOA`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `TB_SALA_PESSOAS_CD_SALA` FOREIGN KEY (`CD_SALA`) REFERENCES `tb_sala` (`CD_SALA`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_sessao`
--
ALTER TABLE `tb_sessao`
  ADD CONSTRAINT `TB_SESSAO_CD_BROWSER` FOREIGN KEY (`CD_BROWSER`) REFERENCES `tb_browser` (`CD_BROWSER`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `TB_SESSAO_CD_DISPOSITIVO` FOREIGN KEY (`CD_DISPOSITIVO`) REFERENCES `tb_dispositivo` (`CD_DISPOSITIVO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `TB_SESSAO_CD_PESSOA` FOREIGN KEY (`CD_PESSOA`) REFERENCES `tb_pessoa` (`CD_PESSOA`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_usuario`
--
ALTER TABLE `tb_usuario`
  ADD CONSTRAINT `TB_USUARIO_CD_PESSOA` FOREIGN KEY (`CD_PESSOA`) REFERENCES `tb_pessoa` (`CD_PESSOA`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

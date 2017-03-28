-- phpMyAdmin SQL Dump
-- version 3.3.10deb1.1
-- http://www.phpmyadmin.net
--
-- Host: 187.45.196.216
-- Generation Time: May 02, 2011 at 09:23 PM
-- Server version: 5.1.54
-- PHP Version: 5.3.3-7+squeeze1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `zyc3`
--

--
-- Dumping data for table `TB_ALBUM`
--


--
-- Dumping data for table `TB_ALBUM_OBJETO`
--


--
-- Dumping data for table `TB_BROWSER`
--


--
-- Dumping data for table `TB_CEP`
--


--
-- Dumping data for table `TB_CIDADE`
--


--
-- Dumping data for table `TB_CIRCULO`
--

INSERT INTO `TB_CIRCULO` (`CD_CIRCULO`, `CD_PESSOA`, `NM_CIRCULO`, `DS_CIRCULO`, `DT_INCLUSAO`, `FL_ATIVO`) VALUES
(1, 1, 'Geral', 'Geral', '2011-03-29 11:43:12', 1),
(2, 2, 'Geral', 'Geral', '2011-03-29 11:43:12', 1),
(3, 3, 'Geral', 'Geral', '2011-03-29 12:09:53', 1),
(4, 1, 'Brasília', 'Amigos de Brasília', '2011-03-29 14:09:45', 1),
(5, 2, 'Goiania', 'Goiania', '2011-03-29 17:23:23', 1),
(6, 6, 'Geral', NULL, '2011-03-31 23:52:34', 1),
(7, 6, 'Família', NULL, '2011-03-31 23:58:21', 1);

--
-- Dumping data for table `TB_CIRCULO_PESSOA`
--

INSERT INTO `TB_CIRCULO_PESSOA` (`CD_CIRCULO_PESSOA`, `CD_CIRCULO`, `CD_PESSOA`, `FL_ATIVO`, `DT_INCLUSAO`) VALUES
(1, 1, 2, 1, '2011-03-29 11:43:51'),
(2, 2, 1, 1, '2011-03-29 11:43:51'),
(3, 1, 3, 1, '2011-03-29 12:10:22'),
(4, 3, 2, 1, '2011-03-29 12:10:22'),
(5, 4, 3, 1, '2011-03-29 14:10:05'),
(6, 5, 1, 1, '2011-03-29 17:23:42'),
(7, 2, 3, 1, '2011-03-29 17:26:52'),
(8, 1, 4, 1, '2011-03-29 17:35:44'),
(9, 6, 1, 1, '2011-03-31 23:53:25'),
(10, 7, 2, 1, '2011-03-31 23:58:40'),
(11, 6, 2, 1, '2011-03-31 23:59:44');

--
-- Dumping data for table `TB_COLEGA`
--

INSERT INTO `TB_COLEGA` (`CD_COLEGA`, `CD_PESSOA`, `CD_PESSOA_COLEGA`, `NM_APELIDO`, `DT_INCLUSAO`, `FL_ATIVO`) VALUES
(1, 1, 2, NULL, '2011-03-29 11:44:50', 1),
(2, 2, 1, NULL, '2011-03-29 11:44:50', 1),
(3, 3, 1, NULL, '2011-03-29 12:09:01', 1),
(4, 3, 2, NULL, '2011-03-29 12:09:01', 1),
(5, 1, 3, NULL, '2011-03-29 12:09:16', 1),
(6, 2, 3, NULL, '2011-03-29 12:09:16', 1),
(7, 6, 1, NULL, '2011-03-31 23:53:09', 1),
(8, 6, 2, NULL, '2011-03-31 23:58:57', 1);

--
-- Dumping data for table `TB_COLEGA_CONVITE`
--


--
-- Dumping data for table `TB_COMENTARIO`
--


--
-- Dumping data for table `TB_COMENTARIO_ALBUM_OBJETO`
--


--
-- Dumping data for table `TB_COMENTARIO_PUBLICACAO`
--


--
-- Dumping data for table `TB_CONVITE`
--

INSERT INTO `TB_CONVITE` (`CD_CONVITE`, `DS_EMAIL`, `NM_PESSOA`, `NM_PRIMEIRO`, `NM_ULTIMO`, `DS_CONSELHO`, `DT_INCLUSAO`, `FL_ATIVO`, `QT_VISUALIZACOES`, `FL_ACEITE`, `DT_VISUALIZACAO`, `DT_ACEITE`) VALUES
(1, 'zevallos@zevallos.com.br', 'Ruben Zevallos', 'Ruben', 'Zevallos', '123123', '2011-03-22 13:58:04', 1, 0, NULL, NULL, NULL);

--
-- Dumping data for table `TB_CONVITE_PESSOA`
--


--
-- Dumping data for table `TB_DISPOSITIVO`
--


--
-- Dumping data for table `TB_ESPECIALIDADE`
--


--
-- Dumping data for table `TB_LOGRADOURO`
--


--
-- Dumping data for table `TB_OBJETO`
--


--
-- Dumping data for table `TB_PESSOA`
--

INSERT INTO `TB_PESSOA` (`CD_PESSOA`, `CD_PREFIXO`, `TP_TIPO`, `NM_PESSOA`, `NM_PRIMEIRO`, `NM_MEIO`, `NM_ULTIMO`, `DS_URL_PERFIL`, `DS_CONSELHO`, `DS_EMAIL`, `DS_AVATAR`, `DT_INCLUSAO`, `FL_ATIVO`) VALUES
(1, NULL, 0, 'Ruben Zevallos', 'Ruben', NULL, 'Zevallos', NULL, '123123', 'zevallos@zevallos.com.br', '/avatar/df/Ruben_1.jpg', '2011-03-22 14:49:49', 1),
(2, NULL, 0, 'Vinicius Leonidas', 'Vinicius', NULL, 'Leonidas', NULL, '1234', 'vinnyspl@gmail.com', NULL, '2011-03-29 11:42:01', 1),
(3, NULL, 0, 'Antonio Carlos Abrantes', 'Antonio Carlos', NULL, 'Abrantes', NULL, '', 'cacauab@gmail.com', NULL, '2011-03-29 12:08:21', 1),
(4, NULL, 0, 'João Beze', 'João', NULL, 'Beze', NULL, '', 'beze@uol.com.br', NULL, '2011-03-29 17:29:25', 1),
(5, NULL, 0, 'Gustavo Costa', 'Gustavo', NULL, 'Costa', NULL, '', 'gustavo.marcelo@gmail.com', NULL, '2011-03-31 17:09:55', 1),
(6, NULL, 0, 'André Mácola', 'André', NULL, 'Mácola', NULL, '', 'andremacola@gmail.com', NULL, '2011-03-31 23:49:43', 1),
(7, NULL, 0, 'andre machado', 'andre', NULL, 'machado', NULL, '', 'andremacola@gmail.com', NULL, '2011-04-12 13:13:25', 1),
(8, NULL, 0, 'Daniel Maffioletti', 'Daniel', NULL, 'Maffioletti', NULL, '1999', 'dmaffioletti@gmail.com', NULL, '2011-05-02 20:36:03', 1);

--
-- Dumping data for table `TB_PESSOA_CONTATO`
--


--
-- Dumping data for table `TB_PESSOA_ENDERECO`
--


--
-- Dumping data for table `TB_PESSOA_FISICA`
--


--
-- Dumping data for table `TB_PESSOA_JURIDICA`
--


--
-- Dumping data for table `TB_PREFIXO`
--


--
-- Dumping data for table `TB_PUBLICACAO`
--


--
-- Dumping data for table `TB_PUBLICACAO_ANEXO`
--


--
-- Dumping data for table `TB_SALA`
--

INSERT INTO `TB_SALA` (`CD_SALA`, `CD_PESSOA`, `CD_ESPECIALIDADE`, `TP_SALA`, `NM_SALA`, `DS_RESUMO`, `DS_SALA`, `DT_INCLUSAO`, `DT_INICIO`, `DT_FIM`, `DS_RESULTADO`, `FL_ESTADO`, `FL_ATIVO`, `TP_PRIVACIDADE`) VALUES
(1, 1, NULL, 1, 'Ruben', 'Ruben Zevallos Jr.', 'Windows path names are specified using forward slashes rather than ...... Since the IGNORE keyword is specified, when zip is set to NULL the row is ignored ...', '2011-03-30 16:17:13', '2011-03-30 16:16:31', '2011-05-25 16:16:37', NULL, 0, 1, 0),
(2, 2, NULL, 0, 'Vivi Teste', 'Teste', 'Teste do vinícios', '2011-03-30 16:34:50', '2011-03-31 16:34:34', '2011-05-26 16:34:40', NULL, 0, 1, 0),
(3, 6, NULL, 0, 'Mácolas', 'Mácolas', NULL, '2011-03-31 23:51:46', '2011-04-04 23:51:37', '2011-04-25 23:51:44', NULL, 0, 1, 0);

--
-- Dumping data for table `TB_SALA_CONVITE`
--


--
-- Dumping data for table `TB_SALA_PESSOAS`
--

INSERT INTO `TB_SALA_PESSOAS` (`CD_SALA_PESSOAS`, `CD_SALA`, `CD_PESSOA`, `DT_INCLUSAO`, `FL_ATIVO`, `TP_PRIVACIDADE`) VALUES
(1, 1, 1, '2011-03-30 16:17:45', 1, 0),
(2, 1, 2, '2011-03-30 16:18:02', 1, 0),
(3, 1, 6, '2011-03-31 23:50:16', 1, 0),
(4, 3, 1, '2011-03-31 23:52:07', 1, 0);

--
-- Dumping data for table `TB_SESSAO`
--


--
-- Dumping data for table `TB_UF`
--

INSERT INTO `TB_UF` (`CD_UF`, `NM_UF`, `DS_SIGLA`) VALUES
(1, 'Distrito Federal', 'DF'),
(2, 'Goiás', 'GO');

--
-- Dumping data for table `TB_USUARIO`
--

INSERT INTO `TB_USUARIO` (`CD_USUARIO`, `CD_PESSOA`, `TP_AUTENTICACAO`, `DS_LOGIN`, `DS_SENHA`) VALUES
(1, 1, 1, 'zevallos@zevallos.com.br', '4321'),
(2, 2, 1, 'vinnyspl@gmail.com', '4321'),
(3, 3, 1, 'cacauab@gmail.com', '4321'),
(4, 4, 1, 'beze@uol.com.br', '4321'),
(5, 5, 1, 'gustavo.marcelo@gmail.com', '4321'),
(6, 6, 1, 'andremacola@gmail.com', '4321'),
(7, 7, 1, 'andremacola@gmail.com', 'satelite90'),
(8, 8, 1, 'dmaffioletti@gmail.com', 'dmm2003');

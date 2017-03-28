<?php

/* *********************************************************************
 *
 *  paCRUD - PHP Ajax CRUD Framework é um framework para
 *  desenvolvimento rápido de sistemas de informação web.
 *  Copyright (C) 2010 Emerson Casas Salvador <salvaemerson@gmail.com>
 *  e Odair Rubleski <orubleski@gmail.com>
 *
 *  This file is part of paCRUD.
 *
 *  paCRUD is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3, or (at your option)
 *  any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, see <http://www.gnu.org/licenses/>.
 *
 * ******************************************************************* */
  
/*
Este arquivo contém as configurações padão do framework. 

*** ATENÇÃO *** NÃO ALTERE ESTE AQUIVO

Siga as instruções do script de instalação "install.php"

*/

$pacrudVersion = '0.1';

// Geral
$pacrudConfig['afterLogin']     = 'index.php';
$pacrudConfig['showPacrudLogo'] = true;
$pacrudConfig['language']       = 'pt_br';

//configs que não estão no install
$pacrudConfig['searchLines']    = 10;
$pacrudConfig['defaultPage']    = $pacrudConfig['pacrudPath'].'/view/desktop.php';
$pacrudConfig['theme']          = 'default';

// Configurações de banco de dados
$pacrudConfig['sgdb']           = 'pgsql';
$pacrudConfig['dbHost']         = 'localhost';
$pacrudConfig['dbPort']         = '5432';
$pacrudConfig['dbName']         = 'db_pacrud';
$pacrudConfig['appSchema']      = 'public';
$pacrudConfig['loginSchema']    = 'pacrud';
$pacrudConfig['dbUserName']     = 'pacrud';
$pacrudConfig['dbPassword']     = 'pacrud';

// exemplo de banco de dados mysql
#$pacrudConfig['sgdb']        = 'mysql';
#$pacrudConfig['dbHost']      = 'localhost';
#$pacrudConfig['dbPort']      = '3306';
#$pacrudConfig['dbName']      = 'db_pacrud';
#$pacrudConfig['dbUserName']  = 'pacrud';
#$pacrudConfig['dbPassword']  = 'pacrud';

// Configuração das paginas de inclusão
$pacrudPages['about'] = $pacrudConfig['pacrudPath'].'/view/about.php';

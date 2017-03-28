<?php

$pacrudConfig['appIdent']        = 'paCRUD';
$pacrudConfig['appName']         = 'PHP Ajax CRUD Framework';
$pacrudConfig['pacrudPath']      = '/var/www/pacrud';
$pacrudConfig['pacrudWebPath']   = '/pacrud';
$pacrudConfig['appPath']         = '/var/www/pacrud/example';
$pacrudConfig['appWebPath']      = '/pacrud/example';

//não remover estas linhas
require_once($pacrudConfig['pacrudPath'] . '/config_default.php');

//$pacrudConfig['theme'] = 'wine';
$pacrudConfig['language'] = 'de';


require_once($pacrudConfig['pacrudPath'] . '/controller/all_models.php');

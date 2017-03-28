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

?>

		<h1><?php echo $pacrudTextDesktop[0]; ?></h1>
		<p><?php echo $pacrudTextDesktop[1]; ?></p>
		<p><?php echo $pacrudTextDesktop[2]; ?></p>
		<h2><?php echo $pacrudTextDesktop[3]; ?></h2>
		<p><?php echo $pacrudTextDesktop[4]; ?></p>
		<p><?php echo $pacrudTextDesktop[5]; ?></p>
		<h2><?php echo $pacrudTextDesktop[6]; ?></h2>
		<p><?php echo $pacrudTextDesktop[7]; ?></p>
		<p><?php
		$pacrudUrlMenu = 'http://'.$_SERVER["SERVER_NAME"].$pacrudConfig['pacrudWebPath'].'/view/menu_xml.php';
		$link = '<a href="'.$pacrudUrlMenu.'">'.$pacrudUrlMenu.'</a>';
		printf($pacrudTextDesktop[8],$link);
		?></p>
		<p><?php echo $pacrudTextDesktop[9]; ?></p>
		<p><?php echo $pacrudTextDesktop[10]; ?></p>
		<h2><?php echo $pacrudTextDesktop[11]; ?></h2>
		<p><?php echo $pacrudTextDesktop[12]; ?></p>


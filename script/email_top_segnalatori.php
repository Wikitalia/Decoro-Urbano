<?php

/*
 * ----------------------------------------------------------------------------
 * Decoro Urbano version 0.2.1
 * ----------------------------------------------------------------------------
 * Copyright Maiora Labs Srl (c) 2012
 * ----------------------------------------------------------------------------   
 * 
 * This file is part of Decoro Urbano.
 * 
 * Decoro Urbano is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * Decoro Urbano is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with Decoro Urbano.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once("../include/config.php");
require_once('../include/db_open.php');
require_once('../include/db_open_funzioni.php');
require_once('../include/funzioni.php');
require_once('../include/decorourbano.php');

$nuovi_top = segnalatori_top_new_get();

foreach ($nuovi_top as $nuovo_top) {

	if ($nuovo_top['email_top']) {

		$data['from'] = $settings['email']['nome'].' <'.$settings['email']['indirizzo'].'>';
		$data['to'] = $nuovo_top['nome'].' '.$nuovo_top['cognome'].' <'.$nuovo_top['email'].'>';
		//$data['to'] = 'f.comi@maioralabs.it';
		$data['template'] = 'segnalatoreTop';
		$variabili['nome_utente'] = $nuovo_top['nome'];
		$data['variabili'] = $variabili;
		email_with_template($data);
	
	}
	
}

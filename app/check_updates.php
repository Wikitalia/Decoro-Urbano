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

/*
 * Interfaccia XML per le app di Decoro Urbano per verificare la presenza di nuove versioni delle app.
 * Confronta la versione inviata dal dispositivo con quella memorizzata nel file di configurazione.
 * 
 * @param string client stringa identificativa della tipologia di client, Android o iPhone
 * @param string versione string aidentificativa della versione dell'app
 */

ini_set('display_errors', 0);
error_reporting(0);

require_once("../include/config.php");
require_once("../include/db_open.php");
require_once("../include/db_open_funzioni.php");
require_once("../include/funzioni.php");
require_once("../include/controlli.php");
require_once("../include/decorourbano.php");

$client=(isset($_POST['client']))?cleanField($_POST['client']):'';
$versione=(isset($_POST['versione']))?cleanField($_POST['versione']):'0';

$update = false;

if ($client=='Android') {
	if ($versione && $versione<$settings['apps']['currentAndroidVersion']) {
		$update = true;
	}
} else if ($client=='iPhone') {
	if ($versione && $versione!=$settings['apps']['currentiPhoneVersion']) {
		$update = true;	
	}
}

$xml_out="";
$xml_out.="<decorourbano>";
$xml_out.="<update>";
if ($update) {
	$xml_out.="true";
} else {
	$xml_out.="false";
}
$xml_out.="</update>";
$xml_out.="</decorourbano>";


echo $xml_out;
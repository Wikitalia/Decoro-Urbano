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
 * Interfaccia XML per le app di Decoro Urbano per ottenere la lista delle segnalazioni
 * 
 * @param int idu id dell'utente
 * @param int t_new segnalazioni degli ultimi t_new giorni
 * @param int t_newer segnalazioni più recenti di un timestamp di riferimento
 * @param int c aggiunge i commenti delle segnalazioni
 * @param int t_old segnalazioni più vecchie di un timestamp riferimento
 * @param int l limita il numero delle segnalazioni restituite a l
 * @param float d aggiunge al risultato il numero di segnalazioni in zona
 * @param float minLat,maxLat,minLng,maxLng vertici dell'area su cui filtrare le segnalazioni
 */

ini_set('display_errors', 0);
error_reporting(0);

require_once("../include/config.php");
require_once("../include/db_open.php");
require_once("../include/db_open_funzioni.php");
require_once("../include/decorourbano.php");
require_once("../include/funzioni.php");

$user=(isset($_GET['idu']))?(int) $_GET['idu']:0;
$recenti=(isset($_GET['t_new']))?(int)$_GET['t_new']:0;
$nuove=(isset($_GET['t_newer']))?(int) $_GET['t_newer']:0;
$vecchie=(isset($_GET['t_old']))?(int) $_GET['t_old']:0;
$limit_numero=(isset($_GET['l']))?(int) $_GET['l']:500;
$distanza=(isset($_GET['d']))?(float) $_GET['d']:0;
$commenti=(isset($_GET['c']))?1:0;


if (isset($_GET['minLat']) && isset($_GET['maxLat']) && isset($_GET['minLng']) && isset($_GET['maxLng'])) {
	$area['minLat']=(float) $_GET['minLat'];
	$area['maxLat']=(float) $_GET['maxLat'];
	$area['minLng']=(float) $_GET['minLng'];
	$area['maxLng']=(float) $_GET['maxLng'];
} else {
	$area = array();
}

$parametri['id_user'] = $user;
$parametri['recenti'] = $recenti;
$parametri['nuove'] = $nuove;
$parametri['vecchie'] = $vecchie;
$parametri['limit'] = $limit_numero;
$parametri['distanza'] = $distanza;
$parametri['commenti'] = $commenti;
$parametri['area'] = $area;
$parametri['formato'] = 1;

$segnalazioni = json_decode(segnalazioni_get($parametri),TRUE);


$xml_out="";

if (count($segnalazioni)) {

	$xml_out.="<decorourbano>";
		
	foreach ($segnalazioni as $segnalazione) {
	
		$xml_out.="<segnalazione>";
		
		$xml_out.="<id_segnalazione>";
		$xml_out.=$segnalazione['id_segnalazione'];
		$xml_out.="</id_segnalazione>";
		$xml_out.="<latitudine>";
		$xml_out.=$segnalazione['lat'];
		$xml_out.="</latitudine>";
		$xml_out.="<longitudine>";
		$xml_out.=$segnalazione['lng'];
		$xml_out.="</longitudine>";
		$xml_out.="<descrizione>";
		$xml_out.=$segnalazione['messaggio'];
		$xml_out.="</descrizione>";
		$xml_out.="<via>";
		$xml_out.=$segnalazione['indirizzo'];
		$xml_out.="</via>";
		$xml_out.="<ncivico>";
		$xml_out.=$segnalazione['civico'];
		$xml_out.="</ncivico>";
		$xml_out.="<citta>";
		$xml_out.=$segnalazione['citta'];
		$xml_out.="</citta>";
		$xml_out.="<datasegnalazione>";
		$xml_out.=$segnalazione['data'];
		$xml_out.="</datasegnalazione>";
		$xml_out.="<urlfoto>";
		$xml_out.=$settings['sito']['url_ns'].$segnalazione['foto_base_url'].'300-0.jpg';
		$xml_out.="</urlfoto>";
		$xml_out.="<segnalatore>";
		$xml_out.=trim($segnalazione['nome'].' '.$segnalazione['cognome']);
		$xml_out.="</segnalatore>";
		$xml_out.="<tipo_nome>";
		$xml_out.=$segnalazione['tipo_nome'];
		$xml_out.="</tipo_nome>";
		$xml_out.="<id_tipo>";
		$xml_out.=$segnalazione['id_tipo'];
		$xml_out.="</id_tipo>";
		$xml_out.="<id_utente>";
		$xml_out.=$segnalazione['id_utente'];
		$xml_out.="</id_utente>";
		$xml_out.="<stato>";
		$xml_out.=$segnalazione['stato'];
		$xml_out.="</stato>";
		
		$xml_out.="</segnalazione>";
	}
	$xml_out.="</decorourbano>";
}	else {
	$xml_out.="<decorourbano></decorourbano>";
}


echo $xml_out;

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
 * Interfaccia XML per le app di Decoro Urbano per verificare lo stato di attivazione di un comune
 * a partire dal punto geografico in cui si trova il dispositivo
 * 
 * @param float lat latitudine del punto geografico
 * @param float lng longitudine del punto geografico
 */

ini_set('display_errors', 0);
error_reporting(0);

require_once("../include/config.php");
require_once("../include/db_open.php");
require_once("../include/db_open_funzioni.php");
require_once("../include/funzioni.php");
require_once("../include/controlli.php");
require_once("../include/SimpleImage.php");
require_once('../include/decorourbano.php');

$lat = (float) $_POST['lat'];
$lng = (float) $_POST['lng'];

$xml_out="";
$xml_out.="<decorourbano>";

if ($lat && $lng) {
	// costruisce l'URL del servizio di geocoding di google maps
	$geoCodeURL = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lng."&sensor=false&language=it";
	
	// invia la richiesta di geocoding
	$result = json_decode(file_get_contents($geoCodeURL), true);

	
	if ($result['status'] == 'OK') {
		// se la richiesta ha avuto esito positivo recupera il risultato
	
		foreach($result['results'][0]['address_components'] as $address_component) {
			$address[$address_component['types'][0]] = $address_component['long_name'];
		}

		// recupera il comune corrispondente dalla tabella comuni
		$comune = data_get('tab_comuni',array('nome_url'=>fixForUri($address['locality'])));
		
		$id_comune = $comune[0]['id_comune'];
		$attivo = $comune[0]['stato'];	
		
		$citta = $address['locality'];
		$citta_url = fixForUri($address['locality']);
		
		// costruisce la risposta
		$xml_out.="<status>";
		$xml_out.="ok";
		$xml_out.="</status>";
		
		$xml_out.="<nome_comune>";
		$xml_out.=$citta;
		$xml_out.="</nome_comune>";
		
		$xml_out.="<nome_comune_fixed>";
		$xml_out.=fixForUri($citta);
		$xml_out.="</nome_comune_fixed>";
		
		$xml_out.="<url_comune>";
		$xml_out.='http://'.$citta_url.'.'.$settings['sito']['dominio'].'/';
		$xml_out.="</url_comune>";
		
		$xml_out.="<url_logo_comune>";
		$xml_out.=$settings['sito']['url'].'images/loghi_comuni/'.$citta_url.'_logo.png';
		$xml_out.="</url_logo_comune>";
	
		$xml_out.="<comune_attivo>";
		$xml_out.=$attivo;
		$xml_out.="</comune_attivo>";
		
	} else {
	
		$xml_out.="<status>";
		$xml_out.="ko";
		$xml_out.="</status>";

	}

} else {
	$xml_out.="<status>";
	$xml_out.="ko";
	$xml_out.="</status>";
}

$xml_out.="</decorourbano>";

echo $xml_out;

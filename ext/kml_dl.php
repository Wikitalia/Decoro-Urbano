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

header("Access-Control-Allow-Origin: *");

require_once("../include/config.php");
require_once("../include/db_open.php");
require_once("../include/db_open_funzioni.php");
require_once("../include/funzioni.php");
require_once("../include/controlli.php");
require_once("../include/SimpleImage.php");
require_once('../include/decorourbano.php');
require_once('../include/zipstream-php-0.2.2/zipstream.php');

if ($settings['sito']['debug']) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

$user=(isset($_GET['idu']))?(int) $_GET['idu']:0;
$recenti=(isset($_GET['t_new']))?$_GET['t_new']:0;
$nuove=(isset($_GET['t_newer']))?(int) $_GET['t_newer']:0;
$vecchie=(isset($_GET['t_old']))?(int) $_GET['t_old']:0;
$limit_numero=(isset($_GET['l']))?(int) $_GET['l']:0;
$distanza=(isset($_GET['d']))?(float) $_GET['d']:0;
$commenti=(isset($_GET['c']))?$_GET['c']:0;
$comune=(isset($_GET['comune']))?cleanField($_GET['comune']):'';

$compress=(isset($_GET['compress']))?(int) $_GET['compress']:0;


if (isset($_GET['minLat']) && isset($_GET['maxLat']) && isset($_GET['minLng']) && isset($_GET['maxLng'])) {
	$area['minLat']=(float) $_GET['minLat'];
	$area['maxLat']=(float) $_GET['maxLat'];
	$area['minLng']=(float) $_GET['minLng'];
	$area['maxLng']=(float) $_GET['maxLng'];
} else {
	$area = array();
}

if ($comune!='') {
	$res_comune = data_get('tab_comuni',array('nome_url'=>$comune));
	if ($res_comune) {
		$parametri['id_comune'] = $res_comune[0]['id_comune'];
	}
	$nome_file = $comune;
} else {
	//$limit_numero = 100;
	$nome_file = 'Italia';
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

if ($_SERVER["HTTPS"] == "on") {
	$pageURL = "https://";
} else {
	$pageURL = "http://";
}

if ($_SERVER["SERVER_PORT"] != "80") {
 	$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
} else {
	$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
}

$xml_out="";

if ($compress) {
	header('Content-Type: application/zip; charset=UTF-8', true);
	header('Content-Disposition: attachment; filename='.$nome_file.'.zip');
} else {
	header('Content-Type: application/rss+xml; charset=UTF-8', true);
	header('Content-Disposition: attachment; filename='.$nome_file.'.kml');
}

$xml_out='<kml xmlns="http://earth.google.com/kml/2.0">'.PHP_EOL;
$xml_out.='<Document>'.PHP_EOL;
$xml_out.='<name>Decoro Urbano</name>'.PHP_EOL;
$xml_out.='<description>Lista segnalazioni per Google Earth</description>'.PHP_EOL;
$xml_out.='<LookAt>'.PHP_EOL;
$xml_out.='<longitude>12.24262835092209</longitude>'.PHP_EOL;
$xml_out.='<latitude>41.777746133935025</latitude>'.PHP_EOL;
$xml_out.='<altitude>155000,00</altitude>'.PHP_EOL;
$xml_out.='<range>1542000.676045224589</range>'.PHP_EOL;
$xml_out.='<tilt>0.0</tilt>'.PHP_EOL;
$xml_out.='<heading>00.0</heading>'.PHP_EOL;
$xml_out.='</LookAt>'.PHP_EOL;

$xml_out.='<Style id="rifiuti">'.PHP_EOL;
$xml_out.='<IconStyle>'.PHP_EOL;
$xml_out.='<Icon>'.PHP_EOL;
$xml_out.='<href>'.$settings['sito']['url_ns'].'/images/marker_rifiuti.png</href>'.PHP_EOL;
$xml_out.='</Icon>'.PHP_EOL;
$xml_out.='</IconStyle>'.PHP_EOL;
$xml_out.='</Style>'.PHP_EOL; 
     
$xml_out.='<Style id="affissioniAbusive">'.PHP_EOL;
$xml_out.='<IconStyle>'.PHP_EOL;
$xml_out.='<Icon>'.PHP_EOL;
$xml_out.='<href>'.$settings['sito']['url_ns'].'/images/marker_affissioniAbusive.png</href>'.PHP_EOL;
$xml_out.='</Icon>'.PHP_EOL;
$xml_out.='</IconStyle>'.PHP_EOL;
$xml_out.='</Style>'.PHP_EOL;   
     
$xml_out.='<Style id="vandalismo">'.PHP_EOL;
$xml_out.='<IconStyle>'.PHP_EOL;
$xml_out.='<Icon>'.PHP_EOL;
$xml_out.='<href>'.$settings['sito']['url_ns'].'/images/marker_vandalismo.png</href>'.PHP_EOL;
$xml_out.='</Icon>'.PHP_EOL;
$xml_out.='</IconStyle>'.PHP_EOL;
$xml_out.='</Style>'.PHP_EOL;        
     
$xml_out.='<Style id="zoneVerdi">'.PHP_EOL;
$xml_out.='<IconStyle>'.PHP_EOL;
$xml_out.='<Icon>'.PHP_EOL;
$xml_out.='<href>'.$settings['sito']['url_ns'].'/images/marker_degradoZoneVerdi.png</href>'.PHP_EOL;
$xml_out.='</Icon>'.PHP_EOL;
$xml_out.='</IconStyle>'.PHP_EOL;
$xml_out.='</Style>'.PHP_EOL;  

$xml_out.='<Style id="dissestoStradale">'.PHP_EOL;
$xml_out.='<IconStyle>'.PHP_EOL;
$xml_out.='<Icon>'.PHP_EOL;
$xml_out.='<href>'.$settings['sito']['url_ns'].'/images/marker_sosBuche.png</href>'.PHP_EOL;
$xml_out.='</Icon>'.PHP_EOL;
$xml_out.='</IconStyle>'.PHP_EOL;
$xml_out.='</Style>'.PHP_EOL;  

$xml_out.='<Style id="segnaletica">'.PHP_EOL;
$xml_out.='<IconStyle>'.PHP_EOL;
$xml_out.='<Icon>'.PHP_EOL;
$xml_out.='<href>'.$settings['sito']['url_ns'].'/images/marker_segnaleticaStradale.png</href>'.PHP_EOL;
$xml_out.='</Icon>'.PHP_EOL;
$xml_out.='</IconStyle>'.PHP_EOL;
$xml_out.='</Style>'.PHP_EOL;  

if ($segnalazione['id_tipo'] === '1') echo '#rifiuti';
     if ($segnalazione['id_tipo'] === '2') $segnTipo = '#vandalismo'; 
     if ($segnalazione['id_tipo'] === '3') $segnTipo = '#dissestoStradale';
     if ($segnalazione['id_tipo'] === '4') $segnTipo = '#zoneVerdi';
     if ($segnalazione['id_tipo'] === '5') $segnTipo = '#segnaletica';
     if ($segnalazione['id_tipo'] === '6') $segnTipo = '#affissioniAbusive';

 
if (count($segnalazioni)) {

	foreach ($segnalazioni as $segnalazione) {
		if ($segnalazione['stato']>=300) {
			$stato = "Risolta";
		} else if ($segnalazione['stato']>=200) {
			$stato = "In carico";
		} else if ($segnalazione['stato']>=100) {
			$stato = "In attesa";
		}
		
		$url_segnalazione = $settings['sito']['url'].fixForUri($segnalazione['tipo_nome']).'/'.fixForUri($segnalazione['citta']).'/'.fixForUri($segnalazione['indirizzo']).'/'.$segnalazione['id_segnalazione'].'/';
		$title = "Segnalazione di ".$segnalazione['tipo_nome']." a ".$segnalazione['citta']." in ".$segnalazione['indirizzo'];
		$url_immagine = $settings['sito']['url_ns'].$segnalazione['foto_base_url'].'0-0.jpg';
		$image_filesize = filesize($settings['sito']['percorso'].'images/segnalazioni/'.$segnalazione['id_utente'].'/'.$segnalazione['id_segnalazione'].'/1.jpeg');

		$xml_out.='<Placemark>'.PHP_EOL;
			$xml_out.='<name></name>'.PHP_EOL;
			$xml_out.='<styleUrl>'.$segnTipo.'</styleUrl>'.PHP_EOL;
			$xml_out.="<description><![CDATA[<div style=\"width:500px;\"><div style=\"widht:100%;\"><b>".$segnalazione['tipo_nome']."</b></div><div style=\"width:48%; float:left;\"><img src=\"".$url_immagine."\" style=\"width:100%;\" /></div><div style=\"width:50%; float:right;\">".$segnalazione['messaggio']."</div></div>]]></description>".PHP_EOL;
			$xml_out.="<Point><coordinates>".$segnalazione['lng']." ".$segnalazione['lat']."</coordinates></Point>".PHP_EOL;
			$xml_out.='</Placemark>'.PHP_EOL;

	}
}
$xml_out.='</Document>'.PHP_EOL;
$xml_out.='</kml>'.PHP_EOL;
		
if ($compress) {
	$zip = new ZipStream($nome_file.'.zip');
	$zip->add_file($nome_file.'.kml', $xml_out);
	$zip->finish();
} else {
	echo $xml_out;
}

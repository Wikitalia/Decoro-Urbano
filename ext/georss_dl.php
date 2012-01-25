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
	header('Content-Disposition: attachment; filename='.$nome_file.'.rss');
}

$xml_out='<?xml version="1.0" encoding="UTF-8" ?>'.PHP_EOL;

$xml_out.='<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:georss="http://www.georss.org/georss" xmlns:creativeCommons="http://backend.userland.com/creativeCommonsRssModule">'.PHP_EOL;
/*
	<?php // xmlns:dc="http://purl.org/dc/elements/1.1/" ? >
	<?php // xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" ? >
	<?php // xmlns:slash="http://purl.org/rss/1.0/modules/slash/" ? >
	<?php // xmlns:wfw="http://wellformedweb.org/CommentAPI/"? >
*/
$xml_out.='<channel>'.PHP_EOL;
$xml_out.='<title>DecoroUrbano</title>'.PHP_EOL;
$xml_out.='<atom:link href="'.$pageURL.'" rel="self" type="application/rss+xml" />'.PHP_EOL;
$xml_out.='<link>'.$settings['sito']['url'].'</link>'.PHP_EOL;
$xml_out.='<description>Decoro Urbano è uno strumento partecipativo per la segnalazione del degrado. Un servizio gratuito per le Istituzioni e per il cittadino.</description>'.PHP_EOL;
$xml_out.='<lastBuildDate>'.date('D, d M Y H:i:s O',$segnalazioni[0]['data']).'</lastBuildDate>'.PHP_EOL;
$xml_out.='<language>it</language>'.PHP_EOL;
$xml_out.='<creativeCommons:license>http://creativecommons.org/licenses/by/3.0/it/</creativeCommons:license>'.PHP_EOL;

/*
	<?php / *<sy:updatePeriod>hourly</sy:updatePeriod>
	<sy:updateFrequency>1</sy:updateFrequency>* /? >
*/
 
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

		$xml_out.='<item>'.PHP_EOL;
			$xml_out.='<title>'.$title.'</title>'.PHP_EOL;
			$xml_out.='<link>'.$url_segnalazione.'</link>'.PHP_EOL;
			$xml_out.='<pubDate>'.date('D, d M Y H:i:s O',$segnalazione['data']).'</pubDate>'.PHP_EOL;
			//$xml_out.='<dc:creator>'.$segnalazione['nome'].' '.$segnalazione['cognome'].'</dc:creator>'.PHP_EOL;
			$xml_out.='<description><![CDATA['.$segnalazione['messaggio'].']]></description>'.PHP_EOL;
			$xml_out.='<guid isPermaLink="true">'.$url_segnalazione.'</guid>'.PHP_EOL;
			$xml_out.='<category>'.$segnalazione['tipo_nome'].'</category>'.PHP_EOL;
			$xml_out.='<category>'.$stato.'</category>'.PHP_EOL;
			$xml_out.='<enclosure url="'.$url_immagine.'" length="'.$image_filesize.'" type="image/jpeg" />'.PHP_EOL;


		//<content:encoded><![CDATA[<?php // immagine encondata ? >]]></content:encoded>
		//<wfw:commentRss><?php // eventuale rss dei commenti ? ></wfw:commentRss>
		//<slash:comments><?php // numero commenti ? ></slash:comments>
		//<?php //rss_enclosure(); ? >
		//<?php //do_action('rss2_item'); ? >

			$xml_out.='<georss:point>'.$segnalazione['lat'].' '.$segnalazione['lng'].'</georss:point>'.PHP_EOL;
		$xml_out.='</item>'.PHP_EOL;

	}
}

$xml_out.='</channel>'.PHP_EOL;
$xml_out.='</rss>'.PHP_EOL;

if ($compress) {
	$zip = new ZipStream($nome_file.'.zip');
	$zip->add_file($nome_file.'.rss', $xml_out);
	$zip->finish();
} else {
	echo $xml_out;
}

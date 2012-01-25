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
 * Interfaccia XML per le app di Decoro Urbano per inviare una nuova segnalazione.
 * Accetta una serie di parametri in POST.
 * 
 * @param int id_fb id dell'utente facebook
 * @param string email indirizzo email dell'utente
 * @param string password password dell'utente 
 * @param int categoria indice della categoria della segnalazione
 * @param string messaggio testo della descrizione della segnalazione
 * @param float lat latitudine
 * @param float lng longitudine
 * @param string client stringa identificativa della tipologia di dispositivo, Android o iPhone
 * @param string versione stringa identificativa della versione dell'app
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
require_once('../include/facebook_3.1.1/facebook.php');

// verifica la presenza dei parametri della segnalazione
if (!$_POST['categoria'] || !checkNumericField($_POST['categoria']) ||
    !$_POST['email'] ||
    !$_POST['messaggio'] ||
    !$_POST['lat'] || !is_numeric($_POST['lat']) ||
    !$_POST['lng'] || !is_numeric($_POST['lng'])) {
	$xml_out="";
	$xml_out.="<decorourbano>";
	$xml_out.="<status>";
	$xml_out.="errore_parametri";
	$xml_out.="</status>";
	$xml_out.="</decorourbano>";
	echo $xml_out;
	exit;
}

// verifica la presenza dei parametri per l'autenticazione
if (!($_POST['email'] && $_POST['password']) && !$_POST['id_fb'] && !$_POST['access_token']) {
	$xml_out="";
	$xml_out.="<decorourbano>";
	$xml_out.="<status>";
	$xml_out.="login_errato";
	$xml_out.="</status>";
	$xml_out.="</decorourbano>";
	echo $xml_out;
	exit;
}

$fb_access_token=(isset($_POST['access_token']))?$_POST['access_token']:0;
$id_fb=(isset($_POST['id_fb']))?(int) $_POST['id_fb']:0;
$email=(isset($_POST['email']))?cleanField($_POST['email']):'';
$password=(isset($_POST['password']))?sha1($_POST['password']):'';

$id_tipo = (int) $_POST['categoria'];
$messaggio = strip_tags($_POST['messaggio']);
$lat = (float) $_POST['lat'];
$lng = (float) $_POST['lng'];
$client = $_POST['client'];
$versione = $_POST['versione'];



// recupera i dati dell'utente che sta cercando di inserire la nuova segnalazione, in funzione
// della tipologia, utente Facebook o utente locale
if ($fb_access_token) {

	$facebook = new Facebook(array(
	  'appId'  => $settings['facebook']['app_id'],
	  'secret' => $settings['facebook']['app_secret'],
	  'cookie' => true
	));

	$result = $facebook->api('/me',array('access_token' => $fb_access_token));

	$user=data_get(
		'tab_utenti',
		array('id_fb'=>$result['id'],'confermato'=>1,'eliminato'=>0)
	);

} elseif ($id_fb) {

	// Invio email di avviso al team di DU.
	error_log ("Segnalazione senza FB access token. ID_FB: ".$id_fb." CLIENT: ".$client." ".$versione,1,$settings['email']['indirizzo']);
	
} else {

	$user=data_get(
		'tab_utenti',
		array('email'=>$email,'password'=>$password,'confermato'=>1,'eliminato'=>0)
	);

}


$xml_out="";
$xml_out.="<decorourbano>";

if ($user) {
	
	$idu = (int) $user[0]['id_utente'];
	
	// filtra alcuni campi non necessari
	$campi = array('id_utente' => '', 'email' => '', 'confermato' => '', 'nome' => '', 'cognome' => '');
	$user = array_intersect_key($user[0],$campi);

	// effettua il reverse geocode del punto dove è stata effettuata la segnalazione 
	// per ottenere l'indirizzo
	$geoCodeURL = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lng."&sensor=false&language=it";
	$result = json_decode(file_get_contents($geoCodeURL), true);
	
	foreach($result['results'][0]['address_components'] as $address_component) {
		$address[$address_component['types'][0]] = $address_component['long_name'];
	}
	
	// recupera dal database il comune dove è stata effettuata la segnalazione
	$comune = data_get('tab_comuni',array('nome_url'=>fixForUri($address['locality'])));
	
	// inizializza i campi della segnalazione
	$segnalazione_db['id_comune'] = $comune[0]['id_comune'];
	$segnalazione_db['civico'] = $address['street_number'];
	$segnalazione_db['indirizzo'] = $address['route'];
	$segnalazione_db['indirizzo_url'] = fixForUri($address['route']);
	$segnalazione_db['citta'] = $address['locality'];
	$segnalazione_db['citta_url'] = fixForUri($address['locality']);
	$segnalazione_db['provincia'] = $address['administrative_area_level_2'];
	$segnalazione_db['regione'] = $address['administrative_area_level_1'];
	$segnalazione_db['regione_url'] = fixForUri($address['administrative_area_level_1']);
	$segnalazione_db['nazione'] = $address['country'];
	$segnalazione_db['cap'] = $address['postal_code'];
	$segnalazione_db['id_utente'] = $idu;
	$segnalazione_db['id_tipo'] = $id_tipo;
	$segnalazione_db['messaggio'] = $messaggio;
	$segnalazione_db['lat'] = $lat;
	$segnalazione_db['lng'] = $lng;
	$segnalazione_db['confermata'] = 1;
	
	
	if ($comune[0]['stato'] == 1) {
		// se il comune è attivo la segnalazione viene inserita nello stato in moderazione
		// se l'utente che sta cercando di inserire la segnalazione è l'utente del comune stesso, la segnalazione
		// non necessita di moderazione
		if ($user['id_ruolo'] == $settings['user']['ruolo_utente_comune'] && $comune[0]['id_comune'] == $user['id_comune']) {
			$segnalazione_db['stato'] = $settings['segnalazioni']['in_attesa_comune'];
		} else {
			$segnalazione_db['stato'] = $settings['segnalazioni']['in_moderazione'];
		}
	}

	
	$segnalazione_db['data'] = time();
	$segnalazione_db['client'] = $client;
	$segnalazione_db['versione'] = $versione;
	$segnalazione_db['ip'] = $_SERVER['REMOTE_ADDR'];

	// pulisce l'input
	$segnalazione_db = cleanArray($segnalazione_db);
	// inserisce la segnalazione
	$id_segnalazione = data_insert("tab_segnalazioni",$segnalazione_db);

	if($id_segnalazione) {
		// se l'inserimento nel db è andato a buon fine, costruisce il percorso dell'immagine della 
		// segnalazione
		$dest_dir=$settings['sito']['percorso']."images/segnalazioni/".$segnalazione_db['id_utente']."/".$id_segnalazione;
		// se la cartella di destinazione non esiste la crea
		if (!(file_exists($dest_dir))){
			mkdir ($dest_dir,0755,true);
		}

		// aggiunge il watermark di Decoro Urbano all'immagine della segnalazione
		$image_filigrana = new SimpleImage();
		$image_filigrana->load($settings['sito']['percorso']."images/DU_filigrana.png");

		$image = new SimpleImage();
		$image->load($_FILES['fileimmagine']['tmp_name']);
		// effettua il resize dell'immagine ad una larghezza di 640 px
		if ($image->getWidth() != '640') 
			$image->resizeToWidth(640);
		$image->watermark(0,0,$image_filigrana);
		// salva l'immagine
		$image->save($dest_dir.'/1.jpeg');
		// cancella il file temporaneo
		unlink($_FILES['fileimmagine']['tmp_name']);
		
		// recupera la categoria della segnalazione
		$tipo=data_get('tab_tipi', array('id_tipo'=>$id_tipo),'',array('nome'=>''));
		
		// costruisce il link alla segnalazione, nel seguente formato 
		// http://www.decorourbano.org/<categoria>/<comune>/<indirizzo>/<id segnalazione>/
		// es. http://www.decorourbano.org/sos-buche/rome/via-capo-spartivento/535/
		$link_segnalazione = $settings['sito']['url'].fixForUri($tipo[0]['nome']).'/'.fixForUri($segnalazione_db['citta']).'/'.fixForUri($segnalazione_db['indirizzo']).'/'.$id_segnalazione.'/';
		
		// costruisce il link all'immagine della segnalazione
		$imgSegnalazione = $settings['sito']['url'].'images/segnalazioni/'.fixForUri($tipo[0]['nome']).'-'.$segnalazione_db['citta_url'].'-'.$segnalazione_db['indirizzo_url'].'-'.$idu.'-'.$id_segnalazione.'-280-215.jpg';
		
		// costruisce il link all'immagine della mappa, nel formato
		// http://maps.google.com/maps/api/staticmap?size=<larghezza>x<altezza>&markers=icon:http://www.decorourbano.org/images/marker_DU.png|<lat>,<lng>&sensor=false
		// es. http://maps.google.com/maps/api/staticmap?size=480x480&markers=icon:http://www.decorourbano.org/images/marker_DU.png|700+E+9th+St+NY&sensor=false
		$imgMappa = 'http://maps.google.com/maps/api/staticmap?size=280x215&markers=icon:'.$settings['sito']['url'].'images/marker_DU.png|'.$lat.','.$lng.'&sensor=false&zoom=14';
		
		// inizializza parametri e variabili per l'invio della notifica via email
		$data['from'] = $settings['email']['nome'].' <'.$settings['email']['indirizzo'].'>';
		$data['to'] = $user['nome'].' '.$user['cognome'].' <'.$user['email'].'>';
		$data['template'] = 'segnalazionePubblicazione';
		
		$variabili['nome_utente'] = trim($user['nome'].' '.$user['cognome']);
		$variabili['link_segnalazione'] = $link_segnalazione;
		$variabili['imgSegnalazione'] = $imgSegnalazione;
		$variabili['imgMappa'] = $imgMappa;
		$variabili['via'] = $segnalazione_db['indirizzo'];
		$variabili['citta'] = $segnalazione_db['citta'];
		$variabili['messaggio'] = stripslashes($segnalazione_db['messaggio']);
		$variabili['categoria'] = $tipo[0]['nome'];
		$variabili['data'] = strftime('%e %B %Y %R');
		
		$data['variabili'] = $variabili;
		
		// la notifica va inviata solo se il comune non è attivo, altrimenti
		// la segnalazione non viene pubblicata immediatamente e quindi la notifica 
		// arriverà solo al momento dell'accettazione della segnalazione
		if ($comune[0]['stato'] == 0) {
			email_with_template($data);
		}
		
		// costruisce la stringa XML di risposta
		$xml_out.="<status>";
		$xml_out.="ok";
		$xml_out.="</status>";
		
		$xml_out.="<id_segnalazione>";
		$xml_out.=$id_segnalazione;
		$xml_out.="</id_segnalazione>";

		
		$xml_out.="<civico>";
		$xml_out.=$segnalazione_db['civico'];
		$xml_out.="</civico>";
		
		$xml_out.="<indirizzo>";
		$xml_out.=$segnalazione_db['indirizzo'];
		$xml_out.="</indirizzo>";
		
		$xml_out.="<citta>";
		$xml_out.=$segnalazione_db['citta'];
		$xml_out.="</citta>";
		
		$xml_out.="<provincia>";
		$xml_out.=$segnalazione_db['provincia'];
		$xml_out.="</provincia>";
		
		$xml_out.="<regione>";
		$xml_out.=$segnalazione_db['regione'];
		$xml_out.="</regione>";
		
		$xml_out.="<nazione>";
		$xml_out.=$segnalazione_db['nazione'];
		$xml_out.="</nazione>";
		
		$xml_out.="<cap>";
		$xml_out.=$segnalazione_db['cap'];
		$xml_out.="</cap>";
		
		$xml_out.="<comune_attivo>";
		$xml_out.=$comune[0]['stato'];
		$xml_out.="</comune_attivo>";
		
		$xml_out.="<url>";
		$xml_out.=$link_segnalazione;
		$xml_out.="</url>";
		
		$array_num_segnalazioni = user_segnalazioni_count($segnalazione_db['id_utente']);
		
		$xml_out.="<num_segnalazioni>";
		$xml_out.=$array_num_segnalazioni[0]['num_segnalazioni'];
		$xml_out.="</num_segnalazioni>";

		
	} else {
		$xml_out.="<status>";
		$xml_out.="errore_inserimento";
		$xml_out.="</status>";
	}

} else {
	$xml_out.="<status>";
	$xml_out.="login_errato";
	$xml_out.="</status>";
}

$xml_out.="</decorourbano>";

echo $xml_out;

<?php

/*
 * ----------------------------------------------------------------------------
 * Decoro Urbano version 0.4.0
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
 * Interfaccia XML per le app di Decoro Urbano per effettuare il login utente
 * 
 * @param int id_fb id dell'utente facebook
 * @param string email indirizzo email dell'utente
 * @param string password password dell'utente 
 */

ini_set('display_errors', 0);
error_reporting(0);

require_once("../include/config.php");
require_once("../include/db_open.php");
require_once("../include/db_open_funzioni.php");
require_once("../include/funzioni.php");
require_once("../include/controlli.php");
require_once("../include/decorourbano.php");

$id_fb=(isset($_POST['id_fb']))?cleanField($_POST['id_fb']):0;
$email=(isset($_POST['email']))?cleanField($_POST['email']):'';
$password=(isset($_POST['password']))?sha1($_POST['password']):'';

$user = null;

if ($id_fb) {
	// in caso di utente Facebook 
	$fb_user['id'] = $id_fb;
	$fb_user['first_name'] = (isset($_POST['first_name']))?cleanField($_POST['first_name']):'';
	$fb_user['last_name'] = (isset($_POST['last_name']))?cleanField($_POST['last_name']):'';
	$fb_user['email'] = $email;
	$fb_user['username'] = (isset($_POST['username']))?cleanField($_POST['username']):'';
	
	if (!($user_data_with_fb_id = user_fb_get_from_db($fb_user['id']))) {
		// se l'utente non è ancora presente nel DB, lo inserisco
		user_fb_insert($fb_user);
		
		// invia l'email di benvenuto al nuovo utente
		$data['from'] = $settings['email']['nome'].' <'.$settings['email']['indirizzo'].'>';
		$data['to'] = $email;
		$data['template'] = 'registrazioneBenvenuto';
		$variabili['nome_utente'] = trim($fb_user['first_name'].' '.$fb_user['last_name']);
		$data['variabili'] = $variabili;
		email_with_template($data);
		
		// recupera le informazioni del nuovo utente
		$user=data_get('tab_utenti', array('id_fb'=>$id_fb));
	} else if (!$user_data_with_fb_id['eliminato']) {
		$user=data_get('tab_utenti', array('id_fb'=>$id_fb));
	}
} else if ($email != '' && $password != '') {
	
	// nel caso di un utente locale, recupera i dati dell'utente corrispondenti al login specificato
	$user=data_get(
		'tab_utenti',
		array('email'=>$email,'password'=>$password,'confermato'=>1,'eliminato'=>0)
	);
}


$xml_out="";
$xml_out.="<decorourbano>";

if (count($user)) {
	// se è stato trovato un utente nel db, il login è valido
	
	$campi = array('id_utente' => '', 'nome' => '', 'cognome' => '', 'citta' => '', 'quartiere' => '', 'sito' => '', 'facebook_url' => '', 'twitter' => '', 'about' => '', 'data' => '');
	$user = array_intersect_key($user[0],$campi);

	$xml_out.="<status>";
	$xml_out.="ok";
	$xml_out.="</status>";
	
	foreach($user as $key => $field) {
		$xml_out.="<$key>";
		$xml_out.=$field;
		$xml_out.="</$key>";
	}
	
	$array_num_segnalazioni = user_segnalazioni_count($user['id_utente']);
	
	$xml_out.="<num_segnalazioni>";
	$xml_out.=$array_num_segnalazioni[0]['num_segnalazioni'];
	$xml_out.="</num_segnalazioni>";
	
	$xml_out.="<avatar>";
	$xml_out.=user_avatar_get($user[0]);
	$xml_out.="</avatar>";
	
} else {
	$xml_out.="<status>";
	$xml_out.="login_errato";
	$xml_out.="</status>";
}

$xml_out.="</decorourbano>";

echo $xml_out;
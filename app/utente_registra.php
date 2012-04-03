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
 * Interfaccia XML per le app di Decoro Urbano per effettuare la registrazione di un nuovo utente
 * 
 * @param string nome 
 * @param string cognome 
 * @param string email 
 * @param string password
 */

ini_set('display_errors', 0);
error_reporting(0);

require_once("../include/config.php");
require_once("../include/db_open.php");
require_once("../include/db_open_funzioni.php");
require_once("../include/decorourbano.php");
require_once("../include/funzioni.php");
require_once("../include/controlli.php");

$fields['nome']=$_POST['nome'];
$fields['cognome']=$_POST['cognome'];
$fields['email']=$_POST['email'];
$fields['password']=sha1($_POST['password']);
$fields['id_ruolo']=$settings['user']['ruolo_utente_normale'];
$fields['data']=time();

$fields = cleanArray($fields);

$errori = array();

// validazione dei campi
if ($fields['nome']=='') {
	$errori[]='nome_mancante';
}

if ($fields['cognome'] == '') {
	$errori[]='cognome_mancante';
}

if (!checkEmailField($fields['email'])) {
	$errori[]='email_mancante';
}

if (strlen($_POST['password']) < 6) {
	$errori[]='password_non_valida';
}

if (!user_email_check($fields['email'])) {
	$errori[]='email_esistente';
}

$xml_out="";
$xml_out.="<decorourbano>";


if (!count($errori)) {
	// se non sono presenti errori inserisci il nuovo utente
	$id_utente = data_insert('tab_utenti', $fields);
	
	// genera il codice di verifica
	$key=$settings['sito']['encrypt_key'];
	$salt=$settings['sito']['hashsalt'];

	$email_code = code_encrypt($salt.$id_utente, $key);
	
	// inizializza parametri e variabili per l'invio dell'email di attivazione del nuovo utente
	$from = $settings['email']['nome'].' <'.$settings['email']['indirizzo'].'>';
	$to=$fields['nome'].' '.$fields['cognome'].' <'.$fields['email'].'>';
	$link=$settings['sito']['confermaRegistrazione']."?s=".$email_code;
	
	$data['from'] = $from;
	$data['to'] = $to;
	$data['template'] = 'registrazioneConferma';
	$variabili['nome_utente'] = $fields['nome'].' '.$fields['cognome'];
	$variabili['link_registrazione'] = $link;
	$data['variabili'] = $variabili;
	// invia l'email di attivazione
	email_with_template($data);

	$xml_out.="<status>";
	$xml_out.="ok";
	$xml_out.="</status>";
	
	$xml_out.="<id_utente>";
	$xml_out.=$id_utente;
	$xml_out.="</id_utente>";

	$xml_out.="<data>";
	$xml_out.=$fields['data'];
	$xml_out.="</data>";

} else {
	$xml_out.="<status>";
	$xml_out.="ko";
	$xml_out.="</status>";
	foreach ($errori as $key => $errore) {
		$xml_out.="<messerrore_$key>";
		$xml_out.=$errore;
		$xml_out.="</messerrore_$key>";
	}


}

$xml_out.="</decorourbano>";
echo $xml_out;

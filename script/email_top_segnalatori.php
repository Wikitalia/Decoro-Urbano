<?php

require_once("../include/config.php");
require_once('../include/db_open.php');
require_once('../include/db_open_funzioni.php');
require_once('../include/funzioni.php');
require_once('../include/decorourbano.php');

$nuovi_top = segnalatori_top_nuovi_get();

foreach ($nuovi_top as $nuovo_top) {

	if ($nuovo_top['email_top']) {

		$data['from'] = $settings['email']['nome'].' <'.$settings['email']['indirizzo'].'>';
		$data['to'] = $nuovo_top['nome'].' '.$nuovo_top['cognome'].' <'.$nuovo_top['email'].'>';
		$data['template'] = 'segnalatoreTop';
		$variabili['nome_utente'] = $nuovo_top['nome'];
		$data['variabili'] = $variabili;
		email_with_template($data);
	
	}
	
}

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

if (isset($_GET['d']) && $_GET['d']) $settings['sito']['debug'] = 1;

if ($settings['sito']['debug']) {
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
} else {
	ini_set('display_errors', 0);
	error_reporting(0);
}

require_once('../include/config.php');
require_once('../include/db_open.php');
require_once('../include/db_open_funzioni.php');
require_once('../include/funzioni.php');
require_once('../include/controlli.php');
require_once('../include/smarty.php');
require_once('../include/decorourbano.php');

$smarty->assign('settings', $settings);

// Il widget può essere generato solo associato ad comune (da verificare)
if ($_GET['c']) {

	$comune = data_get('tab_comuni',array('nome_url'=>cleanField($_GET['c'])));

	$file_logo = $settings['sito']['percorso'].'images/loghi_comuni/'.$comune[0]['nome_url'].'_logo.png';
		
	if (is_file ($file_logo))
		$comune_logo = $settings['sito']['url'].'images/loghi_comuni/'.$comune[0]['nome_url'].'_logo.png';
	else
		$comune_logo = '';

	$comune_url = 'http://'.$_GET['c'].'.'.$settings['sito']['dominio'];
	
	$smarty->assign("comune",$comune[0]['nome']);
	$smarty->assign("comune_attivo",$comune[0]['stato']);
	$smarty->assign("comune_logo",$comune_logo);
	$smarty->assign("comune_url",$comune_url);
	

	
	if ($_GET['url']) {
		$smarty->assign("url",urldecode($_GET['url']));
	}
	
	// Aggiungere mappa delle segnalazioni
	if ($_GET['m']=="1") {
		$smarty->assign("m",1);
		
		$parametri['id_comune'] = $comune[0]['id_comune'];
		$parametri['formato'] = 0;
		
		$segnalazioni = segnalazioni_get($parametri);
		$segnalazioni_json = json_encode($segnalazioni);
		$segnalazioni_json = escapeJSON($segnalazioni_json);
		
		$smarty->assign("segnalazioni_json",$segnalazioni_json);
		$smarty->assign("comune_lat",$comune[0]['lat']);
		$smarty->assign("comune_lng",$comune[0]['lng']);
		
	}
	
	// Aggiungere conteggio segnalazioni
	if ($_GET['n']=="1") {
		$smarty->assign("n",1);
	
		$parametri['id_comune'] = $comune[0]['id_comune'];
		
		if (!$segnalazioni) {
			$segnalazioni = segnalazioni_get($parametri);
		}
		
		$count_totale = count($segnalazioni);
		$count_risolte = 0;
		$count_carico = 0;
		
		foreach ($segnalazioni as $segnalazione) {
			if ($segnalazione['stato']>=300) {
				$count_risolte++;
			} else if ($segnalazione['stato']>=200) {
				$count_carico++;
			}
		}
		
		$smarty->assign('count_totale',$count_totale);
		$smarty->assign('count_risolte',$count_risolte);
		$smarty->assign('count_carico',$count_carico);
		
	}
	
	// Aggiungere box ultime segnalazioni
	if ($_GET['u']=="1") {
		$smarty->assign("u",1);
		
		$parametri['id_comune'] = $comune[0]['id_comune'];
		$parametri['id_user'] = 0;
		$parametri['recenti'] = 0;
		$parametri['nuove'] = 0;
		$parametri['vecchie'] = 0;
		$parametri['limit'] = 3;
		$parametri['distanza'] = 0;
		$parametri['commenti'] = 0;
		$parametri['area'] = array();
		$parametri['formato'] = 0;
		unset($parametri['stato']);
		
		$ultime_segnalazioni = segnalazioni_get($parametri);
		$smarty->assign('ultime_segnalazioni', $ultime_segnalazioni);
	
	}
	
	// Aggiungere widget Twitter
	if ($_GET['tw']==1) {
		$smarty->assign("tw",1);
	}

	
	

	
}
$smarty->display($settings['sito']['percorso'].'templates/widget.tpl');
?>
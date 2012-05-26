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

require_once('include/config.php');

ini_set("session.cookie_domain", ".".$settings['sito']['dominio']);
session_start();

// Impostazione politiche di error reporting in funzione del flag di debug
if ($settings['sito']['debug']) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

require_once('include/db_open.php');
require_once('include/db_open_funzioni.php');
require_once('include/funzioni.php');
require_once('include/controlli.php');
require_once('include/smarty.php');
require_once('include/decorourbano.php');



if ($_SESSION['user']['id_utente'] == 99) {
    error_log ('index.php $_SESSION:'.PHP_EOL.print_r($_SESSION,1),1,$settings['email']['indirizzo']);
    Auth::user_logout();
}

// Inizializzazione parametri generali in smarty
$smarty->assign('settings', $settings);

// Non è usato sul DB. Viene solo controllato che sia una stringa e viene trimmata
$page = (isset($_GET['page']) && checkTextField($_GET['page'])) ? trim($_GET['page']) : '';

// recupera la lista delle categorie delle segnalazioni
$tipi = data_get('tab_tipi');
$smarty->assign('tipi', $tipi);

$abitanti_attivi = get_abitanti_attivi();
$smarty->assign('abitanti_attivi',$abitanti_attivi[0]['abitanti_attivi']);

Auth::init();
$user = Auth::user_get();
$fb_user = Auth::user_fb_get();
$user_eliminato = Auth::user_is_eliminato();
$cookie = Auth::cookie_get();

if ($user)
	$smarty->assign('user', $user);
else
  Auth::user_logout();

if ($fb_user)
	$smarty->assign('fb_user', $fb_user);

if ($user_eliminato)
	$smarty->assign('utente_fb_eliminato', 1);

if ($cookie)
	$smarty->assign('cookie', $cookie);

$smarty->assign('metaDesc', '');

// La variabile user è null se l'utente non è loggato quindi la possiamo usare per le autorizzazioni sulle pagine
if (!$user && (!isset($auth[$page]) || !$auth[$page][0])) {
	if (curPageURL() == $settings['sito']['url']) {
		$page = 'prehome';
	} else {
		header('Location: ' . $settings['sito']['url']);
		exit;
	}
}

$smarty->assign('page', $page);
$smarty->config_load('testi/ITA.conf', $page);



switch ($page) {
	case 'prehome':
		include('pagine/prehome.php');
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'] . 'templates/prehome.tpl');
		break;
	// pagine utente
	case 'principale':
		include('pagine/principale.php');
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'] . 'templates/principale.tpl');
		break;
	case 'modificaProfilo':
		include('pagine/modificaProfilo.php');
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'] . 'templates/modificaProfilo.tpl');
		break;
	case 'impostazioni':
		include('pagine/impostazioni.php');
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'] . 'templates/impostazioni.tpl');
		break;
	case 'logout':
		include('pagine/logout.php');
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'] . 'templates/logout.tpl');
		break;
	case 'vediProfilo':
		include('pagine/vediProfilo.php');
		$smarty->display($settings['sito']['percorso'] . 'templates/vediProfilo.tpl');
		break;
	case 'registrati':
		include('pagine/registrati.php');
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'] . 'templates/registrati.tpl');
		break;
	case 'confermaRegistrazione':
		include('pagine/confermaRegistrazione.php');
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'] . 'templates/confermaRegistrazione.tpl');
		break;
	// strumenti
	case 'mappa':
		include('pagine/mappa.php');
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'].'templates/mappa.tpl');
		break;
	case 'passDimenticata':
		include('pagine/passDimenticata.php');
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'] . 'templates/passDimenticata.tpl');
		break;
	case 'inviaSegnalazione':
		include('pagine/inviaSegnalazione.php');
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'] . 'templates/inviaSegnalazione.tpl');
		break;
	case 'inviaBuonaPratica':
		include('pagine/inviaBuonaPratica.php');
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'] . 'templates/inviaBuonaPratica.tpl');
		break;
	case 'listaSegnalazioni':
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		include('pagine/listaSegnalazioni.php');
		$smarty->display($settings['sito']['percorso'] . 'templates/listaSegnalazioni.tpl');
		break;
	case 'listaSegnalazioni2':
		include('pagine/listaSegnalazioni2.php');
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'] . 'templates/listaSegnalazioni2.tpl');
		break;
	case 'dettaglioSegnalazione':
		include('pagine/dettaglioSegnalazione.php');
		$smarty->display($settings['sito']['percorso'] . 'templates/dettaglioSegnalazione.tpl');
		break;
	case 'applicazioni':
		include('pagine/applicazioni.php');
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'] . 'templates/applicazioni.tpl');
		break;
	// Community
	case 'amici':
		include('pagine/amici.php');
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'] . 'templates/amici.tpl');
		break;
	case 'gruppi':
		include('pagine/gruppi.php');
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'] . 'templates/gruppi.tpl');
		break;
	case 'eventi':
		include('pagine/eventi.php');
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'] . 'templates/eventi.tpl');
		break;
	// Classifiche
	case 'topSegnalatori':
		include('pagine/topSegnalatori.php');
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'] . 'templates/topSegnalatori.tpl');
		break;
	case 'topGruppi':
		include('pagine/topGruppi.php');
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'] . 'templates/topGruppi.tpl');
		break;
	// Documentazione
	case 'FAQs':
		include('pagine/FAQs.php');
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'] . 'templates/FAQs.tpl');
		break;
	case 'news':
		include('pagine/news.php');
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'] . 'templates/news.tpl');
		break;
	case 'funzioniDU':
		include('pagine/funzioniDU.php');
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'] . 'templates/funzioniDU.tpl');
		break;
	case 'suDU':
		include('pagine/suDU.php');
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'] . 'templates/suDU.tpl');
		break;
	case 'awards':
		include('pagine/awards.php');
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'] . 'templates/awards.tpl');
		break;
	case 'guida':
		include('pagine/guida.php');
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'] . 'templates/guida.tpl');
		break;
	case 'patrocini':
		include('pagine/patrocini.php');
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'] . 'templates/patrocini.tpl');
		break;
	case 'supporta':
		include('pagine/supporta.php');
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'] . 'templates/supporta.tpl');
		break;
	case 'tos':
		include('pagine/tos.php');
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'] . 'templates/tos.tpl');
		break;
	case 'privacy':
		include('pagine/privacy.php');
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'] . 'templates/privacy.tpl');
		break;
	case 'contatti':
		include('pagine/contatti.php');
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'] . 'templates/contatti.tpl');
		break;
	case 'confermaRegistrazione':
		include('pagine/confermaRegistrazione.php');
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'] . 'templates/privacy.tpl');
		break;
	case 'creaWidget':
		include('pagine/creaWidget.php');
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'].'templates/creaWidget.tpl');
		break;
	case 'open':
		include('pagine/open.php');
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'].'templates/open.tpl');
		break;
	case 'wikitalia':
		include('pagine/wikitalia.php');
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'].'templates/wikitalia.tpl');
		break;
	case 'comuniAttivi':
		include('pagine/comuniAttivi.php');
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'].'templates/comuniAttivi.tpl');
		break;
	case 'errore':
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'] . 'templates/errore.tpl');
		break;
	case 'paginaNonTrovata':
		$smarty->assign('pageTitle', $settings['sito']['title'][$page]);
		$smarty->display($settings['sito']['percorso'] . 'templates/errore404.tpl');
		break;
	default:
		include('pagine/principale.php');
		$page = 'principale';
		$smarty->assign('page', $page);
		$smarty->config_load('testi/ITA.conf', $page);
		$smarty->assign('pageTitle', $settings['sito']['title']['principale']);
		$smarty->display($settings['sito']['percorso'] . 'templates/principale.tpl');
}

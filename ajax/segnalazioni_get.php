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

/**
 * Chiamata AJAX per recuperare una lista di segnalazioni filtrata in funzione
 * dei parametri passati alla richiesta
 * 
 * @param string reg nome della regione
 * @param int idc id del comune
 * @param int id_competenza id della competenza
 * @param string genere genere della segnalazione (buone-pratiche, degrado)
 * @param int tipoX attiva il filtro sulla categoria X
 * @param int idu id dell'utente
 * @param booelan w restituisce le segnalazioni del wall di un utente
 * @param int t_new segnalazioni degli ultimi t_new giorni
 * @param int t_newer segnalazioni più recenti di un timestamp di riferimento
 * @param int stato indice dello stato su cui filtrare le segnalazioni
 * @param int c aggiunge i commenti delle segnalazioni
 * @param int t_old segnalazioni più vecchie di un timestamp riferimento
 * @param int l limita il numero delle segnalazioni restituite a l
 * @param float d aggiunge al risultato il numero di segnalazioni in zona
 * @param float minLat,maxLat,minLng,maxLng vertici dell'area su cui filtrare le segnalazioni
 */

require_once("../include/config.php");
require_once("../include/db_open.php");
require_once("../include/db_open_funzioni.php");
require_once("../include/decorourbano.php");
require_once("../include/funzioni.php");
require_once("../include/controlli.php");

// regione
$regione=(isset($_GET['reg']))?fixForUri($_GET['reg']):0;

// genere
$genere=(isset($_GET['genere']))?$_GET['genere']:0;

// se il parametro passato per la regione non è presente nella lista delle regioni
// cancellalo
if (!in_array($regione, $settings['geo']['regioni'])) {
    $region = '';
}

// filtro sulle segnalazioni di uno specifico comune
$id_comune=(isset($_GET['idc']))?(int) $_GET['idc']:0;

// filtro sulle segnalazioni di una specifica competenza
$id_competenza=(isset($_GET['id_competenza']))?(int) $_GET['id_competenza']:-1;

// gestione del filtro sulle categorie
$tipi = array();
$tipi[1]=($_GET['tipo1']=='1')?1:0;
$tipi[2]=($_GET['tipo2']=='1')?1:0;
$tipi[3]=($_GET['tipo3']=='1')?1:0;
$tipi[4]=($_GET['tipo4']=='1')?1:0;
$tipi[5]=($_GET['tipo5']=='1')?1:0;
$tipi[6]=($_GET['tipo6']=='1')?1:0;

// filtro segnalazioni di un utente specifico
$user=(isset($_GET['idu']))?(int) $_GET['idu']:0;

// filtro sulle ultime segnalazioni effettuate
$recenti=(isset($_GET['t_new']))?(int)$_GET['t_new']:0;

// filtro sulle segnalazioni più recenti di un riferimento
$nuove=(isset($_GET['t_newer']))?(int) $_GET['t_newer']:0;

// restituisce le segnalazioni del wall dell'utente
$wall=(isset($_GET['w']))?1:0;

// filtro sulle segnalazioni in uno stato specifico
$stato=(isset($_GET['stato']))?(int) $_GET['stato']:0;

// aggiunge i commenti
$commenti=(isset($_GET['c']))?(int) $_GET['c']:0;

// filtro sulle segnalazioni più vecchie di un riferimento
$vecchie=(isset($_GET['t_old']))?(int) $_GET['t_old']:0;

// limita il numero delle segnalazioni restituite
$limit_numero=(isset($_GET['l']))?(int) $_GET['l']:0;

// aggiunge al risultato il numero di segnalazioni in zona
$distanza=(isset($_GET['d']))?(float) $_GET['d']:0;

// filtra le segnalazioni all'interno di un'area
if (isset($_GET['minLat']) && isset($_GET['maxLat']) && isset($_GET['minLng']) && isset($_GET['maxLng'])) {

	$area['minLat'] = (float) $_GET['minLat'];
	$area['maxLat'] = (float) $_GET['maxLat'];
	$area['minLng'] = (float) $_GET['minLng'];
	$area['maxLng'] = (float) $_GET['maxLng'];
			
} else {
    $area = array();
}


$parametri['genere'] = $genere;
$parametri['regione'] = $regione;
$parametri['id_comune'] = $id_comune;
$parametri['id_competenza'] = $id_competenza;
$parametri['id_user'] = $user;
$parametri['tipi'] = $tipi;
$parametri['recenti'] = $recenti;
$parametri['stato'] = $stato;
$parametri['nuove'] = $nuove;
$parametri['commenti'] = $commenti;
$parametri['wall'] = $wall;
$parametri['limit'] = $limit_numero;
$parametri['vecchie'] = $vecchie;
$parametri['distanza'] = $distanza;
$parametri['area'] = $area;
$parametri['formato'] = 1;

$parametri = cleanArray($parametri);
 

if ($wall==1) {
    $segnalazioni = segnalazioni_user_wall_get($parametri);
} else {
    $segnalazioni = segnalazioni_get($parametri);
}

echo $segnalazioni;

?>
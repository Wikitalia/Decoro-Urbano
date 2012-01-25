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

/**
 * Chiamata AJAX per eliminare la sottoscrizione di una segnalazione da parte di un utente
 * 
 * @param ids id della segnalazione
 * 
 */
session_start();

require_once("../include/config.php");
require_once("../include/db_open.php");
require_once("../include/db_open_funzioni.php");
require_once("../include/funzioni.php");
require_once("../include/controlli.php");
require_once('../include/decorourbano.php');

// verifica la presenza dei parametri
if (!$_GET['ids'] || !checkNumericField($_GET['ids'])) {
    echo "-1";
    exit;
}

// recupera i dati dell'utente loggato
$user = logged_user_get();

// se non c'è nessun utente loggato, esce
if (!$user) {
    echo "-1";
    exit;
}

$idu = (int) $user['id_utente'];
$ids = (int) cleanField($_GET['ids']);

// rimuove il record relativo al follow
if (!segnalazione_follow_delete($ids, $idu)) {
    echo "-1";
    exit;
}

// restituisce il numero di followers della segnalazione passata come parametro
echo segnalazione_follow_count($ids);

?>

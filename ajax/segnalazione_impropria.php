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
 * Chiamata AJAX per registrare la notifica di una segnalazione impropria da parte
 * di un utente registrato
 * 
 * @param ids id della segnalazione
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
    echo '0';
    exit;
}

// recupera i dati dell'utente loggato
Auth::init();
$user = Auth::user_get();

// se non c'è nessun utente loggato esce
if (!$user) {
    echo "0";
    exit;
}

$idu = (int) $user['id_utente'];
$ids = (int) $_GET['ids'];

if (!segnalazione_impropria_insert($ids, $idu)) {
    echo "0";
} else {
    echo "1";
}

?>

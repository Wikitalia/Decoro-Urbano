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
 * Chiamata AJAX per cancellare l'avatar dell'utente loggato
 */
session_start();

require_once("../include/config.php");
require_once("../include/db_open.php");
require_once("../include/db_open_funzioni.php");
require_once("../include/funzioni.php");
require_once("../include/controlli.php");
require_once('../include/decorourbano.php');

// recupera i dati dell'utente loggato
$user = logged_user_get();

// se non c'è alcun utente loggato restituisci un errore
if (!$user) {
    $out['status'] = 'session_error';
    echo json_encode($out);
    exit;
}

// costruisci il percorso del file dell'avatar dell'utente
$uid = (int) $user['id_utente'];
$uploadDir = '../images/avatar/' . $uid;

// cancella il file
@unlink($uploadDir . '/1.jpeg');

$user = data_get('tab_utenti', array('id_utente' => $uid));

echo user_avatar_get($user[0]);

?>

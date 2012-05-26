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
 * Chiamata AJAX per l'aggiunta di un commento ad una segnalazione
 * 
 * @param ids id della segnalazione
 * @param commento testo del commento da inserire
 * 
 */
session_start();

require_once("../include/config.php");
require_once("../include/db_open.php");
require_once("../include/db_open_funzioni.php");
require_once("../include/decorourbano.php");
require_once("../include/funzioni.php");
require_once("../include/controlli.php");

// verifica la presenza dei parametri necessari all'inserimento del commento
if (!$_POST['ids'] || !$_POST['commento'] || !checkNumericField($_POST['ids'])) {
    echo '0';
    exit;
}


// verifico che l'utente sia loggato
Auth::init();
$user = Auth::user_get();

if (!$user) {
    echo '0';
    exit;
}


// costruisce l'array contenenti i dati del commento da inserire
$commento_db['id_segnalazione'] = (int) $_POST['ids']; // id della segnalazione
$commento_db['id_utente'] = (int) $user['id_utente']; // id dell'utente che ha inserito il commento
$commento_db['commento'] = str_replace('\n', PHP_EOL, strip_tags($_POST['commento'])); // testo del commento senza interruzioni di riga
$commento_db['confermato'] = $settings['commenti']['conferma_automatica']; // flag di moderazione del commento
$commento_db['data'] = time(); // timestamp

$commento_db = cleanArray($commento_db);

$id_commento = commento_insert($commento_db);

if ($id_commento) {
    echo $id_commento;
} else {
    echo '0';
}

?>

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
 * Chiamata AJAX per flaggare un commento come improprio
 * 
 * @param int idc id del commento da flaggare come improprio
 */
session_start();

require_once("../include/config.php");
require_once("../include/db_open.php");
require_once("../include/db_open_funzioni.php");
require_once("../include/funzioni.php");
require_once("../include/controlli.php");
require_once('../include/decorourbano.php');

// verifica la presenza dei parametri
if (!$_GET['idc']) {
    echo '0';
    exit;
}

// pulisco il parametro idc
$idc = (int) $_GET['idc'];

// verifico che l'utente sia loggato
$user = logged_user_get();

// se l'utente non è loggato esce
if (!$user) {
    echo "0";
    exit;
}

// recupera l'id dell'utente che esegue l'azione
$idu = (int) $user['id_utente'];

// esegue l'inserimento della segnalazione di commento improprio
if (!checkNumericField($idc) || !checkNumericField($idu) || !commento_improrio_insert($idc, $idu)) {
    echo "0";
} else {
    echo "1";
}

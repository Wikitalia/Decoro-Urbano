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
 * Pagina di gestione del login utente
 */

require_once('../include/db_open.php');
require_once('../include/db_open_funzioni.php');
require_once('../include/funzioni.php');
require_once('../include/controlli.php');
require_once('../include/decorourbano.php');


ini_set("session.cookie_domain", ".".$settings['sito']['dominio']);
session_start();

if ($settings['admin_comuni']['debug']) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}


Auth::init();
$user = Auth::user_get();
$fb_user = Auth::user_fb_get();
$user_eliminato = Auth::user_is_eliminato();
$cookie = Auth::cookie_get();


if (!$user || $user['id_ruolo'] != $settings['user']['ruolo_utente_comune']) {
    Auth::user_logout();
    header("location: login-form.php?e=1");
    exit();
} else {
    header("location: segnalazioni_elenco.php");
}

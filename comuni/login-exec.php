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


$user = logged_user_get();

// Se non c'è utente da session vediamo se i dati dal post o i cookie ci consentono di fare login
if (!$user) {
    // Se ho fatto il post dal form di login OPPURE se ho i dati dai cookie posso procedere ad effettuare il login
    if (isset($_POST['login_form']) || $cookie = cookie_data_get()) {

        if (isset($cookie))
            $smarty->assign('cookie', $cookie);

        $email = (isset($_POST['email'])) ? cleanField($_POST['email']) : cleanField($cookie['user_email']);
        $password = (isset($_POST['password'])) ? cleanField($_POST['password']) : cleanField($cookie['user_password']);
        $setcookie = (isset($_POST['restaCollegato']) && $_POST['restaCollegato'] == 'on') ? 1 : 0;
        //Controllo credenziali di accesso. se sono giuste metti i dati in cookie e session e procedo normalmente.
        if (!user_login($email, $password, $setcookie)) {
            
        } else {
            $user = logged_user_get();
        }
    }
}

if (!$user || $user['id_ruolo'] != $settings['user']['ruolo_utente_comune']) {
    user_logout();
    header("location: login-form.php?e=1");
    exit();
} else {
    header("location: segnalazioni_elenco.php");
}

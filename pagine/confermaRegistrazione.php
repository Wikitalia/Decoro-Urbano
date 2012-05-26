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
 * Pagina di conferma della registrazione di un utente.
 * Accetta un parametro 's' contenente il codice di attivazione dell'utente.
 * @param string s è una stringa criptata contenente l'id dell'utente.
 */

// recupera l'id utente contenuto nel codice di attivazione
$key = $settings['sito']['encrypt_key'];
$salt = $settings['sito']['hashsalt'];

$decrypted = code_decrypt($_GET['s'], $key);
// controlla che la stringa sia valida, verificando la presenza del salt
if (strpos($decrypted, $salt)===FALSE) {
    // se la stringa non è valida ritorna un errore
    $smarty->assign('errore_conferma', '1');
    
} else {

    // se la stringa è valida, estrae l'id utente
    $id_utente = str_replace($salt, '', $decrypted);

    // se l'id utente è stato recuperato in modo corretto, aggiorna lo stato dell'utente
    // nel database
    if (is_numeric($id_utente) && data_update('tab_utenti', array('confermato' => 1), array('id_utente' => $id_utente))) {
    
    		unset($_SESSION['ERRMSG_ARR']);
    		Auth::user_set_session($user);

        // invia email di conferma attivazione
        $data['from'] = $settings['email']['nome'] . ' <' . $settings['email']['indirizzo'] . '>';
        $data['to'] = $user['email'];
        $data['template'] = 'registrazioneBenvenuto';
        $variabili['nome_utente'] = stripslashes(trim($user['nome'] . ' ' . $user['cognome']));
        $data['variabili'] = $variabili;
        email_with_template($data);

        // redireziona alla home page
        header('Location: ' . $settings['sito']['login_url']);
        exit();
    } else {
        $smarty->assign('errore_conferma', '1');
    }
}

?>
		
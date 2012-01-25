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
 * Pagina contenente il form di modifca del profilo utente.
 * I dati che possono essere modificati:
 * - password (in caso di un utente locale)
 * - città
 * - quartiere
 * - sito personale
 * - descrizione personale
 * - link al profilo twitter
 * - link al profilo facebook
 */

// post del form di modifica del profilo utente
if (isset($_POST['form_profilo_utente1'])) {
    
    // recupera l'id dell'utente loggato
    $id_utente = $user['id_utente'];

    // verifica i campi per la modifica password
    if (isset($_POST['utentePass']) && isset($_POST['utentePass2']) && $_POST['utentePass'] != '' && $_POST['utentePass'] == $_POST['utentePass2']) {
        $fields['password'] = sha1($_POST['utentePass']);
    }

    // inizializza e pulisce i campi da modificare
    $fields['citta'] = $_POST['citta'];
    $fields['about'] = $_POST['about'];
    $fields['quartiere'] = $_POST['quartiere'];
    $fields['sito'] = $_POST['sito'];
    $fields['facebook_url'] = $_POST['facebook_url'];
    $fields['twitter'] = $_POST['twitter'];

    $fields = cleanArray($fields);

    // aggiorna il profilo dell'utente con i dati inseriti nel form
    if (data_update('tab_utenti', $fields, array('id_utente' => $id_utente))) {
        // aggiorna i dati di sessione con quelli nuovi
        $user = user_session_update($id_utente);
        $smarty->assign('user', $user);
    } else {
        header('Location: '.$settings['sito']['url'].'errore');
    }
}
?>


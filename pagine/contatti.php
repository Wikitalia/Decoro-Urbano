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
 * Pagina contenento il form di contatto a disposizione degli utenti per inviare
 * messaggi al team di Decoro Urbano
 * @param int id_utente id dell'utente che sta inviando il form
 * @param string nome nome dell'utente
 * @param string cognome cognome dell'utente
 * @param string email email dell'utente
 * @param string argomento argomento del contatto scelto da una select
 * @param string testoEmail testo del contatto
 */

// post del form di contatto
if (isset($_POST['form_contatti'])) {
    // costruisce l'array dei campi
    
    $fields['id_utente'] = (isset($_POST['id_utente'])) ? ((int) $_POST['id_utente']) : '';
    $fields['nome'] = $_POST['nome'];
    $fields['cognome'] = $_POST['cognome'];
    $fields['email'] = $_POST['email'];
    $fields['argomento'] = $_POST['argomento'];
    $fields['testoEmail'] = $_POST['testoEmail'];
    
    // pulisce l'array 
    $fields = cleanArray($fields);

    
    $from = $fields['nome'] . ' ' . $fields['cognome'] . '<' . $fields['email'] . '>';
    $to = $settings['email']['indirizzo'];
    $subject = 'Contatto da Decoro Urbano - ' . $fields['argomento'];
    $message = 'ID Utente: ' . $fields['id_utente'] . '<br />Messaggio:<br />' . stripslashes(str_replace('\r\n', '<br />', $fields['testoEmail']));
    
    // invia un'email alla casella dell'amministratore con il contenuto del form
    if (html_email($from, $to, $subject, $message)) {
        $smarty->assign('email_inviata', 1);
    } else {
        header('Location: '.$settings['sito']['url'].'errore');
    }
}

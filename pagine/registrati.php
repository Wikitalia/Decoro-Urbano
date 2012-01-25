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
 * Pagina di registrazione di un nuovo utente. Presenta all'utente il form
 * di registrazione con i seguenti campi da compilare:
 * - nome: stringa di lunghezza minima pari a 1
 * - cognome: stringa di lunghezza minima pari a 1
 * - email
 * - conferma email
 * - password: stringa di lunghezza minima parti a 6
 * 
 * @param string regNome nome dell'utente
 * @param string regCognome cognome dell'utente
 * @param string regEmail email dell'utente
 * @param regPassword password
 */

// post del form di registrazione
if (isset($_POST['form_registrazione'])) {

    $errore_registrazione = 0;

    $fields['nome'] = $_POST['regNome'];
    $fields['cognome'] = $_POST['regCognome'];
    $fields['email'] = $_POST['regEmail'];
    $fields['password'] = sha1($_POST['regPassword']);
    $fields['id_ruolo'] = $settings['user']['ruolo_utente_normale'];
    $fields['data'] = time();

    $fields = cleanArray($fields);
    
    // controllo dei campi 
    if (trim($fields['nome']) == '') {
        $campi['regNome']['errore'] = 'Campo nome necessario';
        $errore_registrazione = 1;
    }

    if (trim($fields['cognome']) == '') {
        $campi['regCognome']['errore'] = 'Campo cognome necessario';
        $errore_registrazione = 1;
    }

    if (strlen($_POST['regPassword']) < 6) {
        $campi['regPassword']['errore'] = 'Password troppo corta';
        $errore_registrazione = 1;
    } else if (!checkPasswordField($_POST['regPassword'])){
        $campi['regPassword']['errore'] = 'Campo password non valido';
        $errore_registrazione = 1;
    }
    
    if (!checkEmailField($fields['email'])) {
        $campi['regEmail']['errore'] = 'Campo email non valido';
        $errore_registrazione = 1;
    } else if ($fields['email'] != $_POST['regConfermaEmail']) {
        $campi['regConfermaEmail']['errore'] = 'Le email non corrispondono';
        $errore_registrazione = 1;
    } else {
        if (!user_email_check($fields['email'])) {
            $campi['regEmail']['errore'] = 'Utente già registrato con questo indirizzo email';
            $campi['regConfermaEmail']['errore'] = 'Utente già registrato con questo indirizzo email';
            $errore_registrazione = 1;
        }
    }

    if (!$errore_registrazione) {
        // se la validazione ha avuto esito positivo inserisce il nuovo utente nel db
        // ed invia l'email di attivazione
        $id_utente = data_insert('tab_utenti', $fields);

        if ($id_utente) {
            // costruisce il codice di attivazione per l'utente
            $key = $settings['sito']['encrypt_key'];
            $salt = $settings['sito']['hashsalt'];
            
            $email_code = code_encrypt($salt . $id_utente, $key);

            // inizializza i parametri per l'invio dell'email
            $from = $settings['email']['nome'] . ' <' . $settings['email']['indirizzo'] . '>';
            $to = $fields['nome'] . ' ' . $fields['cognome'] . ' <' . $fields['email'] . '>';
            $link = $settings['sito']['confermaRegistrazione'] . "?s=" . $email_code;

            $data['from'] = $from;
            $data['to'] = $to;
            $data['template'] = 'registrazioneConferma';
            $variabili['nome_utente'] = trim($fields['nome'] . ' ' . $fields['cognome']);
            $variabili['link_registrazione'] = $link;
            $data['variabili'] = $variabili;
            
            // invio dell'email per la conferma della registrazione
            if (email_with_template($data)) {
                $smarty->assign('email_inviata', 1);
                $smarty->assign('nome_segnalatore', $fields['nome']);
            } else {
                header('Location: '.$settings['sito']['url'].'errore');
            }

        } else {
            header('Location: '.$settings['sito']['url'].'errore');
        }
    } else {
        foreach ($_POST as $key => $campo) {
            $campi[$key]['value'] = $campo;
        }
        //var_dump($campi);
        $smarty->assign('campi', $campi);
    }
}


?>

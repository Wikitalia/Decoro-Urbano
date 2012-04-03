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
 * Questa pagina gestisce il processo di reset della password di un utente.
 * Il processo comprende:
 * - step 1: l'utente indica il proprio indirizzo email, il sistema verifica la 
 * correttezza dell'indirizzo inserito ed invia un'email con il link per il secondo step
 * - step 2: l'utente clicca sul link presente nell'email ricevuta, il sistema 
 * controlla la validità del codice e mostra il form per l'inserimento di una nuova password
 * - step 3: il sistema memorizza la nuova password
 */
$errore_reset = 0;

// se l'utente è già loggato la procedura è inutile e viene ridirezionato alla home page 
if ($user) {
    header('Location: ' . $settings['sito']['url']);
} else {
    // step 1 della procedura di reset password: l'utente indica il proprio indirizzo email, 
    // il sistema verifica la correttezza dell'indirizzo inserito ed invia un'email con il link
    // per il secondo step
    if (isset($_POST['form_reset_password1'])) {
        // controllo validità campo email
        if (!checkEmailField(cleanField($_POST['resetEmail']))) {
            $campi['resetEmail']['errore'] = 'Campo Email necessario';
            $errore_reset = 1;
        } else {

            $email = cleanField($_POST['resetEmail']);

            // controlla che nel database esista un utente con l'indirizzo email inserito
            if (user_email_check($email)) {
                $campi['resetEmail']['errore'] = 'Indirizzo Email non presente';
                $errore_reset = 1;
            }
        }

        if (!$errore_reset) {
            // costruisce e critta il codice per il cambio password, generato a partire
            // da un salt e dall'id dell'utente corrispondente all'email inserita
            $key = $settings['sito']['encrypt_key'];
            $salt = $settings['sito']['hashsalt'];

            $user = data_get('tab_utenti', array('email' => cleanField($_POST['resetEmail'])));

            $email_code = code_encrypt($salt . $user[0]['id_utente'], $key);

            
            // inizializza le variabili per la costruzione dell'email da inviare all'utente
            $from = $settings['email']['nome'] . ' <' . $settings['email']['indirizzo'] . '>';
            $to = $user[0]['nome'] . ' ' . $user[0]['cognome'] . ' <' . $user[0]['email'] . '>';
            $link = $settings['sito']['passDimenticata'] . "?s=" . $email_code;

            $data['from'] = $from;
            $data['to'] = $to;
            $data['template'] = 'passwordRecupero';
            $variabili['link_reset'] = $link;
            $variabili['nome_utente'] = trim($user[0]['nome'] . ' ' . $user[0]['cognome']);
            $data['variabili'] = $variabili;
            
            // invia l'email all'utente
            if (email_with_template($data)) {
                $smarty->assign('email_inviata', 1);
            } else {
                $smarty->assign('errore_generico', 1);
            }
        } else { // in caso di errori nella validazione dell'email viene mostrato nuovamente il form
            foreach ($_POST as $key => $campo) {
                $campi[$key]['value'] = $campo;
            }
            $smarty->assign('campi', $campi);
        }
    } elseif (isset($_POST['form_reset_password2'])) {
        // step 3 procedura di reset password: il sistema memorizza la nuova password
        
        $key = $settings['sito']['encrypt_key'];
        $salt = $settings['sito']['hashsalt'];

        // recupera l'id utente contenuto nel codice di reset password inviato 
        // all'email indicata nello step 1
        $decrypted = code_decrypt($_POST['s'], $key);
        $id_utente = str_replace($salt, '', $decrypted);

        $fields['password'] = sha1($_POST['resetPass']);
        $fields['confermato'] = 1;
        $fields = cleanArray($fields);
        
        // verifica la correttezza della nuova password inserita
        if (!checkPasswordField($_POST['resetPass'])) {
            $campi['resetPass']['errore'] = 'La password non è valida';
            $errore_reset = 1;
        } else {
            if ($fields['password'] != sha1($_POST['resetPass2'])) {
                $campi['resetPass2']['errore'] = 'Le password non corrispondono';
                $errore_reset = 1;
            }
        }
        

        if (!$errore_reset) {
            // in caso di password valida, aggiorna il record dell'utente nel database
            // con la nuova password, altrimenti mostra un messaggio di errore
            $fields = cleanArray($fields);
            if (data_update('tab_utenti', $fields, array('id_utente' => $id_utente))) {
                $smarty->assign('reset_ok', 1);
            } else {
                $smarty->assign('errore_generico', 1);
            }
        } else {
            $smarty->assign('user_id', $id_utente);
            $smarty->assign('errore_reset', 1);
        }
    } else if (isset($_GET['s'])) {
        // step 2 della procedura di reset password: l'utente clicca sul link presente
        // nell'email ricevuta, il sistema controlla la validità del codice e mostra il form
        // per l'inserimento di una nuova password
        $key = $settings['sito']['encrypt_key'];
        $salt = $settings['sito']['hashsalt'];
        // recupera l'id utente contenuto nel codice di reset inviato via email
        $decrypted = code_decrypt($_GET['s'], $key);
        $id_utente = str_replace($salt, '', $decrypted);
        
        // controlla la validità dell'id utente contenuto nel codice
        if (is_numeric($id_utente)) {
            
            $user = data_get('tab_utenti', array('id_utente' => $id_utente));
            if ($user) {
                $smarty->assign('user_id', $user[0]['id_utente']);
                $smarty->assign('code', $_GET['s']);
                $smarty->assign('code_ok', 1);
            } else {
               $smarty->assign('errore_generico', 1); 
            }
        } else {
            $smarty->assign('errore_generico', 1);
        }
    }
}

?>
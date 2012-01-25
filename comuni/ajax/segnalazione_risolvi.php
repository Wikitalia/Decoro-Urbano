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

/*
 * Chiamata AJAX per marcare una segnalazione come "risolta" (300). La risoluzione
 * di una segnalazione può essere effettuata solo dall'utente associato
 * al comune dove è stata effettuata la segnalaione
 */
session_start();

require_once("../../include/config.php");
require_once("../../include/db_open.php");
require_once("../../include/db_open_funzioni.php");
require_once("../../include/funzioni.php");
require_once("../../include/controlli.php");
require_once('../../include/decorourbano.php');

// recupera l'utente loggato
$user = logged_user_get();

// controlla la presenza dell'utente e i privilegi del comune
if (!$user || $user['id_ruolo'] != $settings['user']['ruolo_utente_comune']) {
    echo "0";
    exit;
}

// verifico la presenza dei parametri
if (!$_GET['id'] || !checkNumericField($_GET['id'])) {
    echo '0';
    exit;
}

// id della segnalazoine
$id = (int) $_GET['id'];

if (data_update('tab_segnalazioni', array('stato' => $settings['segnalazioni']['risolta']), array('id_segnalazione' => $id))) {
    // se l'aggiornamento dello stato della segnalazione è andato a buon fine
    // invia, se necessario, l'email di notifica
    // recupera il dettaglio della segnalazione
    $segnalazione = segnalazione_dettaglio_get($id);
    // recupera il dettaglio dell'utente che ha effettuato la segnalazione
    $utente = user_get($segnalazione[0]['id_utente']);

    if ($utente['email_gestione_comune']) {
        // se l'utente ha richiesto l'invio delle notifiche
        
        // recupera la categoria della segnalazione
        $tipo = data_get('tab_tipi', array('id_tipo' => $segnalazione[0]['id_tipo']));
        // costruisce il link alla segnalazione
        $link_segnalazione = $settings['sito']['url'] . $tipo[0]['nome_url'] . '/' . $segnalazione[0]['citta_url'] . '/' . $segnalazione[0]['indirizzo_url'] . '/' . $id . '/';

        // imposta parametri e variabili per l'invio della notifica
        $data['from'] = $settings['email']['nome'] . ' <' . $settings['email']['indirizzo'] . '>';
        $data['to'] = $utente['nome'] . ' ' . $utente['cognome'] . ' <' . $utente['email'] . '>';
        $data['template'] = 'segnalazioneRisolta';

        $variabili['nome_utente'] = trim($utente['nome'] . ' ' . $utente['cognome']);
        $variabili['data'] = strftime('%e %B %Y', $segnalazione[0]['data']);
        $variabili['citta'] = $segnalazione[0]['citta'];
        $variabili['indirizzo'] = $segnalazione[0]['indirizzo'] . ' ' . $segnalazione[0]['civico'];
        $variabili['link_segnalazione'] = $link_segnalazione;
        $data['variabili'] = $variabili;
        // invia la notifica
        email_with_template($data);
    }
    echo "1";
} else {
    echo "0";
}

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
 * Questa pagina mostra il dettaglio di una segnalazione
 * @param int ids id della segnalazione di cui visualizzare il dettaglio
 */

// recupera l'id della segnalazione 
$id_segnalazione = (isset($_GET['ids'])) ? ((int) $_GET['ids']) : '';
$smarty->assign('ids', $id_segnalazione);

// recupera il dettaglio della segnalazione
$segnalazione = segnalazione_dettaglio_get($id_segnalazione);

if ($segnalazione) {
    // costruisce la stringa JSON del dettaglio della segnalazione 
    $segnalazione_json = json_encode($segnalazione);
    $segnalazione_json = escapeJSON($segnalazione_json);
 
    $segnalatore_dati = user_get($segnalazione[0]['id_utente']);
    
    // assegna le variabili smarty
    $smarty->assign('user_profile', $segnalatore_dati);
    $smarty->assign('segnalazione', $segnalazione[0]);
    
    // assegna variabili smarty in funzione dello stato della segnalazione, così 
    // da mostrare diversi template in caso di segnalazione in modarazione o rimossa
    if ($segnalazione[0]['stato'] >= $settings['segnalazioni']['in_attesa']) {
        $smarty->assign('segnalazioni', $segnalazione_json);
        $smarty->assign('metaDesc', $segnalazione[0]['messaggio']);
        $smarty->assign('segnalazione_valida', '1');
        $smarty->assign('pageTitle', $segnalazione[0]['tipo_nome'] . ' a ' . $segnalazione[0]['citta'] . ' in ' . $segnalazione[0]['indirizzo']);
    } else if ($segnalazione[0]['stato'] == 0) {
        $smarty->assign('segnalazione_in_moderazione', '1');
        $smarty->assign('pageTitle', 'Segnalazione in moderazione');
    } else if ($segnalazione[0]['stato'] >= 1) {
        $smarty->assign('segnalazione_rimossa', '1');
        $smarty->assign('pageTitle', 'Segnalazione rimossa');
    }
} else {
    // assegna una variabile smarty per mostrare il template di segnalazione non presente
    $smarty->assign('segnalazione_non_presente', '1');
    $smarty->assign('pageTitle', 'Segnalazione non presente');
}

?>
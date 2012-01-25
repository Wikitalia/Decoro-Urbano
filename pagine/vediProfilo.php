<?php

/*
 * ----------------------------------------------------------------------------
 * Decoro Urbano version 1.0
 * ----------------------------------------------------------------------------
 * Copyright Maiora Labs Srl (c) 2012
 * ----------------------------------------------------------------------------   
 * 
 * This file is part of Decoro Urbano.
 * 
 * Decoro Urbano is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
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
 * Questa pagina mostra il profilo pubblico di un utente, con la lista delle
 * segnalazioni effettuate 
 */

// recupera l'id dell'utente di cui visualizzare il profilo
$id_utente_profilo = (isset($_GET['idu']) && is_numeric($_GET['idu'])) ? $_GET['idu'] : 0;
// recupera dal database le informazioni associate all'id
$user_profile = user_get($id_utente_profilo);

if (!empty($user_profile)) {
    $smarty->assign('user_profile', $user_profile);
    
    // nel caso di un utente associato ad un comune attivo esegui redirect alla pagina personalizzata del comune
    if ($user_profile['id_ruolo'] == $settings['user']['ruolo_utente_comune']) {

        $comune = data_get('tab_comuni', array('id_comune' => $user_profile['id_comune']));
        header('Location: http://' . $comune[0]['nome_url'] . '.' . $settings['sito']['dominio'] . '/');
        exit;
    // in caso di un utente normale recupera le segnalazioni dell'utente
    } else {

        $parametri['id_user'] = $id_utente_profilo;
        $parametri['limit'] = $settings['segnalazioni']['limit_numero'];
        $parametri['commenti'] = 1;
        $parametri['formato'] = 1;
        
        // recupero le segnalazioni dell'utente in formato JSON
        $segnalazioni = segnalazioni_get($parametri);
        $segnalazioni = escapeJSON($segnalazioni);

        $smarty->assign('segnalazioni', $segnalazioni);

        $smarty->assign('pageTitle', $user_profile['nome'] . ' ' . $user_profile['cognome']);

        if ($user_profile['citta'] != '')
            $smarty->assign('metaDesc', 'Elenco delle segnalazione di ' . trim($user_profile['nome'] . ' ' . $user_profile['cognome']) . ' nella città di ' . $user_profile['citta'] . '.');
        else
            $smarty->assign('metaDesc', 'Elenco delle segnalazione di ' . trim($user_profile['nome'] . ' ' . $user_profile['cognome']) . '.');
    }
} else {
    // in caso di errori nell'estrazione del profilo utente redirezione all'homepage 
    header('Location: http://www.' . $settings['sito']['dominio'] . '/');
    exit;
}
?>
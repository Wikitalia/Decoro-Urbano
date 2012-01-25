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
 * Questa pagina presenta il form di modifica delle impostazioni di un utente.
 * Impostazioni relative alle notifiche via email:
 * - email_commento: in caso di nuovo commento ad una delle proprie segnalazioni
 * - email_condivisione: nel caso una delle proprie segnalazioni viene condivisa
 * - email_segnalazione: quando una delle proprie segnalazioni viene rimossa o approvata
 * - email_gestione_comune: quando una delle proprie segnalazioni viene presa in carico o risolta
 * - email_top: quando l'utente diventa uno dei top segnalatori
 * - email_comunicazioni: comunicazioni dallo staff di DecoroUrbano 
 * 
 * Impostazioni relative alla privacy:
 * - mostra_cognome: se mostrare o meno il proprio cognome nel sito
 * 
 * Impostazioni relative ai social network:
 * - fb_share: (solo per utenti Facebook) se condividere o meno in modo automatico le proprie segnalazioni
 * nello stream Facebook
 */

// post del form di modifica delle impostazioni di notifica dell'utente
if (isset($_POST['form_impostazioni_utente'])) {

    $id_utente = $user['id_utente'];
    $fields['email_commento'] = ($_POST['email_commento'] == 'on') ? 1 : 0;
    $fields['email_condivisione'] = ($_POST['email_condivisione'] == 'on') ? 1 : 0;
    $fields['email_segnalazione'] = ($_POST['email_segnalazione'] == 'on') ? 1 : 0;
    $fields['email_gestione_comune'] = ($_POST['email_gestione_comune'] == 'on') ? 1 : 0;
    $fields['email_top'] = ($_POST['email_top'] == 'on') ? 1 : 0;
    $fields['email_comunicazioni'] = ($_POST['email_comunicazioni'] == 'on') ? 1 : 0;

    $fields = cleanArray($fields);

    data_update('tab_utenti', $fields, array('id_utente' => $id_utente));

    $user = user_session_update($id_utente);
    $smarty->assign('user', $user);
}

// post del form di modifica delle impostazioni di privacy dell'utente
if (isset($_POST['form_impostazioni_utente2'])) {

    $id_utente = $user['id_utente'];
    $fields['mostra_cognome'] = ($_POST['mostraCognome'] == 'on') ? 1 : 0;
    $fields['profilo_pubblico'] = ($_POST['profiloPubblico'] == 'on') ? 1 : 0;

    $fields = cleanArray($fields);

    data_update('tab_utenti', $fields, array('id_utente' => $id_utente));

    $user = user_session_update($id_utente);
    $smarty->assign('user', $user);
}

// post del form di modifica delle impostazioni social dell'utente
if (isset($_POST['form_impostazioni_utente3'])) {

    $id_utente = $user['id_utente'];
    $fields['fb_share'] = ($_POST['fbShare'] == 'on') ? 1 : 0;

    $fields = cleanArray($fields);

    data_update('tab_utenti', $fields, array('id_utente' => $id_utente));

    $user = user_session_update($id_utente);
    $smarty->assign('user', $user);
}
	
?>	
	
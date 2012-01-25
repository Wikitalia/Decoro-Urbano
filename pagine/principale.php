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
 * Home page di un utente loggato. La pagina mostra:
 * - le ultime segnalazioni inserite
 * - il wall personale dell'utente
 * - la lista dei nuovi segnalatori
 * - la lista dei top segnalatori 
 *
 * Il wall dell'utente contiene:
 * - le segnalazioni dell'utente
 * - le segnalazioni commentate dall'utente
 * - le segnalazioni flaggate dall'utente con DoIt
 */

// recupera le ultime segnalazioni inserite
$parametri['limit'] = 3;

$ultime_segnalazioni = segnalazioni_get($parametri);
$json_ultime_segnalazioni = json_encode($ultime_segnalazioni);
$json_ultime_segnalazioni = escapeJSON($json_ultime_segnalazioni);

$smarty->assign('ultime_segnalazioni', $ultime_segnalazioni);
$smarty->assign('json_ultime_segnalazioni', $json_ultime_segnalazioni);


// recupera la lista delle segnalazioni da inserire nel wall dell'utente
$parametri['id_user'] = $user['id_utente'];
$parametri['wall'] = 1;
$parametri['limit'] = $settings['segnalazioni']['limit_numero'];
$parametri['commenti'] = 1;

$segnalazioni = segnalazioni_user_wall_get($parametri);

$json_segnalazioni = json_encode($segnalazioni);
$json_segnalazioni = escapeJSON($json_segnalazioni);

$smarty->assign('segnalazioni', $segnalazioni);
$smarty->assign('json_segnalazioni', $json_segnalazioni);

// recupera la lista dei nuovi segnalatori
$nuovi_utenti = segnalatori_new_get(12);
$smarty->assign('nuovi_utenti', $nuovi_utenti);

// recupera la lista dei top segnalatori
$segnalatori_top = segnalatori_top_get(3);
$smarty->assign('segnalatori_top', $segnalatori_top);


?>
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
 * Questa pagina contiene la mappa delle segnalazioni effettuate.
 * La segnalazioni visualizzate sulla mappa possono essere filtrate in base a:
 * - stato della segnalazione: in attesa / in carico / risolta
 * - recenti: mostra unicamente le segnalazioni degli ultimi 90 giorni (il parametro è configurato
 * in config.php come 'limit_giorni'
 * - personali: mostra unicamente le segnalazioni effettuate dall'utente loggato (disponibile
 * solo nel caso in cui l'utente è loggato
 * - filtro per categoria: mostra le segnalazioni filtrate in funzione delle cateogorie selezionate
 * Il filtro è realizzato in javascript.
 * 
 * La pagina accetta due parametri POST, 'lat' e 'lng' che rappresentano le coordinate
 * di un punto su cui centrare la mappa delle segnalazioni. 
 *
 * Questa stessa pagina rappresenta la home page dei sottodomini di decorourbano.org
 * relativi alle diverse entità geografiche (regioni o comuni). 
 * 
 * Ad es.: roma.decorourbano.org
 *
 * In caso di sottodominio, tramite mod_rewrite, il nome del sottodominio viene 
 * passato come parametro 'subdomain' a questa pagina, imponendo un filtro sulle
 * segnalazioni relative a regione o comune
 */

// recupera il parametro dottodominio che rappresenta la regione o il comune
// su cui filtrare le segnalazioni
$subdomain = cleanField($_GET['subdomain']);
$smarty->assign('subdomain', $subdomain);

$regioni = $settings['geo']['regioni'];

$smarty->assign('regioni', $regioni);

// nel caso in cui il sottodominio rappresenti una regione
if (array_key_exists($subdomain, $regioni)) {
    // inizializzo il tipo di pagina per la regione
    $smarty->assign('locType', 'regione');

    // recupero la lista delle segnalazioni relative alla regione
    $parametri['regione'] = $subdomain;
    $segnalazioni = segnalazioni_get($parametri);

    // genero la stringa JSON contenente la lista delle segnalazioni
    $segnalazioni = json_encode($segnalazioni);
    $segnalazioni = escapeJSON($segnalazioni);

    // recupera le statistiche relative ai comuni della regione, in termini di comuni attivi o meno
    $regioni[$subdomain]['dati'] = stats_comuni_regione_get($subdomain);

    // recupera le statistiche relative ai comuni con più segnalazioni effettuate
    unset($parametri);
    $parametri['regione'] = $subdomain;
    $parametri['limit'] = 5;
    $regioni[$subdomain]['top_comuni'] = stats_segnalazioni_get($parametri);

    // inizializza le variabili smarty
    $smarty->assign('segnalazioni', $segnalazioni);
    $smarty->assign('pageTitle', 'Regione ' . $regioni[$subdomain]['nome'] . ', elenco delle segnalazioni di degrado');
    $smarty->assign('siteNameTail', ' - ' . $regioni[$subdomain]['nome']);
    $smarty->assign('regione', $regioni[$subdomain]);
    $smarty->assign('metaDesc', 'Elenco delle segnalazioni di degrado urbano nella regione ' . $regioni[$subdomain]['nome'] . '.');

} else {
    // altrimenti se il sottodominio rappresenta il nome di un comune
    
    // verifica l'esistenza del comune
    $comune = data_get('tab_comuni', array('nome_url'=>$subdomain));

    if (count($comune)) {
        // recupera le statistiche delle segnalazioni effettuate nel comune in oggetto
        unset($parametri);
        $parametri['comune'] = $subdomain;
        $comune = stats_segnalazioni_get($parametri);
        
        // in caso di comune attivo costruisce il percorso del logo
        $file_logo = $settings['sito']['percorso'] . 'images/loghi_comuni/' . $comune[0]['nome_url'] . '_logo.png';
        
        // verifica l'esistenza del logo e ne costruisce l'url
        if (is_file($file_logo))
            $comune[0]['logo'] = $settings['sito']['url'].'images/loghi_comuni/' . $comune[0]['nome_url'] . '_logo.png';
        else
            $comune[0]['logo'] = '';

        // recupera la lista delle segnalazioni del comune
        unset($parametri);
        $parametri['id_comune'] = $comune[0]['id_comune'];
        $segnalazioni = segnalazioni_get($parametri);

        // costruisce la stringa JSON delle segnalazioni
        $segnalazioni = json_encode($segnalazioni);
        $segnalazioni = escapeJSON($segnalazioni);

        // assegna le variabili smarty
        $smarty->assign('segnalazioni', $segnalazioni);
        $smarty->assign('pageTitle', 'Comune di ' . $comune[0]['nome'] . ', elenco delle segnalazioni di degrado');
        $smarty->assign('siteNameTail', ' - ' . $comune[0]['nome']);
        $smarty->assign('locType', 'comune');
        $smarty->assign('comune', $comune[0]);
        $smarty->assign('regione', $regioni[$comune[0]['regione']]);
        $smarty->assign('metaDesc', 'Elenco delle segnalazioni di degrado urbano nel comune di ' . $comune[0]['nome'] . '.');
    } else {

        // nel caso in cui il comune non esista recupera l'intera lista di segnalazioni
        $segnalazioni = segnalazioni_get(array());

        // costruisce la stringa JSON della lista delle segnalazioni
        $segnalazioni = json_encode($segnalazioni);
        $segnalazioni = escapeJSON($segnalazioni);

        // recupera le statistiche relative ai comuni di tutta Italia
        $italia['dati'] = stats_comuni_regione_get('italia');

        // recupera le statistiche relative ai comuni con più segnalazioni effettuate in Italia
        unset($parametri);
        $parametri['regione'] = 'italia';
        $parametri['limit'] = 5;
        $italia['top_comuni'] = stats_segnalazioni_get($parametri);

        // assegna le variabili smarty
        $smarty->assign('italia', $italia);
        $smarty->assign('segnalazioni', $segnalazioni);
        $smarty->assign('locType', 'unknown');
    }
    
    // nel caso in cui vengano specificati i parametri lat e lng in POST, vengono
    // assegnati a variabili smarty per permettere il centramento della mappa sul
    // punto specificato
    if (isset($_POST['lat']) && $_POST['lat'] != '' && $_POST['type'] != 'locality') {
        $location['lat'] = (float) $_POST['lat'];
        $location['lng'] = (float) $_POST['lng'];
        $smarty->assign('location', $location);
    }
}

?>

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
 * Chiamata AJAX per l'inserimento di una nuova segnalazione
 * 
 * @param int id_tipo id della categoria della segnalazione
 * @param string indirizzo indirizzo della segnalazione
 * @param string descrizione descrizione della segnalazione
 * @param float lat latitudine del punto relativo alla segnalazione
 * @param float lng longitudine del punto relativo alla segnalazione
 * @param string civico numero civico dell'indirizzo ricavato tramite le API di Google Maps
 * @param string via indirizzo corrispondente alla posizione indicata dall'utente ricavato da Google Maps
 * @param string cap codice di avviamento postale ricavato tramite le API di Google Maps
 * @param string citta nome del comune ricavato tramite le API di Google Maps
 * @param string provincia nome della provincia ricavato tramite le API di Google Maps
 * @param string regione nome della regione ricavato tramite le API di Google Maps
 * @param string nazione nome del paese ricavato tramite le API di Google Maps
 * @param string codice_nazione codice identificativo della nazione ricavato tramite le API di Google Maps
 * @param string client stringa identificativa della modalità di invio della segnalazione, in questo caso sempre "Segnalazione WEB"
 * @param string versione stringa identificativa del numero di versione del client utilizzato
 */

session_start();

require_once("../include/config.php");
require_once("../include/db_open.php");
require_once("../include/db_open_funzioni.php");
require_once("../include/funzioni.php");
require_once("../include/controlli.php");
require_once('../include/decorourbano.php');

$facebook = null;

// verifica la presenza dei parametri
if (!$_POST['id_tipo'] || !checkNumericField($_POST['id_tipo']) ||
    !$_POST['indirizzo'] ||
    !$_POST['descrizione'] ||
    !$_POST['lat'] || !is_numeric($_POST['lat']) ||
    !$_POST['lng'] || !is_numeric($_POST['lng'])) {
    $out['status'] = 'params_error';
    exit;
}


// recupera i dati dell'utente loggato
$user = logged_user_get();

// se non c'è alcun utente loggato, esce
if (!$user) {
    $out['status'] = 'session_error';
    echo json_encode($out);
    exit;
}

$id_utente = (int) $user['id_utente'];

// parametri inseriti dall'utente
$id_tipo = (int) $_POST['id_tipo'];
$indirizzo = $_POST['indirizzo'];
$descrizione = strip_tags($_POST['descrizione']);
$lat = (float) $_POST['lat'];
$lng = (float) $_POST['lng'];

// parametri calcolati dalla posizione indicata nella mappa
$civico = $_POST['civico'];
$via = $_POST['via'];
$cap = $_POST['cap'];
$citta = $_POST['citta'];
$provincia = $_POST['provincia'];
$regione = $_POST['regione'];
$nazione = $_POST['nazione'];
$codice_nazione = $_POST['codice_nazione'];

// parametri relativi alla modalità di invio
$client = $_POST['client'];
$versione = $_POST['versione'];
$sid = session_id();


$comune = comune_get($citta);
$id_comune = $comune['id_comune'];

// compila i campi della segnalazione
$fields = array(
    'id_utente' => $id_utente,
    'id_tipo' => $id_tipo,
    'confermata' => $settings['segnalazioni']['conferma_automatica'],
    'data' => time(),
    'stringa_indirizzo' => $indirizzo,
    'messaggio' => $descrizione,
    'lat' => $lat,
    'lng' => $lng,
    'civico' => $civico,
    'indirizzo' => $via,
    'indirizzo_url' => fixForUri($via),
    'cap' => $cap,
    'citta' => $citta,
    'citta_url' => fixForUri($citta),
    'provincia' => $provincia,
    'regione' => $regione,
    'regione_url' => fixForUri($regione),
    'nazione' => $nazione,
    'codice_nazione' => $codice_nazione,
    'id_comune' => $id_comune,
    'client' => $client,
    'versione' => $versione,
    'ip' => $_SERVER['REMOTE_ADDR']
);

// se il comune è attivo, la segnalazione viene inserita in moderazione
// se ad inserire la segnalazione è l'utente associato al comune, la segnalazione
// viene inserita in uno stato apposito
if ($comune['stato'] == 1) {
    if ($user['id_ruolo'] == 3 && $comune['id_comune'] == $user['id_comune'])
        $fields['stato'] = $settings['segnalazioni']['in_attesa_comune'];
    else
        $fields['stato'] = $settings['segnalazioni']['in_moderazione'];
}

$fields = cleanArray($fields);

if (!($id_segnalazione = data_insert('tab_segnalazioni', $fields)))
    exit('ERRORE');


// Se l'inserimento è andato a buon fine, sposta le foto dalla cartella temporanea
// a quella definitiva
$destDir = $settings['sito']['percorso'] . '/images/segnalazioni/' . $id_utente . '/' . $id_segnalazione;
$tempDir = $settings['sito']['percorso'] . "/ajax/uploads/" . $sid;

// se la cartella di destinazione non esiste, viene creata
if (!(file_exists($destDir))) {
    mkdir($destDir, 0755, true);
}

// rinomina i nomi dei file delle immaggini
if ($handle = opendir($tempDir)) {
    while (false !== ($file = readdir($handle))) {
        if (!is_dir($file)) {
            //rename ($tempDir."/".$file, $destDir."/".$file);
            rename($tempDir . "/" . $file, $destDir . "/1.jpeg");
        }
    }
    closedir($handle);
    // rimuove la cartella temporanea
    rrmdir($tempDir);
}

// recupera il nome della categoria in cui è stata inserita la segnalazione
$tipo = data_get('tab_tipi', array('id_tipo' => $id_tipo), '', array('nome' => ''));

// costruisce l'url della segnalazione con il formato:
// <base_url>/<categoria>/<comune>/<indirizzo>/<id_segnalazione>
// ad es.: http://www.decorourbano.org/sos-buche/rome/via-capo-spartivento/535/
$link_segnalazione = $settings['sito']['url'] . fixForUri($tipo[0]['nome']) . '/' . fixForUri($citta) . '/' . fixForUri($via) . '/' . $id_segnalazione . '/';

// costruisce l'url dell'immagine ridimensionata dell'immagine della segnalazione.
// l'url richiama lo script resize.php che ridimensiona l'immagine, con un meccanismo di cache
// finalizzato a conservare le immagini già ridimensionate
$variabili['foto_base_url'] = '/images/segnalazioni/'.fixForUri($tipo[0]['nome']).'-'.$fields['citta_url'].'-'.$fields['indirizzo_url'].'-'.$id_utente.'-'.$id_segnalazione.'-';
$imgSegnalazione = $settings['sito']['url_ns'].$variabili['foto_base_url'].'280-215.jpg';

// costruisce l'url per visualizzare la segnalazione su google maps in base alla latitudine
// e longitudine della segnalazione
// ad es.:
// http://maps.google.com/maps/api/staticmap?size=480x480&markers=icon:http://www.decorourbano.org/images/marker_DU.png|700+E+9th+St+NY&sensor=false
$imgMappa = 'http://maps.google.com/maps/api/staticmap?size=280x215&markers=icon:'.$settings['sito']['url'].'images/marker_DU.png|' . $lat . ',' . $lng . '&sensor=false&zoom=14';


// inizializza le variabili per l'invio dell'email di notifica all'utente che ha inviato
// la segnalazione
$data['from'] = $settings['email']['nome'] . ' <' . $settings['email']['indirizzo'] . '>';
$data['to'] = $user['nome'] . ' ' . $user['cognome'] . ' <' . $user['email'] . '>';
$data['template'] = 'segnalazionePubblicazione';

$variabili['nome_utente'] = trim($user['nome'] . ' ' . $user['cognome']);
$variabili['link_segnalazione'] = $link_segnalazione;
$variabili['imgSegnalazione'] = $imgSegnalazione;
$variabili['imgMappa'] = $imgMappa;
$variabili['via'] = $via;
$variabili['citta'] = $citta;
$variabili['messaggio'] = $descrizione;
$variabili['categoria'] = $tipo[0]['nome'];
$variabili['data'] = strftime('%e %B %Y %R');

$data['variabili'] = $variabili;

// se il comune non è attivo, la segnalazione viene immediatamente pubblicata sul sito
// di Decoro Urbano, senza moderazione preventiva. In questo caso l'email di notifica
// all'utente viene inviata subito.
if ($comune[0]['stato'] == 0) {
    email_with_template($data);
}

// nel caso in cui l'utente sia loggato tramite Facebook, e abbia consentito alla 
// pubblicazione delle proprie segnalazioni sul proprio profilo, viene effettua la pubblicazione
// su Facebook
if ($user['id_fb'] && $user['fb_share'] && isset($_SESSION['fb_session']) && $_SESSION['fb_session']) {
    $variabili['id_utente'] = $id_utente;
    $variabili['id_segnalazione'] = $id_segnalazione;
    segnalazione_fb_share($variabili);
}

// viene inviata la risposta in formato JSON
$out['status'] = 'ok';
$out['id_segnalazione'] = $id_segnalazione;
$out['link_segnalazione'] = $link_segnalazione;

echo json_encode($out);


?>

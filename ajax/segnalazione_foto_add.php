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
 * Chiamata AJAX per effettuare l'upload della foto di una segnalazione
 * 
 */
session_start();

require_once("../include/funzioni.php");
require_once("../include/SimpleImage.php");
require_once("../include/config.php");
require_once('../include/db_open.php');
require_once('../include/db_open_funzioni.php');
require_once("../include/file_upload.php");
require_once('../include/decorourbano.php');


// recupera i dati dell'utente loggato
$user = logged_user_get();

// se non c'è un utente loggato, esce
if (!$user) {
    exit;
}

// se non è stato uploadato alcun file
if (empty($_GET['qqfile'])) {
	echo "0";
	exit;
}

// lista delle estensioni dei file accettate 
$allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
// dimensione massima dei file
$sizeLimit = 5 * 1024 * 1024;
// recupera il session ID che verrà utilizzato per creare la directory in cui uploadare i file
$sid = session_id();

// inizializza la classe per la gestione dell'upload dei file
$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
// definisce la cartella di upload dei file degli avatar degli utenti
$uploadDir = 'uploads/' . $sid . '/';

// cancella eventuali cartelle preesistenti con lo stesso nome, basato sul session ID
rrmdir($uploadDir);

// ricrea la cartella
if (!(file_exists($uploadDir))) {
    mkdir($uploadDir, 0755, true);
}

// verifica e copia gli upload nella cartella specificata
$result = $uploader->handleUpload($uploadDir);

// se l'upload è andato a buon fine
if ($result['success'] && $handle = opendir($uploadDir)) {
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
            // ad ogni immagine presente nella cartella degli upload applica il marchio di DecoroUrbano
            $image_filigrana = new SimpleImage();
            $image_filigrana->load($settings['sito']['percorso'] . "images/DU_filigrana.png");
            
            $image = new SimpleImage();
            $image->load($uploadDir . '/' . $file);
            $image->resizeToWidth(640);
            $image->watermark(0, 0, $image_filigrana);
            
            // salva il file modificato e cancella quello originale
            $image->save($uploadDir . '/resized_' . $file);
            unlink($uploadDir . '/' . $file);
        }
    }
    closedir($handle);
}


// genera la risposta JSON
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);


?>
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
 * Chiama AJAX per l'upload del file dell'avatar di un utente
 */

session_start();

require_once("../include/config.php");
require_once("../include/db_open.php");
require_once("../include/db_open_funzioni.php");
require_once("../include/funzioni.php");
require_once("../include/controlli.php");
require_once("../include/decorourbano.php");
require_once("../include/SimpleImage.php");
require_once("../include/file_upload.php");

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

$uid = (int) $user['id_utente'];

// lista delle estensioni dei file accettate 
$allowedExtensions = array('jpg','jpeg','png','gif');
// dimensione massima dei file
$sizeLimit = 5 * 1024 * 1024;

// inizializza la classe per la gestione dell'upload dei file
$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
// definisce la cartella di upload dei file degli avatar degli utenti
$uploadDir = '../images/avatar/' . $uid . '/';

// se la cartella non esiste viene creata
if (!(file_exists($uploadDir)))
    mkdir($uploadDir, 0755, true);

// verifica e copia gli upload nella cartella specificata
$result = $uploader->handleUpload($uploadDir);

// se l'upload è andato a buon fine
if ($result['success'] && $handle = opendir($uploadDir)) {
    // effettua il resize dell'immagine caricata dall'utente e cambia il nome
    $image = new SimpleImage();
    $image->load($uploadDir . '/'.$result['filename']);
    $image->resizeToWidth(128);
    $image->save($uploadDir . '/1.jpeg');
    // cancella il vecchio file
    unlink($uploadDir . '/'.$result['filename']);
    closedir($handle);
}


// genera la risposta JSON
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);

?>

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
 * Questo file contiene una serie di funzioni per la validazione dei campi di input 
 */

require_once('funzioni.php');

/**
 * Effettua il 'clean' di un array di campi
 * 
 * @param array $arr
 * @return array restituisce un array corrispondente all'array passato come parametro ma con i valori pronti per essere inseriti nel database 
 */
function cleanArray($arr) {
    $result = array();
    foreach ($arr as $key => $field) {
        if (is_array($field))
            $result[$key] = cleanArray($field);
        else
            $result[$key] = cleanField($field);
    }
    return $result;
}

/**
 * Pulisce un campo prima dell'inserimento nel database
 * 
 * Questa funzione rende la stringa passata come parametro adatta ad essere 
 * inserita in sicurezza nel database, per evitare rischi derivanti da SQL Injection
 * e XSS. In particolare: 
 * - elimina eventuali tags inseriti nella stringa
 * - effettua l'escape di caratteri speciali per un inserimento sicuro nel database 
 * 
 * @global resource $mysqli
 * @param string $string
 * @return string restituisce la stringa 'pulita' e pronta per essere inserita nel database 
 */
function cleanField($string) {
    global $mysqli;

    $string = strip_tags($string);
    
    // elimina eventuali escape inseriti in automatico da PHP (funzionalità deprecata)
    if (get_magic_quotes_gpc()) {
        $string = stripslashes($string);
    }
    
    // effettua l'escape dei caratteri speciali utilizzando la funzionalità nativa
    // di mysql
    if (phpversion() >= '4.3.0') {
        $string = $mysqli->real_escape_string($string);
    } else {
        $string = $mysqli->escape_string($string);
    }
    return $string;
}

/**
 * Controlla la validità di un'email
 * 
 * Effettua la validazione del formato di un'email passata come parametro
 * 
 * @param string $email
 * @return boolean 
 */
function checkEmailField($email) {
    return (preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/', $email) > 0) ? true : false;
}

/**
 * Controlla la validità di una password
 * 
 * Effettua la validazione del formato di una password passata come parametro
 * La password deve essere alfanumerica e contenere tra 6 e 30 caratteri
 * 
 * @param string $str
 * @return boolean 
 */
function checkPasswordField($str) {
    return (preg_match("/^[\w\d]{6,30}$/", $str) > 0) ? true : false;
}

/**
 * Controlla che la stringa passata come parametro sia formata da sole cifre
 * 
 * @param string $str
 * @return boolean 
 */
function checkNumericField($str) {
    return (preg_match("/^\d+$/", $str) > 0) ? true : false;
}

/**
 * Controlla che la stringa passata come parametro sia composta da lettere, cifre e spazi
 * 
 * @param string $str
 * @return boolean 
 */
function checkTextNumericField($str) {
    return (preg_match("/^[\w\d\s]+$/", accents($str, true)) > 0) ? true : false;
}

/**
 * Controlla che la stringa passata come parametro sia composta da lettere e spazi
 * 
 * @param string $str
 * @return boolean 
 */
function checkTextField($str) {
    return (preg_match("/^[\w\s]+$/", accents($str, true)) > 0) ? true : false;
}

function stripSlashesExcept($string, $except) {
    $except = stripslashes($except);
    $except_entity = htmlentities($except);
    $string = stripslashes($string);
    $string = str_replace($except, $except_entity, $string);
    return $string;
}









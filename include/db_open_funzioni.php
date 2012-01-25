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
 * Questo file contiene funzioni generiche di interazioni con il database per 
 * l'esecuzioni di query e il trattamento dei risultati
 */
require_once('config.php');
require_once('db_open.php');

/**
 * Effettua un update nel database
 * 
 * Questa funzione effettua un update della tabella 'table' del database
 * sui campi specificati dall'array 'fields', sui record che soddisfano le condizioni
 * specificate nell'array 'conditions'
 * 
 * Esempio:
 * $fields = array ( 'name' => 'Riunione strategica', 'location' => 'Roma');
 * $conditions = array ( 'id_evento' => 50);
 * 
 * @global type $mysqli
 * @param string $table nome della tabella su cui effettuare l'update
 * @param array $fields array dei campi da aggiornare con i relativi nuovi valori
 * @param array $conditions array associativo delle condizioni per individuare i record sui cui effettuare l'update
 * @return boolean 
 */
function data_update($table, $fields, $conditions=array()) {
    global $mysqli;

    // costruisce la stringa di update dei campi
    $key_values = "";

    foreach ($fields as $key => $field) {
        $key_values.=$key . " = '" . $field . "', ";
    }

    $key_values = substr($key_values, 0, -2);

    // aggiunge la clausola WHERE in funzione delle condizioni specificate
    if (count($conditions)) {
        $where = " WHERE ";
        foreach ($conditions as $key => $field) {
            $where.=$key . " = '" . $field . "' AND ";
        }
        $where = substr($where, 0, -5);
    } else {
        $where = "";
    }

    $q = "UPDATE " . $table . " SET " . $key_values . $where;


    // Per verificare che esista effettivamente il record con quell'id contare invece mysqli->affected_rows.
    if ($mysqli->query($q)) {
        return true;
    } else {
        printf("Can't execute MySQL query. Errorcode: %s\n", $mysqli->error);
        return false;
    }
}


/**
 * Esegue una query di selezione sul database
 * 
 * Questa funzione effettua una select sulla tabella 'table' del database
 * dei record che soddisfano le condizioni specificate nell'array 'conditions'.
 * Eventuali clausole di ordinamento o raggruppamento possono essere aggiunte nel
 * parametro 'tail'
 * 
 * Esempio:
 * $conditions = array ( 'username' => $username, 'password' => $password );
 * 
 * @global type $mysqli
 * @param string $table nome della tabella su cui effettuare la select
 * @param array $conditions array associativo delle condizioni per individuare i record da restituire
 * @param string $tail
 * @return mixed restituisce l'array con i record selezionati oppure false in caso di errore 
 */
function data_get($table, $conditions = array(), $tail = "") {

    global $mysqli;

    if (count($conditions)) {
        $key_values = "";

        foreach ($conditions as $key => $condition) {
            $key_values.=$key . " = '" . $condition . "' AND ";
        }

        $key_values = substr($key_values, 0, -5);


        $q = "SELECT * FROM " . $table . " WHERE " . $key_values ;
    } else {
        $q = "SELECT * FROM " . $table ;
    }

    $q .= $tail;

    $result = $mysqli->query($q);
    if ($result === false) {
        printf("Can't execute MySQL query. Errorcode: %s\n", $mysqli->error);
        return false;
    } else {
        $result_array = result_to_array($result);
        $result->close();
        return $result_array;
    } 
}

/**
 * Esegue una query di inserimento nel database
 * 
 * Questa funzione esegue una query di inserimento nella tabella 'table' del database
 * di un record con i valori specificati nell'array 'fields'
 * 
 * Esempio:
 * $table = "tab_eventi";
 * $fields = array ( 'name' => 'Riunione strategica', 'location' => 'Roma');
 * 
 * @global resource $mysqli
 * @param string $table nome della tabella su cui eseguire l'inserimento
 * @param array $fields valori dei campi del record da inserire
 * @return mixed restiuisce l'id con cui è stato inserito il campo oppure false in caso di errore 
 */
function data_insert($table, $fields) {
    global $mysqli;

    $keys = "";
    $values = "";

    foreach ($fields as $key => $field) {
        $keys.=$key . ", ";
        $values.="'" . $field . "', ";
    }

    $keys = substr($keys, 0, -2);
    $values = substr($values, 0, -2);

    $q = "INSERT INTO " . $table . " (" . $keys . ") VALUES (" . $values . ")";

    if ($mysqli->query($q)) {
        return $mysqli->insert_id;
    } else {
        printf("Can't execute MySQL query. Errorcode: %s\n", $mysqli->error);
        return false;
    }
}

/**
 * Esegue una query di cancellazione sul database
 * 
 * Questa funzione esegue una query di cancellazione sulla tabella 'table' nel database
 * per cancellare il/i record che soddisfano le condizioni specificate nell'array 'conditions'.
 * Per sicurezza è necessario specificare almeno una condizione.
 * 
 * Esempio:
 * $conditions = array ( 'id_evento' => 50);
 * 
 * @global resource $mysqli
 * @param string $table nome della tabella su cui effettuare la query
 * @param array $conditions array associativo delle condizioni per individuare i record da cancellare
 * @return boolean 
 */
function data_delete($table, $conditions) {
    global $mysqli;
    
    
    $q = "DELETE FROM " . $table . " WHERE ";
    
    if (count($conditions)) {
        $key_values = "";

        foreach ($conditions as $key => $condition) {
            $key_values.=$key . " = '" . $condition . "' AND ";
        }

        $key_values = substr($key_values, 0, -5);


        $q .= $key_values ;
    } else {
        return false;
    }

    if ($mysqli->query($q))
        return true;
    else {
        printf("Can't execute MySQL query. Errorcode: %s\n", $mysqli->error);
        return false;
    }
}

/**
 * Esegue una query generica
 * 
 * Questa funzione esegue una query generica sul database restituendo il risultato
 * in un array
 * 
 * @global resource $mysqli
 * @param string $query la query da eseguire
 * @return array un array multidimensionale contenente il risultato della query 
 */
function data_query($query) {
    global $mysqli;

    $result = $mysqli->query($query);

    if ($result === true)
        return true;
    else if ($result)
        return result_to_array($result);
    else
        return false;
}


/**
 * Costruisce un array multidimensionale a partire dal risultato di una query
 * 
 * Restituisce un array associativo e multidimensionale a partire dal risultato 
 * di un query. Ogni elemento dell'array contiene una riga del risultato
 * 
 * @param resource $result
 * @return array 
 */
function result_to_array($result) {
    $result_array = array();
    
    while ($row = $result->fetch_assoc()) {
        $result_array[] = $row;
    }

    return $result_array;
}

function result_to_array_key($result, $key_field) {

    while ($row = $result->fetch_assoc()) {
        $result_array[$row[$key_field]] = $row;
    }

    return $result_array;
}





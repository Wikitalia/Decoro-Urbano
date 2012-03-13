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
 * Questo file contiene una serie di funzioni per interagire con le informazioni di 
 * DecoroUrbano nel DB 
 */


/**
 * Restituisce i dati di un comune
 * 
 * Questa funzione restituisce i dati di un comune individuato in base al suo nome
 * 
 * @param string $nome nome del comune
 * @return mixed array contenente i dati del comune se questo viene trovato nella tabella, false altrimenti 
 */
function comune_get($nome) {
    $comune = data_get('tab_comuni', array('nome_url' => fixForUri($nome)));
    
    if ($comune) {
        return $comune[0];
    } else {
        return false;
    }
    
}

/**
 * Restituisce la lista dei comuni il cui nome corrisponde ad un parametro di ricerca
 * 
 * Questa funzione viene utilizzata per la ricerca dei comuni in un campo in autocompletamento
 * @param string $term stringa di ricerca
 * @return array lista dei comuni che corrispondono al parametro di ricerca
 */
function comuni_get($term) {

	$q='SELECT id_comune,nome,nome_url 
			FROM tab_comuni 
			WHERE nome LIKE "'.$term.'%" 
			LIMIT 10';
	$comuni = data_query($q);
	
	return $comuni;

}

/**
 * Restituisce gli enti relativi al comune il cui id corrisponde al parametro inserito
 * 
 * Questa funzione restituisce gli enti inseriti dai comuni con i relativi parametri (nome, email, inoltro attivato o meno)
 * @param int $id_comune id del comune di cui restituire gli enti
 * @return array lista degli enti che corrispondono all'id_comune inserito
 */
function enti_get($id_comune) {

	$q='SELECT tt.nome as nome_tipo, te.nome, te.email, tt.id_tipo, te.inoltro_attivo
			FROM tab_tipi tt
			LEFT JOIN tab_enti te
			ON tt.id_tipo = te.id_tipo and id_comune = '.$id_comune;
	$enti_comune = data_query($q);
	
	return $enti_comune;

}

/**
 * Restituisce gli utenti più attivi
 * 
 * Questa funzione restituisce un array dei 'limit' utenti più attivi del giorno, 
 * in funzione del numero di segnalazioni effettuate alla mezzanotte del giorno precedente, 
 * con associato il numero di segnalazioni
 * 
 * @param int $limit numero di utenti da restituire
 * @return array lista di utenti con relativo numero di segnalazioni
 */
function segnalatori_top_get($limit = 10) {
    $midnight = strtotime('midnight');
    
    // la query include solo le segnalazioni valide, non cancellate di utenti attivi
    $q = "SELECT u.*, COUNT(*) as n_segnalazioni
            FROM tab_segnalazioni as s, tab_utenti as u
            WHERE s.id_utente = u.id_utente AND
                    s.stato >= 100 AND
                    s.eliminata = 0 AND
                    s.archiviata = 0 AND
                    u.confermato = 1 AND
                    u.eliminato = 0 AND
                    s.data < $midnight
            GROUP BY u.id_utente
            ORDER BY n_segnalazioni DESC
            LIMIT $limit";

    $segnalatori_top = data_query($q);

    if ($segnalatori_top) {
        foreach ($segnalatori_top as $key => $segnalatore) {
            // esclude il cognome di quegli utenti che hanno impostato come preferenza 
            // di non mostrare il proprio cognome
            if (!$segnalatore['mostra_cognome'])
                $segnalatori_top[$key]['cognome'] = '';

            // include l'url all'avatar dell'utente
            $segnalatori_top[$key]['avatar'] = user_avatar_get($segnalatore);
        }

        return $segnalatori_top;
    } else {
        return array();
    }
}

/**
 * Restituisce gli utenti registrati
 * 
 * Questa funzione restituisce un array degli ultimi 'limit' utenti registrati
 * ed attivi
 * @global array $settings
 * @param int $limit numero di utenti da restituire
 * @return array lista degli utenti
 */
function segnalatori_new_get($limit) {
    global $settings;
    
    $limit = (is_numeric($limit) && $limit>0)?((int)$limit):(10);
    
    $q = "SELECT * 
            FROM tab_utenti
            WHERE confermato = 1 AND 
            eliminato = 0
            ORDER BY data DESC
            LIMIT $limit";

    $nuovi_utenti = data_query($q);

    if ($nuovi_utenti) {
        foreach ($nuovi_utenti as $key => $utente) {
            // esclude il cognome di quegli utenti che hanno impostato come preferenza 
            // di non mostrare il proprio cognome
            if (!$utente['mostra_cognome'])
                $nuovi_utenti[$key]['cognome'] = '';

            // include l'url all'avatar dell'utente
            $nuovi_utenti[$key]['avatar'] = user_avatar_get($utente);
        }


        return $nuovi_utenti;
    } else {
        return array();
    }
}

/**
 * Controlle l'esistenza di un'email nella tabella degli utenti
 * 
 * Questa funzione effettua un check sull'esistenza di un indirizzo email nella
 * tabella degli utenti per verificare che non sia già stato utilizzato.
 * 
 * @param string $email indirizzo email da verificare
 * @return boolean true se l'indirizzo non è presente nel db, false altrimenti
 */
function user_email_check($email) {
    $q="SELECT email
            FROM tab_utenti 
            WHERE email = '$email'";

    $row = data_query($q);

    if (count($row)) {
        return false;
    } else {
        return true;
    }
}

/**
 * Restituisce l'url dell'avatar dell'utente
 * 
 * Restituisce l'url dell'avatar dell'utente passato come parametro, tenendo conto
 * del tipo di utente, ovvero utente locale o utente Facebook
 * @global array $settings
 * @param array $user
 * @return string url dell'avatar 
 */
function user_avatar_get($user) {
    global $settings;

    $avatar_local = 'images/avatar/' . $user['id_utente'] . '/1.jpeg';
    
    // nel caso in cui l'utente abbia specificato un proprio avatar su DU
    // viene data la precedenza all'avatar locale, altrimenti, nel caso in cui l'utente
    // sia un utente Facebook, viene restituito il link all'avatar di Facebook
    // altrimenti viene restituio l'avatar di default
    if (is_file($settings['sito']['percorso'] . $avatar_local)) {
        $avatar_link = '/' . $avatar_local;
    } else if ($user['id_fb']) {
        $avatar_link = 'https://graph.facebook.com/' . $user['id_fb'] . '/picture?type=large';
    } else {
        $avatar_link = '/images/avatarGuest.png';
    }
    
    return $avatar_link;
}

function user_segnalazioni_count($id_utente) {
    global $settings;
    
    $q = "SELECT COUNT(*) as 
            FROM tab_segnalazioni 
            WHERE stato >= ".$settings['segnalazioni']['in_attesa']. " AND
                    eliminata = 0 AND 
                    archiviata = 0 AND 
                    id_utente = ".$id_utente;
    
    return data_query($q);
}

/**
 * Restituisce la lista delle segnalazioni
 * 
 * Restituisce un array di segnalazioni filtrate sulla base dei parametri passati
 * in ingresso. In particolare:
 * - limit      int     limita il numero di segnalazioni restituite
 * - id_user    int     filtra le segnalazioni inviate dall'utente con id = id_user
 * - commenti   1|0     include gli eventuali commenti della segnalazione
 * - regione    string  nome della regione su cui filtrare le segnalazioni
 * - id_comune  int     ID del comune su cui filtrare le segnalazioni
 * - formato    int     indica il formato in cui restituire la lista delle segnalazioni, se 1 il formato è una stringa JSON
 * - tipi       array   attivazione dei filtri sulle categorie
 * - recenti    int     filtra le segnalazioni restituendo solo quelle effettuate nel periodo in giorni indicato da questo parametro
 * - stato      int     filtra le segnalazioni nello stato indicato
 * - nuove      int     filtra le segnalazioni più nuove del timestamp passato come parametro
 * - vecchie    int     filtra le segnalazioni più vecchie del timestamp passato come parametro
 * - distanza   float   aggiunge il numero di segnalazioni in zona
 * - area       array   vertici di un area su cui filtrare le segnalazioni
 * - includi_non_approvate 1|0  include anche le segnalazioni non visibili (perchè non approvate) 
 * - conteggio_inappropriate 1|0 aggiunge per ogni segnalazione il numero delle volte che è stata flaggata come inappropriata 
 * @global array $settings
 * @global array $user array con le informazioni dell'utente loggato
 * @param array $parametri lista dei parametri per filtrare le segnalazioni
 * @return array|string lista delle segnalazioni corrispondenti ai filtri oppure
 *                      una stringa JSON
 */
function segnalazioni_get($parametri) {
    global $settings;
    
    
    $limit = (isset($parametri['limit']) && is_numeric($parametri['limit']))?((int)$parametri['limit']):(0);
    $commenti = ($parametri['commenti']==1)?(1):(0);
    $id_user = (isset($parametri['id_user']) && is_numeric($parametri['id_user']))?((int)$parametri['id_user']):(0);
    $formato = (isset($parametri['formato']) && is_numeric($parametri['formato']))?((int)$parametri['formato']):(0);   
    $regione = $parametri['regione'];
    $id_comune = (isset($parametri['id_comune']) && is_numeric($parametri['id_comune']))?((int)$parametri['id_comune']):(0);
    $id_competenza = (isset($parametri['id_competenza']) && is_numeric($parametri['id_competenza']))?(int) $parametri['id_competenza']:-1;	
    $recenti = (isset($parametri['recenti']) && is_numeric($parametri['recenti']))?((int)$parametri['recenti']):(0);
    $stato = (isset($parametri['stato']) && is_numeric($parametri['stato']))?((int)$parametri['stato']):(0);
    $nuove = (isset($parametri['nuove']) && is_numeric($parametri['nuove']))?((int)$parametri['nuove']):(0);
    $vecchie = (isset($parametri['vecchie']) && is_numeric($parametri['vecchie']))?((int)$parametri['vecchie']):(0);
    $includi_non_approvate = (isset($parametri['includi_non_approvate']) && is_numeric($parametri['includi_non_approvate']))?((int)$parametri['includi_non_approvate']):(0);
    $conteggio_inappropriate = (isset($parametri['conteggio_inappropriate']) && is_numeric($parametri['conteggio_inappropriate']))?((int)$parametri['conteggio_inappropriate']):(0);
    $distanza = (isset($parametri['distanza']) && is_numeric($parametri['distanza']))?((float)$parametri['distanza']):(0.0);
    
    // costruisce un array per il filtro sulle categorie
    if (count($parametri['tipi'])) {
        foreach ($parametri['tipi'] as $key => $value) {
            if ($value==1)
                $tipi[$key] = $value;
        }
    } else {
        $tipi = array();   
    }
    
    // costruisce un array per filtro sulla area
    if (count($parametri['area'])) {
        foreach ($parametri['area'] as $key => $value) {
            $area[$key] = (float) $value;
        } 
    } else {
        $area = array();
    }

    $formato = $parametri['formato'];

    $user = logged_user_get();
    
    // INIZIO COSTRUZIONE SELECT
    $q_select = "SELECT s.id_segnalazione, s.id_tipo, s.lat, s.lng, s.id_comune, s.civico,
                 s.cap, s.indirizzo, s.citta, s.regione, s.regione_url, s.citta_url,
                 s.indirizzo_url, s.id_utente, s.messaggio, s.stato, s.client, 
                 s.data, s.data AS last_edit, u.id_fb, u.nome, u.cognome, u.mostra_cognome, 
                 u.id_ruolo, t.nome AS tipo_nome, t.nome_url AS tipo_nome_url, 
                 t.label AS tipo_label, e.id_ente, e.nome AS nome_ente,
								 cz.id_competenza as id_competenza, cz.nome as nome_competenza, cz.nome_url as nome_url_competenza, ";
    
    if ($user) {
        // se l'utente è loggato ed è necessario estrarre le informazioni sul follow
            $q_select .= ", sf.id_follow as logged_user_following ";
    }
    
	if ($conteggio_inappropriate) {
		$q.=", (SELECT count(*) FROM tab_segnalazioni_improprie WHERE id_segnalazione = s.id_segnalazione) AS n_inappropriate ";
	}
    // FINE COSTRUZIONE SELECT
    
    // INIZIO COSTRUZIONE FROM
    $q_from = "FROM tab_segnalazioni AS s
                INNER JOIN tab_utenti AS u ON u.id_utente = s.id_utente
                LEFT JOIN tab_enti AS e ON e.id_ente = s.id_ente
                LEFT JOIN tab_competenze AS cz ON s.id_competenza = cz.id_competenza
                LEFT JOIN tab_tipi AS t ON s.id_tipo = t.id_tipo ";
    
    if ($user) {
        // se l'utente è loggato ed è necessario estrarre le informazioni sul follow
            $q_from .= "LEFT JOIN tab_segnalazioni_follow AS sf ON sf.id_utente = " . $user['id_utente'] . " and sf.id_segnalazione = s.id_segnalazione ";

    }
    // FINE COSTRUZIONE FROM
    
    
    // INIZIO COSTRUZIONE WHERE
    $q_where = "WHERE u.confermato = 1 AND 
                        u.eliminato = 0 AND 
                        s.eliminata = 0 AND 
                        s.archiviata = 0 ";
    
    if ($includi_non_approvate) {
        $q_where .= " ";
    } else {
        $q_where .= " AND s.stato >= ".$settings['segnalazioni']['in_attesa'];
    }
    
    if ($id_user) {
        $q_where .= " AND s.id_utente = ".$id_user;
    }
    
    if ($regione) {
        $q_where .= " AND s.regione_url = '$regione' ";
    }
    
    if ($id_comune) {
        $q_where .= " AND s.id_comune = ".$id_comune;
    }
    
		if ($id_competenza >= 0) {
				$q_where .= " AND s.id_competenza =".$id_competenza;
		}

    if (count($tipi)) {
        $q_where .= " AND (";
        foreach ($tipi as $key => $tipo) {
            if ($tipo)
                $q_where .= " s.id_tipo = $key OR ";
        }
        $q_where .= " 1=0) ";
    }
    
    if ($recenti) {
        $t_min = (int) time() - $recenti * 24 * 60 * 60;
        $q_where .= " AND s.data > $t_min ";
    }
    
    if ($nuove) { 
        $q_where .= " AND s.data > $nuove ";
    }
    
    if ($vecchie) { 
        $q_where .= " AND s.data < $vecchie ";
    }    
    
    if ($stato==$settings['segnalazioni']['risolta']) {
        $q_where .= " AND s.stato >= " . $stato;
    } else if ($stato==$settings['segnalazioni']['in_carico']) {
        $q_where .= " AND s.stato >= " . $stato . " AND s.stato < ".$settings['segnalazioni']['risolta'];
    } else if ($stato==$settings['segnalazioni']['in_attesa']) {
        $q_where .= " AND s.stato >= " . $stato . " AND s.stato < ".$settings['segnalazioni']['in_carico'];
    }
    
    if (count($area)) {
        $q_where .= " AND s.lat > " . $area['minLat'] . 
                    " AND s.lat < " . $area['maxLat'] . 
                    " AND s.lng > " . $area['minLng'] . 
                    " AND s.lng < " . $area['maxLng'];
    }    
    // FINE COSTRUZIONE WHERE
    

    $q_order_by = " ORDER BY data DESC ";
    
    $q = $q_select.$q_from.$q_where.$q_order_by;

    if ($limit) {
        $q .= " LIMIT $limit";
    }

    $segnalazioni = data_query($q);
    
    if (count($segnalazioni)) {
        foreach ($segnalazioni as $key => $segnalazione) {
            // esclude il cognome di quegli utenti che hanno impostato come preferenza 
            // di non mostrare il proprio cognome
            if (!$segnalazione['mostra_cognome'])
                $segnalazioni[$key]['cognome'] = '';

            // include l'url all'avatar dell'utente
            $segnalazioni[$key]['avatar'] = user_avatar_get($segnalazione);
            
            // aggiunge il base url dell'immagine
            $segnalazioni[$key]['foto_base_url'] = segnalazione_image_url_get($segnalazione);
            
            // aggiunge l'url al marker corretto da utilizzare in base a tipo/stato/genere
            $segnalazioni[$key]['marker'] = segnalazione_marker_url_get($segnalazione);
            
            // se richiesto, recupera di commenti della segnalazione
            if ($commenti) {
                $segnalazioni[$key]['commenti'] = segnalazione_commenti_get($segnalazione['id_segnalazione']);
                if ($segnalazioni[$key]['commenti'])
                    $segnalazioni[$key]['last_edit'] = $segnalazioni[$key]['commenti'][0]['data'];
            }
            
            // per ogni segnalazione aggiunge il conteggio dei followers
            $q_followers = "SELECT COUNT(*) as n_followers
                                FROM tab_segnalazioni_follow 
                                WHERE id_segnalazione = ".$segnalazione['id_segnalazione'];
            $res_followers = data_query($q_followers);
            if ($res_followers) {
                $segnalazioni[$key]['n_follower'] = $res_followers[0]['n_followers'];
            } else {
                $segnalazioni[$key]['n_follower'] = 0;
            }
            
            // nel caso in cui sia richiesto aggiunge il numero di segnalazioni in zona
            // rispetto alla segnalazione corrente
            if ($distanza) {
                $q_d = "SELECT COUNT(*) AS in_zona
                            FROM tab_segnalazioni
                            WHERE stato >= ".$settings['segnalazioni']['in_carico']." AND
                                  eliminata = 0 AND 
                                  archiviata = 0 AND
                                  SQRT(POW(lat-" . $segnalazione['lat'] . ",2)+POW(lng-" . $segnalazione['lng'] . ",2)) < " . $distanza;

                $data = data_query($q);
                if ($data) {
                    $segnalazioni[$key]['in_zona'] = $data[0]['in_zona'];
                } else {
                    $segnalazioni[$key]['in_zona'] = 0;
                }
            }
        }
    }
    
    if ($formato == 1)
        return json_encode($segnalazioni);
    else
        return $segnalazioni;    
}

/**
 * Restituisce la lista delle segnalazioni di interesse per un utente
 * 
 * Restituisce un array di segnalazioni con cui un utente ha interagito in qualche modo
 * ad es. commentando, marcando il doit.
 * - limit      int     limita il numero di segnalazioni restituite
 * - id_user    int     filtra le segnalazioni inviate dall'utente con id = id_user
 * - nuove      int     filtra le segnalazioni più nuove del timestamp passato come parametro
 * - vecchie    int     filtre le segnalazioni più vecchie del timestamp passato come parametro
 * - formato    int     indica il formato in cui restituire la lista delle segnalazioni, se 1 il formato è una stringa JSON
 * @global array $settings
 * @global array $user array con le informazioni dell'utente loggato
 * @param array $parametri lista dei parametri per filtrare le segnalazioni
 * @return array|string lista delle segnalazioni corrispondenti ai filtri oppure
 *                      una stringa JSON
 */
function segnalazioni_user_wall_get($parametri) {
    global $settings,$user;
    
    
    $limit = (isset($parametri['limit']) && is_numeric($parametri['limit']))?((int)$parametri['limit']):(0);
    $id_user = (isset($parametri['id_user']) && is_numeric($parametri['id_user']))?((int)$parametri['id_user']):(0);
    $nuove = (isset($parametri['nuove']) && is_numeric($parametri['nuove']))?((int)$parametri['nuove']):(0);
    $vecchie = (isset($parametri['vecchie']) && is_numeric($parametri['vecchie']))?((int)$parametri['vecchie']):(0);
    $formato = (isset($parametri['formato']) && is_numeric($parametri['formato']))?((int)$parametri['formato']):(0); 
    
    // INIZIO COSTRUZIONE SELECT
    $q_select = "SELECT DISTINCT(s.id_segnalazione), s.id_tipo, s.lat, s.lng, s.id_comune, s.civico,
                 s.cap, s.indirizzo, s.citta, s.regione, s.regione_url, s.citta_url,
                 s.indirizzo_url, s.id_utente, s.messaggio, s.stato, s.client, 
                 s.data, u.id_fb, u.nome, u.cognome, u.mostra_cognome, 
                 u.id_ruolo, t.nome AS tipo_nome, t.nome_url AS tipo_nome_url, 
                 t.label AS tipo_label, MAX(GREATEST(s.data,IFNULL(c.data,0),IFNULL(sf.data,0))) AS last_edit ";
    
    // FINE COSTRUZIONE SELECT
    
    // INIZIO COSTRUZIONE FROM
    $q_from = " FROM tab_segnalazioni AS s
                    INNER JOIN tab_utenti AS u ON u.id_utente = s.id_utente
                    LEFT JOIN tab_tipi AS t ON s.id_tipo = t.id_tipo                     
                    LEFT JOIN tab_commenti AS c ON s.id_segnalazione = c.id_segnalazione 
                    LEFT JOIN tab_segnalazioni_follow AS sf ON s.id_segnalazione = sf.id_segnalazione ";
    
    // FINE COSTRUZIONE FROM
    
    
    // INIZIO COSTRUZIONE WHERE
    $q_where = " WHERE u.confermato = 1 AND 
                        u.eliminato = 0 AND
                        s.eliminata = 0 AND 
                        s.archiviata = 0 AND  
                        (s.id_utente = $id_user OR
                        c.id_utente = $id_user OR    
                        sf.id_utente = $id_user ) ";
    
    
    if ($nuove) { 
        $q_where .= " AND GREATEST(s.data,IFNULL(c.data,0),IFNULL(sf.data,0)) > $nuove ";
    }
    
    if ($vecchie) { 
        $q_where .= " AND GREATEST(s.data,IFNULL(c.data,0),IFNULL(sf.data,0)) < $vecchie ";
    }
    
    // FINE COSTRUZIONE WHERE
    
    $q_order_by = " GROUP BY s.id_segnalazione ORDER BY GREATEST(s.data,IFNULL(c.data,0),IFNULL(sf.data,0)) DESC ";
    
    $q = $q_select.$q_from.$q_where.$q_order_by;

    if ($limit) {
        $q .= " LIMIT $limit";
    }

    $segnalazioni = data_query($q);
    
    if (count($segnalazioni)) {
        foreach ($segnalazioni as $key => $segnalazione) {
            // esclude il cognome di quegli utenti che hanno impostato come preferenza 
            // di non mostrare il proprio cognome
            if (!$segnalazione['mostra_cognome'])
                $segnalazioni[$key]['cognome'] = '';

            // include l'url all'avatar dell'utente
            $segnalazioni[$key]['avatar'] = user_avatar_get($segnalazione);
            
            // aggiunge il base url dell'immagine
            $segnalazioni[$key]['foto_base_url'] = segnalazione_image_url_get($segnalazione);
        }
    }
    
    if ($formato == 1)
        return json_encode($segnalazioni);
    else
        return $segnalazioni;
    
    
    
    
    
    
        if ($wall) {
        if (count($segnalazioni))
            usort($segnalazioni, 'sortByLastEdit');

        if ($recenti) {
            $t_min = (int) time() - $recenti * 24 * 60 * 60;
            $segnalazioni_temp = array();
            foreach ($segnalazioni as $segnalazione) {
                if ($segnalazione['last_edit'] > $t_min)
                    array_push($segnalazioni_temp, $segnalazione);
            }
            $segnalazioni = $segnalazioni_temp;
        }

        if ($vecchie) { // Usato per selezionare le segnalazioni precedenti quando si clicca sul link in fondo all'elenco
            $segnalazioni_temp = array();
            foreach ($segnalazioni as $segnalazione) {
                if ($segnalazione['last_edit'] < $vecchie)
                    array_push($segnalazioni_temp, $segnalazione);
            }
            $segnalazioni = $segnalazioni_temp;
        }

        if ($limit)
            $segnalazioni = array_splice($segnalazioni, 0, $limit);
    }
}


/**
 * Restituisce il dettaglio di una segnalazione
 * 
 * Questa funzione restituisce i dettagli della segnalazione con l'id passato come parametro
 * 
 * @global array $settings
 * @param int $id id della segnalazione da restituire
 * @return mixed restituisce un array con i dati della segnalazione o false se la segnalazione non viene trovata 
 */
function segnalazione_dettaglio_get($id) {
    global $settings;
    
    $id = (int) $id;

    $q = "SELECT s.*, u.id_fb, u.nome, u.cognome, u.mostra_cognome, u.id_ruolo, t.nome AS tipo_nome, t.label AS tipo_label, e.id_ente, e.nome AS nome_ente,
    				cz.id_competenza as id_competenza, cz.nome as nome_competenza, cz.nome_url as nome_url_competenza,
                (SELECT COUNT(*) 
                    FROM tab_segnalazioni_follow AS sf 
                    WHERE sf.id_segnalazione = s.id_segnalazione) AS n_follower
            FROM tab_segnalazioni AS s
                INNER JOIN tab_utenti AS u ON u.id_utente = s.id_utente 
                LEFT JOIN tab_competenze AS cz ON s.id_competenza = cz.id_competenza
                LEFT JOIN tab_tipi AS t ON s.id_tipo = t.id_tipo
                LEFT JOIN tab_enti AS e ON e.id_ente = s.id_ente
            WHERE u.confermato = 1 AND 
                    u.eliminato = 0 AND
                    s.eliminata = 0 AND 
                    archiviata = 0 AND 
                    s.id_segnalazione = $id";

    $segnalazione = data_query($q);

    if (count($segnalazione)) {
        // cancella il cognome dell'utente nel caso in base alle impostazioni 
        if (!$segnalazione[0]['mostra_cognome'])
            $segnalazione[0]['cognome'] = '';

        // recupera il numero delle segnalazioni in zona
        $q = "SELECT COUNT(*) AS num
                FROM tab_segnalazioni
		WHERE stato >= 100 AND 
                        eliminata = 0 AND 
                        archiviata = 0 AND
                        SQRT(POW(lat - ".$segnalazione[0]['lat'].",2) + 
                             POW(lng - ".$segnalazione[0]['lng'].",2)) < ".$settings['segnalazioni']['limit_distanza'];
        
        $data = data_query($q);
        $segnalazione[0]['in_zona'] = $data[0]['num'];

        // recupera il numero delle altre segnalazioni dell'utente
        $q = "SELECT COUNT(*) AS num
                FROM tab_segnalazioni
		WHERE stato >= 100 AND 
                        eliminata = 0 AND 
                        archiviata = 0 AND
                        id_utente = ".$segnalazione[0]['id_utente'];
        
        $data = data_query($q);
        $segnalazione[0]['segnalazioni_utente'] = $data[0]['num'];
        
        // recupera la lista dei commenti della segnalazione
        $segnalazione[0]['commenti'] = segnalazione_commenti_get($id);

        $segnalazione[0]['tipo_nome_url'] = fixForUri($segnalazione[0]['tipo_nome']);
        $segnalazione[0]['citta_url'] = fixForUri($segnalazione[0]['citta']);
        $segnalazione[0]['indirizzo_url'] = fixForUri($segnalazione[0]['indirizzo']);
				$segnalazione[0]['foto_base_url'] = segnalazione_image_url_get($segnalazione[0]);
		
				// aggiunge l'url al marker corretto da utilizzare in base a tipo/stato/genere
				$segnalazione[0]['marker'] = segnalazione_marker_url_get($segnalazione[0]);
		
        // costruisce il link all'avater dell'utente
        $segnalazione[0]['avatar'] = user_avatar_get($segnalazione[0]);

        return $segnalazione;
    } else {
        return false;
    }
}

/**
 * Cancella la segnalazione di un utente
 * 
 * Questa funzione marca come cancellata la segnalazione di un utente, nel caso in cui
 * esso sia il proprietario della segnalazione
 * 
 * @param int $id id della segnalazione
 * @param int $id_utente id del utente che richiede la cancellazione
 * @return boolean true se l'azione è andata a buon fine, false altrimenti
 */
function segnalazione_delete($id,$id_utente) {
    
    $q="SELECT * 
            FROM tab_segnalazioni 
            WHERE id_segnalazione = $id AND
                    id_utente = $id_utente";

    if (!($res = data_query($q)) || !count($res)) {
	return false;
    }
    
    $q="UPDATE tab_segnalazioni 
           SET eliminata = 1 
         WHERE id_segnalazione = $id";

    return data_query($q);
}



/**
 * Restituisce i commenti di una segnalazione
 * 
 * Questa funzione restituisce un'array con i commenti relativi ad una segnalazione
 * 
 * @global array $settings
 * @param int $id id della segnalazione di cui recuperare i commenti
 * @return array 
 */
function segnalazione_commenti_get($id) {
    global $settings;
    
    $id = (int) $id;


    // Commenti segnalazione
    $q = 'SELECT c.*, u.id_fb, u.nome, u.cognome, u.mostra_cognome, u.id_ruolo
            FROM tab_commenti AS c
                INNER JOIN tab_utenti AS u ON c.id_utente = u.id_utente
            WHERE u.confermato = 1 AND 
                    u.eliminato = 0 AND
                    c.id_segnalazione = ' . $id . ' AND 
                    c.eliminato = 0 
            ORDER BY data DESC';


    $commenti = data_query($q);

    if ($commenti) {
        foreach ($commenti as $key => $commento) {
            // esclude il cognome di quegli utenti che hanno impostato come preferenza 
            // di non mostrare il proprio cognome
            if (!$commento['mostra_cognome'])
                $commenti[$key]['cognome'] = '';

            // include l'url all'avatar dell'utente
            $commenti[$key]['avatar'] = user_avatar_get($commento);
        }

        return $commenti;
    } else {
        return array();
    }
}

/**
 * Restituisce l'url del marker da utilizzare per una segnalazione
 * 
 * Questa funzione restituisce una stringa contenente l'url in base a tipo/stato/genere della segnalazione
 * 
 * @param array $segnalazione array contenente i dati relativi alla segnalazione di cui recuperare il marker
 * @return string
 */
function segnalazione_marker_url_get($segnalazione) {

  if ($segnalazione['id_competenza']) {
	  if ($segnalazione['stato'] >= 300) {
			$marker = '/images/'.$segnalazione[0]['nome_url_competenza'].'_risolta.png';
	  } else if ($segnalazione['stato'] >= 200) {
		  $marker = '/images/'.$segnalazione['nome_url_competenza'].'_carico.png';
	  } else {
		  $marker = '/images/'.$segnalazione['nome_url_competenza'].'_marker.png';
	  }
	} else {      
	  if ($segnalazione['stato'] >= 300) {
			$marker = '/images/risolta_'.$segnalazione['tipo_label'].'.png';
	  } else if ($segnalazione['stato'] >= 200) {
		  $marker = '/images/carico_'.$segnalazione['tipo_label'].'.png';
	  } else {
		  $marker = '/images/marker_'.$segnalazione['tipo_label'].'.png';
	  }
	}
	
	return $marker;
	
}

/**
 * Registra un follow per una segnalazione
 * 
 * Questa funzione inserisce un follow, o DoIT, di un utente su una segnalazione
 * 
 * @param int id_segnalazione id della segnalazione
 * @param int id_utente id dell'utente che effettua il follow
 * @return boolean
 */
function segnalazione_follow_insert($id_segnalazione,$id_utente) {
    
    // verifica che l'utente non stia già seguendo la segnalazione
    $q = "SELECT * 
            FROM tab_segnalazioni_follow 
            WHERE id_segnalazione = $id_segnalazione AND 
                    id_utente = $id_utente";
    
    $res = data_query($q);

    if (count($res)) {
        return false;
    }

    $q = "INSERT INTO tab_segnalazioni_follow (id_segnalazione, id_utente, data) 
                VALUES ('$id_segnalazione','$id_utente'," . time() . ")";

    if (!data_query($q)) {
        return false;
    }
    
    return true;
}

/**
 * Rimuove il follow da una segnalazione
 * 
 * Questa funzione rimuove la sottoscrizione di una segnalazione per un utente
 * 
 * @param int id_segnalazione id della segnalazione
 * @param int id_utente id dell'utente che effettua il follow
 * @return boolean
 */
function segnalazione_follow_delete($id_segnalazione,$id_utente) {
    
    $q = "DELETE FROM tab_segnalazioni_follow 
            WHERE id_segnalazione = $id_segnalazione AND 
                    id_utente = $id_utente";
    
    if (data_query($q)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Restituisce il numero di utente di followers di una segnalazione
 * 
 * Questa funzione restituisce il numero di utenti che hanno sottoscritto una segnalazione
 * 
 * @param int $id_segnalazione id della segnalazione
 * @return int numero di followers
 */
function segnalazione_follow_count($id_segnalazione) {
    $q = "SELECT COUNT(*) AS num
            FROM tab_segnalazioni_follow 
            WHERE id_segnalazione = $id_segnalazione";
    
    $res = data_query($q);
    
    if (!$res) {
        return 0;
    } else {
        return $res[0]['num'];
    }
}

/**
 * Flagga una segnalazione come impropria
 * 
 * Questa funzione inserisce un record di segnalazione impropria
 * 
 * @param int $id_segnalazione id della segnalazione
 * @param int $id_utente id dell'utente
 * @return boolean
 */
function segnalazione_impropria_insert($id_segnalazione,$id_utente) {
    $q = "INSERT INTO tab_segnalazioni_improprie (id_segnalazione, id_utente, data) 
                VALUES ('$id_segnalazione','$id_utente'," . time() . ")";
    
    if (data_query($q)) {
        return true;
    } else {
        return false;
    }

}


/**
 * Effettua lo share su Facebook di una segnalazione
 * 
 * Questa funzione inserisce su Facebook, sul wall dell'utente, 
 * un post con i dati della segnalazione appena effettuata
 * 
 * @global Facebook $facebook
 * @global array $settings
 * @param array $segnalazione
 * @return array 
 */
function segnalazione_fb_share($segnalazione) {
    global $facebook,$settings;

    // costruisce i campi per lo share su FB
    $campi_per_fb = array(
        'link' => $segnalazione['link_segnalazione'],
        'name' => $segnalazione['categoria'] . ' a ' . $segnalazione['citta'] . ' in ' . $segnalazione['via'],
        'caption' => parse_url($settings['sito']['url'], PHP_URL_HOST),
        'description' => 'Utilizza anche tu Decoro Urbano, lo strumento gratuito per la segnalazione del degrado via smartphone e PC. La cittadinanza attiva comincia da te.',
        'message' => LimitaTesto($segnalazione['messaggio'], 300, ''),
        'picture' => $settings['sito']['url'].'images/segnalazioni/' . $segnalazione['id_utente'] . '/' . $segnalazione['id_segnalazione'] . '/1.jpeg'
    );

    try {
        // chiama l'api di FB
        $result = $facebook->api('/me/feed', 'POST', $campi_per_fb);
    } catch (Exception $e) {
        $err_str = $e->getMessage();
    }

    return $result;
}

/**
 * Restituisce l'URL della foto della segnalazione
 * 
 * Costruisce l'url della foto della segnalazione a partire da un array contenente
 * i dati della segnalazione
 * 
 * @param array segnalazione 
 * @return string
 */
function segnalazione_image_url_get($segnalazione) {
	$base_url = '/images/segnalazioni/'.$segnalazione['tipo_nome_url'].'-'.$segnalazione['citta_url'].'-'.$segnalazione['indirizzo_url'].'-'.$segnalazione['id_utente'].'-'.$segnalazione['id_segnalazione'].'-';
	return $base_url;
}

/**
 * Inserisce un commento ad una segnalazione
 * 
 * Questa funzione inserisce un commento ad una segnalazione, gestendo l'eventuale
 * invio di email di notifica all'utente che ha inviato la segnalazione
 * 
 * @global array $settings
 * @param array $commento array contenente i dettagli del commento
 * @return mixed l'id del commento se l'operazione è andata a buon fine, false altrimenti 
 */
function commento_insert($commento) {
    global $settings;
    
    $id_commento = data_insert("tab_commenti", $commento);
    if ($id_commento) {
        // se l'inserimento è andato a buon fine, invia un'email di notifica all'utente
        // che ha inserito la segnalazione
    
        // recupera le informazioni relative all'utente segnalatore 
        $q = "SELECT u.id_utente, u.email, u.nome, u.cognome, u.email_commento,
                        t.nome AS categoria, s.citta, s.indirizzo
                FROM tab_utenti AS u 
                    LEFT JOIN tab_segnalazioni AS s ON s.id_utente = u.id_utente
                    LEFT JOIN tab_tipi AS t ON t.id_tipo = s.id_tipo
		WHERE s.id_segnalazione = ".$commento['id_segnalazione'];

        $destinatario = data_query($q);

        if ($destinatario[0]['email_commento']) {
            // se il segnalatore ha richiesto, tra le proprie impostazioni, l'invio
            // di un'email di notifica in caso di commenti alle proprie segnalazioni,
            // invia l'email
        
            // recupera i dati dell'utente che ha inserito il commento
            $q = "SELECT nome, cognome, mostra_cognome 
                    FROM tab_utenti 
                    WHERE id_utente = " . $commento['id_utente'];

            $mittente = data_query($q);
            
            // gestisci l'impostazione di privacy dell'utente che ha inserito il commento
            // relativamente al cognome
            if ($mittente[0]['mostra_cognome'])
                $nome_mittente = $mittente[0]['nome'] . ' ' . $mittente[0]['cognome'];
            else
                $nome_mittente = $mittente[0]['nome'];

            // costruisce il link alla segnalazione
            $link_segnalazione = $settings['sito']['url'] . fixForUri($destinatario[0]['categoria']) . '/' . fixForUri($destinatario[0]['citta']) . '/' . fixForUri($destinatario[0]['indirizzo']) . '/' . $commento['id_segnalazione'] . '/';

            // prepara l'email di notifica all'utente
            
            $data['from'] = $settings['email']['nome'] . ' <' . $settings['email']['indirizzo'] . '>';
            $data['to'] = $destinatario[0]['email'];
            $data['template'] = 'segnalazioneCommento'; // template dell'email da inviare
            // inizializzazione variabili del tempalte
            $variabili['nome_utente'] = $destinatario[0]['nome'] . ' ' . $destinatario[0]['cognome'];
            $variabili['nome_utente2'] = $nome_mittente;
            $variabili['commento'] = str_replace(PHP_EOL, '<br />', trim($_POST['commento']));
            $variabili['link_segnalazione'] = $link_segnalazione;
            $data['variabili'] = $variabili;

            // invia email
            email_with_template($data);
        }
        return $id_commento;
    }
    return false;
}


/**
 * Cancella il commento di un utente
 * 
 * Questa funzione marca come cancellato un commento di un utente, nel caso in cui
 * esso sia il proprietario del commento
 * 
 * @param int $id id del commento
 * @param int $id_utente id del utente che richiede la cancellazione
 * @return boolean true se l'azione è andata a buon fine, false altrimenti
 */
function commento_delete($id,$id_utente) {
    
    $q="SELECT * 
            FROM tab_commenti 
            WHERE id_commento = $id AND 
                    id_utente = $id_utente";

    if (!($res = data_query($q)) || !count($res)) {
	return false;
    }
    
    $q="UPDATE tab_commenti 
           SET eliminato = 1 
         WHERE id_commento = $id";

    return data_query($q);
}

/**
 * Flagga un commento come improprio
 * 
 * Questa funzione memorizza un record relativo alla segnalazione di un utente
 * di un commento ritenuto improprio
 * 
 * @param int $id id del commento
 * @param int $id_utente id del utente che effetta la segnalazione del commento improprio
 * @return boolean true se l'azione è andata a buon fine, false altrimenti
 */
function commento_improrio_insert($id,$id_utente) {
    $q="INSERT INTO tab_commenti_impropri (id_commento, id_utente, data) 
        VALUES ('$id','$id_utente',".time().")";
    
    return data_query($q);
}


/**
 * Aggiorna la sessione dell'utente
 * 
 * Aggiorna le informazioni relative all'utente memorizzate nella sessione, recuperando
 * quelle aggiornate dal database
 * 
 * @param int $id id utente di cui inserire i dati in sessione
 * @return array array contenente i dati dell'utente appena inseriti in sessione
 */
function user_session_update($id) {

    $user = user_get($id);
    $_SESSION['user'] = $user;
    return $user;
}

/**
 * Restituisce i dati di un utente
 * 
 * Questa funzione recupera dal database i dati di utente identificato dall'id passato
 * come parametro. Accetta due parametri opzionali per filtrare sullo stato dell'utente.
 * Di default restituisce i dati di un utente solo se questo è confermato e non eliminato
 * 
 * @global array $settings
 * @param int $id id dell'utente di cui recuperare i dati dal database
 * @param int $confermato 1|0 recupera i dati di un utente confermato
 * @param int $eliminato 1|0 recupera i dati di un utente non eliminato
 * @return type 
 */
function user_get($id,$confermato=1,$eliminato=0) {
    global $settings;

    $id = (int) $id;
    $confermato = ($confermato==1)?(1):(0);
    $eliminato = ($eliminato==1)?(1):(0);

    // recupero le informazioni dalla tabella utenti
    $user = data_get('tab_utenti', array('id_utente' => $id, 'confermato' => $confermato, 'eliminato' => $eliminato));

    if ($user===false || empty($user)) {
        return false;
    }
    
    // costruisco il link all'avatar
    $user[0]['avatar'] = user_avatar_get($user[0]);
    
    // se l'utente è un utente FB, aggiungo i dati specifici
    if ($user[0]['id_fb']) {
        if ($user[0]['facebook_url'] == '') {
            $user[0]['facebook_url'] = 'profile.php?id=' . $user[0]['id_fb'];
        }
    }
    
    // memorizzo il cognome in una variabile diversa per poter tenere conto della 
    // preferenza dell'utente sulla visibilità del cognome
    $user[0]['cognome_hidden'] = $user[0]['cognome'];
    if (!$user[0]['mostra_cognome'])
        $user[0]['cognome'] = '';

    // recupero il numero di segnalazioni effettuate
    $segnalazioni_utente = data_query('SELECT COUNT(*) AS num_segnalazioni FROM tab_segnalazioni WHERE id_utente = ' . $id . ' AND stato >= 100 AND eliminata = 0 AND archiviata = 0');
    $user[0]['n_segnalazioni'] = $segnalazioni_utente[0]['num_segnalazioni'];
    
    // calcolo e memorizzo la media di segnalazioni quotidiane effettuate dall'utente
    $giorni = round((time() - $user[0]['data']) / 60 / 60 / 24);
    if ($giorni)
        $user[0]['n_segnalazioni_quotidiane'] = round(($segnalazioni_utente[0]['num_segnalazioni']) / $giorni, 1);
    else
        $user[0]['n_segnalazioni_quotidiane'] = $segnalazioni_utente[0]['num_segnalazioni'];

    if (substr($user[0]['sito'], 0, 7) == 'http://')
        $user[0]['sito'] = substr($user[0]['sito'], 7);

    // se l'utente è un comune recupero le statistiche delle segnalazioni relative 
    // a quel comune
    if ($user[0]['id_ruolo'] == 3) {
        $q = 'SELECT *,
                (SELECT COUNT(*) 
                    FROM tab_segnalazioni
                    WHERE id_comune = tc.id_comune AND 
                        stato >= 100 AND 
                        eliminata = 0 AND 
                        archiviata = 0) AS totali,
		(SELECT COUNT(*) 
                    FROM tab_segnalazioni 
                    WHERE id_comune = tc.id_comune AND 
                        stato BETWEEN 200 AND 299 AND
                        eliminata = 0 AND archiviata = 0) AS in_carico,
		(SELECT COUNT(*)
                    FROM tab_segnalazioni 
                    WHERE id_comune = tc.id_comune AND
                    stato >= 300 AND 
                    eliminata = 0 AND 
                    archiviata = 0) AS risolte
		FROM tab_comuni tc
		WHERE id_comune = ' . $user[0]['id_comune'];
        $data = data_query($q);
        $user[0]['comune'] = $data[0];
    }

    return $user[0];
}

/**
 * Restituisce i dati di un utente loggato tramite Facebook
 * 
 * Questa funzione utilizza le API di Facebook per ottenere il profilo di un utente
 * Loggato tramite Facebook. Restituisceun array con i dati del profilo o false
 * 
 * @global array $settings
 * @global Facebook $facebook
 * @return mixed array con i dati del profilo o false
 */
function user_fb_get() {
    global $settings;
    global $facebook;

    require_once($settings['sito']['percorso'] . 'include/facebook_3.1.1/facebook.php');

    // Create Application instance.
    $facebook = new Facebook(array(
                'appId' => $settings['facebook']['app_id'],
                'secret' => $settings['facebook']['app_secret'],
                'cookie' => true
            ));

    $fb_user = $facebook->getUser();

    // We may or may not have this data based on whether the user is logged in.
    // If we have a $user id here, it means we know the user is logged into
    // Facebook, but we don't know if the access token is valid. An access
    // token is invalid if the user logged out of Facebook.

    if ($fb_user) {
        try {
            // Proceed knowing you have a logged in user who's authenticated.
            $fb_user_profile = $facebook->api('/me');
            return $fb_user_profile;
        } catch (FacebookApiException $e) {
            error_log($e);
            $fb_user = null;
            return false;
        }
    }
}

// Restituisce l'id utente dell'utente sul DB locale corrispondente all'id FB passato oppure false se non è presente

/**
 * Restituisce i dati memorizzati nel db di un utente loggato tramite Facebook
 * 
 * @param int $id_fb id Facebook dell'utente da restituire
 * @return mixed array contenente i dati dell'utente, false altrimenti 
 */
function user_fb_get_from_db($id_fb) {

    if ($user = data_get('tab_utenti', array('id_fb' => $id_fb)))
        return $user[0];
    else
        return false;
}

/**
 * Inserisce nel db i dati di un utente Facebook
 * 
 * Questa funzione inserisce nel db i dati di un utente loggato tramite Facebook 
 * che ha autorizzato Decoro Urbano. Nel caso in cui l'email utilizzata fosse già presente
 * aggiorna il record corrispondente
 * 
 * @param array $fb_user array contenente i dati dell'utente Facebook 
 */
function user_fb_insert($fb_user) {
    global $settings;
    
    // se nel db è già presente un record con la stessa email, aggiorna il record
    // con i dati dell'utente
    if ($fb_user['email'] != '' && $data = data_get('tab_utenti', array('email' => $fb_user['email'], 'eliminato' => 0))) {
        data_update('tab_utenti', 
                        array(
                            'id_fb' => $fb_user['id'], 
                            'facebook_url' => $fb_user['username'], 
                            'confermato' => 1), 
                        array('email' => $fb_user['email']));
    } else {

        $fields['id_fb'] = $fb_user['id'];
        $fields['nome'] = $fb_user['first_name'];
        $fields['cognome'] = $fb_user['last_name'];
        $fields['email'] = $fb_user['email'];
        $fields['facebook_url'] = $fb_user['username'];
        $fields['id_ruolo'] = $settings['user']['ruolo_utente_normale'];
        $fields['confermato'] = 1;
        $fields['data'] = time();

        data_insert('tab_utenti', $fields);
    }
}


/**
 * Esegue il logout dell'utente
 * 
 * Questa funzione effettua il logout dell'utente cancellando la sessione e i cookie
 *
 */
function user_logout() {

    unset($_SESSION['fb_session']);
    unset($_SESSION['user']);
    unset($_SESSION['ERRMSG_ARR']);
    setcookie("user_email", '', time() - 60 * 60 * 24, "/", ".".$settings['sito']['dominio']);
    setcookie("user_password", '', time() - 60 * 60 * 24, "/", ".".$settings['sito']['dominio']);
}


/**
 * Restituisce l'utente loggato
 * 
 * Restituisce un array con i dati dell'utente loggato oppure false se non c'è utente loggato.
 * La funzione gestisce la differenza tra utente locale e utente Facebook 
 * 
 * @global array $settings
 * @global type $fb_user
 * @return mixed restituisce un array con i dati dell'utente o false 
 */

function logged_user_get() {
    global $settings;
    global $fb_user;
    
    // controlla se in session sono presenti i dati di un utente
    if (isset($_SESSION['user']) && isset($_SESSION['user']['id_utente']) && (trim($_SESSION['user']['id_utente']) != '')) {
        // controlla se in sessione è presente per l'utente un id facebook
        if ($_SESSION['fb_session']) {
            // Controllo se l'utente è attualmente loggato su FB e se il suo id corrisponde con quello che ho in sessione
            $fb_user = user_fb_get();
            if ($fb_user && (int) $_SESSION['fb_session'] === (int) $fb_user['id']) {
                // se gli id corrispondono, l'utente è loggato, aggiorno la sessione
                return user_session_update(trim($_SESSION['user']['id_utente']));
            } else {
                // se gli id sono diversi, l'utente non è loggato
                return false;
            }
        } else {
            // l'utente è loggato, aggiorno la sessione
            return user_session_update(trim($_SESSION['user']['id_utente']));
        }
    } else {
        return false;
    }
}

/**
 * Statistiche sullo stato dei comuni di una regione
 * 
 * Restituisce il numero dei comuni attivi, non attivi e totali in una regione
 *  
 * @param string $regione nome della regione 
 */
function stats_comuni_regione_get($regione='italia') {
    global $settings;
    
    $q="SELECT COUNT(*) AS num, stato 
            FROM tab_comuni ";
    
    if ($regione!='italia') {
        $q .= " WHERE regione = '$regione' ";
    }
            
    $q .= " GROUP BY stato";

    $data = data_query($q);
    
    $res['totali'] = 0;
    $res['attivi'] = 0;
    $res['non_attivi'] = 0;
    
    foreach ($data as $row) {
        if ($row['stato']==0) {
            $res['non_attivi'] = $row['num'];
            $res['totali'] += $row['num'];
        } else if ($row['stato']==1) {
            $res['attivi'] = $row['num'];
            $res['totali'] += $row['num'];
        }
    }
    
    return $res;
	
}

/**
 * Statistiche sul numero di segnalazioni effettuate
 * 
 * Restituisce il numero di segnalazioni effettuate eventualmente filtrate secondo
 * i criteri definiti nell'array 'parametri'. In particolare:
 * - regione    string  nome della regione
 * - comune     string  nome del comune
 * - id_comune  int     id del comune
 * - comuni_attivi int  limitare il numero di statistiche ai comuni attivi
 * - limit      int     numero di righe da restituire 
 * @param array $parametri parametri su cui filtrate le segnalazioni
 * @return array contenente il numero di segnalazioni raggruppate secondo i criteri specificati 
 */
function stats_segnalazioni_get($parametri) {
    global $settings;
    
    $limit = (isset($parametri['limit']) && is_numeric($parametri['limit']))?((int)$parametri['limit']):(0);
    $regione = $parametri['regione'];
    $comune = $parametri['comune'];
    $id_comune = (isset($parametri['id_comune']) && is_numeric($parametri['id_comune']))?((int)$parametri['id_comune']):(0);
    $comuni_attivi = ($parametri['comuni_attivi']==1)?(1):(0);
    
    if ($regione) {
        $q = "SELECT COUNT(*) AS totali,tc.*
                FROM tab_segnalazioni AS s
                    INNER JOIN tab_comuni AS tc ON s.id_comune = tc.id_comune 
                WHERE s.stato >= 100 AND
                      s.eliminata = 0 AND 
                      s.archiviata = 0 ";
        
        if ($regione!='italia') {
            $q .= " AND s.regione = '$regione' ";
        }
        
        $q_tail = " GROUP BY tc.id_comune 
                    HAVING totali > 0
                    ORDER BY totali DESC ";

        if ($limit) {
            $q_tail .= " LIMIT $limit ";
        }
        
        return data_query($q.$q_tail);
    } else {
	$q = "SELECT *,
		(SELECT COUNT(*) FROM tab_segnalazioni WHERE id_comune = tc.id_comune AND stato >= 100 AND eliminata = 0 AND archiviata = 0) AS totali,
		(SELECT COUNT(*) FROM tab_segnalazioni WHERE id_comune = tc.id_comune AND stato BETWEEN 200 AND 299 AND eliminata = 0 AND archiviata = 0) AS in_carico,
		(SELECT COUNT(*) FROM tab_segnalazioni WHERE id_comune = tc.id_comune AND stato >= 300 AND eliminata = 0 AND archiviata = 0) AS risolte
		FROM tab_comuni tc 
                WHERE 1=1 ";
        
        if ($comune) {
            $q .= " AND nome_url = '$comune' ";
        } else if ($id_comune) {
            $q .= " AND id_comune = $id_comune ";
        }
        
        if ($comuni_attivi) {
            $q .= " AND stato = 1 ";
        }

        return data_query($q);
    }

    
    	
}


/**
 * Restitusce i cookie dell'utente
 * 
 * Questa funzione restituisce i cookie decodificati dell'email e della password
 * dell'utente se presente, false altrimenti
 * 
 * @return mixed array dei cookie se presenti, false altrimenti 
 */
function cookie_data_get() {

    if (isset($_COOKIE['user_email']) && isset($_COOKIE['user_password'])) {
        $cookie['user_email'] = $_COOKIE['user_email'];
        $cookie['user_password'] = base64_decode($_COOKIE['user_password']);
        return $cookie;
    } else
        return false;
}


/**
 * Effettua il login di un utente
 * 
 * Questa funzione controlla la validità dei dai di login passati come parametri.
 * In caso positivo effettua il login dell'utente impostando sessione e cookie,
 * altrimenti restituisce false
 * 
 * @param string $email email dell'utente
 * @param string $password password dell'utente
 * @param boolean $setcookie impostare o meno i cookie
 * @return boolean
 */
function user_login($email, $password, $setcookie) {

    // validazione dei parametri di login
    if ($email == '') // email non vuota
        $errmsg_arr[] = 'Nome utente non inserito';
    
    if ($password == '') // password non vuota
        $errmsg_arr[] = 'Password non inserita';

    // se l'utente è presente nel db, è attivo, confermato e la password è corretta
    // procedi con il login
    $result = data_get('tab_utenti', array('email' => $email, 'password' => sha1($password), 'confermato' => 1, 'eliminato' => 0));
    if ($result) {
        // aggiorna i dati in sessione
        $user = user_session_update($result[0]['id_utente']);

        unset($_SESSION['ERRMSG_ARR']);

        // se richiesto imposta i cookie relativi all'email e alla password encodata
        if ($setcookie) {
            setcookie("user_email", $user['email'], time() + 60 * 60 * 24 * 100, "/", ".".$settings['sito']['dominio']);
            setcookie("user_password", base64_encode($password), time() + 60 * 60 * 24 * 100, "/", ".".$settings['sito']['dominio']);
        } else {
            setcookie("user_email", '', time() - 60 * 60 * 24, "/", ".".$settings['sito']['dominio']);
            setcookie("user_password", '', time() - 60 * 60 * 24, "/", ".".$settings['sito']['dominio']);
        }
        return true;
    }
    
    // imposta gli errori di login
    $errmsg_arr[] = 'Dati errati';
    $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
    return false;
}



/**
 * Restituisce la lista dei segnalatori entrati oggi nella top ten
 * 
 * @return array 
 */
function segnalatori_top_new_get() {
    global $settings;
    
    // calcola i timestamp del periodo di interesse
    $last_midnight = strtotime('midnight');
    $prev_midnight = strtotime('midnight yesterday');

    $q = "SELECT COUNT(*) AS n_segnalazioni, u.*
            FROM tab_segnalazioni AS s
                LEFT JOIN tab_utenti AS u ON s.id_utente = u.id_utente
            WHERE u.confermato = 1 AND
                    u.eliminato = 0 AND
                    s.eliminata = 0 AND
                    s.archiviata = 0 AND
                    s.stato >= ".$settings['segnalazioni']['in_attesa']." 
                    s.data < ".$prev_midnight." 
            GROUP BY s.id_utente
            HAVING n_segnalazioni > 0
            ORDER BY n_segnalazioni DESC
            LIMIT 10";
    
    $segnalatori_top_ieri = data_query($q);

    $q = "SELECT COUNT(*) AS n_segnalazioni, u.*
	        FROM tab_segnalazioni AS s
	            LEFT JOIN tab_utenti AS u ON s.id_utente = u.id_utente
	        WHERE u.confermato = 1 AND
	                u.eliminato = 0 AND
	                s.eliminata = 0 AND
	                s.archiviata = 0 AND
	                s.stato >= ".$settings['segnalazioni']['in_attesa']." 
	                s.data < ".$last_midnight." 
	        GROUP BY s.id_utente
	        HAVING n_segnalazioni > 0
	        ORDER BY n_segnalazioni DESC
	        LIMIT 10";
    
    $segnalatori_top_oggi = data_query($q);

    $segnalatori_top_nuovi = array();

    foreach ($segnalatori_top_oggi as $segnalatore_oggi) {
        $era_in_top_ieri = 0;
        foreach ($segnalatori_top_ieri as $segnalatore_ieri) {
            if ($segnalatore_ieri['id_utente'] == $segnalatore_oggi['id_utente'])
                $era_in_top_ieri = 1;
        }
        if (!$era_in_top_ieri)
            $segnalatori_top_nuovi[] = $segnalatore_oggi;
    }

    return $segnalatori_top_nuovi;
}

/**
 * Restituisce il numero di abitanti nei comuni attivi
 * 
 * @return int
 */
function get_abitanti_attivi() {
	$q = "SELECT SUM(abitanti) AS abitanti_attivi FROM tab_comuni WHERE stato = 1";
	$res = data_query($q);
	return $res;
}

/**
 * Restituisce l'elenco delle possibili competenze per la gestione delle segnalazioni (i Comuni sono gestiti a parte e non rientrano in questo elenco)
 * 
 * @param int $id_competenza email Filtra le competenza sulla base dell'id
 * @param string $nome_url_competenza Filtra le competenza sulla base del sottodominio (deve essere univoco)
 * @return array
 */
function competenze_get($id_competenza = 0, $nome_url_competenza = '') {

	$filtro = array();
	if ($id_competenza) $filtro['id_competenza'] = $id_competenza;
	if ($nome_url_competenza) $filtro['nome_url'] = $nome_url_competenza;		

	$competenze = data_get('tab_competenze',$filtro);
	
	return $competenze;

}

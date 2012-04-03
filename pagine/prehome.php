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
 * Landing page del sito di DecoroUrbano
 * Sono presenti diverse aree dinamiche:
 * - form di regitrazione
 * - ultime segnalazioni
 * - top segnalatori
 * - nuovi segnalatori
 * - ultimi tweet
 * - Facebook facepile
 */

// inizializza la descrizione
$smarty->assign('metaDesc', 'Decoro Urbano è uno strumento partecipativo per la segnalazione del degrado. Un servizio gratuito per le Istituzioni e per il cittadino.');

// recupera la lista dei top segnalatori
$segnalatori_top = segnalatori_top_get(3);
$smarty->assign('segnalatori_top', $segnalatori_top);

// recupera la lista delle ultime segnalazioni
$parametri['limit'] = 3;
$ultime_segnalazioni = segnalazioni_get($parametri);
$smarty->assign('ultime_segnalazioni', $ultime_segnalazioni);

// recupera la lista dei nuovi segnalatori
$nuovi_utenti = segnalatori_new_get(9);
$smarty->assign('nuovi_utenti', $nuovi_utenti);

// recupera la lista dei tweets relativi a Decoro Urbano
$tweet_data = file_get_contents("http://search.twitter.com/search.json?q=from%3Adecorourbano+OR+%23decorourbano+OR+%23wedu");
$tweet_array = json_decode($tweet_data,TRUE);

$text = $tweet_array['results'][0]['text'];
// trasforma gli URL, gli hash tag e le menzioni presenti nella stringa in link HTML
$text = preg_replace(
    '@(https?://([-\w\.]+)+(/([\w/_\.]*(\?\S+)?(#\S+)?)?)?)@',
     '<a class="tdNone" href="$1">$1</a>',
    $text);    
$text = preg_replace(
    '/@(\w+)/',
    '<a class="tdNone" href="http://twitter.com/$1">@$1</a>',
    $text);    
$text = preg_replace(
    '/\s+#(\w+)/',
    ' <a class="tdNone" href="http://search.twitter.com/search?q=%23$1">#$1</a>',
    $text);

// recupera e formatta la data dell'ultimo tweet
if ($text != '') {
    $twit_time = ConvertitoreData_UNIXTIMESTAMP_IT(strtotime($tweet_array['results'][0]['created_at']));
} else {
    $twit_time = '';
}

//inizializza le variabili smarty relative all'ultimo tweet
$smarty->assign('ultimo_tweet_from_user', $tweet_array['results'][0]['from_user']);
$smarty->assign('ultimo_tweet_avatar', $tweet_array['results'][0]['profile_image_url']);
$smarty->assign('ultimo_tweet', $text);
$smarty->assign('ultimo_tweet_time', $twit_time);

?>

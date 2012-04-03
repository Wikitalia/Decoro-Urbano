{*
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
 *}

{config_load file="testi_email.conf" section="segnalazioneRimozione"}
{include file="_header.tpl"}

<h1 class="fRed fBig">Ciao <span class="fItalic">{$nome_utente}</span>!<br /><br /></h1>
Una tua segnalazione effettuata a {$citta} in {$indirizzo} è stata segnalata come inappropriata e quindi rimossa perchè non conforme alle <a href="{$settings.sito.tos}">Clausole di Utilizzo</a> di <strong>Decoro Urbano.</strong><br />

<br />

{$motivazione}

<br /><br />


 
Per maggiori informazioni ti invitiamo a consultare la <a href="{$settings.sito.guida}">Guida del Buon Segnalatore</a> e le apposite sezioni sul sito <a href="{$settings.sito.url}">{$settings.sito.dominio}</a>

{include file="_footer.tpl"}

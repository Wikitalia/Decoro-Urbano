{*
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
 *}

{config_load file="testi_email.conf" section="registrazioneBenvenuto"}
{include file="_header.tpl"}

<h1 class="fRed fBig"><span class="fItalic">{$nome_utente}</span>, benvenuto su Decoro Urbano!</h1><br /><br />

Grazie per aver confermato il tuo account su <strong>Decoro Urbano</strong>, lo strumento web 2.0 per la cittadinanza attiva.<br />
Da questo momento puoi effettuare segnalazioni tramite il <a href="{$settings.sito.inviaSegnalazione}">sito</a> e tramite le <a href="{$settings.sito.applicazioni}">applicazioni smartphone</a>. Se stai per inviare la tua prima segnalazione ti invitiamo a consultare la <a href="{$settings.sito.guida}">Guida del Buon Segnalatore</a>, dove potrai trovare a alcuni piccoli suggerimenti per un corretto uso di <strong>Decoro Urbano</strong>.<br />



{include file="_footer.tpl"}
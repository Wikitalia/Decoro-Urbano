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

{config_load file="testi_email.conf" section="segnalazioneCondivisione"}
{include file="_header.tpl"}

<h1 class="fRed fBig">Ciao,<br /><br /></h1>
<a href="{$settings.sito.vediProfilo}?idu={$id_profilo}">{$nome_utente}</a> vuole condividere con te questa segnalazione su Decoro Urbano, lo strumento web 2.0 per la cittadinanza attiva.
<div class="divider"></div>

<div class="fSSmall">{$via}</div>
<div>{$segnalazione}</div>
<div class="fSSmall">{$categoria}</div>
<div class="fSSmall">{$data}</div>
<img src="{$imgSegnalazione}" alt="abilita la visualizzazione delle immagini per vedere questa segnalazione" />
<img src="{$imgMappa}" alt="abilita la visualizzazione delle immagini per vedere questa segnalazione" />

<div>Combatti il degrado, <a href="{$settings.sito.registrati}">diventa Segnalatore!</a><br />Sei gia registrato? Partecipa alla discussione attraverso i commenti o invia le tue segnalazioni.<br /><br />
La cittadinanza attiva inizia da te!</div>


<div class="divider"></div>
{include file="_footer.tpl"}
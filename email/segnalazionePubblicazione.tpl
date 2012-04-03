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

{config_load file="testi_email.conf" section="segnalazionePubblicazione"}

{include file="_header.tpl"}

<h1 class="fRed fBig">Ciao <span class="fItalic">{$nome_utente}</span>,<br /><br /></h1>
Grazie per aver utilizzato <strong>Decoro Urbano</strong>, la tua segnalazione è pubblicata ed è consultabile a questo indirizzo:<br /><br />
<a href="{$link_segnalazione}">{$link_segnalazione}</a>

<div class="divider"></div>

<div class="fSSmall">{$via}</div>
<div>{$messaggio}</div>
{if $genere == 'degrado' && $id_tipo != 0}<div class="fSSmall">{$categoria}</div>{/if}
<div class="fSSmall">{$data}</div>

{if $foto != '0'}
<img src="{$imgSegnalazione}" alt="Abilita la visualizzazione delle immagini per vedere questa segnalazione" />
{/if}
<img src="{$imgMappa}" alt="Abilita la visualizzazione delle immagini per vedere questa segnalazione" />

<div class="divider"></div>
Utilizza gli strumenti di share per diffondere la tua segnalazione, la cittadinanza attiva inizia da te!<br /><br />

<div style="text-align:center;margin:10px 0;"><img src="{$settings.sito.url}/email/images/cellSplash.jpg" /></div>

<div>Utilizza Decoro Urbano al top! Scopri le <a href="{$settings.sito.applicazioni}">applicazioni smartphone</a> gratuite per inviare segnalazioni direttamente dal cellulare!</div>


<div class="divider"></div>
{include file="_footer.tpl"}
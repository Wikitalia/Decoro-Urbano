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

{config_load file="testi_email.conf" section="segnalazioneModerazioneOK"}

{include file="_header.tpl"}

E' stata approvata una nuova segnalazione inserita da {$nome_utente} sul territorio del comune di {$nome_comune}.<br /><br />
E' possibile consultare la segnalazione al seguente indirizzo:<br />
<a href="{$link_segnalazione}">{$link_segnalazione}</a>

<div class="divider"></div>

<div class="fSSmall">{$via}</div>
<div>{$messaggio}</div>
<div class="fSSmall">{$categoria}</div>
<div class="fSSmall">{$data}</div>
<img src="{$imgSegnalazione}" alt="Abilita la visualizzazione delle immagini per vedere questa segnalazione" />
<img src="{$imgMappa}" alt="Abilita la visualizzazione delle immagini per vedere questa segnalazione" />


<div class="divider"></div>
{include file="_footer.tpl"}

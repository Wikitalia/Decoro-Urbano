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

{config_load file="testi_email.conf" section="segnalatoreTop"}

{include file="_header.tpl"}

<h1 class="fRed fBig">Complimenti <span class="fItalic">{$nome_utente}</span>!<br /><br /></h1>
Sei appena entrato nei Top Segnalatori di <strong>Decoro Urbano</strong>!<br />
Clicca questo link: <a href="{$settings.sito.topSegnalatori}">{$settings.sito.url}topSegnalatori/</a> per vedere la tua posizione in classifica. You DU!

{include file="_footer.tpl"}

{$nome_segnalatore}


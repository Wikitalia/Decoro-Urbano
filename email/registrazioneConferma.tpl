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

{config_load file="testi_email.conf" section="registrazioneConferma"}
{include file="_header.tpl"}

<h1 class="fRed fBig">Ciao <span class="fItalic">{$nome_utente}</span>!<br /><br /></h1>
Utilizza questo link: <a href="{$link_registrazione}">{$link_registrazione}</a> per confermare la tua registrazione.<br /> 
Da questo momento puoi utilizzare il sito e le applicazioni smartphone per combattere il degrado della tua città segnalando rifiuti, vandalismo, buche sul manto stradale, incuria nelle zone verdi, problemi nella segnaletica e affissioni abusive.
<div class="divider"></div>
Se cliccando sul link non succede niente, prova con la seguente procedura:<br />
- Seleziona e copia il link completo.<br />
- Apri una finestra del tuo browser (Internet Explorer, Firefox, Chrome) e incolla il link sulla barra degli indirizzi.<br />
- Clicca Vai o premi il tasto Invio sulla tua tastiera.<br />
Grazie!
</span>


{include file="_footer.tpl"}
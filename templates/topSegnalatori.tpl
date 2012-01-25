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

{include file="includes/header.tpl"}

<div class="rightPageHeader">
	<div class="rightHeadText"><h3 class="fontS18 marginB5 fBrown">{#titolo#}</h3>
		{#intro#}
	</div>
	<div class="rightHeadIcon"><div class="rhiTopSegnalatori"></div></div>
</div>

<div class="testoFumetto">
	<div><h3 class="pageTitle auto left marginB15">{#criterio1#}</h3><span class="right fontS10">{#dataAgg#} {math assign=tsdate equation="x - (24*60*60)" x=$smarty.now}{$tsdate|ConvertitoreData_UNIXTIMESTAMP_IT}</span></div>
	<table id="topSegnLista" cellspacing="0" cellpadding="0">
		<tr>
			<th>#</th>
			<th colspan="2">{#segnalatore#}</th>
			<th>{#citta#}</th>
			<th>{#segnalazioni#}</th>
		</tr>
		{foreach name=segnalatori from=$segnalatori_top item=segnalatore}

		<tr class="{cycle values="lightGrayBG,"}">
			<td>{$smarty.foreach.segnalatori.index+1}</td>
			<td >
				<a href="{$settings.sito.vediProfilo}?idu={$segnalatore.id_utente}" class="paddY10"><img src="/resize.php?w=30&h=30&f={$segnalatore.avatar}" /></a>
			</td>
			<td>
				<a href="{$settings.sito.vediProfilo}?idu={$segnalatore.id_utente}" class="paddY10 tdNone fBold">{$segnalatore.nome} {$segnalatore.cognome}</a>
			</td>
			<td>{$segnalatore.citta}</td>
			<td>{$segnalatore.n_segnalazioni}</td>
		</tr>

		{/foreach}
	</table>
</div>

{include file="includes/footer.tpl"}
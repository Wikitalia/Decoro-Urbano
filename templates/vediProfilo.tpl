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

<script>
	var settings_limit_giorni={$settings['segnalazioni'].limit_giorni};
	var json_segnalazioni='{$segnalazioni}';
	var settings_limit_numero={$settings['segnalazioni'].limit_numero};
</script>

<div id="profiloHeader" class="rightPageHeader">
{include file="includes/dettagliProfiloUtente.tpl"}
</div>
<div>
{if $segnalazioni == 'null'}
	<div id="profiloNoSegnalazioniIntro">
		<div class="fBrown fontS32 textCenter fNunito">{#ops#}</div>
		<div class="textCenter marginT10 marginB10"><img src="/images/bachecavuota.png" alt="" /></div>
		<div class="textCenter fGreen fontS26 fNunito">
		{$user_profile.nome} {$user_profile.cognome} {#noSegn#}<br />
		</div>
	</div>
{else}
	{include file="includes/segnalazioni.tpl"}
{/if}
</div>

{include file="includes/footer.tpl"}

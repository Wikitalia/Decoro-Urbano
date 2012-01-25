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



{if $segnalazione_valida}

	<div id="profiloHeader" class="rightPageHeader">
	{*include file="includes/dettagliProfiloUtente.tpl"*}
	 	
		<div id="segnalazionePath" class="marginT5">
	   {*<img src="{$settings.sito.url}images/DU_freccia_path.png" class="left" />*}
	   <div class="auto">
	    &nbsp;DU / <a href="{$settings.sito.listaSegnalazioni}" class="tdNone">Italia</a> / 
	    <a href="http://{$segnalazione.regione_url}.{$settings.sito.dominio}" class="tdNone">{$segnalazione.regione}</a> / <a href="http://{$segnalazione.citta_url}.{$settings.sito.dominio}" class="tdNone">{$segnalazione.citta}</a> / {$segnalazione.indirizzo} {$segnalazione.civico}
	   </div>
	  </div>
		
	</div>

	{include file="includes/segnalazioni.tpl"}

{elseif $segnalazione_in_moderazione}
	Segnalazione in moderazione
{elseif $segnalazione_rimossa}
	Segnalazione rimossa
{elseif $segnalazione_non_presente}
	{#segnNonPresente#}
{/if}

{include file="includes/footer.tpl"}
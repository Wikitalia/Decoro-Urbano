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

{include file="includes/header.tpl"}
<div class="rightPageHeader">
	<div class="rightHeadText"><h3 class="fontS18 marginB5 fBrown">{#titolo#}</h3>
		{#intro#}
	</div>
	<div class="rightHeadIcon"><div class="rhiComuniAttivi"></div></div>
</div>
<div class="testoFumetto">
<p>
{#paragrafo1#}
</p>

<ul id="listaComuniAttivi">
	{foreach from=$comuni_attivi item=comune}
	<li>
     <a href="http://{$comune.nome_url}.{$settings.sito.dominio}/">{$comune.nome}</a> <span>({$comune.totali})</span>
     <br><span class="rss">Dataset GeoRSS</span> <a href="{$settings.sito.url}ext/georss_dl.php?comune={$comune.nome_url}" target="_blank" class="rss">{$comune.nome_url}.rss</a>
    </li>
	{/foreach}
</ul>

<div class="dataset_box_download">
  <div class="dataset_box_download_testo">
    I dataset sono nel formato <a href="http://it.wikipedia.org/wiki/GeoRSS" target="_blank">GeoRSS</a>
    e sono rilasciati con
    Licenza <a href="http://creativecommons.org/licenses/by/3.0/it/" target="_blank">Creative Commons Attribuzione 3.0 Italia (CC BY 3.0)</a>.
  </div>
</div>


</div>
{include file="includes/footer.tpl"}

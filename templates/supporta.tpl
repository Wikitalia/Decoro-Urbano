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
		{#supportaIntro#}
	</div>
	<div class="rightHeadIcon"><div class="rhiSupporta"></div></div>
</div>

<div class="testoFumetto">
	<div><h3 class="pageTitle marginB15">{#banners#}</h3></div>
	
	<div>
		<div class="marginB10">
			<div class="sostieniBannerFormato">{#formato1#}</div>
			<div class="sostieniBannerCodiceTag">{#codice#} <span class="fontS10 pointer" onclick="selezionaTesto('sostieni125x125');">{#selTutto#}</span></div>
		</div>
		<div class="sostieniBanner">
			<a href="/images/banners/125x125.gif" target="_blank"><img src="/images/banners/125x125.gif" /></a><br />
			<span class="fontS10"><a href="/images/banners/125x125.gif" target="_blank">{#dimReali#}</a></span>
		</div>
		<div class="sostieniCodice" id="sostieni125x125" onclick="selezionaTesto('sostieni125x125');">&lt;a href="{$settings.sito.url}" target="_blank"&gt;&lt;img src="{$settings.sito.url}images/banners/125x125.gif" alt="Decoro Urbano - We DU!" title="Decoro Urbano - We DU!" style="border:0;" /&gt;&lt;/a&gt;</div>
	</div>
	<div class="divider"></div>
	
	<div>
		<div class="marginB10">
			<div class="sostieniBannerFormato">{#formato2#}</div>
			<div class="sostieniBannerCodiceTag">{#codice#} <span class="fontS10 pointer" onclick="selezionaTesto('sostieni250x250');">{#selTutto#}</span></div>
		</div>
		<div class="sostieniBanner">
			<a href="/images/banners/250x250.gif" target="_blank"><img src="/images/banners/250x250.gif" /></a>
			<span class="fontS10"><a href="/images/banners/250x250.gif" target="_blank">{#dimReali#}</a></span>
		</div>
		<div class="sostieniCodice" id="sostieni250x250" disabled="disabled" onclick="selezionaTesto('sostieni250x250');">&lt;a href="{$settings.sito.url}" target="_blank"&gt;&lt;img src="{$settings.sito.url}images/banners/250x250.gif" alt="Decoro Urbano - We DU!" title="Decoro Urbano - We DU!" style="border:0;" /&gt;&lt;/a&gt;</div>
	</div>
	<div class="divider"></div>
	
	<div>
		<div class="marginB10">
			<div class="sostieniBannerFormato">{#formato3#}</div>
			<div class="sostieniBannerCodiceTag">{#codice#} <span class="fontS10 pointer" onclick="selezionaTesto('sostieni160x600');">{#selTutto#}</span></div>
		</div>
		<div class="sostieniBanner">
			<a href="/images/banners/160x600.gif" target="_blank"><img src="/images/banners/160x600.gif" /></a>
			<span class="fontS10"><a href="/images/banners/160x600.gif" target="_blank">{#dimReali#}</a></span>
		</div>
		<div class="sostieniCodice" id="sostieni160x600" disabled="disabled" onclick="selezionaTesto('sostieni160x600');">&lt;a href="{$settings.sito.url}" target="_blank"&gt;&lt;img src="{$settings.sito.url}images/banners/160x600.gif" alt="Decoro Urbano - We DU!" title="Decoro Urbano - We DU!" style="border:0;" /&gt;&lt;/a&gt;</div>
	</div>
	<div class="divider"></div>
	
	<div>
		<div class="marginB10">
			<div class="sostieniBannerFormato">{#formato4#}</div>
			<div class="sostieniBannerCodiceTag">{#codice#} <span class="fontS10 pointer" onclick="selezionaTesto('sostieni728x90');">{#selTutto#}</span></div>
		</div>
		<div class="sostieniBanner">
			<a href="/images/banners/728x90.gif" target="_blank"><img src="/images/banners/728x90.gif" /></a>
			<span class="fontS10"><a href="/images/banners/728x90.gif" target="_blank">{#dimReali#}</a></span>
		</div>
		<div class="sostieniCodice" id="sostieni728x90" disabled="disabled" onclick="selezionaTesto('sostieni728x90');">&lt;a href="{$settings.sito.url}" target="_blank"&gt;&lt;img src="{$settings.sito.url}images/banners/728x90.gif" alt="Decoro Urbano - We DU!" title="Decoro Urbano - We DU!" style="border:0;" /&gt;&lt;/a&gt;</div>
	</div>
	
</div>
{include file="includes/footer.tpl"}
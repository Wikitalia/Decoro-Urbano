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
		{#intro1#} <strong>{#decoro#}</strong> {#intro2#}
	</div>
	<div class="rightHeadIcon"><div class="rhiApplicazioni"></div></div>
</div>

<div class="testoFumetto">
	<div class="marginB15">
		<div class="auto right"><a href="{$settings.apps.iPhone}" target="_blank"><img src="/images/dispositivi/appStoreBadgeSmall.png" alt="{#scarica#}" /></a></div>
		<h3 class="pageTitle marginB5 auto">{#decoroIPhone#}</h3> 
		<div class="auto">{#iPhoneInfos#}</div>
	</div>
	<div>
		<div class="applicazioniLeft">
			<img src="/images/dispositivi/appleLogo.png" class="marginB10" />
			<a href="{$settings.apps.iPhone}" target="_blank"><button class="greenButt">{#scarica#}</button></a>
			<img src="/images/dispositivi/qrCodeApple.png" class="marginT10" />
			<div class="marginT10 textLeft auto fontS10 marginL15">{#iPhoneQRIntro#}</div>
		</div>
		<div class="applicazioniRight"><img src="/images/dispositivi/iPhoneSplash.jpg" alt="" class="right" /></div>
	</div>
	
	<div class="divider"></div>
	
	<div class="marginB15">
		<div class="auto right"><a href="{$settings.apps.android}" target="_blank"><img src="/images/dispositivi/androidMarketBadgeSmall.png" alt="{#scarica#}" /></a></div>
		<h3 class="pageTitle marginB5 auto">{#decoroAndroid#}</h3> 
		<div class="auto">{#androidInfos#}</div>
	</div>
	<div>
		<div class="applicazioniLeft">
			<img src="/images/dispositivi/androidLogo.png" class="marginB10" />
			<a href="{$settings.apps.android}" target="_blank"><button class="greenButt">{#scarica#}</button></a>
			<img src="/images/dispositivi/qrCodeAndroid.png" class="marginT10" />
			<div class="marginT10 textLeft auto fontS10 marginL15">{#androidQRIntro#}</div>
		</div>
		<div class="applicazioniRight"><img src="/images/dispositivi/androidSplash.jpg" alt="" class="right" /></div>
	</div>
</div>

{include file="includes/footer.tpl"}
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
	<div class="rightHeadIcon"><div class="rhiGuida"></div></div>
</div>


<div class="guidaContainer" style="margin-top:0;">
	<img src="/images/guida/pittog1.gif" alt="" />
	<h3 class="pageTitle marginB5 fontS18">{#titoloParag1#}</h3>
	 {#parag1#}
	<div class="divider"></div>
</div>
<div class="guidaContainer">
	<img src="/images/guida/pittog2.gif" alt="" />
	<h3 class="pageTitle marginB5 fontS18">{#titoloParag2#}</h3>
	 {#parag2#}
	<div class="divider"></div>
</div>
<div class="guidaContainer">
	<img src="/images/guida/pittog3.gif" alt="" />
	<h3 class="pageTitle marginB5 fontS18">{#titoloParag3#}</h3>
	  {#parag3#}
	<div class="divider"></div>
</div>
<div class="guidaContainer">
	<img src="/images/guida/pittog4.gif" alt="" />
	<h3 class="pageTitle marginB5 fontS18">{#titoloParag4#}</h3>
	 {#parag4#}
</div>

{include file="includes/footer.tpl"}
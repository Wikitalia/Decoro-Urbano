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
		{#testoIntroduttivo#}
	</div>
	<div class="rightHeadIcon"><div class="rhiContatti"></div></div>
</div>
{if ! $email_inviata}
<div class="testoFumetto" id="contattiForm">
	<form class="skinnedForm" method="post">
		{if ! $user}
		<div class="marginB10"><label for="nome">{#nome#}</label> <input type="text" name="nome" id="nome" /></div>
		<div class="marginB10"><label for="cognome">{#cognome#}</label> <input type="text" name="cognome" id="cognome" /></div>
		<div class="marginB10"><label for="email">{#email#}</label> <input type="text" name="email" id="email" /></div>
		{else}
		<div class="marginB10"><label>{#nome#}</label> <span class="fBold fGreen fontS16">{$user.nome} {$user.cognome}</span> </div>
		<div class="marginB10"><label>{#email#}</label> <span class="fBold fGreen fontS16">{$user.email}</span></div>
		<input type="hidden" name="id_utente" value="{$user.id_utente}" />
		<input type="hidden" name="nome" value="{$user.nome}" />
		<input type="hidden" name="cognome" value="{$user.cognome}" />
		<input type="hidden" name="email" value="{$user.email}" />
		{/if}
		<div class="marginB10">
			<label for="argomento">{#argomento#}</label> 
			<select name="argomento" id="argomento">
				<option></option>
				<option value="adesioniComuni">{#arg0#}</option>
				<option value="ideeProgetto">{#arg1#}</option>
				<option value="ideeSito">{#arg2#}</option>
				<option value="ideeApplicazioni">{#arg3#}</option>
				<option value="patrocini">{#arg4#}</option>
				<option value="sponsorizzazioni">{#arg5#}</option>
				<option value="ufficioStampa">{#arg6#}</option>
				<option value="segnalazioniUtenti">{#arg7#}</option>
				<option value="anomaliSito">{#arg8#}</option>
				<option value="altro">{#arg9#}</option>
			</select>
		</div>
		<div class="marginB10">
			<label for="testoEmail">{#testo#}</label>
			<textarea name="testoEmail" id="testoEmail"></textarea>
			<div class="fontS10 marginT15 textCenter">
				{#disclaimerContattaci#} <a href="{$settings.sito.privacy}" target="_blank">{#privacy#}</a>
			</div>
			
		</div>
		<div class="textRight"><input type="submit" name="form_contatti" /></div>
	</form>
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
{else}
<div class="testoFumetto">
	<div class="textCenter marginT10"><img src="/images/DUBollini/conferma.gif" /></div>
	<div class="fontS24 fGreen fBold textCenter marginB10 marginT20">{#grazie#} {$user.nome} {$user.cognome}!</div>
	<div class="fontS18 textCenter marginB20">{#mexInviato#}</div>
</div>
{/if}
{include file="includes/footer.tpl"}
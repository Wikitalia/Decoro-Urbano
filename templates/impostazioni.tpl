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
		{#impIntro#}
	</div>
	<div class="rightHeadIcon"><div class="rhiImpostazioni"></div></div>
</div>

<div class="testoFumetto">
	<div><h3 class="pageTitle marginB5">{#notifiche#}</h3></div>
	<div class="marginT20 marginB20">{#inviaNotif1#} <span class="fBold">{$user.email}</span> {#inviaNotif2#}:</div>
	<form class="skinnedForm" method="post">
		<ul class="privacyList">
			<li>
				<label for="email_commento">{#opzione1#}</label> 
				<input name="email_commento" id="email_commento" type="checkbox" {if $user.email_commento == 1}checked="checked" {/if}/>
			</li>
			<li>
				<label for="email_segnalazione">{#opzione2#}</label> 
				<input name="email_segnalazione" id="email_segnalazione" type="checkbox" {if $user.email_segnalazione == 1}checked="checked" {/if}/>
			</li>
			<li>
				<label for="email_gestione_comune">Viene presa in carico o risolta una mia segnalazione</label> 
				<input name="email_gestione_comune" id="email_gestione_comune" type="checkbox" {if $user.email_gestione_comune == 1}checked="checked" {/if}/>
			</li>
			<li>
				<label for="email_top">{#opzione3#}</label> 
				<input name="email_top" id="email_top" type="checkbox" {if $user.email_top == 1}checked="checked" {/if}/>
			</li>
			<li>
				<label for="email_comunicazioni">Viene inviata una comunicazione da<br />Decoro Urbano</label> 
				<input name="email_comunicazioni" id="email_comunicazioni" type="checkbox" {if $user.email_comunicazioni == 1}checked="checked" {/if}/>
			</li>
		</ul>
		<input type="submit" name="form_impostazioni_utente" value="{#salvaModifiche#}" class="marginT20 right" />
	</form>
</div>

{if $user.id_ruolo == 2 || $user.id_ruolo == 1}
<div class="testoFumetto">
	<div><h3 class="pageTitle marginB5">{#privacyTitolo#}</h3></div>
	<form class="skinnedForm" method="post">
		<ul class="privacyList">
			<li>
				<label for="mostraCognome">{#pOpzione1#}</label> 
				<input name="mostraCognome" id="mostraCognome" type="checkbox" {if $user.mostra_cognome == 1}checked="checked" {/if}/>
			</li>
			<li>
				<label for="profiloPubblico">{#pOpzione2#}</label> 
				<input name="profiloPubblico" id="profiloPubblico" type="checkbox" {if $user.profilo_pubblico == 1}checked="checked" {/if}/>
			</li>
		</ul>
		<input type="submit" name="form_impostazioni_utente2" value="{#salvaModifiche#}" class="marginT20 right" />
	</form>
</div>
{/if}

{if isset($smarty.session.fb_session)}
<div class="testoFumetto">
	<div><h3 class="pageTitle marginB5">{#socialTitolo#}</h3></div>
	<form class="skinnedForm" method="post">
		<ul class="privacyList">
			<li>
				<label for="fbShare">{#socialOpzione1#}</label> 
				<input name="fbShare" id="fbShare" type="checkbox" {if $user.fb_share == 1}checked="checked" {/if}/>
			</li>
		</ul>
		<input type="submit" name="form_impostazioni_utente3" value="{#salvaModifiche#}" class="marginT20 right" />
	</form>
</div>
{/if}

{include file="includes/footer.tpl"}

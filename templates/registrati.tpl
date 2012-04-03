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

<script type="text/javascript" src="/js/controlli.js"></script>

{*
<script>

function checkEmailPresent(email) {

	$.ajax({
		url: '/ajax/email_esistente_check.php?e='+email,
		success: function(data) {
			alert(data);
			if (data == '0') return true;
			else {
			
				$('#modalControlli').html('Indirizzo email già presente nel sistema.<br /><a href="{$settings.sito.passDimenticata}">Clicca qui</a> se non ricordi la password');
			
				$('#modalControlli').dialog({
					height: 400,
					width:550,
					modal: true,
					draggable:false,
					resizable:false,
					buttons: {
						Ok: function() {
							$( this ).dialog( "close" );
						}
					}
				});
			
			}
		}
	});

}

function localValidateForm() {

	if (ValidateForm_(controlFields, 'submit')) {
		// Controllo esistenza email in db
		return checkEmailPresent($('#regEmail').val());
	} else {
		return false;
	}

}

</script>
*}

{if $email_inviata}

<div class="rightPageHeader">
	<div class="rightHeadText"><h3 class="fontS18 marginB5 fBrown">{#titolo#}</h3>
		{#regConferma#}
	</div>
	<div class="rightHeadIcon"><div class="rhiRegistrati"></div></div>
</div>

<div class="testoFumetto">
	<div><h3 class="pageTitle marginB5">{#benvenuto#} {$nome_segnalatore}!</h3></div>
	{#abilita#}
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

{else if $campi}

<div class="rightPageHeader">
	<div class="rightHeadText"><h3 class="fontS18 marginB5 fBrown">{#erroreTitolo#}</h3>
		<span class="fRed">{#errore#}</span>
	</div> 
	<div class="rightHeadIcon"><div class="rhiRegistrati"></div></div>
</div>

<div class="testoFumetto">
	<div><h3 class="pageTitle marginB5">{#regTitolo#}</h3></div>
	<form class="skinnedForm formRegistrazione" method="post" onsubmit="return ValidateForm_(controlFields, 'submit');">
		<div class="marginT10"><label for="regNome">{#nome#}:</label> <div class="inputContainer"><input name="regNome" id="regNome" type="text" /></div><span id="controllo_regNome"></span></div>
		<div class="marginT10"><label for="regCognome">{#cognome#}:</label> <div class="inputContainer"><input name="regCognome" id="regCognome" type="text" /></div><span id="controllo_regCognome"></span></div>
		<div class="marginT10"><label for="regEmail">{#email#}:</label> <div class="inputContainer"><input name="regEmail" id="regEmail" type="text" /></div><span id="controllo_regEmail"></span></div>
		<div class="marginT10"><label for="regConfermaEmail">{#email2#}:</label> <div class="inputContainer"><input name="regConfermaEmail" id="regConfermaEmail" type="text" /></div><span id="controllo_regConfermaEmail"></span></div>
		<div class="marginT10"><label for="regPassword">{#pass#}: <br /><span class="fontS10" style="margin-right:8px;">{#minPassLenght#}</span></label> <div class="inputContainer"><input name="regPassword" id="regPassword" type="password" /></div><span id="controllo_regPassword"></span></div>
		{*<div class="marginT10"><label for="regConfermaPassword">{#pass2#}:</label> <div class="inputContainer"><input name="regConfermaPassword" id="regConfermaPassword" type="password" /></div><span id="controllo_regConfermaPassword"></span></div>*}
		<div class="fontS10 marginT15 textCenter">
				{#regAccetta1#} <a href="{$settings.sito.tos}" target="_blank">{#condizioni#}</a> {#regAccetta2#} 
				<a href="{$settings.sito.privacy}" target="_blank">{#privacy#}</a>
		</div>
		<div class="marginT15 textRight"><input type="submit" name="form_registrazione" value="{#registrati#}" /></div>
	</form>
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>


	{if $campi.regEmail.errore == 'Utente già registrato con questo indirizzo email'}
	<div class="testoFumetto">
		<div><h3 class="pageTitle marginB5">{#utenteEsistenteTitolo#}</h3></div>
		{#utenteEsistenteTesto#}<br />
		<a href="{$settings.sito.passDimenticata}">{#cliccaQui#}</a> {#utenteEsistenteTesto2#}
	</div>
	{/if}

{else}

<div class="rightPageHeader">
	<div class="rightHeadText"><h3 class="fontS18 marginB5 fBrown">{#newRegTitle#}</h3>
		{#newRegTesto#}
	</div>
	<div class="rightHeadIcon"><div class="rhiRegistrati"></div></div>
</div>

<div class="testoFumetto">
	<div><h3 class="pageTitle marginB5">{#regTitolo#}</h3></div>
	<form class="skinnedForm formRegistrazione" method="post" onsubmit="return ValidateForm_(controlFields, 'submit');">
		<div class="marginT10"><label for="regNome">{#nome#}:</label> <div class="inputContainer"><input name="regNome" id="regNome" type="text" /></div><span id="controllo_regNome"></span></div>
		<div class="marginT10"><label for="regCognome">{#cognome#}:</label> <div class="inputContainer"><input name="regCognome" id="regCognome" type="text" /></div><span id="controllo_regCognome"></span></div>
		<div class="marginT10"><label for="regEmail">{#email#}:</label> <div class="inputContainer"><input name="regEmail" id="regEmail" type="text" /></div><span id="controllo_regEmail"></span></div>
		<div class="marginT10"><label for="regConfermaEmail">{#email2#}:</label> <div class="inputContainer"><input name="regConfermaEmail" id="regConfermaEmail" type="text" /></div><span id="controllo_regConfermaEmail"></span></div>
		<div class="marginT10"><label for="regPassword">{#pass#}: <br /><span class="fontS10" style="margin-right:8px;">{#minPassLenght#}</span></label> <div class="inputContainer"><input name="regPassword" id="regPassword" type="password" /></div><span id="controllo_regPassword"></span></div>
		{*<div class="marginT10"><label for="regConfermaPassword">{#pass2#}:</label> <div class="inputContainer"><input name="regConfermaPassword" id="regConfermaPassword" type="password" /></div><span id="controllo_regConfermaPassword"></span></div>*}
		<div class="fontS10 marginT15 textCenter">
				{#regAccetta1#} <a href="{$settings.sito.tos}" target="_blank">{#condizioni#}</a> {#regAccetta2#} 
				<a href="{$settings.sito.privacy}" target="_blank">{#privacy#}</a>
		</div>
		<div class="marginT15 textRight"><input type="submit" name="form_registrazione" value="{#registrati#}" /></div>
	</form>
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>




<script>

{foreach from=$campi item=c key=k}
	{if $c.errore == ''}
		$('#{$k}').val('{$c.value}');
	{else}
		$('#controllo_{$k}').addClass('checkFailed');
	{/if}
{/foreach}

controlFields=new Array();

controlNew = new Array();
controlNew['nome'] = "regNome";
controlNew['nome_esteso'] = "Nome";
controlNew['lenght'] = 1;
controlNew['type'] = 1;
controlFields.push(controlNew);

controlNew = new Array();
controlNew['nome'] = "regCognome";
controlNew['nome_esteso'] = "Cognome";
controlNew['lenght'] = 1;
controlNew['type'] = 1;
controlFields.push(controlNew);

controlNew = new Array();
controlNew['nome'] = "regEmail";
controlNew['nome_esteso'] = "Indirizzo email";
controlNew['lenght'] = 0;
controlNew['type'] = 2;
controlFields.push(controlNew);

controlNew = new Array();
controlNew['nome'] = "regPassword";
controlNew['nome_esteso'] = "Password";
controlNew['lenght'] = 6;
controlNew['type'] = 1;
controlFields.push(controlNew);

controlNew = new Array();
controlNew['nome'] = "regConfermaEmail";
controlNew['nome_esteso'] = "Conferma indirizzo email";
controlNew['compare'] = 'regEmail';
controlNew['type'] = 10;
controlFields.push(controlNew);


addListeners(controlFields);

</script>

<div class="demo-description" id="modalControlli">
</div>

{/if}

{include file="includes/footer.tpl"}
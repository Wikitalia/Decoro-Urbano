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


<script type="text/javascript" src="/js/controlli.js"></script>

{*
<script>

function checkEmailPresent(email) {

	$.ajax({
		url: '/ajax/email_esistente_check.php?e='+email,
		async: false,
		success: function(data) {
			if (data == '1') return true;
			else {
			
				$('#modalControlli').html('Indirizzo email non presente nel sistema');
			
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
				
				alert('false');
				return false;
			
			}
		}
	});

}

function localValidateForm() {

	if (ValidateForm_(controlFields, 'submit')) {
		// Controllo esistenza email in db
		return checkEmailPresent($('#resetEmail').val());
	} else {
		return false;
	}

}

</script>
*}

<div class="rightPageHeader">
	<div class="rightHeadText"><h3 class="fontS18 marginB5 fBrown">{#titolo#}</h3>
		{#intro#}
	</div>
	<div class="rightHeadIcon"><div class="rhiPassDimenticata"></div></div>
</div>

{if $reset_ok}

<div class="testoFumetto">
	<div><h3 class="pageTitle marginB5">{#resetPass#}</h3></div>
	{#passCambiata#}
	
	<meta HTTP-EQUIV="REFRESH" content="5; url={$settings.sito.url}">
	
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

{else if $errore_generico}

<div class="testoFumetto">
	<div><h3 class="pageTitle marginB5">{#error#}</h3></div>
	{#errorTesto#}
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

{else if $email_inviata}

<div class="testoFumetto">
	<div><h3 class="pageTitle marginB5">{#checkEmailTitle#}</h3></div>
	{#checkEmailTesto#}
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

{else if $code_ok}
<div class="testoFumetto" id="resetPassConfirm">
	<div><h3 class="pageTitle marginB5">{#cambiaPass#}</h3></div>
	<form class="skinnedForm" method="post" action="{$settings.sito.passDimenticata}" onsubmit="return ValidateForm_(controlFields, 'submit');">
		{*<input type="hidden" name="id_utente" value="{$user_id}" />*}
		<input type="hidden" name="s" value="{$code}" />
		<ul class="resetPass">
			<li><label for="resetPass">{#insertNewPass#}</label> <input id="resetPass" name="resetPass" type="password" /></li>
			<span id="controllo_resetPass"></span><br/>
			<li><label for="resetPass2">{#insertNewPass2#}</label> <input id="resetPass2" name="resetPass2" type="password" /></li>
			<span id="controllo_resetPass2"></span>
		</ul>
		<input type="submit" name="form_reset_password2" value="Conferma" class="marginT20 right" />
		
	</form>
</div>

	{if $errore_reset}
	<div class="testoFumetto">
		<div><h3 class="pageTitle marginB5">{#error#}</h3></div>
		{#errorTesto2#}
	</div>
	{/if}

<script>

controlFields=new Array();

controlNew = new Array();
controlNew['nome'] = "resetPass";
controlNew['nome_esteso'] = "Password";
controlNew['lenght'] = 6;
controlNew['type'] = 1;
controlFields.push(controlNew);

controlNew = new Array();
controlNew['nome'] = "resetPass2";
controlNew['nome_esteso'] = "Conferma password";
controlNew['compare'] = 'resetPass';
controlNew['type'] = 10;
controlFields.push(controlNew);

addListeners (controlFields);

</script>

{else if $campi}

	{if $campi.resetEmail.errore == 'Indirizzo Email non presente' || $campi.resetEmail.errore == 'Campo Email necessario'}
	<div class="testoFumetto">
		<div><h3 class="pageTitle marginB5">{#error3#}</h3></div> 
		{#errorTesto3#}<br />
		<a href="{$settings.sito.registrati}">{#cliccaQui#}</a> {#2register#}
	</div>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	{/if}
	
	

{else}
<div class="testoFumetto">
	<div><h3 class="pageTitle marginB5">{#resetPassTitolo#}</h3></div>
	<form class="skinnedForm" method="post" onsubmit="return ValidateForm_(controlFields, 'submit');">
		<ul class="resetPass">
			<li><label for="resetEmail">{#resetPassTesto#}</label> <input id="resetEmail" name="resetEmail" type="text" /></li>
			<span id="controllo_resetEmail"></span>
		</ul>
		<input type="submit" name="form_reset_password1" value="{#procedi#}" class="marginT20 right" />
	</form>
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>


<script>

controlFields=new Array();

controlNew = new Array();
controlNew['nome'] = "resetEmail";
controlNew['nome_esteso'] = "Indirizzo email";
controlNew['lenght'] = 0;
controlNew['type'] = 2;
controlFields.push(controlNew);

addListeners (controlFields);

</script>

{/if}

<div class="demo-description" id="modalControlli">
</div>

{include file="includes/footer.tpl"}

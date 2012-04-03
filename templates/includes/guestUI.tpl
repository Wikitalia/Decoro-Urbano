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

{*
<script>
	// increase the default animation speed to exaggerate the effect
	$.fx.speeds._default = 1000;
	$(function() {
		$( "#popupLogin" ).dialog({
			autoOpen: false,
			position: "center",
			draggable: false,
			modal: true
		});

		$( "#loginOpen" ).click(function() {
			$( "#popupLogin" ).dialog( "open" );
			return false;
		});
	});
	$(function() {
		$( "button, input:submit", "#popupLogin" ).button();
	});
	</script>

<div id="popupLogin" title="Login">
	<p>
		<form method="post">
			<label for="email">{#email#}</label><br />
			<input type="text" name="email" value="{if isset($cookie)}{$cookie.user_email}{/if}" /> <br />
			<label for="password">{#pass#}</label><br />
			<input type="password" name="password" value="{if isset($cookie)}{$cookie.user_password}{/if}" /><br />
			<input type="submit" name="login_form" value="Login!" class="marginT5" />
		</form>
	</p>
</div>
*}

<div class="leftBlockBorder">
		<div>
        <div id="UIThumb">
            <img src="/resize.php?w=90&h=90&f=/images/avatarGuest.png" alt="" />
        </div>
        <div id="UIData">
            <h3>{#utenteOspite#}</h3>
            <ul class="UINavi">
                <li><a href="{$settings.sito.registrati}" {if $page=='registrati'}class="fBold"{/if}>{#registrati#}!</a></li>
                <li><a href="{$settings.sito.passDimenticata}" {if $page=='passDimenticata'}class="fBold"{/if}>{#passDimenticata#}</a></li>
             </ul>
        </div>
    </div>
		<div class="fontS10 marginT10">{#accessLimitato#}</div>
</div>
<div class="leftBlockBorder">
    <ul class="naviLeft">
        <li class="title">{#bigSegnalazioni#}</li>
        <li {if $page=='listaSegnalazioni'}class="selected"{/if}><a href="{$settings.sito.listaSegnalazioni}">{#mostraTutte#}</a></li>
    </ul>
</div>
<div class="leftBlockBorder">
    <ul class="naviLeft">
        <li class="title">{#strumenti#}</li>
				<li {if $page=='applicazioni'}class="selected"{/if}><a href="{$settings.sito.applicazioni}">{#applicazioni#}</a></li>
        <li {if $page=='supporta'}class="selected"{/if}><a href="{$settings.sito.supporta}">Supporta il progetto!</a></li>
    </ul>
</div>
<div class="leftBlockBorder">
    <ul class="naviLeft">
        <li class="title">{#classifiche#}</li>
        <li {if $page=='topSegnalatori'}class="selected"{/if}><a href="{$settings.sito.topSegnalatori}">{#topSegnalatori#}</a></li>
    </ul>
</div>
<div class="leftBlock">
    <ul class="naviLeft">
        <li class="title">Documentazione</li>
        <li {if $page=='suDU'}class="selected"{/if}><a href="{$settings.sito.suDU}">{#suDU#}</a></li>
        <li {if $page=='guida'}class="selected"{/if}><a href="{$settings.sito.guida}">{#guida#}</a></li>
				{*<li {if $page=='FAQs'}class="selected"{/if}><a href="{$settings.sito.FAQs}">{#FAQs#}</a></li>*}{*
				<li {if $page=='awards'}class="selected"{/if}><a href="{$settings.sito.awards}">Gli "iDU" di Decoro Urbano</a></li>*}
				<li><a href="{$settings.sito.url}blog/" target="_blank">Blog</a></li>
        {*<li {if $page=='patrocini'}class="selected"{/if}><a href="{$settings.sito.patrocini}">Patrocini</a></li>
        <li {if $page=='supporta'}class="selected"{/if}><a href="{$settings.sito.supporta}">Supporta il progetto!</a></li>*}
    </ul>
</div>
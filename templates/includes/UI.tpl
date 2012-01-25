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

<div class="leftBlockBorder">
    <div>
			{if isset($user)}
        <div id="UIThumb">
            <a href="{$settings.sito.prehome}"><img src="/resize.php?w=60&h=60&f={$user.avatar}" alt="" /></a>
        </div>
        <div id="UIData">
            <h3><a href="{$settings.sito.prehome}">{$user.nome} {$user.cognome}</a></h3>
            <ul class="UINavi">
                <li><a href="{$settings.sito.modificaProfilo}" {if $page=='modificaProfilo'}class="fBold"{/if}>{#modificaP#}</a></li>
                <li><a href="{$settings.sito.impostazioni}" {if $page=='impostazioni'}class="fBold"{/if}>{#impostazioni#}</a></li>
								<li><a href="{$settings.sito.logout}">{#esci#}</a></li>
             </ul>
        </div>
				{else}
				 <div id="UIThumb">
         	<img src="/resize.php?w=60&h=60&f=/images/avatarGuest.png" alt="" />
         </div>
         <div id="UIData">
         	<h3>{#utenteOspite#}</h3>
          <ul class="UINavi">
          	<li><a href="{$settings.sito.registrati}" {if $page=='registrati'}class="fBold"{/if}>{#registrati#}!</a></li>
            <li><a href="{$settings.sito.passDimenticata}" {if $page=='passDimenticata'}class="fBold"{/if}>{#passDimenticata#}</a></li>
          </ul>
         </div>	
				{/if}
    </div>{*
    <div id="UIAchievement">
            <img src="/images/_temp/arch/arch1.png" alt="" />
            <img src="/images/_temp/arch/arch1.png" alt="" />
            <img src="/images/_temp/arch/arch1.png" alt="" />
            <img src="/images/_temp/arch/arch2.png" alt="" />
            <img src="/images/_temp/arch/arch2.png" alt="" />
            <img src="/images/_temp/arch/arch2.png" alt="" />
            <img src="/images/_temp/arch/arch2.png" alt="" />
            <img src="/images/_temp/arch/arch2.png" alt="" />
            <img src="/images/_temp/arch/arch2.png" alt="" />
            <img src="/images/_temp/arch/arch2.png" alt="" />
            <img src="/images/_temp/arch/arch2.png" alt="" />
            <img src="/images/_temp/arch/arch2.png" alt="" />
            <img src="/images/_temp/arch/arch2.png" alt="" />
            <img src="/images/_temp/arch/arch2.png" alt="" />
    </div>*}
		{if isset($user)}
    <div id="UIControls" class="marginT5">
        <ul class="UINavi">
            <li><span class="fBrown">{$user.n_segnalazioni}</span> {#segnalazioni#}</li>
            <li><span class="fBrown">{$user.n_segnalazioni_quotidiane}</span> {#segnalazioniQ#}</li>
            {*<li><span class="fBrown">4</span> gruppi</li>*}
            <li>{#utenteDal#} {$user.data|ConvertitoreData_UNIXTIMESTAMP_IT}</li>
        </ul>	
    </div>
		{else}
		<div class="fontS10 marginT10">{#accessLimitato#}</div>
		{/if}
</div>
<div class="leftBlockBorder">
	<a href="{$settings.docs.presentazione}"><img src="/images/buttonComuniAttivi.png" alt="" style="position:relative; left:-58px;" /></a>
	{*<a href="{$settings.sito.comuniAttivi}"><img src="/images/buttonElencoComuniAttivi.png" alt="" style="position:relative; left:-16px;" /></a> *}
	oggi <span class="fOrange fBold fontS24">{$abitanti_attivi|number_format:0:",":"."} cittadini</span><br /> 
	possono segnalare il degrado nei<br /> 
	<a href="{$settings.sito.comuniAttivi}"><span class="fOrange fUppercase fBold fUnderline">comuni attivi</span></a>
</div>
<div class="leftBlockBorder">
    <ul class="naviLeft">
        <li class="title">{#bigSegnalazioni#}</li>
				{if isset($user)}
        <li {if $page=='inviaSegnalazione'}class="selected"{/if}><a href="{$settings.sito.inviaSegnalazione}">{#invia#}</a></li>
				{/if}
        <li {if $page=='listaSegnalazioni'}class="selected"{/if}><a href="{$settings.sito.listaSegnalazioni}">{#mostraTutte#}</a></li>
    </ul>
</div>
<div class="leftBlockBorder">
    <ul class="naviLeft">
				<li class="title">{#strumenti#}</li>
				<li {if $page=='applicazioni'}class="selected"{/if}><a href="{$settings.sito.applicazioni}">{#applicazioni#}</a></li>
				<li {if $page=='creaWidget'}class="selected"{/if}><a href="{$settings.sito.creaWidget}">{#creaWidget#}</a></li>
				<li {if $page=='supporta'}class="selected"{/if}><a href="{$settings.sito.supporta}">{#sostieni#}</a></li>
    </ul>
</div>
{*
<div class="leftBlockBorder">
    <ul class="naviLeft">
        <li class="title">Community</li>
        <li {if $page=='amici'}class="selected"{/if}><a href="{$settings.sito.amici}">Amici</a></li>
        <li {if $page=='gruppi'}class="selected"{/if}><a href="{$settings.sito.gruppi}">Gruppi territoriali</a></li>
        <li {if $page=='eventi'}class="selected"{/if}><a href="{$settings.sito.eventi}">Eventi</a></li>
    </ul>
</div>
*}

<div class="leftBlockBorder">
    <ul class="naviLeft">
        <li class="title">{#classifiche#}</li>
        <li {if $page=='topSegnalatori'}class="selected"{/if}><a href="{$settings.sito.topSegnalatori}">{#topSegnalatori#}</a></li>
        {*<li {if $page=='topGruppi'}class="selected"{/if}><a href="{$settings.sito.topGruppi}">Top gruppi</a></li>*}
    </ul>
</div>

{*
<div class="leftBlockBorder">
    <ul class="naviLeft">
        <li class="title">{#comuni#}</li>
				<span class="fontS12">E' gratis per tutti i comuni italiani</span>
				<li><a href="{$settings.docs.presentazione}" target="_blank">{#scaricaPDF#}</a></li>
    </ul>
</div>
*}

<div class="leftBlockBorder">
    <ul class="naviLeft">
        <li class="title">{#docum#}</li>
        <li {if $page=='suDU'}class="selected"{/if}><a href="{$settings.sito.suDU}">{#suDU#}</a></li>
        <li {if $page=='guida'}class="selected"{/if}><a href="{$settings.sito.guida}">{#guida#}</a></li>
				<li {if $page=='FAQs'}class="selected"{/if}><a href="{$settings.sito.FAQs}">{#FAQs#}</a></li>
				<li {if $page=='contatti'}class="selected"{/if}><a href="{$settings.sito.contatti}">{#comuniContattaci#}</a></li>
				{*<li {if $page=='funzioniDU'}class="selected"{/if}><a href="{$settings.sito.funzioniDU}">{#funzDU#}</a></li>
				<li {if $page=='awards'}class="selected"{/if}><a href="{$settings.sito.awards}">Gli "iDU" di Decoro Urbano</a></li>*}
    </ul>
</div>

<div class="leftBlock">
    <ul class="naviLeft">
        <li class="title">{#social#}</li>
				 <li><a href="{$settings.sito.url}blog/" target="_blank">{#duBlog#}</a></li>
        <li><a href="{$settings.social.facebook}" target="_blank"><div class="fbIcon  marginR5"></div> {#socialFB#}</a></li>
        <li><a href="{$settings.social.twitter}" target="_blank"><div class="twIcon  marginR5"></div> {#socialTwitter#}</a></li>
    </ul>
			<iframe src="http://www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Fdecorourbano&amp;width=237&amp;colorscheme=light&amp;show_faces=true&amp;border_color&amp;stream=false&amp;header=false&amp;height=345" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:237px; height:345px; margin-top:5px;" allowTransparency="true"></iframe>
</div>

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

<div class="leftBlockBorder">
    <div>
        <div id="UIThumb">
            <a href="{$settings.sito.prehome}"><img src="/resize.php?w=90&h=90&f={$user.avatar}" alt="" /></a>
        </div>
        <div id="UIData">
            <h3><a href="{$settings.sito.prehome}">{$user.nome} {$user.cognome}</a></h3>
            <ul class="UINavi">
                <li><a href="{$settings.sito.modificaProfilo}" {if $page=='modificaProfilo'}class="fBold"{/if}>{#modificaP#}</a></li>
                <li><a href="{$settings.sito.impostazioni}" {if $page=='impostazioni'}class="fBold"{/if}>{#impostazioni#}</a></li>
             </ul>
        </div>
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
    <div id="UIControls" class="marginT10">
        <ul class="UINavi">
            <li><span class="fBrown">{$user.n_segnalazioni}</span> {#segnalazioni#}</li>
            <li><span class="fBrown">{$user.n_segnalazioni_quotidiane}</span> {#segnalazioniQ#}</li>
            {*<li><span class="fBrown">4</span> gruppi</li>*}
            <li>{#utenteDal#} {$user.data|ConvertitoreData_UNIXTIMESTAMP_IT}</li>
        </ul>	
    </div>
</div>
<div class="leftBlockBorder">
    <ul class="naviLeft">
        <li class="title">{#bigSegnalazioni#}</li>
        <li {if $page=='inviaSegnalazione'}class="selected"{/if}><a href="{$settings.sito.inviaSegnalazione}">{#invia#}</a></li>
        <li {if $page=='listaSegnalazioni'}class="selected"{/if}><a href="{$settings.sito.listaSegnalazioni}">{#mostraTutte#}</a></li>
    </ul>
</div>
<div class="leftBlockBorder">
    <ul class="naviLeft">
        <li class="title">{#strumenti#}</li>
        <li {if $page=='applicazioni'}class="selected"{/if}><a href="{$settings.sito.applicazioni}">{#applicazioni#}</a></li>
				    <li {if $page=='supporta'}class="selected"{/if}><a href="{$settings.sito.supporta}">{#sostieni#}</a></li>
    </ul>
</div>{*
<div class="leftBlockBorder">
    <ul class="naviLeft">
        <li class="title">Community</li>
        <li {if $page=='amici'}class="selected"{/if}><a href="{$settings.sito.amici}">Amici</a></li>
        <li {if $page=='gruppi'}class="selected"{/if}><a href="{$settings.sito.gruppi}">Gruppi territoriali</a></li>
        <li {if $page=='eventi'}class="selected"{/if}><a href="{$settings.sito.eventi}">Eventi</a></li>
    </ul>
</div> *}
<div class="leftBlockBorder">
    <ul class="naviLeft">
        <li class="title">{#classifiche#}</li>
        <li {if $page=='topSegnalatori'}class="selected"{/if}><a href="{$settings.sito.topSegnalatori}">{#topSegnalatori#}</a></li>
        {*<li {if $page=='topGruppi'}class="selected"{/if}><a href="{$settings.sito.topGruppi}">Top gruppi</a></li>*}
    </ul>
</div>
<div class="leftBlock">
    <ul class="naviLeft">
        <li class="title">{#docum#}</li>
        <li {if $page=='suDU'}class="selected"{/if}><a href="{$settings.sito.suDU}">{#suDU#}</a></li>
        <li {if $page=='guida'}class="selected"{/if}><a href="{$settings.sito.guida}">{#guida#}</a></li>
				{*<li {if $page=='FAQs'}class="selected"{/if}><a href="{$settings.sito.FAQs}">{#FAQs#}</a></li>*}
				{*<li {if $page=='funzioniDU'}class="selected"{/if}><a href="{$settings.sito.funzioniDU}">{#funzDU#}</a></li>
				<li {if $page=='awards'}class="selected"{/if}><a href="{$settings.sito.awards}">Gli "iDU" di Decoro Urbano</a></li>*}
				<li><a href="{$settings.sito.url}blog/" target="_blank">{#blog#}</a></li>
        
    </ul>
</div>
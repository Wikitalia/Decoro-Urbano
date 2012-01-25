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

<div>
	{if $user.id_utente == $user_profile.id_utente}
	<div class="optionsIcon right marginL10" onclick="location.href = '{$settings.sito.impostazioni}'"></div>
	<div class="modifyIcon right" onclick="location.href = '{$settings.sito.modificaProfilo}'"></div>
	{else}
	{*<div class="addFriendIcon right" onclick="location.href = '{$settings.sito.aggiungiAmico}'"></div>*}
	{/if}
	<div class="auto right fontS10 marginR10">{#segnDal#} {$user_profile.data|ConvertitoreData_UNIXTIMESTAMP_IT}</div>
	<img src="/resize.php?w=90&h=90&f={$user_profile.avatar}" class="left marginR10" alt="" />
	<h2><a href="{$settings.sito.vediProfilo}?idu={$user_profile.id_utente}" class="tdNone">{$user_profile.nome} {$user_profile.cognome}</a></h2>
	{if $user_profile.about}
		<div id="profiloDesc">
			{$user_profile.about}
		</div>
	{/if}
	<div id="profiloAbout">
		{if $user_profile.citta}
		<ul class="profiloAboutList marginT10">
			<li class="profiloAboutTitle">{#citta#}</li>
			<li>{$user_profile.citta}</li>
		</ul>
		{/if}
		{if $user_profile.quartiere}
		<ul class="profiloAboutList marginT10">
			<li class="profiloAboutTitle">{#quartiere#}</li>
			<li>{$user_profile.quartiere}</li>
		</ul>
		{/if}
		{if $user_profile.sito}
		<ul class="profiloAboutList marginT10">
			<li class="profiloAboutTitle">{#sito#}</li>
			<li><span onclick="window.open('http://{$user_profile.sito}');">{$user_profile.sito}</span></li>
		</ul>
		{/if}
		<ul class="profiloAboutList marginT10">
			<li class="profiloAboutTitle">{#cifre#}</li>
			<li>{$user_profile.n_segnalazioni} {#segnalazioni#}</li>
			<li>{$user_profile.n_segnalazioni_quotidiane} {#segnQuot#}</li>
		</ul>
	</div>
</div>

<div id="profiloTools">
	
</div>
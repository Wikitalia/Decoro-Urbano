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

		<div id="dettaglioCifreComune">
			<div class="left w90">
				<img src="/resize.php?w=90&h=90&f={$user_profile.avatar}" class="left" />
			</div>
			<div class="left w380 paddL10">
				<div class="h55">
					<h2 class="fBrown">Comune di {$user_profile.comune.nome}</h2>
				</div>
				
				<div class="fGreen fBold fontS12 marginB5 left">Segnalazioni a {$user_profile.comune.nome}</div>
				
				<div class="auto marginR10 fontS12"><span class="fBold">{#comuneAttivoSegnTot#}: {$user_profile.comune.totali}</span></div>
				<div class="auto marginR10 fontS12"><span class="fBold">{#comuneAttivoSegnInCarico#}: {$user_profile.comune.in_carico}</span></div>
				<div class="auto marginR10 fontS12"><span class="fBold">{#comuneAttivoSegnRisolte#}: {$user_profile.comune.risolte}</span></div>
			</div>
		</div>
		<div id="dettaglioComuneAttivo">
			<div class="fGreen fontS11 h43">Account verificato<img src="/images/DU_account_verificato.png" align="absmiddle" class="marginL5" /></div>

			<div class="fOrange fUppercase marginR5 fontS16 fBold marginT5 right w243">{#comuneAttivo#}</div>
			<span class="auto fGray marginR5 fontS12 right marginT5">{#comuneAttivoDal#} {$user_profile.comune.data_affiliazione|ConvertitoreData_UNIXTIMESTAMP_IT}</span>
		</div>
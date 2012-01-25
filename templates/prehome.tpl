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


		<div id="bodyPrehome">
			<div id="topCategories" onclick="location.href='{$settings.sito.listaSegnalazioni}'"></div>
			<div id="topIntro" onclick="location.href='{$settings.sito.listaSegnalazioni}'">
				<h1>{#contribuisci#}</h1>
				<h2><strong>{#decoro#}</strong> {#progetto#}</h2>
			</div>	
			<div id="cittadiniAttivi">
				<b>{$abitanti_attivi|number_format:0:",":"."} cittadini</b><i>Fonte ISTAT</i><br />
				possono segnalare il degrado nei <a href="{$settings.sito.comuniAttivi}"><span class="fOrange fUnderline fBold">Comuni Attivi</span></a>. Invita il tuo comune ad aderire gratuitamente! WE DU! 
			</div>
			<div id="midContainer">	
				<div id="leftMobile" onclick="location.href='{$settings.sito.applicazioni}'">
					<div class="marginT40">
						<a href="http://itunes.apple.com/it/app/wedu-decoro-urbano/id441229423?mt=8&ls=1" target="_blank">
							<img src="/images/dispositivi/appStoreBadge.png" class="marginR10" />
						</a>
						<a href="https://market.android.com/details?id=it.maioralabs.decorourbano" target="_blank">
							<img src="/images/dispositivi/androidMarketBadge.png" class="right" />
						</a>
					</div>
					<div class="marginT10">{#appIntro#}</div>
				</div>
				<div id="rightRegister">
					<h3 class="marginB5">{#regIntro#}</h3>
					<img src="{$settings.sito.url}/images/prehome/du.png"  alt="" />
					<div id="rightRegisterIntro">
						<fb:login-button scope="{$settings.facebook.perms}" show-faces="false" max-rows="1" onlogin="check_fb_status();" class="left" autologoutlink="false">
							{#fbAccedi#}
						</fb:login-button>
						<div class="auto marginL10 marginT5">{#consigliato#}</div>
					</div>
					<h3 class="marginT5 marginB5" style="width:325px;">{#regDU#}</h3>
					<form action="{$settings.sito.registrati}" method="post" onsubmit="return ValidateForm_(controlFields, 'submit');">
							<div>
								<label for="regNome">{#nome#}</label> 
								<input name="regNome" id="regNome" type="text" autocomplete="off" />
								<span id="controllo_regNome" class="regPrehomeControllo"></span>
							</div>
							<div>
								<label for="regCognome">{#cognome#}</label> 
								<input name="regCognome" id="regCognome" type="text" autocomplete="off" />
								<span id="controllo_regCognome" class="regPrehomeControllo"></span>
							</div>
							{*<div>
								<label for="regCognomeNascosto">{#cognomeNascosto#}</label> 
								<input name="regCognomeNascosto" id="regCognomeNascosto" type="checkbox" />
							</div>
							<div>
								<label for="regAssociazione">{#utenteAssociazione#}</label> 
								<input name="regAssociazione" id="regAssociazione" type="checkbox" onclick="toggle_associazione();" />
							</div>
							<div>
								<label for="regNomeAssociazione">{#nomeAssociazione#}</label>
								<input name="regNomeAssociazione" id="regNomeAssociazione" type="text" disabled="true" />
							</div>*}
							<div>
								<label for="regEmail">{#email#}</label> 
								<input name="regEmail" id="regEmail" type="text" autocomplete="off" />
								<span id="controllo_regEmail" class="regPrehomeControllo"></span>
							</div>
							<div>
								<label for="regConfermaEmail">{#email2#}</label> 
								<input name="regConfermaEmail" id="regConfermaEmail" type="text" autocomplete="off" />
								<span id="controllo_regConfermaEmail" class="regPrehomeControllo"></span>
							</div>
							<div>
								<label for="regPassword">{#pass#}</label> 
								<input name="regPassword" id="regPassword" type="password" autocomplete="off" />
								<span id="controllo_regPassword" class="regPrehomeControllo"></span>
							</div>
							<div class="fontS10 marginL10" style="width:300px;">
								{#regAccetta1#} <a href="{$settings.sito.tos}" target="_blank">{#condizioni#}</a> {#regAccetta2#} 
								<a href="{$settings.sito.privacy}" target="_blank">{#privacy#}</a>
							</div>
							<div class="skinnedForm">
								<input type="submit" name="form_registrazione" class="right marginR30" value="{#iscriviti#}" />
							</div>
						</form>
				</div>
			</div>
			
			<div id="midBoxes">
				<div id="leftBox">
					<h4>{#comuneTitle#}</h4>
					<div id="comune2Intro"><strong>{#decoro#}</strong> {#comuneAttivoIntro#}</div>
					<div id="comune2Box" onclick="window.open('{$settings.sito.url}docs/Decoro_Urbano.pdf','_blank');"></div>
					<ul>
						<div class="fGray marginB5">{#comuneSubTitle#}</div>
						<li><img src="/images/prehome/verySmallArrow.png" alt="" /> {#vantaggio1#}</li>
						<li><img src="/images/prehome/verySmallArrow.png" alt="" /> {#vantaggio2#}</li>
						<li><img src="/images/prehome/verySmallArrow.png" alt="" /> {#vantaggio3#}</li>
						<li><img src="/images/prehome/verySmallArrow.png" alt="" /> {#vantaggio4#}</li>
					</ul>
					<div id="comune2Contatti">
						<div class="auto left marginT10"><img src="../images/prehome/mailIcon.png" alt="{#numVerde#}" title="{#numVerde#}" /></div>
						<div class="auto left marginT15 marginL10"><a href="{$settings.sito.contatti}"><img src="../images/prehome/email.png" alt="" /></a></div>
						<div class="auto right"><img src="../images/prehome/numVerde.png" alt="{#numVerde#}" title="{#numVerde#}" /></div>
					</div>
				</div>
				<div id="rightBox">
					<h4 class="fGreen">
						{#ultimeSegn#} 
						<div class="auto right fontS10 marginT10"><a href="{$settings.sito.listaSegnalazioni}" class="tdNone fNormal">{#vediTutte#}</a></div>
					</h4>
					<div id="ultimeSegnalazioni">
					
					<script>
					var ultime_segnalazioni = new Array();
					</script>
					
					{foreach name="ultime_segnalazioni" from=$ultime_segnalazioni item=segnalazione}
					
					<div id="segnalazione_{$segnalazione.id_segnalazione}" class="ultimeSegnalazioni {if !$smarty.foreach.ultime_segnalazioni.last}borderBDashed{/if}" onclick="location.href='{$settings.sito.url}{$segnalazione.tipo_nome_url}/{$segnalazione.citta_url}/{$segnalazione.indirizzo_url}/{$segnalazione.id_segnalazione}/'">
						<div class="leftAvatar">
							<a href="{$settings.sito.vediProfilo}?idu={$segnalazione.id_utente}"><img src="/resize.php?w=30&h=30&f={$segnalazione.avatar}" alt="{$segnalazione.nome} {$segnalazione.cognome}" /></a>
						</div>
						<div class="rightContents">
							<img src="{$segnalazione.foto_base_url}85-55.jpg" class="marginL5 right" />
							<a href="{$settings.sito.vediProfilo}?idu={$segnalazione.id_utente}" class="tdNone"><span class="fBold fontS12">
								{$segnalazione.nome} {$segnalazione.cognome}</span>
							</a>
							<span class="fBold fontS12">{$segnalazione.data|ConvertitoreData_UNIXTIMESTAMP_IT}</span><br />
							<span class="fontS14 fGeorgia">{$segnalazione.messaggio|truncate:37:"..."}</span><br />
							<div class="auto fontS10 fGreen" style="margin-top:-1px;"> {$segnalazione.citta} - {$segnalazione.indirizzo} {$segnalazione.civico}</div>
							{if $segnalazione.client == 'iPhone'}<div class="auto fontS10 clear" style="margin-top:-10px;">via <a href="{$settings.sito.applicazioni}">iPhone</a></div>{/if}
							{if $segnalazione.client == 'Android'}<div class="auto fontS10 clear" style="margin-top:-10px;">via <a href="{$settings.sito.applicazioni}">Android</a></div>{/if}
						</div>
					</div>
					
					<script>
						var seg = new Array();
						seg['data'] = {$segnalazione.data};
						seg['id_segnalazione'] = {$segnalazione.id_segnalazione};
						ultime_segnalazioni.push(seg);
					</script>
					
					{/foreach}
					</div>
				</div>
			</div>
			
			<div id="twitterBox" {*onclick="var bob=window.open('','_blank');bob.location='{$settings.social.twitter}';"*} >
				{*<div class="auto marginT10">
					}<span class="fBrown">@maioralabs</span> 
					realizza #decorourbano : scarica l'app gratuita, la cittadinanza attiva inizia da te! <a href="http://bit.ly/we_du">http://bit.ly/we_du</a> seguici! RT pls! #WeDU
				</div>*}
				{*<div class="auto right marginR10 marginT10 fontS10">4 luglio</div>*}
				<div id="twitterFollowBox">
					<a href="{$settings.social.twitter}" target="_blank"><img src="/images/prehome/tweet.png" alt="" /></a> 
				</div>
				<img src="{$ultimo_tweet_avatar}" style="width:30px;" class="marginT25 marginR10 left" />
				<div id="twitterText">
					<div class="marginT25 auto"><a href="http://www.twitter.com/{$ultimo_tweet_from_user}" class="fBrown fBold tdNone" target="_blank">{$ultimo_tweet_from_user}</a> {$ultimo_tweet}{*<span class="fBrown">@maioralabs</span> 
						realizza #decorourbano : scarica l'app gratuita, la cittadinanza attiva inizia da te! <a href="http://bit.ly/we_du">http://bit.ly/we_du</a> seguici! RT pls! #WeDU*}				</div>
				</div>
				<div class="auto right fontS10 fArial">{$ultimo_tweet_time}</div>
			</div>
			
			<div id="bottomBox">
			
				<div id="topSegnBox">
					<h5 onclick="location.href='{$settings.sito.topSegnalatori}'" style="cursor:pointer;"><img src="../images/prehome/topSegnalatori.png" alt="" class="marginR5 left" /> {#topSegn#}</h5>
					<div class="bottomBoxBody">
					
						{foreach name=segnalatori from=$segnalatori_top item=segnalatore}

						<div class="bottomBoxSegnalazione {if !$smarty.foreach.segnalatori.last}borderBDashed marginB10{/if}">
							<a href="{$settings.sito.vediProfilo}?idu={$segnalatore.id_utente}"><img src="/resize.php?w=30&h=30&f={$segnalatore.avatar}" class="left" /></a>
							<div class="bottomBoxSegnInfos">
								<a href="{$settings.sito.vediProfilo}?idu={$segnalatore.id_utente}" class="tdNone">
									<div class="fontS14 fBold">{$segnalatore.nome} {$segnalatore.cognome}</div>
								</a>
								<div class="fontS10">{$segnalatore.n_segnalazioni} Segnalazioni</div>
								<div class="textRight fontS10">{$segnalatore.citta}</div>
							</div>
						</div>

						{/foreach}
					</div>
				</div>
				
				<div id="newSegnBox">
					<h5><img src="../images/prehome/newSegnalatori.png" alt="" class="marginR5 left" /> {#nuoviSegn#}</h5>
					<div class="bottomBoxBody">
						{foreach from=$nuovi_utenti item=utente}
							<a href="{$settings.sito.vediProfilo}?idu={$utente.id_utente}"><img src="/resize.php?w=67&h=67&f={$utente.avatar}" title="{$utente.nome} {$utente.cognome}" class="left" /></a>
						{/foreach}
					</div>
				</div>
				<div id="facebookBox">
					<iframe src="http://www.facebook.com/plugins/fan.php?id=211422328889297&amp;size=large&amp;width=410&amp;height=237&amp;stream=false&amp;header=false&amp;connections=14" scrolling="no" frameborder="0" allowTransparency="true" style="width:410px; height:237px; overflow:hidden;"></iframe>
				</div>
 

					
			
					
				</div>
			</div>
			
		</div>





<script>

//var newest = {$ultima_segnalazione.data};
//newest = 1305122836;

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



function segnalazione_nuova_get() {

	//newest = 0;
	$.ajax({
		url: '/ajax/segnalazioni_get.php?t_newer='+ultime_segnalazioni[0].data,
		dataType: "json",
		success: function(data) {
		
			if (data && data.length) {
			
				data = data.reverse();
		
				for(i in data) {
					nuova_segnalazione_scorri(data[i]);
				}
				
			}

		},
		complete: function(seg) {
			lock = 0;
		}
	});

}

function nuova_segnalazione_scorri(seg) {

	var temp = new Array();
	temp['data'] = seg.data;
	temp['id_segnalazione'] = seg.id_segnalazione;
	ultime_segnalazioni.unshift(temp);

	segnalazioneHTML = '\
		<div id="segnalazione_'+seg.id_segnalazione+'" class="ultimeSegnalazioni borderBDashed" style="display:none;" onclick="location.href=\'{$settings.sito.url}'+seg.tipo_nome_url+'/'+seg.citta_url+'/'+seg.indirizzo_url+'/'+seg.id_segnalazione+'/\'">\
			<div class="leftAvatar"><a href="{$settings.sito.vediProfilo}?idu='+seg.id_utente+'"><img src="/resize.php?w=30&h=30&f='+seg.avatar+'" alt="'+seg.nome+' '+seg.cognome+'" /></a></div>\
			<div class="rightContents">\
				<img src="'+seg.foto_base_url+'85-55.jpg" class="marginL5 right" />\
				<a href="{$settings.sito.vediProfilo}?idu='+seg.id_utente+'"><span class="fBold fontS12 fGreen">'+seg.nome+' '+seg.cognome+'</span></a>\
				<span class="fBold fontS12">'+relativeTime(seg.data)+'</span><br />\
				<span class="fontS14 fGeorgia">'+seg.messaggio+'</span><br />\
				<span class="fontS10 fGreen"><div class="rifiutiSmallIcon auto"></div> '+seg.citta+' - '+seg.indirizzo+' '+seg.civico+'</span>\
			</div>\
		</div>';

	$('#ultimeSegnalazioni').prepend(segnalazioneHTML);
	$('#segnalazione_'+seg.id_segnalazione).slideToggle("slow");
	$('#segnalazione_'+ultime_segnalazioni[ultime_segnalazioni.length-1].id_segnalazione).slideToggle("slow", function() {
		$('#segnalazione_'+ultime_segnalazioni[ultime_segnalazioni.length-1].id_segnalazione).remove();
		ultime_segnalazioni.pop();
	});

	

}


function toggle_associazione() {

  if ($('#regAssociazione').is(':checked')) {
  	$('#regNomeAssociazione').removeAttr('disabled');
  } else {
		$('#regNomeAssociazione').attr('disabled', true);
  }   

}


window.onload=function() {
	$('.segnListaText').ThreeDots({ max_rows:2 });
	addListeners (controlFields);
	interval = setInterval ( "segnalazione_nuova_get()", 30000 );
}

</script>

<div class="demo-description" id="modalControlli">
</div>


{include file="includes/footer.tpl"}

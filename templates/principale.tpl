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

<script type="text/javascript" src="{$settings.sito.url}js/jquery.autoellipsis-1.0.2.min.js"></script>

<div id="debug"></div>

<script>
var settings_limit_giorni={$settings['segnalazioni'].limit_giorni};
var json_ultime_segnalazioni='{$json_ultime_segnalazioni}';
var json_segnalazioni='{$json_segnalazioni}';
var settings_limit_numero={$settings['segnalazioni'].limit_numero};

// Inizializzazioni elenco
var segnalazioni=[];
var segnalazioni_nuove=[];
var segnalazioni_vecchie=[];
var numero_nuove_segnalazioni = 0;
var newest = 0;
var oldest = 0;
var lock = 1;
var sito_url = '{$settings.sito.url}';

function segnalazioni_first_load() {
	
	var segnalazioni_obj = jQuery.secureEvalJSON(json_segnalazioni);

	newest = segnalazioni_obj[0].last_edit;
	oldest = segnalazioni_obj[segnalazioni_obj.length-1].last_edit;
	segsel = segnalazioni_obj[0].id_segnalazione;
	
	if (segnalazioni_obj.length) $('#boxWallSegnalazioni').show();

	for (i in segnalazioni_obj) {
		if (typeof segnalazioni_obj[i] == 'object') {
			segnalazioni[segnalazioni_obj[i].id_segnalazione] = segnalazioni_obj[i];
			aggiungi_segnalazione_lista('append', segnalazioni_obj[i]);
		}
	}

	$('.segnalazione_titolo').ellipsis();

	if (segnalazioni_obj.length == settings_limit_numero) {
		segnalazioniVecchieLoad = '<div id="segnalazioniVecchieLoad" class="marginB10" onclick="segnalazioni_vecchie_get();">Segnalazioni precedenti</div>';
		$('#boxWallSegnalazioni').append(segnalazioniVecchieLoad);
	}
	
	lock = 0;

}


function aggiungi_segnalazione_lista(posizione, segnalazione) {

	var stato = '';

	if (segnalazione['stato'] >= 300)
		stato = 'Risolta';
  else if (segnalazione['stato'] >= 200)
	  stato = 'In carico';
  else
	  stato = 'In attesa';

		segnalazioneListaHTML='\
				<div id="segnalazione_'+segnalazione.id_segnalazione+'" class="ultimeSegnalazioni borderBDashed" onclick="location.href=\''+segnalazione.url+'\'">\
					<div class="leftAvatar">\
						<a href="{$settings.sito.vediProfilo}?idu='+segnalazione.id_utente+'"><img src="/resize.php?w=30&h=30&f='+segnalazione.avatar+'" alt="'+segnalazione.nome+' '+segnalazione.cognome+'" /></a>\
					</div>\
					<div class="rightContents">\
						<img src="'+segnalazione.foto_base_url+'85-55.jpg" class="marginL5 right" />\
						<a href="{$settings.sito.vediProfilo}?idu='+segnalazione.id_utente+'" class="tdNone">\
							<span class="fBold fontS12">'+segnalazione.nome+' '+segnalazione.cognome+'</span>\
						</a>\
						<span class="fBold fontS12">'+relativeTime(segnalazione.data)+'</span><br />\
						<div style="width:230px;"><span class="fontS14 fGeorgia segnalazione_titolo">'+segnalazione.messaggio+'</span></div>\
						<div class="auto fontS10 fGreen" style="margin-top:-1px;"> '+segnalazione.citta+' - '+segnalazione.indirizzo+' '+segnalazione.civico+'</div>';
		if (segnalazione.client == 'iPhone') segnalazioneListaHTML+='<div class="auto fontS10" style="clear:left;">via <a href="{$settings.sito.applicazioni}">iPhone</a></div>';
		if (segnalazione.client == 'Android') segnalazioneListaHTML+='<div class="auto fontS10" style="clear:left;">via <a href="{$settings.sito.applicazioni}">Android</a></div>';
		segnalazioneListaHTML+='\
					</div>\
				</div>\
		';

	if (posizione == 'append') $('#boxWallSegnalazioni').append(segnalazioneListaHTML);
	else if (posizione == 'prepend') $('#boxWallSegnalazioni').prepend(segnalazioneListaHTML);


}

function segnalazioni_nuove_get() {

	if (!lock) {
		lock = 1;

		aggiorna_date();
	

		//newest = 1306078360;

		$.ajax({
			url: '/ajax/segnalazioni_get.php?idu={$user.id_utente}&t_newer='+newest+'&c=1&w=1',
			dataType: "json",
			success: function(seg) {
				if (seg) {
					for (i in seg) {
						segnalazioni_nuove = seg;
					}
					numero_nuove_segnalazioni = seg.length;
					mostra_nuove_contatore(numero_nuove_segnalazioni);
				}
			},
			complete: function(seg) {
				lock = 0;
			}
		});
	}

}

function mostra_nuove_contatore(n) {

	if ($('#segnalazioniNuoveContatore').length == 0) {

		segnalazioniNuoveContatore = '<div id="segnalazioniNuoveContatore" class="" style="display:none;">&nbsp;</div>';

		$('#boxWallSegnalazioni').prepend(segnalazioniNuoveContatore);
		$('#segnalazioniNuoveContatore').toggle("blind",{},1000);
		//$('#segnalazioniNuoveContatore').css('display', 'block').slideToggle("slow");
		//$('#segnalazioniNuoveContatore').slideDown('slow');
		//$('#segnalazioniNuoveContatore').effect("blind",{},1000);				
		
		$('#segnalazioniNuoveContatore').click(
			function () {
				mostra_nuove();
			}
		);
	}
	
	$('#segnalazioniNuoveContatore').html(n+' nuove segnalazioni');

}

function mostra_nuove() {

	aggiorna_date();

	//$('#segnalazioniNuoveDivisore').remove();
	$('#segnalazioniNuoveContatore').remove();
	//segnalazioniNuoveDivisore = '<div id="segnalazioniNuoveDivisore"></div>';
	//$('#segnalazioniLista').prepend(segnalazioniNuoveDivisore);
	
	newest = segnalazioni_nuove[0].last_edit;
	segnalazioni_nuove.reverse();

	for (i in segnalazioni_nuove) {
		if (typeof segnalazioni_nuove[i] == 'object') {
			segnalazioni[segnalazioni_nuove[i].id_segnalazione] = segnalazioni_nuove[i];
			aggiungi_segnalazione_lista('prepend', segnalazioni_nuove[i]);
			//$("#segnalazione_"+segnalazioni_nuove[i].id_segnalazione).addClass('segnListaBoxNew');
		}
	}

	segnalazioni_nuove=[];
	numero_nuove_segnalazioni = 0;

}

function segnalazioni_vecchie_get() {

	if (!lock) {
		lock = 1;

		$('#segnalazioniVecchieLoad').remove();
		aggiorna_date();
	
		//newest = 0;

		$.ajax({

			url: '/ajax/segnalazioni_get.php?idu={$user.id_utente}&t_old='+oldest+'&l='+settings_limit_numero+'&c=1&w=1',

			dataType: "json",
			success: function(seg) {

				if (seg) {
					for (i in seg) {
						segnalazioni_vecchie = seg;
					}
					mostra_vecchie();
					//$('.segnalazione_titolo').ThreeDots({ max_rows:1 });
					$('.segnalazione_titolo').ellipsis();
				}
			},
			complete: function(seg) {
				lock = 0;
			}
		});
	}
}

function mostra_vecchie() {

	aggiorna_date();
	
	//alert(segnalazioni_vecchie.length);
	oldest = segnalazioni_vecchie[segnalazioni_vecchie.length-1].last_edit;
	if (!newest) newest = segnalazioni_vecchie[0].last_edit;

	for (i in segnalazioni_vecchie) {
		if (typeof segnalazioni_vecchie[i] == 'object') {
			segnalazioni[segnalazioni_vecchie[i].id_segnalazione] = segnalazioni_vecchie[i];
			aggiungi_segnalazione_lista('append', segnalazioni_vecchie[i]);
		}
	}

	if (segnalazioni_vecchie.length == settings_limit_numero) {
		segnalazioniVecchieLoad = '<div id="segnalazioniVecchieLoad" class="marginB10" onclick="segnalazioni_vecchie_get();">Segnalazioni precedenti</div>';
		$('#boxWallSegnalazioni').append(segnalazioniVecchieLoad);
	}
	
	segnalazioni_vecchie=[];

}

function aggiorna_date() {

	for (i in segnalazioni) {
		$('#segnalazione_data_'+segnalazioni[i].id_segnalazione).html(relativeTime(segnalazioni[i].data));
		
		for (j in segnalazioni[i].commenti) {
			$('#commento_data_'+segnalazioni[i].id_segnalazione+'_'+segnalazioni[i].commenti[j].id_commento).html(relativeTime(segnalazioni[i].commenti[j].data));
		}
		
	}

}


window.onload=function() {
	segnalazioni_first_load();
	//interval = setInterval ( "segnalazioni_nuove_get()", 300000);
}
	
	
</script>

<div class="rightPageHeader">
		<div class="rightHeadText"><h3 class="fontS18 marginB5 fBrown">{#recenti#}</h3>
			{#benvenuto2#}
		</div>
</div>

<div id="segnalazioniStream" class="marginT15">

			{*<div class="principaleBox">
				<div class="title">Segnalazioni seguite</div>

				<div id="segnalazione_{$segnalazione.id_segnalazione}" class="ultimeSegnalazioni {if !$smarty.foreach.ultime_segnalazioni.last}borderBDashed{/if}" onclick="location.href='{$segnalazione.url}'">
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
						{if $segnalazione.client == 'iPhone'}<div class="auto fontS10 clear" style="clear:left;">via <a href="{$settings.sito.applicazioni}">iPhone</a></div>{/if}
						{if $segnalazione.client == 'Android'}<div class="auto fontS10 clear" style="clear:left;">via <a href="{$settings.sito.applicazioni}">Android</a></div>{/if}
					</div>
				</div>			

			</div>*}
			
			<div class="principaleBox{* marginT20*}">
				<div class="title" id="boxNuoveSegnalazioni">Nuove segnalazioni</div>
				<div id="boxNuoveSegnalazioni">
				{foreach from=$ultime_segnalazioni item=segnalazione name="nuovaSegnalazione"}

				<div id="segnalazione_{$segnalazione.id_segnalazione}" class="ultimeSegnalazioni {if !$smarty.foreach.nuovaSegnalazione.last}borderBDashed{/if}" onclick="location.href='{$segnalazione.url}'">
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
						{if $segnalazione.client == 'iPhone'}<div class="auto fontS10 clear"  style="clear:left;">via <a href="{$settings.sito.applicazioni}">iPhone</a></div>{/if}
						{if $segnalazione.client == 'Android'}<div class="auto fontS10 clear"  style="clear:left;">via <a href="{$settings.sito.applicazioni}">Android</a></div>{/if}
					</div>
				</div>
				
				{/foreach}
				</div>

			</div>
			
			<div class="principaleBox marginT20" id="boxWallSegnalazioni" style="display:none;">
				<div class="title">Wall segnalazioni</div>
				<div id="boxWallSegnalazioni">
				{*{foreach from=$segnalazioni item=segnalazione name="nuovaSegnalazione"}

				<div id="segnalazione_{$segnalazione.id_segnalazione}" class="ultimeSegnalazioni {if !$smarty.foreach.nuovaSegnalazione.last}borderBDashed{/if}" onclick="location.href='{$segnalazione.url}'">
					<div class="leftAvatar">
						<a href="{$settings.sito.vediProfilo}?idu={$segnalazione.id_utente}"><img src="/resize.php?w=30&h=30&f={$segnalazione.avatar}" alt="{$segnalazione.nome} {$segnalazione.cognome}" /></a>
					</div>
					<div class="rightContents">
						<img src="{$segnalazione.foto_base_url}85-55.jpg" class="marginL5 right" />
						<a href="{$settings.sito.vediProfilo}?idu={$segnalazione.id_utente}" class="tdNone"><span class="fBold fontS12">
							{$segnalazione.nome} {$segnalazione.cognome}</span>
						</a>
						<span class="fBold fontS12">{$segnalazione.data|ConvertitoreData_UNIXTIMESTAMP_IT}</span><br />
						<div style="width:230px;"><span class="fontS14 fGeorgia">{$segnalazione.messaggio|truncate:37:"..."}</span></div>
						<div class="auto fontS10 fGreen" style="margin-top:-1px;"> {$segnalazione.citta} - {$segnalazione.indirizzo} {$segnalazione.civico}</div>
						{if $segnalazione.client == 'iPhone'}<div class="auto fontS10 clear" style="clear:left;">via <a href="{$settings.sito.applicazioni}">iPhone</a></div>{/if}
						{if $segnalazione.client == 'Android'}<div class="auto fontS10 clear" style="clear:left;">via <a href="{$settings.sito.applicazioni}">Android</a></div>{/if}
					</div>
				</div>
				
				{/foreach}*}
				</div>

			</div>

</div>

<div id="socialStream" class="marginT20">
	<h5>News da Facebook</h5>
	<iframe src="http://www.facebook.com/plugins/activity.php?site={$settings.sito.dominio}&amp;width=246&amp;height=270&amp;header=false&amp;colorscheme=light&amp;font&amp;border_color&amp;recommendations=false" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:246px; height:270px; margin-bottom:15px;" allowTransparency="true"></iframe>
	{*<fb:live-stream event_app_id="211422328889297" width="400" height="500" xid="" always_post_to_friends="false"></fb:live-stream>*}
	<script src="http://widgets.twimg.com/j/2/widget.js"></script>
	<script>
	new TWTR.Widget({
		version: 2,
	  type: 'search',
	  search: '#WEDU OR @decorourbano OR #decorourbano',
		rpp: 3,
	  interval: 30000,
	  title: '',
	  subject: '@decorourbano su Twitter',
	  width: 246,
	  height: 300,
	  theme: {
	    shell: {
	      background: '#4ab5e6',
	      color: '#ffffff'
	    },
	    tweets: {
	      background: '#ffffff',
	      color: '#632016',
	      links: '#3a891d'
	    }
	  },
		features: {
	    scrollbar: false,
	    loop: true,
	    live: true,
	    hashtags: true,
	    timestamp: true,
	    avatars: true,
	    behavior: 'all'
		}
	}).render().start();
	</script>
		
	<div class="right marginT20">
		<h5><img src="../images/prehome/newSegnalatori.png" alt="" class="marginR5 left"> Nuovi Segnalatori</h5>
		{foreach from=$nuovi_utenti item=utente}
			<a href="{$settings.sito.vediProfilo}?idu={$utente.id_utente}"><img src="/resize.php?w=41&h=41&f={$utente.avatar}" title="{$utente.nome} {$utente.cognome}" class="left" /></a>
		{/foreach}
	</div>
	
	<div class="right marginT20">
		<h5><img src="../images/prehome/topSegnalatori.png" alt="" class="marginR5 left"> Top Segnalatori</h5>
		<div class="bottomBoxBody">
		
			{foreach name=segnalatori from=$segnalatori_top item=segnalatore}

			<div class="bottomBoxSegnalazione {if !$smarty.foreach.segnalatori.last}borderBDashed marginB10{/if} {if $smarty.foreach.segnalatori.first}marginT10{/if}">
				<a href="{$settings.sito.vediProfilo}?idu={$segnalatore.id_utente}"><img src="/resize.php?w=30&h=30&f={$segnalatore.avatar}" class="left marginL5" /></a>
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
		
</div>



<div id="segnalatoriStream"></div>

		
		
</div>


{include file="includes/footer.tpl"}
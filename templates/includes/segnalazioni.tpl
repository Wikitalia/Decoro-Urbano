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

{if $page=="dettaglioSegnalazione"}
<!-- Place this tag in your head or just before your close body tag -->
<script type="text/javascript" src="https://apis.google.com/js/plusone.js">
  { lang: 'it' }
</script>
{/if}

<div id="segnalazioniElenco">
</div>

<script>

// Inizializzazioni elenco
var segnalazioni=[];
var segnalazioni_nuove=[];
var segnalazioni_vecchie=[];
var numero_nuove_segnalazioni = 0;
var newest = 0;
var oldest = 0;
var lock = 1;
var max_commenti = 3;
var modalMap;
var modalMarker;
var modalTimer;
var modalMapToggleDuration = 250;
var sito_url = '{$settings.sito.url}';
{if $user}
var logged_in = true;
{else}
var logged_in = false;
{/if}
function segnalazioni_first_load() {
	
	//json_segnalazioni = jQuery.quoteString(json_segnalazioni);
	var segnalazioni_obj = jQuery.secureEvalJSON(json_segnalazioni);

	newest = segnalazioni_obj[0].last_edit;
	oldest = segnalazioni_obj[segnalazioni_obj.length-1].last_edit;
	segsel = segnalazioni_obj[0].id_segnalazione;
	
	//alert(segnalazioni_obj.length);

	for (i in segnalazioni_obj) {
		//alert(i);
		//if (segnalazioni_obj[i].id_segnalazione == 4564) alert(i);
	//for (i=0;i<segnalazioni_obj.length;i++) {
		segnalazioni[segnalazioni_obj[i].id_segnalazione] = segnalazioni_obj[i];
		aggiungi_segnalazione_lista('append', segnalazioni_obj[i]);
	}

	$('.segnalazione_titolo').ThreeDots({ max_rows:1 });
	
	{if $page == 'principale' || $page == 'vediProfilo'}
	//alert(segnalazioni_obj.length);
	if (segnalazioni_obj.length == settings_limit_numero) {
		segnalazioniVecchieLoad = '<div id="segnalazioniVecchieLoad" class="" onclick="segnalazioni_vecchie_get();">Segnalazioni precedenti</div>';
		$('#segnalazioniElenco').append(segnalazioniVecchieLoad);
	}
	{/if}
	
	lock = 0;

}


function aggiungi_segnalazione_lista(posizione, segnalazione) {

	var stato = '';

	if (segnalazione['stato'] >= 300) {
		stato = 'SEGNALAZIONE RISOLTA';
		coloreStato = 'Green';
		img_stato = 'DU_img_risolta.png';
  } else if (segnalazione['stato'] >= 200) {
	  stato = 'SEGNALAZIONE IN CARICO';
	  coloreStato = 'Red';
	  img_stato = 'DU_img_carico.png';
  } else {
	  stato = 'SEGNALAZIONE IN ATTESA';
	  coloreStato = 'Grey';
	  img_stato = 'DU_img_attesa.png';
	}
	
	//alert(segnalazione.foto);

	segnalazioneListaHTML='\
		<div style="display:none;">'+segnalazione.url+'</div>\
		<div id="segnalazione_'+segnalazione.id_segnalazione+'" class="testoFumetto">\
		<div class="segnUtente">\
			<div class="auto left"><a href="{$settings.sito.vediProfilo}?idu='+segnalazione.id_utente+'"><img src="/resize.php?w=25&h=25&f='+segnalazione.avatar+'" alt="" /></a></div>\
			<div class="auto left fBold"><a class="tdNone" href="{$settings.sito.vediProfilo}?idu='+segnalazione.id_utente+'">'+segnalazione.nome+' '+segnalazione.cognome+'</a>';
		if (segnalazione.id_ruolo == 3) segnalazioneListaHTML+=' <span class="auto marginT5 fontS10 fLightGray">(Account verificato)</span>';
		segnalazioneListaHTML+='</div>\
			<div class="auto right">{if $page=="dettaglioSegnalazione"}<g:plusone size="medium" href="'+segnalazione.url+'"></g:plusone>{/if}</div>\
      <div class="auto right"><fb:like href="'+segnalazione.url+'" show_faces="true" send="false" layout="button_count" width="110" show_faces="false" font=""></fb:like></div>\
			<div class="auto right"><a href="http://twitter.com/share" class="twitter-share-button" data-url="'+segnalazione.url+'" data-text="#decorourbano : #'+segnalazione.tipo_nome+' a #'+segnalazione.citta+' #WeDU" data-count="horizontal">Tweet</a>\<script type="text/javascript" src="http://platform.twitter.com/widgets.js"\>\</script\></div>\
		</div>\{*segnUtente*}
		<div>\{*Senza nome 1*}
			<div class="segnDettagli">\
				<div class="segnDettagliLeft">\
					<div class="marginB5 fontS10">'+segnalazione.indirizzo+' '+segnalazione.civico+', '+segnalazione.cap+' '+segnalazione.citta+'</div>\
					<div class="fontS18 marginB5 fGeorgia"><a class="tdNone" href="'+segnalazione.url+'">'+segnalazione.messaggio+'</a></div>';
					if (segnalazione.genere != 'buone-pratiche')
						segnalazioneListaHTML += '<div class="fontS10 marginB5" style="float:left;width:180px;">{#categoria#}: '+segnalazione.tipo_nome+'</div>\
					<div id="segnalazione_data_'+segnalazione.id_segnalazione+'" class="fontS10 marginB5">'+relativeTime(segnalazione.data); 
						if (segnalazione.client=='iPhone')
							segnalazioneListaHTML += ' via <a href="{$settings.sito.applicazioni}">iPhone</a>';
						if (segnalazione.client=='Android')
							segnalazioneListaHTML += ' via <a href="{$settings.sito.applicazioni}">Android</a>'; 
						segnalazioneListaHTML += '</div>\
				</div>\{*segnDettagliLeft*}
				<div class="segnDettagliRight">\
					<div class="auto right textRight">\
						<div class="marginT5 textRight marginB5">';
						if (segnalazione.id_competenza) {
							segnalazioneListaHTML += '<span>{#gestioneCompetenza#}</span>\
							<img src="{$settings.sito.url}images/loghi_competenze/'+segnalazione.nome_url_competenza+'.png" />';
						}
						segnalazioneListaHTML += '</div>\
					</div>\
				</div>';{*segnDettagliRight*}
		if (segnalazione.genere != 'buone-pratiche') {
				segnalazioneListaHTML+='<div style="width:100%" id="infoSegnalazione">\
					<div id="statoSegnalazione" class="f'+coloreStato+'"><img src="{$settings.sito.url}/images/'+img_stato+'" alt="Stato Segnalazione" class="imgStato">'+stato+' <a href="#"  title="Cosa significa in carico?" id="b_c">{*<img src="{$settings.sito.url}/images/question.png" alt="informazioni" >*}</a>';
			if (segnalazione.id_ente > 0) segnalazioneListaHTML+='<br />Inoltrata a: "'+segnalazione.nome_ente+'"';
					segnalazioneListaHTML+='</div>\
					{*<div id="prioritaSegnalazione">\
					Priorit&agrave;: <b class="highPriority fBrown">Media</b> <img src="{$settings.sito.url}/images/question.png" alt="informazioni"></div>\*}
					<div id="doIT" style="float:right;display:block;margin-right:30px;"><div class="doITsubscrive">{*<img src="{$settings.sito.url}/images/question.png" alt="informazioni"> *}Sottoscrivi:</div>';
			if (!logged_in) {
				segnalazioneListaHTML+='<div id="followButtonDiv_'+segnalazione.id_segnalazione+'" class="doITfollow"><a id="followButton_'+segnalazione.id_segnalazione+'" href="javascript:alert(\'È necessario effettuare il login per sottoscrivere una segnalazione\');"></a></div>';
			} else {
				if (!segnalazione.logged_user_following) {
					segnalazioneListaHTML+='<div id="followButtonDiv_'+segnalazione.id_segnalazione+'" class="doITfollow"><a id="followButton_'+segnalazione.id_segnalazione+'" href="javascript:segnalazioneFollow('+segnalazione.id_segnalazione+');"></a></div>';
				} else {
					segnalazioneListaHTML+='<div id="followButtonDiv_'+segnalazione.id_segnalazione+'" class="doITunfollow"><a id="followButton_'+segnalazione.id_segnalazione+'" href="javascript:segnalazioneUnFollow('+segnalazione.id_segnalazione+');"></a></div>';
				}
			}
			segnalazioneListaHTML+='\
			<span id="nFollower_'+segnalazione.id_segnalazione+'">'+segnalazione.n_follower+'</span></div>';{*doIT*}
		}
		segnalazioneListaHTML+='</div>\{*infoSegnalazione*}
		</div>\{*segnDettagli*}
		</div>\{*Senza nome 1*}
		<div class="segnListaBody">\
		{if $user}
			<div class="closeIcon right" style="position:relative; z-index:10000; right:-5px;" id="closeIcon_'+segnalazione.id_segnalazione+'">\
			</div>\
		{/if}
			<div class="marginB15">';
			if (segnalazione.foto == 1) {
				segnalazioneListaHTML+='<div class="segnImmagini" id="segnImmagini_'+segnalazione.id_segnalazione+'" onclick="showModal('+segnalazione.id_utente+', '+segnalazione.id_segnalazione+',\''+segnalazione.foto_base_url+'\');">\
					<div class="auto marginR10"><img src="'+segnalazione.foto_base_url+'315-238.jpg" /></div>\
				</div>\
				<div style="width:315px;height:238px;float:right; margin-right:12px;"  id="mini_mappa_'+segnalazione.id_segnalazione+'"></div>';
			} else {
				segnalazioneListaHTML+='<div style="width:100%;height:238px;float:right; margin-right:12px;"  id="mini_mappa_'+segnalazione.id_segnalazione+'"></div>';
			}
			segnalazioneListaHTML+='</div>';
			if (segnalazione.commenti && segnalazione.commenti.length > max_commenti) segnalazioneListaHTML+='<div class="commentoBox marginB5">\
			<a id="mostraCommenti" href="javascript:mostra_tutti_commenti('+segnalazione.id_segnalazione+');">{#mostraComm1#} '+segnalazione.commenti.length+' {#mostraComm2#}</a></div>';
			segnalazioneListaHTML+='<div id="segnalazione_commenti_'+segnalazione.id_segnalazione+'"></div>\
			{if $user}\
			<div class="commentoBox">\
				<img src="/resize.php?w=30&h=30&f={$user.avatar}" alt="" />';
		if ('{$user.id_ruolo}' == '3')
			segnalazioneListaHTML+='<div class="commentoFumettoBoxComune">\
			<img src="/images/commentFumettoComune.png" class="commentFumettino" />';
		else
			segnalazioneListaHTML+='<div class="commentoFumettoBox">\
			<img src="/images/commentFumetto.png" class="commentFumettino" />';
		segnalazioneListaHTML+='\
					<form onsubmit="return false;"><textarea id="commento_segnalazione_'+segnalazione.id_segnalazione+'" placeholder="Scrivi commento..."></textarea></form>\
				</div>\
			</div>\
			{else}\
			<div class="commentoBox fontS10">\
				<span class="marginL40">Solo gli utenti registrati possono inviare commenti</span>\
			</div>\
			{/if}\
		</div>\{*segnListaBody*}
		<div style="display:none;" id="dialog-confirm_'+segnalazione.id_segnalazione+'" title="">\
		<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><span id="testoBoxConferma_'+segnalazione.id_segnalazione+'"></span></p>\
		</div>\
	</div>';

	if (posizione == 'append') $('#segnalazioniElenco').append(segnalazioneListaHTML);
	else if (posizione == 'prepend') $('#segnalazioniElenco').prepend(segnalazioneListaHTML);

	if (segnalazione.commenti)
		for (i=0;i<max_commenti && i<segnalazione.commenti.length;i++) {
			aggiungi_commento_lista('prepend', segnalazione.id_segnalazione, segnalazione.commenti[i]);
		}
	
	{if $user}
	if ({$user.id_utente} == segnalazione.id_utente) {
		
		$('#closeIcon_'+segnalazione.id_segnalazione).click(function(){
		
			$('#testoBoxConferma_'+segnalazione.id_segnalazione).html('{#eliminaSegnMex#}');

			$( "#dialog-confirm_"+segnalazione.id_segnalazione ).dialog({
				resizable: false,
				movable: false,
				height:200,
				width:300,
				modal: true,
				title: '{#eliminaSegnTitle#}',
				buttons: {
					"{#procedi#}": function() {
						segnalazioneElimina(segnalazione.id_segnalazione);
						$( this ).dialog( "close" );
					},
					"{#annulla#}": function() {
						$( this ).dialog( "close" );
					}
				}
			});
	
		});
	} else {
		$('#closeIcon_'+segnalazione.id_segnalazione).click(function(){
		
			$('#testoBoxConferma_'+segnalazione.id_segnalazione).html('{#segnalaSegnMex#}');

			$( "#dialog-confirm_"+segnalazione.id_segnalazione ).dialog({
				resizable: false,
				movable: false,
				height:200,
				width:300,
				modal: true,
				title: '{#segnalaSegnTitle#}',
				buttons: {
					"{#procedi#}": function() {
						segnalazioneImpropria(segnalazione.id_segnalazione,{$user.id_utente});
						$( this ).dialog( "close" );
					},
					"{#annulla#}": function() {
						$( this ).dialog( "close" );
					}
				}
			});
	
		});
	}

	$("#commento_segnalazione_"+segnalazione.id_segnalazione).keyup(
		function(event) {
			if (event.keyCode == '13' && !event.shiftKey) {
				aggiungi_commento(segnalazione.id_segnalazione,{$user.id_utente});
			} else if (event.keyCode == '13' && event.ctrlKey) {
				$("#commento_segnalazione_"+segnalazione.id_segnalazione).val($("#commento_segnalazione_"+segnalazione.id_segnalazione).val()+'\n');
			}
		}
	);
	{/if}

	FB.XFBML.parse(document.getElementById('segnalazioniElenco'));

	mappa_init(segnalazione,14);

}
/*
 modificato da fabrizio #31082011
  
 per aggiungere lo stile del comune aggiungere 'Comune' nelle seguenti classi:
 class="commentoFumettoBox" quindi diventa class="commentoFumettoBoxComune"
 
 e nell'img src
 
 img src="/images/commentFumetto.png" diventa img src="/images/commentFumettoComune.png"
*/
function aggiungi_commento_lista(posizione, ids, commento) {

	commentoHTML='\
	<div class="commentoBox" id="commento_'+commento.id_commento+'">\
		<img src="/resize.php?w=30&h=30&f='+commento.avatar+'" />';
	
	if (commento.id_ruolo == 3)
		commentoHTML+='<div class="commentoFumettoBoxComune">\
		<img src="/images/commentFumettoComune.png" class="commentFumettino" />';
	else
		commentoHTML+='<div class="commentoFumettoBox">\
		<img src="/images/commentFumetto.png" class="commentFumettino" />';
	
	commentoHTML+='\
			<a href="{$settings.sito.vediProfilo}?idu='+commento.id_utente+'" class="fBold tdNone">'+commento.nome+' '+commento.cognome+'</a> ';
	if (commento.id_ruolo == 3)
		commentoHTML+='<span class="auto marginT5 fontS10 fLightGray">(Account verificato)</span> ';
	commentoHTML+='<span id="commento_data_'+ids+'_'+commento.id_commento+'" class="auto marginT5 fontS10 fLightGray">('+relativeTime(commento.data)+')</span>\
			{if $user}
				<div class="closeIcon right marginB5" id="closeIconCommento_'+commento.id_commento+'">\
				</div>\
			{/if}
			<div class="marginT5">'+commento.commento+'</div>\
		</div>\
	</div>\
	<div style="display:none;" id="dialog-confirmCommento_'+commento.id_commento+'" title="">\
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><span id="testoBoxConfermaCommento_'+commento.id_commento+'"></span></p>\
	</div>';

	if (posizione == 'append') $('#segnalazione_commenti_'+ids).append(commentoHTML);
	else if (posizione == 'prepend') $('#segnalazione_commenti_'+ids).prepend(commentoHTML);
	
	
	{if $user}
	if ({$user.id_utente} == commento.id_utente) {
		
		$('#closeIconCommento_'+commento.id_commento).click(function(){
		
			$('#testoBoxConfermaCommento_'+commento.id_commento).html('{#eliminaCommMex#}');

			$("#dialog-confirmCommento_"+commento.id_commento).dialog({
				resizable: false,
				movable: false,
				height:200,
				width:300,
				modal: true,
				title: '{#eliminaCommTitle#}',
				buttons: {
					"{#procedi#}": function() {
						commentoElimina(commento.id_commento);
						$( this ).dialog( "close" );
					},
					"{#annulla#}": function() {
						$( this ).dialog( "close" );
					}
				}
			});
	
		});
	} else {
		$('#closeIconCommento_'+commento.id_commento).click(function(){
		
			$('#testoBoxConfermaCommento_'+commento.id_commento).html('{#segnalaCommMex#}');

			$("#dialog-confirmCommento_"+commento.id_commento).dialog({
				resizable: false,
				movable: false,
				height:200,
				width:300,
				modal: true,
				title: '{#segnalaCommTitle#}',
				buttons: {
					"{#procedi#}": function() {
						commentoImproprio(commento.id_commento,{$user.id_utente});
						$( this ).dialog( "close" );
					},
					"{#annulla#}": function() {
						$( this ).dialog( "close" );
					}
				}
			});
	
		});
	}
	{/if}

}

function mappa_init(segnalazione, zoom) {

	//alert(dump(segnalazione));

	var posizione = new google.maps.LatLng(segnalazione.lat, segnalazione.lng);
	var selector = '#mini_mappa_'+segnalazione.id_segnalazione;
	

	var mapOptions = {
		zoom: zoom,
		center: posizione,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		disableDefaultUI: true
	};
	
	var map = new google.maps.Map($(selector)[0], mapOptions);

  var image = new google.maps.MarkerImage(segnalazione.marker,
    new google.maps.Size(40, 40),
    new google.maps.Point(0,0),
    new google.maps.Point(19, 40));
	
	var marker = new google.maps.Marker({
	  position: posizione,
	  map: map,
	  icon: image
	  //animation: google.maps.Animation.DROP
	});

}

function mostra_tutti_commenti(id) {

	$('#mostraCommenti').hide();

	for (i=max_commenti;i<segnalazioni[id].commenti.length;i++) {
		aggiungi_commento_lista('prepend', id, segnalazioni[id].commenti[i]);
	}

}

function segnalazioni_nuove_get() {

	if (!lock) {
		lock = 1;

		aggiorna_date();
	

		//newest = 1306078360;

		$.ajax({
		{if $page == 'principale'}
			url: '/ajax/segnalazioni_get.php?idu={$user.id_utente}&t_newer='+newest+'&c=1&w=1',
		{else if $page == 'vediProfilo'}
			url: '/ajax/segnalazioni_get.php?idu={$user_profile.id_utente}&t_newer='+newest+'&c=1',
		{/if}
		
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

		$('#segnalazioniElenco').prepend(segnalazioniNuoveContatore);
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
		segnalazioni[segnalazioni_nuove[i].id_segnalazione] = segnalazioni_nuove[i];
		aggiungi_segnalazione_lista('prepend', segnalazioni_nuove[i]);
		//$("#segnalazione_"+segnalazioni_nuove[i].id_segnalazione).addClass('segnListaBoxNew');
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
		{if $page == 'principale'}
			url: '/ajax/segnalazioni_get.php?idu={$user.id_utente}&t_old='+oldest+'&l='+settings_limit_numero+'&c=1?w=1',
		{else if $page == 'vediProfilo'}
			url: '/ajax/segnalazioni_get.php?idu={$user_profile.id_utente}&t_old='+oldest+'&l='+settings_limit_numero+'&c=1',
		{/if}
			dataType: "json",
			success: function(seg) {

				if (seg) {
					for (i in seg) {
						segnalazioni_vecchie = seg;
					}
					mostra_vecchie();
					$('.segnalazione_titolo').ThreeDots({ max_rows:1 });
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
		segnalazioni[segnalazioni_vecchie[i].id_segnalazione] = segnalazioni_vecchie[i];
		aggiungi_segnalazione_lista('append', segnalazioni_vecchie[i]);
	}

	if (segnalazioni_vecchie.length == settings_limit_numero) {
		segnalazioniVecchieLoad = '<div id="segnalazioniVecchieLoad" class="" onclick="segnalazioni_vecchie_get();">Segnalazioni precedenti</div>';
		$('#segnalazioniElenco').append(segnalazioniVecchieLoad);
	}
	
	segnalazioni_vecchie=[];

}

function aggiungi_commento(ids,idu) {

	aggiorna_date();

	commento = $('#commento_segnalazione_'+ids).val();
	var tmp = document.createElement("DIV");
	tmp.innerHTML = commento;
	commento = $(tmp).text();

	if (commento != '')
		$.ajax({
			type: "POST",
		  url: '/ajax/segnalazione_commento_add.php',
		  data: 'ids='+ids+'&idu='+idu+'&commento='+commento,
		  success: function(data) {
				if (data != "0") {
				
					data_commento=new Date();
					var comm_new = { "id_utente":"{$user.id_utente}", "id_commento":data, "commento":commento, "data":data_commento/1000, "nome":"{$user.nome}", "cognome":"{$user.cognome}", "avatar":"{$user.avatar}", "id_ruolo":"{$user.id_ruolo}"};
	
					aggiungi_commento_lista('append', ids, comm_new);
					
					$("#commento_segnalazione_"+ids).val('');										
					//segnalazioni[ids].commenti.push(comm_new);
				}
		  }
		});
		
	return false;

}

function aggiorna_date() {

	for (i in segnalazioni) {
		$('#segnalazione_data_'+segnalazioni[i].id_segnalazione).html(relativeTime(segnalazioni[i].data));
		
		for (j in segnalazioni[i].commenti) {
			$('#commento_data_'+segnalazioni[i].id_segnalazione+'_'+segnalazioni[i].commenti[j].id_commento).html(relativeTime(segnalazioni[i].commenti[j].data));
		}
		
	}

}

/*segnalazioneEdit(segnalazione.id_segnalazione,campo) {

	// Controllare che l'utente sia autorizzato ad effettuare l'operazione.
	return;

	$.ajax({
		type: "POST",
	  url: '/ajax/segnalazione_edit.php',
	  data: 'ids='+ids,
	  success: function(data) {
			if (data == "1") {
				$('#segnalazione_'+ids).remove();
			}
	  }
	});

}*/

function segnalazioneElimina(id) {

	$.ajax({
	  url: '/ajax/segnalazione_elimina.php?id='+id,
	  success: function(data) {
			if (data == "1") {
			  $('#segnalazione_'+id).remove();
			}
	  }
	});

}

function segnalazioneImpropria(ids,idu) {

	$.ajax({
	  url: '/ajax/segnalazione_impropria.php?ids='+ids+'&idu='+idu,
	  success: function(data) {
			if (data == "1") {
			  alert('Grazie per il tuo contributo!');
			}
	  }
	});

}

function segnalazioneFollow(ids) {

	$.ajax({
	  url: '/ajax/segnalazione_follow.php?ids='+ids,
	  success: function(data) {
	  	if (data != "-1") {
	  		$('#nFollower_'+ids).html(data);
	  		//$('#followButton_'+ids).html('Un-Follow');
	  		$('#followButton_'+ids).attr("href", "javascript:segnalazioneUnFollow("+ids+")");
			$('#followButtonDiv_'+ids).removeClass('doITfollow').addClass('doITunfollow');
	  	}
	  }
	});

}

function segnalazioneUnFollow(ids) {

	//alert("Non implementato: "+ids);
	//exit;

	$.ajax({
	  url: '/ajax/segnalazione_unfollow.php?ids='+ids,
	  success: function(data) {
	  	if (data != "-1") {
	  		$('#nFollower_'+ids).html(data);
	  		//$('#followButton_'+ids).html('Follow');
	  		$('#followButton_'+ids).attr("href", "javascript:segnalazioneFollow("+ids+")");
			$('#followButtonDiv_'+ids).removeClass('doITunfollow').addClass('doITfollow');
	  	}
	  }
	});

}				

function commentoElimina(id) {

	$.ajax({
	  url: '/ajax/commento_elimina.php?id='+id,
	  success: function(data) {
			if (data == "1") {
			  $('#commento_'+id).remove();
			}
	  }
	});

}

function commentoImproprio(idc,idu) {

	$.ajax({
	  url: '/ajax/commento_improprio.php?idc='+idc+'&idu='+idu,
	  success: function(data) {
			if (data == "1") {
			  alert('Grazie per il tuo contributo!');
			}
	  }
	});

}

function showModal(idu, ids, foto_base_url) {

	$('#modalOuter').css('top','0px');
	$('#modalOuter').css('width',$(window).width()+'px');
	$('#modalOuter').css('height',$(document).height()+'px');
				
	/*var img = document.createElement('img');
	img.src='{$settings.sito.url}images/segnalazioni/'+idu+'/'+ids+'/1.jpeg';
	
	img.onload = function () {
		var imgWidth=img.width;
		var imgHeight=img.height;
		var maxHeight = ($(window).height()-140 > 0)?$(window).height()-140:0;
		if (940/maxHeight > imgWidth/imgHeight) {
			finalWidth=maxHeight*imgWidth/imgHeight;
			$('#modalInner').css('top','70px');
			$('#modalInner').css('left',(($(window).width()-finalWidth)/2)+'px');
			$('#modalInner').css('width',finalWidth+'px');
			$('#modalInner').css('height',maxHeight+'px');
			//$('#modalFotoImg').attr('src', '/resize.php?h='+maxHeight+'&f=/images/segnalazioni/'+idu+'/'+ids+'/1.jpeg');
		} else {
			finalHeight=940*imgHeight/imgWidth;
			$('#modalInner').css('top',70+(maxHeight-finalHeight)/2+'px');
			$('#modalInner').css('left',(($(window).width()-940)/2)+'px');
			$('#modalInner').css('width',940+'px');
			$('#modalInner').css('height',finalHeight+'px');
			//$('#modalFotoImg').attr('src', '/resize.php?w=940&f=/images/segnalazioni/'+idu+'/'+ids+'/1.jpeg');
		}

		$('#modalFoto').append(img);
		
		$('#modalInner').show();
		google.maps.event.trigger(modalMap, "resize");
		modalMap.setCenter(posizione);
	
		modalTimer = setTimeout("hideModalMappa();",3000);

	};*/
	
	var image = $('<img />').attr('src', foto_base_url+'0-0.jpg');
	image.load(function () {
	
		if ($('#modalOuter').is(":visible")) {
		
			var imgWidth=this.width;
			var imgHeight=this.height;
			var maxHeight = ($(window).height()-140 > 0)?$(window).height()-140:0;
			if (940/maxHeight > imgWidth/imgHeight) {
				finalWidth=maxHeight*imgWidth/imgHeight;
				$('#modalInner').css('top','70px');
				$('#modalInner').css('left',(($(window).width()-finalWidth)/2)+'px');
				$('#modalInner').css('width',finalWidth+'px');
				$('#modalInner').css('height',maxHeight+'px');
				$(this).css('width', finalWidth+'px');
				$(this).css('height', maxHeight+'px');
			} else {
				finalHeight=940*imgHeight/imgWidth;
				$('#modalInner').css('top',70+(maxHeight-finalHeight)/2+'px');
				$('#modalInner').css('left',(($(window).width()-940)/2)+'px');
				$('#modalInner').css('width',940+'px');
				$('#modalInner').css('height',finalHeight+'px');
				$(this).css('width', 940+'px');
				$(this).css('height', finalHeight+'px');
			}
	
			$('#modalFoto').append(this);
			
			$('#modalInner').show();
			google.maps.event.trigger(modalMap, "resize");
			modalMap.setCenter(posizione);
		
			modalTimer = setTimeout("hideModalMappa();",3000);
			
		}
		
	});
	

	$('#modalOuter').show();
	

	$('#modalMappaToggle').stop();
	$('#modalMappaMap').stop();
	$('#modalMappa').stop();
	
	if ($('#modalMappaToggle').hasClass('bigLeftArrow')) {
		$('#modalMappaToggle').removeClass('bigLeftArrow');
		$('#modalMappaToggle').addClass('bigRightArrow');
	}
	$('#modalMappaMap').css('width', '330px');
	$('#modalMappa').css('width', '350px');
	$('#modalMappaToggle').css('right', '332px');


  var image = new google.maps.MarkerImage(segnalazioni[ids].marker,
    new google.maps.Size(40, 40),
    new google.maps.Point(0,0),
    new google.maps.Point(19, 40));
	
	
	var posizione = new google.maps.LatLng(segnalazioni[ids].lat, segnalazioni[ids].lng);
	
	if (modalMarker) {
		modalMarker.setPosition(posizione);
		modalMarker.setIcon(image);
	} else {
		modalMarker = new google.maps.Marker({
		  position: posizione,
		  map: modalMap,
		  icon: image
		});
	}
	


}

function hideModal() {

	clearTimeout(modalTimer);

	//$('#modalFotoImg').attr('src', '');
	$('#modalFoto').html('');
	
	//hideModalMappa();

	$('#modalOuter').hide();
	$('#modalInner').hide();

}

function toggleModalMappa() {

	if ($('#modalMappaToggle').hasClass('bigRightArrow')) {
		hideModalMappa();
	} else if ($('#modalMappaToggle').hasClass('bigLeftArrow')) {
		showModalMappa();
	}

}

function hideModalMappa() {

	if ($('#modalMappaToggle').hasClass('bigRightArrow')) {
	
		$('#modalMappaMap').animate({
	    width: '0px'
	  }, modalMapToggleDuration, function() {
	  	$('#modalMappaToggle').removeClass('bigRightArrow');
	  	$('#modalMappaToggle').addClass('bigLeftArrow');
	  });
	  
		$('#modalMappa').animate({
	    width: '20px'
	  }, modalMapToggleDuration, function() {

	  });
	  
		$('#modalMappaToggle').animate({
	    right: '2px'
	  }, modalMapToggleDuration, function() {

	  });
	  
	}

}

function showModalMappa() {

	if ($('#modalMappaToggle').hasClass('bigLeftArrow')) {
	
		$('#modalMappaMap').animate({
	    width: '330px'
	  }, modalMapToggleDuration, function() {
			$('#modalMappaToggle').removeClass('bigLeftArrow');
	  	$('#modalMappaToggle').addClass('bigRightArrow');
	  });
	  
		$('#modalMappa').animate({
	    width: '350px'
	  }, modalMapToggleDuration, function() {

	  });
	  
		$('#modalMappaToggle').animate({
	    right: '332px'
	  }, modalMapToggleDuration, function() {

	  });
	  
	}

}

function initModal() {

	modalOuter = '<div id="modalOuter" onclick="hideModal();"></div>';
	
	modalInner = '<div id="modalInner">\
									<div id="modalFoto">\
										<div class="bigCloseIcon right" style="position:relative;margin-bottom:-18px;" onclick="hideModal();"></div>\
									</div>\
									<div id="modalMappa" onclick="toggleModalMappa();">\
									</div>\
									<div id="modalMappaToggle" class="bigRightArrow" style="position:absolute; right:332px; top:170px; z-index:100000;" onclick="toggleModalMappa();"></div>\
									<div id="modalMappaMap"></div>\
									<div style="background:Red;">prova</div>\
								</div>';

	$(document.body).append(modalOuter);
	$(document.body).append(modalInner);
	
	var roma = new google.maps.LatLng(41.893056, 12.482778);
	var selector = '#modalMappaMap';

	var options = {
		zoom: 14,
		center: roma,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		streetViewControl: false
	};
	
	modalMap = new google.maps.Map($(selector)[0], options);
	
	google.maps.event.addListener(modalMap, 'click', function() {	
		clearTimeout(modalTimer);
  });
  
	google.maps.event.addListener(modalMap, 'dragstart', function() {
		clearTimeout(modalTimer);
  });
  
	google.maps.event.addListener(modalMap, 'rightclick', function() {
		clearTimeout(modalTimer);
  });	
  
	google.maps.event.addListener(modalMap, 'tilt_changed', function() {
		clearTimeout(modalTimer);
  });
  
	google.maps.event.addListener(modalMap, 'zoom_changed', function() {
		clearTimeout(modalTimer);
  });									

}

window.onload=function() {

	segnalazioni_first_load();
	
	{if $page == 'principale' || $page == 'vediProfilo'}
	//interval = setInterval ( "segnalazioni_nuove_get()", 1800000);
	//interval = setInterval ( "segnalazioni_nuove_get()", 300000);
	{/if}
	
	initModal();
	
}


</script>


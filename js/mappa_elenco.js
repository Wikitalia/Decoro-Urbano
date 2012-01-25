/*
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
 */


// Inizializzazioni comuni
var limit_giorni=settings_limit_giorni;

// Inizializzazioni elenco
var segnalazioni=[];
var segnalazioni_nuove=[];
var numero_nuove_segnalazioni = 0;
var newest = 0;
var lock = 0;


// Inizializzazioni mappa
//var initialLocation;
var roma = new google.maps.LatLng(41.893056, 12.482778);
var initialLocation = roma;
var id_comune = 0;
var regione = '';
var zoom = 11;
var browserSupportFlag =  new Boolean();
var du_map;
var markerClusterer = null;
var lock = 0;
var clusterEvidenziatoStartTop = 0;
var clusterEvidenziato = null;

var du_map = {
	map: null,
	center: null,
	markers: []
}

function set_filtriLista_cookie() {

	filtriLista = {
		subd: subdomain,
		lat: du_map.map.getCenter().lat(),
		lng: du_map.map.getCenter().lng(),
		zoom: du_map.map.getZoom(),
		tipi: {
			1: ($('#radio1').is(":checked"))?1:0,
			2: ($('#radio2').is(":checked"))?1:0,
			3: ($('#radio3').is(":checked"))?1:0,
			4: ($('#radio4').is(":checked"))?1:0,
			5: ($('#radio5').is(":checked"))?1:0,
			6: ($('#radio6').is(":checked"))?1:0
		},
		stato: $('#listaSegnFiltersStato').val(),
		recenti: ($('#filtro_recenti').is(":checked"))?1:0,
		personali: ($('#filtro_personali').is(":checked"))?1:0
	};

	jsonFiltriLista = $.toJSON(filtriLista);

	$.cookie("jsonFiltriLista", jsonFiltriLista, { domain: '.'+domain, path: '/' } );

}

du_map.init = function(selector, initialLocation, zoom) {

	var mapOptions = {
		zoom: zoom,
		center: initialLocation,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		streetViewControl: false
	};
	
	this.map = new google.maps.Map($(selector)[0], mapOptions);
	
  google.maps.event.addListener(this.map, 'click', function() {
		ib.close();
  });
  
  google.maps.event.addListener(this.map, 'idle', function() {
  	set_filtriLista_cookie()
  });

}

function segnalazioni_filtra() {

	ib.close();

	var tipo1 = ($('#radio1').is(":checked"))?1:0;
	var tipo2 = ($('#radio2').is(":checked"))?1:0;
	var tipo3 = ($('#radio3').is(":checked"))?1:0;
	var tipo4 = ($('#radio4').is(":checked"))?1:0;
	var tipo5 = ($('#radio5').is(":checked"))?1:0;
	var tipo6 = ($('#radio6').is(":checked"))?1:0;

	var personali=($('#filtro_personali').is(":checked"))?1:0;
	var recenti=($('#filtro_recenti').is(":checked"))?1:0;

	var stato = $('#listaSegnFiltersStato').val();
	
	//alert(stato);

	subdomain = filtriLista.subd;
	set_filtriLista_cookie();
	
	du_map.markers.splice(0,du_map.markers.length);
	
	if (markerClusterer) {
		markerClusterer.clearMarkers();
	}

  if (segnalazioni) {

		var today=new Date();
		var oldest_date = today/1000 - limit_giorni*24*60*60;

		for (i in segnalazioni) {
		
			//alert(segnalazioni[i]['stato']+' '+stato);

			if (
				((tipo1 && segnalazioni[i]['id_tipo'] == 1) ||
				(tipo2 && segnalazioni[i]['id_tipo'] == 2) ||
				(tipo3 && segnalazioni[i]['id_tipo'] == 3) ||
				(tipo4 && segnalazioni[i]['id_tipo'] == 4) ||
				(tipo5 && segnalazioni[i]['id_tipo'] == 5) ||
				(tipo6 && segnalazioni[i]['id_tipo'] == 6)) &&
				!(personali && segnalazioni[i]['id_utente'] != idu) &&
				!(recenti && segnalazioni[i]['last_edit'] < oldest_date) &&
				!(stato == '100' && !(segnalazioni[i]['stato'] >= stato && segnalazioni[i]['stato'] < (stato+99))) &&
				!(stato == '200' && !(segnalazioni[i]['stato'] >= stato && segnalazioni[i]['stato'] < (stato+99))) &&
				!(stato == '300' && !(segnalazioni[i]['stato'] >= stato && segnalazioni[i]['stato'] < (stato+99)))
			)
				aggiungi_segnalazione('append', segnalazioni[i]);

    }
		newest = segnalazioni[0].data;
	} else {
		$('#noSegnalazioni').show();
		newest = 0;
	}

}

function segnalazioni_first_load() {
	
	//json_segnalazioni = jQuery.quoteString(json_segnalazioni);
	segnalazioni = jQuery.secureEvalJSON(json_segnalazioni);

	if (!filtriLista.subd || filtriLista.subd != subdomain) {
	
		set_filtriLista_cookie();
	
	} else if (filtriLista.subd == subdomain) {
	
		var location = new google.maps.LatLng(filtriLista.lat, filtriLista.lng);
		du_map.map.setCenter(location);
		du_map.map.setZoom(filtriLista.zoom);
		
		for (j=1;j<=6;j++) {
		
			if (filtriLista.tipi[j]) $("#radio"+j).attr("checked", true); // make checkbox or radio checked
			else $("#radio"+j).removeAttr("checked"); // uncheck the checkbox or radio
		
		}
		
		if (filtriLista.recenti) $("#filtro_recenti").attr("checked", true); // make checkbox or radio checked
		else $("#filtro_recenti").removeAttr("checked"); // uncheck the checkbox or radio
		
		if (filtriLista.personali) $("#filtro_personali").attr("checked", true); // make checkbox or radio checked
		else $("#filtro_personali").removeAttr("checked"); // uncheck the checkbox or radio
		
		$('#listaSegnFiltersStato').val(filtriLista.stato);
	
	}
	
	segnalazioni_filtra();

}

function aggiorna_date() {

	for (i in segnalazioni) {
		$('#segnalazione_data_'+segnalazioni[i].id_segnalazione).html(relativeTime(segnalazioni[i].data));
	}

}

function segnalazioni_nuove_get() {

	if (!lock) {
		lock = 1;

		aggiorna_date();

		dataString = '';
	
		var tipo1 = ($('#radio1').is(":checked"))?1:0;
		var tipo2 = ($('#radio2').is(":checked"))?1:0;
		var tipo3 = ($('#radio3').is(":checked"))?1:0;
		var tipo4 = ($('#radio4').is(":checked"))?1:0;
		var tipo5 = ($('#radio5').is(":checked"))?1:0;
		var tipo6 = ($('#radio6').is(":checked"))?1:0;
		dataString += '&tipo1='+tipo1+'&tipo2='+tipo2+'&tipo3='+tipo3+'&tipo4='+tipo4+'&tipo5='+tipo5+'&tipo6='+tipo6;
	
		if ($('#filtro_personali').is(":checked")) dataString += '&idu='+idu;
		if ($('#filtro_recenti').is(":checked")) dataString += '&t_new='+limit_giorni;
		
		var stato = $('#listaSegnFiltersStato').val();
		if (stato != 0) dataString += '&stato='+stato;
		
		//newest = 1305210304;
		dataString += '&t_newer='+newest;
		
		if (regione != '') dataString += '&reg='+slug(regione);
		if (id_comune != 0) dataString += '&idc='+id_comune;

		$.ajax({
			url: '/ajax/segnalazioni_get.php',
			data: dataString,
			dataType: "json",
			success: function(seg) {
				if (seg) {
					segnalazioni_nuove = seg;
					numero_nuove_segnalazioni = seg.length;
					mostra_nuove_contatore(numero_nuove_segnalazioni);
					
					//prependo un div con il numero di nuove segnalazioni
					//onclick sostituisco questo div con un nuovo div (divisore), elimino il vecchio divisore e aggiungo le nuove segnalazioni sopra.
					$('.segnalazione_titolo').ThreeDots({ max_rows:1 });
				}
			},
			complete: function(seg) {
				lock = 0;
			}
		});
	} else {
		setTimeout ( "segnalazioni_nuove_get()", 1000);
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
	//$('#segnalazioniElenco').prepend(segnalazioniNuoveDivisore);
	
	newest = segnalazioni_nuove[0].data;
	segnalazioni_nuove.reverse();

	for (i in segnalazioni_nuove) {
		segnalazioni[segnalazioni_nuove[i].id_segnalazione] = segnalazioni_nuove[i];
		aggiungi_segnalazione('prepend', segnalazioni_nuove[i],0);
		$("#segnalazione_"+segnalazioni_nuove[i].id_segnalazione).addClass('segnListaBoxNew');
	}

	segnalazioni_nuove=[];
	numero_nuove_segnalazioni = 0;

}

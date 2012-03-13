<?php

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

require_once('../include/config.php');

if (isset($_GET['d']) && $_GET['d']) $settings['sito']['debug'] = 1;

if ($settings['sito']['debug']) {
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
} else {
	ini_set('display_errors', 0);
	error_reporting(0);
}

require_once('../include/db_open.php');
require_once('../include/db_open_funzioni.php');
require_once('../include/funzioni.php');
require_once('../include/decorourbano.php');
require_once('../include/controlli.php');

$infobox=(isset($_GET['infobox']))?$_GET['infobox']:'false'; // Default = false
$cluster=(isset($_GET['cluster']))?$_GET['cluster']:'true'; // Default = true
$limit_numero=(isset($_GET['l']))?(int) $_GET['l']:0;
$nome_url_comune=(isset($_GET['comune']))?cleanField($_GET['comune']):'';

$q = 'SELECT * FROM tab_comuni WHERE nome_url = "'.$nome_url_comune.'"';
$comune = data_query($q);

if (count($comune)) $parametri['id_comune'] = $comune[0]['id_comune'];
else {
	$comune[0]['lat'] = 42;
	$comune[0]['lng'] = 12;
}
$parametri['limit'] = $limit_numero;
$parametri['formato'] = 0;

$segnalazioni = segnalazioni_get($parametri);
$segnalazioni_json = json_encode($segnalazioni);
$segnalazioni_json = escapeJSON($segnalazioni_json);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//IT" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<link rel="stylesheet" type="text/css" href="<?=$settings['sito']['url']?>css/global.css" />
<link rel="stylesheet" type="text/css" href="<?=$settings['sito']['url']?>css/globalClass.css" />

<script type="text/javascript" src="<?=$settings['sito']['url']?>js/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="<?=$settings['sito']['url']?>js/jquery.json-2.2.min.js"></script>
<script type="text/javascript" src="<?=$settings['sito']['url']?>js/jquery.autoellipsis-1.0.2.min.js"></script>
<script type="text/javascript" src="<?=$settings['sito']['url']?>js/markerclusterer.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?v=3.2&sensor=false&language=it"></script>
<script type="text/javascript" src="<?=$settings['sito']['url']?>js/infobox.js"></script>
<script type="text/javascript" src="<?=$settings['sito']['url']?>js/funzioni.js"></script>

<script type="text/javascript">

var json_segnalazioni='<?=$segnalazioni_json?>';
var segnalazioni=[];
var initialLocation = new google.maps.LatLng(<?=$comune[0]['lat']?>, <?=$comune[0]['lng']?>);
var zoom = 11;

var du_map;
var markerClusterer = null;

var du_map = {
	map: null,
	center: null,
	markers: []
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
		<?
		if ($infobox == 'true') {
		?>
		ib.close();
		<?
		}
		?>
  });
  
  google.maps.event.addListener(this.map, 'zoom_changed', function() {
		<?
		if ($infobox == 'true') {
		?>
		ib.close();
		<?
		}
		?>
  });

}

var boxText = document.createElement("div");
boxText.style.cssText = "width:290px; float:right; margin:0; padding: 5px;";

<?
if ($infobox == 'true') {
?>
var infoBoxOptions = {
	content: boxText,
	disableAutoPan: false,
	maxWidth: 0,
	pixelOffset: new google.maps.Size(28, -92),
	zIndex: null,
	closeBoxMargin: "5px 0 0 282px",
	closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif",
	infoBoxClearance: new google.maps.Size(1, 1),
	isHidden: false,
	pane: "floatPane",
	enableEventPropagation: false
};

var ib = new InfoBox(infoBoxOptions);
<?
}
?>

function segnalazioni_mostra() {

	<?
	if ($infobox == 'true') {
	?>
	ib.close();
	<?
	}
	?>
	
	du_map.markers.splice(0,du_map.markers.length);
	
	if (markerClusterer) {
		markerClusterer.clearMarkers();
	}

  if (segnalazioni) {

		for (i in segnalazioni) {

			aggiungi_segnalazione('append', segnalazioni[i]);

    }

	}

}

function aggiungi_segnalazione(posizione,segnalazione) {

	var myLatlng = new google.maps.LatLng(segnalazione['lat'],segnalazione['lng']);
      
  var stato = '';
  
  if (segnalazione['stato'] >= 300) {
  	stato = 'Risolta';
  } else if (segnalazione['stato'] >= 200) {
  	stato = 'In carico';
  } else {
  	stato = 'In attesa';
  }

  var image = new google.maps.MarkerImage(segnalazione.marker,
    new google.maps.Size(40, 40),
    new google.maps.Point(0,0),
    new google.maps.Point(19, 40));

<?
if ($cluster == 'true') {
?>

	var marker = new google.maps.Marker({
	    position: myLatlng,
	    icon: image
	});
	
	if (!markerClusterer) {
		markerClusterer = new MarkerClusterer(du_map.map, du_map.markers);
		markerClusterer.setGridSize(35);
		markerClusterer.setMaxZoom(15);
		markerClusterer.setMinClusterSize(2);

		var styles=markerClusterer.getStyles();
		styles[0]['url'] = '<?=$settings['sito']['url']?>images/ico_group_10.png';
		styles[1]['url'] = '<?=$settings['sito']['url']?>images/ico_group_25.png';
		styles[2]['url'] = '<?=$settings['sito']['url']?>images/ico_group_50.png';
		styles[3]['url'] = '<?=$settings['sito']['url']?>images/ico_group_100.png';
		markerClusterer.setStyles(styles);
	}
	markerClusterer.addMarker(marker);
	//var c = markerClusterer.getCluster(marker);
	
<?
} else {
?>

	var marker = new google.maps.Marker({
			map: du_map.map,
	    position: myLatlng,
	    icon: image
	});

<?
}
?>

      
	google.maps.event.addListener(marker, 'click', function() {

<?
if ($infobox == 'true') {
?>

		ib.close();

		infoBoxHTML = '\
			<img src="/images/popupFreccia.png" alt="" style="position:relative; left:-26px;  top:25px; margin-right:-26px; float:left;" />\
			<div id="infoBoxContent" class="ultimeSegnalazioni">\
					<div class="leftAvatar">\
						<div style="width:35px; float:left;">\
							<a href="<?=$settings['sito']['vediProfilo']?>?idu='+segnalazione.id_utente+'" target="_blank"><img src="/resize.php?w=30&h=30&f='+segnalazione.avatar+'" alt="'+segnalazione.nome+' '+segnalazione.cognome+'" /></a>\
						</div>\
					</div>\
					<div class="rightContents">\
						<div style="width:220px; float:left;">\
							<a href="<?=$settings['sito']['vediProfilo']?>?idu='+segnalazione.id_utente+'" class="tdNone" target="_blank"><span class="fBold fontS12">\
								'+segnalazione.nome+' '+segnalazione.cognome+'</span>\
							</a><br />\
							<span class="fontS10">'+relativeTime(segnalazione.data)+'</span>\
						</div>\
						<div onclick="window.open(\'<?=$settings['sito']['url']?>'+segnalazione.tipo_nome_url+'/'+segnalazione.citta_url+'/'+segnalazione.indirizzo_url+'/'+segnalazione.id_segnalazione+'/\',\'_blank\');">\
						<img src="'+segnalazione.foto_base_url+'85-55.jpg" class="marginL5 right" />\
						<div class="auto fontS12 fGeorgia ellipsis_text marginT5" style="width:150px;height:30px;overflow:hidden;clear:left;">'+segnalazione.messaggio+'</div>\
						<div class="auto fontS10 fGreen marginT5"> '+segnalazione.citta+' - '+segnalazione.indirizzo+' '+segnalazione.civico+'</div></div>';
		if (segnalazione['client'] == 'iPhone') infoBoxHTML += '<div class="auto fontS10">via <a href="<?=$settings['sito']['applicazioni']?>" target="_blank">iPhone</a></div>';
		if (segnalazione['client'] == 'Android') infoBoxHTML += '<div class="auto fontS10">via <a href="<?=$settings['sito']['applicazioni']?>" target="_blank">Android</a></div>';
		infoBoxHTML += '</div></div>';
		
		boxText.innerHTML = infoBoxHTML;
		ib.open(du_map.map, marker);
		
<?
} else {
?>
		
		window.open('<?=$settings['sito']['url']?>'+segnalazione.tipo_nome_url+'/'+segnalazione.citta_url+'/'+segnalazione.indirizzo_url+'/'+segnalazione.id_segnalazione+'/','_blank');

<?
}
?>

	});
	


}

window.onload=function() {

	du_map.init('#map_canvas_list',initialLocation,zoom);

	segnalazioni = jQuery.secureEvalJSON(json_segnalazioni);
	segnalazioni_mostra();		

}
		
</script>

</head>
	
<body>


	<div id="listaSegnMappa" style="position:relative;height:100%;width:100%;">
		<div id="map_canvas_list" style="height:100%;width:100%;">
		</div>
	</div>

</body>
</html>

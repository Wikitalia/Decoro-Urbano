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

/*
 * Pagina per la gestione delle segnalazioni da parte di un comune.
 * Permette la presa in carico e la risoluzione
 */
ini_set("session.cookie_domain", ".".$settings['sito']['dominio']);
session_start();

require_once('../include/config.php');

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
require_once('../include/controlli.php');
require_once('../include/decorourbano.php');

require_once('auth.php');

$id_menu_principale = "20";
$id_menu_secondario = "23";


if (isset($_GET["id"])) {
	$id_segnalazione = (int) $_GET["id"];
} else {
	$id_segnalazione = 0;
}

$comune = data_get('tab_comuni',array('id_comune'=>$user['id_comune']));

$result = segnalazione_dettaglio_get($id_segnalazione);

$messaggio = $result[0]["messaggio"];
$id_utente = $result[0]["id_utente"];
$utente = $result[0]["nome"].' '.$result[0]["cognome"];
$tipo_label = $result[0]["tipo_label"];
$tipo = $result[0]["tipo_nome"];
$regione = $result[0]["regione"];
$provincia = $result[0]["provincia"];
$citta = $result[0]["citta"];
$cap = $result[0]["cap"];
$indirizzo = $result[0]["indirizzo"];
$civico = $result[0]["civico"];
$lat = $result[0]["lat"];
$lng = $result[0]["lng"];
$foto_base_url = $result[0]["foto_base_url"];

if ($result[0]["stato"] < $settings['segnalazioni']['in_carico']) {
    $stato = 'In attesa';
} elseif ($result[0]["stato"] >= $settings['segnalazioni']['in_carico'] && $result[0]["stato"] < $settings['segnalazioni']['risolta']) {
    $stato = 'In carico';
} elseif ($result[0]["stato"] >= $settings['segnalazioni']['risolta']) {
    $stato = 'Risolta';
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<meta name="robots" content="noindex,nofollow" />
			<title><?= $settings['admin_comuni']['nome_sito'] ?></title>          
			 
<? require_once($settings['admin_comuni']['percorso'].'head_tag.php') ?>			 

<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>

  

<style type="text/css">
.ordine_menu ul { list-style-type: none; margin: 0; padding: 0; margin-bottom: 10px; }
.ordine_menu li { border:1px solid #cccccc; height:43px;margin: 5px; padding: 5px; width: 220px; background-color:#F0F0F0;line-height:19px;cursor:move;}
.box_selezione{float:left;width:20px;height:30px;margin-top:2px;margin-right:4px;}
.box_testi{float:left;width:180px;height:40px;padding-top:2px;}
</style>


<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.4.custom.min.js"></script>   	
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>

<script src="js/jquery-ui-timepicker-addon.js" type="text/javascript"></script>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/ui.all.css" rel="stylesheet" type="text/css" />

<STYLE TYPE="text/css">
<!--
/* css for timepicker */
.ui-timepicker-div .ui-widget-header{ margin-bottom: 8px; }
.ui-timepicker-div dl{ text-align: left; }
.ui-timepicker-div dl dt{ height: 25px; }
.ui-timepicker-div dl dd{ margin: -25px 0 10px 65px; }
.ui-timepicker-div td { font-size: 90%; }
-->
</STYLE>

<script type="text/javascript">

//var initialLocation;
var du_map = {
  bounds: null,
  map: null,
  popup: null
  //clearMarkers: null
}

du_map.init = function(selector, initialLocation, zoom) {

	var myOptions = {
	  zoom: zoom,
	  center: initialLocation,
	  mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	
	this.map = new google.maps.Map($(selector)[0], myOptions);
	
	du_map.map.setCenter(initialLocation);
	
  var image = new google.maps.MarkerImage('/images/marker_<?=$tipo_label?>.png',
      new google.maps.Size(40, 40),
      new google.maps.Point(0,0),
      new google.maps.Point(21, 37));
	
	var marker = new google.maps.Marker({
	    position: initialLocation,
	    map: du_map.map,
			icon: image
	    /*title: "TITOLO TEST"*/
	});

}

function risolvi_segnalazione(id) {

	$.ajax({
	  url: 'ajax/segnalazione_risolvi.php?id='+id,
	  success: function(data) {
			if (data == "1") {
				window.location.reload();
			}
	  }
	});

}

function incarico_segnalazione(id) {

	$.ajax({
	  url: 'ajax/segnalazione_incarico.php?id='+id,
	  success: function(data) {
			if (data == "1") {
				window.location.reload();
			}
	  }
	});

}



</script>

</head>
<body>

<!--==================== Inizio Header =======================-->
<div id="header_bg">

	<div class="wrapper">
        
<? require_once($settings['admin_comuni']['percorso'].'header_bar.php') ?>	
<? require_once($settings['admin_comuni']['percorso'].'header_area.php') ?>
<? require_once($settings['admin_comuni']['percorso'].'header_menu.php') ?>      
          
  </div>

</div>
<!--==================== Fine Header =======================-->



<!--============================ Template Content Background ============================-->
<div id="content_bg" class="clearfix">
<!--============================ Main Content Area ============================-->
<div class="content wrapper clearfix">

<!--=======================Forms and Further Sub-Navigations==========================-->
	<div class="box clear">

		<div class="header">
		  <img src="images/icona_pagina_grande.png" style="vertical-align:text-bottom;" />Gestione segnalazione
			
		</div>
		<div class="body_vertical_nav clearfix">
			<!-- Grey backgound applied to box body -->
			<!-- Vertical nav -->
			<ul class="vertical_nav">

				
				<li>
				
				
					

						<button onclick="incarico_segnalazione(<?=$id_segnalazione?>)" style="width:130px;">In carico</button><br/>
						<button onclick="risolvi_segnalazione(<?=$id_segnalazione?>)" style="width:130px;">Risolta</button><br/>
						
						<br /><br />
					
						<b>Stato:</b> <?=$stato?><br />
				
				
				</li>
				
				
			</ul>
			
		
			
			<div class="main_column">
				<!-- Content area that wil show the form and stuff -->
				<div class="panes_vertical">
				
					<!-- All divs inside this div will become panes for navigation above -->
					

					<div style="width:100%">
						<!-- First Pane -->
						<!--=========Forms=========-->
						<img src="<?=$foto_base_url?>0-0.jpg" style="width:58%;position:relative;float:left;" />
						

						
						<div style="width:40%;position:relative;float:right;">
							<div id="map_container" style="width:100%;height:300px;position:relative;float:left;">
								<div id="map_canvas" style="width:100%;height:100%">
								</div>
							</div>
							
							<script>
	
							var posizione_segnalazione = new google.maps.LatLng(<?=$lat?>, <?=$lng?>);
							du_map.init('#map_canvas',posizione_segnalazione,15);
							
							</script>
               <span style="font-size:12px;line-height:12px;">
               <br/>
              <b>Utente:</b> <?=$utente?><br />
              <b>Categoria:</b> <?=$tipo?><br />
							<b>Messaggio:</b> <?=$messaggio?><br />
							<b>Regione:</b> <?=$regione?><br />
							<b>Provincia:</b> <?=$provincia?><br />
							<b>Citta:</b> <?=$citta?><br />
							<b>CAP:</b> <?=$cap?><br />
							<b>Indirizzo:</b> <?=$indirizzo?><br />
							<b>Civico:</b> <?=$civico?><br />
							<b>Latitudine:</b> <?=$lat?><br />
							<b>Longitudine:</b> <?=$lng?>



							</span>
						</div>
						
					</div>

					<!-- fine primo -->
					
				</div>
			</div>
		</div>
	</div>
	<!--End Forms and Sub-Nav's Box-->

</div>
<!--End main content area-->
</div>
<!--End Template Content bacground-->

<? require_once($settings['admin_comuni']['percorso'].'footer.php') ?>

</body>
<!--End Body-->
</html>

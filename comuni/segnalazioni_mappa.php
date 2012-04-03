<?php
/*
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
 */

/*
 * Mappa delle segnalazioni
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
$id_menu_secondario = "22";

if (isset($_GET["p"])) {
    $p = $_GET["p"];
} else {
    $p = 1;
}

$comune = data_get('tab_comuni', array('id_comune' => $user['id_comune']));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="robots" content="noindex,nofollow" />
        <title><?= $settings['admin_comuni']['nome_sito'] ?></title>          

        <? require_once($settings['admin_comuni']['percorso'] . 'head_tag.php') ?>			 

        <script type="text/javascript">

            //var initialLocation;
            var comune = new google.maps.LatLng(<?= $comune[0]['lat'] ?>, <?= $comune[0]['lng'] ?>);
            var initialLocation = comune;
            var browserSupportFlag =  new Boolean();
            var du_map;

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

                // Try W3C Geolocation (Preferred)
                /*if(navigator.geolocation) {
            browserSupportFlag = true;
            navigator.geolocation.getCurrentPosition(function(position) {
              initialLocation = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);      
                    du_map.map.setCenter(initialLocation);
            }, function() {
              handleNoGeolocation(browserSupportFlag);
            });
          // Try Google Gears Geolocation
          } else if (google.gears) {
            browserSupportFlag = true;
            var geo = google.gears.factory.create('beta.geolocation');
            geo.getCurrentPosition(function(position) {
              initialLocation = new google.maps.LatLng(position.latitude,position.longitude);
              du_map.map.setCenter(initialLocation);
            }, function() {
              handleNoGeoLocation(browserSupportFlag);
            });
          // Browser doesn't support Geolocation
          } else {
            browserSupportFlag = false;
            handleNoGeolocation(browserSupportFlag);
          }

          function handleNoGeolocation(errorFlag) {

          }*/

                google.maps.event.addListener(this.map, 'idle', function() {
                    du_map.bounds = du_map.map.getBounds();
                    du_map.map.clearMarkers();
                    du_map.carica_segnalazioni();
                    //du_map.popup.draw();
                });

                google.maps.event.addListener(this.map, 'bounds_changed', function() {
                    //du_map.popup.draw();
                    du_map.popup.hide();
                });

                du_map.popup = new popup;

            }

            var segnalazioni = null;


            var stato_incarico=1;
            var stato_risolto=1;


            du_map.carica_segnalazioni = function () {

                var southWest = du_map.bounds.getSouthWest();
                var northEast = du_map.bounds.getNorthEast();
                var lngSpan = northEast.lng() - southWest.lng();
                var latSpan = northEast.lat() - southWest.lat();

                $.ajax({
                    url: '/<?= $settings['sito']['directory'] ?>ajax/segnalazioni_get.php?idc=<?=$user['id_comune']?>&id_competenza=0&genere=degrado',
                    dataType: "json",
                    data: ({
                        minLat : Math.min(southWest.lat(),northEast.lat()),
                        maxLat : Math.max(southWest.lat(),northEast.lat()),
                        minLng : Math.min(southWest.lng(),northEast.lng()),
                        maxLng : Math.max(southWest.lng(),northEast.lng())
                    }),
                    success: function(segnalazioni) {	  
                        for (i in segnalazioni) {
                            if (segnalazioni[i].stato < 200) aggiungi_segnalazione(segnalazioni[i]);
                            else if (segnalazioni[i].stato >= 200 && segnalazioni[i].stato < 300 && stato_incarico) aggiungi_segnalazione(segnalazioni[i]);
                            else if (segnalazioni[i].stato >= 300 && stato_risolto) aggiungi_segnalazione(segnalazioni[i]);
                        }
                    }
                });
	
                function aggiungi_segnalazione(segnalazione) {
	
                    //alert(dump(segnalazione));
	
                    var myLatlng = new google.maps.LatLng(segnalazione['lat'],segnalazione['lng']);


                    // Marker sizes are expressed as a Size of X,Y
                    // where the origin of the image (0,0) is located
                    // in the top left of the image.
	
                    // Origins, anchor positions and coordinates of the marker
                    // increase in the X direction to the right and in
                    // the Y direction down.

									  var image = new google.maps.MarkerImage(segnalazione['marker'],
									    new google.maps.Size(40, 40),
									    new google.maps.Point(0,0),
									    new google.maps.Point(19, 40));

                    var marker = new google.maps.Marker({
                        position: myLatlng,
                        map: du_map.map,
                        //shadow: shadow,
                        icon: image,
                        //shape: shape,
                        title: "Segnalazione"
                    });
		


                    var map_popup_content = "\
                                <div style='width:41px;top:-40px;left:105px;height:40px;position:absolute;'></div>\
                                <div class='map_popup' style='width:242px;height:70px;left:0px;top:0px;padding:5px;'>\
                                		<div class='map_popup_img' style='width:50px;float:left;margin-right:5px; overflow:hidden;'><img src='"+segnalazione['foto_base_url']+"0-60.jpg' style='height:60px;' /></div>\
                                        <div class='map_popup_text' style='width:175px;height:60px;float:right;margin-right:5px;'>\
                                                <div class='map_popup_ellipsis' style='position:relative; top:0; font-size:12px; line-height:13px;'><span class='ellipsis_text'>"+segnalazione.messaggio+"</span></div>\
                                                <div style='font-size:12px; line-height:12px; position:relative; top:5px;'><a href='segnalazione_gestione.php?id="+segnalazione.id_segnalazione+"'>Visualizza dettagli</a></div>\
                                        </div>\
                                </div>";

                    google.maps.event.addListener(marker, 'mouseover', function() {

                        du_map.popup.updatePopupContent(map_popup_content);
                        du_map.popup.updatePopupPosition(myLatlng);
                        du_map.popup.visible_ = true;
                        du_map.popup.draw();

                        $('.map_popup_ellipsis').ThreeDots({ max_rows:3 });

                        //$('.info_box_ellipsis').ThreeDots({ max_rows:8 });
                    });
		
                    du_map.map.addMarker(marker);

                }			

            }




            function segnalazioni_stato_incarico_filtra() {

                stato_incarico=(stato_incarico)?0:1;
	
                //if (tipi[tipo]) document.getElementById('tipo_'+tipo).src="/images/filters/tipo_"+tipo+"_on.png";
                //else document.getElementById('tipo_'+tipo).src="/images/filters/tipo_"+tipo+".png";

                du_map.map.clearMarkers();
                du_map.carica_segnalazioni();

            }

            function segnalazioni_stato_risolto_filtra() {

                stato_risolto=(stato_risolto)?0:1;
	
                //if (tipi[tipo]) document.getElementById('tipo_'+tipo).src="/images/filters/tipo_"+tipo+"_on.png";
                //else document.getElementById('tipo_'+tipo).src="/images/filters/tipo_"+tipo+".png";

                du_map.map.clearMarkers();
                du_map.carica_segnalazioni();

            }

        </script>




    </head>
    <body>

        <!--==================== Inizio Header =======================-->
        <div id="header_bg">

            <div class="wrapper">

                <? require_once($settings['admin_comuni']['percorso'] . 'header_bar.php') ?>	
                <? require_once($settings['admin_comuni']['percorso'] . 'header_area.php') ?>
                <? require_once($settings['admin_comuni']['percorso'] . 'header_menu.php') ?>      

            </div>

        </div>
        <!--==================== Fine Header =======================-->



        <!--============================ Template Content Background ============================-->
        <div id="content_bg" class="clearfix">
            <!--============================ Main Content Area ============================-->
            <div class="content wrapper clearfix">
                <!--============================Sidebar============================-->
                <div class="sidebar">
                    <!-- inizio barra laterale -->

                    <!--=========History Box=========-->


                    <div class="small_box">
                        <div class="header">
                            Filtri stato
                        </div>
                        <div class="body">
                            <ul class="bulleted_list">

                                <li><input type="checkbox" value="" checked="true" onClick="segnalazioni_stato_incarico_filtra();">Segnalazioni in carico</li>
                                <li><input type="checkbox" value="" checked="true" onClick="segnalazioni_stato_risolto_filtra();">Segnalazioni risolte</li>

                            </ul>
                        </div>
                    </div>
                    <!--End History Box-->		  


                    <!-- fine barra laterale -->
                </div>
                <!--End sidebar-->
                <!--============================Main Column============================-->
                <div class="main_column">
                    <!-- inizio contenuto principale -->






                    <!--=========Tables Box=========-->
                    <div class="box">
                        <div class="header" >

                            <img src="images/icona_pagina_grande.png" style="vertical-align:text-bottom;" />Mappa segnalazioni
                        </div>
                        <div class="body">
                            <div class="panes">
                                <!-- Any div under the class of "panes" will associate itself in the same order as the tabs defined above under "sub_nav"-->
                                <!-- Pane 1 -->
                                <div class="clearfix">

                                    <div id="map_container" style="width:100%;height:400px;position:relative;">
                                        <div id="map_canvas" style="width:100%;height:100%">
                                        </div>
                                    </div>

                                    <!--div id="colonna_dx" style="width:300px;height:450px;float:left;">
                                            <div id="annotazione">
                                            </div>
                                            <div id="admin_segnalazione">
                                            </div>
                                    </div-->

                                    <script>
                                        du_map.init('#map_canvas',comune,11);
                                        /*for (j=1;j<=6;j++){
                                            if (!tipi[j]) document.getElementById('tipo_'+j).src="images/filters/tipo_"+j+".png";
                                    }*/
                                    </script>

                                </div>


                            </div>
                        </div>
                    </div>
                    <!--End Tables Box-->


                    <!-- fine contenuto principale -->
                </div>
                <!--End Main Column-->
            </div>
            <!--End main content area-->
        </div>
        <!--End Template Content bacground-->




        <? require_once($settings['admin_comuni']['percorso'] . 'footer.php') ?>



    </body>
    <!--End Body-->
</html>

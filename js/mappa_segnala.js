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
 

var roma = new google.maps.LatLng(41.893056, 12.482778);
var initialLocation = roma;
var map;
var marker = null;
var geocoder;

var lat;
var lng;

var civico;
var via;
var cap;
var citta;
var provincia;
var regione;
var nazione;
var codice_nazione;														


function init_mappa(selector, initialLocation, zoom) {

  geocoder = new google.maps.Geocoder();

	var myOptions = {
	  zoom: zoom,
	  center: initialLocation,
	  mapTypeId: google.maps.MapTypeId.ROADMAP,
	  streetViewControl: false
	};
	
	map = new google.maps.Map($(selector)[0], myOptions);
	
	/*
  // Try W3C Geolocation (Preferred)
  if(navigator.geolocation) {
    browserSupportFlag = true;
    navigator.geolocation.getCurrentPosition(function(position) {
			//aggiorna_posizione_da_coordinate(position.coords.latitude,position.coords.longitude);
			var location = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
			map.setCenter(location);
			//placeMarker(location);
    }, function() {
      handleNoGeolocation(browserSupportFlag);
    });

  // Try Google Gears Geolocation
  } else if (google.gears) {
    browserSupportFlag = true;
    var geo = google.gears.factory.create('beta.geolocation');
    geo.getCurrentPosition(function(position) {
			//aggiorna_posizione_da_coordinate(position.latitude,position.longitude);										
			var location = new google.maps.LatLng(position.latitude, position.longitude);
			map.setCenter(location);
			//placeMarker(location);
    }, function() {
      handleNoGeoLocation(browserSupportFlag);
    });
  // Browser doesn't support Geolocation
  } else {
    browserSupportFlag = false;
    handleNoGeolocation(browserSupportFlag);
  }
  
  function handleNoGeolocation(errorFlag) {
  	//aggiorna_posizione_da_coordinate(roma.lat(),roma.lng());	
		//placeMarker(roma);
  }
  */
  
  google.maps.event.addListener(map, 'idle', function(event) {
		//alert(map.getCenter().lat());
  });
  
  google.maps.event.addListener(map, 'click', function(event) {
		placeMarker(event.latLng);
		aggiorna_posizione_da_coordinate(event.latLng.lat(),event.latLng.lng());
  });
  
  
}

function placeMarker(location) {

	if(marker) marker.setMap(null);
	
  var image = new google.maps.MarkerImage('/images/marker_DU.png',
      new google.maps.Size(40, 40),
      new google.maps.Point(0,0),
      new google.maps.Point(21, 37));

	marker = new google.maps.Marker({
	    position: location,
	    map: map,
	    icon: image,
	    animation: google.maps.Animation.DROP,
	    draggable: true
	});
  
	google.maps.event.addListener(marker, 'dragend', function(event){
		aggiorna_posizione_da_coordinate(event.latLng.lat(),event.latLng.lng());
	}); 

//map.setCenter(location);
}

function mostra_mappa() {
	$('#dati_posizione').toggle(
		'fast',
		function(){
			$('#mappa_posizione').toggle(
				'fast',
				function(){
		      map_center = new google.maps.LatLng(lat,lng);      
					google.maps.event.trigger(map, 'resize');
					
			  	if(marker) marker.setMap(null);
				  marker = new google.maps.Marker({
				      position: map_center, 
				      map: map
				  });
					
					map.setCenter(map_center);
				}
			);
		}
	);
}

function mostra_indirizzo() {
	$('#mappa_posizione').toggle(
		'fast',
		function(){
			$('#dati_posizione').toggle('fast');
		}
	);
}

var timer = null;

function aggiorna_posizione_da_stringa() {

	if(marker) marker.setMap(null);
	lat = 0;
	lng = 0;

	if (timer) clearTimeout(timer);
	timer = setTimeout('geocode ();',1000);

}

function geocode() {

  var address = $("#indirizzo").val();
  var res = [];

  geocoder.geocode( { 'address': address}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
    	//alert(results[0].geometry.location.lat());
    	
    	map.setCenter(results[0].geometry.location);
    	
    	if (marker) {
    		marker.setMap(map);
    		marker.setPosition(results[0].geometry.location);
    	} else {
    		placeMarker(results[0].geometry.location);
    	}
    	
      lat = results[0].geometry.location.lat();
			lng = results[0].geometry.location.lng();
			
			var res = decodeLocationField(results[0]['address_components']);

		  civico = (typeof(res['street_number']) == 'undefined')?'':res['street_number'].long_name;
		  via = (typeof(res['route']) == 'undefined')?'':res['route'].long_name;
		  cap = (typeof(res['postal_code']) == 'undefined')?'':res['postal_code'].long_name;
		  citta = (typeof(res['locality']) == 'undefined')?'':res['locality'].long_name;
		  provincia = (typeof(res['administrative_area_level_1']) == 'undefined')?'':res['administrative_area_level_2'].long_name;
		  regione = (typeof(res['administrative_area_level_2']) == 'undefined')?'':res['administrative_area_level_1'].long_name;
		  nazione = (typeof(res['country']) == 'undefined')?'':res['country'].long_name;
		  codice_nazione = (typeof(res['country']) == 'undefined')?'':res['country'].short_name;

		  verifica('indirizzo_mappa');

    } else {
      //alert("Geocode was not successful for the following reason: " + status);
    }

  });
}

function aggiorna_posizione_da_coordinate(llat,llng) {

	lat=llat;
	lng=llng;

	var location = new google.maps.LatLng(lat,lng);

  geocoder.geocode({'latLng': location}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
    		      	      
      if (results[0]['address_components']) {
        $('#indirizzo').val(results[0].formatted_address);

				var res = decodeLocationField(results[0]['address_components']);

			  civico = (typeof(res['street_number']) == 'undefined')?'':res['street_number'].short_name;
			  via = (typeof(res['route']) == 'undefined')?'':res['route'].short_name;
			  cap = (typeof(res['postal_code']) == 'undefined')?'':res['postal_code'].short_name;
			  citta = (typeof(res['locality']) == 'undefined')?'':res['locality'].short_name;
			  provincia = (typeof(res['administrative_area_level_1']) == 'undefined')?'':res['administrative_area_level_2'].short_name;
			  regione = (typeof(res['administrative_area_level_2']) == 'undefined')?'':res['administrative_area_level_1'].short_name;
			  nazione = (typeof(res['country']) == 'undefined')?'':res['country'].long_name;
			  codice_nazione = (typeof(res['country']) == 'undefined')?'':res['country'].short_name;

			  verifica('indirizzo_mappa');
  	          
      }
    } else {
      //alert("Geocoder failed due to: " + status);
    }
  });

}

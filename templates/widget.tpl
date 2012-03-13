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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Decoro Urbano - Widget</title>
<link rel="image_src" type="image/jpeg" href="{$settings.sito.url}images/DU_FB.jpg" />
<meta name="robots" content="noindex,follow" />
<meta name="description" content="{$metaDesc}" />
<meta property="og:title" content="{$pageTitle}" />
<meta property="og:type" content="product" />
<meta property="og:url" content="{$settings.sito.url}{$smarty.server.REQUEST_URI}" />
<meta property="og:image" content="{$settings.sito.url}images/DU_FB.jpg" />
<meta property="og:description" content="Utilizza anche tu Decoro Urbano, lo strumento gratuito per la segnalazione del degrado via smartphone e PC. La cittadinanza attiva comincia da te." />
<meta property="og:site_name" content="Decoro Urbano" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="it" />

<link rel="icon" href="{$settings.sito.url}/images/favicon.png" />
<link rel="stylesheet" type="text/css" href="{$settings.sito.url}css/widget.css" />
<link href='http://fonts.googleapis.com/css?family=Nunito&subset=latin&v2' rel='stylesheet' type='text/css'>
<link type="text/css" href="{$settings.sito.url}css/jqueryui/jquery-ui-1.8.12.custom.css" rel="stylesheet" />	
<script type="text/javascript" src="{$settings.sito.url}js/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="{$settings.sito.url}js/jquery-ui-1.8.12.custom.min.js"></script>
<script type="text/javascript" src="{$settings.sito.url}js/jquery.json-2.2.min.js"></script>
<script type="text/javascript" src="{$settings.sito.url}js/funzioni.js"></script>
<script type="text/javascript" src="{$settings.sito.url}js/Date.extend.js"></script>
<script type="text/javascript" src="{$settings.sito.url}js/Array.extend.js"></script>
<script type="text/javascript" src="{$settings.sito.url}js/controlli.js"></script>
<script type="text/javascript" src="{$settings.sito.url}js/markerclusterer.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?v=3.2&sensor=false&language=it"></script>

<!--[if IE]>
<link type="text/css" href="{$settings.sito.url}css/ie.css" rel="stylesheet" />	
<![endif]-->

</head>
<body>


<div id="widgetWrap">
	<div id="logoDu">
   		 <a href="{$settings.sito.url}" target="_blank"><img src="{$settings.sito.url}images/decorourbano.png" width="250" height="37" alt="Decoro Urbano" /></a>
    </div>
    <!-- fine logoDu -->
    
    <div id="comuneWrap">
    	{if $comune_attivo}
    	<div id="logoComune"><img src="{$settings.sito.url}resize.php?h=73&f={$comune_logo}" alt="Comune di {$comune}" /></div>
        {else}
		<div id="logoComune"><img src="{$settings.sito.url}images/logo_du_square.png" width="73" height="73" /></div>
		{/if}
		<div id="comune">
        	<div id="nomeComune"><a href="{$comune_url}" target="_blank">{$comune}</a></div>
			{if $comune_attivo}
            <div id="statusComune">COMUNE ATTIVO</div>
			{else}
			<div id="statusComune">COMUNE NON ATTIVO</div>
			{/if}
        </div>
    </div>
    <!-- fine comuneWrap -->
    
	{if $m}	
		
		<script type="text/javascript">
		
		var json_segnalazioni='{$segnalazioni_json}';
		var segnalazioni=[];
		var initialLocation = new google.maps.LatLng({$comune_lat}, {$comune_lng});
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
				streetViewControl: false,
				mapTypeControl: false
			};
			
			this.map = new google.maps.Map($(selector)[0], mapOptions);
		}
		
		var boxText = document.createElement("div");
		boxText.style.cssText = "width:290px; float:right; margin:0; padding: 5px;";

		function segnalazioni_mostra() {

			du_map.markers.splice(0,du_map.markers.length);
			
			if (markerClusterer) {
				markerClusterer.clearMarkers();
			}
		
		  if (segnalazioni)
				for (i in segnalazioni)
					aggiungi_segnalazione('append', segnalazioni[i]);
		
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
		
			//if ('{$user.id_utente}' == '1') alert(segnalazione.marker);
		
		  var image = new google.maps.MarkerImage(segnalazione.marker,
		    new google.maps.Size(40, 40),
		    new google.maps.Point(0,0),
		    new google.maps.Point(19, 40));
		
			var marker = new google.maps.Marker({
		    position: myLatlng,
		    icon: image
			});
		
			google.maps.event.addListener(marker, 'click', function() {
				window.open('{$settings.sito.url}'+segnalazione.tipo_nome_url+'/'+segnalazione.citta_url+'/'+segnalazione.indirizzo_url+'/'+segnalazione.id_segnalazione+'/','_blank');
			});
			
			if (!markerClusterer) {
				markerClusterer = new MarkerClusterer(du_map.map, du_map.markers);
				markerClusterer.setGridSize(35);
				markerClusterer.setMaxZoom(15);
				markerClusterer.setMinClusterSize(2);
		
				var styles=markerClusterer.getStyles();
				styles[0]['url'] = '{$settings.sito.url}images/ico_group_10.png';
				styles[1]['url'] = '{$settings.sito.url}images/ico_group_25.png';
				styles[2]['url'] = '{$settings.sito.url}images/ico_group_50.png';
				styles[3]['url'] = '{$settings.sito.url}images/ico_group_100.png';
				markerClusterer.setStyles(styles);
			}
			markerClusterer.addMarker(marker);
		}
		
		window.onload=function() {
			du_map.init('#mappaSegnalazioni',initialLocation,zoom);
			segnalazioni = jQuery.secureEvalJSON(json_segnalazioni);
			segnalazioni_mostra();
		}
				
		</script>
		
    <div id="mappaSegnalazioni">
    	mappa segnalazioni
    </div>
    {/if}
	<!-- fine mappaSegnalazioni -->
  
    {if $n}	  
	  <div id="segnalazioniRisolte" class="numSegnalazioni">
	    	<span>{$count_risolte}</span> <div>RISOLTE</div>
	  </div>
	    <!-- fine segnalazioniRisolte -->
	    
	  <div id="segnalazioniCarico" class="numSegnalazioni">
	      <span>{$count_carico}</span> <div>IN CARICO</div>
	  </div>
	    <!-- fine segnalazioniCarico -->
	    
	  <div id="segnalazioniTotali" class="numSegnalazioni">
	    	<span>{$count_totale}</span> <div><b>TOTALI</b></div>
	  </div>
	    <!-- fine segnalazioniTotali -->
    {/if}

    <!-- fine paginaComune -->
    
	{if $u}	
    <div id="ultimeSegnalazioniWrap">
    	<div class="title">&nbsp;Ultime Segnalazioni</div>
        <div id="segnalazioniWrap" >
        	{foreach name="ultime_segnalazioni" from=$ultime_segnalazioni item=segnalazione}
	        	<div class="segnalazioni" onclick="window.open('{$settings.sito.url}{$segnalazione.tipo_nome_url}/{$segnalazione.citta_url}/{$segnalazione.indirizzo_url}/{$segnalazione.id_segnalazione}','_blank')">
            <div class="segnalazioni_box">
	            	<img src="{$segnalazione['foto_base_url']}71-46.jpg" width="71" height="46" alt="1" align="right" />
	            	<div class="segnalatore"><a href="{$settings.sito.vediProfilo}?idu={$segnalazione.id_utente}" class="tdNone"><span class="fBold fontS12">
				{$segnalazione.nome} {$segnalazione.cognome}</span>
			</a> {$segnalazione.data|ConvertitoreData_UNIXTIMESTAMP_IT}</div>
	                <div class="dettaglio">{$segnalazione.messaggio|truncate:47:"..."}</div>
	                <div class="posizione">{$segnalazione.indirizzo} {$segnalazione.civico}</div>
	                <div class="piattaforma" style="display:none;">
	                	{if $segnalazione.client == 'iPhone'}via <a href="{$settings.sito.applicazioni}">iPhone</a>{/if}
						{if $segnalazione.client == 'Android'}via <a href="{$settings.sito.applicazioni}">Android</a>{/if}
	          		</div>
                </div>
			       </div>
          	{/foreach}
        </div>
    </div>
    <!-- fine ultimeSegnalazioniWrap -->
    {/if}	
 
 		
    <div id="paginaComune">
   		<a href="{$comune_url}" target="_blank">Vai alla pagina del comune</a>
    </div>    

    {if $tw}
    <div id="twitterWrap">
    	<div id="twitter" class="marginT20">
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
		</div>
    </div>
	{/if}

   
</div>
<!-- fine widgetWrap -->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-16957391-5']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>

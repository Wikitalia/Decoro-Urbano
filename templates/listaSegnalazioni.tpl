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

<script src="{$settings.sito.url}js/infobox.js" type="text/javascript"></script>
<script type="text/javascript" src="{$settings.sito.url}js/jquery.autoellipsis-1.0.2.min.js"></script>
<script type="text/javascript" src="{$settings.sito.url}js/jquery.cookie.js"></script>
<script>

	var jsonFiltriLista = $.cookie("jsonFiltriLista");
	var filtriLista = new Array();
	var subdomain = ('{$subdomain}' == '')?'www':'{$subdomain}';
	var domain = '{$settings.sito.dominio}';

	if (jsonFiltriLista) {
		filtriLista = jQuery.secureEvalJSON(jsonFiltriLista);
	}


	var settings_limit_giorni={$settings['segnalazioni'].limit_giorni};
	var json_segnalazioni='{$segnalazioni}';
	var settings_limit_numero={$settings['segnalazioni'].limit_numero};
	{if $user}
		var idu={$user.id_utente};
	{/if}
	
	var old_ib = null;
	
</script>

<script type="text/javascript" src="{$settings.sito.url}js/mappa_elenco.js"></script>
<script type="text/javascript" src="{$settings.sito.url}js/markerclusterer.js"></script>

<script>

// boxText è il contenitore del popup
var boxText = document.createElement("div");
boxText.style.cssText = "width:290px; float:right; margin:0; padding: 5px;";

var infoBoxOptions = {
	content: boxText,
	disableAutoPan: false,
	maxWidth: 0,
	pixelOffset: new google.maps.Size(28, -92),
	zIndex: null,
	/*
	boxStyle: { 
		background: "white",
		display: "hidden",
		opacity: 1,
		width: "290px",
		float: "left",
		margin: "22px",
		height: "22px",
		border: "1px #ccc solid"
	},
	*/
	closeBoxMargin: "5px 0 0 282px",
	closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif",
	infoBoxClearance: new google.maps.Size(1, 1),
	isHidden: false,
	pane: "floatPane",
	enableEventPropagation: false
};

var ib = new InfoBox(infoBoxOptions);

//var dotsIB = $('.infoBox').ThreeDots({ max_rows:1 });

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

	var marker = new google.maps.Marker({
	    position: myLatlng,
	    icon: image
	});

	google.maps.event.addListener(marker, 'click', function() {
		//location.href=segnalazione.url;
		ib.close();
		
		
		// Contenuto del popup
		infoBoxHTML = '\
			<img src="/images/popupFreccia.png" alt="" style="position:relative; left:-26px;  top:25px; margin-right:-26px; float:left;" />\
			<div id="infoBoxContent" class="ultimeSegnalazioni" onclick="location.href=\''+segnalazione.url+'\'">\
					<div class="leftAvatar">\
						<div style="width:35px; float:left;">\
							<a href="{$settings.sito.vediProfilo}?idu='+segnalazione.id_utente+'"><img src="/resize.php?w=30&h=30&f='+segnalazione.avatar+'" alt="'+segnalazione.nome+' '+segnalazione.cognome+'" /></a>\
						</div>\
					</div>\
					<div class="rightContents">\
						<div style="width:220px; float:left;">\
							<a href="{$settings.sito.vediProfilo}?idu='+segnalazione.id_utente+'" class="tdNone"><span class="fBold fontS12">\
								'+segnalazione.nome+' '+segnalazione.cognome+'</span>\
							</a><br />\
							<span class="fontS10">'+relativeTime(segnalazione.data)+'</span>\
						</div>\
						<img src="'+segnalazione['foto_base_url']+'85-55.jpg" class="marginL5 right" />\
						<div class="auto fontS12 fGeorgia ellipsis_text marginT5" style="width:150px;height:30px;overflow:hidden;clear:left;">'+segnalazione.messaggio+'</div>\
						<div class="auto fontS10 fGreen marginT5"> '+segnalazione.citta+' - '+segnalazione.indirizzo+' '+segnalazione.civico+'</div>';
		if (segnalazione['client'] == 'iPhone') infoBoxHTML += '<div class="auto fontS10">via <a href="{$settings.sito.applicazioni}">iPhone</a></div>';
		if (segnalazione['client'] == 'Android') infoBoxHTML += '<div class="auto fontS10">via <a href="{$settings.sito.applicazioni}">Android</a></div>';
		if (segnalazione['client'] == 'Windows Phone') infoBoxHTML += '<div class="auto fontS10">via <a href="{$settings.sito.applicazioni}">Windows Phone</a></div>';

		infoBoxHTML += '</div></div>';
		
		boxText.innerHTML = infoBoxHTML;
		ib.open(du_map.map, marker);


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
	var c = markerClusterer.getCluster(marker);

}

window.onload=function() {

	du_map.init('#map_canvas_list',initialLocation,zoom);
	
	segnalazioni_first_load();

	$( "#listaSegnFiltersCategoria" ).buttonset();
	
	$( "#listaSegnFilters" ).show();

	//interval = setInterval ( "segnalazioni_nuove_get()", 1800000);
	//interval = setInterval ( "segnalazioni_nuove_get()", 300000);
	//interval = setInterval ( "segnalazioni_nuove_get()", 15000);

}
		
</script>


<div id="listaSegnalazioni">

	{if $locType == 'comune'}
	
	 <script>
		 {if $location}
			 initialLocation = new google.maps.LatLng({$location.lat}, {$location.lng});
			 zoom = 15;        
		 {else}
			 initialLocation = new google.maps.LatLng({$comune.lat}, {$comune.lng});
			 zoom = 11;                
		 {/if}  
		id_comune = {$comune.id_comune};
	</script>
	
	<div id="listaSegnComuneTop">
		{if $comune.stato == 0}
		<div id="bannerScriviComune">
			{*<img src="/images/invitaComune.png" alt="" class="left marginR10" />*}
			<h4 class="auto">{#comuneNonAttivoBox1Titolo#}</h4>
			{#comuneNonAttivoBox1Testo#}
		</div>
		<div id="dettaglioComuneNonAttivo">
			<div class="auto fGreen right clear fBold fontS13">{$comune.nome}</div>
			<div class="auto fLightGray fUppercase right clear fontS16 fBold marginT5">{#comuneNonAttivo#}</div>
			<div class="auto right clear fontS13 fBold marginT5">
				{*<img src="/images/miniBusta.png" alt="" class="marginR5" />
				<a href="http://www.google.it/search?q=comune+di+{$comune.nome}" class="tdNone" target="_blank"><span class="fOrange">{#scriviAlComune#}</span></a>*}
			</div>
		</div>	
		{else}

		<div id="dettaglioCifreComune">
			{if $comune.logo != ''}		
			<div class="left w90" style="text-align:center;margin: 10px 0;height:90px;">
				<img src="/resize.php?h=90&f={$comune.logo}" />
			</div>
			{/if}			
			<div class="left w380 paddL10">
				<div class="h55">
					<h2 class="fBrown">Comune di {$comune.nome}</h2>
				</div>
				
				<div class="fGreen fBold fontS12 marginB5 left">Segnalazioni a {$comune.nome}</div>
				
				<div class="auto marginR10 fontS12"><span class="fBold">{#comuneAttivoSegnTot#}:</span> {$comune.totali}</div>
				<div class="auto marginR10 fontS12"><span class="fBold">{#comuneAttivoSegnInCarico#}:</span> {$comune.in_carico}</div>
				<div class="auto marginR10 fontS12"><span class="fBold">{#comuneAttivoSegnRisolte#}:</span> {$comune.risolte}</div>
			</div>
		</div>
		<div id="dettaglioComuneAttivo">
			{*<div class="fGreen fontS11 h43">Account verificato<img src="/images/DU_account_verificato.png" align="absmiddle" class="marginL5" /></div>*}
			{*<img src="{$comune.logo}" style="width:40px;" class="right marginL5" />*}
			<div class="fOrange fUppercase marginR5 fontS16 fBold marginT5 right w243">{#comuneAttivo#}</div>
			<span class="auto fGray marginR5 fontS12 right marginT5">{#comuneAttivoDal#} {$comune.data_affiliazione|ConvertitoreData_UNIXTIMESTAMP_IT}</span>
		</div>
		
		
		{/if}
	</div>
	
	{else if $locType == 'regione'}
	
	<script>
	initialLocation = new google.maps.LatLng({$regione.lat}, {$regione.lng});
	zoom = 8;
	regione = "{$regione.nome}";
	</script>
	
	<div id="listaRegione">
		<div id="listaSegnRegioneStats">
			<div class="fontS14 fBrown fBold">{#regione#} {$regione.nome}</div>
			<div class="fontS12">{#regioneComuniTotale#} {$regione.nome}: <strong>{$regione.dati.totali}</strong></div>
			{*<div class="fontS12">{#regioneComuniAttivi#}: <strong>{$regione.dati.attivi}</strong></div>
			<div class="fontS12">{#regioneComuniNonAttivi#}: <strong>{$regione.dati.non_attivi}</strong></div>*}
		</div>
		<div id="listaSegnRegioneTopComuni">
			<div class="fontS14 fBold fBrown">{#segnalatoriAttivi#}:</div>
			{assign var="fontS" value="20"}
			{foreach from=$regione.top_comuni item=comune name=segnAttiviRegione}
				<span class="fBold fontS{$fontS-($smarty.foreach.segnAttiviRegione.index*2)}"><a href="http://{$comune.nome_url}.{$settings.sito.dominio}" class="tdNone">{$comune.nome}</a> ({$comune.totali})</span>
			{/foreach}
		</div>
	</div>
	{else if $locType == 'competenza'}
	<script>
		zoom = 6;  
	</script>
	<div id="listaSegnCompetenza">
		<div id="listaSegnCompetenzaTitolo">
			<div class="fontS20  ">{#gestioneCompetenza#}</div>
		</div>
		<div id="listaSegnCompetenzaLogo">
			<img style="width:100%;" src="{$settings.sito.url}images/loghi_competenze/agcom.png" />
		</div>
	</div>
	{else}
	<script>
	//var initialLocation = new google.maps.LatLng({$regione.lat}, {$regione.lng});
	{if $location}
		initialLocation = new google.maps.LatLng({$location.lat}, {$location.lng});
		zoom = 15;
	{else}
		zoom = 6;
	{/if}    
	/*if ("{$user.citta}" != '') {
		var geocoder = new google.maps.Geocoder();
	  geocoder.geocode( { 'address': '{$user.quartiere} {$user.citta} italy' } , function(results, status) {
	    if (status == google.maps.GeocoderStatus.OK) {
	    	initialLocation = results[0].geometry.location;
	    	zoom = 14;
	    }
	  });
	} else zoom = 5;*/
	</script>
	<div id="listaSegnItalia">
		<div id="listaSegnItaliaStats">
			<div class="fontS14 fBrown fBold">{#italiaComuni#}</div>
			<div class="fontS12">{#italiaTotaleComuni#}: <strong>{$italia.dati.totali}</strong></div>
			{*<div class="fontS12">{#italiaTotaleComuniAttivi#}: <strong>{$italia.dati.attivi}</strong></div>
			<div class="fontS12">{#italiaTotaleComuniNonAttivi#}: <strong>{$italia.dati.non_attivi}</strong></div>*}
		</div>
		<div id="listaSegnItaliaTopComuni">
			<div class="fontS14 fBold fBrown">{#segnalatoriAttivi#}:</div>
			{assign var="fontS" value="20"}
			{foreach from=$italia.top_comuni item=comune name=segnAttivi}
				<span class="fBold noWrap fontS{$fontS-($smarty.foreach.segnAttivi.index*2)}"><a href="http://{$comune.nome_url}.{$settings.sito.dominio}" class="tdNone">{$comune.nome}</a> ({$comune.totali})</span>
			{/foreach}
		</div>
	</div>
	{/if}
	{if ($locType == 'regione') || ($locType == 'comune')}
  <div id="segnalazionePath">
   {*<img src="{$settings.sito.url}images/DU_freccia_path.png" class="left" />*}
   <div class="auto marginT5 marginB5">
    &nbsp;DU / <a href="{$settings.sito.listaSegnalazioni}" class="tdNone">Italia</a> / 
    <a href="http://{$regione.nome_url}.{$settings.sito.dominio}" class="tdNone">{$regione.nome}</a>  
    {if $locType == 'comune'}
    / <a href="http://{$comune.nome_url}.{$settings.sito.dominio}" class="tdNone">{$comune.nome}</a>
    {/if}
   </div>
  </div>
 {/if}
	<div id="listaSegnMappa" style="position:relative;">
		{*<div id="listaComuni" style="position:absolute;height:100%;width:175px;background-color:white;z-index:10;">
			<input id="listaComuniFiltro" type="text" onkeyup="listFilter();" />
			<div id="listaComuniFiltrata" style="height:95%;overflow:auto;">
			{foreach from=$regioni item=regione}
				<a href="" style="display:block;">{$regione.nome}</a>
				{foreach from=$regioni['piemonte'].comuni item=comune}
					<a href="http://{$comune.nome_url}.{$settings.sito.dominio}" style="display:block;">{$comune.nome}</a>
				{/foreach}
			{/foreach}
			</div>
		</div>*}
		<div id="map_canvas_list" style="height:100%;">
		</div>
	</div>
	<div id="listaSegnBottom">
		<div id="listaSegnFilters" style="display:none;">
			<h5 class="fGreen">{#filtraSegnalazioni#}</h5>
			<div>	
				<form class="skinnedForm">
					<div>
						<select id="listaSegnFiltersStato" onchange="segnalazioni_filtra();" class="marginB10">
							<option value="0">{#filtraTutte#}</option>
							<option value="100">{#filtraInAttesa#}</option>
							<option value="200">{#filtraInCarico#}</option>
							<option value="300">{#filtraRisolte#}</option>
						</select>
						<input type="checkbox" id="filtro_recenti" name="recenti" checked="checked"  onclick="segnalazioni_filtra();" value="recenti" />
						<label for="filtro_recenti">
							<span class="ui-button-text">{#filtraRecenti#}</span>
						</label>
					{if $user}
						<input type="checkbox" id="filtro_personali" name="personali" onclick="segnalazioni_filtra();" value="personali" />
						<label for="filtro_personali">
							<span class="ui-button-text">{#filtraPersonali#}</span>
						</label>
					{else}
					<input type="checkbox" id="filtro_personali" name="personali" value="personali" style="display:none;"/>
					{/if}
					
					
					
					{*{foreach from=$tipi item=tipo}
						<input type="checkbox" id="radio{$tipo.id_tipo}" name="tipo" class="" value="{$tipo.id_tipo}" onclick="segnalazioni_filtra();" checked="checked" />
						<label for="radio{$tipo.id_tipo}" aria-pressed="false" class="categoriaButt" role="button" aria-disabled="false">
							<span class="ui-button-text"><span class="{$tipo.label}SmallIcon"></span><br />{$tipo.nome}</span>
						</label>
					{/foreach}
					<div class="ui-state-active" style="background:none;border:0;">
						<div class="rifiutiSmallIcon clear"></div>
						<div class="vandalismoSmallIcon clear"></div>
						<div class="degradoZoneVerdiSmallIcon clear"></div>
						<div class="sosBucheSmallIcon clear"></div>
						<div class="segnaleticaStradaleSmallIcon clear"></div>
						<div class="affissioniAbusiveSmallIcon clear"></div>
					</div>*}
					
					<div id="listaSegnFiltersCategoria">
						{foreach from=$tipi item=tipo name="filtri"}
							<input type="checkbox" id="radio{$tipo.id_tipo}" name="tipo" value="{$tipo.id_tipo}" onclick="segnalazioni_filtra();" checked="checked" />
							<label for="radio{$tipo.id_tipo}" aria-pressed="false" class="categoriaButt {if ! $smarty.foreach.filtri.first}marginL15{/if}" role="button" aria-disabled="false">
								<div class="{$tipo.label}SmallIcon"></div> <div class="auto fontS12 fNormal fArial marginL5 fGray">{$tipo.nome}</div>
							</label>
						{/foreach}
					</div>
					
				</form>

			{if $locType == 'comune'} {* Comune *}

      <div class="dataset_box_download">
      <div class="dataset_box_download_testo">
        Download del dataset <a href="http://it.wikipedia.org/wiki/GeoRSS" target="_blank">GeoRSS</a> per <b>{$comune.nome}</b>:
				<a href="{$settings.sito.url}ext/georss_dl.php?comune={$comune.nome_url}&compress=1" target="_blank">{$comune.nome_url}.zip</a>
				- <a href="{$settings.sito.url}ext/georss_dl.php?comune={$comune.nome_url}" target="_blank">{$comune.nome_url}.rss</a>
				<br>
        <span class="dataset_box_download_licenza">Licenza <a href="http://creativecommons.org/licenses/by/3.0/it/" target="_blank">Creative Commons Attribuzione 3.0 Italia (CC BY 3.0)</a></span>
			</div>
      </div>
      
      {else if $locType == 'regione'} {* Regione *}
      
      {else if $locType == 'competenza'} {* Regione *}
			
			{else} {* Italia intera *}
			
      <div class="dataset_box_download">
      <div class="dataset_box_download_testo">
        Download del dataset <a href="http://it.wikipedia.org/wiki/GeoRSS" target="_blank">GeoRSS</a> per <b>Italia</b>:
        
        <a href="{$settings.sito.url}ext/georss_dl.php?compress=1" target="_blank">Italia.zip</a>
        - <a href="{$settings.sito.url}ext/georss_dl.php" target="_blank">Italia.rss</a>
				
				<br>
        <span class="dataset_box_download_licenza">Licenza <a href="http://creativecommons.org/licenses/by/3.0/it/" target="_blank">Creative Commons Attribuzione 3.0 Italia (CC BY 3.0)</a></span>
			</div>
      </div>
      
      {/if}

			</div>
		</div>
	</div>
</div>
</div>
{include file="includes/footer.tpl"}
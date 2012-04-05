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

<script type="text/javascript" src="{$settings.sito.url}js/mappa_segnala.js"></script>

<script>
	$(function() {
		$( "#invSegnCategoria" ).buttonset();
	});
	var tipo = 0;
</script>

<div class="rightPageHeader">
	<div class="rightHeadText"><h3 class="fontS18 marginB5 fBrown">{#titolo#}</h3>
		{#inviaIntro#} <a href="{$settings.sito.guida}">{#guida#}</a>
	</div>
	<div class="rightHeadIcon"><div class="rhiInviaSegnalazione"></div></div>
</div>

<form id="formInviaSegnalazione" onsubmit="return ajaxSubmit();">
	
	<div id="invSegnCategoria" class="testoFumetto">
		<div><h3 class="pageTitle marginB5"><span class="fontS20">1.</span> {#categoria#} <span id="controlloCategoria"></span></h3></div>
		{foreach from=$tipi item=tipo}
		<input type="radio" id="radio{$tipo.id_tipo}" name="tipo" class="" value="{$tipo.id_tipo}" />
		<label for="radio{$tipo.id_tipo}" aria-pressed="false" class="categoriaButt" role="button" aria-disabled="false">
			<span class="ui-button-text" onclick="categoria_set({$tipo.id_tipo});"><span class="{$tipo.label}Icon"></span><br />{$tipo.nome}</span>
		</label>
		{/foreach}
	</div>
	
	<div id="invSegnForm" class="skinnedForm">
		<h3 class="pageTitle marginB5"><span class="fontS20">2.</span> {#descProblema1#} <span id="controlloDescrizione"></span></h3> 
		<div>
			<div>
				<b>{#descrizione#} *</b><br />
				<span class="fontS10">{#descProblema2#}</span>
			</div>
			<textarea id="descrizione" name="descrizione" onblur="verifica('descrizione');" onkeyup="verifica('descrizione');"></textarea>
		</div>
		<div>
			<div class="marginT10">
				<b>{#indirizzo#} *</b><span id="controlloIndirizzo"></span><br />
				<span class="fontS10">{#indirizzoDesc#}</span>
			</div>
			<input type="text" id="indirizzo" name="indirizzo" onkeyup="aggiorna_posizione_da_stringa();verifica('indirizzo_mappa');" onblur="aggiorna_posizione_da_stringa();verifica('indirizzo_mappa');" />
		</div>
		<div>
			<div class="marginT10"><b>{#immagine#} *</b> <span class="fontS10">{#limiteImg#}</span><span id="controlloFoto"></span><br />
				<span class="fontS10">{#uploaderInfo#}</span></div>
			<ul id="lista_file" class="qq-upload-list">
			</ul>
			<div id="file-uploader">       
		    <noscript>
	        <p>{#jsOff#}</p>
	        <!-- or put a simple form for upload here -->
		    </noscript>         
			</div>
		</div>
		<div class="marginT10 fontS10">
			{#noSegnDuplicate#} <a href="{$settings.sito.guida}" target="_blank">{#guida#}</a>
		</div>
		<div class="marginT20"><input type="submit" value="{#inviaButt#}" /></div>
	</div>
	
	<div id="invSegnMappa">
		<h3 class="pageTitle marginB5"><span class="fontS20">3.</span> {#affinaPos#} <span id="controlloMappa"></span></h3>
		<div id="map_container_invia">
			<div id="map_canvas"></div>
		</div>
	</div>
</form>

<div id="invSegnRiepilogo" style="display:none;">
	<div class="testoFumetto">
		<div><h3 class="pageTitle marginB15">{#segnInserita#}</h3></div>
		<div>
			<div class="riepilogoLeft">{#dove#}</div>
			<div class="riepilogoRight" id="riepilogoIndirizzo"></div>
		</div>
		<div class="marginT20">
			<div class="riepilogoLeft">{#commento#}</div>
			<div class="riepilogoRight" id="riepilogoMessaggio">
			</div>
		</div>
		<div class="marginT20">
			<div class="riepilogoLeft">{#foto#}</div>
			<div class="riepilogoRight"><img id="riepilogoFoto" src="" alt="" /></div>
		</div>
	</div>
</div>



<script>

//$('#radio6').click(function() { verifica('categoria'); });

var upload_done = false;
var err_msg;

var uploader = new qq.FileUploader({
  // pass the dom node (ex. $(selector)[0] for jQuery users)
  element: document.getElementById('file-uploader'),
  // path to server-side upload script
  action: '/ajax/segnalazione_foto_add.php',
  // ex. ['jpg', 'jpeg', 'png', 'gif'] or []
	allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
	multiple: false,
	listElement: document.getElementById('lista_file'),
	onSubmit: function(id, fileName){
		$('#lista_file').html('');
	},
	onComplete: function(id, fileName, responseJSON){
		if (responseJSON.success) {
			upload_done = true;
			verifica('foto');
		}
	},
	params: {
		sid: '{$sid}'
	}
});

function categoria_set(cat) {

	tipo=cat;
	verifica('categoria');

}

function verifica(campo) {

	//var tipo = $('input[name=tipo]:checked').val();
	//alert(tipo);
	var descrizione = $("#descrizione").val();

	var tmp = document.createElement("DIV");
	tmp.innerHTML = descrizione;
	descrizione = $(tmp).text();
	
	var indirizzo = $("#indirizzo").val();

	var err = 0;
	err_msg='';

	if(campo == 'categoria' || campo == '')
		if(!tipo) {
			$("#controlloCategoria").removeClass('checkPassed');
			$("#controlloCategoria").addClass('checkFailed');
			err = 1;
			err_msg += '<p>{#verifica1#}</p>';
		} else {
			$("#controlloCategoria").removeClass('checkFailed');
			$("#controlloCategoria").addClass('checkPassed');
		}
	if(campo == 'descrizione' || campo == '')
		if(!descrizione.length) {
			$("#controlloDescrizione").removeClass('checkPassed');
			$("#controlloDescrizione").addClass('checkFailed');
			err = 1;
			err_msg += '<p>{#verifica2#}</p>';
		} else {
			$("#controlloDescrizione").removeClass('checkFailed');
			$("#controlloDescrizione").addClass('checkPassed');
		}
		
	if(campo == 'indirizzo_mappa' || campo == '')
		if(indirizzo == '' || via == '' || citta == '') {
			$("#controlloIndirizzo").removeClass('checkPassed');
			$("#controlloIndirizzo").addClass('checkFailed');
			err = 1;
			err_msg += '<p>{#verifica3#}</p>';
		} else {
			$("#controlloIndirizzo").removeClass('checkFailed');
			$("#controlloIndirizzo").addClass('checkPassed');
		}
	
	if(campo == 'indirizzo_mappa' || campo == '')
		if(typeof(lat) == 'undefined' || typeof(lng) == 'undefined' || lat == 0 || lng == 0) {
			$("#controlloMappa").removeClass('checkPassed');
			$("#controlloMappa").addClass('checkFailed');
			err = 1;
			err_msg += '<p>{#verifica4#}</p>';
		} else {
			$("#controlloMappa").removeClass('checkFailed');
			$("#controlloMappa").addClass('checkPassed');
		}
	
	if(campo == 'foto' || campo == '')
		if(!upload_done) {
			$("#controlloFoto").removeClass('checkPassed');
			$("#controlloFoto").addClass('checkFailed');
			err = 1;
			err_msg += '<p>{#verifica5#}</p>';
		} else {
			$("#controlloFoto").removeClass('checkFailed');
			$("#controlloFoto").addClass('checkPassed');
		}
		
	if (err) return false;
	else return true

}

function ajaxSubmit() {

	//var tipo = $('input:radio[name=tipo]:checked').val();
	var descrizione = $("#descrizione").val();
	var indirizzo = $("#indirizzo").val();
	//var lat = $("#lat").val();
	//var lng = $("#lng").val();

	if (!verifica('')) {
		$('#modalControlli').html(err_msg);
		$('#modalControlli').dialog({
			height: 400,
			width:550,
			modal: true,
			draggable:false,
			resizable:false,
			buttons: {
				Ok: function() {
					//$( this ).dialog( "{#annulla#}" );
					$('#modalControlli').dialog('close');
				}
			}
		});
		return false;
	}

	$('#formInviaSegnalazione').hide();
	$('#loadingInviaSegnalazione').show();

  var dataString = 'id_utente={$user.id_utente}';
	dataString += '&sid={$sid}';
	dataString += '&id_tipo='+tipo;
	dataString += '&descrizione='+descrizione;
	
	dataString += '&indirizzo='+indirizzo;
	dataString += '&lat='+lat;
	dataString += '&lng='+lng;
	
  dataString += '&civico='+civico;
	dataString += '&via='+via;
	dataString += '&cap='+cap;
	dataString += '&citta='+citta;
	dataString += '&provincia='+provincia;
	dataString += '&regione='+regione;
	dataString += '&nazione='+nazione;
	dataString += '&codice_nazione='+codice_nazione;
	
	dataString += '&client={$settings.client.nome}';
	dataString += '&versione={$settings.client.versione}';
	


	//alert (dataString);
	//return false;
  $.ajax({
    type: "POST",
    url: "/ajax/segnalazione_data_add.php",
    dataType: "json",
    data: dataString,
    success: function(result) {
    	if (result && result.status == 'ok') {
	    	/*$('#loadingInviaSegnalazione').hide();
	    	$('#riepilogoIndirizzo').html(indirizzo+' '+civico);
	    	$('#riepilogoMessaggio').html(descrizione);
	    	$('#riepilogoFoto').attr('src','/resize.php?w=450&f=/images/segnalazioni/{$user.id_utente}/'+result+'/1.jpeg');						    	
	    	$('#invSegnRiepilogo').show();*/
	    	window.location.href=result.link_segnalazione;
    	} else {
    		alert('{#erroreInvio#}');
			}
    	//window.location.href='{$settings.sito.url}';
    }
  });
  
  return false;

}

</script>

<div id="invSegnApplicaz" class="marginT20">
	<div><h3 class="pageTitle marginB5">{#mobilitatiTitolo#}</h3></div>
		<div style="width:540px;">{#mobilitati1#} <strong>{#decoro#}</strong> {#mobilitati2#} <a href="{$settings.sito.applicazioni}">{#applicazioni#}</a> {#mobilitati3#}</div>
		<div class="rhiApplicazioni right"></div>
</div>

<div class="demo-description" id="modalControlli" style="display:none;">
</div>


<script type="text/javascript">

if ('{$user.citta}' != '') {
	var geocoder = new google.maps.Geocoder();
  geocoder.geocode( { 'address': '{$user.quartiere} {$user.citta} italy' } , function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
    	initialLocation = results[0].geometry.location;
    	init_mappa('#map_canvas',initialLocation,14);
    }
  });
} else {
	init_mappa('#map_canvas',initialLocation,14);
}

$("#loadingInviaSegnalazione").css("height", $(document).height());
</script>

{include file="includes/footer.tpl"}
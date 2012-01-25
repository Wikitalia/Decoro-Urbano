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

{include file="includes/header.tpl"}

<script type="text/javascript" src="{$settings.sito.url}js/controlli.js"></script>

<div class="rightPageHeader">
	<div class="rightHeadText"><h3 class="fontS18 marginB5 fBrown">{#titolo#}</h3>
		{#modificaProfiloIntro#}
	</div>
	<div class="rightHeadIcon"><div class="rhiProfilo"></div></div>
</div>

<div id="modificaProfiloContainer">

	<div class="testoFumetto">

{if $user.id_ruolo == 2 || $user.id_ruolo == 1}
		<form class="skinnedForm" method="post" onsubmit="return check_submit();">	

			<div><h3 class="pageTitle marginB5">{#datiPers#}</h3></div>
			<div class="fontS10 marginB15">{#datiPersIntro#}</div>
			<div class="marginB10">
				<label>{#nome#}</label><input id="utenteNome" type="text" name="nome" value="{$user.nome}" disabled="disabled" />
				<span id="controllo_utenteNome"></span>
			</div>
			<div class="marginB10">
				<label>{#cognome#}</label><input id="utenteCognome" type="text" name="cognome" value="{$user.cognome_hidden}" disabled="disabled" />
				<span id="controllo_utenteCognome"></span>
			</div>

	
			<div class="marginT40"><h3 class="pageTitle marginB5">{#immagProfilo#}</h3></div>
			<div id="modProfImgContainer">
				<img id="immagineProfilo" src="/resize.php?w=90&h=90&f={$user.avatar}" class="left" alt="" />
				<div id="closeIcon_715" style="position:relative; z-index:10000;" class="closeIcon marginL5" onclick="avatarElimina({$user.id_utente});"></div>
			</div>
			
			<div id="modProfImgTools">
				<div class="marginB15 fontS10">{#selNuovaImg#}</div>
				<ul id="lista_file" class="qq-upload-list">
				</ul>
				<div id="file-uploader">       
					<noscript>          
						<p>{#jsOff#}</p>
						<!-- or put a simple form for upload here -->
					</noscript>         
				</div>
			</div>
			


			{if !isset($smarty.session.fb_session)}
			<div class="marginT40"><h3 class="pageTitle marginB5">{#cambiaPass#}</h3></div>
			<div class="fontS10 marginB15">{#passMin6#}</div>
			<div class="marginB10">
				<label for="utentePass">{#nuovaPass#}</label><input id="utentePass" type="password" name="utentePass" value="" />
				<span id="controllo_utentePass"></span>
			</div>
			<div class="marginB10">
				<label for="utentePass2">{#nuovaPass2#}</label><input id="utentePass2" type="password" name="utentePass2" value=""  />
				<span id="controllo_utentePass2"></span>
			</div>
			{/if}

			<div class="marginT40"><h3 class="pageTitle marginB15">{#infoExtra#}</h3></div>
			<div clasS="marginB10">
				<label for="utenteCitta">{#citta#}</label><input id="utenteCitta" type="text" name="citta" value="{$user.citta}" />
			</div>
			<div class="marginB10">
				<label for="utenteQuartiere">{#quartiere#}</label><input id="utenteQuartiere" type="text" name="quartiere" value="{$user.quartiere}" />
			</div>
			<div class="marginB10">
				<label for="utenteSito">{#sito#}<br /><span class="fontS10 italic">{#sitoEs#}</span></label><input id="utenteSito" type="text" name="sito" value="{$user.sito}" />
			</div>
			<div class="marginB10">
				<label for="utenteFacebook">{#profiloFb#}<br /><span class="fontS10 italic">www.facebook.com/</span> </label><input id="utenteFacebook" type="text" name="facebook_url" value="{$user.facebook_url}" {if isset($smarty.session.fb_session)}disabled="disabled"{/if}/>
			</div>
			<div class="marginB10">
				<label for="utenteTwitter">{#profiloTw#}<br /><span class="fontS10 italic">www.twitter.com/</span></label><input id="utenteTwitter" type="text" name="twitter" value="{$user.twitter}" />
			</div>
			<div class="marginB10">
				<label for="utenteAbout">{#suDiTe#}</label><textarea name="about" id="utenteAbout">{$user.about}</textarea>
			</div>
			<div class="marginT20 textRight"><input type="submit" value="Salva le modifiche" name="form_profilo_utente1" class="marginR10" /></div>

		

	</form>
	
{elseif $user.id_ruolo == 3}



		<form class="skinnedForm" method="post" onsubmit="return check_submit();">
		
			<div class="marginT40"><h3 class="pageTitle marginB5">{#immagProfilo#}</h3></div>
			<div id="modProfImgContainer">
				<img id="immagineProfilo" src="/resize.php?w=90&h=90&f={$user.avatar}" class="left" alt="" />
				<div id="closeIcon_715" style="position:relative; z-index:10000;" class="closeIcon marginL5" onclick="avatarElimina({$user.id_utente});"></div>
			</div>
			
			<div id="modProfImgTools">
				<div class="marginB15 fontS10">{#selNuovaImg#}</div>
				<ul id="lista_file" class="qq-upload-list">
				</ul>
				<div id="file-uploader">       
					<noscript>          
						<p>{#jsOff#}</p>
						<!-- or put a simple form for upload here -->
					</noscript>         
				</div>
			</div>
		
		
			<input id="utenteCitta" type="hidden" name="citta" value="{$user.citta}" />

			<div class="marginT40"><h3 class="pageTitle marginB5">{#cambiaPass#}</h3></div>
			<div class="fontS10 marginB15">{#passMin6#}</div>
			<div class="marginB10">
				<label for="utentePass">{#nuovaPass#}</label><input id="utentePass" type="password" name="utentePass" value="" />
				<span id="controllo_utentePass"></span>
			</div>
			<div class="marginB10">
				<label for="utentePass2">{#nuovaPass2#}</label><input id="utentePass2" type="password" name="utentePass2" value=""  />
				<span id="controllo_utentePass2"></span>
			</div>
			<div class="marginT20 textRight"><input type="submit" value="Salva le modifiche" name="form_profilo_utente1" class="marginR10" /></div>

		</form>
		
{/if}

</div>
<script>

function avatarElimina(id) {

	$.ajax({
	  url: '/ajax/utente_avatar_elimina.php?uid='+id,
	  success: function(data) {
			$('#immagineProfilo').attr('src','/resize.php?w=90&h=90&f='+data);
	  }
	});

}


var uploading = false;

var uploader = new qq.FileUploader({
  // pass the dom node (ex. $(selector)[0] for jQuery users)
  element: document.getElementById('file-uploader'),
  // path to server-side upload script
  action: '/ajax/utente_avatar_upload.php',
  // ex. ['jpg', 'jpeg', 'png', 'gif'] or []
	allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
	multiple: false,
	listElement: document.getElementById('lista_file'),
	onSubmit: function(id, fileName){
		uploading = true;
		$('#lista_file').html('');
	},
	onComplete: function(id, fileName, responseJSON){
		uploading = false;
		//$('#immagineProfilo').attr('src','/resize.php?w=90&h=90&f=/images/avatar/{$user.id_utente}/resized_temp_avatar.jpeg');
		$('#immagineProfilo').attr('src','/resize.php?w=90&h=90&f=/images/avatar/{$user.id_utente}/1.jpeg');
	},
	params: {
		uid: '{$user.id_utente}'
	}
});

function check_submit() {

	if ($('#utentePass').val() == '') return true;
	else if (!ValidateForm_(controlFields, 'submit') || uploading) return false;
	else return true;

}

/*function utente_profilo_modifica() {

	id={$user.id_utente};
	nome=$('#utenteNome').val();
	cognome=$('#utenteCognome').val();
	citta=$('#utenteCitta').val();
	about=$('#utenteAbout').val();

	$.ajax({
	  url: '/ajax/utente_profilo_update.php?id='+id+'&nome='+nome+'&cognome='+cognome+'&citta='+citta+'&about='+about,
	  success: function(data) {
			if (data == "1") {
				//Cosa fare dopo l'aggiornamento del profilo?
			}
	  }
	});

}*/

controlFields=new Array();

/*controlNew = new Array();
controlNew['nome'] = "utenteNome";
controlNew['lenght'] = 1;
controlNew['type'] = 1;
controlFields.push(controlNew);

controlNew = new Array();
controlNew['nome'] = "utenteCognome";
controlNew['lenght'] = 1;
controlNew['type'] = 1;
controlFields.push(controlNew);*/

{if ! isset($smarty.session.fb_session)}
controlNew = new Array();
controlNew['nome'] = "utentePass";
controlNew['nome_esteso'] = "Password";
controlNew['lenght'] = 6;
controlNew['type'] = 1;
controlFields.push(controlNew);

controlNew = new Array();
controlNew['nome'] = "utentePass2";
controlNew['nome_esteso'] = "Conferma password";
controlNew['compare'] = 'utentePass';
controlNew['type'] = 10;
controlFields.push(controlNew);
{/if}

addListeners (controlFields);


</script>

<div class="demo-description" id="modalControlli">
</div>

{include file="includes/footer.tpl"}
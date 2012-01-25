<?php /* Smarty version Smarty-3.0.7, created on 2012-01-25 15:45:40
         compiled from "/var/www/fork.decorourbano.org/templates/includes/header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:20496454514f20159400b189-37000520%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c8366fe5b7cb48d0b113c8fde6496418738456cd' => 
    array (
      0 => '/var/www/fork.decorourbano.org/templates/includes/header.tpl',
      1 => 1327332184,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20496454514f20159400b189-37000520',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//IT" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:og="http://ogp.me/ns#">
<head>
<title><?php echo $_smarty_tpl->getVariable('pageTitle')->value;?>
 - We DU! </title>

<link rel="image_src" type="image/jpeg" href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['url'];?>
images/DU_FB.jpg" />

<?php if ($_smarty_tpl->getVariable('page')->value=="vediProfilo"&&($_smarty_tpl->getVariable('segnalazioni')->value=='null'||$_smarty_tpl->getVariable('user_profile')->value['profilo_pubblico']==0)){?>
<meta name="robots" content="noindex,follow" />
<?php }?>

<meta name="description" content="<?php echo $_smarty_tpl->getVariable('metaDesc')->value;?>
" />

<meta property="og:title" content="<?php echo $_smarty_tpl->getVariable('pageTitle')->value;?>
" />
<meta property="og:type" content="product" />
<meta property="og:url" content="http://<?php echo $_SERVER['SERVER_NAME'];?>
<?php echo $_SERVER['REQUEST_URI'];?>
" />
<?php if ($_smarty_tpl->getVariable('page')->value=="dettaglioSegnalazione"&&$_smarty_tpl->getVariable('segnalazione_valida')->value){?>
<meta property="og:image" content="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['url'];?>
images/segnalazioni/<?php echo $_smarty_tpl->getVariable('segnalazione')->value['tipo_nome_url'];?>
-<?php echo $_smarty_tpl->getVariable('segnalazione')->value['citta_url'];?>
-<?php echo $_smarty_tpl->getVariable('segnalazione')->value['indirizzo_url'];?>
-<?php echo $_smarty_tpl->getVariable('user_profile')->value['id_utente'];?>
-<?php echo $_smarty_tpl->getVariable('ids')->value;?>
-0-0.jpg" />
<?php }else{ ?>
<meta property="og:image" content="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['url'];?>
images/DU_FB.jpg" />
<?php }?>
<meta property="og:description" content="Utilizza anche tu Decoro Urbano, lo strumento gratuito per la segnalazione del degrado via smartphone e PC. La cittadinanza attiva comincia da te!" />
<meta property="og:site_name" content="Decoro Urbano<?php echo $_smarty_tpl->getVariable('siteNameTail')->value;?>
" />
<meta property="fb:app_id" content="<?php echo $_smarty_tpl->getVariable('settings')->value['facebook']['app_id'];?>
" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="it" />



<link rel="icon" href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['url'];?>
/images/favicon.png" />

<?php if ($_smarty_tpl->getVariable('page')->value=="prehome"){?> 
<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['url'];?>
css/prehome.css" />
<?php }?>

<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['url'];?>
css/global.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['url'];?>
css/globalClass.css" />

<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['url'];?>
css/leftSideBar.css" />

<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['url'];?>
css/fileuploader.css" />	
<link href='http://fonts.googleapis.com/css?family=Nunito&subset=latin&v2' rel='stylesheet' type='text/css'>

<link type="text/css" href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['url'];?>
css/jqueryui/jquery-ui-1.8.12.custom.css" rel="stylesheet" />	
<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['url'];?>
js/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['url'];?>
js/jquery-ui-1.8.12.custom.min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['url'];?>
js/jquery.json-2.2.min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['url'];?>
js/jquery.ThreeDots.min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['url'];?>
js/fileuploader.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['url'];?>
js/funzioni.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['url'];?>
js/Date.extend.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['url'];?>
js/Array.extend.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['url'];?>
js/controlli.js"></script>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?v=3.2&sensor=false&language=it"></script>

<script type="text/javascript">

	function check_fb_status_PHP_JS() {

		FB.getLoginStatus(function(response) {
			if (!response.authResponse) {
				//alert('Discrepanza.');
				if ('<?php echo $_smarty_tpl->getVariable('fb_user')->value;?>
' != '') location.href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['logout'];?>
";
			}
		});
	}

	function check_fb_status() {

		FB.getLoginStatus(function(response) {
			if (response.authResponse) {
				<?php if (!$_smarty_tpl->getVariable('utente_fb_eliminato')->value){?>
				window.location.reload();
				<?php }?>
			}
		});
	}
	
	function show_login_dialog() {
	
		$('#loginForm').dialog({
			width:300,
			modal: true,
			draggable:false,
			resizable:false,
			dialogClass: 'loginDialog',
			buttons: {
				Ok: function() {
					$( this ).dialog( "close" );
				}
			}
		});
	
	}

</script>

<!--[if IE]>
<link type="text/css" href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['url'];?>
css/ie.css" rel="stylesheet" />	
<![endif]-->


</head>
	
<body>

	<?php if ($_smarty_tpl->getVariable('page')->value=="dettaglioSegnalazione"&&$_smarty_tpl->getVariable('segnalazione_valida')->value){?>
	<img src="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['url'];?>
images/segnalazioni/<?php echo $_smarty_tpl->getVariable('segnalazione')->value['tipo_nome_url'];?>
-<?php echo $_smarty_tpl->getVariable('segnalazione')->value['citta_url'];?>
-<?php echo $_smarty_tpl->getVariable('segnalazione')->value['indirizzo_url'];?>
-<?php echo $_smarty_tpl->getVariable('user_profile')->value['id_utente'];?>
-<?php echo $_smarty_tpl->getVariable('ids')->value;?>
-0-0.jpg" style="display:none;" />
	<?php }else{ ?>
	<img src="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['url'];?>
images/DU_FB.jpg" style="display:none;" />
	<?php }?>
	
<!--
	<div style="position:fixed; 
							cursor:pointer;
							z-index:0;
							top:75px; 
							float:none; 
							width:100%; 
							height:228px; 
							background:url(<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['url'];?>
images/topNews_openSource.png) center top no-repeat;
							" onClick= "window.open('<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['url'];?>
blog/2011/10/open-du-apri-tutto/');"></div>
              -->
							
	<div class="marginT5 fOrange" id="msgErrore" style="display:none;"></div>

	<div id="header">
		<div class="container" id="banner" > 
			
				<?php if (!isset($_smarty_tpl->getVariable('user',null,true,false)->value)){?>

				<div class="auto left"><a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['prehome'];?>
" style="float:left;"><img src="/images/logo.png" id="logo" alt="<?php echo $_smarty_tpl->getConfigVariable('logoAlt');?>
" /></a></div>
				<div id="topBox">
					<div id="ricercaVeloce">
						<form id="ricercaVeloceForm" method="post" action="" onsubmit="return checkGeocode();">
							<div id="ricercaVeloceInput">
								<input type="text" id="ricercaVeloceField" name="ricerca" value="" placeholder="Cerca una città o un indirizzo" />
								<input type="hidden" id="ricercaVeloceType" name="type" value=""/>						
								<input type="hidden" id="ricercaVeloceLat" name="lat" value=""/>
								<input type="hidden" id="ricercaVeloceLng" name="lng" value=""/>
								<input type="submit" value=""   />
							</div>
						</form>
					</div>
					
					<div id="loginForm" style="display:none;" class="skinnedForm">
						<div>
							<h4 class="fontS18 fGreen margin0 auto left"><?php echo $_smarty_tpl->getConfigVariable('duAccedi');?>
</h4>
							<div class="closeIcon marginT5 right" onclick="$('#loginForm').dialog('close');"></div>
						</div>
						<p class="fontS16 marginT10 marginB5 fBold"><?php echo $_smarty_tpl->getConfigVariable('accediConFb');?>
</p>
						<fb:login-button scope="<?php echo $_smarty_tpl->getVariable('settings')->value['facebook']['perms'];?>
" show-faces="false" max-rows="1" onlogin="check_fb_status();" class="left" autologoutlink="false">
							<?php echo $_smarty_tpl->getConfigVariable('fbAccedi');?>

						</fb:login-button> <span class="marginL10 fontS16"><?php echo $_smarty_tpl->getConfigVariable('consigliato');?>
</span>
						<div class="normalLogin">
						<p class="fontS16 margin0 marginB10 fBold"><?php echo $_smarty_tpl->getConfigVariable('accediConDU');?>
</p>
							<div style="width:340px;">
								<form method="post">
									<div>
										<input type="text" name="email" value="<?php if (isset($_smarty_tpl->getVariable('cookie',null,true,false)->value)){?><?php echo $_smarty_tpl->getVariable('cookie')->value['user_email'];?>
<?php }?>" placeholder="email" class="marginB5" /> 
										<input type="password" name="password" value="<?php if (isset($_smarty_tpl->getVariable('cookie',null,true,false)->value)){?><?php echo $_smarty_tpl->getVariable('cookie')->value['user_password'];?>
<?php }?>" placeholder="password" /> 
									</div>
									<div class="textLeft marginT10"> 
										<input type="checkbox" name="restaCollegato" id="restaCollegato" style="height:10px;" checked="checked"/> <label class="fontS10" for="restaCollegato"><?php echo $_smarty_tpl->getConfigVariable('restCollegato');?>
</label> 
										<span class="fontS12 right"><a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['passDimenticata'];?>
"><?php echo $_smarty_tpl->getConfigVariable('passDimenticata');?>
</a></span>
									</div>
									<div class="marginB5 marginT10"><input type="submit" name="login_form" value="<?php echo $_smarty_tpl->getConfigVariable('accedi');?>
" class="right" /></div>
								</form>
							</div>
							<div style="width:200px" class="right fontS12">
								<div class="loginRegister">
									<div class="fontS14 fBold"><?php echo $_smarty_tpl->getConfigVariable('nonDuUser');?>
</div>
									<div class="marginT10 fontS16 fBold"><a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['registrati'];?>
" class="tdNone"><?php echo $_smarty_tpl->getConfigVariable('registrati');?>
</a> <span class="fontS12"><?php echo $_smarty_tpl->getConfigVariable('gratis');?>
</span></div>
								</div>
							</div>
						</div>
					</div>
					
					<div id="login" style="background:none !important;">
						<div class="fontS12" style="background:none !important;">
							 <a href="javascript:show_login_dialog();" style="background:none !important;">Entra</a> <span class="fGreen">|</span> 
							 <a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['registrati'];?>
" style="background:none !important;"><?php echo $_smarty_tpl->getConfigVariable('registrati');?>
</a> <span class="fGreen">|</span> 
							 
							
								
								<fb:login-button scope="<?php echo $_smarty_tpl->getVariable('settings')->value['facebook']['perms'];?>
" style="width:72px; height:16px;  margin-top:-3px;" show-faces="false" width="200" max-rows="1" onlogin="check_fb_status();" autologoutlink="false"></fb:login-button>
								
								<div id="fb-root"></div>
			       	<script src="http://connect.facebook.net/it_IT/all.js" type="text/javascript"></script>
								<script>
									window.fbAsyncInit = function() {
									
										FB.init({
											appId  : '<?php echo $_smarty_tpl->getVariable('settings')->value['facebook']['app_id'];?>
',
											status : true, // check login status
											cookie : true, // enable cookies to allow the server to access the session
											xfbml  : true, // parse XFBML
											oauth  : true
										});
										
										check_fb_status();
										
									};
								
								</script>
								
						</div>
					</div>
						
						<?php if ($_smarty_tpl->getVariable('errore_login')->value==1){?>
						
						<script>
						
							$('#msgErrore').html('<?php echo $_smarty_tpl->getConfigVariable('loginMexErrore');?>
');
						
							$('#msgErrore').dialog({
								height: 200,
								width:300,
								modal: true,
								draggable:false,
								resizable:false,
								buttons: {
									Ok: function() {
										$( this ).dialog( "close" );
									}
								}
							});
						
						</script>
						
						<?php }?>
					
				</div>
				<?php }else{ ?>
				
			  <div id="fb-root"></div>
       	<script src="http://connect.facebook.net/it_IT/all.js" type="text/javascript"></script>
       	
       	<script>
       	  window.fbAsyncInit = function() {
						FB.init({
							appId  : '<?php echo $_smarty_tpl->getVariable('settings')->value['facebook']['app_id'];?>
',
							status : true, // check login status
							cookie : true, // enable cookies to allow the server to access the session
							xfbml  : true, // parse XFBML
							oauth  : true
						});
						
						check_fb_status_PHP_JS();
						
       		};
       	</script>
				
				<div class="auto left"><a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['principale'];?>
" style="float:left;"><img src="/images/logo.png" id="logo" alt="<?php echo $_smarty_tpl->getConfigVariable('logoAlt');?>
" /></a></div>
				<div id="topBox">
					<div id="ricercaVeloce">
						<form id="ricercaVeloceForm" method="post" action="" onsubmit="return checkGeocode();">
							<div id="ricercaVeloceInput">
								<input type="text" id="ricercaVeloceField" name="ricerca" value="" placeholder="Cerca una città o un indirizzo" />
								<input type="hidden" id="ricercaVeloceType" name="type" value=""/>						
								<input type="hidden" id="ricercaVeloceLat" name="lat" value=""/>
								<input type="hidden" id="ricercaVeloceLng" name="lng" value=""/>
								<input type="submit" value=""   />
							</div>
						</form>
					</div>
					<div id="logout">
						<span class="fontS10"><?php echo $_smarty_tpl->getConfigVariable('bentornato');?>
 <span class="fBold fontS12 paddX5"><?php echo $_smarty_tpl->getVariable('user')->value['nome'];?>
</span></span>
					</div>
				</div>
			<?php }?>
				

	
				
				<script>
				
				function checkGeocode() {
				
					if ($('#ricercaVeloceLat').val() == '' || $('#ricercaVeloceLng').val() == '') {
					
						/*$('#msgErrore').html("<?php echo $_smarty_tpl->getConfigVariable('ricercaMexErrore');?>
");
					
						$('#msgErrore').dialog({
							height: 200,
							width:300,
							modal: true,
							draggable:false,
							resizable:false,
							buttons: {
								Ok: function() {
									$( this ).dialog( "close" );
								}
							}
						});*/
					
						return false;
					} else return true;
				
				}
				
				var geocoderAutocomplete = new google.maps.Geocoder();
				var res;

				$("#ricercaVeloceField").autocomplete({
		      //This bit uses the geocoder to fetch address values
		      source: function(request, response) {
		        geocoderAutocomplete.geocode( { 'address': request.term + ' Italia'  }, function(results, status) {
		        
		        	if (results.length) {
		        	
		        		// Ho risultati validi, vedo se è una regione o altro
								res = decodeLocationField(results[0]['address_components']);
								
								//alert(results[0]['types'].contains('administrative_area_level_1'));

								if (array_contains(results[0]['types'], 'administrative_area_level_1')) { // Ho una regione
									locality = res['administrative_area_level_1'].long_name;
		          	} else {
		          		locality = res['locality'].long_name;
		          	}
		          	
		          	$("#ricercaVeloceForm").attr("action", "http://"+slug(locality)+".<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['dominio'];?>
/");
			        	$('#ricercaVeloceLat').val(results[0].geometry.location.lat());
				        $('#ricercaVeloceLng').val(results[0].geometry.location.lng());
				        $('#ricercaVeloceType').val(results[0]['types'][0]);				        
							
							} else {
							
								// Non ho risultati validi (bloccare il submit sarebbe meglio)
								$("#ricercaVeloceForm").attr("action", "");
			        	$('#ricercaVeloceLat').val('');
				        $('#ricercaVeloceLng').val('');
				        $('#ricercaVeloceType').val('');				        
							
							}

		          response(jQuery.map(results, function(item) {
		          
		          	//alert(dump(item['types']));
								
								res = decodeLocationField(item['address_components']);

								if (array_contains(item['types'], 'administrative_area_level_1')) { // Regione
									locality = res['administrative_area_level_1'].long_name;
		          	} else if (array_contains(item['types'], 'locality')) { // Comune
		          		locality = res['locality'].long_name;
		          	} else { // Altro
									locality = res['locality'].long_name;
								}
		          	
		          	//$("#ricercaVeloceForm").attr("action", "http://"+slug(locality)+".<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['dominio'];?>
/");
			        	//$('#ricercaVeloceLat').val(item.geometry.location.lat());
				        //$('#ricercaVeloceLng').val(item.geometry.location.lng());
		          	
		            return {
		              label:  item.formatted_address,
		              value: item.formatted_address,
		              latitude: item.geometry.location.lat(),
		              longitude: item.geometry.location.lng(),
		              locality: locality,
		              type: item['types'][0]		              
		            }
		          }));

		        })
		      },
		      //This bit is executed upon selection of an address
		      select: function(event, ui) {
		        $('#ricercaVeloceLat').val(ui.item.latitude);
		        $('#ricercaVeloceLng').val(ui.item.longitude);
		        $('#ricercaVeloceType').val(ui.item.type);		        
		        $("#ricercaVeloceForm").attr("action", "http://"+slug(ui.item.locality)+".<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['dominio'];?>
/");
		        $('#ricercaVeloceForm').submit();
		      }
		    });
				</script>
				
		 </div>
	</div>
	
	<?php if ($_smarty_tpl->getVariable('page')->value=='inviaSegnalazione'){?>
	<div id="loadingInviaSegnalazione" class="loadingMask" style="display:none;" >
		<div class="loadingPopUp">
			<img src="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['url'];?>
images/loading.gif" alt="Caricamento...."  /><br /><br />
			<span class="fontS20 marginT10"><?php echo $_smarty_tpl->getConfigVariable('caricamento');?>
...</span>
		</div>
	</div>
	<?php }?>
	
	<div class="container" style="position:relative; z-index:10;">
		<?php if ($_smarty_tpl->getVariable('page')->value!='prehome'&&$_smarty_tpl->getVariable('page')->value!='prehome2'){?>
        <div id="body">
        	<div id="leftSideBar">
              <?php $_template = new Smarty_Internal_Template("includes/UI.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
          </div>
            <div id="rightBody">
		<?php }?>

<?php /* Smarty version Smarty-3.0.7, created on 2012-01-25 15:45:39
         compiled from "/var/www/fork.decorourbano.org/templates/principale.tpl" */ ?>
<?php /*%%SmartyHeaderCode:14427349684f201593c3a3d5-85186509%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0a9df21089daaef3962143bd466a773fef13ede8' => 
    array (
      0 => '/var/www/fork.decorourbano.org/templates/principale.tpl',
      1 => 1327499296,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14427349684f201593c3a3d5-85186509',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_modifier_truncate')) include '/var/www/fork.decorourbano.org/include/smarty_3.0.7/libs/plugins/modifier.truncate.php';
?>

<?php $_template = new Smarty_Internal_Template("includes/header.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>

<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['url'];?>
js/jquery.autoellipsis-1.0.2.min.js"></script>

<div id="debug"></div>

<script>
var settings_limit_giorni=<?php echo $_smarty_tpl->getVariable('settings')->value['segnalazioni']['limit_giorni'];?>
;
var json_ultime_segnalazioni='<?php echo $_smarty_tpl->getVariable('json_ultime_segnalazioni')->value;?>
';
var json_segnalazioni='<?php echo $_smarty_tpl->getVariable('json_segnalazioni')->value;?>
';
var settings_limit_numero=<?php echo $_smarty_tpl->getVariable('settings')->value['segnalazioni']['limit_numero'];?>
;

// Inizializzazioni elenco
var segnalazioni=[];
var segnalazioni_nuove=[];
var segnalazioni_vecchie=[];
var numero_nuove_segnalazioni = 0;
var newest = 0;
var oldest = 0;
var lock = 1;
var sito_url = '<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['url'];?>
';

function segnalazioni_first_load() {
	
	var segnalazioni_obj = jQuery.secureEvalJSON(json_segnalazioni);

	newest = segnalazioni_obj[0].last_edit;
	oldest = segnalazioni_obj[segnalazioni_obj.length-1].last_edit;
	segsel = segnalazioni_obj[0].id_segnalazione;
	
	if (segnalazioni_obj.length) $('#boxWallSegnalazioni').show();

	for (i in segnalazioni_obj) {
		if (typeof segnalazioni_obj[i] == 'object') {
			segnalazioni[segnalazioni_obj[i].id_segnalazione] = segnalazioni_obj[i];
			aggiungi_segnalazione_lista('append', segnalazioni_obj[i]);
		}
	}

	$('.segnalazione_titolo').ellipsis();

	if (segnalazioni_obj.length == settings_limit_numero) {
		segnalazioniVecchieLoad = '<div id="segnalazioniVecchieLoad" class="marginB10" onclick="segnalazioni_vecchie_get();">Segnalazioni precedenti</div>';
		$('#boxWallSegnalazioni').append(segnalazioniVecchieLoad);
	}
	
	lock = 0;

}


function aggiungi_segnalazione_lista(posizione, segnalazione) {

	var stato = '';

	if (segnalazione['stato'] >= 300)
		stato = 'Risolta';
  else if (segnalazione['stato'] >= 200)
	  stato = 'In carico';
  else
	  stato = 'In attesa';

		segnalazioneListaHTML='\
				<div id="segnalazione_'+segnalazione.id_segnalazione+'" class="ultimeSegnalazioni borderBDashed" onclick="location.href=\'<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['url'];?>
'+segnalazione.tipo_nome_url+'/'+segnalazione.citta_url+'/'+segnalazione.indirizzo_url+'/'+segnalazione.id_segnalazione+'/\'">\
					<div class="leftAvatar">\
						<a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['vediProfilo'];?>
?idu='+segnalazione.id_utente+'"><img src="/resize.php?w=30&h=30&f='+segnalazione.avatar+'" alt="'+segnalazione.nome+' '+segnalazione.cognome+'" /></a>\
					</div>\
					<div class="rightContents">\
						<img src="'+segnalazione.foto_base_url+'85-55.jpg" class="marginL5 right" />\
						<a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['vediProfilo'];?>
?idu='+segnalazione.id_utente+'" class="tdNone">\
							<span class="fBold fontS12">'+segnalazione.nome+' '+segnalazione.cognome+'</span>\
						</a>\
						<span class="fBold fontS12">'+relativeTime(segnalazione.data)+'</span><br />\
						<div style="width:230px;"><span class="fontS14 fGeorgia segnalazione_titolo">'+segnalazione.messaggio+'</span></div>\
						<div class="auto fontS10 fGreen" style="margin-top:-1px;"> '+segnalazione.citta+' - '+segnalazione.indirizzo+' '+segnalazione.civico+'</div>';
		if (segnalazione.client == 'iPhone') segnalazioneListaHTML+='<div class="auto fontS10" style="clear:left;">via <a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['applicazioni'];?>
">iPhone</a></div>';
		if (segnalazione.client == 'Android') segnalazioneListaHTML+='<div class="auto fontS10" style="clear:left;">via <a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['applicazioni'];?>
">Android</a></div>';
		segnalazioneListaHTML+='\
					</div>\
				</div>\
		';

	if (posizione == 'append') $('#boxWallSegnalazioni').append(segnalazioneListaHTML);
	else if (posizione == 'prepend') $('#boxWallSegnalazioni').prepend(segnalazioneListaHTML);


}

function segnalazioni_nuove_get() {

	if (!lock) {
		lock = 1;

		aggiorna_date();
	

		//newest = 1306078360;

		$.ajax({
			url: '/ajax/segnalazioni_get.php?idu=<?php echo $_smarty_tpl->getVariable('user')->value['id_utente'];?>
&t_newer='+newest+'&c=1&w=1',
			dataType: "json",
			success: function(seg) {
				if (seg) {
					for (i in seg) {
						segnalazioni_nuove = seg;
					}
					numero_nuove_segnalazioni = seg.length;
					mostra_nuove_contatore(numero_nuove_segnalazioni);
				}
			},
			complete: function(seg) {
				lock = 0;
			}
		});
	}

}

function mostra_nuove_contatore(n) {

	if ($('#segnalazioniNuoveContatore').length == 0) {

		segnalazioniNuoveContatore = '<div id="segnalazioniNuoveContatore" class="" style="display:none;">&nbsp;</div>';

		$('#boxWallSegnalazioni').prepend(segnalazioniNuoveContatore);
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
	//$('#segnalazioniLista').prepend(segnalazioniNuoveDivisore);
	
	newest = segnalazioni_nuove[0].last_edit;
	segnalazioni_nuove.reverse();

	for (i in segnalazioni_nuove) {
		if (typeof segnalazioni_nuove[i] == 'object') {
			segnalazioni[segnalazioni_nuove[i].id_segnalazione] = segnalazioni_nuove[i];
			aggiungi_segnalazione_lista('prepend', segnalazioni_nuove[i]);
			//$("#segnalazione_"+segnalazioni_nuove[i].id_segnalazione).addClass('segnListaBoxNew');
		}
	}

	segnalazioni_nuove=[];
	numero_nuove_segnalazioni = 0;

}

function segnalazioni_vecchie_get() {

	if (!lock) {
		lock = 1;

		$('#segnalazioniVecchieLoad').remove();
		aggiorna_date();
	
		//newest = 0;

		$.ajax({

			url: '/ajax/segnalazioni_get.php?idu=<?php echo $_smarty_tpl->getVariable('user')->value['id_utente'];?>
&t_old='+oldest+'&l='+settings_limit_numero+'&c=1&w=1',

			dataType: "json",
			success: function(seg) {

				if (seg) {
					for (i in seg) {
						segnalazioni_vecchie = seg;
					}
					mostra_vecchie();
					//$('.segnalazione_titolo').ThreeDots({ max_rows:1 });
					$('.segnalazione_titolo').ellipsis();
				}
			},
			complete: function(seg) {
				lock = 0;
			}
		});
	}
}

function mostra_vecchie() {

	aggiorna_date();
	
	//alert(segnalazioni_vecchie.length);
	oldest = segnalazioni_vecchie[segnalazioni_vecchie.length-1].last_edit;
	if (!newest) newest = segnalazioni_vecchie[0].last_edit;

	for (i in segnalazioni_vecchie) {
		if (typeof segnalazioni_vecchie[i] == 'object') {
			segnalazioni[segnalazioni_vecchie[i].id_segnalazione] = segnalazioni_vecchie[i];
			aggiungi_segnalazione_lista('append', segnalazioni_vecchie[i]);
		}
	}

	if (segnalazioni_vecchie.length == settings_limit_numero) {
		segnalazioniVecchieLoad = '<div id="segnalazioniVecchieLoad" class="marginB10" onclick="segnalazioni_vecchie_get();">Segnalazioni precedenti</div>';
		$('#boxWallSegnalazioni').append(segnalazioniVecchieLoad);
	}
	
	segnalazioni_vecchie=[];

}

function aggiorna_date() {

	for (i in segnalazioni) {
		$('#segnalazione_data_'+segnalazioni[i].id_segnalazione).html(relativeTime(segnalazioni[i].data));
		
		for (j in segnalazioni[i].commenti) {
			$('#commento_data_'+segnalazioni[i].id_segnalazione+'_'+segnalazioni[i].commenti[j].id_commento).html(relativeTime(segnalazioni[i].commenti[j].data));
		}
		
	}

}


window.onload=function() {
	segnalazioni_first_load();
	interval = setInterval ( "segnalazioni_nuove_get()", 300000);
}
	
	
</script>

<div class="rightPageHeader">
		<div class="rightHeadText"><h3 class="fontS18 marginB5 fBrown"><?php echo $_smarty_tpl->getConfigVariable('recenti');?>
</h3>
			<?php echo $_smarty_tpl->getConfigVariable('benvenuto2');?>

		</div>
</div>

<div id="segnalazioniStream" class="marginT15">
			
			<div class="principaleBox">
				<div class="title" id="boxNuoveSegnalazioni">Nuove segnalazioni</div>
				<div id="boxNuoveSegnalazioni">
				<?php  $_smarty_tpl->tpl_vars['segnalazione'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('ultime_segnalazioni')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['segnalazione']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['segnalazione']->iteration=0;
if ($_smarty_tpl->tpl_vars['segnalazione']->total > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['segnalazione']->key => $_smarty_tpl->tpl_vars['segnalazione']->value){
 $_smarty_tpl->tpl_vars['segnalazione']->iteration++;
 $_smarty_tpl->tpl_vars['segnalazione']->last = $_smarty_tpl->tpl_vars['segnalazione']->iteration === $_smarty_tpl->tpl_vars['segnalazione']->total;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']["nuovaSegnalazione"]['last'] = $_smarty_tpl->tpl_vars['segnalazione']->last;
?>

				<div id="segnalazione_<?php echo $_smarty_tpl->tpl_vars['segnalazione']->value['id_segnalazione'];?>
" class="ultimeSegnalazioni <?php if (!$_smarty_tpl->getVariable('smarty')->value['foreach']['nuovaSegnalazione']['last']){?>borderBDashed<?php }?>" onclick="location.href='<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['url'];?>
<?php echo $_smarty_tpl->tpl_vars['segnalazione']->value['tipo_nome_url'];?>
/<?php echo $_smarty_tpl->tpl_vars['segnalazione']->value['citta_url'];?>
/<?php echo $_smarty_tpl->tpl_vars['segnalazione']->value['indirizzo_url'];?>
/<?php echo $_smarty_tpl->tpl_vars['segnalazione']->value['id_segnalazione'];?>
/'">
					<div class="leftAvatar">
						<a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['vediProfilo'];?>
?idu=<?php echo $_smarty_tpl->tpl_vars['segnalazione']->value['id_utente'];?>
"><img src="/resize.php?w=30&h=30&f=<?php echo $_smarty_tpl->tpl_vars['segnalazione']->value['avatar'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['segnalazione']->value['nome'];?>
 <?php echo $_smarty_tpl->tpl_vars['segnalazione']->value['cognome'];?>
" /></a>
					</div>
					<div class="rightContents">
						<img src="<?php echo $_smarty_tpl->tpl_vars['segnalazione']->value['foto_base_url'];?>
85-55.jpg" class="marginL5 right" />
						<a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['vediProfilo'];?>
?idu=<?php echo $_smarty_tpl->tpl_vars['segnalazione']->value['id_utente'];?>
" class="tdNone"><span class="fBold fontS12">
							<?php echo $_smarty_tpl->tpl_vars['segnalazione']->value['nome'];?>
 <?php echo $_smarty_tpl->tpl_vars['segnalazione']->value['cognome'];?>
</span>
						</a>
						<span class="fBold fontS12"><?php echo ConvertitoreData_UNIXTIMESTAMP_IT($_smarty_tpl->tpl_vars['segnalazione']->value['data']);?>
</span><br />
						<span class="fontS14 fGeorgia"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['segnalazione']->value['messaggio'],37,"...");?>
</span><br />
						<div class="auto fontS10 fGreen" style="margin-top:-1px;"> <?php echo $_smarty_tpl->tpl_vars['segnalazione']->value['citta'];?>
 - <?php echo $_smarty_tpl->tpl_vars['segnalazione']->value['indirizzo'];?>
 <?php echo $_smarty_tpl->tpl_vars['segnalazione']->value['civico'];?>
</div>
						<?php if ($_smarty_tpl->tpl_vars['segnalazione']->value['client']=='iPhone'){?><div class="auto fontS10 clear"  style="clear:left;">via <a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['applicazioni'];?>
">iPhone</a></div><?php }?>
						<?php if ($_smarty_tpl->tpl_vars['segnalazione']->value['client']=='Android'){?><div class="auto fontS10 clear"  style="clear:left;">via <a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['applicazioni'];?>
">Android</a></div><?php }?>
					</div>
				</div>
				
				<?php }} ?>
				</div>

			</div>
			
			<div class="principaleBox marginT20" id="boxWallSegnalazioni" style="display:none;">
				<div class="title">Wall segnalazioni</div>
				<div id="boxWallSegnalazioni">
				</div>

			</div>

</div>

<div id="socialStream" class="marginT20">
	<h5>News da Facebook</h5>
	<iframe src="http://www.facebook.com/plugins/activity.php?site=<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['dominio'];?>
&amp;width=246&amp;height=270&amp;header=false&amp;colorscheme=light&amp;font&amp;border_color&amp;recommendations=false" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:246px; height:270px; margin-bottom:15px;" allowTransparency="true"></iframe>
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
		
	<div class="right marginT20">
		<h5><img src="../images/prehome/newSegnalatori.png" alt="" class="marginR5 left"> Nuovi Segnalatori</h5>
		<?php  $_smarty_tpl->tpl_vars['utente'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('nuovi_utenti')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['utente']->key => $_smarty_tpl->tpl_vars['utente']->value){
?>
			<a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['vediProfilo'];?>
?idu=<?php echo $_smarty_tpl->tpl_vars['utente']->value['id_utente'];?>
"><img src="/resize.php?w=41&h=41&f=<?php echo $_smarty_tpl->tpl_vars['utente']->value['avatar'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['utente']->value['nome'];?>
 <?php echo $_smarty_tpl->tpl_vars['utente']->value['cognome'];?>
" class="left" /></a>
		<?php }} ?>
	</div>
	
	<div class="right marginT20">
		<h5><img src="../images/prehome/topSegnalatori.png" alt="" class="marginR5 left"> Top Segnalatori</h5>
		<div class="bottomBoxBody">
		
			<?php  $_smarty_tpl->tpl_vars['segnalatore'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('segnalatori_top')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['segnalatore']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['segnalatore']->iteration=0;
 $_smarty_tpl->tpl_vars['segnalatore']->index=-1;
if ($_smarty_tpl->tpl_vars['segnalatore']->total > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['segnalatore']->key => $_smarty_tpl->tpl_vars['segnalatore']->value){
 $_smarty_tpl->tpl_vars['segnalatore']->iteration++;
 $_smarty_tpl->tpl_vars['segnalatore']->index++;
 $_smarty_tpl->tpl_vars['segnalatore']->first = $_smarty_tpl->tpl_vars['segnalatore']->index === 0;
 $_smarty_tpl->tpl_vars['segnalatore']->last = $_smarty_tpl->tpl_vars['segnalatore']->iteration === $_smarty_tpl->tpl_vars['segnalatore']->total;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['segnalatori']['first'] = $_smarty_tpl->tpl_vars['segnalatore']->first;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['segnalatori']['last'] = $_smarty_tpl->tpl_vars['segnalatore']->last;
?>

			<div class="bottomBoxSegnalazione <?php if (!$_smarty_tpl->getVariable('smarty')->value['foreach']['segnalatori']['last']){?>borderBDashed marginB10<?php }?> <?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['segnalatori']['first']){?>marginT10<?php }?>">
				<a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['vediProfilo'];?>
?idu=<?php echo $_smarty_tpl->tpl_vars['segnalatore']->value['id_utente'];?>
"><img src="/resize.php?w=30&h=30&f=<?php echo $_smarty_tpl->tpl_vars['segnalatore']->value['avatar'];?>
" class="left marginL5" /></a>
				<div class="bottomBoxSegnInfos">
					<a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['vediProfilo'];?>
?idu=<?php echo $_smarty_tpl->tpl_vars['segnalatore']->value['id_utente'];?>
" class="tdNone">
						<div class="fontS14 fBold"><?php echo $_smarty_tpl->tpl_vars['segnalatore']->value['nome'];?>
 <?php echo $_smarty_tpl->tpl_vars['segnalatore']->value['cognome'];?>
</div>
					</a>
					<div class="fontS10"><?php echo $_smarty_tpl->tpl_vars['segnalatore']->value['n_segnalazioni'];?>
 Segnalazioni</div>
					<div class="textRight fontS10"><?php echo $_smarty_tpl->tpl_vars['segnalatore']->value['citta'];?>
</div>
				</div>
			</div>

			<?php }} ?>
		</div>
	</div>
		
</div>



<div id="segnalatoriStream"></div>

		
		
</div>


<?php $_template = new Smarty_Internal_Template("includes/footer.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<?php /* Smarty version Smarty-3.0.7, created on 2012-01-25 15:45:40
         compiled from "/var/www/fork.decorourbano.org/templates/includes/UI.tpl" */ ?>
<?php /*%%SmartyHeaderCode:15565959354f2015943e4b35-92883124%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4c55e0e988922ef85c45d74c9a4ed2c8fa429a00' => 
    array (
      0 => '/var/www/fork.decorourbano.org/templates/includes/UI.tpl',
      1 => 1327332617,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15565959354f2015943e4b35-92883124',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>


<div class="leftBlockBorder">
    <div>
			<?php if (isset($_smarty_tpl->getVariable('user',null,true,false)->value)){?>
        <div id="UIThumb">
            <a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['prehome'];?>
"><img src="/resize.php?w=60&h=60&f=<?php echo $_smarty_tpl->getVariable('user')->value['avatar'];?>
" alt="" /></a>
        </div>
        <div id="UIData">
            <h3><a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['prehome'];?>
"><?php echo $_smarty_tpl->getVariable('user')->value['nome'];?>
 <?php echo $_smarty_tpl->getVariable('user')->value['cognome'];?>
</a></h3>
            <ul class="UINavi">
                <li><a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['modificaProfilo'];?>
" <?php if ($_smarty_tpl->getVariable('page')->value=='modificaProfilo'){?>class="fBold"<?php }?>><?php echo $_smarty_tpl->getConfigVariable('modificaP');?>
</a></li>
                <li><a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['impostazioni'];?>
" <?php if ($_smarty_tpl->getVariable('page')->value=='impostazioni'){?>class="fBold"<?php }?>><?php echo $_smarty_tpl->getConfigVariable('impostazioni');?>
</a></li>
								<li><a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['logout'];?>
"><?php echo $_smarty_tpl->getConfigVariable('esci');?>
</a></li>
             </ul>
        </div>
				<?php }else{ ?>
				 <div id="UIThumb">
         	<img src="/resize.php?w=60&h=60&f=/images/avatarGuest.png" alt="" />
         </div>
         <div id="UIData">
         	<h3><?php echo $_smarty_tpl->getConfigVariable('utenteOspite');?>
</h3>
          <ul class="UINavi">
          	<li><a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['registrati'];?>
" <?php if ($_smarty_tpl->getVariable('page')->value=='registrati'){?>class="fBold"<?php }?>><?php echo $_smarty_tpl->getConfigVariable('registrati');?>
!</a></li>
            <li><a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['passDimenticata'];?>
" <?php if ($_smarty_tpl->getVariable('page')->value=='passDimenticata'){?>class="fBold"<?php }?>><?php echo $_smarty_tpl->getConfigVariable('passDimenticata');?>
</a></li>
          </ul>
         </div>	
				<?php }?>
    </div>
		<?php if (isset($_smarty_tpl->getVariable('user',null,true,false)->value)){?>
    <div id="UIControls" class="marginT5">
        <ul class="UINavi">
            <li><span class="fBrown"><?php echo $_smarty_tpl->getVariable('user')->value['n_segnalazioni'];?>
</span> <?php echo $_smarty_tpl->getConfigVariable('segnalazioni');?>
</li>
            <li><span class="fBrown"><?php echo $_smarty_tpl->getVariable('user')->value['n_segnalazioni_quotidiane'];?>
</span> <?php echo $_smarty_tpl->getConfigVariable('segnalazioniQ');?>
</li>
            <li><?php echo $_smarty_tpl->getConfigVariable('utenteDal');?>
 <?php echo ConvertitoreData_UNIXTIMESTAMP_IT($_smarty_tpl->getVariable('user')->value['data']);?>
</li>
        </ul>	
    </div>
		<?php }else{ ?>
		<div class="fontS10 marginT10"><?php echo $_smarty_tpl->getConfigVariable('accessLimitato');?>
</div>
		<?php }?>
</div>
<div class="leftBlockBorder">
	<a href="<?php echo $_smarty_tpl->getVariable('settings')->value['docs']['presentazione'];?>
"><img src="/images/buttonComuniAttivi.png" alt="" style="position:relative; left:-58px;" /></a>
	oggi <span class="fOrange fBold fontS24"><?php echo number_format($_smarty_tpl->getVariable('abitanti_attivi')->value,0,",",".");?>
 cittadini</span><br /> 
	possono segnalare il degrado nei<br /> 
	<a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['comuniAttivi'];?>
"><span class="fOrange fUppercase fBold fUnderline">comuni attivi</span></a>
</div>
<div class="leftBlockBorder">
    <ul class="naviLeft">
        <li class="title"><?php echo $_smarty_tpl->getConfigVariable('bigSegnalazioni');?>
</li>
				<?php if (isset($_smarty_tpl->getVariable('user',null,true,false)->value)){?>
        <li <?php if ($_smarty_tpl->getVariable('page')->value=='inviaSegnalazione'){?>class="selected"<?php }?>><a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['inviaSegnalazione'];?>
"><?php echo $_smarty_tpl->getConfigVariable('invia');?>
</a></li>
				<?php }?>
        <li <?php if ($_smarty_tpl->getVariable('page')->value=='listaSegnalazioni'){?>class="selected"<?php }?>><a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['listaSegnalazioni'];?>
"><?php echo $_smarty_tpl->getConfigVariable('mostraTutte');?>
</a></li>
    </ul>
</div>
<div class="leftBlockBorder">
    <ul class="naviLeft">
				<li class="title"><?php echo $_smarty_tpl->getConfigVariable('strumenti');?>
</li>
				<li <?php if ($_smarty_tpl->getVariable('page')->value=='applicazioni'){?>class="selected"<?php }?>><a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['applicazioni'];?>
"><?php echo $_smarty_tpl->getConfigVariable('applicazioni');?>
</a></li>
				<li <?php if ($_smarty_tpl->getVariable('page')->value=='creaWidget'){?>class="selected"<?php }?>><a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['creaWidget'];?>
"><?php echo $_smarty_tpl->getConfigVariable('creaWidget');?>
</a></li>
				<li <?php if ($_smarty_tpl->getVariable('page')->value=='supporta'){?>class="selected"<?php }?>><a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['supporta'];?>
"><?php echo $_smarty_tpl->getConfigVariable('sostieni');?>
</a></li>
    </ul>
</div>

<div class="leftBlockBorder">
    <ul class="naviLeft">
        <li class="title"><?php echo $_smarty_tpl->getConfigVariable('classifiche');?>
</li>
        <li <?php if ($_smarty_tpl->getVariable('page')->value=='topSegnalatori'){?>class="selected"<?php }?>><a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['topSegnalatori'];?>
"><?php echo $_smarty_tpl->getConfigVariable('topSegnalatori');?>
</a></li>
    </ul>
</div>

<div class="leftBlockBorder">
    <ul class="naviLeft">
        <li class="title"><?php echo $_smarty_tpl->getConfigVariable('docum');?>
</li>
        <li <?php if ($_smarty_tpl->getVariable('page')->value=='suDU'){?>class="selected"<?php }?>><a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['suDU'];?>
"><?php echo $_smarty_tpl->getConfigVariable('suDU');?>
</a></li>
        <li <?php if ($_smarty_tpl->getVariable('page')->value=='guida'){?>class="selected"<?php }?>><a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['guida'];?>
"><?php echo $_smarty_tpl->getConfigVariable('guida');?>
</a></li>
				<li <?php if ($_smarty_tpl->getVariable('page')->value=='FAQs'){?>class="selected"<?php }?>><a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['FAQs'];?>
"><?php echo $_smarty_tpl->getConfigVariable('FAQs');?>
</a></li>
				<li <?php if ($_smarty_tpl->getVariable('page')->value=='contatti'){?>class="selected"<?php }?>><a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['contatti'];?>
"><?php echo $_smarty_tpl->getConfigVariable('comuniContattaci');?>
</a></li>
    </ul>
</div>

<div class="leftBlock">
    <ul class="naviLeft">
        <li class="title"><?php echo $_smarty_tpl->getConfigVariable('social');?>
</li>
				 <li><a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['url'];?>
blog/" target="_blank"><?php echo $_smarty_tpl->getConfigVariable('duBlog');?>
</a></li>
        <li><a href="<?php echo $_smarty_tpl->getVariable('settings')->value['social']['facebook'];?>
" target="_blank"><div class="fbIcon  marginR5"></div> <?php echo $_smarty_tpl->getConfigVariable('socialFB');?>
</a></li>
        <li><a href="<?php echo $_smarty_tpl->getVariable('settings')->value['social']['twitter'];?>
" target="_blank"><div class="twIcon  marginR5"></div> <?php echo $_smarty_tpl->getConfigVariable('socialTwitter');?>
</a></li>
    </ul>
			<iframe src="http://www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Fdecorourbano&amp;width=237&amp;colorscheme=light&amp;show_faces=true&amp;border_color&amp;stream=false&amp;header=false&amp;height=345" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:237px; height:345px; margin-top:5px;" allowTransparency="true"></iframe>
</div>

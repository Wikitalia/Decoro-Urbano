<?php /* Smarty version Smarty-3.0.7, created on 2012-01-25 15:45:40
         compiled from "/var/www/fork.decorourbano.org/templates/includes/footer.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4069490144f20159461f366-21813507%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f0ff1a5f2e42b1de6abec4aa2f4ff6cdfd1775c3' => 
    array (
      0 => '/var/www/fork.decorourbano.org/templates/includes/footer.tpl',
      1 => 1327333821,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4069490144f20159461f366-21813507',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>


</div>
        </div>
    </div>
    <div id="footer">
    	<div class="container">
				<div class="auto left marginT5 fontS10 textLeft">
				 <?php if ($_smarty_tpl->getVariable('page')->value=='prehome'){?>
				  <a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['url'];?>
blog/" target="_blank"><?php echo $_smarty_tpl->getConfigVariable('duBlog');?>
</a> - 
				 <?php }?>
					<a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['contatti'];?>
"><?php echo $_smarty_tpl->getConfigVariable('contatti');?>
</a> - <a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['privacy'];?>
"><?php echo $_smarty_tpl->getConfigVariable('privacy');?>
</a> - <a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['tos'];?>
"><?php echo $_smarty_tpl->getConfigVariable('condizioni');?>
</a> - <a href="<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['open'];?>
"><?php echo $_smarty_tpl->getConfigVariable('open_data_open_source');?>
</a><br />
					<div class="paddY5 textLeft"><?php echo $_smarty_tpl->getConfigVariable('versione');?>
 <?php echo $_smarty_tpl->getVariable('settings')->value['sito']['versione'];?>
</div>
				</div>
				<?php echo $_smarty_tpl->getConfigVariable('footProgetto');?>
 <a href="http://www.maioralabs.it/" target="_blank"><?php echo $_smarty_tpl->getConfigVariable('maiora');?>
</a> 
				<a href="http://www.maioralabs.it" class="marginL10" target="_blank"><img src="/images/logoMaioraFooter.png" alt="" /></a>
			</div>
    </div>
    
    <div id="footer_social">
    <div class="container">
    <iframe src="http://www.facebook.com/plugins/like.php?app_id=228628673839033&amp;href=http%3A%2F%2F<?php echo $_smarty_tpl->getVariable('settings')->value['sito']['dominio'];?>
%2F&amp;send=false&amp;layout=standard&amp;width=450&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=35;locale=it_IT" scrolling="no" frameborder="0" style="float:left;border:none; overflow:hidden; width:450px; height:35px;" allowTransparency="true"></iframe>
    </div>
    </div>
    
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-16957391-4']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>

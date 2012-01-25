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

</div>
        </div>
    </div>
    <div id="footer">
    	<div class="container">
				<div class="auto left marginT5 fontS10 textLeft">
				 {if $page == 'prehome'}
				  <a href="{$settings.sito.url}blog/" target="_blank">{#duBlog#}</a> - 
				 {/if}
					<a href="{$settings.sito.contatti}">{#contatti#}</a> - <a href="{$settings.sito.privacy}">{#privacy#}</a> - <a href="{$settings.sito.tos}">{#condizioni#}</a> - <a href="{$settings.sito.open}">{#open_data_open_source#}</a><br />
					<div class="paddY5 textLeft">{#versione#} {*<a href="{$settings.sito.funzioniDU}">*}{$settings.sito.versione}{*</a>*}</div>{*
					<a href="" target="_blank"><img src="/images/facebook.png" alt="Facebook" class="marginR10" /></a>
					<a href="" target="_blank"><img src="/images/twitter.png" alt="Twitter" /></a>*}
				</div>
				{#footProgetto#} <a href="http://www.maioralabs.it/" target="_blank">{#maiora#}</a> 
				<a href="http://www.maioralabs.it" class="marginL10" target="_blank"><img src="/images/logoMaioraFooter.png" alt="" /></a>
			</div>
    </div>
    
    <div id="footer_social">
    <div class="container">
    <iframe src="http://www.facebook.com/plugins/like.php?app_id=228628673839033&amp;href=http%3A%2F%2F{$settings.sito.dominio}%2F&amp;send=false&amp;layout=standard&amp;width=450&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=35;locale=it_IT" scrolling="no" frameborder="0" style="float:left;border:none; overflow:hidden; width:450px; height:35px;" allowTransparency="true"></iframe>
    </div>
    </div>
    {*
    <div id="social_fisso">
    <iframe src="http://www.facebook.com/plugins/like.php?app_id=228628673839033&amp;href=http%3A%2F%2F{$settings.sito.dominio}%2F&amp;send=false&amp;layout=box_count&amp;width=80&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=90;locale=it_IT" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:70px; height:90px;" allowTransparency="true"></iframe>
    </div>
    *}
    
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

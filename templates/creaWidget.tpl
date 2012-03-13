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

<script type="text/javascript" src="/js/controlli.js"></script>

<script>

$(function() {
	$("#comune").autocomplete({ 
		source: "{$settings['sito']['url']}ajax/comuni.php", 
		minLength: 2,
		select: function (event, ui) {
			$('#id_comune').val(ui.item.nome_url);
			updateWidget();
		}
	});
});

function updateWidget() {
	var widget_link = "<iframe src=\"{$settings['sito']['url']}ext/widget.php?c="+$('#id_comune').val();
	height = 37+76+26;
	/*if ($('#urlInvio').val()!='') {
		widget_link += '&'+$('#url').serialize();
	}*/
	
	if ($('#includiMappa').attr('checked')) {
		widget_link += "&m=1";
		height += 340;
	}
	if ($('#includiNumeri').attr('checked')) {
		widget_link += "&n=1";
		height += 49*3;
	}
	if ($('#includiUltime').attr('checked')) {
		widget_link += "&u=1";
		height += 242;
	}
	if ($('#includiTwitter').attr('checked')) {
		widget_link += "&tw=1";
		height += 375;
	}
	
	
	if ($('#width').val()!='' && !isNaN($('#width').val())) {
		width = parseInt($('#width').val())
		if (width<200) {
			$('#width').val('200');
			alert('Non è possibile specificare un valore inferiore a 200 pixel');
			width = 200;
		}
	} else {
		$('#width').val('200');
		width = 200;
	}
	$('#height').val(height);
	widget_link += "\" width=\""+width+"\" height=\""+height+"\" frameborder=0></iframe>";
	$('#linkWidget').text(widget_link);
}

function popitup(url) {
	//newwindow=window.open(url,'mywindow');
	newwindow=window.open(url,'mywindow','width=280,height=600,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,copyhistory=no,resizable=yes');
	if (window.focus) { newwindow.focus() }
	return false;
}

function provaWidget() {
	var widget_link = "{$settings['sito']['url']}ext/widget.php?c="+$('#id_comune').val();
	height = 37+76+26;
	/*if ($('#urlInvio').val()!='') {
		widget_link += '&'+$('#url').serialize();
	}*/
	
	if ($('#includiMappa').attr('checked')) {
		widget_link += "&m=1";
		height += 340;
	}
	if ($('#includiNumeri').attr('checked')) {
		widget_link += "&n=1";
		height += 49*3;
	}
	if ($('#includiUltime').attr('checked')) {
		widget_link += "&u=1";
		height += 242;
	}
	if ($('#includiTwitter').attr('checked')) {
		widget_link += "&tw=1";
		height += 375;
	}

	popitup(widget_link);

}


</script>

<div class="rightPageHeader">
	<div class="rightHeadText"><h3 class="fontS18 marginB5 fBrown">{#creaWidgetTitle#}</h3>
		{#creaWidgetTesto#}
	</div>
	<div class="rightHeadIcon"><div class="rhiCreaWidget"></div></div>
</div>

<div class="testoFumetto" id="creaWidget">
	<!--<div><h3 class="pageTitle marginB5">{#regTitolo#}</h3></div>-->
	<form method="post" onsubmit="return false;">
		<div><label for="comune">{#comune#}:</label> <input onchange="return updateWidget();" name="comune" id="comune" type="text" /><input type="hidden" name="id_comune" id="id_comune" /><span id="controllo_comune"></span></div>
		<!--<div><label for="url">{#urlInvio#}:</label> <div class="inputContainer"><input onchange="return updateWidget();" name="url" id="url" type="text" /></div>-->
		<div><label for="includiMappa">{#includiMappa#}</label> <input onchange="return updateWidget();" name="includiMappa" id="includiMappa" type="checkbox" /></div>
		<div><label for="includiNumeri">{#includiNumeri#}</label> <input onchange="return updateWidget();" name="includiNumeri" id="includiNumeri" type="checkbox" /></div>
		<div><label for="includiUltime">{#includiUltime#}</label> <input onchange="return updateWidget();" name="includiUltime" id="includiUltime" type="checkbox" /></div>
		<!--<div class="marginT10"><label for="includiTwitter">{#includiTwitter#}</label> <input onchange="return updateWidget();" name="includiTwitter" id="includiTwitter" type="checkbox" /></div> -->
		<div><label for="width">{#width#}:</label> <input onchange="return updateWidget();" name="width" id="width" type="text" class="width_w" /></div>
		<!-- <div class="marginT10"><label for="height">{#height#}:</label> <input disabled name="height" id="height" type="text" /></div> -->
		<div class="noBordo"><label for="linkWidget">{#linkWidget#}<br /><span class="fontS10 pointer" onclick="selezionaTesto('linkWidget');">{#selTutto#}</span></label> <div class="sostieniCodiceWidget" id="linkWidget" disabled="disabled" onclick="selezionaTesto('linkWidget');"></div>
		<input type="submit" name="Prova" value="Prova" onclick="provaWidget();" />
		</div>
	</form>
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>






<div class="demo-description" id="modalControlli">
</div>

{*
<iframe src="{$settings.sito.url}ext/widget.php?c=roma&m=1&n=1&u=1" width="250" height="868" frameborder=0></iframe>
*}

{include file="includes/footer.tpl"}
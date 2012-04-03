/*
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
 */


var submit = 0;
var error = new Array();

/*function ValidateEmailAddr(id){
	var emailAddress = $('#'+id).val();
	var statusIcon = '#controllo_'+id;

	var atSymbol = emailAddress.indexOf("@");                                  // Get the index of the '@'
	var period = emailAddress.lastIndexOf(".");                                // Get the value of the last '.'
	var suffix = emailAddress.substring(period + 1, emailAddress.length);
  
  // Make sure the '@' symbol and '.' is in a valid location
  if (((atSymbol != 0) && (atSymbol != -1)) && (suffix.length > 1) && (atSymbol < period) && (atSymbol != period - 1)) { 
		// if ((suffix == "com") || (suffix == "org") || (suffix == "gov") || (suffix == "net") || (suffix == "edu") || (suffix == "it") || (suffix == "eu"))
		if ($(statusIcon).hasClass('checkFailed') || submit) {
			$(statusIcon).removeClass('checkFailed');
			$(statusIcon).addClass('checkPassed');
		}
	  return true;
  } else {
  	if (submit) {
			$(statusIcon).removeClass('checkPassed');
			$(statusIcon).addClass('checkFailed');
		}			
		return false;
  }
}*/

function ValidateEmailAddr(id){

	var emailAddress = $('#'+id).val();
	var statusIcon = '#controllo_'+id;
	//var emailRegEx = /^[-a-zA-Z0-9_.+]+@[a-zA-Z0-9-]{2,}\.[a-zA-Z]{2,}$/i;
	var emailRegEx = '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$';

	if (emailAddress.search(emailRegEx) != -1) {
		if ($(statusIcon).hasClass('checkFailed') || submit) {
			$(statusIcon).removeClass('checkFailed');
			$(statusIcon).addClass('checkPassed');
		}
		return true;
	} else {
  	if (submit) {
			$(statusIcon).removeClass('checkPassed');
			$(statusIcon).addClass('checkFailed');
			
		}
		return false;
	}

}

function ValidateTextField (id, leng) {
	var value = document.getElementById(id).value;
	var statusIcon = '#controllo_'+id;
	
	if (value.length >= leng) {
		if ($(statusIcon).hasClass('checkFailed') || submit) {
			$(statusIcon).removeClass('checkFailed');
			$(statusIcon).addClass('checkPassed');
		}
		return true;
  } else {
  	if (submit) {
			$(statusIcon).removeClass('checkPassed');
			$(statusIcon).addClass('checkFailed');
		}
		return false;
	}
}

function ValidateNumber (id, leng) {
	var value = document.getElementById(id).value;
	var statusIcon = '#controllo_'+id;
	
	if( (!isNaN(value)) && (value.length >= leng) ) {
		if ($(statusIcon).hasClass('checkFailed') || submit) {
			$(statusIcon).removeClass('checkFailed');
			$(statusIcon).addClass('checkPassed');
		}
		return true;
  } else {
  	if (submit) {
			$(statusIcon).removeClass('checkPassed');
			$(statusIcon).addClass('checkFailed');
		}
		return false;
	}
	 	
}

function ValidateCheckbox (id) {
	var checked = document.getElementById(id).checked;
	var statusIcon = '#controllo_'+id;
	
	if( checked == true ) {
		if ($(statusIcon).hasClass('checkFailed') || submit) {
			$(statusIcon).removeClass('checkFailed');
			$(statusIcon).addClass('checkPassed');
		}
		return true;
  } else {
  	if (submit) {
			$(statusIcon).removeClass('checkPassed');
			$(statusIcon).addClass('checkFailed');
		}
		return false;
	}

}

function GetWordCount(htmlData) {
	return htmlData.replace(/<(?:.|\s)*?>/g, '').split(' ').length;    
}

function ValidateTextareaLength (id, min_leng, max_leng) {
	//var value = document.getElementById(id).value;
	
	var statusIcon = '#controllo_'+id;
	
	//alert(document.getElementById(id).value);
	//alert(GetWordCount(ck_elem.getData())+", "+min_leng+", "+max_leng);
	
	count = document.getElementById(id).value.split(/\s+/).length;
	
	if (count >= min_leng && count <= max_leng) {
		if ($(statusIcon).hasClass('checkFailed') || submit) {
			$(statusIcon).removeClass('checkFailed');
			$(statusIcon).addClass('checkPassed');
		}
		return true;
  } else {
  	if (submit) {
			$(statusIcon).removeClass('checkPassed');
			$(statusIcon).addClass('checkFailed');
		}
		return false;
	}
}

function ValidateSelect (id) {

	var statusIcon = '#controllo_'+id;
	
	index = document.getElementById(id).selectedIndex;

	if (index) {
		if ($(statusIcon).hasClass('checkFailed') || submit) {
			$(statusIcon).removeClass('checkFailed');
			$(statusIcon).addClass('checkPassed');
		}
		return true;
  } else {
  	if (submit) {
			$(statusIcon).removeClass('checkPassed');
			$(statusIcon).addClass('checkFailed');
		}
		return false;
	}
}

function ValidateFotoNum (id, min) {
	//var value = document.getElementById(id).value;
	
	var statusIcon = '#controllo_'+id;
	
	//alert(eval('num_'+id));
	eval ('var numero_immagini = num_'+id);
	//alert(numero_immagini);
	
	if (numero_immagini >= min) {
		if ($(statusIcon).hasClass('checkFailed') || submit) {
			$(statusIcon).removeClass('checkFailed');
			$(statusIcon).addClass('checkPassed');
		}
		return true;
  } else {
  	if (submit) {
			$(statusIcon).removeClass('checkPassed');
			$(statusIcon).addClass('checkFailed');
		}
		return false;
	}
}

function ValidateServiziNum (id, min) {
	//var value = document.getElementById(id).value;
	
	var statusIcon = '#controllo_'+id;
	
	//alert(eval('num_'+id));
	eval ('var numero_servizi = num_'+id);
	//alert(numero_immagini);
	
	if (numero_servizi >= min) {
		if ($(statusIcon).hasClass('checkFailed') || submit) {
			$(statusIcon).removeClass('checkFailed');
			$(statusIcon).addClass('checkPassed');
		}
		return true;
  } else {
  	if (submit) {
			$(statusIcon).removeClass('checkPassed');
			$(statusIcon).addClass('checkFailed');
		}
		return false;
	}
}

function ValidateStelleNum (id, min) {
	//var value = document.getElementById(id).value;
	
	var statusIcon = '#controllo_'+id;
	
	//alert(eval('num_'+id));
	eval ('var numero_stelle = num_'+id);
	//alert(numero_immagini);
	
	if (numero_stelle >= min) {
		if ($(statusIcon).hasClass('checkFailed') || submit) {
			$(statusIcon).removeClass('checkFailed');
			$(statusIcon).addClass('checkPassed');
		}
		return true;
  } else {
  	if (submit) {
			$(statusIcon).removeClass('checkPassed');
			$(statusIcon).addClass('checkFailed');
		}
		return false;
	}
}

function ValidateCompare (id, id_compare) {
	//var value = document.getElementById(id).value;
	
	var f1 = $('#'+id).val();
	var f2 = $('#'+id_compare).val();	
			
	var statusIcon = '#controllo_'+id;
	
	if (f1 == f2) {
		if ($(statusIcon).hasClass('checkFailed') || submit) {
			$(statusIcon).removeClass('checkFailed');
			$(statusIcon).addClass('checkPassed');
		}
		return true;
  } else {
  	if (submit) {
			$(statusIcon).removeClass('checkPassed');
			$(statusIcon).addClass('checkFailed');
		}
		return false;
	}
}

function ValidateIllegalChars(id) {
	var value = document.getElementById(id).value;
	var re = new RegExp(/[\s\[\]\(\)=,"\/\?@\:\;]/g);
	if (re.test(value)) return true;
	else return false;
}

function ValidateForm (controlFields) {
	ValidateForm_ (controlFields, "field");
}

function ValidateForm_ (controlFields, sender) {

	error = [];
	error['type'] = 0;
	error['msg'] = '';
	var i = 0;
	
	submit = (sender=="submit")?1:0;
	
	

	for(i in controlFields) {
		//alert(dump(controlFields[controlFields.length - i - 1]));
		ValidateField(controlFields[controlFields.length - i - 1]);
	}
	/*if (error)
	document.getElementById('sendForm').disabled = true;
	else
	document.getElementById('sendForm').disabled = false;*/
	
	if (error['type']) {
		if (submit) {
			//smoothScroll(error['field_id']);
			$('#modalControlli').html(error['msg']);
			//alert($('#modalControlli').html());

			$('#modalControlli').dialog({
				height: 400,
				width:550,
				modal: true,
				draggable:false,
				resizable:false,
				buttons: {
					Ok: function() {
						$( this ).dialog( "close" );
					}
				}
			});

		}
		return false;
	}	else {
		return true;
	}
}

function ValidateField(controlField) {

	//alert(dump(controlField));

	if (controlField['type'] == 1)
		if (! ValidateTextField(controlField['nome'], controlField['lenght'])) {
			error['type'] = controlField['type'];
			error['field_id'] = controlField['nome'];
			error['msg'] = '<p>Verificare il campo '+controlField['nome_esteso']+': lunghezza minima '+controlField['lenght']+' caratteri</p>'+error['msg'];
		}
	if (controlField['type'] == 2)
		if (! ValidateEmailAddr(controlField['nome'])) {
			error['type'] = controlField['type'];
			error['field_id'] = controlField['nome'];
			error['msg'] = '<p>Verificare di aver inserito un indirizzo email valido</p>'+error['msg'];
		}
	if (controlField['type'] == 3)
		if (! ValidateNumber(controlField['nome'], controlField['lenght'])){
			error['type'] = controlField['type'];
			error['field_id'] = controlField['nome'];
			error['msg'] = '<p>Verificare '+controlField['nome_esteso']+'</p>'+error['msg'];
		}
	if (controlField['type'] == 4)
		if (! ValidateCheckbox(controlField['nome'], controlField['lenght'])) {
			error['type'] = controlField['type'];
			error['field_id'] = controlField['nome'];
			error['msg'] = '<p>Verificare '+controlField['nome_esteso']+'</p>'+error['msg'];
		}
	if (controlField['type'] == 5)
		if (! ValidateTextareaLength(controlField['nome'], controlField['min_lenght'], controlField['max_lenght'])) {
			error['type'] = controlField['type'];
			error['field_id'] = controlField['nome'];
			error['msg'] = '<p>Verificare il campo '+controlField['nome_esteso']+': lunghezza massima '+controlField['max_lenght']+' parole</p>'+error['msg'];
		}
	if (controlField['type'] == 6)
		if (! ValidateSelect(controlField['nome'])) {
			error['type'] = controlField['type'];
			error['field_id'] = controlField['nome'];
			error['msg'] = '<p>Verificare '+controlField['nome_esteso']+'</p>'+error['msg'];
		}
	if (controlField['type'] == 7)
		if (! ValidateFotoNum(controlField['nome'], controlField['min'])) {
			error['type'] = controlField['type'];
			error['field_id'] = controlField['nome'];
			error['msg'] = '<p>Verificare '+controlField['nome_esteso']+'</p>'+error['msg'];
		}
	if (controlField['type'] == 8)
		if (! ValidateServiziNum(controlField['nome'], controlField['min'])) {
			error['type'] = controlField['type'];
			error['field_id'] = controlField['nome'];
			error['msg'] = '<p>Verificare '+controlField['nome_esteso']+'</p>'+error['msg'];
			//alert("errore");
		}
	if (controlField['type'] == 9)
		if (! ValidateStelleNum(controlField['nome'], controlField['min'])) {
			error['type'] = controlField['type'];
			error['field_id'] = controlField['nome'];
			error['msg'] = '<p>Verificare '+controlField['nome_esteso']+'</p>'+error['msg'];
		}
	if (controlField['type'] == 10)
		if ($('#'+controlField['compare']).val()!='')		
			if (! ValidateCompare(controlField['nome'], controlField['compare'])) {
				error['type'] = controlField['type'];
				error['field_id'] = controlField['nome'];
				error['msg'] = '<p>Verificare '+controlField['nome_esteso']+'</p>'+error['msg'];
			}

}

function currentYPosition() {
  // Firefox, Chrome, Opera, Safari
  if (self.pageYOffset) return self.pageYOffset;
  // Internet Explorer 6 - standards mode
  if (document.documentElement && document.documentElement.scrollTop)
      return document.documentElement.scrollTop;
  // Internet Explorer 6, 7 and 8
  if (document.body.scrollTop) return document.body.scrollTop;
  return 0;
}

function elmYPosition(eID) {
  var elm = document.getElementById(eID);
  var y = elm.offsetTop;
  var node = elm;
  while (node.offsetParent && node.offsetParent != document.body) {
    node = node.offsetParent;
    y += node.offsetTop;
  } return y;
}

function smoothScroll(eID) {
	var startY = currentYPosition();
	var stopY = elmYPosition(eID);
	var distance = stopY > startY ? stopY - startY : startY - stopY;
	if (distance < 100) {
		scrollTo(0, stopY); return;
	}
	var speed = Math.round(distance / 100);
	if (speed >= 20) speed = 20;
	var step = Math.round(distance / 25);
	var leapY = stopY > startY ? startY + step : startY - step;
	var timer = 0;
	if (stopY > startY) {
		for ( var i=startY; i<stopY; i+=step ) {
			setTimeout("window.scrollTo(0, "+leapY+")", timer * speed);
			leapY += step; if (leapY > stopY) leapY = stopY; timer++;
		} return;
	}
	for ( var i=startY; i>stopY; i-=step ) {
		setTimeout("window.scrollTo(0, "+leapY+")", timer * speed);
		leapY -= step; if (leapY < stopY) leapY = stopY; timer++;
	}
}

function windowTip(id, filename) {
	var windowTooltip = document.getElementById('windowTip');
	var element = document.getElementById(id);
	//alert(id.id);
	windowTooltip.style.top = element.offsetTop+10-268+"px";
	//windowTooltip.style.left = element.offsetLeft + 300+"px";
	windowTooltip.style.background = "url('images/tips/"+filename+".png')";
	//alert (element.offsetTop+" - "+windowTooltip.style.top);
	$('#windowTip').fadeIn('slow', function() {
        // Animation complete
      });
}

function windowTipBlur () {
	var windowTooltip = document.getElementById('windowTip');
    windowTooltip.style.display = "none";
}

function addListeners (controlFields) {

	for(i in controlFields) {
			
		if (controlFields[controlFields.length - i - 1]['type'] == 1) {				
			$('#'+controlFields[controlFields.length - i - 1]['nome']).bind('blur',function(){ValidateForm(controlFields);});
			$('#'+controlFields[controlFields.length - i - 1]['nome']).bind('focus',function(){ValidateForm(controlFields);});
			$('#'+controlFields[controlFields.length - i - 1]['nome']).bind('keyup',function(){ValidateForm(controlFields);});
			$('#'+controlFields[controlFields.length - i - 1]['nome']).bind('paste',function(){ValidateForm(controlFields);});											
		}		
			
		if (controlFields[controlFields.length - i - 1]['type'] == 2) {
			$('#'+controlFields[controlFields.length - i - 1]['nome']).bind('blur',function(){ValidateForm(controlFields);});
			$('#'+controlFields[controlFields.length - i - 1]['nome']).bind('focus',function(){ValidateForm(controlFields);});
			$('#'+controlFields[controlFields.length - i - 1]['nome']).bind('keyup',function(){ValidateForm(controlFields);});
			$('#'+controlFields[controlFields.length - i - 1]['nome']).bind('paste',function(){ValidateForm(controlFields);});
		}

		if (controlFields[controlFields.length - i - 1]['type'] == 3) {
		}

		if (controlFields[controlFields.length - i - 1]['type'] == 4) {
			$('#'+controlFields[controlFields.length - i - 1]['nome']).bind('change',function(){ValidateForm(controlFields);});		
		}

		if (controlFields[controlFields.length - i - 1]['type'] == 5) {
		}

		if (controlFields[controlFields.length - i - 1]['type'] == 6) {
		}

		if (controlFields[controlFields.length - i - 1]['type'] == 7) {
		}

		if (controlFields[controlFields.length - i - 1]['type'] == 8) {
		}

		if (controlFields[controlFields.length - i - 1]['type'] == 9) {
		}

		if (controlFields[controlFields.length - i - 1]['type'] == 10) {
			$('#'+controlFields[controlFields.length - i - 1]['nome']).bind('blur',function(){ValidateForm(controlFields);});
			$('#'+controlFields[controlFields.length - i - 1]['nome']).bind('focus',function(){ValidateForm(controlFields);});
			$('#'+controlFields[controlFields.length - i - 1]['nome']).bind('keyup',function(){ValidateForm(controlFields);});
			$('#'+controlFields[controlFields.length - i - 1]['nome']).bind('paste',function(){ValidateForm(controlFields);});
		}		
	}
}



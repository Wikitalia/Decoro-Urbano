<?php

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

/*
 * Insieme di funzioni di utilità generica
 */

/**
 * Invia un'email HTML costruita a partire da un template smarty 
 * 
 * Questa funzione invia un'email HTML, costruendone il contenuto a partire da un template
 * smarty e dai dati necessari all'invio contenuti nell'array 'data' passato come parametro 
 * In particolare:
 * - $data['from']: indirizzo mittente
 * - $data['to']: indirizzo destinazione
 * - $data['template']: identificativo del template smarty da caricare
 * - $data['variabili']: array contenente le stringhe necessarie da sostituire nel template.
 * 
 * @global array $settings
 * @param array $data array contenente i parametri per l'invio dell'email
 * @return boolean
 */
function email_with_template($data) {
    global $settings;

    require_once($settings['smarty']['root'] . 'libs/Smarty.class.php');
    
    // inizializza Smarty
    $smarty_email = new Smarty;
    $smarty_email->template_dir = $settings['sito']['percorso'] . 'email/';
    $smarty_email->compile_dir = $settings['smarty']['private'] . 'templates_c/';
    $smarty_email->config_dir = $settings['sito']['percorso'] . 'email/';
    $smarty_email->cache_dir = $settings['smarty']['private'] . 'cache/';
    
    // carica i testi delle email
    $conf = parse_ini_file($settings['sito']['percorso'] . 'email/testi_email.conf', true);
    
    // inizializza le variabili da valorizzare nel template
    foreach ($data['variabili'] as $key => $value) {
        $smarty_email->assign($key, $value);
    }
    
    $smarty_email->assign("settings", $settings);
    
    // costruisce il corpo dell'email 
    $html_email = $smarty_email->fetch($settings['sito']['percorso'] . 'email/' . $data['template'] . '.tpl');
    
    // invia l'email
    return html_email($data['from'], $data['to'], $conf[$data['template']]['oggetto'], $html_email);

}


/**
 * Invia un'email HTML
 * 
 * Questa funzione invia un'email HTML
 * 
 * @param string $from indirizzo email mittente
 * @param string $to indirizzo email destinatario
 * @param string $subject oggetto dell'email
 * @param string $message corpo dell'email
 * @return boolean 
 */
function html_email($from, $to, $subject, $message) {

    // imposta il Content-type header
    $headers = 'MIME-Version: 1.0' . "\n";
    $headers .= 'Content-type: text/html; charset=UTF-8' . "\n";

    // aggiunge gli header aggiuntivi
    $headers .= 'From: ' . $from . "\n";
    $headers .= 'Reply-To: ' . $from . "\n";

    // invia l'email
    return mail($to, $subject, $message, $headers);
}

/**
 * Rende una stringa uri-friendly
 * 
 * @param string $string
 * @return string 
 */
function fixForUri($string) {
    // elimina gli spazi
    $slug = trim($string);
    // elimina le lettere accentate, sostuituendole con lettere standard
    $slug = accents($slug, true);
    // elimina tutti i caratteri diversi da lettere numeri e -
    $slug = preg_replace('/[^a-zA-Z0-9 -]/', '', $slug);
    // sostituisce gli spazi con -
    $slug = str_replace(' ', '-', $slug); // replace spaces by dashes
    
    return $slug;
}


/**
 * Pulisce e adatta la stringa JSON generata da PHP per essere utilizzata da JS
 * 
 * @param string $s
 * @return string 
 */
function escapeJSON($s) {
    $s = addcslashes($s, "'\\\t\x08\x0c");
    $s = str_replace('\r\n', '<br />', $s);
    $s = str_replace('\r', '<br />', $s);
    $s = str_replace('\n', '<br />', $s);

    return $s;
}

/**
 * Sostituisce le lettere accentate da una stringa sostituendole con le lettere standard
 * 
 * @param string $str
 * @param type $normalize
 * @return mixed 
 */
function accents($str, $normalize = false) {
    $str = mb_strtolower($str, 'utf-8');
    //accented characters from Wikipedia's alphabet pages at http://en.wikipedia.org/wiki/Basic_modern_Latin_alphabet
    $chars = array(
        'a' => 'AaÁáÀàĂăẮắẰằẴẵẲẳÂâẤấẦầẪẫẨẩǍǎÅåǺǻÄäǞǟÃãȦȧǠǡĄąĀāẢảȀȁȂȃẠạẶặẬậḀḁȺⱥᶏⱯɐⱭɑ',
        'b' => 'BbḂḃḄḅḆḇɃƀƁɓƂƃᵬᶀ',
        'c' => 'CcĆćĈĉČčĊċÇçḈḉȻȼƇƈɕ',
        'd' => 'DdĎďḊḋḐḑḌḍḒḓḎḏĐđƉɖƊɗƋƌᵭᶁᶑȡ∂',
        'e' => 'EeÉéÈèĔĕÊêẾếỀềỄễỂểĚěËëẼẽĖėȨȩḜḝĘęĒēḖḗḔḕẺẻȄȅȆȇẸẹỆệḘḙḚḛɆɇᶒⱸ',
        'f' => 'FfḞḟƑƒᵮᶂ',
        'g' => 'GgǴǵĞğĜĝǦǧĠġĢģḠḡǤǥƓɠᶃ',
        'h' => 'HhĤĥȞȟḦḧḢḣḨḩḤḥḪḫH̱ẖĦħⱧⱨ',
        'i' => 'IiÍíÌìĬĭÎîǏǐÏïḮḯĨĩĮįĪīỈỉȈȉȊȋỊịḬḭƗɨᵻᶖİiIı',
        'j' => 'JjĴĵɈɉJ̌ǰȷʝɟʄ',
        'k' => 'KkḰḱǨǩĶķḲḳḴḵƘƙⱩⱪᶄꝀꝁ',
        'l' => 'LlĹĺĽľĻļḶḷḸḹḼḽḺḻŁłĿŀȽƚⱠⱡⱢɫɬᶅɭȴ',
        'm' => 'MmḾḿṀṁṂṃᵯᶆⱮɱ',
        'n' => 'NnŃńǸǹŇňÑñṄṅŅņṆṇṊṋṈṉN̈n̈ƝɲȠƞᵰᶇɳȵ',
        'o' => 'OoÓóÒòŎŏÔôỐốỒồỖỗỔổǑǒÖöȪȫŐőÕõṌṍṎṏȬȭȮȯȰȱØøǾǿǪǫǬǭŌōṒṓṐṑỎỏȌȍȎȏƠơỚớỜờỠỡỞởỢợỌọỘộƟɵⱺ',
        'p' => 'PpṔṕṖṗⱣᵽƤƥP̃p̃ᵱᶈ',
        'q' => 'QqɊɋʠ',
        'r' => 'RrŔŕŘřṘṙŖŗȐȑȒȓṚṛṜṝṞṟɌɍⱤɽᵲᶉɼɾᵳ',
        's' => 'SsŚśṤṥŜŝŠšṦṧṠṡẛŞşṢṣṨṩȘșS̩s̩ᵴᶊʂȿ',
        't' => 'TtŤťṪṫŢţṬṭȚțṰṱṮṯŦŧȾⱦƬƭƮʈT̈ẗᵵƫȶ',
        'u' => 'UuÚúÙùŬŭÛûǓǔŮůÜüǗǘǛǜǙǚǕǖŰűŨũṸṹŲųŪūṺṻỦủȔȕȖȗƯưỨứỪừỮữỬửỰựỤụṲṳṶṷṴṵɄʉᵾᶙ',
        'v' => 'VvṼṽṾṿƲʋᶌⱱⱴ',
        'w' => 'WwẂẃẀẁŴŵẄẅẆẇẈẉW̊ẘⱲⱳ',
        'x' => 'XxẌẍẊẋᶍ',
        'y' => 'YyÝýỲỳŶŷY̊ẙŸÿỸỹẎẏȲȳỶỷỴỵɎɏƳƴʏ',
        'z' => 'ZzŹźẐẑŽžŻżẒẓẔẕƵƶȤȥⱫⱬᵶᶎʐʑɀ'
    );
    if ($normalize) {
        foreach ($chars as $normal => $accents) {
            $str = str_replace(mb_str_split($accents), $normal, $str);
        }
        return $str;
    } else {
        if (!array_key_exists($str, $chars)) {
            return false;
        }
        return mb_str_split($chars[$str]);
    }
}


function random_float ($min,$max) {
   return ($min+lcg_value()*(abs($max-$min)));
}

function xnor($a, $b) {
	return ~($a ^ $b);
}

function debug($valore){
	echo "<pre>";
	print_r($valore);
	echo "</pre>";
}

function right($value, $count){
	return substr($value, ($count*-1));
}

function left($string, $count){
	return substr($string, 0, $count);
}	


/*
 * Set di funzioni di conversione di stringhe rappresentati date tra diversi formati
 */

function ConvertitoreData($data){
	$separa=explode("-",$data);
	$a=$separa[0];
	$b=$separa[1];
	$c=$separa[2];
	$data_convertita="$c-$b-$a";
	return $data_convertita;
}	

function ConvertitoreDataPerDB($data){
	$separa=explode("-",$data);
	$a=$separa[0];
	$b=$separa[1];
	$c=$separa[2];
	$data_convertita="$c$b$a";
	return $data_convertita;
}	

function ConvertitoreData_US_DB($data){
	$separa=explode("/",$data);
	$a=$separa[0];
	$b=$separa[1];
	$c=$separa[2];
	$data_convertita="$c$a$b";
	return $data_convertita;
}

function ConvertitoreData_DB_IT($data){
	
	$separa=explode("-",$data);
	$a=$separa[0];
	$b=$separa[1];
	$c=$separa[2];
	
	//$data_convertita="$c/$b/$a";
	
	//PHP precedenti al 5.2:
	$data_convertita = date('d/m/Y', mktime(0, 0, 0, $b, $c, $a));
	
	//PHP 5.2 e successivi:
	//$data_convertita = date_format( date_create($data), 'd/m/Y');
	
	return $data_convertita;
}


function ConvertitoreData_TMSTMP_IT($data){
	
	$data = substr($data,0,10);
	$separa=explode("-",$data);
	$a=$separa[0];
	$b=$separa[1];
	$c=$separa[2];
	
	$data_convertita="$c/$b/$a";    
	
	return $data_convertita;
}


function ConvertitoreData_DB_US($data) {
	
	$separa=explode("-",$data);
	$a=$separa[0];
	$b=$separa[1];
	$c=$separa[2];
	
	//$data_convertita="$c/$b/$a";*/
	
	//PHP precedenti al 5.2:
	$data_convertita = date('m/d/Y', mktime(0, 0, 0, $b, $c, $a));
	
	//PHP 5.2 e successivi:
	//$data_convertita = date_format( date_create($data), 'd/m/Y');
	
	return $data_convertita;
}

function ConvertitoreData_US_IT($data){
	$separa=explode("/",$data);
	$a=$separa[0];
	$b=$separa[1];
	$c=$separa[2];
	$data_convertita="$b/$a/$c";
	return $data_convertita;
}	

function ConvertitoreData_UNIXTIMESTAMP_IT($data){
	return date('d/m/Y', $data);
}

function ConvertitoreData_UNIXTIMESTAMP_YMD_HM($data){
	return date('Y/m/d H:i', $data);
}

function ConvertitoreData_UNIXTIMESTAMP_US($data){
	return date('m/d/Y', $data);
}


/**
 * Overwrite della funzione originale mb_str_split()
 * rif. http://php.net/manual/en/function.str-split.php#69331
 */
function mb_str_split($str, $length = 1, $encoding = 'utf-8') {
	if ($length < 1) {
		return false;
	}
	$result = array();
	for ($i = 0; $i < mb_strlen($str, $encoding); $i += $length) {
		$result[] = mb_substr($str, $i, $length, $encoding);
	}
	return $result;
}


/*
 * Insieme di funzioni per il troncamento dei testi
 */

function LimitaTesto($text,$maxchar=210,$end=' ...') {

	$text = str_replace("à","ж",$text); //Workaround per risolvere un bug di PCRE (libreria per le espressioni regolari) sulla a accentata.

	$arr_remove=array("/(&nbsp;)+/", "/\s+/", "/(\r\n)+/", "/\n+/", "/\r+/", "/\t+/", "/(<br\s\/>)+/", "/(<br>)+/"); // add all things you need replacing	
	$text = preg_replace($arr_remove, " ", $text);
	$text = preg_replace("/\s+/", " ", $text);
	
	$text = strip_tags($text);

	if(strlen($text)>$maxchar) {
		$words=explode(" ",$text);
		$output = '';
		$i=0;
		while(1) {
			$length = (strlen($output)+strlen($words[$i]));
			if($length>$maxchar) {
				break;
			} else {
				$output = $output." ".$words[$i];
				++$i;
			};
		};
		$output.=$end;
	} else {
		$output = $text;
	}
	$output = str_replace("ж","à",$output); //Workaround per risolvere un bug di PCRE (libreria per le espressioni regolari) sulla a accentata.
	return $output;
}	

function LimitaTesto_500 ($stringa){
    return LimitaTesto($stringa,500);
}


function LimitaTesto_230 ($stringa){
    return LimitaTesto($stringa,230);
}

function LimitaTesto_180 ($stringa){
    return LimitaTesto($stringa,180);
}


function LimitaTesto_90 ($stringa){
    return LimitaTesto($stringa,90);
}

function LimitaTesto_30 ($stringa){
    $stringa = str_replace("-",' ',$stringa);
    return LimitaTesto($stringa,30);
}

/*
 * Varie
 */

function superencoder64 ($s) {
  $s = base64_encode($s);
  //$s = sha1($s);
  return $s;
}

function superdecoder64 ($s) {
  //$s = sha1($s);
  $s = base64_decode($s);
  return $s;
}

function nome_documento_wiki_pulisci($testo){
	$testo = str_replace(' ','_',$testo);
	$testo = strtolower($testo);	
	return $testo;	
	
}

function pulsici_trattini($testo){
	$testo = str_replace('-',' ',$testo);
  $testo = str_replace('_',' ',$testo);
	$testo = strtolower($testo);	
	return $testo;	
}

function menu_per_seo ($testo) {

	$testo = str_replace(' ','-',$testo);
	$testo = str_replace('à','a',$testo);
	$testo = str_replace('À','a',$testo);
	$testo = str_replace('à','a',$testo);
	$testo = str_replace('é','e',$testo);
	$testo = str_replace('è','e',$testo);
	$testo = str_replace('ê','e',$testo);
	$testo = str_replace('ț','t',$testo);		// manca la t con il puntino sotto del rumeno
	$testo = str_replace('ţ','t',$testo);
  $testo = str_replace('’','',$testo);
  $testo = str_replace("'","",$testo);
	$testo = str_replace('"','',$testo);
  $testo = str_replace('“','',$testo);
  $testo = str_replace('”','',$testo);
  $testo = str_replace('/','',$testo);
  $testo = str_replace('\\','',$testo);
  $testo = str_replace('«','',$testo);
  $testo = str_replace('»','',$testo);        
  $testo = str_replace('(','',$testo);
  $testo = str_replace(')','',$testo);
	$testo = str_replace('?','',$testo);
	$testo = str_replace('!','',$testo);	
	$testo = str_replace(',','',$testo);
	$testo = str_replace('.','',$testo);
	$testo = str_replace(':','',$testo);
	$testo = str_replace(';','',$testo);
	$testo = str_replace('’','-',$testo);
	$testo = str_replace('‘','-',$testo);
	$testo = str_replace('–','-',$testo);  // sembrano uguali ma non lo sono!  --->  URCA!! In cosa differiscono?
	$testo = str_replace('----','-',$testo);
	$testo = str_replace('---','-',$testo);
	$testo = str_replace('--','-',$testo);


	$testo = strtolower($testo);
	
	return $testo;
}


function converti_iso8601_human($stringa) {
	$stringa = strtotime($stringa);
	$stringa = date('d m Y',$stringa);
  
  if ($stringa=='01 01 1970') $stringa = 'No event yet';
  
	return $stringa;
}

function converti_iso8601_US($stringa) {
	$stringa = strtotime($stringa);
	$stringa = date('m/d/Y H:i',$stringa);
	return $stringa;
}

function converti_timestamp_human($stringa) {
	$stringa = date('D, d M Y H:i:s',$stringa);
	return $stringa;
}


function explode_url($posizione) {
	$stringa = explode('/',$_SERVER["REQUEST_URI"]);
	$stringa = $stringa[$posizione];
	//echo $stringa;
	return $stringa;	
}


function resizeImage($originalImage,$type,$toWidth,$toHeight){ // Resize solo se l'immagine è più grande.

    // Get the original geometry and calculate scales
    list($width, $height) = getimagesize($originalImage);
    $xscale=$width/$toWidth;
    $yscale=$height/$toHeight;
    
    // Recalculate new size with default ratio
    if ($yscale>$xscale){
        $new_width = round($width * (1/$yscale));
        $new_height = round($height * (1/$yscale));
    }
    else {
        $new_width = round($width * (1/$xscale));
        $new_height = round($height * (1/$xscale));
    }

	if($type=="image/jpeg"){
	  $imageTmp=imagecreatefromjpeg($originalImage);
	} elseif($type=="image/png") {
	  $imageTmp=imagecreatefrompng($originalImage);
	} elseif($type=="image/gif") {
	  $imageTmp=imagecreatefromgif($originalImage);
	} else {
		exit();
	}

	if($width > $toWidth || $height > $toHeight) {
		// Resize the original image
		$imageResized = imagecreatetruecolor($new_width, $new_height);
		imagecopyresampled($imageResized, $imageTmp, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		return $imageResized;
	} else {
		return $imageTmp;
	}
	
}

function remove_accent($str) {
  $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'Ð', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', '?', '?', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', '?', '?', 'L', 'l', 'N', 'n', 'N', 'n', 'N', 'n', '?', 'O', 'o', 'O', 'o', 'O', 'o', 'Œ', 'œ', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'Š', 'š', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Ÿ', 'Z', 'z', 'Z', 'z', 'Ž', 'ž', '?', 'ƒ', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', '?', '?', '?', '?', '?', '?');
  $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
  return str_replace($a, $b, $str);
}

function post_slug($str) {
	$str=str_replace("'","-",$str);
  return strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'), array('', '-', ''), remove_accent($str)));
}

function rrmdir($dir) { 
	if (is_dir($dir)) { 
		$objects = scandir($dir); 
		foreach ($objects as $object) { 
			if ($object != "." && $object != "..") { 
				if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object); 
			} 
		} 
		reset($objects); 
		rmdir($dir); 
	} 
} 

function hex_encode($s) {

	$out = PREG_REPLACE("'(.)'e", "dechex(ord('\\1'))", $s);
	return $out;

}

function hex_decode($s) {

	$out = PREG_REPLACE("'([\S,\d]{2})'e", "chr(hexdec('\\1'))", $s);
	return $out;

}

function cryptare($text, $key, $alg, $crypt) 
{ 
    $encrypted_data=""; 
    switch($alg) 
    { 
        case "3des": 
            $td = mcrypt_module_open('tripledes', '', 'ecb', ''); 
            break; 
        case "cast-128": 
            $td = mcrypt_module_open('cast-128', '', 'ecb', ''); 
            break;    
        case "gost": 
            $td = mcrypt_module_open('gost', '', 'ecb', ''); 
            break;    
        case "rijndael-128": 
            $td = mcrypt_module_open('rijndael-128', '', 'ecb', ''); 
            break;        
        case "twofish": 
            $td = mcrypt_module_open('twofish', '', 'ecb', ''); 
            break;    
        case "arcfour": 
            $td = mcrypt_module_open('arcfour', '', 'ecb', ''); 
            break; 
        case "cast-256": 
            $td = mcrypt_module_open('cast-256', '', 'ecb', ''); 
            break;    
        case "loki97": 
            $td = mcrypt_module_open('loki97', '', 'ecb', ''); 
            break;        
        case "rijndael-192": 
            $td = mcrypt_module_open('rijndael-192', '', 'ecb', ''); 
            break; 
        case "saferplus": 
            $td = mcrypt_module_open('saferplus', '', 'ecb', ''); 
            break; 
        case "wake": 
            $td = mcrypt_module_open('wake', '', 'ecb', ''); 
            break; 
        case "blowfish-compat": 
            $td = mcrypt_module_open('blowfish-compat', '', 'ecb', ''); 
            break; 
        case "des": 
            $td = mcrypt_module_open('des', '', 'ecb', ''); 
            break; 
        case "rijndael-256": 
            $td = mcrypt_module_open('rijndael-256', '', 'ecb', ''); 
            break; 
        case "xtea": 
            $td = mcrypt_module_open('xtea', '', 'ecb', ''); 
            break; 
        case "enigma": 
            $td = mcrypt_module_open('enigma', '', 'ecb', ''); 
            break; 
        case "rc2": 
            $td = mcrypt_module_open('rc2', '', 'ecb', ''); 
            break;    
        default: 
            $td = mcrypt_module_open('blowfish', '', 'ecb', ''); 
            break;                                            
    } 
    
    $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND); 
    $key = substr($key, 0, mcrypt_enc_get_key_size($td)); 
    mcrypt_generic_init($td, $key, $iv); 
    
    if($crypt) 
    { 
        $encrypted_data = mcrypt_generic($td, $text); 
    } 
    else 
    { 
        $encrypted_data = mdecrypt_generic($td, $text); 
    } 
    
    mcrypt_generic_deinit($td); 
    mcrypt_module_close($td); 
    
    return $encrypted_data; 
} 

function code_encrypt($s, $k) {

	$out =  trim(hex_encode(base64_encode(cryptare ($s, $k, '3des', 1))));
	return $out;

}

function code_decrypt($s, $k) {

	$out =  trim(cryptare(base64_decode(hex_decode($s)), $k, '3des', 0));
	return $out;

}



function curPageURL() {
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {
		$pageURL .= "s";
	}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}
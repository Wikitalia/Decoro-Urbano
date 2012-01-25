<?

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
 * Script con funzionalità di ridimensionamento delle immagini con meccanismo di caching integrato.
 */
ini_set('display_errors', 0);
error_reporting(0);

function clean_string($string){
 $slug = trim($string); // trim the string
 $slug = accents($slug, true);
 $slug = str_replace('/','_', $slug); // replace spaces by dashes
 $slug = preg_replace('/[^a-zA-Z0-9 -._]/','-',$slug ); // only take alphanumerical characters, but keep the spaces and dashes too...
 $slug = str_replace(' ','-', $slug); // replace spaces by dashes
 return $slug;
}

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

function resize_crop($original_image_gd,$crop_height,$crop_width) {

	$original_width = imagesx ($original_image_gd);
	$original_height = imagesy ($original_image_gd);

	if ($crop_height && !$crop_width) {
		$crop_width = $crop_height*$original_width/$original_height;
	}
	
	if ($crop_width && !$crop_height) {
		$crop_height = $crop_width*$original_height/$original_width;
	}

	$cropped_image_gd = imagecreatetruecolor($crop_width, $crop_height);
	
	imagealphablending($cropped_image_gd, false);
	imagesavealpha($cropped_image_gd,true);
	
	$wm = $original_width /$crop_width;
	$hm = $original_height /$crop_height;
	$h_height = $crop_height/2;
	$w_height = $crop_width/2;
	
	if($original_width > $original_height ) {
		$adjusted_width =$original_width / $hm;
		$half_width = $adjusted_width / 2;
		$int_width = $half_width - $w_height;
		
		imagecopyresampled($cropped_image_gd ,$original_image_gd ,-$int_width,0,0,0, $adjusted_width, $crop_height, $original_width , $original_height );
	} elseif(($original_width < $original_height ) || ($original_width == $original_height )) {
		$adjusted_height = $original_height / $wm;
		$half_height = $adjusted_height / 2;
		$int_height = $half_height - $h_height;
		
		imagecopyresampled($cropped_image_gd , $original_image_gd ,0,-$int_height,0,0, $crop_width, $adjusted_height, $original_width , $original_height );
	} else {
		imagecopyresampled($cropped_image_gd , $original_image_gd ,0,0,0,0, $crop_width, $crop_height, $original_width , $original_height );
	}
	
	return $cropped_image_gd;

}

$el = explode('-',$_GET['s']);

$len = count($el);

$idu = $el[$len-4];
$ids = $el[$len-3];

$source_file_name = '/images/segnalazioni/'.$idu.'/'.$ids.'/1.jpeg';

$target_width = $el[$len-2];
$target_height = $el[$len-1];

$target_height=isset($target_height)?$target_height:0;
$target_width=isset($target_width)?$target_width:0;

if ($target_width == 0 && $target_height == 0) {

	$file_name=$_SERVER['DOCUMENT_ROOT'].$source_file_name;

	if (!is_file($file_name)) {
		header("HTTP/1.0 404 Not Found");
		exit;
	} else {
		header ('Content-length: ' .filesize($file_name));
		header ("Content-type: image/jpeg");
		readfile($file_name);
	}

}

$crop_height=$target_height;
$crop_width=$target_width;

$cache_file = clean_string($crop_width.'_'.$crop_height.'_'.$source_file_name);
$cache_path = 'images/cache/';

$cache_full_path = $cache_path.$cache_file;



if (is_file($_SERVER['DOCUMENT_ROOT'].$cache_full_path)) {


	header ('Content-length: ' .filesize($cache_full_path));
	header ("Content-type: image/jpeg");
	readfile($cache_full_path);


} else {

	if (substr($source_file_name,0,7) == 'http://' || substr($source_file_name,0,8) == 'https://') {
	
		$data = file_get_contents($source_file_name);
	
		$original_image_gd = imagecreatefromstring($data);
		$cropped_image_gd = resize_crop($original_image_gd,$crop_height,$crop_width);

		imagejpeg($cropped_image_gd,$cache_full_path);
		header ('Content-length: ' .filesize($cache_full_path));
		header ("Content-type: image/jpeg");
		readfile($cache_full_path);
	
	} else {
	
		$file_name=$_SERVER['DOCUMENT_ROOT'].$source_file_name;
	
		if (!is_file($file_name)) {
			header("HTTP/1.0 404 Not Found");
			exit;
		}
		
		$image_data = getimagesize($file_name);
		$file_type = $image_data['mime'];
	
		if($file_type=='jpg' || $file_type=='jpeg' || $file_type == 'image/jpeg') {
			$original_image_gd = imagecreatefromjpeg($file_name);
		} else if($file_type=='gif' || $file_type == 'image/gif') {
			$original_image_gd = imagecreatefromgif($file_name);
		} else if($file_type=='png' || $file_type == 'image/png') {
			$original_image_gd = imagecreatefrompng($file_name);
		}
		
		$cropped_image_gd = resize_crop($original_image_gd,$crop_height,$crop_width);
		
		if($file_type=='jpg' || $file_type=='jpeg' || $file_type == 'image/jpeg') {
			imagejpeg($cropped_image_gd,$cache_full_path);
			header ('Content-length: ' .filesize($cache_full_path));
			header ("Content-type: image/jpeg");
			readfile($cache_full_path);
		} else if($file_type=='gif' || $file_type == 'image/gif') {
			imagegif($cropped_image_gd,$cache_full_path);
			header ('Content-length: ' .filesize($cache_full_path));
			header ("Content-type: image/gif");
			readfile($cache_full_path);			
		} else if($file_type=='png' || $file_type == 'image/png') {
			imagepng($cropped_image_gd,$cache_full_path);
			header ('Content-length: ' .filesize($cache_full_path));
			header ("Content-type: image/png");
			readfile($cache_full_path);		
		}
	
	}

}
?>

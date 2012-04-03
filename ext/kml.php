<?php
header("Access-Control-Allow-Origin: *");

require_once("../include/config.php");
require_once("../include/db_open.php");
require_once("../include/db_open_funzioni.php");
require_once("../include/funzioni.php");
require_once("../include/controlli.php");
require_once("../include/SimpleImage.php");
require_once('../include/decorourbano.php');

$user=(isset($_GET['idu']))?(int) $_GET['idu']:0;
$recenti=(isset($_GET['t_new']))?$_GET['t_new']:0;
$nuove=(isset($_GET['t_newer']))?(int) $_GET['t_newer']:0;
$vecchie=(isset($_GET['t_old']))?(int) $_GET['t_old']:0;
$limit_numero=(isset($_GET['l']))?(int) $_GET['l']:0;
$distanza=(isset($_GET['d']))?(float) $_GET['d']:0;
$commenti=(isset($_GET['c']))?$_GET['c']:0;
$comune=(isset($_GET['comune']))?cleanField($_GET['comune']):'';


if (isset($_GET['minLat']) && isset($_GET['maxLat']) && isset($_GET['minLng']) && isset($_GET['maxLng'])) {
	$area['minLat']=(float) $_GET['minLat'];
	$area['maxLat']=(float) $_GET['maxLat'];
	$area['minLng']=(float) $_GET['minLng'];
	$area['maxLng']=(float) $_GET['maxLng'];
} else {
	$area = array();
}

if ($comune!='') {
	$res_comune = data_get('tab_comuni',array('nome_url'=>$comune));
	if ($res_comune) {
		$parametri['id_comune'] = $res_comune[0]['id_comune'];
	}
} else {
	$limit_numero = 100;
}

$parametri['user'] = $user;
$parametri['recenti'] = $recenti;
$parametri['nuove'] = $nuove;
$parametri['vecchie'] = $vecchie;
$parametri['limit'] = $limit_numero;
$parametri['distanza'] = $distanza;
$parametri['commenti'] = $commenti;
$parametri['area'] = $area;

//$segnalazioni = json_decode(segnalazioni_get($parametri),TRUE);
$segnalazioni = segnalazioni_get($parametri);


if ($_SERVER["HTTPS"] == "on") {
	$pageURL = "https://";
} else {
	$pageURL = "http://";
}

if ($_SERVER["SERVER_PORT"] != "80") {
 	$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
} else {
	$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
}


$xml_out="";

header('Content-Type: application/rss+xml; charset=UTF-8', true);

echo '<?xml version="1.0" encoding="UTF-8" ?>'; ?>

<kml xmlns="http://earth.google.com/kml/2.0">
<Document>
  <name>Decoro Urbano</name>
  <description>Lista segnalazioni per Google Earth</description>
  <LookAt>
     <longitude>12.24262835092209</longitude>
     <latitude>41.777746133935025</latitude>
     <altitude>155000,00</altitude>
     <range>1542000.676045224589</range>
     <tilt>0.0</tilt>
     <heading>00.0</heading>
  </LookAt>
  <Style id="rifiuti">
      <IconStyle>
        <Icon>
          <href><?php echo $settings['sito']['url_ns'] ?>/images/marker_rifiuti.png</href>
        </Icon>
      </IconStyle>
    </Style>
    <Style id="affissioniAbusive">
      <IconStyle>
        <Icon>
          <href><?php echo $settings['sito']['url_ns'] ?>/images/marker_affissioniAbusive.png</href>
        </Icon>
      </IconStyle>
    </Style>
    <Style id="vandalismo">
      <IconStyle>
        <Icon>
          <href><?php echo $settings['sito']['url_ns'] ?>/images/marker_vandalismo.png</href>
        </Icon>
      </IconStyle>
    </Style>
    <Style id="zoneVerdi">
      <IconStyle>
        <Icon>
          <href><?php echo $settings['sito']['url_ns'] ?>/images/marker_degradoZoneVerdi.png</href>
        </Icon>
      </IconStyle>
    </Style>
    <Style id="dissestoStradale">
      <IconStyle>
        <Icon>
          <href><?php echo $settings['sito']['url_ns'] ?>/images/marker_sosBuche.png</href>
        </Icon>
      </IconStyle>
    </Style>
     <Style id="segnaletica">
      <IconStyle>
        <Icon>
          <href><?php echo $settings['sito']['url_ns'] ?>/images/marker_segnaleticaStradale.png</href>
        </Icon>
      </IconStyle>
    </Style>
  

<?php 
if (count($segnalazioni)) {

	foreach ($segnalazioni as $segnalazione) {
		if ($segnalazione['stato']>=300) {
			$stato = "Risolta";
		} else if ($segnalazione['stato']>=200) {
			$stato = "In carico";
		} else if ($segnalazione['stato']>=100) {
			$stato = "In attesa";
		}
	
		$url_segnalazione = $settings['sito']['url'].fixForUri($segnalazione['tipo_nome']).'/'.fixForUri($segnalazione['citta']).'/'.fixForUri($segnalazione['indirizzo']).'/'.$segnalazione['id_segnalazione'].'/';
		$title = "Segnalazione di ".$segnalazione['tipo_nome']." a ".$segnalazione['citta']." in ".$segnalazione['indirizzo'];
		$url_immagine = $settings['sito']['url_ns'].$segnalazione['foto_base_url'].'0-0.jpg';
		$image_filesize = filesize($settings['sito']['percorso'].'images/segnalazioni/'.$segnalazione['id_utente'].'/'.$segnalazione['id_segnalazione'].'/1.jpeg');
		$marker = $settings['sito']['url_ns'].$segnalazione['marker'];
?>

  <Placemark>
   <name><?php //echo $segnalazione['tipo_nome'] ?></name>
   <styleUrl><?php 
     if ($segnalazione['id_tipo'] === '1') echo '#rifiuti';
     if ($segnalazione['id_tipo'] === '2') echo '#vandalismo'; 
     if ($segnalazione['id_tipo'] === '3') echo '#dissestoStradale';
     if ($segnalazione['id_tipo'] === '4') echo '#zoneVerdi';
     if ($segnalazione['id_tipo'] === '5') echo '#segnaletica';
     if ($segnalazione['id_tipo'] === '6') echo '#affissioniAbusive';
   ?></styleUrl>
   <description>
    <![CDATA[<div style="width:500px;"><div style="widht:100%;"><b><?php echo $segnalazione['tipo_nome'] ?></b></div><div style="width:48%; float:left;"><img src="<?php echo $url_immagine?>" style="width:100%;" /></div>
    <div style="width:50%; float:right;"><?php echo $segnalazione['messaggio']?></div></div>]]></description>
   <Point><coordinates><?php echo $segnalazione['lng']." ".$segnalazione['lat'] ?></coordinates></Point>
  </Placemark>
<?php 

		//<content:encoded><![CDATA[<?php // immagine encondata ? >]]></content:encoded>
		//<wfw:commentRss><?php // eventuale rss dei commenti ? ></wfw:commentRss>
		//<slash:comments><?php // numero commenti ? ></slash:comments>
		//<?php //rss_enclosure(); ? >
		//<?php //do_action('rss2_item'); ? >
?>
		
<?php 
	}
}
?>
</Document>
</kml>
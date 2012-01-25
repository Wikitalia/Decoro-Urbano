<?php

require_once("../include/config.php");
require_once("../include/db_open.php");
require_once("../include/db_open_funzioni.php");
require_once("../include/decorourbano.php");
require_once("../include/funzioni.php");
require_once("../include/controlli.php");

$term = cleanField($_GET['term']);

$return_array = array();

if (strlen($term)>=2) {
	$comuni = comuni_get($term);
	
	foreach ($comuni as $comune) {
		$comune['value'] = $comune['nome'];
		array_push($return_array,$comune);
	}
	echo json_encode ($return_array);
}
?>
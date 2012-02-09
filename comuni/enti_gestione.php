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
 * Elenco delle segnalazioni
 */

ini_set("session.cookie_domain", ".".$settings['sito']['dominio']);
session_start();

require_once('../include/config.php');

if ($settings['sito']['debug']) {
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
} else {
	ini_set('display_errors', 0);
	error_reporting(0);
}

require_once('../include/db_open.php');
require_once('../include/db_open_funzioni.php');
require_once('../include/funzioni.php');
require_once('../include/controlli.php');
require_once('../include/decorourbano.php');

require_once('auth.php');


if (isset($_POST["salva_enti"])) {

	foreach ($_POST as $nome_campo => $campo) {
		
		$temp = explode('_', $nome_campo);
		$id_ente = $temp[2];
		
		if ($temp[0] == 'nome' && $temp['1'] == 'ente' && $temp['2']) {
			$campi_db['id_comune'] = $user['id_comune'];
			$campi_db['id_tipo'] = $id_ente;
			$campi_db['nome'] = $_POST['nome_ente_'.$id_ente];
			$campi_db['email'] = $_POST['email_ente_'.$id_ente];
			$campi_db['inoltro_attivo'] = (isset($_POST['inoltro_ente_'.$id_ente]))?1:0;
			
			$campi_db = cleanArray($campi_db);

			$condizioni = array('id_comune' => $campi_db['id_comune'], 'id_tipo' => $campi_db['id_tipo']);
			
			/*$ente = data_get('tab_enti', $condizioni);
			
			if (count($ente)) {
				data_update('tab_enti', array('eliminato' => 1), $condizioni);
			}*/
			
			data_update('tab_enti', array('eliminato' => 1), $condizioni);
			data_insert('tab_enti', $campi_db);
			
		}
	
	}

}


$id_menu_principale = "30";
$id_menu_secondario = "31";

//$enti_comune = data_query('SELECT tt.nome as nome_tipo, te.nome, te.email, tt.id_tipo, te.inoltro_attivo FROM tab_tipi tt left join tab_enti te on tt.id_tipo = te.id_tipo and id_comune = '.$user['id_comune']);
$enti_comune = enti_get($user['id_comune']);
$comune = data_get('tab_comuni',array('id_comune'=>$user['id_comune']));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<meta name="robots" content="noindex,nofollow" />
			<title><?= $settings['admin_comuni']['nome_sito'] ?></title>          




			 
<? require_once($settings['admin_comuni']['percorso'].'head_tag.php') ?>			 






</head>
<body>

<!--==================== Inizio Header =======================-->
<div id="header_bg">

	<div class="wrapper">
        
<? require_once($settings['admin_comuni']['percorso'].'header_bar.php') ?>	
<? require_once($settings['admin_comuni']['percorso'].'header_area.php') ?>
<? require_once($settings['admin_comuni']['percorso'].'header_menu.php') ?>      
          
  </div>

</div>
<!--==================== Fine Header =======================-->



<!--============================ Template Content Background ============================-->
<div id="content_bg" class="clearfix">
<!--============================ Main Content Area ============================-->
<div class="content wrapper clearfix">

	<!--============================Main Column============================-->
	<div class="main_column">
  <!-- inizio contenuto principale -->
  





		<!--=========Tables Box=========-->
		<div class="box">
			<div class="header" >

				<img src="images/icona_pagina_grande.png" style="vertical-align:text-bottom;" />Gestione enti/uffici
			</div>
			<div class="body">
				<div class="panes">
					<!-- Any div under the class of "panes" will associate itself in the same order as the tabs defined above under "sub_nav"-->
					<!-- Pane 1 -->
					<div class="clearfix">

						<form method="POST">
						
							<table>

								<thead>
									<tr style="background-color:#F2F2F2;">
										<th>Categoria</th>
										<th>Nome ente/ufficio</th>
										<th>Email ente/ufficio</th>
										<th>Inoltro attivo</th>
									</tr>
								</thead>
								
								<!--tfoot>
									<tr style="background-color:#F2F2F2;">
										<th>Categoria</th>
										<th>Nome ente/ufficio</th>
										<th>Email ente/ufficio</th>
										<th>Inoltro attivo</th>
									</tr>
								</tfoot-->
	
								<?
								foreach ($enti_comune as $ente) {
								?>
								
								<tr>
								
								<td><?=$ente['nome_tipo']?></td>
								<td><input type="text" name="nome_ente_<?=$ente['id_tipo']?>" value="<?=$ente['nome']?>"></td>
								<td><input type="text" name="email_ente_<?=$ente['id_tipo']?>" value="<?=$ente['email']?>"></td>
								<td><input type="checkbox" name="inoltro_ente_<?=$ente['id_tipo']?>"<?=($ente['inoltro_attivo'])?' checked':'';?>></td>
								
								</tr>
								
								<?
								}
								?>
							
							</table>
							
							<input type="submit" name="salva_enti" value="Salva">
						
						</form>


					</div>
					
					
				</div>
			</div>
		</div>
		<!--End Tables Box-->







  
  

  
  
  
  
  

  <!-- fine contenuto principale -->
	</div>
	<!--End Main Column-->
</div>
<!--End main content area-->
</div>
<!--End Template Content bacground-->




<? require_once($settings['admin_comuni']['percorso'].'footer.php') ?>



</body>
<!--End Body-->
</html>
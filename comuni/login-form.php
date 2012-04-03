<?php

/*
 * ----------------------------------------------------------------------------
 * Decoro Urbano version 0.4.0
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
 * Pagina contenente il form di login
 */

session_start();
require_once('../include/config.php');

if ($settings['sito']['debug']) {
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
} else {
	ini_set('display_errors', 0);
	error_reporting(0);
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<!--============================Head============================-->
<head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<meta name="robots" content="noindex,nofollow" />
			
<!--=========Title=========-->
            <title><?= $settings['admin_comuni']['nome_sito'] ?></title>
            
<? require_once($settings['admin_comuni']['percorso'].'head_tag.php') ?>	

</head>


<!--============================Begin Body============================-->
<body id="login_page">

<!--Wrapper of 450px-->
<div class="wrapper content">

  <div class="box">
    <!--Begin Login Header-->
    <div class="header">
      <p><img src="images/half_width_icon.png" alt="<?= ADMIN_NOME_SITO ?>" width="30" height="30" /><?= $settings['admin_comuni']['nome_sito'] ?></p>
    </div>
    <!--End Login Header-->
    <div class="body">
      <form  action="login-exec.php" method="post">
        <p>
          <label>Nome Utente:</label>
          <input name="email" type="text" class="textfield large" id="login" value=""/>
        </p>
        <p>
          <label>Password:</label>
          <input name="password" type="password" class="textfield large" id="password"  value=""/>
        </p>
        <p>
          <input name="login_form" type="submit" class="button2" value="Accedi" />
          <input type="checkbox" name="restaCollegato" id="ricorda"<? if (isset($_COOKIE['admin_user'])) echo ' checked="checked"' ?>/> 
          Ricorda
        </p>
      </form>
    </div>
  </div>
  
<? if (isset($_GET["e"])) {?>
  <div class="error">
    <strong><img src="images/error_icon.png" alt="Errore" width="28" height="29" class="icon" /></strong>Dati inseriti non corretti!<a href="#" class="close_notification" title="Chiudi"><img src="images/close_icon.gif" width="6" height="6" alt="Chiudi" /></a>
  </div>
<? } else { ?>  
  <div class="info">
    <strong><img src="images/iinfo_icon.png" alt="Informazione" width="28" height="29" class="icon" /></strong>Inserire nome utente password per l'utente amministratore.<a href="#" class="close_notification" title="Chiudi"><img src="images/close_icon.gif" width="6" height="6" alt="Chiudi" /></a>
  </div>
<? }?>  
</div>
<!--End Wrapper-->
</body>
<!--End Body-->
</html>
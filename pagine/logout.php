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

/**
 * Questa pagina effettua il logout dell'utente
 */

$params['next'] = $settings['sito']['url'];

// in caso di utente FB deve essere invocato il logout da Facebook
if(isset($_SESSION['fb_session']) && $_SESSION['fb_session']) {
	Auth::user_logout();
	try {
	  require_once($settings['sito']['percorso'] . 'include/facebook_3.1.1/facebook.php');
	  // Create Application instance.
	  $facebook = new Facebook(array(
			'appId' => $settings['facebook']['app_id'],
			'secret' => $settings['facebook']['app_secret'],
			'cookie' => true
		));
		$facebook->logout($params);
	} catch (Exception $e) {
		header('Location: ' . $params['next']);
		exit;
	}
} else {
	Auth::user_logout();
	header('Location: ' . $params['next']);
	exit;
}

?>

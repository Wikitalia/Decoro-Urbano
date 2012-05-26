<?php

require_once('controlli.php');

class Auth {

	private static $user = null;
	private static $fb_user = null;
	private static $user_eliminato = false;
	private static $cookie = null;

	public static function init() {

		if ($user = Auth::check_session()) {
			Auth::user_update($user);
			return;
		} else {
			Auth::clear_session();
		}
		
		if($user = Auth::check_post()) {
			Auth::user_update($user);
			return;
		}
		
		if($user = Auth::check_cookie()) {
			Auth::user_update($user);
			return;
		} else {
			Auth::clear_cookie();
		}
		
		if($user = Auth::check_session_fb()) {
			Auth::user_set_session_fb(Auth::$fb_user);
			Auth::user_update($user);
			return;
		}
		

	}
	
	public static function check_session() {
		if (isset($_SESSION['user'])
			&& isset($_SESSION['user']['id_utente'])
			&& trim($_SESSION['user']['id_utente']) != ''
			&& ($temp_user = user_get($_SESSION['user']['id_utente'])))
		{
			if (isset($_SESSION['fb_user']) && isset($_SESSION['fb_user']['id']) && $temp_user['id_fb'] == $_SESSION['fb_user']['id']) {
				Auth::$fb_user = $_SESSION['fb_user'];
			}
			return $temp_user;
			
		} else {
			return null;
		}
	}
	
	public static function check_session_fb() {

		$fb_user = Auth::get_session_fb();

    if ($fb_user) { // L'utente è loggato su FB ed ha autorizzato la nostra app.
			if (!($user_data_with_fb_id = user_fb_get_from_db($fb_user['id']))) {
				// L'utente non è ancora presente nel DB, lo inserisco
				user_fb_insert($fb_user);
				$user_data_with_fb_id = user_fb_get_from_db($fb_user['id']);
				Auth::$fb_user = $fb_user;
				return user_get($user_data_with_fb_id['id_utente']);
			} else {
				if (!$user_data_with_fb_id['eliminato']) {
					Auth::$fb_user = $fb_user;
					return user_get($user_data_with_fb_id['id_utente']);
				} else {
					Auth::$user_eliminato = true;
					return null;
				}
			}
    } else {
			return null;
		}

	}
	
	public static function check_post() {
    if (isset($_POST['login_form'])
			&& isset($_POST['email'])
			&& isset($_POST['password'])
			&& ($temp_user = Auth::check_credentials($_POST['email'],$_POST['password'])))
		{

			$email = $_POST['email'];
			$password = $_POST['password'];
			$setcookie = (isset($_POST['restaCollegato']) && $_POST['restaCollegato'] == 'on') ? 1 : 0;

			if ($setcookie) Auth::user_set_cookie($email, $password);
			return $temp_user;

    } else return null;
	}
	
	public static function check_cookie() {

		if (($cookie = cookie_data_get())
		&& ($temp_user = Auth::check_credentials(cleanField($cookie['user_email']),cleanField($cookie['user_password'])))) {

			Auth::$cookie = $cookie;

			$email = cleanField($cookie['user_email']);
			$password = cleanField($cookie['user_password']);

			Auth::user_set_cookie($email, $password);
			return $temp_user;

		} else return null;
	}
	
	public static function check_credentials($email,$password) {

		$user_data_with_userpass = data_get('tab_utenti', array('email' => $email, 'password' => sha1($password), 'confermato' => 1));
		
		if (count($user_data_with_userpass)) {
			
      if (!$user_data_with_userpass[0]['eliminato']) {
				return user_get($user_data_with_userpass[0]['id_utente']);
      } else {
      	Auth::$user_eliminato = true;
        return null;
      }
		} else {
			return null;
		}

	}

	public static function get_session_fb() {
		$fb_user = user_fb_get();
		return $fb_user;
	}
	
	public static function user_update($user) {
		Auth::user_set_session($user);
		Auth::$user = $user;
	}
	
	public static function user_set_session($user) {
		$_SESSION['user'] = $user;
	}
	
	public static function user_set_session_fb($fb_user) {
		$_SESSION['fb_user'] = $fb_user;
		$_SESSION['fb_session'] = $fb_user['id'];
		$_SESSION['fb_access_token'] = user_fb_access_token();
	}
	
	public static function user_set_cookie($email, $password) {
		global $settings;
		setcookie("user_email", $email, time() + 60 * 60 * 24 * 100, "/", '.'.$settings['sito']['dominio']);
		setcookie("user_password", base64_encode($password), time() + 60 * 60 * 24 * 100, "/", '.'.$settings['sito']['dominio']);
	}
	
	public static function clear_session() {
		session_unset();
	}
	
	public static function clear_cookie() {
		global $settings;
    setcookie("user_email", FALSE, time() - 60 * 60 * 24, "/", '.'.$settings['sito']['dominio']);
    setcookie("user_password", FALSE, time() - 60 * 60 * 24, "/", '.'.$settings['sito']['dominio']);
	}

	public static function user_logout() {
		Auth::clear_session();
		Auth::clear_cookie();
		Auth::$user = null;
		Auth::$fb_user = null;
	}

	public static function user_get() {
		return Auth::$user;
	}
	
	public static function user_fb_get() {
		return Auth::$fb_user;
	}

	public static function user_is_eliminato() {
		return Auth::$user_eliminato;
	}
	
	public static function cookie_get() {
		return Auth::$cookie;
	}

}

?>

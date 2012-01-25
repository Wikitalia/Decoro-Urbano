<?php

// Post dalla pagina Modifica profilo
if (isset($_POST['form_registrazione'])) {

	$errore_rigistrazione = 0;

	//$id_utente=$user['id_utente'];
	$fields['nome']=$_POST['regNome'];
	$fields['cognome']=$_POST['regCognome'];
	$fields['mostra_cognome']=(isset($_POST['regCognomeNascosto']) && $_POST['regCognomeNascosto'] == 'on')?0:1;
	
	$fields['nome_associazione']=(isset($_POST['regAssociazione']) && $_POST['regAssociazione'] == 'on' && $_POST['regNomeAssociazione'] != '')?$_POST['regNomeAssociazione']:'';
	
	$fields['email']=$_POST['regEmail'];
	//$fields['conferma_email']=$_POST['regConfermaEmail'];
	$fields['password']=sha1($_POST['regPassword']);
	//$fields['conferma_pass']=sha1($_POST['regConfermaPassword']);
	$fields['id_ruolo']=2;
	$fields['data']=time();
	
	$fields = cleanArray($fields);

	if ($fields['nome']=='') {
		$campi['regNome']['errore']='Campo nome necessario';
		$errore_rigistrazione = 1;
	}
	
	if ($fields['cognome'] == '') {
		$campi['regCognome']['errore']='Campo cognome necessario';
		$errore_rigistrazione = 1;
	}
	
	if (!checkEmailField($fields['email'])) {
		$campi['regEmail']['errore']='Campo email non valido';
		$errore_rigistrazione = 1;
	} else {
		if ($fields['email'] != $_POST['regConfermaEmail']) {
			$campi['regConfermaEmail']['errore']='Le email non corrispondono';
			$errore_rigistrazione = 1;
		}
	}
	
	if (strlen($_POST['regPassword']) < 6) {
		$campi['regPassword']['errore']='Password troppo corta';
		$errore_rigistrazione = 1;
	}

	$user_present=count(data_get('tab_utenti',array('email'=>$fields['email'])));
	if ($user_present) {
		$campi['regEmail']['errore']='Utente già registrato con questo indirizzo email';
		$campi['regConfermaEmail']['errore']='Utente già registrato con questo indirizzo email';
		$errore_rigistrazione = 1;
	}

	if (!$errore_rigistrazione) {
		$fields = cleanArray($fields);
		$id_utente = data_insert('tab_utenti', $fields);
		
		$key=$settings['sito']['encrypt_key'];
		$salt=$settings['sito']['hashsalt'];

		$email_code = code_encrypt($salt.$id_utente, $key);
		
		$from = $settings['email']['nome'].' <'.$settings['email']['indirizzo'].'>';
		$to=$fields['nome'].' '.$fields['cognome'].' <'.$fields['email'].'>';
		$link=$settings['sito']['confermaRegistrazione']."?s=".$email_code;
		
		//echo "Attenzione, su bischerone non funziona l'invio email. Questo è il link per confermare la registrazione.<br />";
		//echo $message;
		//exit;
		//html_email($from, $to, $subject, $message);

		$data['from'] = $from;
		$data['to'] = $to;
		$data['template'] = 'registrazioneConferma';
		$variabili['nome_utente'] = trim($fields['nome'].' '.$fields['cognome']);
		$variabili['link_registrazione'] = $link;
		$data['variabili'] = $variabili;
		email_with_template($data);

		$smarty->assign('email_inviata', 1);
		$smarty->assign('nome_segnalatore', $fields['nome']);
		
		//header('Location: index.html');
	} else {
		foreach ($_POST as $key => $campo) {
			$campi[$key]['value'] = $campo;
		}
		$smarty->assign('campi', $campi);
	}


}

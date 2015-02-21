<?php
function number($float) {
	if($float === null) {
		return '-';
	}
	return number_format($float, 2, '.', ' ');
}

function verify_login($url = null){
	global $settings;
	if(empty($_SESSION['login'])) {
		if(isset($_SERVER['HTTP_METHOD']) && $_SERVER['HTTP_METHOD'] == 'POST') {
			$_SESSION['_POST'] = $_POST;
		}
		if($settings['primary_login_method'] == "nxauth") {
			kick('Session/nx_login?kickback='.htmlspecialchars($url));
		} else {
			Message::add_error('Du har blivit utloggad, logga in igen för att återgå.');
			kick('Session/login?kickback='.htmlspecialchars($url));
		}
	}
}

function get_rand() {
	$random = rand();
	if(isset($_SESSION['random']) && $random == $_SESSION['random']) {
		$random++;
	}
	return $random;
}

function mark($bool) {
	if($bool) {
		return 'marked';
	} else {
		return '';
	}
}

function old_value($name, $values, $index=null) {
	if(!isset($values) || $values === false || !array_key_exists($name, $values)) {
		return null;
	}
	if($index !== null) {
		return $values[$name][$index];
	}
	return $values[$name];
}
?>

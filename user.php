<?php

require 'includes.php';
define('HTML_ACCESS', false);
ob_implicit_flush();

function getstring($str){
	echo $str;
	ob_flush();
	return substr(fgets(STDIN),0,-1);
}

function getpass($str){
	echo $str;
	ob_flush();
	system('stty -echo');
	$passwd = substr(fgets(STDIN),0,-1);
	system('stty echo');
	echo "\n";
	return $passwd;
}

if ( count($argv) >= 2 ){
	$user = User::from_username($argv[1]);
	$salt = '$5$rounds=5000$'.$argv[1];
	if ( !$user ){
		echo "Adding new user {$argv[1]}\n";
		$user = new User;
		$user->username = $argv[1];
		$user->first_name = getstring("First name: ");
		$user->surname = getstring("Surname: ");

		$passwd1 = getpass("Enter new password: ");
		$passwd2 = getpass("Retype new password: ");
		if ( $passwd1 != $passwd2 ){
			echo "Passwords does not match, user not added\n";
			exit(1);
		}

		$user->password = crypt($passwd1, $salt);
		$user->commit();
	} else {
		echo "Changing password for {$user->username}.\n";
		$passwd1 = getpass("Enter new password: ");
		$passwd2 = getpass("Retype new password: ");
		if ( $passwd1 != $passwd2 ){
			echo "Passwords does not match, password remain unchanged\n";
			exit(1);
		}
		$user->password = crypt($passwd1, $salt);
		$user->commit();
	}
} else {
	echo "Usage: user.php [username]\n\n";
	echo "Available users:\n";
	foreach ( User::selection() as $user ){
		echo ' * ' . $user . "\n";
	}
}
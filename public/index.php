<?php
require "../includes.php";

// Prepare path
$path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
$untouched_request=$path_info;
$request=explode('/',$path_info);
array_shift($request);
$main=array_shift($request);
if($main == '') {
	if($settings['allow_anonymous_shopping'] === true) {
		$main = "Retail";
	} else {
		$main = "Product";
	}
}
$page = $main.'C';
if(!class_exists($page)) {
	die("Controller $main does not exist");
}
$page::_declare($main.'/'.array_shift($request), $request);
?>

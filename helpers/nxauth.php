<?php
if(empty($global_root)) {
	throw new Exception("\$global_root needs to be set");
}
require_once $global_root."/vendor/nitroxy/nxauth/include.php";
NXAuth::init($settings['cas_config']);

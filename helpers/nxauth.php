<?php
if(empty($global_root)) {
	throw new Exception("\$global_root needs to be set");
}

if($settings['primary_login_method'] == "nxauth") {
	NXAuth::init($settings['cas_config']);
}

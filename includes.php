<?php
$global_root = dirname(__FILE__);

if ( !file_exists("{$global_root}/vendor") ){
	die("composer vendor folder missing, run 'composer install'");
}

// Get settings
require_once "settings.php";

// Init
require_once "inc_app/init.php";

// Generic
require_once "inc_gen/init.php";
require_once "inc_gen/session.php";
require_once "inc_gen/url.php";

// Application
require_once "inc_app/application.php";
require_once "inc_app/helpers.php";
require_once "inc_app/open_box.php";

require_once "helpers/nxauth.php";

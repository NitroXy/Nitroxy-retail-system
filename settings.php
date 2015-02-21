<?php
/**
 * Innehåller inställningar för sajten.
 */

// **** DEBUG ****
$settings['debug']=true;
$debug=$settings['debug'];

// **** NXAUTH = Settings for CAS via NitroXy.com ****
$settings['cas_config'] = [
	'site' => "nitroxy.com",
	'port' => 443,
	'key_id' => "nitroxy-retail-system", /* The id for the local site, used together with the private key for extra data */
	'private_key' => "$global_root/nxauth_private_key",
	'ca_cert' => "$global_root/vendor/nitroxy/nxauth/certs/GeoTrustGlobalCA.pem", /* If this is null no cert validation will be done */
];

// **** OTHER STUFF ****
$webpage_stage = "nitroxy_retail";

$settings['coupon_url'] = null;

$settings['open_box_parport_socket_path'] = "/home/nx-kiosk/parport_server/parserver.sock";

$settings['allow_anonymous_shopping'] = true;
$settings['primary_login_method'] = "local"; // Change to "nxauth" if you want things to work

// **** INCLUDE LOCAL SETTINGS ****
$local_file = "$global_root/settings.local.php";
if(file_exists($local_file)) {
	require $local_file;
}

<?php
function open_box() {
	global $settings;
	$sock = socket_create(AF_UNIX, SOCK_STREAM, 0);
	socket_connect($sock, $settings['open_box_parport_socket_path']);
	socket_write($sock, "strobe d0 500");
	socket_close($sock);
}

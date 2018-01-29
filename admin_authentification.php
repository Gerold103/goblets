<?php
	require_once('credentials.php');

	$real_login = $_SERVER['PHP_AUTH_USER'];
	$real_password = $_SERVER['PHP_AUTH_PW'];
	if ($real_login != $admin_login || $real_password != $admin_password) {
		header('WWW-Authenticate: Basic realm="My Realm"');
		header('HTTP/1.0 401 Unauthorized');
		exit();
	}
?>
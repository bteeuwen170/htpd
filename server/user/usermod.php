<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	verify_login(GID_ADMIN);

	echo("Verbinding maken met SQL database... ");
	$dbconn = new mysqli(DB_URL . ":" . DB_PORT, DB_USER, DB_PASS, DB_NAME);
	check($dbconn, !$dbconn->connect_error);

	$uid = $dbconn->real_escape_string($_POST["uid"]);
	$name = $dbconn->real_escape_string($_POST["name"]);
	$username = $dbconn->real_escape_string($_POST["username"]);

	check($dbconn, $uid != 1);

	/*echo("Controleren op gebruikersnaam beschikbaarheid... ");
	$usernamecheck = sprintf("SELECT username FROM %s WHERE username='%s'",
			DB_USERS, $username); //TODO Not working but handled nevertheless
	check($dbconn, ($dbconn->query($usernamecheck))->num_rows < 2);*/

	echo("Gebruiker wordt aangepast... ");
	$user = sprintf("UPDATE %s SET name='%s', username='%s' WHERE uid=%s",
			DB_USERS, $name, $username, $uid);
	check($dbconn, $dbconn->query($user));

	$dbconn->close();

	header("Location: " . $_SERVER["HTTP_REFERER"]);
?>

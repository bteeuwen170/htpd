<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	echo("Verbinding maken met SQL database... ");
	$dbconn = new mysqli(DB_URL . ":" . DB_PORT, DB_USER, DB_PASS, DB_NAME);
	check($dbconn, !$dbconn->connect_error);

	$uid = $dbconn->real_escape_string($_POST["uid"]);
	if (isset($_POST["name"])) {
		echo("UID controleren... ");
		verify_login(GID_ADMIN, isset($_POST["submit"]));
		check($dbconn, $uid != 1);

		$name = $dbconn->real_escape_string($_POST["name"]);
		$username = $dbconn->real_escape_string($_POST["username"]);

		echo("Gebruikersinformatie wordt gecontroleerd... ");
		check($dbconn, strlen($name) <= 64);

		echo("Gebruikersnaam wordt gecontroleerd... ");
		check($dbconn, strlen($username) <= 64);

		echo("Gebruiker wordt aangepast... ");
		$user = sprintf("UPDATE %s SET name='%s', username='%s' WHERE uid=%s",
				DB_USERS, $name, $username, $uid);
		check($dbconn, $dbconn->query($user));

		$dbconn->close();

		header("Location: " . $_SERVER["HTTP_REFERER"]);
	} else {
		echo("UID controleren... ");
		verify_login(GID_STUDENT, isset($_POST["submit"]));
		check($dbconn, $uid == $_COOKIE["uid"]);

		$passwordold = $dbconn->real_escape_string($_POST["passwordold"]);
		$password = $dbconn->real_escape_string($_POST["password"]);
		$passwordrep = $dbconn->real_escape_string($_POST["passwordrep"]);

		echo("Huidig wachtwoord wordt gecontroleerd... ");
		$qurows =
			sprintf("SELECT password FROM %s WHERE uid=%s", DB_USERS, $uid);
		$urows = $dbconn->query($qurows);
		$urow = $urows->fetch_array();
		check($dbconn, password_verify($passwordold, $urow["password"]));

		echo("Wachtwoord wordt gecontroleerd... ");
		$passwordlen = strlen($password);
		check($dbconn, $password == $passwordrep &&
				$passwordlen >= 8 && $passwordlen <= 255);

		echo("Wachtwoord wordt gehashed... ");
		$hash = password_hash($password, PASSWORD_BCRYPT);
		check($dbconn, is_string($hash));

		echo("Gebruiker wordt aangepast... ");
		$user = sprintf("UPDATE %s SET password='%s' WHERE uid=%s",
				DB_USERS, $hash, $uid);
		check($dbconn, $dbconn->query($user));

		$dbconn->close();

		echo("<script>window.parent.location = '/login.php'</script>");
			//XXX Logged out properly?
	}
?>

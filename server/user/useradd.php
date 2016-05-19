<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	if (!verify_login(USR_TEACHER))
		header("Location: /user/logout.php");

	echo("Verbinding maken met SQL database... ");
	$dbconn = new mysqli(DB_URL . ":" . DB_PORT, DB_USER, DB_PASS, DB_NAME);
	check($dbconn, !$dbconn->connect_error);

	$gid = $dbconn->real_escape_string($_POST["gid"]);
	$name = $dbconn->real_escape_string($_POST["firstname"]) . " " .
			$dbconn->real_escape_string($_POST["lastname"]);
	$username = $dbconn->real_escape_string($_POST["username"]);
	$password = $dbconn->real_escape_string($_POST["password"]);

	echo("Controleren op gebruikersnaam beschikbaarheid... ");
	$usernamecheck = sprintf("SELECT username FROM %s WHERE username='%s'",
			DB_USERS, $username);
	check($dbconn, !($dbconn->query($usernamecheck))->num_rows);

	echo("Wachtwoord wordt gehashed... ");
	$hash = password_hash($password, PASSWORD_BCRYPT);
	check($dbconn, is_string($hash));

	echo("Gebruiker wordt aangemaakt... ");
	$user = sprintf("INSERT INTO %s (gid, name, username, password)
			VALUES(%s, '%s', '%s', '%s')",
			DB_USERS, $gid, $name, $username, $hash);
	check($dbconn, $dbconn->query($user));

	if ($gid == USR_STUDENT) {
		echo("Persoonlijke map wordt aangemaakt... ");
		$columns = sprintf("SELECT uid FROM %s WHERE username='%s'",
				DB_USERS, $username);
		$result = $dbconn->query($columns);
		$row = $result->fetch_assoc();
		$path = URL_USERS . $row["uid"];
		mkdir($path, 0755);
		check($dbconn, file_exists($path));

		$result->free();
	}

	$dbconn->close();

	header("Location: " . $_SERVER["HTTP_REFERER"]);
?>

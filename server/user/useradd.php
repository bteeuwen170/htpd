<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	verify_login(GID_ADMIN);

	echo("Verbinding maken met SQL database... ");
	$dbconn = new mysqli(DB_URL . ":" . DB_PORT, DB_USER, DB_PASS, DB_NAME);
	check($dbconn, !$dbconn->connect_error);

	$gid = $dbconn->real_escape_string($_POST["gid"]);
	if (isset($_POST["firstname"]) && isset($_POST["lastname"]))
		$name = $dbconn->real_escape_string($_POST["firstname"]) . " " .
				$dbconn->real_escape_string($_POST["lastname"]);
	else (isset($_POST["name"])
		$name = $dbconn->real_escape_string($_POST["name"]);
	$username = $dbconn->real_escape_string($_POST["username"]);
	$password = $dbconn->real_escape_string($_POST["password"]);
	$passwordrep = $dbconn->real_escape_string($_POST["passwordrep"]);

	echo("Gebruikersinformatie wordt gecontroleerd... ");
	check($dbconn, strlen($name) <= 64);

	echo("Gebruikersnaam wordt gecontroleerd... ");
	check($dbconn, strlen($username) <= 64);

	echo("Wachtwoord wordt gecontroleerd... ");
	$passwordlen = strlen($password);
	check($dbconn, $password == $passwordrep &&
			$passwordlen >= 8 && $passwordlen <= 255);

	echo("Wachtwoord wordt gehashed... ");
	$hash = password_hash($password, PASSWORD_BCRYPT);
	check($dbconn, is_string($hash));

	echo("Gebruiker wordt aangemaakt... ");
	$user = sprintf("INSERT INTO %s (gid, name, username, password)
			VALUES(%s, '%s', '%s', '%s')",
			DB_USERS, $gid, $name, $username, $hash);
	check($dbconn, $dbconn->query($user));

	if ($gid == GID_STUDENT) {
		echo("Persoonlijke map wordt aangemaakt... ");
		$columns = sprintf("SELECT uid FROM %s WHERE username='%s'",
				DB_USERS, $username);
		$result = $dbconn->query($columns);
		$row = $result->fetch_assoc();
		$path = URL_USERS . $row["uid"];
		mkdir($path, 0755);
		check($dbconn, file_exists($path));

		$result->close();
	}

	$dbconn->close();

	header("Location: " . $_SERVER["HTTP_REFERER"]);
			//TODO Prevent error from import.php
?>

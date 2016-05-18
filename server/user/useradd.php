<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	if (!verify_login(USER_TEACHER))
		header("Location: /user/logout.php");

	echo("Verbinding maken met SQL database... ");
	$dbconn = new mysqli(DB_URL . ":" . DB_PORT, DB_USER, DB_PASS, DB_NAME);
	check($dbconn, !$dbconn->connect_error);

	$gid = $_POST["gid"];
	$firstname = $_POST["firstname"];
	$lastname = $_POST["lastname"];
	$username = $_POST["username"];
	$password = $_POST["password"];
	$email = $_POST["email"];

	echo("Controleren op gebruikersnaam beschikbaarheid... ");
	$usernamecheck = "SELECT username FROM " . DB_USERS . " WHERE username = '"
			. $username . "'";
	check($dbconn, mysqli_num_rows($dbconn->query($usernamecheck)) == 0);

	echo("Controleren op emailadres beschikbaarheid... ");
	$emailcheck = "SELECT email FROM " . DB_USERS . " WHERE email = '" .
			$email . "'";
	check($dbconn, mysqli_num_rows($dbconn->query($emailcheck)) == 0);

	echo("Wachtwoord wordt gehashed... ");
	$hash = password_hash($password, PASSWORD_BCRYPT);
	check($dbconn, is_string($hash));

	echo("Gebruiker wordt aangemaakt... ");
	$user = "INSERT INTO " . DB_USERS . "
			(gid, firstname, lastname, username, password, email)
			VALUES(" . $gid . ", '" . $firstname . "', '" . $lastname . "', '" .
			$username . "', '" . $hash . "', '" . $email . "')";
	check($dbconn, $dbconn->query($user));

	if ($gid == USER_STUDENT) {
		echo("Persoonlijke map wordt aangemaakt... ");
		$columns = "SELECT uid FROM " . DB_USERS . " WHERE username='" .
				$username . "'";
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

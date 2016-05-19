<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	if (file_exists(URL_STORAGE . "configured")) { //TODO Safe check?
		header("HTTP/1.0 403 Forbidden");
		die("De server is al geconfigureerd!");
	}

	if (!isset($_POST["username"])) {
		header("HTTP/1.0 403 Forbidden");
		die("De server is nog niet geconfigureerd!");
	}

	echo("Current working directory: " . getcwd() . "<br><br>");

	echo("Verbinding maken met SQL database... ");
	$dbconn = new mysqli(DB_URL . ":" . DB_PORT, DB_USER, DB_PASS, DB_NAME);
	check($dbconn, !$dbconn->connect_error);

	echo("Wachtwoord wordt gehashed... ");
	$hash = password_hash($_POST["password"], PASSWORD_BCRYPT);
	check($dbconn, is_string($hash));

	echo("Administrator account gegevens worden opgehaald... ");
	$astatus = "SELECT uid FROM " . DB_USERS . " WHERE gid='0'";
	if (!check($dbconn,
			mysqli_num_rows($dbconn->query($astatus)) > 0, true, true)) {
		echo("Administrator account wordt aangemaakt... ");
		$user = "INSERT INTO " . DB_USERS . "
				(gid, firstname, lastname, username, password, email)
				VALUES(0, '" . $_POST["firstname"] . "', '" .
				$_POST["lastname"] . "', '" . $_POST["username"] . "', '" .
				$hash . "', '" . $_POST["email"] . "')";
		check($dbconn, $dbconn->query($user));
	}

	echo("Tabel met projectengegevens wordt opgehaald... ");
	$tstatus = "SHOW TABLES LIKE '" . DB_PROJECTS ."'";
	if (!check($dbconn,
			mysqli_num_rows($dbconn->query($tstatus)) > 0, true, true)) {
		echo("Nieuwe tabel \"" . DB_NAME . "." . DB_PROJECTS .
				"\" wordt aangemaakt... ");
		$table = "
			CREATE TABLE " . DB_PROJECTS . " (
				pid         INT(64) UNSIGNED AUTO_INCREMENT NOT NULL,
				name        VARCHAR(64) UNIQUE NOT NULL,
				groups      TINYINT UNSIGNED NOT NULL,
				year        TINYINT UNSIGNED NOT NULL,
							PRIMARY KEY(pid)
			)
		"; //TODO Remove students
		check($dbconn, $dbconn->query($table));
	}

	echo("Tabel met project groepen wordt opgehaald... ");
	$tstatus = "SHOW TABLES LIKE '" . DB_GROUPS ."'";
	if (!check($dbconn,
			mysqli_num_rows($dbconn->query($tstatus)) > 0, true, true)) {
		echo("Nieuwe tabel \"" . DB_NAME . "." . DB_PROJECTS .
				"\" wordt aangemaakt... ");
		$table = "
			CREATE TABLE " . DB_PROJECTS . " (
				pid         INT(64) UNSIGNED NOT NULL,
				grp         TINYINT UNSIGNED NOT NULL,
				uid         INT(64) UNSIGNED NOT NULL
			)
		";
		check($dbconn, $dbconn->query($table));
	}

	echo("Opslagmap wordt aangemaakt... ");
	if (!check($dbconn, file_exists(URL_STORAGE), true)) {
		mkdir(URL_STORAGE, 0755);
		check($dbconn, file_exists(URL_STORAGE));
	}

	echo("Gebruikersmap wordt aangemaakt... ");
	$path = URL_USERS;
	if (!check($dbconn, file_exists($path), true)) {
		mkdir($path, 0755);
		check($dbconn, file_exists($path));
	}

	echo("Initiële server configuratie wordt voltooid... ");
	check($dbconn, touch(URL_STORAGE . "configured"));

	echo("De server is nu geconfigureerd!");

	echo("
		<p>
			<form action='/login.php'>
				<input type='submit' value='Oké'>
			</form>
		</p>");
?>

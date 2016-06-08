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

	echo("Verbinding maken met SQL database... ");
	$dbconn = new mysqli(DB_URL . ":" . DB_PORT, DB_USER, DB_PASS, DB_NAME);
	check($dbconn, !$dbconn->connect_error);

	$name = $dbconn->real_escape_string($_POST["firstname"]) . " " .
			$dbconn->real_escape_string($_POST["lastname"]);
	$username = $dbconn->real_escape_string($_POST["username"]);
	$password = $dbconn->real_escape_string($_POST["password"]);
	$passwordrep = $dbconn->real_escape_string($_POST["passwordrep"]);

	echo("Wachtwoord wordt gecontroleerd... ");
	check($dbconn, $password == $passwordrep);

	echo("Wachtwoord wordt gehashed... ");
	$hash = password_hash($password, PASSWORD_BCRYPT);
	check($dbconn, is_string($hash));

	echo("Administrator account gegevens worden opgehaald... ");
	$astatus = "SELECT uid FROM " . DB_USERS . " WHERE gid=0";
	if (check($dbconn, !($dbconn->query($astatus))->num_rows, true, true)) {
		echo("Administrator account wordt aangemaakt... ");
		$user = sprintf("INSERT INTO %s (gid, name, username, password)
				VALUES(0, '%s', '%s', '%s')",
				DB_USERS, $name, $username, $hash);
		check($dbconn, $dbconn->query($user));
	}

	echo("Tabel met projectengegevens wordt opgehaald... ");
	$tstatus = "SHOW TABLES LIKE '" . DB_PROJECTS ."'";
	if (check($dbconn, !($dbconn->query($tstatus))->num_rows, true, true)) {
		echo(sprintf("Nieuwe tabel '%s.%s' wordt aangemaakt... ",
				DB_NAME, DB_PROJECTS));
		$table = sprintf("
			CREATE TABLE %s (
				pid         INT(64) UNSIGNED AUTO_INCREMENT NOT NULL,
				name        VARCHAR(64) UNIQUE NOT NULL,
				groups      TINYINT UNSIGNED NOT NULL,
				year        TINYINT UNSIGNED NOT NULL,
							PRIMARY KEY(pid)
			)
		", DB_PROJECTS);
		check($dbconn, $dbconn->query($table));
	}

	echo("Tabel met project groepen wordt opgehaald... ");
	$tstatus = "SHOW TABLES LIKE '" . DB_GROUPS ."'";
	if (check($dbconn, !($dbconn->query($tstatus))->num_rows, true, true)) {
		echo(sprintf("Nieuwe tabel '%s.%s' wordt aangemaakt... ",
				DB_NAME, DB_GROUPS));
		$table = sprintf("
			CREATE TABLE %s (
				pid         INT(64) UNSIGNED NOT NULL,
				grp         TINYINT NOT NULL,
				uid         INT(64) UNSIGNED NOT NULL
			)
		", DB_GROUPS);
		check($dbconn, $dbconn->query($table));
	}

	echo("Opslagmap aanwezigheid wordt gecontroleerd... ");
	if (check($dbconn, !file_exists(URL_STORAGE), true, true)) {
		echo("Opslagmap wordt aangemaakt... ");
		mkdir(URL_STORAGE, 0755);
		check($dbconn, file_exists(URL_STORAGE));
	}

	echo("Gebruikersmap aanwezigheid wordt gecontroleerd... ");
	$path = URL_USERS;
	if (check($dbconn, !file_exists($path), true, true)) {
		echo("Gebruikersmap wordt aangemaakt... ");
		mkdir($path, 0755);
		check($dbconn, file_exists($path));
	}

	echo("Initiële server configuratie wordt voltooid... ");
	check($dbconn, touch(URL_STORAGE . "configured"));

	echo("De server is nu geconfigureerd!");

	echo("
		<p>
			<form action='/login.php'>
				<input type='submit' value='Voltooien'>
			</form>
		</p>");
?>

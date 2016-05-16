<!DOCTYPE html>

<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	if (file_exists(URL_STORAGE . "configured")) {
		header("HTTP/1.0 403 Forbidden");
		die("De server is al geconfigureerd!");
	}

	echo("Current working directory: " . getcwd() . "<br><br>");

	echo("Verbinding maken met SQL... ");
	$dbconn = new mysqli(DB_URL . ":" . DB_PORT, DB_USER, DB_PASS);
	check($dbconn, !$dbconn->connect_error);

	echo("Verbinding maken met SQL database... ");
	if (!check($dbconn, mysqli_select_db($dbconn, DB_NAME), true, true)) {
		echo("Nieuwe database \"" . DB_NAME . "\" wordt aangemaakt... ");
		$db = "CREATE DATABASE " . DB_NAME;
		check($dbconn, $dbconn->query($db));

		echo("Opnieuw verbinding maken met gebruikers database... ");
		check($dbconn, mysqli_select_db($dbconn, DB_NAME));
	}

	echo("Tabel met gebruikersgegevens wordt opgehaald... ");
	$tstatus = "SHOW TABLES LIKE '" . DB_USERS ."'";
	if (!check($dbconn,
			mysqli_num_rows($dbconn->query($tstatus)) > 0, true, true)) {
		echo("Nieuwe tabel \"" . DB_NAME . "." . DB_USERS .
				"\" wordt aangemaakt... ");
		$table = "
			CREATE TABLE " . DB_USERS . " (
				uid         INT(64) UNSIGNED AUTO_INCREMENT NOT NULL,
				gid         TINYINT UNSIGNED NOT NULL,
				firstname   VARCHAR(32) NOT NULL,
				lastname    VARCHAR(32) NOT NULL,
				username    VARCHAR(64) UNIQUE NOT NULL,
				password    VARCHAR(255) NOT NULL,
				email       VARCHAR(64) UNIQUE NOT NULL,
							PRIMARY KEY(uid)
			)
		";
		check($dbconn, $dbconn->query($table));
	}

	echo("
		<p>
			<form method='post' action='install.php'>
				<p>Voornaam: <input
					type='text'
					name='firstname'
					autofocus required>
				</p>
				<p>Achternaam: <input
					type='text'
					name='lastname'
					required>
				</p>
				<p>Gebruikersnaam: <input
					type='text'
					name='username'
					required>
				</p>
				<p>Wachtwoord: <input
					type='password'
					name='password'
					required>
				</p>
				<p>Emailadres: <input
					type='email'
					name='email'
					required>
				</p>
				<p><input
					type='submit'
					name='create'
					value='Configuratie voltooien'>
				</p>
			</form>
		</p>
	");
?>

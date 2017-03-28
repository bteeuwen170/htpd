<!DOCTYPE html>

<html style="font-family: monospace;">
	<?php
		include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

		echo("Welkom bij HTPD versie " . VERSION . "!<br>");

		if (file_exists(URL_STORAGE . "configured")) {
			header("HTTP/1.0 403 Forbidden");
			die("De server is al geconfigureerd.");
		}

		echo("Verbinding maken met SQL... ");
		$dbconn = new mysqli(DB_URL . ":" . DB_PORT, DB_USER, DB_PASS);
		check($dbconn, !$dbconn->connect_error);

		echo("Verbinding maken met de SQL database... ");
		if (check($dbconn, !$dbconn->select_db(DB_NAME), true, true)) {
			echo(sprintf("Nieuwe database '%s' wordt aangemaakt... ", DB_NAME));
			$qpdb = sprintf("CREATE DATABASE %s", DB_NAME);
			check($dbconn, $dbconn->query($qpdb));

			echo("Opnieuw verbinding maken met de SQL database... ");
			check($dbconn, $dbconn->select_db(DB_NAME));
		}

		echo("Tabel met gebruikersgegevens wordt opgehaald... ");
		$qutable = sprintf("DESCRIBE %s", DB_USERS);
		if (check($dbconn, !$dbconn->query($qutable), true, true)) {
			echo(sprintf("Nieuwe tabel '%s.%s' wordt aangemaakt... ",
					DB_NAME, DB_USERS));
			$qutable = sprintf("
				CREATE TABLE %s (
					uid         INT(64) UNSIGNED AUTO_INCREMENT NOT NULL,
					gid         TINYINT UNSIGNED NOT NULL,
					name        VARCHAR(64) NOT NULL,
					username    VARCHAR(64) UNIQUE NOT NULL,
					password    VARCHAR(255) NOT NULL,
								PRIMARY KEY(uid)
				)
			", DB_USERS);
			check($dbconn, $dbconn->query($qutable));
		}

		$dbconn->close();

		echo("
			<p>
				<form method='post' action='install.php'>
					<p>Voornaam: <input
						type='text'
						name='firstname'
						maxlength='32'
						autofocus required>
					</p>
					<p>Achternaam: <input
						type='text'
						name='lastname'
						maxlength='32' required>
					</p>
					<p>Gebruikersnaam: <input
						type='text'
						name='username'
						maxlength='64' required>
					</p>
					<p>Wachtwoord: <input
						type='password'
						name='password'
						pattern='.{8,255}'
						minlength='8'
						maxlength='255' required>
						<!-- Please support minlength -->
					</p>
					<p>Wachtwoord herhalen: <input
						type='password'
						name='passwordrep'
						required>
					</p>
					<p><input
						type='submit'
						name='submit'
						value='Installeren'>
					</p>
				</form>
			</p>
		");
	?>
</html>

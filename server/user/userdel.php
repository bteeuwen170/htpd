<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	verify_login(GID_ADMIN);

	echo("Verbinding maken met SQL database... ");
	$dbconn = new mysqli(DB_URL . ":" . DB_PORT, DB_USER, DB_PASS, DB_NAME);
	check($dbconn, !$dbconn->connect_error);

	$uids = $_POST["uids"];

	for ($i = 0; $i < count($uids); $i++) {
		check($dbconn, $uids[$i] != 1);

		echo("Gebruikers informatie wordt opgehaald... ");
		$columns =
			sprintf("SELECT gid FROM %s WHERE uid=%s", DB_USERS, $uids[$i]);
		$result = $dbconn->query($columns);
		check($dbconn, $result->num_rows);
		$row = $result->fetch_assoc();

		echo("Gebruiker wordt verwijderd... ");
		$user = sprintf("DELETE FROM %s WHERE uid=%s", DB_USERS, $uids[$i]);
		check($dbconn, $dbconn->query($user));

		echo("Gebruiker wordt uit projecten verwijderd... ");
		$user = sprintf("DELETE FROM %s WHERE uid=%s", DB_GROUPS, $uids[$i]);
		check($dbconn, $dbconn->query($user));

		if ($row["gid"] == 2) {
			echo("Persoonlijke bestanden worden verwijderd... ");
			$path = URL_USERS . $uids[$i];
			deldir($path);
			check($dbconn, !file_exists($path));
		}

		$result->close();
	}

	$dbconn->close();

	header("Location: " . $_SERVER["HTTP_REFERER"]);
?>

<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	verify_login(GID_TEACHER, isset($_POST["delete"]) || isset($_POST["save"]));

	if (isset($_POST["delete"])) {
		echo("Verbinding maken met SQL database... ");
		$dbconn = new mysqli(DB_URL . ":" . DB_PORT, DB_USER, DB_PASS, DB_NAME);
		check($dbconn, !$dbconn->connect_error);

		$pid = $dbconn->real_escape_string($_POST["pid"]);
		$uids = $_POST["uids"];

		for ($i = 0; $i < count($uids); $i++) {
			echo("Gebruiker wordt uit project verwijderd... ");
			$user = sprintf("DELETE FROM %s WHERE pid=%s AND uid=%s",
					DB_GROUPS, $pid, $dbconn->real_escape_string($uids[$i]));
			check($dbconn, $dbconn->query($user));
		}
	} else if (isset($_POST["save"])) {
		echo("Verbinding maken met SQL database... ");
		$dbconn = new mysqli(DB_URL . ":" . DB_PORT, DB_USER, DB_PASS, DB_NAME);
		check($dbconn, !$dbconn->connect_error);

		$pid = $dbconn->real_escape_string($_POST["pid"]);
		$uids = $_POST["students"];
		$grps = $_POST["groups"];

		for ($i = 0; $i < count($uids); $i++) {
			echo("Gebruiker wordt aan groep toegevoegd... ");
			$user = sprintf("UPDATE %s SET grp=%s WHERE pid=%s AND uid=%s",
					DB_GROUPS, $dbconn->real_escape_string($grps[$i]),
					$pid, $dbconn->real_escape_string($uids[$i]));
			check($dbconn, $dbconn->query($user));
		}
	}

	$dbconn->close();

	header("Location: " . $_SERVER["HTTP_REFERER"]);
?>

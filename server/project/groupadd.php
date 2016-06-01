<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	verify_login(GID_TEACHER);

	echo("Verbinding maken met SQL database... ");
	$dbconn = new mysqli(DB_URL . ":" . DB_PORT, DB_USER, DB_PASS, DB_NAME);
	check($dbconn, !$dbconn->connect_error);

	$pid = $dbconn->real_escape_string($_POST["pid"]);
	$uids = $_POST["cb"];

	for ($i = 0; $i < count($uids); $i++) {
		echo("Gebruiker wordt aan project toegevoegd... ");
		$user = sprintf("INSERT INTO %s (pid, grp, uid) VALUES(%s, -1, %s)",
				DB_GROUPS, $pid, $uids[$i]);
		check($dbconn, $dbconn->query($user));
	}

	$dbconn->close();

	header("Location: " . $_SERVER["HTTP_REFERER"]);
?>

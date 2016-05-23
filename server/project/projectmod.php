<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	if (!verify_login(GID_TEACHER))
		header("Location: /user/logout.php");

	echo("Verbinding maken met SQL database... ");
	$dbconn = new mysqli(DB_URL . ":" . DB_PORT, DB_USER, DB_PASS, DB_NAME);
	check($dbconn, !$dbconn->connect_error);

	$pid = $_POST["pid"];
	$name = $dbconn->real_escape_string($_POST["name"]);
	$groups = $_POST["groups"];
	$year = $_POST["year"];

	echo("Project wordt bewerkt... ");
	$project =
		sprintf("UPDATE %s SET name='%s', groups=%s, year=%s WHERE pid=%s",
			DB_PROJECTS, $name, $groups, $year, $pid);
	check($dbconn, $dbconn->query($project));

	$dbconn->close();

	header("Location: " . $_SERVER["HTTP_REFERER"]);
?>

<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	verify_login(GID_TEACHER);

	echo("Verbinding maken met SQL database... ");
	$dbconn = new mysqli(DB_URL . ":" . DB_PORT, DB_USER, DB_PASS, DB_NAME);
	check($dbconn, !$dbconn->connect_error);

	$name = $dbconn->real_escape_string($_POST["name"]);
	$groups = $dbconn->real_escape_string($_POST["groups"]);
	$year = $dbconn->real_escape_string($_POST["year"]);

	echo("Project wordt aangemaakt... ");
	$project = sprintf("INSERT INTO %s (name, groups, year)
		VALUES('%s', %s, %s)", DB_PROJECTS, $name, $groups, $year);
	check($dbconn, $dbconn->query($project));

	$dbconn->close();

	header("Location: " . $_SERVER["HTTP_REFERER"]);
?>

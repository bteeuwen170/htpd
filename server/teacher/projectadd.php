<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	if (!verify_login(USER_TEACHER))
		header("Location: /user/logout.php");

	echo("Verbinding maken met SQL database... ");
	$dbconn = new mysqli(DB_URL . ":" . DB_PORT, DB_USER, DB_PASS, DB_NAME);
	check($dbconn, !$dbconn->connect_error);

	$name = $_POST["name"];
	$groups = $_POST["groups"];
	$year = $_POST["year"];
	$active = $_POST["active"];

	echo("Project wordt aangemaakt... ");
	$project = "INSERT INTO " . DB_PROJECTS . " (name, groups, year, active)
			VALUES('" . $name . "', " . $groups . ", " . $year .
			", " . ($active ? "true" : "false") . ")";
	check($dbconn, $dbconn->query($project));

	$dbconn->close();

	header("Location: " . $_SERVER["HTTP_REFERER"]);
?>
